<?php

namespace app\core;

class Router
{
  public Request $request;
  protected array $routes = [];

  public function __construct(\app\core\Request $request)
  {
    $this->request = $request;
  } 

  // ＊＊＊＊＊將所有get方法載入＊＊＊＊＊
  public function get($path, $callback)
  {
    $this->routes['get'][$path] = $callback;
  }

  public function resolve()
  {
    $path = $this->request->getPath();
    $method = $this->request->getMethod();
    //如果有此路徑
    $callback = $this->routes[$method][$path] ?? false;
    if($callback === false) {
      echo "Not found";
      exit;
    }

    if(is_string($callback)){
      return $this->renderView($callback);
    }

    echo call_user_func($callback);

  }

  public function renderView($view)
  {
    $layoutContent = $this->layoutContent();
    $viewContent = $this->renderOnlyView($view);
    // layoutContent main模板 viewContent內文 {{content}}替換成viewContent
    return str_replace('{{content}}', $viewContent, $layoutContent);
    // include_once __DIR__."/../views/$view.php";
    // include_once Application::$ROOT_DIR."/views/$view.php";
  }


  protected function layoutContent()
  {
    ob_start();
    include_once Application::$ROOT_DIR."/views/layouts/main.php";
    return ob_get_clean();
  }

  protected function renderOnlyView($view)
  {
    ob_start();
    include_once Application::$ROOT_DIR."/views/$view.php";
    return ob_get_clean();
  }
} 