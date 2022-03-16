import os
import ftplib

timestamp = os.popen('date +"%F %T"').read()
pi_hostname = os.popen('hostname').read()
pi_boot = os.popen('uptime -s').read()
pi_uptime = os.popen('uptime -p').read()
pi_temp = os.popen('cat /sys/class/thermal/thermal_zone0/temp').read()
pi_model = os.popen('cat /proc/device-tree/model').read()

file_name = '/home/pi/python/pi_uptime.txt'
f = open(file_name, 'w')
f.write(timestamp + pi_hostname + pi_boot + pi_uptime + pi_temp + pi_model)
f.close()

ftp = ftplib.FTP('yourdomain.com', 'ftpuser@yourdomain.com', 'ftppassword')
ftp.storbinary('STOR pi_uptime.txt', open(file_name, 'rb'))
ftp.quit()
