#Libraries
import RPi.GPIO as GPIO
import time
import MySQLdb
import subprocess

notification_sent=False
off = False
contatore = []
dist_from_door = 12
 
#GPIO Mode (BOARD / BCM)
GPIO.setmode(GPIO.BOARD)
 
#set GPIO Pins
GPIO_SENSOR = 16

doorActive = False
GPIO.setwarnings(False)

GPIO.setup(GPIO_SENSOR, GPIO.IN, pull_up_down=GPIO.PUD_DOWN)
 

#set connection to db
#cnx = MySQLdb.connect(host="localhost", user="root", passwd="emidio", db="alarmSystem")
#cur = cnx.cursor()
 
 
if __name__ == '__main__':
    try:
        while True:
            cnx = MySQLdb.connect(host="localhost", user="root", passwd="emidio", db="alarmSystem")
            cur = cnx.cursor()
            cur.execute("SELECT off FROM data")
            rows = cur.fetchall()
            for x in range(len(rows)):
                row = int(rows[x][0])
        
                if(row==1):
                    off=True
                    try:                            #counter to check if sensor is turned off for more than five minutes
                        contatore[x]+=1
                    except IndexError:
                        contatore.append(0)
                    if contatore[x]>=30:            #scrittura database off=0 where id=x+1 (turning on sensor)
                        off=False
                        contatore[x]=0
                        cur.close()
                        cnx.close()
                        cnx = MySQLdb.connect(host="localhost", user="root", passwd="emidio", db="alarmSystem")
                        cur = cnx.cursor()
                        id_for_query = int(x)
                        line = "UPDATE data SET off='0' WHERE id='%s'" % (id_for_query+1)
                        cur.execute(line)
                        cnx.commit()

        
                else:
                    try:
                        contatore[x]=0
                    except IndexError:
                        contatore.append(0)
            if( off==False) :                               #sensore acceso
                doorActive = GPIO.input(GPIO_SENSOR)
                if doorActive == False and notification_sent== False :
                        #sending alarm notification
                        print ("The door is open!")
                        cur.execute("SELECT token, uid FROM data")
                        rows = cur.fetchall()
                        
                        for x in range(len(rows)):
                            line = "php /var/www/html/httpPost.php %s" % (rows[x][0])
                            proc = subprocess.Popen(line, shell=True, stdout=subprocess.PIPE)
                            script_response = proc.stdout.read()
                            success = script_response[46:47]
                            failure = script_response[58:59]
                            notification_sent=True
                            if (success=='1' and failure=='0'):
                                print ("ALARM, intrusion detected: notification sent to %s" % (rows[x][1]))
                                notification_sent=True
                            else:
                                print ("ALARM, intrusion detected: something went wrong in posting notification to %s" % (rows[x][1]))

                elif (doorActive == False and notification_sent==True) :
                    continue


                elif doorActive == True :
                    print ("The door is closed!")
                    notification_sent=False

            
            else:
                print ("Sensor is turned off")
                off=False

            cur.close()
            cnx.close()
            time.sleep(1)
 
        # Reset by pressing CTRL + C
    except KeyboardInterrupt:
        print("Measurement stopped by User")
        try:
            cur.close()
            cnx.close()
        except MySQLdb.ProgrammingError, e:
            print ("Connection closed")
        GPIO.cleanup()
