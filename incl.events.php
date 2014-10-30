


  <h2>Upcoming Events</h2>
  <ul class="list">

<?php
  $today = date("Y-m-d 00:00:00");
  $q = "SELECT id FROM events WHERE disabled = 0 AND start_date >= '$today' ORDER BY start_date ASC LIMIT 3";
  $results = mysql_query($q);
  while ($row = mysql_fetch_array($results)) {
    $event = new Object('events', $row["id"]);
?>
                <li>
                  <a href="/events/<?php echo $event->permalink; ?>" >
                  <h3><?php echo $event->title; ?></h3>
                  <small><p class="text-muted"><?php echo $event->get_date('start_date'); ?><br>
                  <?php echo $event->time; ?></p></small>
                  </a>
                </li>
<?php
  }
?>

  </ul>





