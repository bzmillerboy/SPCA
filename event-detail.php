<?php
	include("config/config.php");
	include("class/class.Object.php");

	$permalink = (isset($_GET["permalink"])) ? $_GET["permalink"] : '';
	$q = "SELECT id FROM events WHERE permalink = '$permalink'";
	$results = mysql_query($q);
	$row = mysql_fetch_array($results);

	$event_id = $row["id"];
	$event = new Object('events', $event_id);
?>
<?php require("incl.head.php"); ?>
<title><?php echo $event->title; ?> SPCA Cincinnati</title>
<?php require("incl.top.php"); ?>

</section><!--/header-section-->

<section class="single-page">

  <div class="content">
    <div class="container">
    <h1 class="page-title">Events</h1>
    <div class="clearfix"></div>
      <div class="row">
        <div class="col-lg-12">
            <h2><?php echo $event->title; ?></h2>
            <div class="event-overview">
            <br>
                <div class="row">
                    <div class="col-md-3 clearfix">
                        <div class="icon fa fa-map-marker"></div>
                        <div class="info">
                            <h5><?php echo $event->location; ?></h5>
                            <span class="text-muted"><?php echo $event->address; ?></span>
                        </div>
                    </div>
                    <div class="col-md-3 clearfix">
                        <div class="icon fa fa-calendar"></div>
                        <div class="info">
                            <h5><?php echo $event->get_date('start_date', 'd F'); ?></h5>
                            <span class="text-muted">of the year <?php echo $event->get_date('start_date', 'Y'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-3 clearfix">
                        <div class="icon fa fa-clock-o"></div>
                        <div class="info">
                            <h5><?php echo $event->time; ?></h5>
                            <span class="text-muted">Be There</span>
                        </div>
                    </div>
                    <div class="col-md-3 clearfix">
                        <div class="icon fa fa-usd"></div>
                        <div class="info">
                            <h5><?php echo $event->cost; ?></h5>
                            <span class="text-muted">It's a deal</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="event-details">
                <div class="row">
                    <div class="col-md-12 clearfix">
                        <a href="https://www.google.com/maps/place/1230+Elm+Street,+Cincinnati,+OH/"><img class="img-responsive" src="http://maps.googleapis.com/maps/api/staticmap?center=1230+Elm+Street,+Cincinnati,+OH&zoom=12&scale=2&size=640x175&maptype=roadmap&sensor=false&key=AIzaSyD22-VhdHtHpOnaqPwfTwsh7L6ArMRbUbI&format=jpg&visual_refresh=true&markers=size:mid%7Ccolor:red%7Clabel:%7C1230+Elm+Street,+Cincinnati,+OH" alt="Google Map of 1230 Elm Street, Cincinnati, OH"></a>
                        <div class="row">
                            <div class="col-md-7">
                                <h3>Event Details</h3>
                                <?php echo $event->content; ?>
                            </div>
                            
						<?php if ($event->eventbrite_code != '') { ?>
                            <div class="col-md-5 ticket-info">
                                <div style="width:100%; text-align:left;" >
                                  <iframe src="http://www.eventbrite.com/tickets-external?eid=<?php echo $event->eventbrite_code; ?>&ref=etckt&v=2" frameborder="0" height="856" width="100%" vspace="0" hspace="0" marginheight="5" marginwidth="5" scrolling="auto" allowtransparency="true"></iframe>
                                </div>
                            </div>
						<?php } ?>
                            
                            
                            
                        </div>

                        <br>

                    </div>
                </div>
            </div>

        </div><!--/col-->
      </div>
    </div>
  </div>
</section><!--/single-page-section-->

<hr>

<?php require("incl.bottom.php"); ?>
<!-- Page-Specific Scripts -->
</body>
</html>
