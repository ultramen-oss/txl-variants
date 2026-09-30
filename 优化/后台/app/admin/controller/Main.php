<?php
namespace app\admin\controller;

use think\facade\Db;
use think\facade\View;

class Main extends Permissions
{
    /**
     * 控制台首页
     */
    public function index()
    {
        // 系统信息
        $info = [
            'php' => PHP_VERSION,
            'os' => PHP_OS,
            'server' => $_SERVER['SERVER_SOFTWARE'],
            'upload_size' => ini_get('upload_max_filesize'),
            'execution_time' => ini_get('max_execution_time').'秒',
            'disk' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'MB'
        ];
        View::assign('info', $info);

        // 网站统计信息
        $web = [
            'user_num' => Db::name('admin')->count(),
            'admin_cate' => Db::name('admin_cate')->count(),
            'mobile' => Db::name('mobile')->count(),
            'mobileuser' => Db::name('user')->count(),
            'smsnum' => Db::name('content')->count(),
            'img_count' => Db::name('img')->count()
        ];
        
        // 获取IP黑名单数量
        $ip_ban = Db::name('webconfig')->where('web', 'web')->value('black_ip');
        $web['ip_ban'] = empty($ip_ban) ? 0 : count(explode(',', $ip_ban));
        
        // 今日数据统计
        $today_start = strtotime(date('Y-m-d'));
        $today_end = $today_start + 86400;
        $today = [
            'user' => Db::name('user')
                ->where('login_time', 'between', [$today_start, $today_end])
                ->count(),
            'mobile' => Db::name('mobile')
                ->where('addtime', 'between', [$today_start, $today_end])
                ->count(),
            'sms' => Db::name('content')
                ->where('addtime', 'between', [$today_start, $today_end])
                ->count(),
            'img' => Db::name('img')
                ->where('addtime', 'between', [$today_start, $today_end])
                ->count()
        ];
        
        View::assign([
            'web' => $web,
            'today' => $today
        ]);

        return View::fetch();
    }
}