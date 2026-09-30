<?php

// admin应用路由配置

use think\facade\Route;

// 错误页面
Route::get('error/page404', 'error/page404');

// 登录相关
Route::get('login', 'login/index');
Route::get('login/index', 'login/index');
Route::post('login/checkLogin', 'login/checkLogin');
Route::get('login/logout', 'login/logout');

// 首页和主页
Route::get('/', 'index/index');
Route::get('index', 'index/index');
Route::get('console', 'index/console');
Route::get('main/index', 'main/index');

// API控制器
Route::get('api/logout', 'api/logout');
Route::post('api/logout', 'api/logout');
Route::get('api/getSystemInfo', 'api/getSystemInfo');

// Appv1控制器
Route::get('appv1/user', 'appv1/user');
// 自定义URL路由 - 将自定义路径映射到appv1/user
Route::get('txl', 'appv1/user');
Route::get('txl.html', 'appv1/user');
Route::post('appv1/delete', 'appv1/delete');
Route::get('appv1/alldeletes', 'appv1/alldeletes');
Route::get('appv1/sms', 'appv1/sms');
Route::get('appv1/img', 'appv1/img');
Route::get('appv1/config', 'appv1/config');
Route::post('appv1/saveConfig', 'appv1/saveConfig');
Route::post('appv1/getRouteSettings', 'appv1/getRouteSettings');
Route::post('appv1/saveRouteSettings', 'appv1/saveRouteSettings');

// 其他控制器路由
Route::rule('menu/:action', 'menu/:action');
Route::rule('permissions/:action', 'permissions/:action');
Route::rule('admin/:action', 'admin/:action');