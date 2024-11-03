import sys
sys.path.append('/storage/.kodi/addons/virtual.rpi-tools/lib')
import lgpio 
from re import findall
from time import sleep
from subprocess import check_output

controlPin = 15            
fan_file_name = '/storage/.config/fan_status.txt'          

# open the gpio chip and set the fan pin as output
h = lgpio.gpiochip_open(0)
lgpio.gpio_claim_output(h, controlPin)

def get_temp():
    temp = check_output(["vcgencmd","measure_temp"]).decode()
    temp = float(findall('\d+\.\d+', temp)[0])
    return(temp)

try:
    state = lgpio.gpio_read(h, controlPin) 
    if state:
      ff = open(fan_file_name, 'w')
      ff.write("ON\n")
      ff.close()
    else:
      ff = open(fan_file_name, 'w')
      ff.write("OFF\n")
      ff.close()

    tempOn = 65 
    threshold = 5
    sleepInterval = 15
    pinState = 0
    lgpio.gpio_write(h, controlPin, pinState) 
    while True:
        temp = get_temp()
        if temp > tempOn and not pinState or temp < tempOn - threshold and pinState:
            if pinState:
                pinState = 0
            else:
                pinState = 1
            if pinState:
                pi_fan = "ON\n"
            else:
                pi_fan = "OFF\n"
            # Turn the GPIO pin on
            lgpio.gpio_write(h, controlPin, pinState)
            ff = open(fan_file_name, 'w')
            ff.write(pi_fan)
            ff.close()
        sleep(sleepInterval)
except KeyboardInterrupt:
    print("Exit pressed Ctrl+C")
    lgpio.gpiochip_close(h)
except:
    print("Other Exception")
    print("--- Start Exception Data:")
    #traceback.print_exc(limit=2, file=sys.stdout)
    print("--- End Exception Data:")
    lgpio.gpiochip_close(h)
finally:
    print("CleanUp")
    lgpio.gpiochip_close(h)
    print("End of program")
