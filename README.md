<h1>Raspberry Pi Status Monitor V2</h1>

This is a simple idea how to monitor your Raspberry Pi status remotely via website located outside of your Raspberry

![alt text](https://github.com/kkuderko/raspberry-pi-status-monitor/blob/main/screenshot01.png)

In situations when your Pi is behind the firewall, you can't or you don't want to access it from public internet but you'd still like to know it's current status.

The Raspberry would send its status to the external FTP server and from there the data would be displayed on the website.
This will give you a quick glimpse if your Pi is up and running and you can incorporate this information into some wall dashboard for example.

My initial idea was to send the information to the external dashing or smashing https://github.com/Smashing/smashing dashboard but I was unable to make it work on my godaddy hosted webserver, so I made just a single tile. This way I can quickly check on my mobile if the Pi is up.

<h3>What you need is:</h3>

- raspberry pi
- webserver with FTP (i.e. some hosting)
 
<h3>Steps</h3>

1. On your Raspberry Pi, create python script <b>ftp.py</b> (make it executable by chmod +x) which will gather and upload data
2. Schedule the script in crontab to execute every 15 minutes: crontab -e and include the below line

    <code>*/15 * * * * /usr/bin/python /home/pi/python/ftp.py</code>

3. The executed script will upload the pi_uptime.txt text file with status like timestamp and uptime info to the external server
4. On your web server, create website <b>index.php</b> to display the data
5. Open your website and view the results.

<h3>Email Alerts (Bonus)</h3>
My webserver will check the heartbeat file and send the email alert if the file was received longer than 30min ago.
This can be done via shell script <b>email_alert.sh</b>

Similarily, we can schedule the script in crontab to run every 15 mins.

  <code>*/15 * * * * /path_to/email_alert.sh</code>
  
