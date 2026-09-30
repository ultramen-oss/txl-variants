<?php

// +----------------------------------------------------------------------
// | 路由设置
// +----------------------------------------------------------------------

return [
    // URL普通方式参数 用于自动生成
    'url_common_param'       => true,
    // 是否开启路由延迟解析
    'url_lazy_route'         => false,
    // 是否强制使用路由
    'url_route_must'         => false,
    // 合并路由规则
    'route_rule_merge'       => false,
    // 路由是否完全匹配
    'route_complete_match'   => false,
    // 是否开启路由缓存
    'route_check_cache'      => false,
    // 路由缓存连接参数
    'route_cache_option'     => [],
    // 路由缓存Key
    'route_check_cache_key'  => '',
    // 默认的路由变量规则
    'default_route_pattern'  => '[\w\.]+',
];