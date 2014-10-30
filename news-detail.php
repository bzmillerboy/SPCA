<?php
	include("config/config.php");
	include("class/class.Object.php");

	$permalink = (isset($_GET["permalink"])) ? $_GET["permalink"] : '';
	$q = "SELECT id FROM news WHERE permalink = '$permalink'";
	$results = mysql_query($q);
	$row = mysql_fetch_array($results);

	$news_id = $row["id"];
	$news = new Object('news', $news_id);
?>
<?php require("incl.head.php"); ?>
<title><?php echo $news->title; ?> SPCA Cincinnati</title>
<?php require("incl.top.php"); ?>

</section><!--/header-section-->

<section class="single-page">

  <div class="content">
    <div class="container">
    <h1 class="page-title">News</h1>
    <div class="clearfix"></div>
      <div class="row">
        <div class="col-lg-9">
            <h2><?php echo $news->title; ?></h2>
            <?php echo $news->content; ?>


        </div><!--/col-->
        <div class="col-md-3">
          <?php require("incl.sidebar.php"); ?>
      </div>
    </div>
  </div>
</section><!--/single-page-section-->

<hr>

<?php require("incl.bottom.php"); ?>
<!-- Page-Specific Scripts -->
</body>
</html>
