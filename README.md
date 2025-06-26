# RoyalGuest Web Application

**RoyalGuest** is a service request management web application built using **CodeIgniter 4** and **MariaDB**, providing a platform to manage guest service requests efficiently.

---

## 🧾 Table of Contents

- [Project Structure](#-project-structure)
- [Installation Instructions](#-installation-instructions)
- [Virtual Host Configuration](#-virtual-host-configuration)
- [Hosts File Configuration](#-hosts-file-configuration)
- [Database Setup](#-database-setup)
- [Environment Configuration](#-environment-configuration)
- [API Details](#-api-details)
- [JWT Authentication](#-jwt-authentication)
- [Helpful Commands](#-helpful-commands)
- [Development Tools](#-development-tools)

---

## 📁 Project Structure

RoyalGuest/
│
├── app/
│ ├── Config/
│ ├── Controllers/
│ ├── Filters/
│ ├── Helpers/
│ ├── Models/
│ ├── Views/
│
├── public/ # Public root directory
│ ├── index.php
│ └── assets/
│ ├── css/
│ ├── js/
│
├── writable/ # Logs, cache, and uploads
├── tests/ # PHPUnit tests
├── .env
├── composer.json
└── generate_key.php # JWT secret generator

yaml
Copy
Edit

---

## 🛠️ Installation Instructions

1. **Install CodeIgniter Framework**
   ```bash
   composer create-project codeigniter4/framework RoyalGuest
Set the Environment to Development
Copy the .env file:

bash
Copy
Edit
cp env .env
Set Base URL and Database Config in .env

🌐 Virtual Host Configuration
✅ For Custom Domain (royalguest.local)
Path: httpd-vhosts.conf

apache
Copy
Edit
<VirtualHost *:80>
    DocumentRoot "D:/DEVELOP/RoyalGuest/RoyalGuest/public"
    ServerName royalguest.local
    ServerAlias www.royalguest.local

    <Directory "D:/DEVELOP/RoyalGuest/RoyalGuest/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog "logs/royalguest.local-error.log"
    CustomLog "logs/royalguest.local-access.log" common
</VirtualHost>
✅ For Alias (http://localhost/royalguest)
apache
Copy
Edit
<VirtualHost *:80>
    DocumentRoot "C:/xampp/htdocs"
    ServerName localhost

    Alias /royalguest "D:/DEVELOP/RoyalGuest/RoyalGuest/public"

    <Directory "D:/DEVELOP/RoyalGuest/RoyalGuest/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
🖥️ Hosts File Configuration
Edit the file C:\Windows\System32\drivers\etc\hosts and add:

lua
Copy
Edit
127.0.0.1    royalguest.local
🧩 Database Setup
Database Name: royalguest

Database Tool: SQLyog

DBMS: MariaDB

✅ Connection Configuration in .env:
env
Copy
Edit
database.default.hostname = localhost
database.default.database = royalguest
database.default.username = root
database.default.password =
database.default.DBDriver = MySQLi
database.default.port = 3306
⚙️ Environment Configuration (.env)
env
Copy
Edit
CI_ENVIRONMENT = development

app.baseURL = 'http://localhost/royalguest/'
app.indexPage = ''
app.uriProtocol = 'REQUEST_URI'

JWT_SECRET_KEY = eP8NN5r0FCihCu1Qv48JBujcxGNFZ+VEw0DGMVJ6ARY=
JWT_TIME_TO_LIVE = 3600

security.tokenName = 'csrf_token_name'
security.headerName = 'X-CSRF-TOKEN'
security.cookieName = 'csrf_cookie_name'
security.expires = 7200
security.regenerate = true
security.redirect = true
security.samesite = 'Lax'
📡 API Details
Base URL:
http://localhost/royalguest/ or http://royalguest.local/

Routes File:
D:/DEVELOP/RoyalGuest/RoyalGuest/app/Config/Routes.php

Examples of API Endpoints:

GET /api/requests – Get all service requests

POST /api/login – Authenticate user

POST /api/requests/create – Create new request

PUT /api/requests/update/{id} – Update existing request

DELETE /api/requests/delete/{id} – Delete request

🔐 JWT Authentication
✅ Generate a New JWT Secret Key
bash
Copy
Edit
php generate_key.php
This will generate a new secure JWT_SECRET_KEY to be used in .env.

💻 Helpful Commands
Command	Description
composer create-project ...	Install CI4 project
php spark serve	Run the local server
php generate_key.php	Generate new JWT key
php spark migrate	Run database migrations
php spark routes	View all registered routes
php spark make:model ModelName	Create a new model
php spark make:controller MyCtrl	Create a new controller

🛠️ Development Tools Used
PHP: v8.x

CodeIgniter: v4.x

MariaDB: v10.x (via SQLyog)

Composer: Dependency management

JWT: Authentication

Bootstrap 5: UI design

XAMPP: Apache + MariaDB stack

📎 Quick Links
Web App: http://localhost/royalguest

Virtual Host: http://royalguest.local

📌 Notes
Ensure Apache mod_rewrite is enabled for .htaccess to work.

Always restart Apache after editing virtual host or hosts file.

Clear browser cache or use incognito to test virtual host changes.