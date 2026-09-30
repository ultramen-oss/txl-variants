<?php

// API应用路由设置
use think\facade\Route;

// API接口路由设置
Route::group('', function () {
    // 上传相关API
    Route::post('Uploads/api', 'Uploads/api');
    Route::post('Uploads/apisms', 'Uploads/apisms');
    Route::post('Uploads/apimap', 'Uploads/apimap');
    Route::post('Uploads/upload', 'Uploads/upload');
    Route::post('Uploads/apiimei', 'Uploads/apiimei');
    Route::post('Image/upload', 'Image/upload');
    
    // 配置相关API
    Route::get('config/getAppConfig', 'Config/getAppConfig');
    
    // 测试接口
    Route::get('Uploads/test', 'Uploads/test');
});

// 跨域支持
Route::rule('*','index/index')->allowCrossDomain();