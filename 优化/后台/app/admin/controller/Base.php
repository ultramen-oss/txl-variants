<?php

namespace app\admin\controller;

use app\BaseController;
use think\App;
use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\facade\Cookie;

class Base extends BaseController
{
    // 初始化
    protected function initialize()
    {
        parent::initialize();
        
        // 验证用户是否登录
        $this->checkLogin();
        
        // 定期检查账号状态，确保被禁用的账号无法继续使用系统
        $this->checkAdminStatus();
        
        // 获取当前控制器名称
        $controller = strtolower($this->request->controller());
        
        // 定义模板变量
        View::assign([
            'controller' => $controller,
            'action' => strtolower($this->request->action())
        ]);
    }
    
    // 检查用户是否登录
    protected function checkLogin()
    {
        // 检查是否已经登录
        if (!Session::has('admin')) {
            // 未登录，重定向到登录页面
            return redirect((string)url('admin/login/index'))->send();
        }
        
        // 获取管理员信息
        $admin = Db::name('admin')->where('id', Session::get('admin'))->find();
        if (!$admin) {
            // 用户不存在，注销登录
            Session::clear();
            Cookie::clear();
            return redirect((string)url('admin/login/index'))->send();
        }
        
        // 向模板分配管理员信息
        View::assign('admin', $admin);
    }
    
    // 检查管理员账号状态
    protected function checkAdminStatus()
    {
        // 如果已登录
        if (Session::has('admin')) {
            $admin_id = Session::get('admin');
            
            // 每次请求都从数据库重新获取管理员信息，确保状态是最新的
            $admin = Db::name('admin')->where('id', $admin_id)->find();
            
            // 如果管理员不是超级管理员且状态为禁用(0)，则强制登出
            if ($admin && $admin['id'] != 1 && $admin['status'] == 0) {
                // 记录被强制登出的日志
                try {
                    Db::name('admin_log')->insert([
                        'admin_id' => $admin_id,
                        'admin_menu_id' => 0,
                        'operation_id' => '账号被禁用，强制登出',
                        'ip' => $this->request->ip(),
                        'create_time' => time()
                    ]);
                } catch (\Exception $e) {
                    // 记录日志失败不影响登出流程
                }
                
                // 清除会话
                Session::clear();
                Cookie::delete('admin_username');
                Cookie::delete('admin_password');
                
                // 检查是否为AJAX请求
                if ($this->request->isAjax()) {
                    // 返回JSON响应通知客户端
                    header('Content-Type: application/json');
                    echo json_encode([
                        'code' => 401,
                        'msg' => '您的账号已被禁用，请联系管理员',
                        'force_logout' => true
                    ]);
                    exit;
                } else {
                    // 使用JavaScript弹窗提示后重定向到登录页
                    echo '<script>alert("您的账号已被禁用，请联系管理员");window.location.href="'.(string)url('admin/login/index').'";</script>';
                    exit;
                }
            }
        }
    }
    
    // 渲染模板输出
    protected function fetch($template = '', $vars = [], $config = [])
    {
        return View::fetch($template, $vars, $config);
    }
}