<?php


namespace app\index\controller;

use app\BaseController;
use think\facade\View;
use think\Response;

class Index extends BaseController
{
    /**
     * 默认404页面
     */
    public function index()
    {
        // 正确方式设置404状态码
        $content = View::fetch('error/page404');
        return Response::create($content, 'html', 404);
    }
}