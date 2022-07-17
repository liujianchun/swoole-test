<?php

class TestController extends HttpBaseController {

  public function swoole()
  {
    $data = [
      'status' => 'success',
      'timestamp' => time(),
      'data' => [
        'php' => 'swoole',
      ]
    ];
    $this->writeJsonResponse($data);
  }

}