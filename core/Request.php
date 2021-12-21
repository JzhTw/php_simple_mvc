<?php
namespace app\core;

class Request
{
  // 取得位子並且進行網址後多於參數處理
  public function getPath()
  {
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    $position = strpos($path, '?'); // 取得問號前
    if ($position === false) {
      return $path;
    }
    return substr($path, 0, $position); // 往後取到最後一個,往前取到問號之前
  }

  public function getMethod()
  {
    // 全部轉小寫strtolower
    return strtolower($_SERVER['REQUEST_METHOD']);
  }
} 