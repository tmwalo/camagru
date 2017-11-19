<?php

  class Image {

    public static function all($pdo)
    {
      $sql = "SELECT * FROM images ORDER BY upload_date DESC";
      $stmt = $pdo->query($sql);
      return ($stmt);
    }

    public static function countAll($pdo)
    {
      $sql = "SELECT COUNT(image_id) AS total FROM images";
      $stmt = $pdo->query($sql);
      return ($stmt);
    }

    public static function find($pdo, $image_id)
    {
      $sql = "SELECT * FROM images WHERE image_id = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(1, $image_id, PDO::PARAM_INT);
      $result = $stmt->execute();
      if ($result) {
        $row = $stmt->fetch();
        return ($row);
      }
      else
        return (false);
    }

    public static function findAll($pdo, $user_id)
    {
      $sql = "SELECT * FROM images WHERE user_id = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
      $result = $stmt->execute();
      if ($result)
        return ($stmt);
      else
        return (false);
    }

    public static function findRange($pdo, $order_by, $start_index, $num_records)
    {
      $sql = "SELECT * FROM images ORDER BY ? LIMIT ?, ?";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(1, $order_by, PDO::PARAM_STR);
      $stmt->bindValue(2, $start_index, PDO::PARAM_INT);
      $stmt->bindValue(3, $num_records, PDO::PARAM_INT);
      $result = $stmt->execute();
      if ($result)
        return ($stmt);
      else
        return (false);
    }

    public static function insert($pdo, $user_id, $filename)
    {
      $sql = "INSERT INTO images (user_id, filename, upload_date) VALUES (?, ?, UTC_TIMESTAMP() )";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
      $stmt->bindValue(2, $filename, PDO::PARAM_STR);
      $result = $stmt->execute();
      if ($result && ($stmt->rowCount() == 1) )
        return (true);
      else
        return (false);
    }

    public static function delete($pdo, $image_id)
    {
      $sql = "DELETE FROM images WHERE image_id = ? LIMIT 1";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(1, $image_id, PDO::PARAM_INT);
      $result = $stmt->execute();
      if ($result && ($stmt->rowCount() == 1) )
        return (true);
      else
        return (false);
    }

  }

?>
