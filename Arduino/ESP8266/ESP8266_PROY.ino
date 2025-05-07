#include <SPI.h>             
#include <MFRC522.h>         
#include <ESP8266WiFi.h>     
#include <ESP8266HTTPClient.h> 

#define RST_PIN 5   
#define SS_PIN 4   

MFRC522 reader(SS_PIN, RST_PIN); // Instancia del lector RFID

// Configuración WiFi
const char *ssid = "whoami";         
const char *password = "whoami12";          
const char* nombreServidor = "http://192.168.118.113/data"; 

// Objeto para cliente WiFi
WiFiClient cliente;

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.print(".");
  }
  Serial.println("\nWiFi conectado");

  SPI.begin();
  reader.PCD_Init();
}

void loop() {
  if (WiFi.status() != WL_CONNECTED) {
    Serial.println("WiFi desconectado.");
    return;
  }

  if (!reader.PICC_IsNewCardPresent()) {
    return;
  }

  if (!reader.PICC_ReadCardSerial()) {
    Serial.println("Error al leer la tarjeta.");
    return;
  }

  String serial = "";
  for (int x = 0; x < reader.uid.size; x++) {
    if (reader.uid.uidByte[x] < 0x10) {
      serial += "0";
    }
    serial += String(reader.uid.uidByte[x], HEX);
    if (x + 1 != reader.uid.size) {
      serial += "-";
    }
  }
  serial.toUpperCase();

  Serial.print("ID de la tarjeta: ");
  Serial.println(serial); 
  reader.PICC_HaltA(); 
  reader.PCD_StopCrypto1();

   // Enviar el ID al servidor
    HTTPClient http;
    http.begin(cliente, nombreServidor);
    http.addHeader("Content-Type", "text/plain");

    Serial.println("Enviando ID al servidor...");
    int RespuestaHTTP = http.POST(serial);

    if (RespuestaHTTP > 0) {
        Serial.println("Respuesta del servidor: " + http.getString());
    } else {
        Serial.print("Error HTTP: ");
        Serial.println(RespuestaHTTP);
    }

  http.end();
  delay(2000);
}

 
