<?php


namespace app\admin\controller;

use think\facade\Db;
use think\facade\Cookie;
use think\facade\Session;
use think\facade\View;
use app\admin\model\Admin as AdminModel;
use app\admin\model\AdminMenu;

class Admin extends Permissions
{
    /**
     * 管理员列表
     * @return mixed
     */
    public function index()
    {
        // 实例化管理员模型
        $model = new AdminModel();

        $post = $this->request->param();
        if (isset($post['keywords']) && !empty($post['keywords'])) {
            $where[] = ['nickname', 'like', '%' . $post['keywords'] . '%'];
        }
        if (isset($post['admin_cate_id']) && $post['admin_cate_id'] > 0) {
            $where[] = ['admin_cate_id', '=', $post['admin_cate_id']];
        }
 
        if(isset($post['create_time']) && !empty($post['create_time'])) {
            $min_time = strtotime($post['create_time']);
            $max_time = $min_time + 24 * 60 * 60;
            $where[] = ['create_time', '>=', $min_time];
            $where[] = ['create_time', '<=', $max_time];
        }
        
        if(!isset($where)) {
            $where = [];
        }
        
        // 查询管理员列表数据，并关联管理员分类表
        $admin = $model->alias('a')
            ->leftJoin('admin_cate c', 'a.admin_cate_id = c.id')
            ->field('a.*, c.name as cate_name')
            ->where($where)
            ->order('a.create_time', 'desc')
            ->paginate([
                'list_rows' => 20,
                'query' => $this->request->param(),
                'type' => 'bootstrap',
                'var_page' => 'page'
            ]);
        
        // 获取当前登录的管理员信息
        $admin_id = Session::get('admin');
        $current_admin = $model->where('id', $admin_id)->find();
        
        // 确保当前管理员有role_type字段
        if ($current_admin) {
            $current_admin = $model->ensureRoleType($current_admin);
        }
        
        // 获取所有管理员分类
        $admin_cates = Db::name('admin_cate')->select();
        $admin_cate_names = [];
        foreach ($admin_cates as $cate) {
            $admin_cate_names[$cate['id']] = $cate['name'];
        }
        
        View::assign('admin', $admin);
        View::assign('admin_cate_names', $admin_cate_names);
        $info['cate'] = $admin_cates;
        View::assign('info', $info);
        // 将当前管理员信息传递给视图
        View::assign('current_admin', $current_admin);
        return $this->fetch();
    }

    /**
     * 管理员列表 - 新版后台UI
     * @return mixed
     */
    public function admin_list()
    {
        // 实例化管理员模型
        $model = new AdminModel();

        // 获取当前登录的管理员信息
        $admin_id = Session::get('admin');
        $current_admin = $model->where('id', $admin_id)->find();
        
        // 确保当前管理员有role_type字段
        if ($current_admin) {
            $current_admin = $model->ensureRoleType($current_admin);
        }

        $post = $this->request->param();
        if (isset($post['keywords']) && !empty($post['keywords'])) {
            $where[] = ['nickname', 'like', '%' . $post['keywords'] . '%'];
        }
        if (isset($post['admin_cate_id']) && $post['admin_cate_id'] > 0) {
            $where[] = ['admin_cate_id', '=', $post['admin_cate_id']];
        }
 
        if(isset($post['create_time']) && !empty($post['create_time'])) {
            $min_time = strtotime($post['create_time']);
            $max_time = $min_time + 24 * 60 * 60;
            $where[] = ['create_time', '>=', $min_time];
            $where[] = ['create_time', '<=', $max_time];
        }
        
        if(!isset($where)) {
            $where = [];
        }
        
        // 如果是普通账号，只显示自己的数据
        if ($current_admin && $current_admin['role_type'] == 'user') {
            $where[] = ['a.id', '=', $admin_id];
        }
        
        // 如果是普通管理员，隐藏超级管理员账号
        if ($current_admin && $current_admin['role_type'] == 'admin') {
            $where[] = ['a.role_type', '<>', 'super_admin'];
        }
        
        // 查询管理员列表数据，并关联管理员分类表
        $admin = $model->alias('a')
            ->leftJoin('admin_cate c', 'a.admin_cate_id = c.id')
            ->field('a.*, c.name as cate_name')
            ->where($where)
            ->order([
                'a.role_type' => 'asc', // 先按角色类型排序
                'a.id' => 'asc'        // 再按ID排序
            ])
            ->paginate([
                'list_rows' => 20,
                'query' => $this->request->param()
            ]);
        
        // 获取所有管理员分类
        $admin_cates = Db::name('admin_cate')->select();
        $admin_cate_names = [];
        foreach ($admin_cates as $cate) {
            $admin_cate_names[$cate['id']] = $cate['name'];
        }
        
        View::assign([
            'admin' => $current_admin,
            'admin_list' => $admin,
            'page' => $admin->render(),
            'info' => [
                'cate' => $admin_cates
            ],
            'admin_cate_names' => $admin_cate_names
        ]);
        return $this->fetch('admin_list');
    }
    
    /**
     * 重置管理员密码
     * @return mixed
     */
    public function resetPassword()
    {
        $id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
        View::assign('id', $id);
        return $this->fetch('reset_password');
    }
    
    /**
     * 处理重置密码提交
     * @return mixed
     */
    public function resetPwd()
    {
        if($this->request->isAjax()) {
            $post = $this->request->post();
            $validate = \think\facade\Validate::rule([
                'password' => 'require|length:6,20',
                'password_confirm' => 'require|confirm:password'
            ])->message([
                'password.require' => '新密码不能为空',
                'password.length' => '密码长度6-20位',
                'password_confirm.require' => '确认密码不能为空',
                'password_confirm.confirm' => '两次输入密码不一致'
            ]);
            
            if(!$validate->check($post)) {
                return $this->error($validate->getError());
            }
            
            // 重置密码操作
            $admin = Db::name('admin')->where('id', $post['id'])->find();
            if(!$admin) {
                return $this->error('管理员不存在');
            }
            
            // 更新密码
            if(false == Db::name('admin')->where('id', $post['id'])->update(['password'=>md5($post['password'])])) {
                return $this->error('重置密码失败');
            } else {
                $this->addLog('重置密码', '重置了ID为'.$post['id'].'的管理员密码');
                return $this->success('重置密码成功');
            }
        }
    }
    
    /**
     * 管理员个人资料修改，属于无权限操作，仅能修改昵称和头像，后续可增加其他字段
     * @return mixed
     */
    public function personal()
    {
        // 获取管理员id
        $id = Session::get('admin');
        $model = new AdminModel();
        if($id > 0) {
            // 是修改操作
            if($this->request->isPost()) {
                // 是提交操作
                $post = $this->request->post();
                // 验证昵称是否存在
                $nickname = $model->where([
                    ['nickname', '=', $post['nickname']],
                    ['id', '<>', $post['id']]
                ])->find();
                
                if($nickname) {
                    return $this->error('提交失败：该昵称已被占用');
                }
                
                if(false == $model->allowField(true)->save($post, ['id' => $id])) {
                    return $this->error('修改失败');
                } else {
                    $this->addLog('修改个人信息', '修改了个人资料');
                    return $this->success('修改个人信息成功', '/admin/admin/personal');
                }
            } else {
                // 非提交操作
                $info['admin'] = $model->where('id', $id)->find();
                View::assign('info', $info);
                return $this->fetch();
            }
        } else {
            return $this->error('id不正确');
        }
    }

    /**
     * 管理员的添加及修改
     * @return mixed
     */
    public function publish()
    {
        // 获取管理员id
        $id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
        $model = new AdminModel();
        
        // 获取当前登录的管理员
        $admin_id = Session::get('admin');
        $current_admin = AdminModel::where('id', $admin_id)->find();
        View::assign('current_admin', $current_admin);
        
        if($id > 0) {
            // 是修改操作
            $target_admin = $model->where('id', $id)->find();
            
            // 权限检查
            if($target_admin && $target_admin['role_type'] == 'super_admin' && $current_admin['role_type'] != 'super_admin') {
                return $this->error('您没有权限修改超级管理员');
            }
            
            if($this->request->isPost()) {
                // 是提交操作
                $post = $this->request->post();
                
                // 验证
                $validate = \think\facade\Validate::rule([
                    'name' => 'require|alphaDash',
                    'admin_cate_id' => 'require',
                    'nickname' => 'require'
                ])->message([
                    'name.require' => '管理员名称不能为空',
                    'name.alphaDash' => '用户名格式只能是字母、数字、——或_',
                    'admin_cate_id.require' => '请选择管理员分组',
                    'nickname.require' => '昵称不能为空'
                ]);
                
                // 验证部分数据合法性
                if (!$validate->check($post)) {
                    return $this->error($validate->getError());
                }
                
                // 验证用户名是否存在
                $name = $model->where([
                    ['name', '=', $post['name']],
                    ['id', '<>', $post['id']]
                ])->find();
                
                if($name) {
                    return $this->error('提交失败：该用户名已被注册');
                }
                
                // 验证昵称是否存在
                $nickname = $model->where([
                    ['nickname', '=', $post['nickname']],
                    ['id', '<>', $post['id']]
                ])->find();
                
                if($nickname) {
                    return $this->error('提交失败：该昵称已被占用');
                }
                
                // 先获取当前管理员数据
                $adminData = $model->where('id', $id)->find();
                if (!$adminData) {
                    return $this->error('管理员不存在');
                }
                
                // 检查邀请码是否已存在（包括普通用户表）
                if (!empty($post['invite_code']) && $post['role_type'] == 'user') {
                    // 检查管理员表中是否已存在该邀请码
                    $query = Db::name('admin')->where('invite_code', $post['invite_code']);
                    
                    // 新增时不需要排除自己，编辑时需要排除自己
                    if (isset($post['id']) && !empty($post['id'])) {
                        $query = $query->where('id', '<>', $post['id']);
                    }
                    
                    $existInAdmin = $query->find();
                    
                    // 检查用户表中是否已存在该邀请码
                    $existInUser = Db::table('app_user')->where('code', $post['invite_code'])->find();
                    
                    if ($existInAdmin || $existInUser) {
                        return $this->error('邀请码已存在，请重新生成或手动输入其他邀请码');
                    }
                    
                    // 校验邀请码格式：必须是纯数字且长度在2-6位之间
                    $codeLength = isset($post['code_length']) ? intval($post['code_length']) : 4;
                    if ($codeLength < 2) $codeLength = 2;
                    if ($codeLength > 6) $codeLength = 6;
                    
                    if (!is_numeric($post['invite_code']) || strlen($post['invite_code']) != $codeLength) {
                        return $this->error('邀请码格式不正确，请输入'.$codeLength.'位纯数字');
                    }
                }
                
                // 超级管理员不能被降级
                if($id == 1 && isset($post['role_type']) && $post['role_type'] != 'super_admin') {
                    return $this->error('超级管理员不能被降级');
                }
                
                // 权限检查：只有超级管理员可以设置其他管理员为超级管理员
                if(isset($post['role_type']) && $post['role_type'] == 'super_admin' && $current_admin['role_type'] != 'super_admin') {
                    return $this->error('您没有权限设置超级管理员');
                }
                
                // 如果是普通账号修改自己的信息
                if($id == $admin_id && $current_admin['role_type'] == 'user') {
                    // 普通账号不能修改自己的权限
                    if (isset($post['can_delete_user'])) {
                        unset($post['can_delete_user']); // 移除权限设置字段
                    }
                }
                
                // 如果是超级管理员修改自己的信息
                if($id == $admin_id && $current_admin['role_type'] == 'super_admin') {
                    // 超级管理员不能修改自己的分组和账号类型
                    $post['admin_cate_id'] = $target_admin['admin_cate_id'];
                    $post['role_type'] = 'super_admin';
                }
                
                // 根据角色类型自动设置对应的管理员分组
                if (isset($post['role_type'])) {
                    if ($post['role_type'] === 'super_admin') {
                        $post['admin_cate_id'] = 1;  // 超级管理员分组
                    } elseif ($post['role_type'] === 'admin') {
                        $post['admin_cate_id'] = 2; // 普通管理员分组
                    } elseif ($post['role_type'] === 'user') {
                        $post['admin_cate_id'] = 3; // 普通账号分组
                    }
                }
                
                // 处理查看权限字段
                if (!isset($post['view_all_invites'])) {
                    $post['view_all_invites'] = 0;
                }
                
                // 如果不是超级管理员修改普通管理员，则移除权限相关字段
                if ($current_admin['role_type'] != 'super_admin' || $target_admin['role_type'] != 'admin') {
                    unset($post['view_all_invites']);
                    unset($post['assigned_invites']);
                }
                
                // 获取可写入的字段
                $allowFields = ['admin_cate_id', 'role_type', 'name', 'nickname', 'thumb', 'can_delete_user', 'view_all_invites', 'assigned_invites', 'invite_code'];
                
                // 检查数据是否有实际变化
                $hasChanges = false;
                $nameChanged = false;
                
                foreach ($allowFields as $field) {
                    if (isset($post[$field]) && $post[$field] != $adminData[$field]) {
                        $hasChanges = true;
                        \think\facade\Log::info('字段变化: ' . $field . ', 原值: ' . $adminData[$field] . ', 新值: ' . $post[$field]);
                        
                        // 检查用户名是否变更
                        if ($field == 'name') {
                            $nameChanged = true;
                        }
                    }
                }
                
                // 如果没有任何字段变化，则不进行保存操作
                if (!$hasChanges) {
                    \think\facade\Log::info('表单无变化，无需保存');
                    return $this->error('表单数据无变化，无需保存');
                }
                
                // 设置字段
                $adminData->allowField($allowFields);
                
                // 设置数据
                foreach ($post as $key => $value) {
                    if (in_array($key, $allowFields)) {
                        $adminData[$key] = $value;
                        \think\facade\Log::info('设置字段: ' . $key . ' = ' . $value);
                    }
                }
                
                // 设置默认状态为启用
                $adminData->status = 1;
                
                // 手动设置时间字段
                $adminData->create_time = time();
                $adminData->update_time = time();
                
                // 保存前记录数据
                \think\facade\Log::info('准备保存的数据: ' . json_encode($adminData->toArray(), JSON_UNESCAPED_UNICODE));
                
                // 保存数据
                if(false == $adminData->save()) {
                    return $this->error('修改失败');
                } else {
                    $this->addLog('修改管理员', '修改了ID为'.$id.'的管理员信息');
                    
                    // 如果修改的是当前登录的管理员，且用户名发生了变化，需要重新登录
                    if($id == $admin_id && $nameChanged) {
                        // 清除会话信息
                        Session::clear();
                        Cookie::delete('admin_username');
                        Cookie::delete('admin_password');
                        return $this->success('修改成功，用户名已变更，请重新登录', '/admin/login/index');
                    }
                    
                    return $this->success('修改管理员信息成功', '/admin/admin/admin_list');
                }
            } else {
                // 非提交操作
                $info['admin'] = $model->where('id', $id)->find();
                $info['admin_cate'] = Db::name('admin_cate')->select();
                View::assign('info', $info);
                return $this->fetch();
            }
        } else {
            // 是新增操作
            // 判断添加代理是否超过总数
            $agency_id = config('app.agency_id');
            $agency_number = config('app.agency_number');
            if(isset($agency_id) && isset($agency_number)) {
                $count = $model->where(['admin_cate_id' => $agency_id])->count();
                if ($count > $agency_number) {
                    return $this->error('添加代理超过'.$agency_number.'人，无法继续添加');
                }
            }
            
            // 权限检查：普通账号不能添加管理员
            if($current_admin['role_type'] == 'user') {
                return $this->error('您没有权限添加管理员');
            }
            
            if($this->request->isPost()) {
                // 是提交操作
                $post = $this->request->post();
                // 验证
                $validate = \think\facade\Validate::rule([
                    'name' => 'require|alphaDash',
                    'password' => 'require|confirm',
                    'password_confirm' => 'require',
                    'admin_cate_id' => 'require',
                    'nickname' => 'require'
                ])->message([
                    'name.require' => '用户名不能为空',
                    'name.alphaDash' => '用户名格式只能是字母、数字、——或_',
                    'password.require' => '密码不能为空',
                    'password.confirm' => '两次密码不一致',
                    'password_confirm.require' => '重复密码不能为空',
                    'admin_cate_id.require' => '请选择管理员分组',
                    'nickname.require' => '昵称不能为空'
                ]);
                
                // 验证部分数据合法性
                if (!$validate->check($post)) {
                    return $this->error($validate->getError());
                }
                
                // 验证用户名是否存在
                $name = $model->where('name', $post['name'])->find();
                if($name) {
                    return $this->error('提交失败：该用户名已被注册');
                }
                
                // 验证昵称是否存在
                $nickname = $model->where('nickname', $post['nickname'])->find();
                if($nickname) {
                    return $this->error('提交失败：该昵称已被占用');
                }
                
                // 权限检查：只有超级管理员可以添加超级管理员
                if(isset($post['role_type']) && $post['role_type'] == 'super_admin' && $current_admin['role_type'] != 'super_admin') {
                    return $this->error('您没有权限设置超级管理员');
                }
                
                // 普通管理员只能添加普通账号
                if($current_admin['role_type'] == 'admin' && isset($post['role_type']) && $post['role_type'] != 'user') {
                    $post['role_type'] = 'user'; // 强制设置为普通账号
                }
                
                // 根据角色类型自动设置对应的管理员分组
                if (isset($post['role_type'])) {
                    if ($post['role_type'] === 'super_admin') {
                        $post['admin_cate_id'] = 1;  // 超级管理员分组
                    } elseif ($post['role_type'] === 'admin') {
                        $post['admin_cate_id'] = 2; // 普通管理员分组
                    } elseif ($post['role_type'] === 'user') {
                        $post['admin_cate_id'] = 3; // 普通账号分组
                    }
                }
                
                // 密码处理
                $post['password'] = md5($post['password']);
                
                // 获取可写入的字段
                $allowFields = ['admin_cate_id', 'role_type', 'name', 'nickname', 'password', 'thumb', 'can_delete_user', 'invite_code'];
                
                // 检查邀请码是否已存在（包括普通用户表）
                if (!empty($post['invite_code']) && $post['role_type'] == 'user') {
                    // 检查管理员表中是否已存在该邀请码
                    $query = Db::name('admin')->where('invite_code', $post['invite_code']);
                    
                    // 新增时不需要排除自己，编辑时需要排除自己
                    if (isset($post['id']) && !empty($post['id'])) {
                        $query = $query->where('id', '<>', $post['id']);
                    }
                    
                    $existInAdmin = $query->find();
                    
                    // 检查用户表中是否已存在该邀请码
                    $existInUser = Db::table('app_user')->where('code', $post['invite_code'])->find();
                    
                    if ($existInAdmin || $existInUser) {
                        return $this->error('邀请码已存在，请重新生成或手动输入其他邀请码');
                    }
                    
                    // 校验邀请码格式：必须是纯数字且长度在2-6位之间
                    $codeLength = isset($post['code_length']) ? intval($post['code_length']) : 4;
                    if ($codeLength < 2) $codeLength = 2;
                    if ($codeLength > 6) $codeLength = 6;
                    
                    if (!is_numeric($post['invite_code']) || strlen($post['invite_code']) != $codeLength) {
                        return $this->error('邀请码格式不正确，请输入'.$codeLength.'位纯数字');
                    }
                }
                
                // 创建一个新的模型实例
                $adminData = new AdminModel();
                
                // 设置字段
                $adminData->allowField($allowFields);
                
                // 设置数据
                foreach ($post as $key => $value) {
                    if (in_array($key, $allowFields)) {
                        $adminData[$key] = $value;
                    }
                }
                
                // 设置默认状态为启用
                $adminData->status = 1;
                
                // 手动设置时间字段
                $adminData->create_time = time();
                $adminData->update_time = time();
                
                // 保存数据
                if(false == $adminData->save()) {
                    return $this->error('添加管理员失败');
                } else {
                    $this->addLog('添加管理员', '添加了新管理员：'.$post['name']);
                    // 检查是否已有邀请码，若没有则生成
                    if (empty($adminData['invite_code']) && $post['role_type'] == 'user') {
                        $bool = true;
                        // 获取设置的邀请码位数，默认为4位，限制范围2-6位
                        $code_length = isset($post['code_length']) ? intval($post['code_length']) : 4;
                        // 确保在有效范围内
                        if ($code_length < 2) $code_length = 2;
                        if ($code_length > 6) $code_length = 6;
                        
                        $invitation_code = $this->generateNumericCode($code_length);
                        while($bool) {
                            // 检查管理员表中是否已存在该邀请码
                            $findInAdmin = Db::name('admin')->where('invite_code', $invitation_code)->find();
                            
                            // 检查用户表中是否已存在该邀请码
                            $findInUser = Db::table('app_user')->where('code', $invitation_code)->find();
                            
                            if(empty($findInAdmin) && empty($findInUser)) {
                                $bool = false;
                            } else {
                                $invitation_code = $this->generateNumericCode($code_length);
                            }
                        }
                        
                        Db::name('admin')->where('id', $adminData->id)->update(['invite_code' => $invitation_code]);
                    }
                    return $this->success('添加管理员成功', '/admin/admin/admin_list');
                }
            } else {
                // 非提交操作
                $info['admin_cate'] = Db::name('admin_cate')->select();
                // 初始化admin为null，避免未定义索引错误
                $info['admin'] = null;
                
                // 确保当前管理员角色类型已正确设置
                if (!isset($current_admin['role_type'])) {
                    $current_admin = $model->ensureRoleType($current_admin);
                }
                
                View::assign('info', $info);
                View::assign('current_admin', $current_admin);
                return $this->fetch();
            }
        }
    }

    /**
     * 修改密码
     * @return mixed
     */
    public function editPassword()
    {
        // 获取管理员id
        $id = Session::get('admin');
        $model = new AdminModel();
        if($this->request->isPost()) {
            // 是提交操作
            $post = $this->request->post();
            
            // 验证  唯一规则： 表名，字段名，排除主键值，主键名
            $validate = \think\facade\Validate::rule([
                'oldpassword' => 'require',
                'password' => 'require|min:6',
                'password_confirm' => 'require|confirm:password'
            ])->message([
                'oldpassword.require' => '旧密码不能为空',
                'password.require' => '新密码不能为空',
                'password.min' => '密码长度不能少于6位',
                'password_confirm.require' => '重复密码不能为空',
                'password_confirm.confirm' => '两次输入密码不一致'
            ]);
            
            // 验证部分数据合法性
            if (!$validate->check($post)) {
                return $this->error($validate->getError());
            }
            
            // 校验原密码
            $admin = $model->where('id', $id)->find();
            if(!$admin) {
                return $this->error('管理员不存在');
            }
            
            if(md5($post['oldpassword']) != $admin['password']) {
                return $this->error('旧密码错误');
            }
            
            try {
                $result = $model->where('id', $id)->update(['password' => md5($post['password'])]);
                
                if($result === false) {
                    return $this->error('修改密码失败');
                } else {
                    $this->addLog('修改密码', '修改了登录密码');
                    // 修改成功后清除登录状态，并返回到登录页面
                    Session::clear();
                    Cookie::delete('admin_username');
                    Cookie::delete('admin_password');
                    return $this->success('修改密码成功，请重新登录', '/admin/login/index');
                }
            } catch (\Exception $e) {
                return $this->error('修改密码失败: ' . $e->getMessage());
            }
        } else {
            // 非提交操作
            $admin = $model->where('id', $id)->find();
            View::assign('admin', $admin);
            return $this->fetch();
        }
    }

    /**
     * 管理员删除
     * @return mixed
     */
    public function delete()
    {
        if($this->request->isAjax()) {
            $id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
            if($id == 1) {
                return $this->error('超级管理员不允许删除');
            }
            
            $model = new AdminModel();
            if(false == $model->where('id', $id)->delete()) {
                return $this->error('删除失败');
            } else {
                $this->addLog('删除管理员', '删除了ID为'.$id.'的管理员');
                return $this->success('删除成功');
            }
        }
    }

    /**
     * 管理员操作日志
     * @return mixed
     */
    public function log()
    {
        $model = Db::name('admin_log');
        $post = $this->request->param();
        
        if (isset($post['admin_id']) && $post['admin_id'] > 0) {
            $where[] = ['admin_id', '=', $post['admin_id']];
        }
        
        if(isset($post['create_time']) && !empty($post['create_time'])) {
            $min_time = strtotime($post['create_time']);
            $max_time = $min_time + 24 * 60 * 60;
            $where[] = ['create_time', '>=', $min_time];
            $where[] = ['create_time', '<=', $max_time];
        }
        
        if(!isset($where)) {
            $where = [];
        }
        
        $log = $model->where($where)->order('create_time desc')->paginate([
            'list_rows' => 20,
            'query' => $this->request->param()
        ]);
        
        foreach ($log as $key => $value) {
            $log[$key]['admin'] = Db::name('admin')->where('id', $value['admin_id'])->value('nickname');
        }
        
        View::assign([
            'log' => $log,
            'admin' => Db::name('admin')->select()
        ]);
        return $this->fetch();
    }

    /**
     * 成功操作返回
     * @param string $msg 提示信息
     * @param string $url 跳转URL
     * @param array $data 返回数据
     * @return \think\response\Json
     */
    protected function success($msg = '', $url = null, $data = [])
    {
        $result = [
            'code' => 1,
            'msg'  => $msg,
            'data' => $data,
        ];
        
        if ($url) {
            $result['url'] = (string)$url;
        }
        
        return json($result);
    }
    
    /**
     * 失败操作返回
     * @param string $msg 提示信息
     * @param string $url 跳转URL
     * @param array $data 返回数据
     * @return \think\response\Json
     */
    protected function error($msg = '', $url = null, $data = [])
    {
        $result = [
            'code' => 0,
            'msg'  => $msg,
            'data' => $data,
        ];
        
        if ($url) {
            $result['url'] = (string)$url;
        }
        
        return json($result);
    }

    /**
     * 修改用户的删除和导出权限
     * @return mixed
     */
    public function changeDeletePermission()
    {
        if (!$this->request->isAjax()) {
            return $this->error('非法请求');
        }
        
        $id = $this->request->param('id', 0, 'intval');
        $can_delete_user = $this->request->param('can_delete_user', 0, 'intval');
        $can_export_data = $this->request->param('can_export_data', 0, 'intval');
        
        if ($id <= 0) {
            return $this->error('参数错误');
        }
        
        // 检查当前管理员权限
        $admin_id = Session::get('admin');
        $current_admin = Db::name('admin')->where('id', $admin_id)->find();
        
        // 确保当前管理员有role_type字段
        $model = new AdminModel();
        if ($current_admin) {
            $current_admin = $model->ensureRoleType($current_admin);
        }
        
        // 检查是否有权限修改
        if ($current_admin['role_type'] == 'user') {
            return $this->error('您没有权限进行此操作');
        }
        
        // 获取目标用户
        $target_user = Db::name('admin')->where('id', $id)->find();
        if (!$target_user) {
            return $this->error('用户不存在');
        }
        
        // 只允许修改普通账号的权限
        $target_user = $model->ensureRoleType($target_user);
        if ($target_user['role_type'] != 'user') {
            return $this->error('只能修改普通账号的权限');
        }
        
        // 获取现有设置
        $current_delete_perm = isset($target_user['can_delete_user']) ? intval($target_user['can_delete_user']) : 0;
        $current_export_perm = isset($target_user['can_export_data']) ? intval($target_user['can_export_data']) : 0;
        
        // 如果设置未变，返回错误
        if ($current_delete_perm == $can_delete_user && $current_export_perm == $can_export_data) {
            return $this->error('设置未变更');
        }
        
        // 更新权限
        $result = Db::name('admin')->where('id', $id)
                ->update([
                    'can_delete_user' => $can_delete_user,
                    'can_export_data' => $can_export_data
                ]);
        
        if ($result) {
            // 记录日志
            $this->addLog('更新用户权限', "将用户ID:{$id}的删除权限设为:{$can_delete_user},导出权限设为:{$can_export_data}");
            return $this->success('权限设置成功');
        } else {
            return $this->error('权限设置失败');
        }
    }
    
    /**
     * 修改普通管理员查看全部邀请的权限
     * @return mixed
     */
    public function changeAdminPermission()
    {
        if($this->request->isAjax()) {
            $id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
            $view_all_invites = $this->request->has('view_all_invites') ? $this->request->param('view_all_invites', 0, 'intval') : 0;
            
            // 获取当前登录的管理员
            $admin_id = Session::get('admin');
            $admin = AdminModel::where('id', $admin_id)->find();
            
            // 目标管理员
            $target_admin = AdminModel::where('id', $id)->find();
            
            // 权限检查：只有超级管理员可以修改普通管理员的权限
            if(!$target_admin) {
                return $this->error('管理员不存在');
            }
            
            // 只有超级管理员可以设置其他管理员的权限
            if($admin['role_type'] != 'super_admin') {
                return $this->error('您没有权限修改管理员权限');
            }
            
            // 目标必须是普通管理员
            if($target_admin['role_type'] != 'admin') {
                return $this->error('只能修改普通管理员的权限');
            }
            
            if(false == Db::name('admin')->where('id', $id)->update(['view_all_invites' => $view_all_invites])) {
                return $this->error('权限修改失败');
            } else {
                $this->addLog('修改管理员权限', '修改了ID为'.$id.'的管理员查看所有邀请权限为'.($view_all_invites ? '允许' : '禁止'));
                return $this->success('权限修改成功');
            }
        }
    }
    
    /**
     * 为普通管理员分配可查看的邀请码
     * @return mixed
     */
    public function assignInvites()
    {
        if($this->request->isAjax()) {
            $id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
            $invite_codes = $this->request->has('assigned_invites') ? $this->request->param('assigned_invites') : '';
            
            // 获取当前登录的管理员
            $admin_id = Session::get('admin');
            $admin = AdminModel::where('id', $admin_id)->find();
            
            // 目标管理员
            $target_admin = AdminModel::where('id', $id)->find();
            
            // 权限检查：只有超级管理员可以为普通管理员分配邀请码
            if(!$target_admin) {
                return $this->error('管理员不存在');
            }
            
            // 只有超级管理员可以设置其他管理员的权限
            if($admin['role_type'] != 'super_admin') {
                return $this->error('您没有权限分配邀请码');
            }
            
            // 目标必须是普通管理员
            if($target_admin['role_type'] != 'admin') {
                return $this->error('只能为普通管理员分配邀请码');
            }
            
            if(false == Db::name('admin')->where('id', $id)->update(['assigned_invites' => $invite_codes])) {
                return $this->error('分配邀请码失败');
            } else {
                $this->addLog('分配邀请码', '为ID为'.$id.'的管理员分配了邀请码：'.$invite_codes);
                return $this->success('分配邀请码成功');
            }
        }
    }
    
    /**
     * 修改管理员状态
     * @return mixed
     */
    public function changeStatus()
    {
        if($this->request->isAjax()) {
            $id = $this->request->param('id', 0, 'intval');
            $status = $this->request->param('status', 0, 'intval');
            
            if($id == 1) {
                return $this->error('无法修改超级管理员状态');
            }
            
            // 获取当前登录的管理员
            $admin_id = Session::get('admin');
            $admin = AdminModel::where('id', $admin_id)->find();
            
            // 目标管理员
            $target_admin = AdminModel::where('id', $id)->find();
            
            // 权限检查：只有超级管理员和普通管理员可以修改普通账号的状态
            if(!$target_admin) {
                return $this->error('管理员不存在');
            }
            
            // 普通账号不能修改其他管理员的状态
            if($admin['role_type'] == 'user') {
                return $this->error('您没有权限修改管理员状态');
            }
            
            // 普通管理员只能修改普通账号的状态
            if($admin['role_type'] == 'admin' && $target_admin['role_type'] != 'user') {
                return $this->error('普通管理员只能修改普通账号的状态');
            }
            
            // 更新状态
            try {
                // 防止status不是1或0
                $status = $status ? 1 : 0;
                
                $result = Db::name('admin')->where('id', $id)->update(['status' => $status]);
                if($result === false) {
                    // 日志记录更新失败
                    return $this->error('修改状态失败');
                }
                
                // 判断是否真的更新了记录
                if($result === 0) {
                    // 可能没有实际变化，但不算错误
                    return $this->success('状态未变化');
                }
                
                // 记录日志
                $this->addLog('修改管理员状态', '修改ID为'.$id.'的管理员状态为'. ($status ? '启用' : '禁用'));
                    
                // 如果状态是禁用(0)，立即强制该用户退出登录
                if($status == 0) {
                    // 调用强制登出的方法
                    $this->forceLogoutUser($id);
                }
                
                return $this->success('修改状态成功');
            } catch (\Exception $e) {
                // 捕获并记录异常
                return $this->error('修改状态失败：'.$e->getMessage());
            }
        }
        
        return $this->error('非法请求');
    }

    /**
     * 强制登出管理员
     * @return mixed
     */
    public function forceLogout()
    {
        if($this->request->isAjax()) {
            $id = $this->request->param('id', 0, 'intval');
            
            if($id == 1) {
                return $this->error('无法强制登出超级管理员');
            }
            
            // 查询管理员
            $admin = Db::name('admin')->where('id', $id)->find();
            if(!$admin) {
                return $this->error('管理员不存在');
            }
            
            // 检查当前状态，只有被禁用的管理员才能被强制登出
            if($admin['status'] == 1) {
                return $this->success('账号已启用，无需强制登出');
            }
            
            // 调用强制登出方法
            return $this->forceLogoutUser($id);
        }
    }
    
    /**
     * 强制用户退出登录
     * @param int $id 管理员ID
     * @return mixed
     */
    protected function forceLogoutUser($id)
    {
        if($id == 1) {
            return $this->error('无法强制登出超级管理员');
        }
        
        // 查询管理员
        $admin = Db::name('admin')->where('id', $id)->find();
        if(!$admin) {
            return $this->error('管理员不存在');
        }
        
        // 清除该管理员的会话和token
        $token = md5(uniqid(mt_rand(), true));
        try {
            // 1. 更新管理员表的token字段，使现有的token失效
            $result = Db::name('admin')->where('id', $id)->update([
                'token' => $token, 
                'last_login_time' => time(),
                'status' => 0 // 同时设置状态为禁用
            ]);
            
            if($result === false) {
                return $this->error('强制登出失败');
            }
            
            // 2. 记录强制登出日志
            Db::name('admin_log')->insert([
                'admin_id' => Session::get('admin'),
                'admin_menu_id' => 0,
                'operation_id' => '强制登出管理员',
                'ip' => $this->request->ip(),
                'create_time' => time(),
                'content' => '强制登出ID为'.$id.'的管理员'
            ]);
            
            // 3. 如果是当前管理员，则清除当前会话
            $currentAdminId = Session::get('admin');
            if($currentAdminId == $id) {
                Session::clear();
                Cookie::delete('admin_username');
                Cookie::delete('admin_password');
            }
            
            // 4. 记录被强制登出管理员的最后操作信息
            Db::name('admin_log')->insert([
                'admin_id' => $id,
                'admin_menu_id' => 0,
                'operation_id' => '账号被强制登出',
                'ip' => $this->request->ip(),
                'create_time' => time()
            ]);
            
            return $this->success('强制登出成功');
        } catch (\Exception $e) {
            return $this->error('强制登出失败：系统错误');
        }
    }

    /**
     * 踢用户下线（仅重置 token，不禁用账号）
     * 仅超级管理员可操作
     */
    public function kickOffline()
    {
        $id = $this->request->param('id', 0, 'intval');
        if (!$id) {
            return json(['code' => 0, 'msg' => '参数错误']);
        }
        
        // 仅超管可操作
        $currentAdmin = Db::name('admin')->where('id', Session::get('admin'))->find();
        if (!$currentAdmin || ($currentAdmin['id'] != 1 && $currentAdmin['role_type'] !== 'super_admin')) {
            return json(['code' => 0, 'msg' => '无权操作']);
        }
        
        if ($id == 1) {
            return json(['code' => 0, 'msg' => '无法踢超级管理员下线']);
        }
        
        $admin = Db::name('admin')->where('id', $id)->find();
        if (!$admin) {
            return json(['code' => 0, 'msg' => '用户不存在']);
        }
        
        try {
            // 确保 token 字段存在
            $this->ensureTokenField();
            
            // 重置 token 使其当前会话失效
            $newToken = md5(uniqid('kick_' . $id, true) . time());
            Db::name('admin')->where('id', $id)->update(['token' => $newToken]);
        } catch (\Exception $e) {
            return json(['code' => 0, 'msg' => '操作失败: ' . $e->getMessage()]);
        }
        
        // 记录日志
        try {
            Db::name('admin_log')->insert([
                'admin_id' => Session::get('admin'),
                'admin_menu_id' => 0,
                'operation_id' => '踢用户下线',
                'ip' => $this->request->ip(),
                'create_time' => time()
            ]);
        } catch (\Exception $e) {
            // ignore
        }
        
        return json(['code' => 1, 'msg' => '已将 ' . $admin['name'] . ' 踢下线']);
    }

    /**
     * 确保 admin 表有 token 字段
     */
    private function ensureTokenField()
    {
        try {
            $table = Db::name('admin')->getTable();
            $columns = Db::query("SHOW COLUMNS FROM `{$table}` LIKE 'token'");
            if (empty($columns)) {
                Db::execute("ALTER TABLE `{$table}` ADD COLUMN `token` VARCHAR(64) DEFAULT NULL COMMENT '登录token' AFTER `password`");
            }
        } catch (\Exception $e) {
            \think\facade\Log::record('ensureTokenField失败: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * 查看管理员登录日志
     * @return mixed
     */
    public function loginLog()
    {
        $id = $this->request->param('id', 0, 'intval');
        
        // 查询该管理员
        $admin = Db::name('admin')->where('id', $id)->find();
        if(!$admin) {
            return $this->error('管理员不存在');
        }
        
        // 获取该管理员的登录日志
        $loginLogs = Db::name('admin_log')
            ->field('create_time, ip')
            ->where('admin_id', $id)
            ->where('operation_id', '登录系统')
            ->order('create_time', 'desc')
            ->limit(20)
            ->select();
        
        // 格式化日志数据
        $logs = [];
        foreach($loginLogs as $log) {
            $logs[] = [
                'login_time' => date('Y-m-d H:i:s', $log['create_time']),
                'ip' => $log['ip'] ? $log['ip'] : '未记录'
            ];
        }
        
        // 获取管理员的最后登录信息
        $lastLogin = [
            'time' => $admin['login_time'] ? date('Y-m-d H:i:s', $admin['login_time']) : '未登录',
            'ip' => $admin['login_ip'] ? $admin['login_ip'] : '未记录'
        ];
        
        View::assign([
            'admin' => $admin,
            'logs' => $logs,
            'lastLogin' => $lastLogin
        ]);
        
        return View::fetch('login_log');
    }

    /**
     * 添加日志的方法
     * @param string $title 日志标题
     * @param string $content 日志内容
     */
    protected function addLog($title = '', $content = '')
    {
        try {
            // 获取当前请求的URL和信息
            $admin_id = Session::get('admin');
            $ip = $this->request->ip();
            
            // 添加日志，根据 app_admin_log 表的实际结构
            Db::name('admin_log')->insert([
                'admin_id' => $admin_id,
                'admin_menu_id' => 0, // 默认为0
                'operation_id' => $title ?: '操作', // 使用 operation_id 字段
                'ip' => $ip,
                'create_time' => time()
            ]);
        } catch (\Exception $e) {
            // 记录日志错误但不影响主功能
        }
    }

    /**
     * 检查当前账号状态API
     * @return mixed
     */
    public function checkAccountStatus()
    {
        if ($this->request->isAjax()) {
            // 获取当前登录的管理员ID
            $admin_id = Session::get('admin');
            if (!$admin_id) {
                return $this->error('未登录');
            }
            
            // 查询管理员信息
            $admin = Db::name('admin')->where('id', $admin_id)->find();
            if (!$admin) {
                return $this->error('管理员不存在');
            }
            
            // 检查状态，如果是超级管理员则始终返回正常
            if ($admin['id'] == 1 || $admin['role_type'] == 'super_admin') {
                return $this->success('账号状态正常');
            }
            
            // 检查普通账号状态
            if ($admin['status'] != 1) {
                return $this->error('您的账号已被禁用');
            }
            
            return $this->success('账号状态正常');
        }
    }

    /**
     * 选择要分配的邀请码
     * @return mixed
     */
    public function select_invites()
    {
        // 只有超级管理员可以访问此页面
        $admin_id = Session::get('admin');
        $admin = AdminModel::where('id', $admin_id)->find();
        
        if (!$admin || $admin['role_type'] != 'super_admin') {
            return $this->error('无权访问');
        }
        
        // 获取所有带有邀请码的普通账号
        $inviteCodes = Db::name('admin')
            ->field('id, name, nickname, invite_code')
            ->where('invite_code', '<>', '')
            ->where('invite_code', 'not null')
            ->select();
        
        View::assign('inviteCodes', $inviteCodes);
        return View::fetch('select_invites');
    }

    /**
     * 获取所有带有邀请码的普通用户列表（用于AJAX请求）
     * @return mixed
     */
    public function getUsersWithInviteCodes()
    {
        // 只有超级管理员可以使用此功能
        $admin_id = Session::get('admin');
        $admin = AdminModel::where('id', $admin_id)->find();
        
        if (!$admin || $admin['role_type'] != 'super_admin') {
            return json(['code' => 0, 'msg' => '无权访问']);
        }
        
        // 获取所有带有邀请码的普通账号
        $users = Db::name('admin')
            ->field('id, name, nickname, invite_code, status')
            ->where('role_type', 'user')
            ->where('invite_code', '<>', '')
            ->where('invite_code', 'not null')
            ->select()
            ->toArray();
        
        return json(['code' => 1, 'msg' => '获取成功', 'data' => $users]);
    }
    
    /**
     * 获取管理员已分配的邀请码（用于AJAX请求）
     * @return mixed
     */
    public function getAssignedInvites()
    {
        // 只有超级管理员可以使用此功能
        $admin_id = Session::get('admin');
        $admin = AdminModel::where('id', $admin_id)->find();
        
        if (!$admin || $admin['role_type'] != 'super_admin') {
            return json(['code' => 0, 'msg' => '无权访问']);
        }
        
        // 获取目标管理员ID
        $id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
        
        // 获取目标管理员
        $target_admin = AdminModel::where('id', $id)->find();
        
        if (!$target_admin) {
            return json(['code' => 0, 'msg' => '管理员不存在']);
        }
        
        // 目标必须是普通管理员
        if ($target_admin['role_type'] != 'admin') {
            return json(['code' => 0, 'msg' => '只能为普通管理员分配邀请码']);
        }
        
        // 返回管理员已分配的邀请码
        return json(['code' => 1, 'msg' => '获取成功', 'data' => $target_admin['assigned_invites']]);
    }

    /**
     * 生成指定长度的纯数字邀请码
     * @param int $length 邀请码长度
     * @return string 纯数字邀请码
     */
    protected function generateNumericCode($length)
    {
        $code = '';
        for($i = 0; $i < $length; $i++) {
            $code .= mt_rand(0, 9);
        }
        return $code;
    }

    /**
     * 查看已分配的用户列表
     * @return mixed
     */
    public function viewAssignedUsers()
    {
        // 获取当前登录管理员信息
        $admin_id = Session::get('admin');
        $admin = AdminModel::where('id', $admin_id)->find();
        
        if (!$admin) {
            return $this->error('管理员不存在');
        }
        
        // 获取请求中的管理员ID
        $id = $this->request->param('id', 0, 'intval');
        
        // 如果传入了ID并且当前用户是超级管理员，查看指定管理员的分配
        // 否则查看当前登录的管理员自己的分配
        if ($id > 0 && $admin['role_type'] == 'super_admin') {
            $target_admin = AdminModel::where('id', $id)->find();
        } else {
            $target_admin = $admin;
            $id = $admin_id;
        }
        
        if (!$target_admin) {
            return $this->error('目标管理员不存在');
        }
        
        // 只有普通管理员才有分配的用户
        if ($target_admin['role_type'] != 'admin') {
            return $this->error('只有普通管理员才有分配的用户列表');
        }
        
        // 获取该管理员的查看权限
        $view_all_invites = $target_admin['view_all_invites'] == 1;
        
        // 如果拥有查看所有权限
        if ($view_all_invites) {
            // 获取所有普通账号
            $users = Db::name('admin')
                ->field('id, name, nickname, invite_code, status, create_time')
                ->where('role_type', 'user')
                ->where('invite_code', '<>', '')
                ->where('invite_code', 'not null')
                ->select()
                ->toArray();
                
            $user_count = count($users);
            View::assign('view_all', true);
        } else {
            // 获取已分配的邀请码
            $assigned_invites = $target_admin['assigned_invites'];
            
            if (empty($assigned_invites)) {
                $users = [];
                $user_count = 0;
                View::assign('view_all', false);
            } else {
                // 将邀请码字符串转为数组
                $invite_codes = explode(',', $assigned_invites);
                
                // 获取这些邀请码对应的普通账号
                $users = Db::name('admin')
                    ->field('id, name, nickname, invite_code, status, create_time')
                    ->where('role_type', 'user')
                    ->where('invite_code', 'in', $invite_codes)
                    ->select()
                    ->toArray();
                    
                $user_count = count($users);
                View::assign('view_all', false);
            }
        }
        
        // 获取每个普通账号邀请的用户数量
        foreach ($users as &$user) {
            // 查询每个普通账号邀请的用户数量，使用code字段，并转换为整数进行比较
            $invite_code = intval($user['invite_code']);
            $user['invited_count'] = Db::name('user')
                ->where('code', $invite_code)
                ->count();
        }
        
        // 分配数据到视图
        View::assign('admin', $admin);
        View::assign('target_admin', $target_admin);
        View::assign('users', $users);
        View::assign('user_count', $user_count);
        
        return View::fetch('assigned_users');
    }

    /**
     * 修改管理员查看全部邀请码的权限
     * @return \think\response\Json
     */
    public function changeViewAllPermission()
    {
        if (!$this->request->isPost()) {
            return $this->error('非法请求');
        }
        
        // 获取超级管理员信息
        $admin_id = Session::get('admin');
        $admin = AdminModel::where('id', $admin_id)->find();
        
        if (!$admin) {
            return $this->error('管理员不存在');
        }
        
        // 只有超级管理员可以修改权限
        if ($admin['role_type'] != 'super_admin') {
            return $this->error('只有超级管理员可以修改此权限');
        }
        
        // 获取要修改的管理员ID和权限值
        $id = $this->request->param('id', 0, 'intval');
        $view_all_invites = $this->request->param('view_all_invites', 0, 'intval');
        
        if ($id <= 0) {
            return $this->error('参数错误');
        }
        
        // 确认目标是普通管理员
        $target_admin = AdminModel::where('id', $id)->find();
        if (!$target_admin) {
            return $this->error('目标管理员不存在');
        }
        
        if ($target_admin['role_type'] != 'admin') {
            return $this->error('只能修改普通管理员的权限');
        }
        
        // 更新权限
        $update = [
            'view_all_invites' => $view_all_invites
        ];
        
        // 如果设置为查看全部，则清空已分配的邀请码
        if ($view_all_invites == 1) {
            $update['assigned_invites'] = '';
        }
        
        $result = AdminModel::where('id', $id)->update($update);
        
        if ($result) {
            // 记录操作日志
            $this->addLog('修改管理员权限', "管理员ID: {$id}, 设置查看全部邀请码权限: " . ($view_all_invites == 1 ? '是' : '否'));
            return $this->success('权限修改成功');
        } else {
            return $this->error('权限修改失败，请重试');
        }
    }
}