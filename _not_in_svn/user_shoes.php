<?php
require_once 'php/bc_html_page_init.php';
?>
<meta charset="utf-8">
<script src="./js/functions.js" type="text/javascript"></script>   
<script type="text/javascript"
        src="./js/jquery.ui.datepicker-he.js">
</script>
<script type='text/javascript'>

$(function() {
	$( "#datepicker" ).datepicker();
	$( "#datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
	$( "#datepicker" ).datepicker( "option", "showAnim", "clip" );
	$.datepicker.regional['he']
});

	
function populateUserShoesRecords() {
	var the_runner_id = $("#users").val();;
    $.ajax({
        url : 'php/user_shoes.php',
        dataType : 'json',
        data : {
			runner_id : the_runner_id
            // empty - for now
        },
        success : function(json) {
        	if (json.status.ecode == BC_ERR) {
				alert("Failed to get shoes data - " + json.status.emessage);
        	} else {
	            var html = "";
	            $.each(json.data, function(index, item) {
					var d = new Date(item.start_using_date);
					var the_month = d.getMonth();
					the_month++;
					var the_year = d.getFullYear();
	                html += "<tr align='center'>";
	                html += "<td>" + item.name + "</td>";
	                html += "<td>" + (item.active == 0 ? 'לא' : 'כן') + "</td>";
	                html += "<td>" + the_month + '/' + the_year + "</td>";
	                html += "<td>" + item.type + "</td>";
	                html += "<td>" + item.distance + "</td>";
	                html += "</tr>";
	            });
	            var tbody = $('#records');
	            tbody.html(html);
        	}
         }
    });
}

$('#create_shoe_dialog').dialog(
{
	height : 300,
	width : 380,
	show : {
		effect : 'drop',
		direction : 'rtl'
	},
	autoOpen: false,
	buttons : {
		"אשר" : function() {
			var shoe_data = getShoe();
			if (shoe_data != null)
			{
				//event_fields_data.date = the_date;
				var shoe_str = JSON.stringify(shoe_data);
				$.ajax({
					url : 'php/create_shoe.php',
					dataType : 'text',
					data : {
						shoeStr : shoe_str
					},
					success : function(txt) {
						var doc = Utils.parseJSON(txt);
						if (doc.status.ecode == BC_ERR) {
							alert("Create failed: "
									+ doc.status.emessage);
						} else {
							//$('#calendar').fullCalendar('render');

						}
					}
				});
				$(this).dialog("close");
				populateUserShoesRecords();
			}
		},
		"סגור" : function() {
			$(this).dialog("close");
		}
	}
});

function addShoe() {
		$('#shoe_name').val('');
		$('#datepicker').val('');
		$('#type').val('כביש');
		$('#active').attr('checked', 'checked');
		$('#create_shoe_dialog').css('background-color', '#feffe5');
		$("#create_shoe_dialog").dialog("open");
}

function getShoe() {
	var shoeFields = new Object();
	shoeFields.shoe_name = $('#shoe_name').val();
	shoeFields.start_using_date = $('#datepicker').val();
	shoeFields.type = $('#type').val();
	shoeFields.active = $('input:radio[name=active]:checked').val();
	shoeFields.runner_id = $("#users").val();
	return shoeFields;
}

</script>
<div id="create_shoe_dialog" title="הוספת/עדכון נעל">
<form>
<table border="0">
<tr>
	<td>
		<span>שם הנעל:</span>
	</td>
	<td>
		<input id="shoe_name" size="20"></input>
	</td>
</tr>
<tr>
	<td>
		<span>תחילת שימוש:</span>
	</td>
	<td>
		<input type="text" size="15" name="start_using_date" id="datepicker">
	</td>
</tr>
<tr>
	<td>
		<label for="type">סוג נעל:</label>
	</td>
	<td>
		<select id="type">
		  <option value="1">כביש</option>
		  <option value="2">שטח</option>
		  <option value="3">אימון קלה</option>
		  <option value="4">תחרות</option>
		</select>
	</td>
</tr>
<tr>
	<td>
		<label for="active">בשימוש:</label>
	</td>
	<td>
		<input type="radio" name="active" value="1" checked> כן
		<input type="radio" name="active" value="0"> לא
	</td>
</tr>
</table>
</form>
</div>
<input type="button" onclick="addShoe()" value="הוסף">

<h2>רשימת הנעליים</h2>
<div id="data">
<table id="user_shoes_table" class="tablesorter">

<thead align="center">
<th>שם</th>
<th>אקטיבית</th>
<th>תחילת שימוש</th>
<th>סוג</th>
<th>סך קילומטרים</th>

</thead>

<tbody id="records">
</tbody>

</table>
</div>
