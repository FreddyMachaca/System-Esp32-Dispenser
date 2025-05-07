#include <WiFi.h>
#include <WebServer.h>
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <HTTPClient.h>
#include <Keypad.h>

// Configuración WiFi

const char *ssid = "whoami";
const char *password = "whoami12";

// Inicializar servidor en el puerto 80
WebServer server(80);

// Configuración del LCD
LiquidCrystal_I2C lcd(0x27, 16, 2);

// Dirección del servidor remoto
const String SERVER_ADDRESS = "http://192.168.118.89/Proyecto_SCO";

// Configuración del teclado matricial
const byte FILAS = 4; // Número de filas
const byte COLUMNAS = 3; // Número de columnas

// Definición de las teclas del teclado
char teclas[FILAS][COLUMNAS] = {
    {'1', '2', '3'},
    {'4', '5', '6'},
    {'7', '8', '9'},
    {'*', '0', '#'}
};

// Definición de los pines de filas y columnas
byte pinesFilas[FILAS] = {32, 33, 25, 26};
byte pinesColumnas[COLUMNAS] = {27, 14, 12};

Keypad teclado = Keypad(makeKeymap(teclas), pinesFilas, pinesColumnas, FILAS, COLUMNAS);

// Variables globales
String serialRecibido = "";  // Almacena el serial recibido
String codigoProducto = ""; // Código ingresado por el teclado

// Pines para los motores
const int MOTOR1_PIN1 = 13, MOTOR1_PIN2 = 15;
const int MOTOR2_PIN1 = 2, MOTOR2_PIN2 = 4;

// Pines para sensores infrarrojos
const int SENSOR1_PIN = 18, SENSOR2_PIN = 19;

// Función para manejar solicitudes POST del ESP8266
void handleData() {
    if (server.hasArg("plain")) {
        serialRecibido = server.arg("plain");
        Serial.println("Serial recibido del ESP8266: " + serialRecibido);

        // Mostrar en el LCD
        lcd.clear();
        lcd.print("Serial recibido:");
        lcd.setCursor(0, 1);
        lcd.print(serialRecibido);

        // Enviar el serial al servidor
        enviarAlServidor(serialRecibido, 0, "");

        server.send(200, "text/plain", "Serial recibido y enviado");
    } else {
        server.send(400, "text/plain", "No se recibieron datos");
    }
}

// Función para enviar datos al servidor remoto
void enviarAlServidor(String dato, int tipo, String tecla) {
    HTTPClient http;
    WiFiClient client;
    String full_url, payload;

    if (tipo == 0) {
        full_url = SERVER_ADDRESS + "/ESP32/recibirSerial.php";
        payload = "serial=" + dato;
    } else if (tipo == 1) {
      boolean estado_p;

      if (tecla == "10") { 
          estado_p = controlarMotor(MOTOR1_PIN1, MOTOR1_PIN2, SENSOR2_PIN); 
          if (estado_p) {
              // Operación exitosa
              full_url = SERVER_ADDRESS + "/ESP32/registroTransaccion.php";
              payload = "serial=" + dato + "&num=" + tecla; 
          } else {
              // Error: No se detectó el objeto
              mostrarRespuestaEnLCD("Sin deteccion");
              delay(3000); // Mostrar mensaje por 3 segundos
          }
      } else if (tecla == "8") {  
          estado_p = controlarMotor(MOTOR2_PIN1, MOTOR2_PIN2, SENSOR1_PIN);  

          if (estado_p) {
              // Operación exitosa
              full_url = SERVER_ADDRESS + "/ESP32/registroTransaccion.php";
              payload = "serial=" + dato + "&num=" + tecla; 
          } else {
              // Error: No se detectó el objeto 
              mostrarRespuestaEnLCD("Sin deteccion");
              delay(3000); // Mostrar mensaje por 3 segundos
          }
      } else { 
        // Operaciones para teclas distintas de "10" y "8"
        full_url = SERVER_ADDRESS + "/ESP32/registroTransaccion.php";
        payload = "serial=" + dato + "&num=" + tecla;
      }
    }

    http.begin(client, full_url);
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    int codigo_respuesta = http.POST(payload);
    if (codigo_respuesta > 0) {
        Serial.println("Código HTTP: " + String(codigo_respuesta));
        if (codigo_respuesta == 200) {
            String respuesta = http.getString();
            Serial.println("Respuesta del servidor: " + respuesta);
            if (tipo == 0) {
              mostrarRespuestaEnLCD(respuesta);
            }else if(tipo == 1){
              mostrarRespuestaEnLCD(respuesta);
              delay(3000);
            } 
        }
    } else {
        Serial.println("Error enviando datos al servidor.");
    }
    http.end();
}

// Mostrar respuesta en el LCD (con manejo de líneas)
void mostrarRespuestaEnLCD(String respuesta) {
    lcd.clear();
    int separador = respuesta.indexOf('\n');
    if (separador != -1) {
        lcd.setCursor(0, 0);
        lcd.print(respuesta.substring(0, separador));
        lcd.setCursor(0, 1);
        lcd.print(respuesta.substring(separador + 1)); 
    } else {
        lcd.print(respuesta); 
    }
}
 
bool controlarMotor(int motorPin1, int motorPin2, int sensorPin) {
    // Encender el motor
    digitalWrite(motorPin1, LOW);
    digitalWrite(motorPin2, HIGH);

    bool estado = true; // Indica si el sensor detectó un objeto
    unsigned long tiempoInicio = millis();
    unsigned long tiempoMaximo = 5000; // Tiempo máximo para esperar (5 segundos)

    // Esperar hasta que el sensor detecte un objeto o se agote el tiempo
    while (digitalRead(sensorPin) == HIGH) {
        if (millis() - tiempoInicio > tiempoMaximo) {
            Serial.println("Timeout: Objeto no detectado.");
            estado = false; // Cambiar estado a false si no se detectó objeto
            break;          // Salir del bucle
        }
        delay(20); // Pequeño retraso para evitar bucles rápidos
    }

    // Apagar el motor después de salir del bucle
    digitalWrite(motorPin1, LOW);
    digitalWrite(motorPin2, LOW);

    return estado; // Devolver el estado de detección del sensor
}


// Configuración inicial
void setup() {
    Serial.begin(115200);
    WiFi.begin(ssid, password);

    lcd.init();
    lcd.backlight();
    lcd.print("Conectando...");

    // Conexión WiFi con tiempo máximo de espera
    int intentos = 30;
    while (WiFi.status() != WL_CONNECTED && intentos > 0) {
        delay(1000);
        Serial.println("Conectando a WiFi...");
        intentos--;
    }
    if (WiFi.status() == WL_CONNECTED) {
        Serial.println("Conectado a WiFi");
        Serial.println("IP: " + WiFi.localIP().toString());
        lcd.clear();
        lcd.print("WiFi Conectado");
    } else {
        Serial.println("Error: No se pudo conectar.");
        ESP.restart();
    }

    // Configurar pines de motores y sensores
    pinMode(MOTOR1_PIN1, OUTPUT);
    pinMode(MOTOR1_PIN2, OUTPUT);
    pinMode(MOTOR2_PIN1, OUTPUT);
    pinMode(MOTOR2_PIN2, OUTPUT);
    pinMode(SENSOR1_PIN, INPUT);
    pinMode(SENSOR2_PIN, INPUT);

    // Iniciar servidor web
    server.on("/data", HTTP_POST, handleData);
    server.begin();
    Serial.println("Servidor iniciado");
}

// Bucle principal
void loop() {
    server.handleClient();

    char tecla = teclado.getKey();
  if (tecla) {
        if (tecla == '*') {
            // Borrar el último dígito
            if (codigoProducto.length() > 0) {
                codigoProducto = codigoProducto.substring(0, codigoProducto.length() - 1);
            }
        } else if (tecla == '#') {
            if (serialRecibido != "") {
                enviarAlServidor(serialRecibido, 1, codigoProducto);
                codigoProducto = ""; // Reiniciar código
                serialRecibido = ""; // Limpiar serial recibido
            }else{
              lcd.clear();
              lcd.print("Esperando");
              lcd.setCursor(0, 1);
              lcd.print("Tarjeta");
              delay(2000);
            }
        } else if (codigoProducto.length() < 6) { // Limitar a 6 caracteres
            codigoProducto += tecla;
        }

        lcd.clear();
        lcd.print("Codigo: ");
        lcd.setCursor(0, 1);
        lcd.print(codigoProducto);
  }
}
