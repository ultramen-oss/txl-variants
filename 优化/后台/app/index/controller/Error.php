<?php


namespace app\index\controller;

use app\BaseController;
use think\facade\View;
use think\Response;

class Error extends BaseController
{
    public function __call($method, $args)
    {
        return $this->page404();
    }

    /**
     * 404页面
     */
    public function page404()
    {
        $content = View::fetch('error/page404');
        return Response::create($content, 'html', 404);
    }
}