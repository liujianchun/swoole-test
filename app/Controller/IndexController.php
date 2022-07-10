<?php

use app\Task\LogTask;
class IndexController extends HttpBaseController {
  public function index()
  {
    $data = [
      'status' => 'success',
      'timestamp' => time(),
      'data' => [
        'region' => 'sh',
        'query' => $this->request->get
      ]
    ];
    $log_task = new LogTask($this->request->get);
    $this->server->task($log_task);
    $this->writeJsonResponse($data);
  }
}