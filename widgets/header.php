<!--[if IE]>
<style>
.tabrow li {
    margin: 0 -1px;
}
.tabrow li:before,
.tabrow li:after {
    border: none;
}
.tabrow:before {
    border-bottom: inherit;
}
</style>
<![endif]-->
<?php
    error_reporting(0);
    $ts = strtotime(date('Y-m-d'));
    $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
    $start_date = date('Y-m-d', $start);
    $end_date = date('Y-m-d', strtotime('next saturday', $start)); 
	
	$link = mysqli_connect("localhost", "u256530890_tlog", "Eti&Shelly=2013", "u256530890_tlog") or die("Could not connect");

//	$link = mysqli_connect("mysql.hostinger.co.il", "u256530890_tlog", "Eti&Shelly=2013", "u256530890_tlog" ) or die("Could not connect");
	mysqli_set_charset($link, "utf8");
    mysqli_select_db($link, "u256530890_tlog") or die("Could not select database");
 
    $query_d = "SELECT (SUM(run_distance) + SUM(warmup_distance) + SUM(cooldown_distance)) as daily from tl_events where date(run_date) = '" . date('Y-m-d') . "'" ;
	$result_d = mysqli_query($link, $query_d) or die("Query failed");
	$row_d = mysqli_fetch_array($result_d, MYSQLI_ASSOC);
	if ($row_d['daily'] == "" or $row_d['daily'] == null)
		$row_d['daily'] = 0;

    $query_w = "SELECT (SUM(run_distance) + SUM(warmup_distance) + SUM(cooldown_distance)) as weekly from tl_events where date(run_date) >= '" . $start_date . "' and date(run_date) <= '" . $end_date . "'";
	$result_w = mysqli_query($link, $query_w) or die("Query failed");
	$row_w = mysqli_fetch_array($result_w, MYSQLI_ASSOC);
	
	if ($row_w['weekly'] == "" or $row_w['weekly'] == null)
		$row_w['weekly'] = 0;
	
	$query_bd = "SELECT member_name AS runner_name FROM  tl_runners WHERE m_show_profile = true and DATE_FORMAT(birthday, '%d-%m') = DATE_FORMAT(CURDATE(), '%d-%m')";
	$result_bd = mysqli_query($link, $query_bd) or die("Query failed");
	
?>


    <script type='text/javascript' src='https://www.google.com/jsapi'></script>
    <script type='text/javascript'>
	  
      google.load('visualization', '1', {packages:['gauge']});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
		var k_d = <?php echo $row_d['daily']; ?>;
        var k_w = <?php echo $row_w['weekly']; ?>;
        var data_d = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
          ['TODAY', k_d]
        ]);

        var options_d = {
          width: 200, height: 150,
          max: 900,
          redFrom: 800, redTo: 900,
          yellowFrom:600, yellowTo: 800,
          minorTicks: 10
        };

        var data_w = google.visualization.arrayToDataTable([
          ['Label', 'Value'],
		  ['WEEK', k_w]
        ]);

        var options_w = {
          width: 200, height: 150,
          max: 5000,
          redFrom: 4500, redTo: 5000,
          yellowFrom: 4000, yellowTo: 4500,
          minorTicks: 10
        };

        var chart = new google.visualization.Gauge(document.getElementById('chart_div_d'));
        chart.draw(data_d, options_d);
		var chart = new google.visualization.Gauge(document.getElementById('chart_div_w'));
		chart.draw(data_w, options_w);
      }
	</script>
	
<div id="header">
	<div >
		<table dir="rtl" align="center" border="0">
			  <tr >
			    <td width="30%"><span id="swbdh" style="color:#ffbb99"><b> מזל טוב ליום ההולדת<b></td>
				<td align="center"><b><font color="white">קילומטרז' כללי היום</font></b></td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td align="center"><b><font color="white">קילומטרז' כללי שבועי</font></b></td>
				
			  </tr> 
				<td width="30%" align="right">
					
					<div align="right" id='swbd'>
					
					<?php
					$counter = 0;
					while ($row_bd = mysqli_fetch_array($result_bd, MYSQLI_NUM)) {
						printf("<span style='color:yellow'><b> %s </b></span> <br>", $row_bd[0]);
						$counter++;						
					}
					?>
					<!--<iframe src="https://giphy.com/embed/AwcmOV28QPnck" width="120" height="140" frameBorder="0" class="giphy-embed" ></iframe>
					--><br><image src="./widgets/bd.gif" width="100" height="80">
					</div>
				</td>
				<td>
					<div align="center" id='chart_div_d'></div>
				</td>
				<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td>
					<div align="center" id='chart_div_w'></div>
				</td></font>
				
				
			  </tr>
		</table>
	</div>
</div>
<div id="header_space"></div>
<div id="header">
	
    <div id="header_graphics" >
        <div id="title">מעקב אימונים</div>
		<div id='content' style="display:none;">
            <a id="logout" href="logout.php">התנתק</a>
            <div id="tabs">
                <ul class="tabrow">
                    <li><a id="runlog" href="runlog.php">יומן ריצה</a></li>
                    <li><a id="fedd" href="feed.php">מה עשו החבר'ה</a></li>
                    <li><a id="shoes" href="shoes.php">הנעליים שלי</a></li>
                    <li><a id="courses" href="courses.php">המסלולים שלי</a></li>
					<li><a id="statistics" href="statistics.php">סטטיסטיקה</a></li>
					<li><a id="results" href="results.php">תוצאות</a></li>
					<?php 
					$runner_id = $memberAuthentication->getMemberId();
					$stravaId = $memberAuthentication->getStravaId();
					if ($stravaId != null){?>
					    <li><a id="admin" href="strava.php">Strava</a></li>
					<?php } ?>
					<?php
					if ($runner_id == '122' || $runner_id == '18600106' || $runner_id == '18600102') {
					?>
					
					<li><a id="admin" href="races.php">מרוצים</a></li>
					<li><a id="admin" href="users.php">משתמשים</a></li>
					<?php } ?>
                </ul>
            </div>
        </div> 
    </div>
</div>
<script>
$(document).ready(function () {
	var counter = '<?php echo $counter; ?>';
	if (counter == 0){
	  $('#swbdh').hide();
	  $('#swbd').hide();
	}
});	
$(function(){
    var href = document.location.href;
    var page = href.substr(href.lastIndexOf('/') +1);
	
    $('.tabrow li').each(function()
    {
		  if ($(this).children('a').attr('href') == page)
        {
            $(this).addClass('selected');
            $('#content').show();
        }
    })
});
</script>