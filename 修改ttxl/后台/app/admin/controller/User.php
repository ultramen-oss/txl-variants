<?php

namespace app\admin\controller;

use think\facade\Db;
use think\facade\Session;
use think\facade\View;

class User
{
    /**
     * 用户列表
     * @return mixed
     */
    public function index()
    {
        $post = $this->request->param();
        
        // 获取当前管理员信息
        $admin_id = Session::get('admin');
        $admin = Db::name('admin')->where('id', $admin_id)->find();
        
        // 构建查询条件
        $where = [];
        
        // 如果是普通账号，只能查看自己邀请码注册的用户
        if ($admin && $admin['role_type'] == 'user') {
            if (empty($admin['invite_code'])) {
                // 如果没有邀请码，则不显示任何数据
                $where[] = ['id', '=', 0]; // 设置一个不可能满足的条件
            } else {
                $where[] = ['invite_code', '=', $admin['invite_code']];
            }
        } 
        // 如果是普通管理员，根据权限查看
        elseif ($admin && $admin['role_type'] == 'admin') {
            // 如果具有查看所有用户的权限
            if (isset($admin['view_all_invites']) && $admin['view_all_invites'] == 1) {
                // 不添加额外条件，可以查看所有用户
            } 
            // 否则只能查看分配的邀请码用户
            elseif (!empty($admin['assigned_invites'])) {
                $assignedInvites = explode(',', $admin['assigned_invites']);
                $where[] = ['invite_code', 'in', $assignedInvites];
            } else {
                // 没有分配邀请码，则不显示任何数据
                $where[] = ['id', '=', 0]; // 设置一个不可能满足的条件
            }
        }
        
        // 其他查询条件处理（关键词搜索等）
        if (isset($post['keywords']) && !empty($post['keywords'])) {
            $where[] = ['nickname|mobile', 'like', '%' . $post['keywords'] . '%'];
        }
        
        // 时间范围搜索等条件...
        
        // 执行查询
        $list = Db::table('app_user')->where($where)->order('id desc')->paginate([
            'list_rows' => 15,
            'query' => request()->param()
        ]);
        
        // 将管理员信息传递给视图
        View::assign('admin', $admin);
        View::assign('list', $list);
        View::assign('page', $list->render());
        return $this->fetch();
    }
}