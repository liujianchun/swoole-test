<?php
class Autoloader {

  public static function myAutoload($name)
  {
    $class_path = str_replace('\\',DIRECTORY_SEPARATOR, $name);
    $files[] = __DIR__ . '/app/Controller/' . $class_path . '.php';
    $files[] = __DIR__ . '/' . $class_path . '.php';
    foreach($files as $file) {
      if(file_exists($file)) {
        require_once($file);
        if(class_exists($name, false)) {
          continue;
        }
      }
    }
    return true;
  }
}

spl_autoload_register('Autoloader::myAutoload');