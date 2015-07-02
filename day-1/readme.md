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
  * include virtual hosts
* Configure PHP
  * error_reporting = E_ALL & ~E_DEPRECATED
  * display_errors = 1
  * date.timezone = "Europe/Kiev"
  * short_open_tag = 0
* Configure Mysql workbench

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

### Resonse
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
