<?php


// index应用路由配置

use think\facade\Route;

// 首页路由
Route::get('/', 'index/index');
Route::get('/index', 'index/index');