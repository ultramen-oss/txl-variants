<?php

// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

return [
    // 应用地址
    'app_host'         => env('app.host', ''),
    // 应用的命名空间
    'app_namespace'    => '',
    // 是否启用路由
    'with_route'       => true,
    // 是否启用多应用模式
    'auto_multi_app'   => true,
    // 默认应用
    'default_app'      => 'index',
    // 默认时区
    'default_timezone' => 'Asia/Shanghai',

    // 应用映射（自动多应用模式有效）
    'app_map'          => [],
    // 域名绑定（自动多应用模式有效）
    'domain_bind'      => [],
    // 禁止URL访问的应用列表（自动多应用模式有效）
    'deny_app_list'    => [],

    // 异常页面的模板文件
    'exception_tmpl'   => app()->getThinkPath() . 'tpl/think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'    => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'   => false,
    
    // 自定义错误处理
    'http_exception_template' => [
        404 => app()->getAppPath() . 'index/view/error/page404.html',
    ],
    
    // 自定义配置
    'agency_id'        => 2, // 代理角色ID
    'upload_path'      => 'uploads', // 上传路径
    'site_name'        => '管理系统', // 站点名称
    'version'          => '2.0.1', // 版本号
];