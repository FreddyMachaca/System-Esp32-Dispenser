#include <WiFi.h>
#include <WebServer.h>
#include <Wire.h>
// #include <LiquidCrystal_I2C.h>
#include <HTTPClient.h>
#include <Keypad.h>
#include <ArduinoJson.h>

// Configuración WiFi
const char *ssid = "whoami";
const char *password = "whoami12";

// Inicializar servidor en el puerto 80
WebServer server(80);

// Configuración del LCD (desactivado)
// LiquidCrystal_I2C lcd(0x27, 16, 2);

// Dirección del servidor remoto
const String SERVER_ADDRESS = "http://192.168.153.89/Proyecto_SCO";

// Configuración del teclado matricial
const byte FILAS = 4;
const byte COLUMNAS = 3;
char teclas[FILAS][COLUMNAS] = {
    {'1', '2', '3'},
    {'4', '5', '6'},
    {'7', '8', '9'},
    {'*', '0', '#'}
};
// Pines de conexión del teclado matricial
byte pinesFilas[FILAS] = {15, 2, 0, 4};
byte pinesColumnas[COLUMNAS] = {16, 17, 5};
Keypad teclado = Keypad(makeKeymap(teclas), pinesFilas, pinesColumnas, FILAS, COLUMNAS);

// Variables globales
String serialRecibido = "";
String codigoProducto = "";
int ultimoNumeroActivado = 0; 
bool salidasActivas = false;
unsigned long tiempoActivacion = 0;
bool esperandoCaida = false;
bool productoVerificado = false;
String infoProducto = "";
String infoEstudiante = "";
String infoPrecio = "";
String infoSaldoActual = "";
String infoSaldoRestante = "";

// Pines de salida
const int OUTPUT_AE = 18; 
const int OUTPUT_BE = 19; 
const int OUTPUT_CC = 21; 
const int OUTPUT_BB = 22; 
const int OUTPUT_AA = 23; 

// Pin de entrada para detener las salidas
const int PIN_STOP = 13;

// Tiempo de espera máximo para detección de caída (5 segundos)
const unsigned long TIEMPO_ESPERA_CAIDA = 5000;

// Prototipos de funciones
void establecerSalidas(int numero);
void enviarAlServidor(String dato, int tipo, String tecla, String accion = "verificar");
void procesarRespuestaTransaccion(String respuesta, String accion);
void mostrarRespuestaEnLCD(String respuesta);

// Función para establecer las salidas según el número presionado (1-20)
void establecerSalidas(int numero) {
    // Valores por defecto (todo apagado)
    int ae = 0, be = 0, cc = 0, bb = 0, aa = 0;
    
    if (numero >= 1 && numero <= 20) {
        switch(numero) {
            case 1:  // 01000 - M0
                be = 1; cc = 0; bb = 0; aa = 0;
                break;
            case 2:  // 01001 - M1
                be = 1; cc = 0; bb = 0; aa = 1;
                break;
            case 3:  // 01010 - M2
                be = 1; cc = 0; bb = 1; aa = 0;
                break;
            case 4:  // 01011 - M3
                be = 1; cc = 0; bb = 1; aa = 1;
                break;
            case 5:  // 01100 - M4
                be = 1; cc = 1; bb = 0; aa = 0;
                break;
            case 6:  // 01101 - M5
                be = 1; cc = 1; bb = 0; aa = 1;
                break;
            case 7:  // 01110 - M6
                be = 1; cc = 1; bb = 1; aa = 0;
                break;
            case 8:  // 01111 - M7
                be = 1; cc = 1; bb = 1; aa = 1;
                break;
            case 9:  // 10000 - M8
                ae = 1; be = 0; cc = 0; bb = 0; aa = 0;
                break;
            case 10:  // 10001 - M9
                ae = 1; be = 0; cc = 0; bb = 0; aa = 1;
                break;
            case 11:  // 10010 - M10
                ae = 1; be = 0; cc = 0; bb = 1; aa = 0;
                break;
            case 12:  // 10011 - M11
                ae = 1; be = 0; cc = 0; bb = 1; aa = 1;
                break;
            case 13:  // 10100 - M12
                ae = 1; be = 0; cc = 1; bb = 0; aa = 0;
                break;
            case 14:  // 10101 - M13
                ae = 1; be = 0; cc = 1; bb = 0; aa = 1;
                break;
            case 15:  // 10110 - M14
                ae = 1; be = 0; cc = 1; bb = 1; aa = 0;
                break;
            case 16:  // 10111 - M15
                ae = 1; be = 0; cc = 1; bb = 1; aa = 1;
                break;
            case 17:  // 11000 - M16
                ae = 1; be = 1; cc = 0; bb = 0; aa = 0;
                break;
            case 18:  // 11001 - M17
                ae = 1; be = 1; cc = 0; bb = 0; aa = 1;
                break;
            case 19:  // 11010 - M18
                ae = 1; be = 1; cc = 0; bb = 1; aa = 0;
                break;
            case 20:  // 11011 - M19
                ae = 1; be = 1; cc = 0; bb = 1; aa = 1;
                break;
        }
    }
    
    // Establecer las salidas según los valores calculados
    digitalWrite(OUTPUT_AE, ae);
    digitalWrite(OUTPUT_BE, be);
    digitalWrite(OUTPUT_CC, cc);
    digitalWrite(OUTPUT_BB, bb);
    digitalWrite(OUTPUT_AA, aa);
    
    if (numero > 0) {
        Serial.println("Número: " + String(numero));
    }
}

void handleData() {
    if (server.hasArg("plain")) {
        serialRecibido = server.arg("plain");
        Serial.println("Serial recibido del ESP8266: " + serialRecibido);
        Serial.println("\n----- TARJETA DETECTADA -----");
        Serial.println("¡Bienvenido! Por favor ingrese el número del producto (1-20)");
        Serial.println("y presione * para confirmar");
        Serial.println("-----------------------------\n");

        // lcd.clear();
        // lcd.print("Serial recibido:");
        // lcd.setCursor(0, 1);
        // lcd.print(serialRecibido);

        enviarAlServidor(serialRecibido, 0, "");
        server.send(200, "text/plain", "Serial recibido y enviado");
    } else {
        server.send(400, "text/plain", "No se recibieron datos");
    }
}

void enviarAlServidor(String dato, int tipo, String tecla, String accion) {
    HTTPClient http;
    WiFiClient client;
    String full_url, payload;

    if (tipo == 0) {
        full_url = SERVER_ADDRESS + "/Esp32/recibirSerial.php";
        payload = "serial=" + dato;
    } else if (tipo == 1) {
        full_url = SERVER_ADDRESS + "/Esp32/registroTransaccion.php";
        payload = "serial=" + dato + "&num=" + tecla + "&accion=" + accion;
    }

    if (full_url.length() > 0) {
        http.begin(client, full_url);
        http.addHeader("Content-Type", "application/x-www-form-urlencoded");

        int codigo_respuesta = http.POST(payload);
        if (codigo_respuesta > 0) {
            Serial.println("Código HTTP: " + String(codigo_respuesta));
            if (codigo_respuesta == 200) {
                String respuesta = http.getString();
                Serial.println("Respuesta del servidor: " + respuesta);
                
                // Procesar la respuesta JSON
                if (tipo == 1) { // Si es una transacción
                    procesarRespuestaTransaccion(respuesta, accion);
                }
                
                // mostrarRespuestaEnLCD(respuesta);
                delay(500);
            }
        } else {
            Serial.println("Error enviando datos al servidor.");
        }
        http.end();
    } else {
        Serial.println("No se preparó URL para enviar al servidor.");
    }
}

// Procesar la respuesta JSON de la transacción
void procesarRespuestaTransaccion(String respuesta, String accion) {
    DynamicJsonDocument doc(1024);
    DeserializationError error = deserializeJson(doc, respuesta);
    
    if (error) {
        Serial.println("Error al analizar el JSON");
        return;
    }
    
    bool status = doc["status"];
    String mensaje = doc["message"];
    
    Serial.println("Status: " + String(status ? "true" : "false"));
    Serial.println("Mensaje: " + mensaje);
    
    if (status && doc.containsKey("data")) {
        JsonObject data = doc["data"];
        
        if (accion == "verificar") {
            // Guardar información para mostrar cuando se complete la transacción
            infoEstudiante = data["estudiante"].as<String>();
            infoProducto = data["producto"].as<String>();
            infoPrecio = data["precio"].as<String>();
            infoSaldoActual = data["saldo_actual"].as<String>();
            infoSaldoRestante = data["saldo_restante"].as<String>();
            
            // Mostrar información en el serial
            Serial.println("\n----- DETALLE DE PRODUCTO -----");
            Serial.println("Estudiante: " + infoEstudiante);
            Serial.println("Producto: " + infoProducto);
            Serial.println("Precio: " + infoPrecio);
            Serial.println("Saldo actual: " + infoSaldoActual);
            Serial.println("Saldo después de compra: " + infoSaldoRestante);
            Serial.println("-------------------------------\n");
            
            productoVerificado = true;
        } 
        else if (accion == "comprar") {
            String saldoNuevo = data["saldo_actual"].as<String>();
            
            Serial.println("\n------ COMPRA REALIZADA ------");
            Serial.println("Estudiante: " + infoEstudiante);
            Serial.println("Producto: " + infoProducto);
            Serial.println("Precio: " + infoPrecio);
            Serial.println("Saldo anterior: " + infoSaldoActual);
            Serial.println("Saldo actual: " + saldoNuevo);
            Serial.println("-------------------------------\n");
        }
    } else {
        Serial.println("\n------ ERROR EN TRANSACCIÓN ------");
        Serial.println(mensaje);
        Serial.println("---------------------------------\n");
        
        // Si la verificación falla, asegurarse de marcar como no verificado
        if (accion == "verificar") {
            productoVerificado = false;
        }
    }
}

void mostrarRespuestaEnLCD(String respuesta) {
    // lcd.clear();
    // int separador = respuesta.indexOf('\n');
    // if (separador != -1) {
    //     lcd.setCursor(0, 0);
    //     lcd.print(respuesta.substring(0, separador));
    //     lcd.setCursor(0, 1);
    //     lcd.print(respuesta.substring(separador + 1)); 
    // } else {
    //     lcd.print(respuesta); 
    // }
}

void setup() {
    Serial.begin(115200);
    WiFi.begin(ssid, password);

    // lcd.init();
    // lcd.backlight();
    // lcd.print("Conectando...");

    int intentos = 30;
    while (WiFi.status() != WL_CONNECTED && intentos > 0) {
        delay(1000);
        Serial.println("Conectando a WiFi...");
        intentos--;
    }
    if (WiFi.status() == WL_CONNECTED) {
        Serial.println("Conectado a WiFi");
        Serial.println("IP: " + WiFi.localIP().toString());
        // lcd.clear();
        // lcd.print("WiFi Conectado");
    } else {
        Serial.println("Error: No se pudo conectar.");
        ESP.restart();
    }
    
    // Configurar pines de salida
    pinMode(OUTPUT_AE, OUTPUT);
    pinMode(OUTPUT_BE, OUTPUT);
    pinMode(OUTPUT_CC, OUTPUT);
    pinMode(OUTPUT_BB, OUTPUT);
    pinMode(OUTPUT_AA, OUTPUT);
    
    // Configurar pin de parada como entrada
    pinMode(PIN_STOP, INPUT);
    
    // Inicializar todos los pines de salida en 0
    digitalWrite(OUTPUT_AE, LOW);
    digitalWrite(OUTPUT_BE, LOW);
    digitalWrite(OUTPUT_CC, LOW);
    digitalWrite(OUTPUT_BB, LOW);
    digitalWrite(OUTPUT_AA, LOW);

    server.on("/data", HTTP_POST, handleData);
    server.begin();
    Serial.println("Servidor iniciado");
}

void loop() {
    server.handleClient();

    // Verificar si hay que desactivar las salidas por pin 13 en HIGH (caída del producto)
    if (salidasActivas) {
        int estado = digitalRead(PIN_STOP);
        if (estado == HIGH) {
            // Guardar el número de slot antes de apagar las salidas
            int slotNumero = ultimoNumeroActivado;
            establecerSalidas(0);
            salidasActivas = false;
            esperandoCaida = false;
            
            // Producto detectado, procesar la compra
            if (productoVerificado && serialRecibido != "") {
                Serial.println("Producto caído detectado - Procesando compra...");
                // Usar el slotNumero guardado, no codigoProducto que podría estar vacío
                enviarAlServidor(serialRecibido, 1, String(slotNumero), "comprar");
            }
            
            ultimoNumeroActivado = 0;
            Serial.println("Pin 13 HIGH: Producto entregado, salidas desactivadas");
        }
        
        // Si está esperando la caída del producto y ha pasado el tiempo máximo, apagar las salidas
        if (esperandoCaida && (millis() - tiempoActivacion > TIEMPO_ESPERA_CAIDA)) {
            establecerSalidas(0);
            salidasActivas = false;
            esperandoCaida = false;
            ultimoNumeroActivado = 0;
            Serial.println("Tiempo de espera excedido: No se detectó caída del producto");
        }
    }

    char tecla = teclado.getKey();
    if (tecla) {
        // Si la tecla es '#' se borra el código digitado
        if (tecla == '#') {
            if (codigoProducto.length() > 0) {
                codigoProducto = codigoProducto.substring(0, codigoProducto.length() - 1);
            }
        }
        // Si la tecla es '*' se actúa como ENTER: activar las salidas  
        else if (tecla == '*') {
            Serial.println("ENTER presionado (tecla '*'), codigoProducto: " + codigoProducto);
            if (codigoProducto.length() > 0) {
                int numProd = codigoProducto.toInt();
                if (numProd >= 1 && numProd <= 20) {
                    // Verificar si el serialRecibido existe y si hay producto en el slot
                    if (serialRecibido != "") {
                        // Primero verificar si hay producto y el estudiante puede comprarlo
                        enviarAlServidor(serialRecibido, 1, codigoProducto, "verificar");
                        
                        // Si el producto está verificado, activar las salidas
                        if (productoVerificado) {
                            establecerSalidas(numProd);
                            salidasActivas = true;
                            esperandoCaida = true;
                            tiempoActivacion = millis();
                            ultimoNumeroActivado = numProd;
                            Serial.println("Salidas ACTIVADAS para producto: " + String(numProd));
                            Serial.println("Esperando caída del producto (máximo 5 segundos)...");
                        } else {
                            Serial.println("No se pudo activar las salidas: Producto no verificado");
                        }
                    } else {
                        Serial.println("No hay tarjeta RFID registrada. Acerque una tarjeta primero.");
                    }
                } else {
                    Serial.println("Código de producto inválido para activar salidas: " + codigoProducto);
                }
            } else {
                Serial.println("ENTER presionado pero código no establecido");
            }
            codigoProducto = ""; 
            delay(200);
        }
        else {
            String nuevoCodigoProducto = "";
            String intento = codigoProducto + tecla;
            int numIntento = 0;
            if (intento.length() > 0) numIntento = intento.toInt();
    
            if (tecla == '0' && codigoProducto == "") {
                nuevoCodigoProducto = "";
            }
            else if (numIntento >= 1 && numIntento <= 20 && intento.length() <= 2) {
                if (intento.length() == 2 && intento[0] == '0') {
                    nuevoCodigoProducto = intento.substring(1);
                } else {
                    nuevoCodigoProducto = intento;
                }
            } else {
                if (tecla >= '1' && tecla <= '9') {
                    nuevoCodigoProducto = String(tecla);
                } else {
                    nuevoCodigoProducto = "";
                }
            }
            codigoProducto = nuevoCodigoProducto;
        }
    
        if (codigoProducto.length() > 0) {
            Serial.println("Codigo: " + codigoProducto);
        } else {
            Serial.println("Codigo: --");
        }
    }
}
