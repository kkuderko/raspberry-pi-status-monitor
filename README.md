<h1>Raspberry Pi Status Monitor</h1>

This is simple idea how to monitor your Raspberry Pi status remotely via website located outside of your Raspberry

![alt text](https://github.com/kkuderko/raspberry-pi-status-monitor/blob/main/screenshot01.png)

In situations when your Pi is behind the firewall, you can't or you don't want to access it from public internet but you'd still like to know it's current status.

The Raspberry would send its status to the external FTP server and from there the data would be displayed on the website.

What you need is:
<ul>
  <li> raspberry pi </li>
  <li> webserver with FTP (i.e. some hosting) </li>
 </ul>
 
<h3>STEPS</h3>

1. Create python script on the Raspberry to gather data and upload to the external server via FTP

2. Schedule the script in crontab to execute every N minutes

2. Create website to display the data
