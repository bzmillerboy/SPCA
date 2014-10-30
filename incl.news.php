<?php
  include("config/config.php");
  include("class/class.Object.php");
?>


  <h2>Latest News</h2>
  <ul class="list">

<?php
  $q = "SELECT id FROM news WHERE disabled = 0 AND post_date <= NOW() ORDER BY post_date DESC LIMIT 4";
  $results = mysql_query($q);
  while ($row = mysql_fetch_array($results)) {
    $news = new Object('news', $row["id"]);
?>

    <li>
    <a href="/news/<?php echo $news->permalink; ?>">
    <h3><?php echo $news->title; ?></h3>
    <small><p class="text-muted"><?php echo $news->get_date('post_date'); ?></p></small>
    </a>
    </li>

<?php
  }
?>

  </ul>

