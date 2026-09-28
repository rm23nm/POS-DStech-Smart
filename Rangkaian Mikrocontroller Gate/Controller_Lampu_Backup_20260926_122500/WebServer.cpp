#include "Credentials.h"
#include "style.css.h"

String scannedNetworks = "";

void scanWiFiNetworks() {
  int numNetworks = WiFi.scanNetworks();
  if (numNetworks == 0) {
    scannedNetworks = "<p>No networks found</p>";
  } else {
    scannedNetworks = "<select id='ssid' name='ssid' required>";
    for (int i = 0; i < numNetworks; i++) {
      scannedNetworks += "<option value='" + WiFi.SSID(i) + "'>" + WiFi.SSID(i) + "</option>";
    }
    scannedNetworks += "</select><br><br>";
  }
}

void setupWebServer() {
  server.on("/", HTTP_GET, [](){
  String html = R"rawliteral(
  <!DOCTYPE html>
  <html>
  <head>
    <title>ESP32 Main Menu</title>
    <link rel="stylesheet" href="/style.css">
  </head>
  <body>
    <div class="container">
      <h1>ESP32 24CH Relay</h1>
      <form action="/wifi" method="GET">
        <button type="submit">Wi-Fi Setting</button>
      </form>
      <form action="/setup" method="GET">
        <button type="submit">Setup Config</button>
      </form>
      <form action="/quit" method="POST">
        <button type="submit">Quit / Restart</button>
      </form>
    </div>
  </body>
  </html>
)rawliteral";

  server.send(200, "text/html", html);
});
  
//=============

server.on("/wifi", HTTP_GET, [](){
  
  Preferences preferences;
  preferences.begin("wifi", true);  // true = read only
  String savedSSID = preferences.getString("ssid", "");
  String password = preferences.getString("password", "");
  preferences.end();

  Serial.print("LOAD SSID :");Serial.println(savedSSID);
  
  String html = R"rawliteral(
    <!DOCTYPE html>
    <html>
    <head>
      <title>ESP32 Wi-Fi Setup</title>
      <link rel="stylesheet" href="/style.css">
      <style>
        #confirmDialog {
          display: none;
          position: fixed;
          top: 0; left: 0; right: 0; bottom: 0;
          background-color: rgba(0, 0, 0, 0.6);
          z-index: 1000;
        }
        .dialog-box {
          background: white;
          padding: 20px;
          border-radius: 10px;
          width: 300px;
          margin: 100px auto;
          text-align: center;
        }
        .dialog-box button {
          margin: 10px;
        }
      </style>
    </head>
    <body>
      <div class="container">
        <h1>Wi-Fi Setup</h1>
        <form id="wifiForm" action="/save" method="POST">
          <label for="ssid">Select SSID:</label><br>
  )rawliteral";

  if (savedSSID != "") {
    html += "<p><strong>Saved SSID:</strong> " + savedSSID + "</p>";
  } else {
    html += "<p><strong>Saved SSID:</strong> (none)</p>";
  }

  html += scannedNetworks;

  html += R"rawliteral(
          <label for="password">Password:</label><br>
          <input type="password" id="password" name="password" required>
          <input type="checkbox" id="showPassword" onclick="togglePassword()"> Show<br><br>
          
          <button type="button" onclick="showConfirm()">Save</button>
        </form>

        <form action="/" method="GET" style="margin-top:20px;">
          <button type="submit" class="btn-back">Back Main Menu</button>
        </form>
      </div>

      <!-- Konfirmasi Dialog -->
      <div id="confirmDialog">
        <div class="dialog-box">
          <p>Apakah Anda yakin ingin menyimpan Wi-Fi ini?</p>
          <button onclick="submitForm()">Ya, Simpan</button>
          <button onclick="closeConfirm()">Tidak, Batal</button>
        </div>
      </div>

      <script>
        function togglePassword() {
          var passInput = document.getElementById("password");
          passInput.type = passInput.type === "password" ? "text" : "password";
        }

        function showConfirm() {
          document.getElementById("confirmDialog").style.display = "block";
        }

        function closeConfirm() {
          document.getElementById("confirmDialog").style.display = "none";
        }

        function submitForm() {
          document.getElementById("wifiForm").submit();
        }
      </script>
    </body>
    </html>
  )rawliteral";

  server.send(200, "text/html", html);
});


//=============

server.on("/save", HTTP_POST, []() {

  if (!server.hasArg("ssid") || !server.hasArg("password")) {
    server.send(400, "text/plain", "Missing SSID or password");
    return;
  }

  String ssid = server.arg("ssid");
  String password = server.arg("password");
  
  Preferences preferences;
  preferences.begin("wifi", false);  // write mode
  preferences.putString("ssid", ssid);
  preferences.putString("password", password);
  preferences.end();

  Serial.println("Wi-Fi credentials saved:");
  Serial.println("SSID: " + ssid);
  Serial.println("Password: " + password);

  server.send(200, "text/html", R"rawliteral(
    <!DOCTYPE html>
    <html>
    <head><title>Saved</title><meta http-equiv="refresh" content="2; url=/" /></head>
    <body><h1>Wi-Fi Configuration Saved.</h1><p>Returning to menu...</p></body>
    </html>
  )rawliteral");
});



//=============
server.on("/setup", HTTP_GET, [](){
  
  if (!server.hasArg("access")) {
    String loginForm = R"rawliteral(
      <!DOCTYPE html>
      <html>
      <head><title>Login</title><link rel="stylesheet" href="/style.css"></head>
      <body>
        <div class="container">
          <h1>Password Required</h1>
          <form action="/setup" method="GET">
            <label for="access">Admin:</label><br>
            <input type="password" id="access" name="access" required><br><br>
            <button type="submit">Submit</button>
          </form>
          <form action="/" method="GET">
            <button type="submit" class="btn-back">Back to Menu</button>
          </form>
        </div>
      </body>
      </html>
    )rawliteral";
    server.send(200, "text/html", loginForm);
    return;
  }

  if (server.arg("access") != setupPassword) {
  String errorPage = R"rawliteral(
    <!DOCTYPE html>
    <html>
    <head>
      <title>Unauthorized</title>
      <meta http-equiv="refresh" content="3; url=/" />
      <link rel="stylesheet" href="/style.css">
    </head>
    <body>
      <div class="container">
        <h1>Unauthorized</h1>
        <p>Password salah. Kembali ke menu utama dalam 3 detik...</p>
      </div>
    </body>
    </html>
  )rawliteral";
  server.send(401, "text/html", errorPage);
  return;
}

  String html = R"rawliteral(
    <!DOCTYPE html>
    <html>
    <head>
      <title>ESP32 Setup</title>
      <link rel="stylesheet" href="/style.css">
      <style>
        #confirmDialog {
          display: none;
          position: fixed;
          top: 0; left: 0; right: 0; bottom: 0;
          background-color: rgba(0, 0, 0, 0.6);
          z-index: 1000;
        }
        .dialog-box {
          background: white;
          padding: 20px;
          border-radius: 10px;
          width: 300px;
          margin: 100px auto;
          text-align: center;
        }
        .dialog-box button {
          margin: 10px;
        }
      </style>
    </head>
    <body>
      <div class="container">
        <h1>Setup Configuration</h1>
        <form id="configForm" action="/saveconfig" method="POST">
          <label>Serial Number:</label><br>
          <input type="text" name="serial" value=")rawliteral" + config.serialNumber + R"rawliteral(" required><br><br>

          <label>AP Name:</label><br>
          <input type="text" name="apname" value=")rawliteral" + config.apName + R"rawliteral(" required><br><br>

          <label>Static IP Address:</label><br>
          <input type="text" name="ip" value=")rawliteral" + config.staticIP + R"rawliteral(" required><br><br>

          <label>Gateway:</label><br>
          <input type="text" name="gateway" value=")rawliteral" + config.gateway_IP + R"rawliteral(" required><br><br>

          <label>Subnet:</label><br>
          <input type="text" name="subnet" value=")rawliteral" + config.subnet_IP + R"rawliteral(" required><br><br>

          <label>API Address:</label><br>
          <input type="text" name="api" value=")rawliteral" + config.apiAddress + R"rawliteral(" required><br><br>

          <label>Record Owner ID:</label><br>
          <input type="text" name="recordowner" value=")rawliteral" + config.recordOwnerID + R"rawliteral(" maxlength="6" required><br><br>
          
          <label>API Endpoint:</label><br>
          <input type="text" name="apiendpoint" value=")rawliteral" + config.apiEndpoint + R"rawliteral(" required><br><br>

          <label>API Endpoint(LAN):</label><br>
          <input type="text" name="apiendpoint_lan" value=")rawliteral" + config.apiEndpoint_http + R"rawliteral(" required><br><br>
        
          <button type="button" onclick="showConfirm()">Save Config</button>
        </form>

        <form action="/" method="GET">
          <button type="submit" class="btn-back">Back to Menu</button>
        </form>
      </div>

      <!-- Popup konfirmasi -->
      <div id="confirmDialog">
        <div class="dialog-box">
          <p>Apakah Anda yakin ingin menyimpan konfigurasi ini?</p>
          <button onclick="submitForm()">Ya, Simpan</button>
          <button onclick="closeConfirm()">Tidak, Batal</button>
        </div>
      </div>

      <script>
        function showConfirm() {
          document.getElementById("confirmDialog").style.display = "block";
        }

        function closeConfirm() {
          document.getElementById("confirmDialog").style.display = "none";
        }

        function submitForm() {
          document.getElementById("configForm").submit();
        }
      </script>
    </body>
    </html>
  )rawliteral";

  server.send(200, "text/html", html);
});


//=============

server.on("/saveconfig", HTTP_POST, [](){

  String serial          = server.arg("serial");
  String apname          = server.arg("apname");
  String ip              = server.arg("ip");
  String api             = server.arg("api");
  String gateway         = server.arg("gateway");
  String subnet          = server.arg("subnet");
  String recordOwner     = server.arg("recordowner");
  String apiEndpoint     = server.arg("apiendpoint");
  String apiEndpoint_http= server.arg("apiendpoint_lan");
  
  File configFile = SPIFFS.open("/config.json", "w");
  if (configFile) {
    // Membuat dokumen JSON
    StaticJsonDocument<256> doc;
    doc["serialNumber"] = serial;
    doc["apName"] = apname;
    doc["staticIP"] = ip;
    doc["gateway_IP"] = gateway;
    doc["subnet_IP"] = subnet;
    doc["apiAddress"] = api;
    doc["recordOwnerID"] = recordOwner;
    doc["apiEndpoint"] = apiEndpoint;
    doc["apiEndpoint_http"] = apiEndpoint_http;
    
    if (serializeJson(doc, configFile) == 0) {
      Serial.println("Failed to write JSON to file.");
    } else {
      Serial.println("Config Saved to file:");
      Serial.println("Serial  : " + serial);
      Serial.println("AP Name : " + apname);
      Serial.println("IP      : " + ip);
      Serial.println("gateway : " + gateway);
      Serial.println("subnet  : " + subnet);
      Serial.println("API     : " + api);
      Serial.println("Record Owner ID     : " + recordOwner);
      Serial.println("API Endpoint        : " + apiEndpoint);
      Serial.println("API Endpoint(LAN)   : " + apiEndpoint_http);
    }

    configFile.close();
  } else {
    Serial.println("Failed to open file for writing.");
  }

  
  Serial.println("Config Saved:");
  Serial.println("Serial  : " + serial);
  Serial.println("AP Name : " + apname);
  Serial.println("IP      : " + ip);
  Serial.println("gateway : " + gateway);
  Serial.println("subnet  : " + subnet);
  Serial.println("API     : " + api);
  Serial.println("Record Owner ID   : " + recordOwner);
  Serial.println("API Endpoint      : " + apiEndpoint);
  Serial.println("API Endpoint(LAN) : " + apiEndpoint_http);
  
  String response = R"rawliteral(
    <!DOCTYPE html>
    <html>
    <head>
      <meta charset="utf-8">
      <title>Configuration Saved</title>
      <meta http-equiv="refresh" content="2;url=/" />
      <link rel="stylesheet" href="/style.css">
      <style>
        body { font-family: sans-serif; text-align: center; margin-top: 50px; }
        .container { background-color: #f0f0f0; padding: 30px; border-radius: 10px; display: inline-block; }
      </style>
    </head>
    <body>
      <div class="container">
        <h2>Konfigurasi berhasil disimpan!</h2>
        <p>Anda akan diarahkan kembali ke menu utama...</p>
      </div>
    </body>
    </html>
  )rawliteral";

  server.send(200, "text/html", response);
});

//=============

server.on("/quit", HTTP_POST, [](){
  String quitPage = R"rawliteral(
    <!DOCTYPE html>
    <html>
    <head>
      <title>Restarting...</title>
      <meta http-equiv="refresh" content="3; url=/" />
      <link rel="stylesheet" href="/style.css">
    </head>
    <body>
      <div class="container">
        <h1>Restarting ESP32</h1>
        <p>Perangkat akan restart dalam beberapa detik...</p>
      </div>
    </body>
    </html>
  )rawliteral";

  server.send(200, "text/html", quitPage);
  delay(1000); // Tunggu pengiriman selesai
  ESP.restart();
});


//=============

  // Serve the style.css file
  server.on("/style.css", HTTP_GET, [](){
    server.send(200, "text/css", style_css);
  });


  server.begin();
}
