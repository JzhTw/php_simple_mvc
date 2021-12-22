<?php

namespace app\core;

class Router
{
  public Request $request;
  protected array $routes = [];
  public Response $response;


  public function __construct(Request $request, Response $response)
  {
    $this->request = $request;
    $this->response = $response;
  } 

  // ＊＊＊＊＊將所有get方法載入＊＊＊＊＊
  public function get($path, $callback)
  {
    $this->routes['get'][$path] = $callback;
  }

  // ＊＊＊＊＊將所有post方法載入＊＊＊＊＊
  public function post($path, $callback)
  {
    $this->routes['post'][$path] = $callback;
  }


  public function resolve()
  {
    $path = $this->request->getPath();
    $method = $this->request->method();
    //如果有此路徑
    $callback = $this->routes[$method][$path] ?? false;
    if($callback === false) {
      $this->response->setStatusCode(404);
      return $this->renderView("_404");
      exit;
    }
    if(is_string($callback)){
      return $this->renderView($callback);
    }

    if(is_array($callback)) {
      $callback[0] = new $callback[0]();
    }
    return call_user_func($callback, $this->request);

  }

  public function renderView($view, $params = [])
  {
    $layoutContent = $this->layoutContent();
    $viewContent = $this->renderOnlyView($view, $params);
    return str_replace('{{content}}', $viewContent, $layoutContent);
  }

  public function renderContent($viewContent)
  {
    $layoutContent = $this->layoutContent();
    $viewContent = $this->renderOnlyView($view);
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

  protected function renderOnlyView($view,$params)
  {
    foreach($params as $key => $value){
      // name => $name
      $$key = $value;
    }
    ob_start();
    include_once Application::$ROOT_DIR."/views/$view.php";
    return ob_get_clean();
  }
} 