# Day 1

Основы Web-программирования. Установка и настройка ПО (Apache, MySQL, PHP).

* What is a web development?
* What is a website?
* What is a web page?
* What does HTML & CSS mean?
* What is an HTTP?
* What is a URL?
* What are static & dynamic websites?
* How does a web server work?
* The apache-php-mysql flow.
* Setup the Apache HTTP Server
* Setup PHP
* Setup Mysql & Mysql workbench
* Configure apache and set up a virtual host.
  * enable mode_rewrite
  * enable php
  * update DirectoryIndex
  * include virtual hosts
* Configure PHP
  * error_reporting = E_ALL & ~E_DEPRECATED
  * display_errors = 1
  * date.timezone = "Europe/Kiev"
  * short_open_tag = 0
* Configure Mysql workbench

### Enabling php in apache conf
```
LoadModule php5_module "D:/php/php5apache2_4.dll"
AddHandler application/x-httpd-php .php
# configure the path to php.ini
PHPIniDir "D:/php"
```
```
<IfModule dir_module>
    DirectoryIndex index.php index.html
</IfModule>
```

## HTTP request and response examples

### Request
```
GET /products HTTP/1.1
Host: web-shop.com
Accept: */*
```

```
POST /login HTTP/1.1
Host: web-shop.com
Content-Type: application/x-www-form-urlencoded
Content-Length: 132
Accept: */*

email=example.com&password=123132
```

### Response
```
HTTP/1.1 200 OK
Content-Type: text/html; charset=utf-8
Content-Length: 45612

<!doctype html>
<html>
<head>
    <title>Products</title>
</head>
<body>
    <!-- List of products -->
</body>
</html>
```

```
HTTP/1.1 302 Moved Temporarily
Location: /correct-url
```

## Basic Apache virtual host configuration
```
# Ensure that Apache listens on port 80
Listen 80

# Listen for virtual host requests on all IP addresses
NameVirtualHost *:80

<VirtualHost *:80>
    ServerName my-website.loc
    DocumentRoot /your/website/dir/web

    ErrorLog /your/website/dir/error.log
    CustomLog /your/website/dir/access.log combined

    <Directory /your/website/dir/web>
        Require all granted
        AllowOverride all
    </Directory>
</VirtualHost>
```

## Resources

* XAMP:
https://www.apachefriends.org/index.html
* Apache HTTP Server:
http://httpd.apache.org/docs/current/platform/windows.html#down
http://www.apachelounge.com/download/
* Mysql:
https://dev.mysql.com/downloads/windows/installer/5.6.html
* PHP:
http://windows.php.net/download#php-5.6
* Windows x64 or x86?
http://stackoverflow.com/questions/12322308/batch-file-to-check-64bit-or-32bit-os
* Setup step-by-step:
http://blog.denisbondar.com/post/apache24php56win7