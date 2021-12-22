<?php

namespace app\models;
use app\core\Model;
use app\models\User;

class LoginForm extends Model
{

  public string $email = '';
  public string $password = '';

  public function rules(): array
  {
    return [
      'email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
      'password' => [self::RULE_REQUIRED]
    ];
  }

  public function login()
  {
    $user = (new User)->findOne([ 'email' => $this->email ]);

    if(!$user) {
      $this->addError('email', 'User does not exist with email');
      return false;
    }
    if(!password_verify($this->password, $user->password)) {
      $this->addError('Password', 'Password invalid');
      return false;
    }
    return 'Success Login';
  }
} 