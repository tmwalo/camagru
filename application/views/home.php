<?php
  $title = "Camagru Home";
  require_once(INCLUDES_VIEWS . 'header_top.inc.php');
?>
<link rel="stylesheet" href="css/home.css">
<?php
  require_once(INCLUDES_VIEWS . 'header_bottom.inc.php');
?>
<h1>Camagru Home Page</h1>
<h2>Gallery:</h2>
<div class="gallery">
<?php

  require_once(INCLUDES_DATABASE . "connection.inc.php");
  require_once(MODEL_PATH . "Image.class.php");

  $records_per_page = 3;
  $pdo = Db::getInstance();

  $result = Image::countAll($pdo);
  if ($result) {
    $stmt = $result;
    $row = $stmt->fetch();
    $records = $row['total'];
    if ($records > $records_per_page)
      $pages = ceil($records / $records_per_page);
    else
      $pages = 1;
  }
  else
    return ;

  if (isset($_GET['s']) && is_numeric($_GET['s']) )
    $start_index = (int) $_GET['s'];
  else
    $start_index = 0;

  $result = NULL;
  $stmt = NULL;
  $row = NULL;

  $order_by = "upload_date DESC";
  var_dump($order_by);
  var_dump($start_index);
  var_dump($records_per_page);
  $result = Image::findRange($pdo, $order_by, $start_index, $records_per_page);
  if ($result) {
    $stmt = $result;
    while ($row = $stmt->fetch() ) {
      echo "<div class=\"gallery_img\">
              <img src=\"uploads/" . $row['filename'] . "\" alt=\"\" />
            </div>";
    }
  }

  if ($pages > 1) {

    echo "<br />
          <div class=\"pagination\">";

    $current_page = ($start_index / $records_per_page) + 1;

    if ($current_page != 1) {
      echo "<a href=\"index.php?s=" . urlencode(($start_index - $records_per_page) ) . "\">Previous </a>";
    }

    $index = 1;
    while ($index <= $pages) {
      if ($index != $current_page) {
        echo "<a href=\"index.php?s=" . urlencode(($index - 1) * $records_per_page) . "\">" . $index . "</a>";
      }
      else {
        echo " $index ";
      }
      $index++;
    }

    if ($current_page != $pages) {
      echo "<a href=\"index.php?s=" . urlencode(($start_index + $records_per_page) ) . "\"> Next</a>";
    }

    echo "</div>";

  }

?>
</div>
<?php
  require_once(INCLUDES_VIEWS . 'footer_top.inc.php');
  require_once(INCLUDES_VIEWS . 'footer_bottom.inc.php');
?>
