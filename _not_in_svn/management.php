<?php
require_once 'php/bc_html_page_init.php';
debugLogRequest(debug_backtrace());

if (isset($_GET['debug']))
{
    DBLogger::setDebugEnabled($_GET['debug'] == 'true');
}
?>
<html>
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

		<link href="./css/jquery-ui.css" rel="stylesheet" type="text/css"/>	
		<script src="./js/jquery.min.js"></script>
		<script src="./js/jquery-ui.min.js"></script>
		<script src="./js/jquery.maskedinput.js" type="text/javascript"></script>
		

				
		<script src="./js/json2.js" type="text/javascript"></script>
        <script src="./js/constants.js" type="text/javascript"></script>	
		<script src="./js/json_sans_eval.js"></script> 
        <script src="./js/functions.js" type="text/javascript"></script>
		

		<script type='text/javascript'>
		
		$(document).ready(function() {
			Functions.populateUsersSelect();
		});

		function selectTab(index){
		 $("#tabs").tabs('load', index);
		}				

		$(function() {
			$( "#tabs" ).tabs();
			$( "#tabs" ).bind( "tabsload", function(event, ui) {
			  populateUserShoesRecords();
			});
		});
		
		</script>
<style type='text/css'>

	body {
		margin-top: 40px;
		text-align: center;
		font-size: 14px;
		font-family: "Lucida Grande",Helvetica,Arial,Verdana,sans-serif;
		background-color: #fefff5;
		direction:rtl;
		}

    table.tablesorter {
        font-family:arial;
        background-color: #CDCDCD;
        margin:10px 0pt 15px;
        font-size: 8pt;
        width: 100%;
        text-align: left;
        table-layout: fixed;
    }
    table.tablesorter thead tr th, table.tablesorter tfoot tr th {
        background-color: #e6EEEE;
        border: 1px solid #FFF;
        font-size: 8pt;
        padding: 4px;
    }
    table.tablesorter thead tr .header {
        background-image: url(http://tablesorter.com/themes/blue/bg.gif);
        background-repeat: no-repeat;
        background-position: center right;
        cursor: pointer;
    }
    table.tablesorter tbody td {
        color: #3D3D3D;
        padding: 4px;
        background-color: #FFF;
        vertical-align: top;
    }
    table.tablesorter tbody tr.odd td {
        background-color:#F0F0F6;
    }
    table.tablesorter thead tr .headerSortUp {
        background-image: url(http://tablesorter.com/themes/blue/asc.gif);
    }
    table.tablesorter thead tr .headerSortDown {
        background-image: url(http://tablesorter.com/themes/blue/desc.gif);
    }
    table.tablesorter thead tr .headerSortDown, table.tablesorter thead tr .headerSortUp {
    background-color: #8dbdd8;
    }

	
</style>
</head>
<body>
		<select id="users" onchange="populateUserShoesRecords(); populateUserCoursesRecords()" ></select>
		<div class="RunLog" class="ui-widget">

			<div id="tabs">
				<ul>
					<li><a href="user_shoes.php">נעליים</a></li>
					<li><a href="user_courses.php">מסלולים</a></li>
				</ul>
			</div>

		</div>
		
</body>
</html>