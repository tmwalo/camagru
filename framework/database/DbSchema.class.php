<?php

require_once(INCLUDES_DATABASE . 'connection.inc.php');

abstract class DbSchema {

    private static function createDatabaseCamagru(PDO $db)
    {
      $sql = "CREATE DATABASE IF NOT EXISTS camagru";
      try {
        $db->exec($sql);
      } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage() . "<br />";
      }
    }

    private static function useDatabaseCamagru(PDO $db)
    {
      $sql = "USE camagru";
      try {
        $db->exec($sql);
      } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage() . "<br />";
      }
    }

    private static function createUsersTable(PDO $db)
    {
      $sql = "CREATE TABLE IF NOT EXISTS users (
                user_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                username VARCHAR(20) NOT NULL,
                email VARCHAR(60) NOT NULL,
                pword CHAR(20) NOT NULL,
                registration_date DATETIME NOT NULL,
                PRIMARY KEY (user_id)
              ) ENGINE = INNODB";
      try {
        $db->exec($sql);
      } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage() . "<br />";
      }
    }

    private static function createImagesTable(PDO $db)
    {
      $sql = "CREATE TABLE IF NOT EXISTS images (
                image_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                user_id INT UNSIGNED NOT NULL,
                filename VARCHAR(20) NOT NULL,
                likes INT UNSIGNED NOT NULL DEFAULT 0,
                upload_date DATETIME NOT NULL,
                PRIMARY KEY (image_id),
                FOREIGN KEY (user_id) REFERENCES users (user_id)
              ) ENGINE = INNODB";
      try {
        $db->exec($sql);
      } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage() . "<br />";
      }
    }

    private static function createCommentsTable(PDO $db)
    {
      $sql = "CREATE TABLE IF NOT EXISTS comments (
                comment_id INT UNSIGNED NOT NULL AUTO_INCREMENT,
                parent_id INT UNSIGNED NOT NULL DEFAULT 0,
                image_id INT UNSIGNED NOT NULL,
                user_id INT UNSIGNED NOT NULL,
                comment VARCHAR(255) NOT NULL,
                likes INT UNSIGNED NOT NULL DEFAULT 0,
                submit_date DATETIME NOT NULL,
                PRIMARY KEY (comment_id),
                FOREIGN KEY (image_id) REFERENCES images (image_id)
                ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users (user_id)
              ) ENGINE = INNODB";
      try {
        $db->exec($sql);
      } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage() . "<br />";
      }
    }

    public static function createDatabase()
    {
      $db = Db::getInstance();

      self::createDatabaseCamagru($db);
      self::useDatabaseCamagru($db);
      self::createUsersTable($db);
      self::createImagesTable($db);
      self::createCommentsTable($db);
    }

}

?>
