<?php

namespace app\admin\controller;

use think\facade\Db;
use think\facade\View;
use app\admin\model\AdminMenu as MenuModel;

class Menu extends Permissions
{
    /**
     * 菜单列表
     * @return mixed
     */
    public function index()
    {
        $model = new MenuModel();
        $menu = $model->order('orders asc')->select();
        $menus = $model->menulist($menu);
        View::assign('menus', $menus);
        return $this->fetch();
    }

    /**
     * 添加/编辑菜单
     * @return mixed
     */
    public function publish()
    {
        // 获取菜单id
        $id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
        $model = new MenuModel();
        // 是正常添加操作
        if($id > 0) {
            // 是修改操作
            if($this->request->isPost()) {
                // 是提交操作
                $post = $this->request->post();
                // 验证
                $validate = \think\facade\Validate::rule([
                    'name' => 'require',
                    'pid' => 'require',
                    'type' => 'require'
                ])->message([
                    'name.require' => '菜单名称不能为空',
                    'pid.require' => '请选择上级菜单',
                    'type.require' => '请选择菜单类型'
                ]);
                
                // 验证部分数据合法性
                if (!$validate->check($post)) {
                    return $this->error('提交失败：' . $validate->getError());
                }
                
                // 验证菜单是否存在
                $menu = $model->where('id', $id)->find();
                if(empty($menu)) {
                    return $this->error('id不正确');
                }
                
                // 如果关闭默认展开，给默认值0
                if(empty($post['is_open'])) {
                    $post['is_open'] = 0;
                }
                
                if(false == $model->allowField(true)->save($post, ['id' => $id])) {
                    return $this->error('修改失败');
                } else {
                    $this->addLog('修改菜单', '修改了ID为'.$id.'的菜单');
                    return $this->success('修改菜单信息成功', 'admin/menu/index');
                }
            } else {
                // 非提交操作
                $menu = $model->where('id', $id)->find();
                $menus = $model->select();
                $menus_all = $model->menulist($menus);
                View::assign('menus', $menus_all);
                
                if(!empty($menu)) {
                    View::assign('menu', $menu);
                    return $this->fetch();
                } else {
                    return $this->error('id不正确');
                }
            }
        } else {
            // 是新增操作
            if($this->request->isPost()) {
                // 是提交操作
                $post = $this->request->post();
                // 验证
                $validate = \think\facade\Validate::rule([
                    'name' => 'require',
                    'pid' => 'require',
                    'type' => 'require'
                ])->message([
                    'name.require' => '菜单名称不能为空',
                    'pid.require' => '请选择上级菜单',
                    'type.require' => '请选择菜单类型'
                ]);
                
                // 验证部分数据合法性
                if (!$validate->check($post)) {
                    return $this->error('提交失败：' . $validate->getError());
                }
                
                if(false == $model->allowField(true)->save($post)) {
                    return $this->error('添加菜单失败');
                } else {
                    $this->addLog('添加菜单', '添加了新菜单：'.$post['name']);
                    return $this->success('添加菜单成功', 'admin/menu/index');
                }
            } else {
                // 非提交操作
                $pid = $this->request->has('pid') ? $this->request->param('pid', null, 'intval') : null;
                if(!empty($pid)) {
                    View::assign('pid', $pid);
                }
                
                $menu = $model->select();
                $menus = $model->menulist($menu);
                View::assign('menus', $menus);
                return $this->fetch();
            }
        }
    }

    /**
     * 删除菜单
     * @return mixed
     */
    public function delete()
    {
        if($this->request->isAjax()) {
            $id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
            
            if(Db::name('admin_menu')->where('pid', $id)->select()->isEmpty()) {
                if(false == Db::name('admin_menu')->where('id', $id)->delete()) {
                    return $this->error('删除失败');
                } else {
                    $this->addLog('删除菜单', '删除了ID为'.$id.'的菜单');
                    return $this->success('删除成功', 'admin/menu/index');
                }
            } else {
                return $this->error('该菜单下还有子菜单，不能删除');
            }
        }
    }

    /**
     * 菜单排序
     * @return mixed
     */
    public function orders()
    {
        if($this->request->isPost()) {
            $post = $this->request->post();
            $i = 0;
            
            foreach ($post['id'] as $k => $val) {
                $order = Db::name('admin_menu')->where('id', $val)->value('orders');
                if($order != $post['orders'][$k]) {
                    if(false == Db::name('admin_menu')->where('id', $val)->update(['orders' => $post['orders'][$k]])) {
                        return $this->error('更新失败');
                    } else {
                        $i++;
                    }
                }
            }
            
            $this->addLog('菜单排序', '更新了菜单排序');
            return $this->success('成功更新'.$i.'个数据', 'admin/menu/index');
        }
    }
}