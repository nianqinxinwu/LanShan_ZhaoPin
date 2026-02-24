<?php
// PHP 内置服务器路由脚本，模拟 .htaccess 的 URL 重写
$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// 如果请求的是真实存在的文件或目录，直接返回
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// 后台入口：/fSVtCuiJPW.php 开头的路径交给后台入口文件
if (preg_match('#^/fSVtCuiJPW\.php(/.*)?$#', $uri, $m)) {
    $_SERVER['PATH_INFO'] = isset($m[1]) ? $m[1] : '/';
    require __DIR__ . '/fSVtCuiJPW.php';
    return;
}

// 否则交给 index.php 处理（模拟 RewriteRule）
$_SERVER['PATH_INFO'] = $uri;
require __DIR__ . '/index.php';
