# System-Esp32-Dispenser

System-Esp32-Dispenser es un sistema IoT que utiliza un ESP32 conectado a una aplicación web desarrollada en PHP, MySQL y JavaScript para controlar y monitorear un dispensador automatizado. El sistema se ejecuta localmente usando XAMPP como servidor.

Para que el sistema funcione correctamente, sigue estos pasos:

1. Instala XAMPP y asegúrate de que los servicios de Apache y MySQL estén activos.
2. Copia la carpeta del proyecto llamada `Proyecto_SCO` dentro del directorio `htdocs` de XAMPP. La ruta debe quedar así:

   C:\xampp\htdocs\Proyecto_SCO

3. Una vez copiado el proyecto, abre tu navegador y accede a la siguiente URL:

   http://localhost/Proyecto_SCO/Principal/principal.php

   ⚠️ Importante: No accedas a http://localhost/Proyecto_SCO/ directamente, ya que mostrará un error. Debes ir específicamente a la ruta `/Principal/principal.php`.

4. El ESP32 se comunica con el servidor a través del puerto 80. Para permitir esta comunicación, asegúrate de que el puerto 80 esté habilitado en el firewall de Windows. Para hacerlo, sigue estos pasos:

   - Abre el menú de inicio y busca “Firewall de Windows con seguridad avanzada”.
   - Haz clic en “Reglas de entrada” en el panel izquierdo.
   - Luego haz clic en “Nueva regla…” en el panel derecho.
   - Selecciona “Puerto” y haz clic en “Siguiente”.
   - Selecciona “TCP” y escribe el puerto: 80.
   - Haz clic en “Siguiente”, luego selecciona “Permitir la conexión”.
   - Haz clic en “Siguiente” hasta llegar al campo de nombre, donde puedes escribir: esp8266 (o cualquier nombre identificativo).
   - Finaliza la creación de la regla.

Con esto, el ESP32 podrá acceder al servidor local sin ser bloqueado por el firewall. El sistema ya estará listo para funcionar: la interfaz web permitirá enviar comandos al dispensador y visualizar los datos capturados por el ESP32 en tiempo real, utilizando MySQL para almacenar la información y JavaScript para actualizar dinámicamente la interfaz.

