<?php

use Swoole\Http\Request;
use Swoole\Http\Response;

abstract class HttpBaseController {
  /**
   * @var Request
   */
  protected $request;

  /**
   * @var Response
   */
  protected $response;

  public function __construct($request, $response)
  {
    $this->request = $request;
    $response->header('Content-Type', 'application/json; charset=utf-8');
    $this->response = $response;
  }

  public function writeJsonResponse($data)
  {
    $this->response->end(json_encode($data));
  }
}