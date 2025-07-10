#include <ESP8266WiFi.h>
#include <DHT.h>
#include <ArduinoJson.h>
#include <ESP8266HTTPClient.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>

#define DHTPIN D4
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);

// Fan and LED Pins
#define COOL_RELAY D5
#define HEAT_RELAY D6
#define COOL_LED   D7
#define HEAT_LED   D8

LiquidCrystal_I2C lcd(0x27, 16, 2);

// WiFi credentials
const char* ssid = "jesus";
const char* password = "jesus1234";

// Server URLs
const char* post_url = "http://192.168.1.69/maize_weevil_new/sensors/insert.php";
const char* control_url = "http://192.168.1.69/maize_weevil_new/sensors/control.php";

void setup() {
  Serial.begin(115200);
  dht.begin();

  pinMode(COOL_RELAY, OUTPUT);
  pinMode(HEAT_RELAY, OUTPUT);
  pinMode(COOL_LED, OUTPUT);
  pinMode(HEAT_LED, OUTPUT);

  digitalWrite(COOL_RELAY, LOW);
  digitalWrite(HEAT_RELAY, LOW);
  digitalWrite(COOL_LED, LOW);
  digitalWrite(HEAT_LED, LOW);

  Wire.begin(D1, D2);
  lcd.init();
  lcd.backlight();

  lcd.setCursor(0, 0);
  lcd.print("Connecting WiFi");

  WiFi.begin(ssid, password);
  Serial.print("Connecting to WiFi");
  int timeout = 0;
  while (WiFi.status() != WL_CONNECTED && timeout < 20) { // wait max 10 seconds
    delay(500);
    Serial.print(".");
    timeout++;
  }

  if (WiFi.status() == WL_CONNECTED) {
    Serial.println("\nWiFi Connected!");
    Serial.print("IP address: ");
    Serial.println(WiFi.localIP());
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("WiFi Connected");
    lcd.setCursor(0, 1);
    lcd.print(WiFi.localIP());
  } else {
    Serial.println("\nFailed to connect WiFi");
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("WiFi Failed");
  }

  delay(3000);
}

void loop() {
  float temp = dht.readTemperature();
  float humid = dht.readHumidity();

  if (isnan(temp) || isnan(humid)) {
    Serial.println("Sensor Error!");
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Sensor Error!");
    delay(3000);
    return;
  }

  // Display readings on LCD
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.printf("T: %.1fC H: %.1f%%", temp, humid);
  Serial.printf("Temp: %.1f, Humidity: %.1f\n", temp, humid);

  // POST JSON data to server
  if (WiFi.status() == WL_CONNECTED) {
    WiFiClient client;
    HTTPClient http;

    http.begin(client, post_url);
    http.addHeader("Content-Type", "application/json");

    StaticJsonDocument<100> doc;
    doc["temperature"] = temp;
    doc["humidity"] = humid;

    String payload;
    serializeJson(doc, payload);

    int httpResponseCode = http.POST(payload);

    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.printf("POST Response code: %d\n", httpResponseCode);
      Serial.println("Response: " + response);
    } else {
      Serial.printf("Error on POST: %d\n", httpResponseCode);
    }

    http.end();
  } else {
    Serial.println("WiFi Disconnected");
  }

  // Get fan control status from server
  bool fan_manual = false;
  if (WiFi.status() == WL_CONNECTED) {
    WiFiClient client;
    HTTPClient http;

    http.begin(client, control_url);
    int code = http.GET();

    if (code == 200) {
      String resp = http.getString();
      Serial.println("Control response: " + resp);
      fan_manual = (resp == "1");
    } else {
      Serial.printf("Error getting control: %d\n", code);
    }

    http.end();
  }

  // Fan control logic
  bool cool_on = (temp > 30.0) || fan_manual;
  bool heat_on = (temp < 20.0) || fan_manual;

  digitalWrite(COOL_RELAY, cool_on ? HIGH : LOW);
  digitalWrite(COOL_LED, cool_on ? HIGH : LOW);

  digitalWrite(HEAT_RELAY, heat_on ? HIGH : LOW);
  digitalWrite(HEAT_LED, heat_on ? HIGH : LOW);

  // Display fan status on LCD
  lcd.setCursor(0, 1);
  if (cool_on && !heat_on) lcd.print("Cooling Fan ON ");
  else if (heat_on && !cool_on) lcd.print("Heater Fan ON ");
  else if (cool_on && heat_on) lcd.print("Both Fans ON");
  else lcd.print("Fans OFF       ");

  delay(15000);  // Delay 15 seconds before next loop
}