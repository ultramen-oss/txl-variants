<?php
// +----------------------------------------------------------------------
// | API控制器
// +----------------------------------------------------------------------

namespace app\admin\controller;

use think\facade\Db;
use think\facade\Cookie;
use think\facade\Session;
use think\facade\Request;

class Api extends Permissions
{
    /**
     * 初始化方法
     */
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 错误输出方法，解决权限检查时调用的error方法未定义问题
     * @param string $msg 错误信息
     * @param string $url 跳转URL
     * @param int $wait 等待时间
     * @return \think\response\Json
     */
    public function error($msg = '', $url = null, $data = [])
    {
        $result = [
            'code' => 0,
            'msg'  => $msg,
        ];
        
        if (!empty($data)) {
            $result['data'] = $data;
        }
        
        if ($url) {
            $result['url'] = (string)$url;
        }
        
        return json($result);
    }

    /**
     * 成功输出方法
     * @param string $msg 成功信息
     * @param string $url 跳转URL
     * @param array $data 返回数据
     * @return \think\response\Json
     */
    public function success($msg = '', $url = null, $data = [])
    {
        $result = [
            'code' => 1,
            'msg'  => $msg,
        ];
        
        if (!empty($data)) {
            $result['data'] = $data;
        }
        
        if ($url) {
            $result['url'] = (string)$url;
        }
        
        return json($result);
    }

    /**
     * 退出登录API接口
     * @return \think\response\Json
     */
    public function logout()
    {
        // 简化退出登录流程，不再依赖配置表
        try {
            // 记录日志（使用全局函数）
            \addlog('退出登录', '管理员退出了登录');
            
            // 删除session
            Session::delete('admin');
            Session::delete('admin_cate_id');
            Session::clear();
            
            // 删除cookie
            Cookie::delete('admin_username');
            Cookie::delete('admin_password');
            
            // 检查是否成功删除
            if(Session::has('admin') || Session::has('admin_cate_id')) {
                return json(['code' => 0, 'msg' => '退出失败']);
            } else {
                return json(['code' => 1, 'msg' => '退出成功', 'url' => '/admin/login/index']);
            }
        } catch(\Exception $e) {
            // 出现异常也尝试删除session并返回成功
            Session::delete('admin');
            Session::delete('admin_cate_id');
            Session::clear();
            Cookie::delete('admin_username');
            Cookie::delete('admin_password');
            return json(['code' => 1, 'msg' => '退出成功', 'url' => '/admin/login/index']);
        }
    }
    
    /**
     * 获取系统状态信息
     */
    public function getSystemInfo()
    {
        // 系统信息
        $info = [
            'os' => PHP_OS,
            'php' => PHP_VERSION,
            'upload_max' => ini_get('upload_max_filesize'),
            'max_execution_time' => ini_get('max_execution_time').'秒',
        ];
        
        // 数据统计
        $stats = [
            'mobile_count' => Db::name('mobile')->count(),
            'user_count' => Db::name('user')->count(),
            'sms_count' => Db::name('content')->count(),
            'img_count' => Db::name('img')->count()
        ];
        
        return json([
            'code' => 1,
            'msg' => '获取成功',
            'data' => [
                'system' => $info,
                'stats' => $stats
            ]
        ]);
    }

    /**
     * 检查账号状态接口
     * 用于account-status-monitor.js检查账号是否被禁用
     * @return \think\response\Json
     */
    public function checkStatus()
    {
        $userId = Request::param('userId', 0);
        
        // 检查是否提供了用户ID
        if (empty($userId)) {
            return json([
                'code' => 0,
                'msg' => '参数错误',
                'disabled' => false
            ]);
        }
        
        // 获取当前登录的管理员信息
        $adminInfo = Session::get('admin');
        
        // 检查是否登录
        if (empty($adminInfo)) {
            return json([
                'code' => 0,
                'msg' => '未登录',
                'disabled' => true
            ]);
        }
        
        // 如果提供的用户ID与当前登录的用户ID不匹配，返回错误
        if ((string)$userId !== (string)$adminInfo['id']) {
            return json([
                'code' => 0,
                'msg' => '无效请求',
                'disabled' => false
            ]);
        }
        
        // 查询用户状态
        try {
            $user = Db::name('admin')->where('id', $userId)->find();
            
            // 如果找不到用户或者用户被禁用
            if (empty($user) || $user['status'] != 1) {
                return json([
                    'code' => 0,
                    'msg' => '账号已被禁用',
                    'disabled' => true
                ]);
            }
            
            // 用户存在且正常
            return json([
                'code' => 0,
                'msg' => '账号状态正常',
                'disabled' => false
            ]);
        } catch (\Exception $e) {
            // 出现异常，默认返回正常状态，避免误判
            return json([
                'code' => 0,
                'msg' => '查询失败',
                'disabled' => false
            ]);
        }
    }
}