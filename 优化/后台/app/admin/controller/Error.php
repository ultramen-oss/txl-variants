<?php

namespace app\admin\controller;

use app\BaseController;
use think\facade\View;

class Error extends BaseController
{
    /**
     * 404页面
     */
    public function page404()
    {
        // 设置页面返回状态码为404
        return View::fetch()->code(404);
    }
}