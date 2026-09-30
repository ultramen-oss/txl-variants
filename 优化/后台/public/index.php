<?php
// [ 应用入口文件 ]
namespace think;

// 判断是否为首页访问
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
if ($requestUri == '/' || $requestUri == '') {
    // 包含404页面
    include __DIR__ . '/../app/index/view/error/page404.html';
    exit;
}

// 检查是否需要进行自定义路由重定向
$currentPath = trim(parse_url($requestUri, PHP_URL_PATH), '/');

// 判断是否是访问后台登录
if ($currentPath === 'admin/login') {
    // 允许直接访问登录页面，不做任何处理
    // 继续处理请求
    require __DIR__ . '/../vendor/autoload.php';
    define('APP_PATH', __DIR__ . '/../app/');
    $http = (new App())->http;
    $response = $http->run();
    $response->send();
    $http->end($response);
    exit;
}

// 优先尝试从缓存文件中读取路由设置
$cacheFile = __DIR__ . '/../runtime/cache/route_settings.php';
$routeSettings = null;

if (file_exists($cacheFile)) {
    $routeSettings = include $cacheFile;
}

// 如果缓存文件不存在或读取失败，直接加载框架继续处理
if (empty($routeSettings) || !isset($routeSettings['login_routes'])) {
    // 继续处理请求
    require __DIR__ . '/../vendor/autoload.php';
    define('APP_PATH', __DIR__ . '/../app/');
    $http = (new App())->http;
    $response = $http->run();
    $response->send();
    $http->end($response);
    exit;
}

// 检查是否为自定义登录路由
$isCustomLoginRoute = false;
foreach ($routeSettings['login_routes'] as $route) {
    $routePath = trim($route, '/');
    if ($currentPath === $routePath) {
        $isCustomLoginRoute = true;
        
        // 设置一个加密的临时访问令牌参数
        $authToken = md5(uniqid() . time() . 'uxwnet_auth');
        
        // 重定向到登录页面并带上令牌
        header('Location: /admin/login?_auth=' . $authToken);
        exit;
    }
}

// 检查是否为admin路径
if ($currentPath === 'admin') {
    // 如果/admin路径在允许的路由中，则允许访问
    $allowed = false;
    foreach ($routeSettings['login_routes'] as $route) {
        $routePath = trim($route, '/');
        if ($routePath === 'admin') {
            $allowed = true;
            break;
        }
    }
    
    // 如果/admin路径不在允许的路由中，返回404页面
    if (!$allowed) {
        include __DIR__ . '/../app/index/view/error/page404.html';
        exit;
    }
}

// 继续处理请求
require __DIR__ . '/../vendor/autoload.php';

// 定义应用目录
define('APP_PATH', __DIR__ . '/../app/');

// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->run();

$response->send();

$http->end($response);
