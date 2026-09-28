#include "Credentials.h"
//#include <FS.h>

Config config;

Preferences prefs;
//AsyncWebServer server(80);
WebServer server(80);

bool isReset = false;
bool debugMode = false;
bool SetupMode = false;
bool WiFiMode = false;
bool EthernetMode = false;
bool networkConnected = false;
bool wifiScanned = false;

String ssid = "";
String password = "";

const char* SerialNumber = "YQ2P34N51D";
const char* APName = "IOT-DSTEchSmart";
const char* APPass = "12345678910";
const char* setupPassword = "admin";
const char* API_device = "http://pos.dstechsmart.com/api/getTable";
//"http://192.168.1.6:8000/api/getTable";
const char* IP_Device = "192.168.1.100";
const char* gateway_Device = "192.168.1.1";
const char* subnet_Device = "255.255.255.0";

const char* recordOwnerID = "000000";//Ex : CL0001
const char* apiEndpoint = "https://pos.dstechsmart.com/api"; // http://pos.dstechsmart.com/api
const char* apiEndpoint_http = "http://pos.dstechsmart.com/api";

const int LED_LAN = 17;//26
const int LED_WIFI = 21;//22
const int LED_SIGNAL = 22;//21 #no use/NC
const int BTN_SELECT = 26;//33
const int BTN_SETUP = 25;//32

const int latchPin = 12;
const int clockPin = 4;
const int dataPin  = 14;

int attempt = 0;
int led_blink = 0;


unsigned long currentMillis;
unsigned long interval = 10000;
unsigned long signal_display = 0;
unsigned long ONE_SECOND = 1000;
unsigned long TWO_SECOND = 2000;
unsigned long previousMillis = 0;
unsigned long previousMillisWarning = 0;
unsigned long lastConneced = 0;
unsigned long lastReconnectAttempt = 0;
const unsigned long Lost_connection_interval = 200000;
const unsigned long reconnectInterval = 10000;
const long intervalWarning = 180000;

byte relayState[3] = {0b00000000, 0b00000000, 0b00000000};
byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };

IPAddress ip(192, 168, 1, 100);
IPAddress gateway(192, 168, 1, 1);
IPAddress subnet(255, 255, 255, 0);


#include <ArduinoJson.h>

void readConfig() {
  if (!SPIFFS.exists("/config.json")) {
    Serial.println("Config file not found. Creating default config...");

    //Make default json
    config.serialNumber     = SerialNumber;
    config.apName           = APName;
    config.staticIP         = IP_Device;
    config.gateway_IP       = gateway_Device;
    config.subnet_IP        = subnet_Device;
    config.apiAddress       = API_device;
    config.recordOwnerID    = String(recordOwnerID);
    config.apiEndpoint      = String(apiEndpoint);
    config.apiEndpoint_http = String(apiEndpoint_http);
    saveConfig();
    return;
  }

  File file = SPIFFS.open("/config.json", FILE_READ);
  if (!file) {
    Serial.println("Failed to open config file for reading.");
    return;
  }

  StaticJsonDocument<256> doc;
  DeserializationError error = deserializeJson(doc, file);
  file.close();

  if (error) {
    Serial.print("Failed to parse config file: ");
    Serial.println(error.c_str());
    return;
  }

  config.serialNumber     = doc["serialNumber"] | "";
  config.apName           = doc["apName"] | "";
  config.staticIP         = doc["staticIP"] | "";
  config.gateway_IP       = doc["gateway_IP"] | "";
  config.subnet_IP        = doc["subnet_IP"] | "";
  config.apiAddress       = doc["apiAddress"] | "";
  config.recordOwnerID    = doc["recordOwnerID"] | recordOwnerID;
  config.apiEndpoint      = doc["apiEndpoint"] | apiEndpoint;
  config.apiEndpoint_http = doc["apiEndpoint_http"] | apiEndpoint_http;

  Serial.println("Config loaded:");
  Serial.println("Serial: " + config.serialNumber);
  Serial.println("AP Name: " + config.apName);
  Serial.println("Static IP: " + config.staticIP);
  Serial.println("Gateway IP: " + config.gateway_IP);
  Serial.println("Subnet IP: " + config.subnet_IP);
  Serial.println("API Address: " + config.apiAddress);
  Serial.println("ID Owner: " + String(config.recordOwnerID));
  Serial.println("End API: " + String(config.apiEndpoint));
  Serial.println("End API(LAN): " + String(config.apiEndpoint_http));
}

void saveConfig() {
  File file = SPIFFS.open("/config.json", FILE_WRITE);
  if (!file) {
    Serial.println("Failed to open config file for writing.");
    return;
  }

  StaticJsonDocument<256> doc;
  doc["serialNumber"]   = config.serialNumber;
  doc["apName"]         = config.apName;
  doc["staticIP"]       = config.staticIP;
  doc["gateway_IP"]     = config.gateway_IP;
  doc["subnet_IP"]      = config.subnet_IP;
  doc["apiAddress"]     = config.apiAddress;
  doc["recordOwnerID"]  = config.recordOwnerID;
  doc["apiEndpoint"]    = config.apiEndpoint;
  doc["apiEndpoint_http"]    = config.apiEndpoint_http;
  

  if (serializeJson(doc, file) == 0) {
    Serial.println("Failed to write JSON to file.");
  } else {
    Serial.println("Config saved to /config.json");
  }

  file.close();
}


//=====
/*
  void readConfig() {
  if (!SPIFFS.exists("/config.txt")) {
    Serial.println("Config file not found.");
    return;
  }

  File file = SPIFFS.open("/config.txt", FILE_READ);
  if (!file) {
    Serial.println("Failed to open config file for reading.");
    return;
  }

  config.serialNumber = file.readStringUntil('\n');
  config.apName       = file.readStringUntil('\n');
  config.staticIP     = file.readStringUntil('\n');
  config.apiAddress   = file.readStringUntil('\n');
  file.close();

  // Trim newline/whitespace
  config.serialNumber.trim();
  config.apName.trim();
  config.staticIP.trim();
  config.apiAddress.trim();

  Serial.println("Config loaded:");
  Serial.println("Serial: " + config.serialNumber);
  Serial.println("AP Name: " + config.apName);
  Serial.println("Static IP: " + config.staticIP);
  Serial.println("API Address: " + config.apiAddress);
  }

  void saveConfig() {
  File file = SPIFFS.open("/config.txt", FILE_WRITE);
  if (!file) {
    Serial.println("Failed to open config file for writing.");
    return;
  }

  file.println(config.serialNumber);
  file.println(config.apName);
  file.println(config.staticIP);
  file.println(config.apiAddress);
  file.close();

  Serial.println("Config saved to /config.txt");
  }
*/
