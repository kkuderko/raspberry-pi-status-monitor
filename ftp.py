import os
import ftplib

fan_file_name = '/storage/.config/fan_status.txt'
pi_file_name = '/storage/.config/pi_uptime.txt'

timestamp = os.popen('date +"%F %T"').read()
pi_osver = os.popen('cat /etc/release').read()
pi_hostname = os.popen('hostname').read()
pi_hostname = pi_hostname[:-1] + ' (' + pi_osver[:-1] + ')\n'
pi_boot = os.popen('uptime -s').read()
pi_uptime = os.popen('uptime').read()
pi_uptime = pi_uptime.split(" ", 2)[2]
pi_temp = os.popen('cat /sys/class/thermal/thermal_zone0/temp').read()
pi_fan = "N/A\n"
pi_fan_sc = os.popen('date -r /storage/.config/fan_status.txt -u +"%Y-%m-%d %H:%M"').read()
pi_fan_mode = os.popen('ps | grep fan.py | grep -v grep | wc -l').read()
if pi_fan_mode == 0:
	pi_fan_mode = "off\n"
else:
	pi_fan_mode = "auto\n"
pi_model = os.popen('cat /proc/device-tree/model').read()

ff = open(fan_file_name, 'r')
pi_fan = ff.readline()
ff.close()

pf = open(pi_file_name, 'w')
pf.write(timestamp + pi_hostname + pi_boot + pi_uptime + pi_temp + pi_fan + pi_fan_sc + pi_fan_mode + pi_model)
pf.close()

ftp = ftplib.FTP('ftp.host.com', 'user', 'password')

ftp.storbinary('STOR pi_uptime.txt', open(pi_file_name, 'rb'))

ftp.quit()
