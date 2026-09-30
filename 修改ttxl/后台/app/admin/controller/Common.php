<?php

namespace app\admin\controller;

use think\facade\Cache;
use think\facade\Db;
use think\facade\Cookie;
use think\facade\Session;
use think\facade\View;

class Common extends Permissions
{
    /**
     * 初始化方法
     */
    protected function initialize()
    {
        parent::initialize();
    }

    /**
     * 清除全部缓存
     * @return mixed
     */
    public function clear()
    {
        if(false == Cache::clear()) {
            return $this->error('清除缓存失败');
        } else {
            return $this->success('清除缓存成功');
        }
    }

    /**
     * 图片上传方法
     * @param string $module 模块名
     * @param string $use 用途
     * @return \think\Response\Json
     */
    public function upload($module='admin', $use='admin_thumb')
    {
        // 记录请求信息
        \think\facade\Log::info('上传请求开始 - 模块: ' . $module . ', 用途: ' . $use);
        
        // 检查是否有上传文件
        if(!$this->request->file('file')){
            $res['code'] = 0;
            $res['msg'] = '没有上传文件';
            \think\facade\Log::info('上传失败: 没有上传文件');
            return json($res);
        }
        
        $file = $this->request->file('file');
        // 记录文件信息
        \think\facade\Log::info('收到文件: ' . $file->getOriginalName() . ', 大小: ' . $file->getSize() . ' 字节');
        
        $module = $this->request->param('module', $module); // 模块
        
        try {
            // 确保上传目录存在并可写
            $uploadPath = app()->getRootPath() . 'public/storage/' . $module . '/' . $use;
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath, 0777, true);
                \think\facade\Log::info('创建上传目录: ' . $uploadPath);
            }
            
            // 验证文件
            $fileExt = 'jpg,jpeg,png,gif,bmp,webp';
            $fileSize = 20480000; // 约20MB
            
            // 检查文件类型
            $extension = strtolower($file->getOriginalExtension());
            if (!in_array($extension, explode(',', $fileExt))) {
                $res['code'] = 0;
                $res['msg'] = '上传失败：不支持的文件类型';
                return json($res);
            }
            
            // 检查文件大小
            if ($file->getSize() > $fileSize) {
                $res['code'] = 0;
                $res['msg'] = '上传失败：文件过大';
                return json($res);
            }
            
            // 生成文件名称
            $filename = md5(uniqid(microtime(true), true)) . '.' . $extension;
            
            // 创建子目录按日期分类
            $subdir = date('Ymd');
            $fullPath = $uploadPath . '/' . $subdir;
            if (!is_dir($fullPath)) {
                mkdir($fullPath, 0777, true);
            }
            
            // 移动文件
            $file->move($fullPath, $filename);
            
            // 构建文件路径
            $filepath = '/storage/' . $module . '/' . $use . '/' . $subdir . '/' . $filename;
            \think\facade\Log::info('文件保存成功: ' . $filepath);
            
            // 记录日志到系统日志而不是数据库
            \think\facade\Log::info('上传文件：' . $file->getOriginalName());
            
            // 返回结果
            $res['id'] = 0; // 为兼容前端，仍然返回id字段
            $res['src'] = $filepath;
            $res['code'] = 2;
            
            return json($res);
        } catch (\Exception $e) {
            // 记录详细错误日志
            \think\facade\Log::error('文件上传异常：' . $e->getMessage() . ' ' . $e->getFile() . ':' . $e->getLine());
            // 返回错误信息
            $res['code'] = 0;
            $res['msg'] = '上传失败：' . $e->getMessage();
            return json($res);
        }
    }

    /**
     * 登录 - 重定向到登录页面
     * @return mixed
     */
    public function login()
    {
        // 重定向到login控制器的index方法
        return redirect((string)url('admin/login/index'));
    }

    /**
     * 管理员退出，清除名字为admin的session
     * @return mixed
     */
    public function logout()
    {
        Session::delete('admin');
        Session::delete('admin_cate_id');
        if(Session::has('admin') || Session::has('admin_cate_id')) {
            return $this->error('退出失败');
        } else {
            return $this->success('正在退出...', (string)url('admin/login/index'));
        }
    }
    
    /**
     * 添加日志的方法
     * @param string $title 日志标题
     * @param string $content 日志内容
     */
    protected function addLog($title = '', $content = '')
    {
        // 获取当前请求的URL和信息
        $admin_id = Session::get('admin');
        $ip = $this->request->ip();
        
        try {
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
            \think\facade\Log::error('记录日志失败: ' . $e->getMessage());
        }
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
}