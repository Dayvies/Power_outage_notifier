#include <Wire.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClient.h>
#include <Adafruit_ADS1X15.h>
#include <SoftwareSerial.h>
#include <TinyGPS++.h> // library for GPS module
#include <ESPAsyncTCP.h>
#include <ESPAsyncWebServer.h>
#include <WebSerial.h>
AsyncWebServer server(80);

TinyGPSPlus gps;  // The TinyGPS++ object

SoftwareSerial ss(12, 14); // The serial connection to the GPS device
float latitude , longitude;
int year , month , date, hour , minute , second;
String lat_str1 , lng_str1;
String lat_str="NotValid";
String lng_str="NotValid";
int pm;

Adafruit_ADS1115 ads;

Adafruit_ADS1115 ads2;

const float FACTOR = 30; //30A/1V from the CT

const float multiplier = 0.03125;
float data1;
float data2;
String redp,bluep,yellowp,statu,check1;
String check2="y";
float count=0;

String sendval, postData;

// DEFINE SENSOR UNIQUE ID 
const String uid="TX12";
const char* ssid ="NAIMER";
const char* password="ELEMENTAITA12";
//const char* ssid ="ManMo";
//const char* password="Iwashere@2021";
//const char* ssid ="My ASUS";
//const char* password="davydavy";
const String ip="192.168.8.104";
//const String ip="46.101.117.150";

// for xampp and local host use computer ip 
//const char* serverName = "http://46.101.117.150/dbwrite.php";
const char* serverName = "http://192.168.8.104/dbwrite.php";



void setup() {
  // put your setup code here, to run once:
Serial.begin(115200);
 ss.begin(9600);
Serial.println("Communication Started \n\n");

//current transformer setup
  ads.setGain(GAIN_FOUR);      // +/- 1.024V 1bit = 0.5mV
  ads.begin();
  ads2.begin(0x49);
  ads2.setGain(GAIN_FOUR);

 WebSerial.begin(&server);
    //WebSerial.msgCallback(callback);
server.begin();
//connections setup
WiFi. begin(ssid,password);
delay(200);
delay(200);
while(WiFi.status() != WL_CONNECTED) { 
    delay(500);
    Serial.print(".");
  }
  Serial.println("");
  Serial.print("Connected to WiFi network with IP Address: ");
  Serial.println(WiFi.localIP());
 
  

}
// printing current values
void printMeasure(String prefix, float value, String postfix,int phase )
{
     if (phase==1){
  if (value <0.010 && phase == 1)
  {
    redp="OFF";
     
    
    }
    else { redp="ON";}
     Serial.println("red:"+redp);
    }
    if (phase==2){
  if (value <0.010 && phase == 2)
  {
    yellowp="OFF";
     
    
    }
    else { yellowp="ON";}
     Serial.println("yellow :"+yellowp);
     
    }
     if (phase==3){
  if (value <0.010 && phase == 3)
  {
    bluep="OFF";
     
    
    }
    else { bluep="ON";}
     Serial.println("blue :"+bluep);
    }
  
  Serial.print(prefix);
  Serial.print(value, 3);
  Serial.println(postfix);
}
void loop() {
  // put your main code here, to run repeatedly:
  
  
   float currentRMS1 = getcurrent();
  // float currentRMS2= 0.10;
  float currentRMS2= getcurrent2();
  float currentRMS3 = getcurrent3();

  printMeasure("Irms1: ", currentRMS1, "A",1);
  printMeasure("Irms2: ", currentRMS2, "A",2);
  printMeasure("Irms3: ", currentRMS3, "A",3);
  
  if( (bluep=="OFF")||(redp=="OFF")||(yellowp=="OFF"))
  {
    statu="OFF";
    }
  else{
    statu="ON";
  }
  check1=bluep+yellowp+redp;
  getGps();
  if (check1!=check2)
  {
      //Serial.println(WiFi.localIP());
  
  WiFiClient wifiClient;
  HTTPClient http;
  sendval= String(data1);
  postData= "bluepl="+bluep+"&yellowpl="+yellowp+"&redpl="+redp+"&statusl="+statu+"&uidl="+uid; // sendval = data1
  http.begin(wifiClient ,serverName);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");  //Specify content-type header
  int httpCode = http.POST(postData);   // Send POST request to php file and store server response code in variable named httpCode
  //Serial.println("Values are sendval = " + sendval );
  // if connection eatablished then do this
  if (httpCode == 200) { Serial.println("Values uploaded successfully."); Serial.println(httpCode);
    String webpage = http.getString();  // Get html webpage output and store it in a string
    Serial.println(webpage + "\n");
  } else {
    // if failed to connect then return and restart
    Serial.println(httpCode);
    Serial.println("Failed to upload values. \n");
    http.end();
    return;
  }
  check2=check1;}

  if (count>=10)
  {
WebSerial.println(lng_str+lat_str); 
//WebSerial.println("B: "+bluep+"R: "+redp);   
     WiFiClient wifiClient;
  HTTPClient http;
  String bluepa= String(currentRMS3);
   String yellowpa= String(currentRMS2);
    String redpa= String(currentRMS1);
  postData= "bluepa="+bluepa+"&yellowpa="+yellowpa+"&redpa="+redpa+"&statusl="+statu+"&uidl="+uid; // sendval = data1
  String serv2 ="http://"+ip+"/dbwrite2.php";
  http.begin(wifiClient ,serv2);
  http.addHeader("Content-Type", "application/x-www-form-urlencoded");  //Specify content-type header
  int httpCode = http.POST(postData);   // Send POST request to php file and store server response code in variable named httpCode
  //Serial.println("Values are sendval = " + sendval );
  // if connection eatablished then do this
  if (httpCode == 200) { Serial.println("Values uploaded successfully."); Serial.println(httpCode);
    String webpage = http.getString();  // Get html webpage output and store it in a string
    Serial.println(webpage + "\n");
  } else {
    // if failed to connect then return and restart
    Serial.println(httpCode);
    Serial.println("Failed to upload values. \n");
    http.end();
    return;
  }
 count=0;
    }
  else
    {
      count=count+1;
      }

  digitalWrite(LED_BUILTIN, LOW);
  delay(200);
  digitalWrite(LED_BUILTIN, HIGH);
}
float getcurrent()
{
  float voltage;
  float current;
  float sum = 0;
  long Raw=ads.readADC_Differential_0_1();
  long maxRaw=Raw;
  long minRaw=Raw;
  long time_check = millis();
  int counter = 0;

  while (millis() - time_check < 1000)
  {
   Raw=ads.readADC_Differential_0_1();
   maxRaw= maxRaw > Raw ? maxRaw: Raw;
   minRaw= minRaw > Raw ? minRaw: Raw;
    //current /= 1000.0;
delay(5);
  
  }
  maxRaw= maxRaw > -minRaw ? maxRaw: -minRaw;
  voltage =maxRaw*multiplier/1000;
  float voltageRms= voltage*0.70710678118;
  current=voltageRms*FACTOR;

  return (current);
}
float getcurrent2()
{
  float voltage;
  float current;
  float sum = 0;
  long Raw=ads2.readADC_Differential_0_1();
  long maxRaw=Raw;
  long minRaw=Raw;
  long time_check = millis();
  int counter = 0;

  while (millis() - time_check < 1000)
  {
   Raw=ads2.readADC_Differential_0_1();
   maxRaw= maxRaw > Raw ? maxRaw: Raw;
   minRaw= minRaw > Raw ? minRaw: Raw;
    current /= 1000.0;
delay(5);
  
  }
  maxRaw= maxRaw > -minRaw ? maxRaw: -minRaw;
  voltage =maxRaw*multiplier/1000;
  float voltageRms= voltage*0.70710678118;
  current=voltageRms*FACTOR;

  return (current);
}
float getcurrent3()
{
  float voltage;
  float current;
  float sum = 0;
  long Raw=ads.readADC_Differential_2_3();
  long maxRaw=Raw;
  long minRaw=Raw;
  long time_check = millis();
  int counter = 0;

  while (millis() - time_check < 1000)
  {
   Raw=ads.readADC_Differential_2_3();
   maxRaw= maxRaw > Raw ? maxRaw: Raw;
   minRaw= minRaw > Raw ? minRaw: Raw;
    current /= 1000.0;
delay(5);
  
  }
  maxRaw= maxRaw > -minRaw ? maxRaw: -minRaw;
  voltage =maxRaw*multiplier/1000;
  float voltageRms= voltage*0.70710678118;
  current=voltageRms*FACTOR;

  return (current);}
void getGps(){


while (ss.available() > 0) //while data is available
    if (gps.encode(ss.read())) //read gps data
    {
      if (gps.location.isValid()) //check whether gps location is valid
      {
        latitude = gps.location.lat();
        lat_str = String(latitude , 6); // latitude location is stored in a string
        longitude = gps.location.lng();
        lng_str = String(longitude , 6); //longitude location is stored in a string
      }  
    }  
  Serial.println(lat_str);
Serial.println(lng_str);


  

  

}
 
void callback(unsigned char* data, unsigned int length)
{
    data[length] = '\0';
    Serial.println((char*) data);
}
 
