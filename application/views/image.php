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
<?php
  require_once(INCLUDES_VIEWS . 'footer_top.inc.php');
  require_once(INCLUDES_VIEWS . 'footer_bottom.inc.php');
?>
