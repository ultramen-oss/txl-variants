<?php
use think\facade\Route;
use think\facade\Db;

// 根路由 - 交由默认Index应用处理
// Route::get('/', 'index/index/index');

// 管理后台路由 - 从数据库获取自定义管理后台路由
try {
    // 优先从数据库读取
    $config = Db::name('config')->where('name', 'admin_route_settings')->find();
    
    if ($config && !empty($config['value'])) {
        $routeSettings = json_decode($config['value'], true);
        
        // 保存到缓存文件，方便入口文件读取
        $cacheDir = app()->getRuntimePath() . 'cache/';
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        $cacheContent = "<?php\nreturn " . var_export($routeSettings, true) . ";\n";
        file_put_contents($cacheDir . 'route_settings.php', $cacheContent);
        
        // 注册路由
        if (!empty($routeSettings) && isset($routeSettings['login_routes']) && is_array($routeSettings['login_routes'])) {
            foreach ($routeSettings['login_routes'] as $route) {
                // 所有自定义路由都添加重定向规则
                Route::get($route, function () {
                    return redirect('/admin/login');
                });
            }
        }
    } else {
        // 如果数据库中没有设置，使用默认配置
        Route::get('/admin', function () {
            return redirect('/admin/login');
        });
    }
} catch (\Exception $e) {
    // 如果发生异常，使用缓存文件
    $cacheFile = __DIR__ . '/../runtime/cache/route_settings.php';
    if (file_exists($cacheFile)) {
        $routeSettings = include $cacheFile;
        if (!empty($routeSettings) && isset($routeSettings['login_routes']) && is_array($routeSettings['login_routes'])) {
            foreach ($routeSettings['login_routes'] as $route) {
                // 所有自定义路由都添加重定向规则
                Route::get($route, function () {
                    return redirect('/admin/login');
                });
            }
        }
    } else {
        // 如果缓存文件也不存在，使用默认配置
        Route::get('/admin', function () {
            return redirect('/admin/login');
        });
    }
}

// 注意：其他应用的路由配置已移至各自的应用路由配置文件中
