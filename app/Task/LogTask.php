<?php

namespace app\Task;

class LogTask extends TaskBase {
  public function handler()
  {
    $log_line = json_encode($this->data) . "\n";
    self::writeFile($this->getLogFilePath(), $log_line, FILE_APPEND);
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
    return file_put_contents($filename, $data, $flags);
  }

  protected function getLogFilePath()
  {
    $folder_path = BASE_PATH . '/runtime/access_logs/';
    return $folder_path . date('Y-m-d', time()) . '/' . date('H') . '.log';
  }
}