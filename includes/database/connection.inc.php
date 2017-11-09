<?php

class Db {

  private static $instance = NULL;

  private function __construct() {}

  private function __clone() {}

  public static function getInstance() {
    if (!isset(self::$instance)) {
      require_once(CONFIG_PATH . 'database.php');
      $DB_INIT_DSN = "mysql:host=localhost";
      try {
        self::$instance = new PDO($DB_INIT_DSN, $DB_USER, $DB_PASSWORD);
        self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        echo "Database connection failed: " . $e->getMessage() . "<br />";
        die();
      }
    }
    return (self::$instance);
  }

}

?>
