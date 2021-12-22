<?php

namespace app\core;

abstract class DbModel extends Model
{
  abstract public function tableName();

  abstract public function attributes();

  public function save()
  {
    $tableName = $this->tableName();
    $attributes = $this->attributes();
    $params = array_map(fn($attr) => ":$attr", $attributes);
    // firstname, lastname
    $statement = self::prepare("INSERT INTO $tableName (".implode(',', $attributes).") VALUES(".implode(',',$params).")");

    foreach ($attributes as $attribute) {
      //表示{}裡面的是一個變數 ,執行時按照變數來處理 
      $statement->bindValue(":$attribute", $this->{$attribute});
    }
    $statement->execute();
    return true;
  }

  public static function prepare($sql)
  {
    return Application::$app->db->pdo->prepare($sql);
  }

  public function findOne($where)
  {
    // [email => abc@gmail.com, firstname => abc]
    $tableName = static::tableName();
    $attributes = array_keys($where);
    // SELECT * FROM $tableName WHERE email = :email, AND firstname = :firstname
    $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
    $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
    foreach($where as $key => $item) {
      $statement->bindValue(":$key", $item);
    }

    $statement->execute();
    return $statement->fetchObject();
  }
} 