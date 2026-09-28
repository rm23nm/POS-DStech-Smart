#ifndef CREDENTIALS_H
#define CREDENTIALS_H

#include <Arduino.h>
#include <WiFiClientSecure.h> //-> Handle https
#include <WiFi.h>
#include <HTTPClient.h>
#include <WebServer.h>
//#include <ESPAsyncWebServer.h>
#include <Preferences.h>
#include <SPI.h>
#include <Ethernet.h>
//#include <Ethernet2.h>
#include "FS.h"
#include "SPIFFS.h"
#include <ArduinoJson.h> 

struct Config {
  String serialNumber;
  String apName;
  String staticIP;
  String gateway_IP;
  String subnet_IP;
  String apiAddress;
  String recordOwnerID;  
  String apiEndpoint; 
  String apiEndpoint_http;
};

extern Config config;

void saveConfig();
void readConfig();

extern Preferences prefs;
//extern AsyncWebServer server;
extern WebServer server;
extern bool isReset;
extern bool debugMode;
extern bool SetupMode;
extern bool WiFiMode;
extern bool EthernetMode;
extern bool networkConnected;
extern bool wifiScanned;

extern const char* setupPassword;
extern const char* SerialNumber;
extern const char* APName;
extern const char* APPass;
extern const char* API_device;
extern const char* IP_Device;
extern const char* gateway_Device;
extern const char* subnet_Device;

extern const char* recordOwnerID;
extern const char* apiEndpoint;
extern const char* apiEndpoint_http;

extern int attempt;
extern int led_blink;

extern const int LED_LAN;
extern const int LED_WIFI;
extern const int LED_SIGNAL;
extern const int BTN_SELECT;
extern const int BTN_SETUP;

extern const int latchPin;
extern const int clockPin;
extern const int dataPin;

extern unsigned long currentMillis;
extern unsigned long interval;
extern unsigned long signal_display;
extern unsigned long ONE_SECOND;
extern unsigned long TWO_SECOND;
extern unsigned long previousMillis;
extern unsigned long previousMillisWarning;
extern unsigned long lastConneced; 
extern unsigned long lastReconnectAttempt;
extern const long intervalWarning; 
extern const unsigned long reconnectInterval; 
extern const unsigned long Lost_connection_interval;

extern byte relayState[3];
extern byte mac[];

extern String ssid;
extern String password;
extern String scannedNetworks;

extern IPAddress ip;
extern IPAddress gateway;
extern IPAddress subnet;
class Credentials {
  

public:
  void begin() {
    prefs.begin("wifi", false);
  }

  void save(const String& ssidInput, const String& passwordInput) {
    ssid = ssidInput;
    password = passwordInput;
    prefs.putString("ssid", ssid);
    prefs.putString("pass", password);
  }

  void load() {
    ssid = prefs.getString("ssid", "");
    password = prefs.getString("pass", "");
  }

  String getSSID() const {
    return ssid;
  }

  String getPassword() const {
    return password;
  }

  bool isEmpty() const {
    return ssid.isEmpty();
  }

  void end() {
    prefs.end();
  }
};

#endif
