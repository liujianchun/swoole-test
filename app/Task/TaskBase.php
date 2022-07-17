<?php
namespace app\Task;

abstract class TaskBase {
  protected $data;

  public function __construct($data)
  {
    $this->data = $data;
  }

  /**
   * 异步任务执行函数
   */
  public function handler()
  {}

  /**
   * 异步任务执行结束回调
   */
  public function finish()
  {}
}