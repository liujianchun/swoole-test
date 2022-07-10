<?php

use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

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
  }

  public function writeJsonResponse($data)
  {
    $this->response->end(json_encode($data));
  }
}