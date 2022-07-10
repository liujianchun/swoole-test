<?php
namespace app\Task;

abstract class TaskBase {
  protected $data;

  public function __construct($data)
  {
    $this->data = $data;
  }
}