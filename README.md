<h1>Raspberry Pi Status Monitor</h1>

This is simple idea how to monitor your Raspberry Pi status remotely via website located outside of your Raspberry

![alt text](https://github.com/kkuderko/raspberry-pi-status-monitor/blob/main/screenshot01.png)

In situations when your Pi is behind the firewall, you can't or you don't want to access it from public internet but you'd still like to know it's current status.

The Raspberry would send its status to the external FTP server and from there the data would be displayed on the website.
This will give you a quick glimpse if your Pi is up and running and you can incorporate this information into some wall dashboard for example.

What you need is:
- raspberry pi
- webserver with FTP (i.e. some hosting)
 
<h3>STEPS</h3>

1. Create python script on the Raspberry which will gather data and upload it to the external server via FTP: <b>ftp.py</b> (make it executable by chmod +x)

2. Schedule the script in crontab to execute every 15 minutes: crontab -e and include the below line:

    <code>*/15 * * * * /usr/bin/python /home/pi/python/ftp.py</code>

3. The executed script will upload the pi_uptime.txt text file with status like timestamp and uptime info to the external server
 
4. Create website to display the data: <b>index.php</b>

5. Open the website and view the results.

<sup>My initial idea was to send the information to the external dashing or smashing https://github.com/Smashing/smashing dashboard but I was unable to make it work on my godaddy hosted webserver, so I made just a single tile. This way I can quickly check on my mobile if the Pi is up.
As a next step I'd like to have a email notification if the Pi goes offline (i.e heartbeat was longer than 30 min ago)</sup>
