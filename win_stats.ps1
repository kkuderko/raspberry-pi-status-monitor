$timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
$upload_file = "C:\scripts\win_stats.txt"

# os info
$os_name = Get-CimInstance -ClassName Win32_OperatingSystem | Select-Object -Property "Caption"
$manufacturer = Get-CimInstance -ClassName Win32_ComputerSystem | Select-Object "Manufacturer"
$model = Get-CimInstance -ClassName Win32_ComputerSystem | Select-Object "Model"
$cpu = Get-CimInstance -ClassName Win32_Processor | Select-Object "Name"
$boot_time = Get-CimInstance -ClassName Win32_OperatingSystem | Select-Object -Property "LastBootUpTime"

# disk info
#Get-WmiObject -Class Win32_LogicalDisk | Select-Object -Property DeviceID, VolumeName, @{Label='FreeSpace (Gb)'; expression={($_.FreeSpace/1GB).ToString('F0')}},@{Label='Total (Gb)'; expression={($_.Size/1GB).ToString('F0')}}, @{label='FreePercent'; expression={[Math]::Round(($_.freespace / $_.size) * 100, 0)}}|ft
$disk = Get-WmiObject -Class Win32_LogicalDisk | Select-Object -Property DeviceID, @{label='FreePercent'; expression={[Math]::Round(($_.freespace / $_.size) * 100, 0)}}
$disk_string = ($disk.DeviceID)[0]+($disk.FreePercent)[0]+"% free, "+($disk.DeviceID)[1]+($disk.FreePercent)[1]+"% free, "+($disk.DeviceID)[2]+($disk.FreePercent)[2]+"% free"

# temperature
$temperature_data = Get-WMIObject -Query "SELECT * FROM Win32_PerfFormattedData_Counters_ThermalZoneInformation" -Namespace "root/CIMV2"
$temperature_string = [Math]::Round(@($temperature_data)[0].Temperature - 273.15,0)

# save to file
$timestamp, $os_name.Caption, $manufacturer.Manufacturer, $model.Model, $boot_time.LastBootUpTime, $temperature_string, $disk_string | Out-File -FilePath $upload_file -Encoding ASCII

# ftp upload
$ftp = [System.Net.FtpWebRequest]::Create("ftp://yourftphost.com/win_stats.txt")
$ftp = [System.Net.FtpWebRequest]$ftp
$ftp.Method = [System.Net.WebRequestMethods+Ftp]::UploadFile
$ftp.Credentials = new-object System.Net.NetworkCredential('username','password')
$ftp.UseBinary = $true
$ftp.UsePassive = $true
# read in the file to upload as a byte array
$content = [System.IO.File]::ReadAllBytes($upload_file)
$ftp.ContentLength = $content.Length
# get the request stream, and write the bytes into it
$rs = $ftp.GetRequestStream()
$rs.Write($content, 0, $content.Length)
# be sure to clean up after ourselves
$rs.Close()
$rs.Dispose()
