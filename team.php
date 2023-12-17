<?php
require_once 'php/html_page_init.php';
?>
<!DOCTYPE HTML>
<meta http-equiv="X-UA-Compatible" content="IE=edge"/>
<html dir="RTL">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>הרוח השניה - יומן קבוצתי</title>
    <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.5.1/build/cssreset/cssreset-min.css">
    <link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet"
          type="text/css"/>
    <link href='./css/fullcalendar.css?v=<?php echo CSS_VERSION;?>' rel='stylesheet' type='text/css'/>
    <link href='./css/jquery.qtip.min.css' rel='stylesheet' type='text/css'/>
    <link href='./css/runlog.css' rel='stylesheet' type='text/css'/>

    <script src="./js/jquery.min.js" type="text/javascript"></script>
    <script src="./js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="./js/jquery.qtip.js" type="text/javascript"></script>
    <script src="./js/jquery.elastic.js" type="text/javascript"></script>
    <script src="./js/jquery.maskedinput.js" type="text/javascript"></script>
    <script src="./js/json2.js" type="text/javascript"></script>
    <script src="./js/fullcalendar.js?v=<?php echo JS_VERSION;?>" type="text/javascript"></script>
    <script src="./js/constants.js?v=<?php echo JS_VERSION;?>" type="text/javascript"></script>
    <script src="./js/json_sans_eval.js"></script>
    <script src="./js/jquery.ui.datepicker-he.js" type="text/javascript"></script>
    <script src="./js/functions.js?v=<?php echo JS_VERSION;?>" type="text/javascript"></script>

    <script type="text/javascript">
        if (jQuery.browser.msie && jQuery.browser.version.indexOf("8.") == 0) {
            if (typeof JSON !== 'undefined') {
                JSON.parse = null;
            }
        }

        $(document).ready(function () {
        	$.ajax({
            url:'php/get_team_events.php',
            dataType:'text',
            data:{
                start_date:'2013-03-17'
            },
            success:function (txt) {
                var doc = Utils.parseJSON(txt);
                if (doc.status.ecode == ERR) {
                    alert("Fetch events failed - " + doc.status.emessage);
                } else {
                	var runner_name = '';
                	var html = [];
                	var htmli = 0;
                	
                	html[htmli++] = '<table cellspacing="0" cellpadding="0" border="1">';
                	html[htmli++] = '<tr><td>ראשון</td><td>שני</td><td>שלישי</td><td>רביעי</td><td>חמישי</td><td>שישי</td><td>שבת</td></tr>';
                	for (var i in doc.data) {
                		var activity = doc.data[i];
                		if (runner_name != activity['runner_name']) {
                			runner_name = activity['runner_name'];
                			html[htmli++] = '<tr><td colspan="7"><b>'+runner_name+'</b></td></tr>';
                			
                			html[htmli++] = '<tr>';
                			for (var d = 17; d < 24; d++){
                				html[htmli++] = '<td id="'+d+'_'+activity['runner_id']+'"></td>';
                			}
                			html[htmli++] = '</tr>';
                		}
                	}
                	html[htmli++] = '</table>';
                	$('#calendar').append(html.join(''));
                	
                	for (var i in doc.data) {
                		var activity = doc.data[i];
                		var d = activity['start'].split(' ')[0].split('-')[2];
                		var runner_id = activity['runner_id'];
                		
                		$('#'+d+'_'+runner_id).append('<div class="event" style="background-color:'+Calendar.getEventColor(activity.run_type_id)+'; border:solid 1px '+Calendar.getEventBorderColor(activity.run_type_id)+';">'+Calendar.getEventTitle(activity)+'</div>');
                	}
                /*
                    var events = [];
                    $.each(doc.data, function (index, item) {
                        events.push({
                            event_id:item.id,
                            title:Calendar.getEventTitle(item),
                            start:item.start,
                            member_id:the_member_id,
                            color:Calendar.getEventColor(item.run_type_id),
                            borderColor:Calendar.getEventBorderColor(item.run_type_id)
                        });
                    });
                    */
                }
            }
        });

        });
    </script>
    <style>
        #calendar {
            width: 920px;
            margin: 0 auto;
        }
        
        #calendar table {
        	margin: 20px 0;
        	table-layout: fixed;
        	border-color: #cccccc;
        }
        
        #calendar td {
        	width: 14%;
        	vertical-align: top;
        	border-color: #cccccc;
        }
        
        .event {
        	margin: 10px 2px;
        	border-radius: 2px;
        	font-size: 10px;
        }
    </style>
</head>
<body>
<?php require 'widgets/bc_header.php'; ?>
<div id='calendar'></div>
</body>
</html>