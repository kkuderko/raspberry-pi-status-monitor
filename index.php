<!DOCTYPE html>
<html>
<head>
    <title>Raspberry Pi Monitor</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="refresh" content="60">
<style>
    body {color: #ced4da; font-family: 'Open Sans', "Helvetica Neue", Helvetica, Arial, sans-serif; font-size: 20px; background-color: #212529;}
    .square-box{
    position: relative;
    width: 370px;
    height: 348px;
    overflow: hidden;
    background: #4679BD;
    }
    .square-box:before{
    content: "";
    display: block;
    padding-top: 100%;
    }
    .square-content{
    position:  absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    color: #ced4da;
    text-align: center;
    }
    .square-title{
    font-size: 20px;
    padding: 20px 0px 8px 0px;
    }
     .square-subtitle{
    font-size: 12px;
    padding: 0px 0px 0px 0px;
    }
    .square-text{
    font-size: 14px;
    padding: 4px;
    }
    .updated-at {
    position: absolute;
    font-size: 13px;
    color: rgba(0, 0, 0, 0.3);
    bottom: 0;
    width: 100%;
    text-align: center;
    padding: 4px;
    }
    .square-image{
    padding: 20px;    
    }
</style>
</head>
<body>
    
<?php
// process the input file and calculate time intervals
$lines = file('pi_uptime.txt');
$timezone = new DateTimeZone('Europe/London');
$date_now = new DateTime('now', $timezone);
$date_pi_last_heartbeat = new DateTime($lines[0], $timezone);
$heartbeat_interval = date_diff($date_pi_last_heartbeat, $date_now);
$date_pi_last_boot = new DateTime($lines[2], $timezone);
$boot_interval = date_diff($date_pi_last_boot, $date_now);
$to_time = strtotime($date_now->format('Y-m-d H:i:s'));
$boot_from_time = strtotime($lines[2]);
$boot_interval_min = round(abs($to_time - $boot_from_time) / 60,0);
$heartbeat_from_time = strtotime($lines[0]);
$heartbeat_interval_min = round(abs($to_time - $heartbeat_from_time) / 60,0);
$pi_temperature = round((int)$lines[4] / 1000,0);

// apply colour coding
$hostname_color = "#ffffff";
$heartbeat_color = "#ffffff";
$last_boot_color = "#ffffff";
$temperature_color = "#ffffff";
if (($heartbeat_interval_min >= 0) && ($heartbeat_interval_min <= 10)) $heartbeat_color = "#75a928";
else if (($heartbeat_interval_min >= 11) && ($heartbeat_interval_min <= 30)) $heartbeat_color = "#fad83e";
else if ($heartbeat_interval_min >= 31) $heartbeat_color = "#bc1142";

// display html
echo "<div class=\"square-box\"><div class=\"square-content\">";
echo "<div class=\"square-title\">Hostname: <span style=\"color: $hostname_color\">".$lines[1]."</div>";
echo "<div class=\"square-subtitle\">".$lines[5]."</div>";
echo "<img class=\"square-image\" src=\"raspberry_pi_logo.png\" width=\"80\" height=\"100\"><br>";
echo "<div class=\"square-text\">Heartbeat: <span style=\"color: $heartbeat_color\">".$heartbeat_interval->format('%a d, %h h, %i min, %s sec ago')."</span></div>";
echo "<div class=\"square-text\">Uptime: <span style=\"color: $last_boot_color\">".$lines[3]."</span></div>";
echo "<div class=\"square-text\">CPU Temperature: <span style=\"color: $temperature_color\">".$pi_temperature."</span> &#8451;</div>";    
echo "<div class=\"updated-at\">Updated at: ".$date_now->format('Y-m-d H:i:s')."</div>";
echo "</div></div>";
?>

</body>
</html>
