<?php
namespace app\controllers;

use app\core\Controller;
use app\core\Request;
use app\core\Response;
use app\models\User;
use app\models\LoginForm;


class AuthController extends Controller
{

  public function login(Request $request, Response $response)
  {
    $loginForm = new LoginForm();
    if ($request->isPost()) {
      $loginForm->loadData($request->getBody());
      if($loginForm->validate() && $loginForm->login()) {
        $response->redirect('/',200,'Login Success');
        return;
      }else{
        $response->redirect('/',500,'Error! Please check you account or password ');
        return;
      }
    }
    $this->setLayout('auth');
    return $this->render('login', [
      'model' => $loginForm
    ]);
  }

  public function register(Request $request)
  {
    $user = new User();
    if($request->isPost()) {
      $user->loadData($request->getBody());

      if($user->validate() && $user->register()){
        return 'success';
      }

      return $this->render('register', [
        'model' => $user
      ]);
    }
    $this->setLayout('auth');
    return $this->render('register', [
      'model' => $user
    ]);
  }
}