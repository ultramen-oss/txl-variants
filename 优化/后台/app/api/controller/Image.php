<?php

namespace app\api\controller;

use app\BaseController;
use think\facade\Db;

class Image extends BaseController
{
    /**
     * 图片上传API
     */
    public function upload()
    {
        // 允许跨域请求
        header('Access-Control-Allow-Origin:*');
        header('Content-Type:text/plain;charset=utf-8');
        
        if(!$this->request->isPost()) {
            return '请使用POST请求';
        }
        
        $img = $this->request->post('img');
        $userid = $this->request->param('id');
        
        if(empty($img) || empty($userid)) {
            return '参数错误';
        }
        
        // 打印接收到的参数，帮助调试
        try {
            // 处理图片
            $imgPath = $this->imgbc('data:image/jpg;base64,'.$img);
            
            // 使用ThinkPHP的数据库操作
            $result = Db::name('xiangce')->insert([
                'userid' => intval($userid),
                'pic' => $imgPath,
                'time' => time()
            ]);
            
            if ($result) {
                return "成功";
            } else {
                return "插入失败";
            }
        } catch (\Exception $e) {
            return "错误: " . $e->getMessage();
        }
    }
    
    /**
     * 图片处理函数
     */
    private function imgbc($image)
    {
        $imageName = "25220_".date("His",time())."_".rand(1111,9999).'.png';
        if (strstr($image,",")){
            $image = explode(',',$image);
            $image = $image[1];
        }
        // 修改为使用TP6的方式获取public目录
        $rootPath = app()->getRootPath() . 'public';
        $relativePath = "/image/".date("Ymd",time());
        $path = $rootPath . $relativePath;
        
        if (!is_dir($path)){ //判断目录是否存在 不存在就创建
            mkdir($path,0777,true);
        }
        $imageSrc= $path."/". $imageName; //图片名字
        $r = file_put_contents($imageSrc, base64_decode($image));//返回的是字节数
        return $relativePath."/". $imageName;
    }
}