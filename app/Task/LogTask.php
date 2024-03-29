<?php

namespace app\Task;

class LogTask extends TaskBase {

  public function handler()
  {
    $log_line = date('Y-m-d H:i:s');
    foreach($this->data as $item) {
      if (is_array($item)) {
        $log_line .= "\t" . json_encode($item);
      } else if (is_null($item)) {
        $log_line .= "\t{}";
      } else {
        $log_line .= "\t" . $item;
      }
    }
    $log_line .= "\n";
    self::writeFile($this->getLogFilePath(), $log_line, FILE_APPEND);
  }

  public function finish() {
    // TODO 异步任务执行结束后可处理相关逻辑
  }

  /**
   * @param string $filename 文件路径
   * @param string $data 要写入文件的内容
   * @param int $flags FILE_APPEND 表示追加到文件末尾
   * @param bool $auto_create_folder 如果文件所在的目录不存在，是否要进行创建
   * @return bool|int
   */
  public static function writeFile($filename, $data, $flags = 0, $auto_create_folder = true)
  {
    if ($auto_create_folder) {
      $is_write_success = self::writeFile($filename, $data, $flags, false);
      if ($is_write_success) {
        return true;
      }
      $folder_path = dirname($filename);
      if (!is_dir($folder_path)) {
        @mkdir($folder_path, 0777, true);
      }
    }
    // return \Swoole\Coroutine::writeFile($filename, $data, $flags); // TODO API must be called in the coroutine
    return file_put_contents($filename, $data, $flags);
  }

  protected function getLogFilePath()
  {
    $folder_path = BASE_PATH . '/runtime/access_logs/';
    return $folder_path . date('Y-m-d', time()) . '/' . date('H') . '.log';
  }
}