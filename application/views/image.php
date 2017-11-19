<?php
  $title = "Image";
  require_once(INCLUDES_VIEWS . 'header_top.inc.php');
?>
<link rel="stylesheet" href="css/image.css" />
<?php
  require_once(INCLUDES_VIEWS . 'header_bottom.inc.php');
?>
<h1>Image</h1>
<div class="image">
<?php

  require_once(INCLUDES_DATABASE . "connection.inc.php");
  require_once(MODEL_PATH . "Image.class.php");

  if (isset($_GET['img']) && is_numeric($_GET['img']) )
    $image_id = (int) $_GET['img'];
  else
    return ;

  $pdo = Db::getInstance();
  $result = Image::find($pdo, $image_id);
  if ($result) {
    $row = $result;
    echo "<img src=\"uploads/" . $row['filename'] . "\" alt=\"\" />
          <div class=\"image_data\">
            likes: " . $row['likes'] . "
          </div>";
  }
  else
    echo "Image not found.";

?>
</div>
<div class="comments">
  <h2>Comments:</h2>
  <div>
    <div>
    <?php

      require_once(MODEL_PATH . "Comment.class.php");

      $result_show_comments = Comment::findAll($pdo, $row['image_id']);
      if ($result_show_comments) {
        while ($row_show_comment = $result_show_comments->fetch() ) {
          echo "<div>" . $row_show_comment['comment'] . "</div>";
        }
      }

    ?>
    </div>
    <div class="comment_form">
      <h3>Post Comment:</h3>
      <form action="" method="post">
        <textarea name="comment" rows="10" cols="40"></textarea>
        <input type="hidden" name="img" value="<?php echo $row['image_id'] ?>">
        <br />
        <input type="submit" value="Post Comment">
      </form>
      <?php

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

          require_once(MODEL_PATH . "Comment.class.php");

          $validate_comment = false;
          $validate_image_id = false;

          $comment = $_POST['comment'];
          $image_id = $_POST['img'];

          if (!empty($comment) ) {
            $comment = htmlentities($comment);
            $validate_comment = true;
          }

          if (isset($image_id) && is_numeric($image_id) ) {
            $image_id = (int) $image_id;
            $validate_image_id = true;
          }

          $user_id = 1;

          if ($validate_comment && $validate_image_id) {
            $comment_result = Comment::insert($pdo, $image_id, $user_id, $comment);
            if ($comment_result) {
              echo "Comment posted.";
            }
            else
              echo "Comment could not be posted.";
          }

        }

      ?>
    </div>
  </div>
</div>
<?php
  require_once(INCLUDES_VIEWS . 'footer_top.inc.php');
  require_once(INCLUDES_VIEWS . 'footer_bottom.inc.php');
?>
