<?php

  require_once(INCLUDES_DATABASE . "connection.inc.php");

  class ProfileController extends BaseController {

    public function load()
    {
      $title = "Profile";
      require_once(VIEW_PATH . 'profile.php');
    }

    public function deleteImage()
    {
      $image_id = $_GET['image_id'];
      if (!empty($image_id) ) {
        $pdo = Db::getInstance();

        $sql_filename = "SELECT * FROM images WHERE image_id = ?";
        $stmt_filename = $pdo->prepare($sql_filename);
        $stmt_filename->bindValue(1, $image_id, PDO::PARAM_INT);
        $stmt_filename->execute();
        if ($row = $stmt_filename->fetch() ) {

          $filename = UPLOADS_PATH . $row['filename'];
          $sql = "DELETE FROM images WHERE image_id = ? LIMIT 1";
          $stmt = $pdo->prepare($sql);
          $stmt->bindValue(1, $image_id, PDO::PARAM_INT);
          $stmt->execute();
          if ($stmt->rowCount() == 1) {
            if (file_exists($filename) && is_file($filename))
              unlink($filename);
            echo "Image successfully deleted." . PHP_EOL;
          }
          else {
            echo "Image could not be deleted." . PHP_EOL;
          }

          $stmt = NULL;
          $pdo = NULL;

        }

      }

      $title = "Profile";
      require_once(VIEW_PATH . 'profile.php');
    }

  }

?>
