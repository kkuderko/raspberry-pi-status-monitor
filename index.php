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
    margin: 10px 10px 10px 0px;
    overflow: hidden;
    /* blue background: #4679BD; */
    background: #181818; /* dark mode */
    
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
    color: #b3b3b3;
    text-align: center;
    }
    .square-title{
    font-size: 16px;
    padding: 20px 0px 8px 0px;
    }
     .square-subtitle{
    font-size: 12px;
    padding: 2px;
    }
    .square-text{
    font-size: 14px;
    padding: 3px;
    }
    .updated-at {
    position: absolute;
    font-size: 13px;
    color: rgba(150, 150, 150, 0.3);
    bottom: 0;
    width: 100%;
    text-align: center;
    padding: 4px;
    }
    .square-image{
    padding: 18px;    
    }
</style>
</head>
<body>
    
<?php
$timezone = new DateTimeZone('Europe/London');
$date_now = new DateTime('now', $timezone);

// process the input files and calculate time intervals
$pi_lines = file('pi_uptime.txt');
$pi_last_heartbeat_date = new DateTime($pi_lines[0], $timezone);
$pi_heartbeat_interval = date_diff($pi_last_heartbeat_date, $date_now);
$pi_last_boot_date = new DateTime($pi_lines[2], $timezone);
$pi_boot_interval = date_diff($pi_last_boot_date, $date_now);
$pi_to_time = strtotime($date_now->format('Y-m-d H:i:s'));
$pi_boot_from_time = strtotime($pi_lines[2]);
$pi_boot_interval_min = round(abs($pi_to_time - $pi_boot_from_time) / 60,0);
$pi_heartbeat_from_time = strtotime($pi_lines[0]);
$pi_heartbeat_interval_min = round(abs($pi_to_time - $pi_heartbeat_from_time) / 60,0);
$pi_temperature = round((int)$pi_lines[4] / 1000,0);

$win_lines = file('win_stats.txt');
$win_last_heartbeat_date = new DateTime($win_lines[0], $timezone);
$win_heartbeat_interval = date_diff($win_last_heartbeat_date, $date_now);
$win_last_boot_date = new DateTime($win_lines[5], $timezone);
$win_boot_interval = date_diff($win_last_boot_date, $date_now);
$win_to_time = strtotime($date_now->format('Y-m-d H:i:s'));
$win_boot_from_time = strtotime($win_lines[5]);
$win_boot_interval_days = floor(abs($win_to_time - $win_boot_from_time) / 86400);
$win_heartbeat_from_time = strtotime($win_lines[0]);
$win_heartbeat_interval_min = round(abs($win_to_time - $win_heartbeat_from_time) / 60,0);


// apply colour coding
$pi_hostname_color = "#ffffff";
$pi_heartbeat_color = "#ffffff";
$pi_last_boot_color = "#ffffff";
$pi_temperature_color = "#ffffff";

$win_hostname_color = "#ffffff";
$win_heartbeat_color = "#ffffff";
$win_last_boot_color = "#ffffff";
$win_temperature_color = "#ffffff";
$win_disk_color = "#ffffff";

if (($pi_heartbeat_interval_min >= 0) && ($pi_heartbeat_interval_min <= 10)) {
    $pi_heartbeat_color = "#75a928";
} elseif (($pi_heartbeat_interval_min >= 11) && ($pi_heartbeat_interval_min <= 30)) {
    $pi_heartbeat_color = "#fad83e";
} elseif ($pi_heartbeat_interval_min >= 31) {
    $pi_heartbeat_color = "#bc1142";
}

if (($win_heartbeat_interval_min >= 0) && ($win_heartbeat_interval_min <= 10)) {
    $win_heartbeat_color = "#75a928";
} elseif (($win_heartbeat_interval_min >= 11) && ($win_heartbeat_interval_min <= 30)) {
    $win_heartbeat_color = "#fad83e";
} elseif ($win_heartbeat_interval_min >= 31) {
    $win_heartbeat_color = "#bc1142";
}

if (str_replace(array("\r", "\n"), '', $pi_lines[5]) == "OFF") {
    $pi_fan_color = "#ffffff";
} else {
    $pi_fan_color = "#fad83e";
}
$pi_fan_sc_color = "#ffffff";

// display pi tile
echo "<div class=\"square-box\"><div class=\"square-content\">";
echo "<div class=\"square-title\">OS: <span style=\"color: $pi_hostname_color\">".$pi_lines[1]."</div>";
echo "<div class=\"square-subtitle\">HW: ".$pi_lines[8]."</div>";
    
echo "<img class=\"square-image\" src=\"raspberry_pi_logo.png\" width=\"80\" height=\"100\">";

echo "<div class=\"square-text\">Heartbeat: <span style=\"color: $pi_heartbeat_color\">".$pi_heartbeat_interval->format('%a d, %h h, %i min, %s sec ago')."</span></div>";
echo "<div class=\"square-subtitle\">Uptime: <span style=\"color: $pi_last_boot_color\">".$pi_lines[3]."</span></div>";
echo "<div class=\"square-text\">CPU Temperature: <span style=\"color: $pi_temperature_color\">".$pi_temperature."</span> &#8451;</div>";
echo "<div class=\"square-text\">Fan Status: <span style=\"color: $pi_fan_color\">".$pi_lines[5]."</div>";
echo "<div class=\"square-subtitle\">Fan Status Change: <span style=\"color: $pi_fan_sc_color\">".$pi_lines[6]."( ".$pi_lines[7].")</div>";

echo "<div class=\"updated-at\">Updated at: ".$date_now->format('Y-m-d H:i:s')."</div>";
echo "</div></div>";

// display win tile
echo "<div class=\"square-box\"><div class=\"square-content\">";
echo "<div class=\"square-title\">OS: <span style=\"color: $win_hostname_color\">".$win_lines[1]."</div>";
echo "<div class=\"square-subtitle\">HW: ".$win_lines[2]."".$win_lines[3]."</div>";
    
echo "<img class=\"square-image\" src=\"win_logo.png\" width=\"100\" height=\"100\">";

echo "<div class=\"square-text\">Heartbeat: <span style=\"color: $win_heartbeat_color\">".$win_heartbeat_interval->format('%a d, %h h, %i min, %s sec ago')."</span></div>";
echo "<div class=\"square-text\">Uptime: <span style=\"color: $win_last_boot_color\">".$win_boot_interval_days."</span> days</div>";
echo "<div class=\"square-text\">PC Temperature: <span style=\"color: $win_temperature_color\">".$win_lines[6]."</span> &#8451;</div>";
echo "<div class=\"square-subtitle\">Disk Capacity: <span style=\"color: $win_disk_color\">".$win_lines[7]."</div>";

echo "<div class=\"updated-at\">Updated at: ".$date_now->format('Y-m-d H:i:s')."</div>";
echo "</div></div>";

?>
</body>
</html>