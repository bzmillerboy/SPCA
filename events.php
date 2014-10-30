<?php
	include("config/config.php");
	include("class/class.Object.php");
?>

<?php require("incl.head.php"); ?>

<title>Events SPCA Cincinnati</title>
<?php require("incl.top.php"); ?>

</section><!--/header-section-->

<section class="single-page">

  <div class="content">
    <div class="container">
    <h1 class="page-title">Events</h1>
    <div class="clearfix"></div>
      <div class="row">
        <div class="col-lg-9">
        <ul class="list plain-list">

<?php
	$today = date("Y-m-d 00:00:00");
	$q = "SELECT id FROM events WHERE disabled = 0 AND start_date >= '$today' ORDER BY start_date ASC";
	$results = mysql_query($q);
	while ($row = mysql_fetch_array($results)) {
		$event = new Object('events', $row["id"]);
?>
                <li>
                  <a class="plain-link" href="/events/<?php echo $event->permalink; ?>" >
                  <h2><?php echo $event->title; ?></h2>
                  </a>
                  <small><p class="text-muted"><?php echo $event->get_date('start_date'); ?><br>
                  <?php echo $event->time; ?></p></small>
                  <p><?php echo $event->synopsis; ?></p>
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
  $("a#about-events").addClass('active');
</script>
</body>
</html>
