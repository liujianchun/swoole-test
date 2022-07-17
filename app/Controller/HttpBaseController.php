<?php

use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;
use app\Task\LogTask;

abstract class HttpBaseController {
  /**
   * @var Request
   */
  protected $request;

  /**
   * @var Response
   */
  protected $response;

  /**
   * @var Server
   */
  protected $server;

  public function __construct($request, $response, $server)
  {
    $this->request = $request;
    $response->header('Content-Type', 'application/json; charset=utf-8');
    $this->response = $response;
    $this->server = $server;

    // 请求信息写入日志文件
    $task_data = [
      'request_uri' => $this->request->server['request_uri'],
      'get' => $this->request->get,
      'post' => $this->request->post,
    ];
    $log_task = new LogTask($task_data);
    $this->server->task($log_task);
  }

  public function writeJsonResponse($data)
  {
    $this->response->end(json_encode($data));
  }
}