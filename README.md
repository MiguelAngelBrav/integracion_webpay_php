# Taller practico de implementación Webpay
Este taller se realizo el día 9 de diciembre, en el marco de la Hackaton Santiago 2015. En este se realizó una implementación de Webpay usando el Manual Oficial y el KCC proporcionado por Transbank en un sitio web PHP. En este taller no se usó ningún tipo de plugin y los conocimientos previos necesarios eran programación web básica PHP y Javascript.

# Preparación del ambiente
La instalación de los archivos se realizo en un servidor Ubuntu 14.04 de 64bits. A continuación se detalla la preparación del ambiente, en que los archivos contenidos en este repositorio funcionan.

## 1. Instalación de servidor apache
Para instalar el servidor apache, se deben ejecutar los siguientes comandos:
```
sudo apt-get update && sudo apt-get upgrade
sudo apt-get install apache2
```
Para comprobar el estado de apache:
```
service apache2 status
```
En caso que el servidor apache este detenido, se debe iniciar con el siguiente comando:
```
service apache2 status
```


## 2. Instalación de PHP
Para instalar PHP, se deben ejecutar los siguientes comandos:
```
sudo apt-get install php5 libapache2-mod-php5 php5-mcrypt
sudo nano /etc/apache2/mods-enabled/dir.conf
```
Luego se debe agregar index.php a la configuración de apache de la siguiente forma:
```
<IfModule mod_dir.c>
  DirectoryIndex index.php index.html index.cgi index.pl index.php index.xhtml index.htm
</IfModule>
```
Esto permitirá que apache reconozca index.php como una pagina valida con la cual se inicia una aplicación.


## 3. Habilitar ejecución de cgi
Para que Apache ejecute los archivos “cgi” como una aplicación, se debe habilitar el “mod” de la siguiente forma:
```
sudo a2enmod cgi
sudo service apache2 restart
```


## 4. Configurar virtual host
El siguiente es un Virtual Host de ejemplo:
```
<VirtualHost *:80>
  DocumentRoot "/home/test-comercio"
  ScriptAlias /cgi-bin/ /home/test-comercio/webpay/
  <Directory "/home/test-comercio">
    Options +ExecCGI
    AddHandler cgi-script .cgi
    AllowOverride All
    Require all granted
  </Directory>
</VirtualHost>
```
Nótese que se esta asignando un alias a la carpeta “cgi-bin” y se agregan permisos de ejecución para los archivos “cgi”.


# Configuración de Webpay
Los archivos que son configurados en este taller, se deben descargar desde el sitio oficial de Transbank, en sección “Descargas”:
```
https://www.transbank.cl/public/productos-y-servicios/webpay/webpay-plus-kit-autorizacion-y-captura-simultanea/
```
A continuación se detallan los pasos para configurar Webpay.


## 1. Subir al servidor el zip con el KCC
Una vez que esta preparado el ambiente, se deben subir al servidor los archivos del KCC proporcionado por Transbank. Personalmente recomiendo subir los archivos usando SSH, lo cual se debe hacer de la siguiente forma:
```
scp /Users/macuser/Desktop/Taller\ implementación\ WebPay/linux_64_6.0.2.zip root@45.55.187.70:/home/test-comercio/linux_64_6.0.2.zip
```


## 2. Descomprimir el zip y eliminar el archivo original
Luego se deben descomprimir los archivos. En este ejemplo use la aplicación “unzip” para Ubuntu:
```
unzip linux_64_6.0.2.zip
```
Una vez que los archivos sean descomprimidos, se debe mover el directorio “cgi-bin” a la raíz del proyecto (en este caso a “test-comercio”), y luego se debe renombrar como “webpay”.

Además se debe crear el directorio “comun” dentro del directorio “log”.
```
mkdir comun
```


## 3. Asignación de permisos
Para que los “cgi” se puedan ejecutar sin problemas, deben tener permisos “755” y se deben ejecutar con el mismo usuario con que se ejecuta el servidor web. Para otorgar permisos debe ejecutar el siguiente comando a la carpeta raíz de la aplicación:
```
chmod –Rf 755 test-comercio
```
Para cambiar usuario y grupo a los archivos, se debe aplicar el siguiente comando a la carpeta raíz del proyecto “test-comercio”:
```
chown -Rf www-data:www-data test-comercio
```
Transbank recomienda en su manual oficial otra combinación de permisos, la cual puedes consultar en el manual de integración oficial.


## 4. Asignación de permisos de RED
Por ultimo solamente Transbank debe poder acceder a los “cgi”, por lo que se debe aplicar restricciones a nivel de red, para que ningún otro usuario tenga acceso a esos archivos.


## 5. Configuración tbk_config.dat
La siguiente es una configuración de pruebas y para conocer mayores detalles se debe consultar el “Manual de integración KCC”, pagina 27:
```
IDCOMERCIO = 597026007976
MEDCOM = 1
TBK_KEY_ID = 101
PARAMVERIFCOM = 1
URLCGICOM = /cgi-bin/tbk_bp_resultado.cgi
SERVERCOM = 45.55.187.70
PORTCOM = 80
WHITELISTCOM = ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz 0123456789./:=&?_
HOST = 45.55.187.70
WPORT = 80
URLCGITRA = /filtroUnificado/bp_revision.cgi
URLCGIMEDTRA = /filtroUnificado/bp_validacion.cgi
SERVERTRA = https://certificacion.webpay.cl
PORTTRA = 6443
PREFIJO_CONF_TR = HTML_
HTML_TR_NORMAL = http://45.55.187.70/cierre.php
```


## 6. Tarjeta de pruebas
El siguiente es el numero de tarjeta VISA que se puede usar para realizar las pruebas:
```
4051885600446623
```


# Autor
Esta aplicación fue desarrollada por Miguel Angel Bravo (Twitter: {@MiguelAngelBrav}[http://twitter.com/miguelangelbrav])


# Licencia
Esta aplicación puede ser usada por cualquier persona y/ó empresa para fines personales, comerciales ó lo que estime conveniente
siempre y cuando respete las normas y leyes vigentes en Chile.

