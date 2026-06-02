#include <WiFi.h>
#include <HTTPClient.h>

const char* ssid = "YOUR_WIFI_NAME";
const char* password = "YOUR_WIFI_PASSWORD";

String serverName = "http://YOUR_IP/motion/insert.php";

// Ultrasonic
#define TRIG_PIN 12
#define ECHO_PIN 13

// LEDs
#define RED_LED    14
#define GREEN_LED  15
#define BLUE_LED   2

long duration;
float distance;

HardwareSerial gsm(1);

void setup() {

  Serial.begin(115200);

  pinMode(TRIG_PIN, OUTPUT);
  pinMode(ECHO_PIN, INPUT);

  pinMode(RED_LED, OUTPUT);
  pinMode(GREEN_LED, OUTPUT);
  pinMode(BLUE_LED, OUTPUT);

  // GSM
  gsm.begin(9600, SERIAL_8N1, 3, 1);

  // WiFi
  WiFi.begin(ssid, password);

  Serial.print("Connecting WiFi");

  while(WiFi.status() != WL_CONNECTED)
  {
    delay(500);
    Serial.print(".");
  }

  Serial.println("\nWiFi Connected");
  Serial.println(WiFi.localIP());
}

void loop()
{
  digitalWrite(TRIG_PIN, LOW);
  delayMicroseconds(2);

  digitalWrite(TRIG_PIN, HIGH);
  delayMicroseconds(10);

  digitalWrite(TRIG_PIN, LOW);

  duration = pulseIn(ECHO_PIN, HIGH);

  distance = duration * 0.034 / 2;

  Serial.print("Distance: ");
  Serial.println(distance);

  digitalWrite(RED_LED, LOW);
  digitalWrite(GREEN_LED, LOW);
  digitalWrite(BLUE_LED, LOW);

  String motion = "NO";

  if(distance <= 10)
  {
    motion = "DETECTED_10CM";

    digitalWrite(RED_LED, HIGH);
    delay(350);
    digitalWrite(RED_LED, LOW);
    delay(250);

    sendSMS("ALERT! Object detected within 10cm");
  }

  else if(distance <= 20)
  {
    motion = "DETECTED_20CM";

    digitalWrite(GREEN_LED, HIGH);
    delay(400);
    digitalWrite(GREEN_LED, LOW);
    delay(300);
  }

  else if(distance <= 30)
  {
    motion = "DETECTED_30CM";

    digitalWrite(BLUE_LED, HIGH);
    delay(450);
    digitalWrite(BLUE_LED, LOW);
    delay(350);
  }

  if(WiFi.status() == WL_CONNECTED)
  {
    HTTPClient http;

    String url = serverName + "?motion=" + motion;

    http.begin(url);

    int responseCode = http.GET();

    Serial.print("HTTP Response: ");
    Serial.println(responseCode);

    http.end();
  }

  delay(1000);
}

void sendSMS(String message)
{
  gsm.println("AT+CMGF=1");
  delay(1000);

  gsm.println("AT+CMGS=\"+2507XXXXXXXX\"");
  delay(1000);

  gsm.print(message);
  delay(500);

  gsm.write(26);
  delay(3000);
}