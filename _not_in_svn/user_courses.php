<?php
require_once 'php/bc_html_page_init.php';
?>

<script type='text/javascript'>
$(document).ready(function() {
	populateUserCoursesRecords();
});

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
                html += "<td>" + item.course_name + "</td>";
                html += "<td>" + item.length + "</td>";
				html += "<td>" + item.description + "</td>";
                html += "</tr>";
            });
            var tbody = $('#records_c');
            tbody.html(html);
        }
    });
}

$('#create_course_dialog').dialog(
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
			var course_data = getCourse();
			if (course_data != null)
			{
				//event_fields_data.date = the_date;
				var course_str = JSON.stringify(course_data);
				$.ajax({
					url : 'php/create_course.php',
					dataType : 'text',
					data : {
						courseStr : course_str
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
				populateUserCoursesRecords();
			}
		},
		"סגור" : function() {
			$(this).dialog("close");
		}
	}
});

function addCourse() {
		$('#course_name').val('');
		$('#length').mask('99.9', {placeholder:"0"});
		$('#length').val('00.0');
		$('#description').val('');
		$('#create_course_dialog').css('background-color', '#feffe5');
		$("#create_course_dialog").dialog("open");
}

function getCourse() {
	var courseFields = new Object();
	courseFields.course_name = $('#course_name').val();
	courseFields.length = $('#length').val();
	courseFields.description = $('#description').val();
	courseFields.runner_id = $("#users").val();
	return courseFields;
}

</script>
<div id="create_course_dialog" title="הוספת/עדכון מסלול">
<form>
<table border="0">
<tr>
	<td>
		<span>שם המסלול:</span>
	</td>
	<td>
		<input type="text" id="course_name" size="20"></input>
	</td>
</tr>
<tr>
	<td>
		<span>מרחק:</span>
	</td>
	<td>
		<input type="text" id="length">
	</td>
</tr>
<tr>
	<td>
		<div class="label" style="width:62px; float:right;">פרטים:</div>
	</td>
	<td>
		<textarea id="description" rows="4" cols="20"></textarea>
	</td>
</tr>
<tr><td>
</td></tr>
</table>
</form>
</div>

<input type="button" onclick="addCourse()" value="הוסף">

<h2>רשימת המסלולים</h2>
<table id="user_courses_table" class="tablesorter">

<thead align="center">
<th>שם המסלול</th>
<th>מרחק</th>
<th>תיאור</th>
</thead>

<tbody id="records_c">
</tbody>

</table>
