import os
import ftplib

pi_model = os.popen('cat /proc/device-tree/model').read()
timestamp = os.popen('date +"%F %T"').read()
pi_boot = os.popen('uptime -s').read()
pi_uptime = os.popen('uptime -p').read()
file_name = '/home/pi/python/pi_uptime.txt'

f = open(file_name, 'w')
f.write(timestamp + pi_boot + pi_uptime + pi_model)
f.close()

ftp = ftplib.FTP('yourhost.com', 'ftpuser@yourhost.com', 'password')
ftp.storbinary('STOR pi_uptime.txt', open(file_name, 'rb'))
ftp.quit()