<?php
$serv = new Swoole\Server('0.0.0.0', 9503);

//设置异步任务的工作进程数量
$serv->set([
  'task_worker_num' => 4
]);

//此回调函数在worker进程中执行
$serv->on('Receive', function(\Swoole\Server $serv, $fd, $reactor_id, $data) {
  //投递异步任务
  $task_id = $serv->task($data);
  echo date('Y-m-d H:i:s') . " Dispatch AsyncTask: id={$task_id}\n";
  $serv->send($fd, "Server: {$data}");
});

//处理异步任务(此回调函数在task进程中执行)
$serv->on('Task', function (\Swoole\Server $serv, $task_id, $reactor_id, $data) {
  sleep(10); // 测试延迟执行task
  echo date('Y-m-d H:i:s') . " New AsyncTask[id={$task_id}]" . PHP_EOL;
  //返回任务执行的结果
  $serv->finish("{$data} -> OK");
});

//处理异步任务的结果(此回调函数在worker进程中执行)
$serv->on('Finish', function (\Swoole\Server $serv, $task_id, $data) {
  echo date('Y-m-d H:i:s') . " AsyncTask[{$task_id}] Finish: {$data}" . PHP_EOL;
});

$serv->start();