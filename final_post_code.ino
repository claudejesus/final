#include <ESP8266WiFi.h>
#include <DHT.h>
#include <ArduinoJson.h>
#include <ESP8266HTTPClient.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>

#define DHTPIN D4
#define DHTTYPE DHT11
DHT dht(DHTPIN, DHTTYPE);

// Pins
#define COOL_RELAY D6
#define HEAT_RELAY D5
#define LED_COOL D7
#define LED_HEAT D8

LiquidCrystal_I2C lcd(0x27, 16, 2);

// WiFi
const char* ssid = "jesus";
const char* password = "jesus1234";

// API URLs
const char* post_url = "http://192.168.10.110/maize_weevil_new/sensors/insert.php";
const char* control_url = "http://192.168.10.110/maize_weevil_new/sensors/control.php";

void setup() {
  Serial.begin(115200);
  dht.begin();

  pinMode(COOL_RELAY, OUTPUT);
  pinMode(HEAT_RELAY, OUTPUT);
  pinMode(LED_COOL, OUTPUT);
  pinMode(LED_HEAT, OUTPUT);

  digitalWrite(COOL_RELAY, LOW);
  digitalWrite(HEAT_RELAY, LOW);
  digitalWrite(LED_COOL, LOW);
  digitalWrite(LED_HEAT, LOW);

  Wire.begin(D1, D2);
  lcd.init();
  lcd.backlight();
  lcd.setCursor(0, 0);
  lcd.print("Connecting WiFi");

  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.print("WiFi Connected");
  lcd.setCursor(0, 1);
  lcd.print(WiFi.localIP());
  delay(3000);
}

void loop() {
  float temp = dht.readTemperature();
  float humid = dht.readHumidity();

  if (isnan(temp) || isnan(humid)) {
    Serial.println("❌ Sensor error");
    lcd.clear();
    lcd.setCursor(0, 0);
    lcd.print("Sensor Error!");
    delay(3000);
    return;
  }

  // LCD
  lcd.clear();
  lcd.setCursor(0, 0);
  lcd.printf("T: %.1fC H: %.1f%%", temp, humid);

  // Control vars
  bool cool_manual = false, heat_manual = false;

  if (WiFi.status() == WL_CONNECTED) {
    // --- GET control commands ---
    WiFiClient client;
    HTTPClient http;
    http.begin(client, control_url);
    int code = http.GET();
    if (code == 200) {
      String json = http.getString();
      StaticJsonDocument<200> doc;
      DeserializationError err = deserializeJson(doc, json);
      if (!err) {
        cool_manual = doc["fan_cool"] == 1;
        heat_manual = doc["fan_heat"] == 1;
      }
    }
    http.end();
  }

  // --- Logic ---
  bool cool_auto = temp > 30 || humid > 35;
  bool heat_auto = temp < 20;

  bool cool_on = cool_auto || cool_manual;
  bool heat_on = heat_auto || heat_manual;

  digitalWrite(COOL_RELAY, cool_on ? HIGH : LOW);
  digitalWrite(LED_COOL, cool_on ? HIGH : LOW);
  digitalWrite(HEAT_RELAY, heat_on ? HIGH : LOW);
  digitalWrite(LED_HEAT, heat_on ? HIGH : LOW);

  lcd.setCursor(0, 1);
  lcd.print("Cool:");
  lcd.print(cool_on ? "ON " : "OFF");
  lcd.print(" Heat:");
  lcd.print(heat_on ? "ON" : "OFF");

  // --- POST data to DB ---
  if (WiFi.status() == WL_CONNECTED) {
    WiFiClient client;
    HTTPClient http;
    http.begin(client, post_url);
    http.addHeader("Content-Type", "application/json");

    StaticJsonDocument<256> doc;
    doc["temperature"] = temp;
    doc["humidity"] = humid;
    doc["cooling_status"] = cool_on ? 1 : 0;
    doc["heating_status"] = heat_on ? 1 : 0;

    String payload;
    serializeJson(doc, payload);

    int code = http.POST(payload);
    Serial.println("POST Status: " + String(code));
    http.end();
  }

  delay(15000);
}
