<?php

declare (strict_types = 1);

namespace app\controller;

use think\Request;
use think\facade\View;
use think\facade\Db;

class Debug
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $request)
    {
        // 获取所有请求数据
        $get = $request->get();
        $post = $request->post();
        $param = $request->param();
        
        // 检查是否有文件上传
        $files = $request->file();
        
        // 输出所有数据
        return json([
            'get' => $get,
            'post' => $post,
            'param' => $param,
            'files' => !empty($files) ? 'Has files' : 'No files',
            'files_info' => $files,
            'server' => $_SERVER,
            'headers' => $request->header()
        ]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }

    public function config()
    {
        // 读取配置数据
        $config = Db::name('config')->where('name', 'app_frontend')->find();
        
        if ($config) {
            $configData = json_decode($config['value'], true);
            return json([
                'config' => $config,
                'parsed' => $configData,
                'bg_images' => $configData['bg_images'] ?? [],
                'urls' => $configData['urls'] ?? []
            ]);
        }
        
        return json(['error' => 'Config not found']);
    }
    
    public function testSave()
    {
        // 模拟保存一些数据
        $configData = [
            'bg_count' => 3,
            'url_count' => 1,
            'bg_images' => [
                '/storage/uploads/bg_images/20250428/019ad2918e14c27a9b8a705d7c98f296.jpg',
                '/storage/uploads/bg_images/20250428/93dbeb4ae98970509ea0bfba16f4c95c.jpg',
                '/storage/uploads/bg_images/20250428/a3e0ba7ecd210fa3eaf90e24cf85a5e8.jpg'
            ],
            'urls' => ['https://www.baidu.com']
        ];
        
        // 保存到数据库
        $config = Db::name('config')->where('name', 'app_frontend')->find();
        
        if ($config) {
            // 更新配置
            $result = Db::name('config')->where('id', $config['id'])->update([
                'value' => json_encode($configData, JSON_UNESCAPED_UNICODE),
                'update_time' => time()
            ]);
        } else {
            // 创建配置
            $result = Db::name('config')->insert([
                'name' => 'app_frontend',
                'value' => json_encode($configData, JSON_UNESCAPED_UNICODE),
                'create_time' => time(),
                'update_time' => time()
            ]);
        }
        
        return json([
            'success' => true,
            'message' => '配置已保存',
            'result' => $result,
            'data' => $configData
        ]);
    }
}