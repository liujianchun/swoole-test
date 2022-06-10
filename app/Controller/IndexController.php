<?php

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
    $this->writeJsonResponse($data);
  }
}