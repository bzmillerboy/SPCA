<?php
    include("config/config.php");
    include("class/class.Object.php");
?>

<?php require("incl.head.php"); ?>

<title>News SPCA Cincinnati</title>
<?php require("incl.top.php"); ?>

</section><!--/header-section-->

<section class="single-page">

  <div class="content">
    <div class="container">
    <h1 class="page-title">News</h1>
    <div class="clearfix"></div>
      <div class="row">
        <div class="col-lg-9">
        <ul class="list plain-list">
<?php
	$q = "SELECT id FROM news WHERE disabled = 0 AND post_date <= NOW() ORDER BY post_date DESC";
	$results = mysql_query($q);
	while ($row = mysql_fetch_array($results)) {
		$news = new Object('news', $row["id"]);
?>
            <li>
                <a class="plain-link" href="/news/<?php echo $news->permalink; ?>">
                <h2><?php echo $news->title; ?></h2>
                </a>
                <small><p class="text-muted"><?php echo $news->get_date('post_date'); ?></p></small>
                <p><?php echo $news->synopsis; ?></p>
            </li>
            <hr>
<?php
	}
?>
            </ul>
        </div>
        <div class="col-lg-3">
          <?php require("incl.about-sidenav.php"); ?>
        </div>


      </div>
    </div>
  </div>
</section><!--/single-page-section-->

<hr>

<?php require("incl.bottom.php"); ?>
<!-- Page-Specific Scripts -->
<script>
  $("a#about-news").addClass('active');
</script>
</body>
</html>