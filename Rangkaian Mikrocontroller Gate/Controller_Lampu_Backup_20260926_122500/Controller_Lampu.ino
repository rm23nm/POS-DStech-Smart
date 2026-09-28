#include "Credentials.h"

extern void setupWebServer();
extern void scanWiFiNetworks();
extern void readConfig();

Credentials wifi;
Credentials credentials;

//EthernetClient client;

void setup() {
  Serial.begin(115200);

  pinMode(latchPin, OUTPUT);
    pinMode(clockPin, OUTPUT);
      pinMode(dataPin, OUTPUT);
  pinMode(LED_LAN, OUTPUT);
    pinMode(LED_WIFI, OUTPUT);
      pinMode(LED_SIGNAL, OUTPUT);
  pinMode(BTN_SELECT, INPUT);
    pinMode(BTN_SETUP, INPUT);
  
  delay(1000);
  
  digitalWrite(LED_LAN, LOW);
    digitalWrite(LED_WIFI, LOW);
      digitalWrite(LED_SIGNAL, LOW);

  updateRelays();
  
  for(int i = 0;i < 3;i++)
  {
    digitalWrite(LED_SIGNAL, HIGH);
    digitalWrite(LED_LAN, HIGH);
    digitalWrite(LED_WIFI, HIGH);
    delay(400);
    digitalWrite(LED_SIGNAL, LOW);
    digitalWrite(LED_LAN, LOW);
    digitalWrite(LED_WIFI, LOW);
    
    delay(400);
  }
  
  SPIFFS.begin(true); 
  readConfig();
  setDefaultValues();

  loaddata_SSID();

  if (digitalRead(BTN_SETUP) == LOW) 
  {
   Serial.println("Detect Setup Mode, run softAP");
   SetupMode = true;
   digitalWrite(LED_SIGNAL, HIGH);
   digitalWrite(LED_WIFI, HIGH);
   digitalWrite(LED_LAN, HIGH);
   run_softAP(); 
  }else{
    Serial.println("No detect run setup mode");
    Serial.println("Running Mode");
    if (digitalRead(BTN_SELECT) == LOW) 
    {
      Serial.println("=== LAN MODE ===");
      for(int i = 0;i < 4;i++)
      {
        digitalWrite(LED_LAN, HIGH);
        delay(400);
        digitalWrite(LED_LAN, LOW);
        delay(400);
      }
      LAN_mode();
    }else{
      Serial.println("=== WiFi MODE ===");
      for(int i = 0;i < 4;i++)
      {
        digitalWrite(LED_WIFI, HIGH);
        delay(400);
        digitalWrite(LED_WIFI, LOW);
        delay(400);
      }
      wifi_mode();
    }
  } 

  attempt = 0;
  led_blink = 0;
}

void loaddata_SSID()
{
  Preferences preferences;
  preferences.begin("wifi", true);
  //String 
  ssid = preferences.getString("ssid", "");
  //String 
  password = preferences.getString("password", "");
  preferences.end();

  Serial.println("SSID Loaded: " + ssid + "," + password);
}

void run_softAP()
{
  APName = config.apName.c_str();
  WiFi.softAP(APName, APPass);  // Start the AP (Access Point)
  delay(1000);

  Serial.print("AP IP address: ");Serial.println(WiFi.softAPIP());
  wifiScanned = false;    // reset status
  scanWiFiNetworks();  
  
  setupWebServer();
  attempt = 0; 
}

void LAN_mode()
{
  unsigned long wait_connection = millis();
  const unsigned long timeout_binding_LAN = 60000; 

  
  Ethernet.init(5);
  if (!ip.fromString(config.staticIP)) 
  {
    Serial.println("Invalid Static IP");
  }
  
  if (!gateway.fromString(config.gateway_IP)) 
  {
    Serial.println("Invalid Gateway IP");
  }
  
  if (!subnet.fromString(config.subnet_IP)) 
  {
    Serial.println("Invalid Subnet IP");
  }
  IPAddress dns(8, 8, 8, 8);
  Ethernet.begin(mac, ip, dns, gateway, subnet);
  //Ethernet.begin(mac, ip, gateway, gateway, subnet);
  //Ethernet.begin(mac, ip);
  delay(1000);

  attempt = 0;
  while (Ethernet.linkStatus() != LinkON) 
  { 
    Serial.println("Kabel Ethernet tidak terhubung atau koneksi down");
    Serial.println("Menunggu LAN terhubung...");
    delay(1000);
    
    if (millis() - wait_connection  > timeout_binding_LAN) 
    {
      Serial.println("Gagal terhubung ke LAN setelah 1 menit. Restart ESP...");
      delay(1000);
      ESP.restart();
    }
  
    Serial.print(".");
    delay(1000);

    int signal_connecting = attempt % 3;
    digitalWrite(LED_LAN, signal_connecting == 0 ? HIGH : LOW);
    digitalWrite(LED_SIGNAL, signal_connecting == 0 ? HIGH : LOW);
      
    attempt++;
  }
    
  Serial.println("Connected via LAN");
  Serial.print("IP --> ");
    Serial.println(Ethernet.localIP());
  Serial.print("GW --> ");
    Serial.println(Ethernet.gatewayIP());
  Serial.print("SM --> ");
    Serial.println(Ethernet.subnetMask());

  WiFiMode = false;
  EthernetMode = true;
  
  digitalWrite(LED_LAN, HIGH);

  if (testInternetConnection()) 
  {
    Serial.println("LAN has internet access.");
  }else 
  {
    Serial.println("LAN has no internet access.");
  } 
}//End LAN_Mode

void wifi_mode()
{
  WiFi.begin(ssid.c_str(), password.c_str());
  WiFi.setAutoReconnect(true);
  WiFi.persistent(true); 
  //Serial.println("=== STA MODE ===");
  
  unsigned long wait_connection = millis();
  const unsigned long timeout_binding_wifi = 60000; 
    
  attempt = 0;
  Serial.print("Connecting to WiFi...");
  while (WiFi.status() != WL_CONNECTED) 
  { 
    if (millis() - wait_connection  > timeout_binding_wifi) {
      Serial.println("Gagal terhubung ke WiFi setelah 1 menit. Restart ESP...");
      delay(1000);
      ESP.restart();
    }
    
    Serial.print(".");
    delay(1000);

    int signal_connecting = attempt % 3;
    digitalWrite(LED_WIFI, signal_connecting == 0 ? HIGH : LOW);
    digitalWrite(LED_SIGNAL, signal_connecting == 0 ? HIGH : LOW);
      
    attempt++;
    
  }
    /*
        if (digitalRead(BTN_SETUP) == HIGH) {
        Serial.println("Mode button pressed during STA connection. Switching to AP Mode...");
        WiFi.disconnect(true);
        WiFi.mode(WIFI_OFF);
        delay(500);

         run_softAP();
         return;
      }
      */
  Serial.println("Connected to Wi-Fi!");
  
  Serial.print("IP --> ");
    Serial.println(WiFi.localIP());
  Serial.print("GW --> ");
    Serial.println(WiFi.gatewayIP());
  Serial.print("SM --> ");
    Serial.println(WiFi.subnetMask());
  
  digitalWrite(LED_WIFI, HIGH);
  
  WiFiMode = true;
  EthernetMode = false;
}//END wifi_mode

void setDefaultValues() {

  if (config.serialNumber == "") config.serialNumber = SerialNumber;
  if (config.apName == "") config.apName = APName;
  if (config.staticIP == "") config.staticIP = IP_Device;
  if (config.gateway_IP == "") config.gateway_IP = gateway_Device;
  if (config.subnet_IP == "") config.subnet_IP = subnet_Device;
  if (config.apiAddress == "") config.apiAddress = API_device;
  if (config.recordOwnerID == "") config.recordOwnerID = recordOwnerID; 
  if (config.apiEndpoint == "") config.apiEndpoint = apiEndpoint;

  Serial.println("Init Setup");
  Serial.println("Serial Number: " + config.serialNumber);  Serial.println("AP Name: " + config.apName);
  Serial.println("Static IP: " + config.staticIP);Serial.println("Gateway IP: " + config.gateway_IP);
  Serial.println("Subnet IP: " + config.subnet_IP);Serial.println("API Address: " + config.apiAddress);
  Serial.println("ID Owner: " + String(config.recordOwnerID));Serial.println("End API: " + String(config.apiEndpoint));

  SerialNumber = config.serialNumber.c_str();
  apiEndpoint = config.apiEndpoint.c_str();
  recordOwnerID = config.recordOwnerID.c_str();
  apiEndpoint_http = config.apiEndpoint.c_str();
}

void loop() {
  updateRelays();
  debug_relay();

  if (SetupMode)
  {
    server.handleClient();
    currentMillis = millis();

    if (currentMillis - previousMillis >= TWO_SECOND) 
    {
      previousMillis = currentMillis;
      Serial.print(".");
      int signal_softAPMode = attempt % 2;
      digitalWrite(LED_WIFI, signal_softAPMode == 0 ? HIGH : LOW);
      digitalWrite(LED_LAN, signal_softAPMode == 0 ? HIGH : LOW);
      digitalWrite(LED_SIGNAL, signal_softAPMode == 0 ? HIGH : LOW);
      
      attempt++;
    
    }
  }
  if (!SetupMode)
  {
    currentMillis = millis();
    if(WiFiMode)
    {
      if (WiFi.status() != WL_CONNECTED) 
      {
        if (currentMillis - lastReconnectAttempt > reconnectInterval) 
        {
            lastReconnectAttempt = currentMillis;
            Serial.println("WiFi disconnected. Attempting reconnect...");
            
            WiFi.disconnect(); 
            WiFi.begin(ssid.c_str(), password.c_str()); 
            //led_signal();
        }
        if (currentMillis - signal_display > ONE_SECOND) 
        {
          led_signal();
          signal_display = millis();
        }
      
      }

      
    }//End Wifi Mode

    if(EthernetMode)
    {
      if (Ethernet.linkStatus() != LinkON || Ethernet.localIP() == IPAddress(0, 0, 0, 0)) 
      {
        if (currentMillis - lastReconnectAttempt > reconnectInterval) 
        {
          lastReconnectAttempt = currentMillis;
          Serial.println("Ethernet disconnected. Check cable or IP config.");
          //led_signal();
          
        }
        if (currentMillis - signal_display > ONE_SECOND) 
        {
          led_signal();
          signal_display = millis();
        }
      }
      
    }//End Ethernet Mode

    if (currentMillis - lastConneced > Lost_connection_interval) 
    {
      
      Serial.println("Gagal menghubungkan ulang setelah 1 menit. Restart ESP...");
      delay(1000);
      ESP.restart();
      
    }
   
    if (currentMillis - previousMillis >= interval) 
    {

      previousMillis = currentMillis;
      networkConnected = false;

      if (WiFiMode && WiFi.status() == WL_CONNECTED) 
      {
        networkConnected = true;
        digitalWrite(LED_WIFI, HIGH);
      } else if (EthernetMode && Ethernet.linkStatus() == LinkON && Ethernet.localIP() != IPAddress(0, 0, 0, 0)) 
      {
        networkConnected = true;
        digitalWrite(LED_LAN, HIGH);
      }

      if (networkConnected) 
      {
        lastConneced = millis();
        if (recordOwnerID == "") 
        {
          for (int i = 0; i < 5; i++) 
          {
            digitalWrite(LED_SIGNAL, HIGH);
            delay(500);
            digitalWrite(LED_SIGNAL, LOW);
            delay(500);
          }
          Serial.println("Record Owner ID is Blank");
        } else if (apiEndpoint == "") 
        {
          for (int i = 0; i < 5; i++) {
            digitalWrite(LED_SIGNAL, HIGH);
            delay(500);
            digitalWrite(LED_SIGNAL, LOW);
            delay(500);
          }
          Serial.println("API Endpoint is Blank");
        } else 
        {
          checkCommand(); 
          hitApi();
        }
        attempt = 0;
      } else 
      {
        Serial.println(WiFiMode ? "WiFi not connected" : "Ethernet not connected");
      }   
    }  
  }
  delay(10);
}
void checkCommand() {
  HTTPClient http;
  WiFiClientSecure client; // gunakan satu client SSL untuk semua koneksi
  client.setInsecure();    // sementara, untuk bypass sertifikat

  String url = String(apiEndpoint) + "/checkCommand"; // HTTPS endpoint tunggal

  http.begin(client, url); 
  http.addHeader("Content-Type", "application/json");

  String payload = "{\"RecordOwnerID\":\"" + String(recordOwnerID) +
                   "\",\"SN\":\"" + String(SerialNumber) + "\"}";

  Serial.println("POST " + url);
  Serial.println(payload);

  int httpResponseCode = http.POST(payload);

  if (httpResponseCode <= 0) {
    Serial.print("HTTP Error: ");
    Serial.println(httpResponseCode);
    http.end();
    return;
  }

  Serial.print("HTTP Response Code: ");
  Serial.println(httpResponseCode);

  String response = http.getString();
  Serial.println("Response:");
  Serial.println(response);

  http.end();

  // ===== VALIDASI JSON =====
  if (!response.startsWith("{")) {
    Serial.println("Response bukan JSON, skip parsing");
    return;
  }

  DynamicJsonDocument doc(1024);
  if (deserializeJson(doc, response)) {
    Serial.println("Failed to parse JSON");
    return;
  }

  bool success = doc["success"] | false;
  int command = doc["Command"] | 0;

  if (success && command > 0) {
    digitalWrite(LED_SIGNAL, HIGH);
    releaseCommand();

    switch (command) {
      case 1:
        delay(3000);
        ESP.restart();
        break;

      case 2:
        digitalWrite(LED_WIFI, HIGH);
        digitalWrite(LED_LAN, HIGH);
        digitalWrite(LED_SIGNAL, HIGH);
        delay(3000);
        Reset_data();
        WiFi.disconnect(true);
        ESP.restart();
        break;
    }
  }
}


void hitApi() {
  HTTPClient http;
  WiFiClientSecure client;
  client.setInsecure();  // sementara, untuk bypass sertifikat SSL
  String url = String(apiEndpoint) + "/getTable";   // HTTPS endpoint tunggal

  http.begin(client, url);
  http.addHeader("Content-Type", "application/json");

  String payload = "{\"RecordOwnerID\":\"" + String(recordOwnerID) +
                   "\",\"SerialNumber\":\"" + SerialNumber + "\"}";

  // Debug
  Serial.println("POST " + url);
  Serial.println(payload);

  int httpResponseCode = http.POST(payload);

  if (httpResponseCode <= 0) {
    Serial.print("HTTP Error: ");
    Serial.println(httpResponseCode);
    http.end();
    led_NG();
    return;
  }

  Serial.print("HTTP Response Code: ");
  Serial.println(httpResponseCode);

  String response = http.getString();
  Serial.println("Response:");
  Serial.println(response);

  http.end();

  // ======================
  // Validasi JSON
  // ======================
  if (!response.startsWith("{")) {
    Serial.println("Response bukan JSON, skip parsing");
    led_NG();
    return;
  }

  DynamicJsonDocument doc(1024);
  if (deserializeJson(doc, response)) {
    Serial.println("Failed to parse JSON");
    led_NG();
    return;
  }

  // ======================
  // Proses data relay
  // ======================
  JsonArray data = doc["data"].as<JsonArray>();
  unsigned long currentMillisWarning = millis();

  for (JsonObject obj : data) {
    int id = obj["id"];
    int status = obj["Status"];

    Serial.printf("id: %d, Status: %d\n", id, status);

    switch (status) {
      case 0:
        setRelay(id, false);
        updateRelays();
        Serial.println("Table Status OFF");
        break;

      case 1:
        setRelay(id, true);
        updateRelays();
        Serial.println("Table Status ON");
        break;

      case 2:
        if (currentMillisWarning - previousMillisWarning >= intervalWarning) {
          previousMillisWarning = currentMillisWarning;
          for (int i = 0; i < 4; i++) {
            setRelay(id, false);
            updateRelays();
            delay(500);
            setRelay(id, true);
            updateRelays();
            delay(500);
          }
          setRelay(id, true);
          updateRelays();
          Serial.println("Table Status WARNING");
        } else {
          Serial.println("Waiting WARNING");
        }
        break;

      case -1:
        setRelay(id, false);
        updateRelays();
        Serial.println("Table Status CHECKOUT");
        break;

      case 3:
        setRelay(id, true);
        updateRelays();
        delay(3000);
        setRelay(id, false);
        updateRelays();
        Serial.println("Table Status TEST DEVICE CONNECTION");
        break;
    }
  }

  led_OK();
}

void releaseCommand() {
  HTTPClient http;
  WiFiClientSecure client;
  client.setInsecure();  // sementara untuk bypass sertifikat SSL
  String url = String(apiEndpoint) + "/releaseCommand";   // endpoint HTTPS
  String response;
  int Command = 0;

  http.begin(client, url);
  http.addHeader("Content-Type", "application/json");

  String payload = "{\"Command\":\"" + String(Command) +
                   "\",\"SN\":\"" + SerialNumber + "\"}";

  // Debug
  Serial.println("POST " + url);
  Serial.println("Payload: " + payload);

  int httpResponseCode = http.POST(payload);

  if (httpResponseCode <= 0) {
    Serial.print("HTTP Error: ");
    Serial.println(httpResponseCode);
    http.end();
    return;
  }

  Serial.print("HTTP Response Code: ");
  Serial.println(httpResponseCode);

  response = http.getString();
  Serial.println("Response:");
  Serial.println(response);

  http.end();
}

void Reset_data()
{
  File configFile = SPIFFS.open("/config.json", "r");
  if (!configFile) {
    Serial.println("Gagal membuka config.json untuk dibaca.");
    return;
  }

  // Alokasikan buffer dan parse JSON
  StaticJsonDocument<1024> doc;
  DeserializationError error = deserializeJson(doc, configFile);
  configFile.close();

  if (error) {
    Serial.print("Gagal parsing config.json: ");
    Serial.println(error.f_str());
    return;
  }

  doc["recordOwnerID"] = recordOwnerID;
  doc["apiEndpoint"] =  apiEndpoint;

  configFile = SPIFFS.open("/config.json", "w");
  if (!configFile) {
    Serial.println("Gagal membuka config.json untuk ditulis.");
    return;
  }

  serializeJsonPretty(doc, configFile);
  configFile.close();

  Serial.println("Berhasil direset ke default.");
}

  
void led_OK()
{
  digitalWrite(LED_SIGNAL, HIGH);
  delay(400);
  digitalWrite(LED_SIGNAL, LOW);
}
void led_NG()
{
  for(int a = 0;a < 3;a++)
  {
    digitalWrite(LED_SIGNAL, HIGH);
    delay(300);
    digitalWrite(LED_SIGNAL, LOW);
    delay(300);
  }
}
void led_signal()
{
  int signal_connecting = led_blink % 3;

  if(WiFiMode)
  {
    digitalWrite(LED_WIFI, signal_connecting == 0 ? HIGH : LOW);
    digitalWrite(LED_SIGNAL, signal_connecting == 0 ? HIGH : LOW);
  }
    
  if(EthernetMode)
  {
    digitalWrite(LED_LAN, signal_connecting == 0 ? HIGH : LOW);
    digitalWrite(LED_SIGNAL, signal_connecting == 0 ? HIGH : LOW);
  }
      
  led_blink++;
}

bool testInternetConnection() {
  HTTPClient http;
  int httpCode = -1;

  Serial.println("Testing internet connection...");

  if (WiFiMode) {
    // ===== WiFi =====
    WiFiClient client;
    http.begin(client, "http://clients3.google.com/generate_204");
    httpCode = http.GET();
  }
  else if (EthernetMode) {
    // ===== Ethernet =====
    //EthernetClient client;
    http.begin("http://clients3.google.com/generate_204");
    httpCode = http.GET();
  }
  else {
    Serial.println("No active network mode");
    return false;
  }

  http.end();

  if (httpCode > 0) {
    Serial.printf("Internet OK (HTTP code: %d)\n", httpCode);
    return true;
  } else {
    Serial.printf("Internet NOT OK (HTTP code: %d)\n", httpCode);
    return false;
  }
}


// Command Control Relay

void setRelay(int relayNum, bool state) 
{
  int index = (relayNum - 1) / 8;         
  int bitPos = (relayNum - 1) % 8;        
  bitWrite(relayState[index], bitPos, state);
}

void updateRelays() 
{
  digitalWrite(latchPin, LOW);
  for (int i = 2; i >= 0; i--) 
  { 
    shiftOut(dataPin, clockPin, LSBFIRST, relayState[i]);
  }
  digitalWrite(latchPin, HIGH);
}

//===================
//Debug Purpose, 
void debug_relay() 
{
  if (Serial.available()) {
    String input = Serial.readStringUntil('\n');
    input.trim();

    if (input.equalsIgnoreCase("#debug_on")) {
      debugMode = true;
      Serial.println("DEBUG MODE AKTIF.");
      return;
    } else if (input.equalsIgnoreCase("#debug_off")) {
      debugMode = false;
      Serial.println("DEBUG MODE NONAKTIF.");
      return;
    }

    if (!debugMode) return;

    if (input.startsWith("relay")) {
      int relayNum = input.substring(5).toInt();
      bool setValue = input.endsWith("on");

      if (relayNum >= 1 && relayNum <= 24) {
        setRelay(relayNum, setValue);
        Serial.printf("Relay %d di-set ke %s\n", relayNum, setValue ? "ON" : "OFF");
        updateRelays();
      } else {
        Serial.println("Active Relay Only 1 - 24");
      }

    } else if (input.equalsIgnoreCase("all off")) {
      for (int i = 0; i < 3; i++) relayState[i] = 0b00000000;
      Serial.println("Semua relay dimatikan.");
      updateRelays();

    } else if (input.equalsIgnoreCase("all on")) {
      for (int i = 0; i < 3; i++) relayState[i] = 0b11111111;
      Serial.println("Semua relay dinyalakan.");
      updateRelays();

    } else {
      Serial.println("Perintah tidak dikenali. Contoh: relay5 on / all off");
    }
  }
}
