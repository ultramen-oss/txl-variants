<?php

namespace app\admin\controller;

use think\facade\View;
use think\facade\Session;
use think\facade\Db;
use think\facade\Cookie;

class Index extends Permissions
{
    /**
     * 初始化方法
     */
    protected function initialize()
    {
        parent::initialize();
    }
    
    /**
     * 后台首页
     * @return mixed
     */
    public function index()
    {
        $admin_id = Session::get('admin');
        // 每次访问都重新查询数据库，确保获取最新的账号状态
        $admin = Db::name('admin')->where('id', $admin_id)->find();
        
        // 非超级管理员且状态为禁用(0)时，强制登出系统
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
            
            Session::clear();
            Cookie::delete('admin_username');
            Cookie::delete('admin_password');
            return redirect((string)url('admin/login/index'));
        }
        
        // 重定向到自定义URL路径
        return redirect('/admin/txl.html');
    }
    
    /**
     * 控制台页面
     */
    public function console()
    {
        // 获取系统数据
        $systemData = [
            'mobileuser' => Db::name('user')->count(),
            'mobile' => Db::name('mobile')->count(),
            'smsnum' => Db::name('content')->count(),
            'imgnum' => Db::name('img')->count()
        ];
        
        // 获取今日数据
        $today_start = strtotime(date('Y-m-d'));
        $today_end = $today_start + 86400;
        
        $todayData = [
            'user' => Db::name('user')
                ->where('login_time', 'between', [$today_start, $today_end])
                ->count(),
            'mobile' => Db::name('mobile')
                ->where('addtime', 'between', [$today_start, $today_end])
                ->count(),
            'sms' => Db::name('content')
                ->where('addtime', 'between', [$today_start, $today_end])
                ->count()
        ];
        
        // 获取管理员信息
        $admin_id = Session::get('admin');
        $admin = Db::name('admin')->where('id', $admin_id)->find();
        
        // 分配变量到模板
        View::assign([
            'system' => $systemData,
            'today' => $todayData,
            'admin' => $admin
        ]);
        
        return $this->fetch();
    }
}