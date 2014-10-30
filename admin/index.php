<?php

	define('DATABASE', 'dashboard');
	
	require_once("../config/config.php");
	require_once("../class/class.Login.php");
	require_once("../class/class.GAPI.php");
	
	$login = new Login();
	$login->check_access(1);
	
	$google = (GA_PASSWORD == '') ? false : true;
	
	if ($google) {
		$ga = new GAPI(GA_EMAIL, GA_PASSWORD);
		$ga->requestReportData(GA_PROFILE_ID, array('date'), array('pageviews','visits'), array('-date'));
	}
		
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
<?php
	include("inc.admin-head.php");
?>
<title>Dashboard</title>
<?php
	if ($google) {
?>
<!--Load the AJAX API-->
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', 'Date');
        data.addColumn('number', 'Visits');
        data.addRows([
<?php
		$results = array_reverse($ga->getResults());
		$num_results = count($results);
		$i = 1;
		foreach($results as $date_record) {
			echo "[new Date(" . date('Y', strtotime($date_record->getDate())) . ", " . (date('n', strtotime($date_record->getDate())) - 1) . ", " . date('j', strtotime($date_record->getDate())) . "), " . $date_record->getVisits() . "]";
			if ($i != $num_results) echo ', ';
			$i++;
		}
?>
        ]);

        var options = {			
          width: '100%', 
		  height: 200,
		  strictFirstColumnType: true,
          title: 'Recent Visits',
		  legend: {
			position: 'none'
		  },
		  titlePosition: 'none',
		  lineWidth: 3,
		  pointSize: 5,
		  chartArea: {
			  left: '4%',
			  right: '2%',
			  width: '94%',
			  height: '77%',
			  bottom: '15%',
			  top: '8%'
		  },
		  hAxis: {
			  showTextEvery: 1,
			  format: 'MMM dd',
			  gridlines: {
			  	color: '#DDD',
				count: 3
			  },
			textStyle: {
				fontSize: 11	
			}
		  },
		  vAxis: {
			  gridlines: {
			  	color: '#DDD'
			  },
			textStyle: {
				fontSize: 11	
			}
		  }
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
<?php
	}
?>
</head>
<body class="admin">
<?php
	include("inc.admin-top.php"); 
?>
<h1>Dashboard</h1>
<?php
	if ($google) {
?>
<h3>Site Visits</h3>
<div id="chart_div"></div> 

<h3 style="margin-top: 35px;">Most Visited Pages</h3>
<table>
<?php
		$ga->requestReportData(GA_PROFILE_ID, array('pageTitle'), array('pageviews','uniquePageviews'), array('-pageviews'));
		$results = ($ga->getResults());
		foreach($results as $page_record) {
?>
	<tr><td style="padding-right:35px;"><?php echo $page_record->getPageTitle(); ?></td><td><?php echo $page_record->getPageviews(); ?></td></tr>
<?php	
        }
?>
</table>
<?php
	}
?>
<?php
	include("inc.admin-bottom.php");
?>
</body>
</html>
