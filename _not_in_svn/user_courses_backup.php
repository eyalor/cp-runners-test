<?php
require_once 'php/bc_html_page_init.php';
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style>
    /* tables */
	body{
		font-family:arial;
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

<link href="css/CalendarControl.css" rel="stylesheet" type="text/css">

<script src="./js/jquery.min.js"></script>
<script src="./js/json2.js" type="text/javascript"></script>
<script src="./js/json_sans_eval.js"></script>
<script src="./js/constants.js" type="text/javascript"></script>
<script src="./js/functions.js" type="text/javascript"></script>
<script src="./js/table_sorter.js" type="text/javascript"></script>
<script src="./js/CalendarControl.js" language="javascript"></script>
   
<script type='text/javascript'>

function populateUserCoursesRecords() {
	var the_runner_id = $("#users").val();
    $.ajax({
        url : 'php/user_courses.php',
        dataType : 'json',
        data : {
			runner_id : the_runner_id
            // empty - for now
        },
        success : function(json) {
            var html = "";
            $.each(json, function(index, item) {
				var d = new Date(item.start_using_date);
				var the_month = d.getMonth();
				var the_year = d.getFullYear();
                html += "<tr align='center'>";
                html += "<td>" + item.name + "</td>";
                html += "<td>" + item.length + "</td>";
				html += "<td>" + item.description + "</td>";
                html += "</tr>";
            });
            var tbody = $('#records');
            tbody.html(html);
        }
    });
}

function addCourse() {
}

$(document).ready(function() {
    $("#user_courses_table").tablesorter();
	Functions.populateUsersSelect();

});
</script>
</head>
<body dir="rtl">
<form>
<table border="0">
<tr>
	<td>
		<span>שם המסלול:</span>
	</td>
	<td>
		<input id="name" size="20"></input>
	</td>
</tr>
<tr>
	<td>
		<span>מרחק:</span>
	</td>
	<td>
		<input type="text" id="run_distance">
	</td>
</tr>
<tr>
	<td>
		<div class="label" style="width:62px; float:right;">פרטים:</div>
	</td>
	<td>
		<textarea rows="4" cols="20"></textarea>
	</td>
</tr>
</table>
<input type="button" onclick="addCourse()" value="הוסף">
</form>
<h2>רשימת המסלולים</h2>
<select id="users" onchange="populateUserCoursesRecords()" onload="populateUserShoesRecords()" ></select>
<table id="user_courses_table" class="tablesorter">

<thead align="center">
<th>שם המסלול</th>
<th>מרחק</th>
<th>תיאור</th>
</thead>

<tbody id="records">
</tbody>

</table>
</div>
</body>
</html>