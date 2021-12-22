<?php
namespace app\core;

use PDO;

class Database
{
  public PDO $pdo;

  public function __construct($config)
  {
    $dsn = $config['dsn'] ?? '';
    $user = $config['user'] ?? '';
    $password = $config['password'] ?? '';

    $this->pdo = new PDO($dsn, $user, $password);

    $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //PDO::ATTR_ERRMODE：錯誤報告。
    //PDO::ERRMODE_EXCEPTION: 拋出 exceptions 異常。

  }

  public function applyMigrations()
  {
    $this->createMigrationsTable(); // 新建資料表
    $appliedMigrations = $this->getAppliedMigrations();// 從資料庫取得舊資料表跟資料夾的比對
    $newMigrations = [];
    $files = scandir(Application::$ROOT_DIR.'/migrations');// 取得migrations資料夾內所有檔案
        /*
        例子
        <?php
            print_r(scandir("images"));
        ?> 
        輸出：
        Array
        (
            [0] => .
            [1] => ..
            [2] => dog.jpg
        )
    */
    $toApplyMigrations = array_diff($files, $appliedMigrations); // 比對
    foreach($toApplyMigrations as $migration) {
      // 系統本來就會有 . 跟 .. 仍執行就好
      if ($migration === '.' || $migration === '..') {
        continue;
      }
      require_once Application::$ROOT_DIR.'/migrations/'.$migration; // 將migrations包含進來
      $className = pathinfo($migration, PATHINFO_FILENAME);
      $instance = new $className();// 上 取得路徑名 下 並且實體化
      echo "開始建立 ... Begin migration $migration".PHP_EOL;// PHP_EOL 換行(linux,mac,windows)
      $instance->up();
      echo "建立完成 ... Finish migration $migration".PHP_EOL;
      $newMigrations[] = $migration; // 將建立完並且是新的的migration放到陣列裡
    }

    //如果 migration 都已完成 newMigrations則全空
    if(!empty($newMigrations)) {
      $this->saveMigrations($newMigrations);//儲存migrations資料表
    } else {
      echo "All migrations are applied".PHP_EOL;
    }
  }

  public function createMigrationsTable() //新建 migrations 資料表 
  {
    $this->pdo->exec("CREATE TABLE IF NOT EXISTS migrations(
      id INT AUTO_INCREMENT PRIMARY KEY,
      migration VARCHAR(255),
      created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=INNODB;");
  }

  public function getAppliedMigrations() //取得目前資料庫中migrations 資料表
  {
    $statement = $this->pdo->prepare("SELECT migration FROM migrations");
    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_COLUMN);
  }

  public function saveMigrations($migrations)
  {
    // array to string
    // m0001_initial.php => "('m0001_initial.php')"
    // ('m0001_initial.php'), ('m0002_initial.php')
    $str = implode(",", array_map(function($m){
            return "('$m')";
    }, $migrations));
    /*
        簡寫
        $str = implode(",", array_map(fn($m) => "('$m')", $migrations));
    */
    $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES
      $str
    ");
    $statement->execute();
  }
} 