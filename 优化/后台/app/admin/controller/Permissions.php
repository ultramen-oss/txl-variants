<?php

namespace app\admin\controller;

use think\App;
use think\facade\Db;
use think\facade\Session;
use think\facade\Cookie;

class Permissions extends Base
{
    protected $noLogin = ['login/index', 'login/verify', 'login/logout']; // 无需登录的方法
    protected $noAuth = ['index/index', 'index/home', 'index/clear']; // 无需权限的方法
    protected $adminInfo; // 登录信息

    // 构造方法
    public function __construct(App $app)
    {
        // 调用父类的构造函数
        parent::__construct($app);
        
        define('MODULE_NAME', app('http')->getName());
        define('CONTROLLER_NAME', $this->request->controller());
        define('ACTION_NAME', $this->request->action());
        
        // 定义是否AJAX请求常量
        define('IS_AJAX', $this->request->isAjax());
        define('IS_PJAX', $this->request->isPjax());
        
        // 检查是否登录
        $this->checkLogin();
        
        // 检查普通账号状态
        $this->checkUserStatus();
        
        // 二次验证权限
        if (session('admin')) {
            $this->checkAuth();
        }
    }
    
    // 检查当前用户的权限
    protected function checkPermissions()
    {
        // 获取当前访问的URL
        $module = app('http')->getName();
        $controller = strtolower($this->request->controller());
        $action = strtolower($this->request->action());
        $url = "$module/$controller/$action";
        
        // 判断用户是否有该权限
        $admin_id = Session::get('admin');
        $admin_cate_id = Session::get('admin_cate_id');
        
        // 超级管理员免检权限
        if ($admin_cate_id == 1) {
            return true;
        }
        
        // 简化权限验证，不再依赖 admin_menu 表
        // 默认允许访问常用控制器
        $allowed_controllers = ['index', 'appv1', 'mobile', 'sms'];
        if (in_array($controller, $allowed_controllers)) {
            return true;
        }
        
        // 如果没有权限，则跳转到无权限页面
        $this->error('您没有权限访问这个页面');
    }
    
    // 添加日志的方法
    protected function addLog($title = '', $content = '')
    {
        // 获取当前请求的URL和信息
        $admin_id = Session::get('admin');
        $ip = $this->request->ip();
        
        // 添加日志
        Db::name('admin_log')->insert([
            'admin_id' => $admin_id,
            'admin_menu_id' => 0, // 保留字段，但固定为0
            'operation_id' => $title,
            'ip' => $ip,
            'create_time' => time()
        ]);
    }

    /**
     * 检查普通账号状态 - 确保被禁用的账号不能继续访问系统
     * 每次请求都重新检查数据库中的账号状态，确保状态更新即时生效
     */
    protected function checkUserStatus()
    {
        // 获取当前登录的管理员ID
        $admin_id = Session::get('admin');
        
        // 如果已登录且不在无需登录的方法中
        if ($admin_id && !in_array(strtolower(CONTROLLER_NAME.'/'.ACTION_NAME), $this->noLogin)) {
            // 每次请求都从数据库重新查询管理员信息，确保获取最新状态
            $admin = Db::name('admin')->where('id', $admin_id)->find();
            
            // 非超级管理员且状态为禁用(0)时强制登出
            if ($admin && $admin['id'] != 1 && $admin['status'] == 0) {
                // 记录强制登出日志
                try {
                    Db::name('admin_log')->insert([
                        'admin_id' => $admin_id,
                        'admin_menu_id' => 0,
                        'operation_id' => '账号已禁用，强制登出',
                        'ip' => $this->request->ip(),
                        'create_time' => time()
                    ]);
                } catch (\Exception $e) {
                    // 记录日志失败不影响登出流程
                }
                
                // 清除登录状态
                Session::clear();
                Cookie::delete('admin_username');
                Cookie::delete('admin_password');
                
                // AJAX请求返回JSON
                if (IS_AJAX) {
                    $this->error('您的账号已被禁用，请联系管理员', url('admin/login/index'));
                } else {
                    // 普通请求重定向到登录页
                    return redirect((string)url('admin/login/index'))->send();
                }
            }
        }
    }

    /**
     * 检查是否登录
     */
    protected function checkLogin()
    {
        // 检查是否登录
        if (!session('admin')) {
            // 如果未登录，则跳转到登录页面
            return redirect((string)url('admin/login/index'))->send();
        }
    }

    /**
     * 检查普通账号状态
     */
    protected function checkAuth()
    {
        // 检查普通账号状态
        $this->checkPermissions();
    }
}