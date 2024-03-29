<?php
require_once 'autoload.php';
ini_set('display_errors', 'on');
ini_set('display_startup_errors', 'on');

!defined('BASE_PATH') && define('BASE_PATH', __DIR__);
ini_set('date.timezone','Asia/Shanghai'); // 时间默认东8区

// 使用 1001 端口时，Fatal error: Uncaught Swoole\Exception: failed to listen server port[0.0.0.0:1001], Error: Permission denied[13]
// 因为 Linux 和 Mac 中，1024 及以下端口只有超级管理员有使用权限，因此可将端口改为 9501 即可（ host 默认是 127.0.0.1 ，代表监听本地地址，这里顺便更改为 0.0.0.0 ，表示监听所有地址）。
$http = new Swoole\Http\Server('0.0.0.0', 9501);

//设置异步任务的工作进程数量
$http->set([
  'task_worker_num' => 4
]);

// HTTP client Request
$http->on('Request', function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) use ($http) {
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
  // TODO MAC环境下大小写不敏感，Linux系统上大小写敏感，需要增加兼容
  (new $controller($request, $response, $http))->$action();
});

//处理异步任务(此回调函数在task进程中执行)
$http->on('Task', function (\Swoole\Server $serv, $task_id, $reactor_id, $data) {
  // echo date('Y-m-d H:i:s') . " New AsyncTask[id={$task_id}]" . PHP_EOL;
  $data->handler();
  //返回任务执行的结果
  $serv->finish($data);
});

//处理异步任务的结果(此回调函数在worker进程中执行)
$http->on('Finish', function ($serv, $task_id, $data) {
  $data->finish();
  // echo date('Y-m-d H:i:s') . " AsyncTask[id={$task_id}] Finish" . PHP_EOL;
});


$http->start();