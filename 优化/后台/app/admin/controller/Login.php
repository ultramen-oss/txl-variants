<?php

namespace app\admin\controller;

use app\BaseController;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\facade\Cookie;
use think\facade\Validate;
use think\facade\Config;
use think\captcha\facade\Captcha;

class Login extends BaseController
{
    /**
     * 登录页面
     */
    public function index()
    {
        // 来源验证 - 检查是否通过自定义路径访问
        $requestUri = request()->server('REQUEST_URI');
        
        // 获取URL中的访问令牌
        $authToken = $this->request->param('_auth');
        $referer = request()->server('HTTP_REFERER');
        
        // 检查会话中是否存在登录验证标记
        $loginVerified = Session::get('login_verified');
        
        // 如果有令牌，设置会话标记
        if ($authToken) {
            Session::set('login_verified', true);
            // 重定向到不带令牌的登录页面
            return redirect('/admin/login');
        }
        
        // 如果没有会话标记且是直接访问，展示404页面
        if (false && !$loginVerified && !$referer) {
            // 展示404页面
            include app()->getRootPath() . 'app/index/view/error/page404.html';
            exit;
        }
        
        // 如果已经登录，则直接跳转到后台首页
        if (Session::has('admin')) {
            // 获取自定义后台首页路由
            $config = Db::name('config')->where('name', 'admin_route_settings')->find();
            $homeRoute = '/admin/index/index';
            
            if ($config && !empty($config['value'])) {
                $routeSettings = json_decode($config['value'], true);
                if (!empty($routeSettings) && isset($routeSettings['admin_home_route'])) {
                    $homeRoute = $routeSettings['admin_home_route'];
                    // 确保路由格式正确
                    if (strpos($homeRoute, '/admin/') !== 0) {
                        $homeRoute = '/admin/index/index';
                    }
                }
            }
            
            return redirect($homeRoute);
        }
        
        // 判断是否记住了登录状态
        if (Cookie::has('admin_username') && Cookie::has('admin_password')) {
            $admin = Db::name('admin')
                ->where('name', Cookie::get('admin_username'))
                ->where('password', Cookie::get('admin_password'))
                ->find();
                
            if ($admin) {
                Session::set('admin', $admin['id']);
                Session::set('admin_cate_id', $admin['admin_cate_id']);
                
                // 获取自定义后台首页路由
                $config = Db::name('config')->where('name', 'admin_route_settings')->find();
                $homeRoute = '/admin/index/index';
                
                if ($config && !empty($config['value'])) {
                    $routeSettings = json_decode($config['value'], true);
                    if (!empty($routeSettings) && isset($routeSettings['admin_home_route'])) {
                        $homeRoute = $routeSettings['admin_home_route'];
                        // 确保路由格式正确
                        if (strpos($homeRoute, '/admin/') !== 0) {
                            $homeRoute = '/admin/index/index';
                        }
                    }
                }
                
                return redirect($homeRoute);
            }
        }
        
        return View::fetch();
    }
    
    /**
     * 处理登录提交
     */
    public function checkLogin()
    {
        if (!$this->request->isPost()) {
            return json(['code' => 0, 'msg' => '非法请求']);
        }
        
        $data = $this->request->post();
        
        // 开发调试：记录提交的数据
        \think\facade\Log::record('登录提交数据: ' . json_encode($data, JSON_UNESCAPED_UNICODE), 'info');
        
        // 先验证验证码
        if (!isset($data['captcha']) || empty($data['captcha'])) {
            return json(['code' => 0, 'msg' => '验证码不能为空']);
        }
        
        if (!captcha_check($data['captcha'])) {
            return json(['code' => 0, 'msg' => '验证码错误']);
        }
        
        // 基本数据验证
        if (!isset($data['name']) || empty($data['name'])) {
            return json(['code' => 0, 'msg' => '用户名不能为空']);
        }
        
        if (!isset($data['password']) || empty($data['password'])) {
            return json(['code' => 0, 'msg' => '密码不能为空']);
        }
        
        $username = $data['name'];
        $password = $data['password'];
        $remember = isset($data['remember']) ? $data['remember'] : 0;
        
        // 验证用户名密码
        $admin = Db::name('admin')->where('name', $username)->find();
        if (!$admin) {
            return json(['code' => 0, 'msg' => '用户名不存在']);
        }
        
        // 检查账号状态，除了超级管理员外，其他用户被禁用时不能登录
        if ($admin['id'] != 1 && $admin['status'] == 0) {
            return json(['code' => 0, 'msg' => '账号已被禁用，请联系管理员']);
        }
        
        // 记录密码信息
        $md5Password = md5($password);
        $dbPassword = $admin['password'];
        
        \think\facade\Log::record("密码对比: 输入=[{$password}], MD5=[{$md5Password}], 数据库=[{$dbPassword}]", 'info');
        
        // 验证密码
        if ($md5Password != $dbPassword) {
            return json(['code' => 0, 'msg' => '密码错误']);
        }
        
        // 记住登录状态
        if ($remember) {
            Cookie::set('admin_username', $username, 7 * 24 * 60 * 60);
            Cookie::set('admin_password', $md5Password, 7 * 24 * 60 * 60);
        }
        
        // 更新登录信息
        Db::name('admin')->where('id', $admin['id'])->update([
            'login_time' => time(),
            'login_ip' => $this->request->ip()
        ]);
        
        // 设置session
        Session::set('admin', $admin['id']);
        Session::set('admin_cate_id', $admin['admin_cate_id']);
        
        // 记录日志
        try {
            Db::name('admin_log')->insert([
                'admin_id' => $admin['id'],
                'admin_menu_id' => 0,
                'ip' => $this->request->ip(),
                'operation_id' => '登录系统',
                'create_time' => time()
            ]);
        } catch (\Exception $e) {
            // 记录日志失败不影响登录
        }
        
        // 获取自定义后台首页路由
        $config = Db::name('config')->where('name', 'admin_route_settings')->find();
        $homeRoute = '/admin/index/index';
        
        if ($config && !empty($config['value'])) {
            $routeSettings = json_decode($config['value'], true);
            if (!empty($routeSettings) && isset($routeSettings['admin_home_route'])) {
                $homeRoute = $routeSettings['admin_home_route'];
                // 确保路由格式正确
                if (strpos($homeRoute, '/admin/') !== 0) {
                    $homeRoute = '/admin/index/index';
                }
            }
        }
        
        // 使用自定义的后台首页URL
        return json(['code' => 1, 'msg' => '登录成功', 'url' => $homeRoute]);
    }
    
    /**
     * 退出登录
     */
    public function logout()
    {
        // 清除session和cookie
        Session::clear();
        Cookie::delete('admin_username');
        Cookie::delete('admin_password');
        
        // 获取自定义登录路径
        $loginPath = '/admin/login/index';
        $config = Db::name('config')->where('name', 'admin_route_settings')->find();
        
        if ($config && !empty($config['value'])) {
            $routeSettings = json_decode($config['value'], true);
            if (!empty($routeSettings) && isset($routeSettings['login_routes']) && !empty($routeSettings['login_routes'])) {
                // 使用第一个自定义路径
                $loginPath = $routeSettings['login_routes'][0];
            }
        }
        
        // 使用JavaScript重定向，避免ThinkPHP重定向失效
        echo '<script>window.location.href="' . $loginPath . '";</script>';
        exit;
    }
}