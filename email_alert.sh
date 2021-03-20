#!/bin/bash
declare -a array=()
i=0

# read the email flag file
while IFS= read -r line; do
    array[i]=$line
    let "i++"
done < "/home/kkuderko/public_html/pi/email_flag.txt"

# calculate date difference
file_date=`stat -c %Y /home/kkuderko/public_html/pi/pi_uptime.txt`
now_date=`date "+%s"`
time_diff=$((now_date - file_date))

# send email if heartbeat longer than 30min ago and email not yet sent
if [[ $time_diff -gt 1800 ]] && [[ array[0] -eq 0 ]]
then
  echo "Last Heartbeat: $((time_diff / 60)) min ago!" | mailx -s 'Raspberry Pi Alert' -r 'info@yourdomain.com' your@email.com
  echo "1" > /home/kkuderko/public_html/pi/email_flag.txt 
fi

# reset the sent email flag
if [[ $time_diff -le 1800 ]] && [[ array[0] -eq 1 ]]
then 
  echo "0" > /home/kkuderko/public_html/pi/email_flag.txt 
fi