<h1>Raspberry Pi Status Monitor V2</h1>

This is an addition to this main branch to monitor Windows PC too

![alt text](https://github.com/kkuderko/raspberry-pi-status-monitor/blob/v2/screenshot02.png)

win_stats.ps1 powershell script gets executed every 15min by Windows Task Scheduler

Make sure the user to run the task with have anough permissions to execute script.

Scheduled Task General properties:

Run whether user is logged on or not: Enabled

Run with highest privileges

Actions:

Action: Start a program

Program/script: C:\Windows\System32\WindowsPowerShell\v1.0\powershell.exe

Add arguments: -NoProfile -NoLogo -NonInteractive -ExecutionPolicy Bypass -File "c:\scripts\win_stats.ps1"

Start in: c:\scripts\
