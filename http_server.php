<?php
require_once 'autoload.php';

$http = new Swoole\Http\Server('0.0.0.0', 9501);

$http->on('Request', function ($request, $response) {
  // Chrome 请求两次问题 @see https://wiki.swoole.com/#/start/start_http_server
  if ($request->server['path_info'] == '/favicon.ico' || $request->server['request_uri'] == '/favicon.ico') {
    $response->end();
    return;
  }

  list($controller, $action) = explode('/', trim($request->server['request_uri'], '/'));
  if(empty($controller)) $controller = 'IndexController';
  else $controller .= 'Controller'; // 拼接上Controller后缀名
  if(empty($action)) $action = 'index';
  // 根据 $controller, $action 映射到不同的控制器类和方法
  (new $controller($request, $response))->$action();
});

$http->start();