<?php

namespace app\models;
use app\core\DbModel;


class User extends DbModel
{
  public string $firstname = '';
  public string $lastname = '';
  public string $email = '';
  public string $password = '';

  public function tableName()
  {
    return 'users';
  }

  public function register()
  {
    $this->password = password_hash($this->password, PASSWORD_DEFAULT);
    return $this->save();
  }

  public function rules()
  {
    return [
      'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8]],
    ];
  }

  public function attributes()
  {
    return ['firstname', 'lastname', 'email', 'password'];
  }

  public function primaryKey()
  {
    return 'id';
  }
}