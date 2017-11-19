<?php

  class Comment {

    public static function findAll($pdo, $image_id)
    {
      $sql = "SELECT * FROM comments WHERE image_id = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->bindValue(1, $image_id, PDO::PARAM_INT);
      $result = $stmt->execute();
      if ($result)
        return ($stmt);
      else
        return (false);
    }

    public static function insert($pdo, $image_id, $user_id, $comment)
    {
      $sql = "INSERT INTO comments (image_id, user_id, comment, submit_date) VALUES (?, ?, ?, UTC_TIMESTAMP() )";
      $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $image_id, PDO::PARAM_INT);
      $stmt->bindValue(2, $user_id, PDO::PARAM_INT);
      $stmt->bindValue(3, $comment, PDO::PARAM_STR);
      $result = $stmt->execute();
      if ($result && ($stmt->rowCount() == 1) )
        return (true);
      else
        return (false);
    }

  }

?>
