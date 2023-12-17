<?php
require_once 'php/html_page_init.php';
?>
<!DOCTYPE HTML>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>

<Cache-Control: no-cache, no-store, must-revalidate
Pragma: no-cache
Expires: 0>
<meta http-equiv="content-type" content="text/html; charset=utf-8"/>

<title>הרוח השניה - תוצאות</title>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.5.1/build/cssreset/cssreset-min.css">
<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet"
      type="text/css"/>
<link href='./css/runlog.css?v=<?php echo CSS_VERSION;?>' rel='stylesheet' type='text/css'/>

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script src="./js/jquery.min.js" type="text/javascript"></script>
<script src="./js/functions.js?v=<?php echo JS_VERSION;?>" type="text/javascript"></script>
<script src="./js/jquery-ui.min.js" type="text/javascript"></script>
<script src="./js/constants.js?v=<?php echo JS_VERSION;?>" type="text/javascript"></script>
<script src="./js/json_sans_eval.js"></script>
<script src="./js/jquery.ui.datepicker-he.js" type="text/javascript"></script>
<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">

var memberId = <?php echo $memberId; ?>;

function drawRunnerCharts() {
    drawRunnerTable();
}


function drawRunnerTable() {
	var runner_id = $('#users').val();
	var jsonData = null;
    var jsonDataResult = $.ajax({
        url:'./php/get_races_report.php',
        dataType:"json",
        data:{
			runner_id:runner_id
        },
        success:(
            function (data) {
				drawRunnerTableCallback(data);
            })
    });
}


function drawRunnerTableCallback(jsonData) {
    if (jsonData.data == "") {
        if (jsonData.status.ecode == STATUS_ERR) {
            $("#errMsg1").css("color", "red");
            $("#errMsg1").html(jsonData.status.emessage);
        } else {
            $("#errMsg1").css("color", "blue");
            $("#errMsg1").html("אין נתונים, נסה שוב");
        }
        $("#errMsg1").show();
        $("#dashboard").hide();

        return;
    } else {
        $("#errMsg1").hide();
        $("#dashboard").show();
    }

	var cssClassNames = {
    'headerRow': 'italic-darkblue-font large-font bold-font',
    'tableRow': '',
    'oddTableRow': 'beige-background',
    'selectedTableRow': 'orange-background large-font',
    'hoverTableRow': '',
    'headerCell': 'gold-border bold-font center-text orchid-background',
    'tableCell': 'center-text',
    'rowNumberCell': 'underline-blue-font'};
	var options = {'cssClassNames': cssClassNames, 'allowHtml': true};
    var data = new google.visualization.DataTable();
	data.addColumn('string', 'id');
	data.addColumn('string', 'runner id');
    data.addColumn('string', 'שנה');
    data.addColumn('string', 'מרוץ');
    data.addColumn('string', 'סוג');
    data.addColumn('string', 'תוצאה');
    data.addColumn('string', 'קצב');
	data.addColumn('string', 'הערות');
	data.addRows(jsonData.data);
	data.setProperty(0, 2, 'style', 'width:25%');
	data.setProperty(0, 3, 'style', 'width:35%');
	var view = new google.visualization.DataView(data);
	view.hideColumns([0]);
	view.hideColumns([1]);

    var table = new google.visualization.ChartWrapper({
        'chartType':'Table',
        'containerId':'report',
        'options':{
			width:'620px'
        }
    });
	
	var categoryPicker = new google.visualization.ControlWrapper({
        'controlType':'CategoryFilter',
        'containerId':'control1',
        'options':{
            'filterColumnLabel':'מרוץ',
            'ui':{
                'labelStacking':'vertical',
                'allowTyping':false,
                'allowMultiple':false,
                'label':'סנן לפי מרוץ',
                'caption':'כל המרוצים',
                'cssClass':'bc'
            }
        }
    });

	var categoryPicker1 = new google.visualization.ControlWrapper({
        'controlType':'CategoryFilter',
        'containerId':'control2',
        'options':{
            'filterColumnLabel':'סוג',
            'ui':{
                'labelStacking':'vertical',
                'allowTyping':false,
                'allowMultiple':false,
                'label':'סנן לפי סוג מרוץ',
                'caption':'כל הסוגים',
                'cssClass':'bc'
            }
        }
    });

	var user_admin = '<?php echo $memberId; ?>';
	
	new google.visualization.events.addListener(table, 'ready', function () {
	        google.visualization.events.addListener(table.getChart(), 'select', function () {
			    var selection = table.getChart().getSelection();
				var currentRow = selection[0].row;
				var chartDataView = table.getDataTable();
				var true_ind = chartDataView.getUnderlyingTableRowIndex(selection[0].row);
 
				$('#results_race_id').val(data.getValue(true_ind,0));
				$('#results_runner_id').val(data.getValue(true_ind,1));
				$('#results_race_date').text(data.getValue(true_ind,4));
				$('#results_race_name').text(data.getValue(true_ind,3));
				$('#notes').val(data.getValue(true_ind,7));
				$('#run_time').val(data.getValue(true_ind,5));
				$('#runner_results_dialog').css('background-color', '#feffe5');
				$('#runner_results_dialog .label1').css({
					'color': 'purple',
					'font-weight': 'bold'
				});
				if (user_admin == '122'){
					var dialogTitle = 'עדכן תוצאה';
					$("#runner_results_dialog").dialog({ title: dialogTitle });
					$("#runner_results_dialog").dialog("open");
							
				}
				else{
					populateRacesResults(data.getValue(true_ind,0),data.getValue(true_ind,3),data.getValue(true_ind,2));
				}
    	    });

	});

    new google.visualization.Dashboard(document.getElementById('dashboard')).
        // Establish bindings, declaring the both the slider and the category
        // picker will drive both charts.
        bind([categoryPicker, categoryPicker1], [table, table]).
        // Draw the entire dashboard.
		draw(view);
}

function getResult() {
	var result = {
        race_id : $('#results_race_id').val(),
		runner_id : $('#results_runner_id').val(),
		run_time : Time.convertHMMSSToSeconds($('#run_time').val()),
		notes : $('#notes').val()
    };

	return result;
}

function populateRacesResults(raceId,raceName,raceDate) {
    $.ajax({
        url : 'php/get_race_results.php',
        dataType : 'text',
		data:{
			race_id: raceId
		},

        success : function(txt) {
            var doc = Utils.parseJSON(txt);
        	if (doc.status.ecode == STATUS_ERR) {
				alert("Failed to get races data - " + doc.status.emessage);
        	} else {
	            var html = "";
				//html += '<span>' + raceName + '</span>';
                race_results = new Array();
	            $.each(doc.data, function(index, item) {
					race_results[item.runner_id] = item;		
					html += '<tr id='+item.runner_id+'>';
//						html += '<td>' + item.race_date + '</td>';
					html += '<td>' + item.runner_name + '</td>';
					html += '<td>' + Time.convertSecondsToHMMSS(item.run_time) + '</td>';
					html += '<td>' + item.notes + '</td>';
					html += '</tr>';	            
				});
                		
	            $('#records').html(html);
                
        	}
         }
    });
	var dialogTitle = 'תוצאות '.concat(raceName);
	dialogTitle = dialogTitle.concat(' - ');
	dialogTitle = dialogTitle.concat(raceDate);
	$('#race_results_dialog').css('background-color', '#feffe5');
	$("#race_results_dialog").dialog({ title: dialogTitle });
	$("#race_results_dialog").dialog("open");
}

$(document).ready(function () {
	
    $('#runner_results_dialog').dialog(
		
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
                var result = getResult();
				if (result != null)
                {
                    var result_str = JSON.stringify(result);
					var url = 'php/update_result.php';
                    $.ajax({
                        url : url,
                        dataType : 'text',
                        data : {
                            resultStr : result_str
                        },
                        success : function(txt) {
                            var doc = Utils.parseJSON(txt);
                            if (doc.status.ecode == STATUS_ERR) {
                                alert(doc.status.emessage);
                            }
                        }
                    });
					drawRunnerCharts();
                    $(this).dialog("close");
					
                }
            },
            "סגור" : function() {
                $(this).dialog("close");
            },
            "מחק" : function() {
                var result = getResult();
				if (result != null)
                {
                    var result_str = JSON.stringify(result);
					
					var url = 'php/delete_result.php';
                    $.ajax({
                        url : url,
                        dataType : 'text',
                        data : {
                            resultStr : result_str
                        },
                        success : function(txt) {
                            var doc = Utils.parseJSON(txt);
                            if (doc.status.ecode == STATUS_ERR) {
                                alert(doc.status.emessage);
                            }
                        }
                    });
                    $(this).dialog("close");
                }
            }
        }
    });


    $('#race_results_dialog').dialog(
		
       {
        height : 500,
        width : 380,
        show : {
            effect : 'drop',
            direction : 'rtl'
        },
        autoOpen: false,
        buttons : {
            "סגור" : function() {
                    $(this).dialog("close");
					
                }
        }
    });

	$(function () {
		
        Functions.populateUsersSelect();
		drawRunnerCharts();
		
    });

});
</script>

<style>
  .bold-green-font {
    font-weight: bold;
    color: green;
  }

  .bold-font {
    font-weight: bold;
  }

  .center-text {
    text-align: center;
  }

  .right-text {
    text-align: right;
  }

  .large-font {
    font-size: 15px;
  }

  .italic-darkblue-font {
    font-style: italic;
    color: darkblue;
  }

  .italic-purple-font {
    font-style: italic;
    color: purple;
  }

  .underline-blue-font {
    text-decoration: underline;
    color: blue;
  }

  .gold-border {
    border: 3px solid gold;
  }

  .deeppink-border {
    border: 3px solid deeppink;
  }

  .orange-background {
    background-color: orange;
  }

  .orchid-background {
    background-color: #e6ffff;
  }

  .beige-background {
    background-color: beige;
  }
</style>
<style>
#runner_race_dialog {
    padding-left: 25px;
    padding-right: 25px;
	
}
#runner_race_dialog .label {
    width: 85px;
    font-size: 11px;
    font-weight: bold;
    display: inline-block;
}
#runner_race_dialog input {
    width: 150px;
}
#runner_race_dialog select {
    width: 158px;
	
#runner_race_dialog #description
{
    width: 233px;
    height: 52px;
    padding: 0 3px;
    max-height: 86px;
}
</style>


<style>
    .label {
        font-weight: bold;
    }
    #control1 .bc .goog-inline-block
    {
        margin-top: 3px;
        direction: ltr;
    }
    #report .google-visualization-table-th
    {
        text-align: right;
    }
    .bc .goog-menu-button-caption
    {
        padding: 0 0 0 4px;
    }
    .goog-menu .goog-menuitem
    {
        padding: 4px 28px 4px 7em;
    }
    .goog-menu .goog-menuitem-highlight
    {
        padding-top: 3px;
        padding-bottom: 3px;
    }
    .goog-menu .goog-menuitem-checkbox
    {
        left: auto;
        right: 6px;
    }
</style>
</head>

<html dir="RTL">
<body>

<?php require 'widgets/header.php'; ?>

<script type="text/javascript">
      google.charts.load('current', {
        packages: ['controls', 'corechart', 'table'],
        callback: drawRunnerCharts
	});
    //google.load('visualization', '1.0', {packages:['corechart']});
    //google.load('visualization', '1.0', {packages:['controls']});
    //google.load('visualization', '1.0', {packages:['table']});
</script>

<div class="RunLog" class="ui-widget" style="width:920px; margin-left:auto; margin-right:auto; padding-bottom:40px;">
	<div id="runner_results_dialog" >
		<form>
			<div style="margin-top:15px;">
				<span id="results_race_name" class="label1"></span>
			</div>
			
			<div style="margin-top:5px;">
				<span id="results_race_date" class="label1"></span>
			</div>
			<div style="margin-top:15px;">
				<span id="results_runner_name" class="label1"></span>
			</div>
			<div style="margin-top:15px;">
				<span class="label">תוצאה:</span>
				<input id="run_time" >
			</div>
			<div style="margin-top:5px;">
				<span class="label">פרטים:</span>
				<input id="notes">
			</div>			
			<input type="hidden" id="results_runner_id" >
			<input type="hidden" id="results_race_id" >
		</form>
	</div>
	<div id="race_results_dialog" style="margin-top:20px;">
		<table id="races_table" class="tablesorter">
			<thead>
				<th>רץ</th>
				<th>תוצאה</th>
				<th>הערות</th>
			</thead>
			<tbody id="records"></tbody>
		</table>
	</div>	
	<div style="margin-top:15px;">
        <span class="label">רץ:&nbsp;</span>
        <select id="users" onchange="drawRunnerCharts();"></select>
		<br><br><br>
		<span class="label" style="color:red">ניתן לסנן לפי רץ,מרוץ או סוג מרוץ ולמיין על ידי לחיצה על ראש הטור בטבלה<br>
		לחיצה על שורת המרוץ תביא את תוצאות שאר החברים שהשתתפו
		</span>
    </div>
    <div id="dashboard" style="width:100%;">
		<h2 class="page_header" >תוצאות מרוצים</h2>
		<br>
		<div id="errMsg1" style="margin-top:15px;"></div>
	    <div id="report" style="width:85%; float:left;"></div>
        <div style="width:15%; float:right; ">
            <div id="control1"></div>
            <br>

            <div id="control2"></div>
        </div>
		
    </div>
	
    <div style="clear:both;"></div>


</div>

</body>
</html>

