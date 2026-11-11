# Study Project: PHP Backend

A study project for practicing PHP backend development with forms, HTTP requests, PDO, and MySQL. The project also includes object-oriented programming exercises in `src/`.

## Setup Guide

- [Windows setup](#study-environment-setup-windows)
- [Linux setup](#study-environment-setup-linux)

## Current Features

- Create, list, search, complete, and delete tasks.
- Contact form that saves messages to the database.
- Prepared PDO queries.
- Input validation, CSRF protection, and HTML content escaping.
- Automatic creation of the `tarefas` and `mensagens` tables when connecting to the database.

## Requirements

- PHP 8.1 or later.
- PHP extensions `pdo_mysql` and `mbstring`.
- A local MySQL server.

## Run

From the project directory, configure the MySQL connection variables and start the local server:

```powershell
$env:DB_HOST = '127.0.0.1'
$env:DB_PORT = '3306'
$env:DB_NAME = 'estudo_php'
$env:DB_USER = 'root'
$env:DB_PASSWORD = ''
php -S 127.0.0.1:8000
```

Open `http://127.0.0.1:8000` in your browser. Update the username and password to match your MySQL configuration. Variables set in PowerShell are only available in that terminal window.

## Main Structure

```text
index.php          Task manager
contato.php        Contact form and message history
includes/
  config.php       Session and general configuration
  db.php           PDO connection to MySQL and table creation
  funcoes.php      HTML escaping, CSRF, redirects, and flash messages
src/               Object-oriented programming exercises
css/               Stylesheets
js/                JavaScript
```

The current focus is practicing web applications with a database. The exercises in `src/` are separate and are not used by the task and contact pages.

# Study Environment Setup (Windows)

## 1. Install and start the local environment

Install [Laragon Full](https://laragon.org/download), which includes PHP and MySQL. Start the **Apache** and **MySQL** services in Laragon. XAMPP can also be used, provided its PHP installation has the `pdo_mysql` and `mbstring` extensions enabled.

Open the Laragon terminal (or a new PowerShell window) and check:

```powershell
php -v
php -m
```

Make sure the `PDO`, `pdo_mysql`, and `mbstring` modules appear in the `php -m` output. If PowerShell does not recognize `php`, use the Laragon terminal or add PHP to your `PATH`.

## 2. Create the database

In the MySQL client included with your environment (for example, HeidiSQL or phpMyAdmin), run:

```sql
CREATE DATABASE estudo_php
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;
```

The application’s database user must have permission to create and modify tables in this database. The project automatically creates the `tarefas` and `mensagens` tables on the first connection.

## 3. Configure and run the project

In PowerShell, go to the project directory and set your MySQL connection details. The example below uses common Laragon defaults; change the username and password if your environment uses different values:

```powershell
cd "C:\Users\user\Documents\Estudos PHP"
$env:DB_HOST = '127.0.0.1'
$env:DB_PORT = '3306'
$env:DB_NAME = 'estudo_php'
$env:DB_USER = 'root'
$env:DB_PASSWORD = ''
php -S 127.0.0.1:8000
```

Keep this terminal window open and visit <http://127.0.0.1:8000>. The `$env:DB_*` variables are only available in the current PowerShell window. Do not use the `root` account or passwordless credentials on a public server.

## Troubleshooting

- **`php` is not recognized:** open the Laragon terminal or add the PHP directory to your `PATH`.
- **MySQL driver error:** enable `pdo_mysql` in the `php.ini` file used by PHP and restart the terminal.
- **Connection error:** make sure the MySQL service is running and that the host, port, database, username, and password are correct.
- **Database does not exist:** run the `CREATE DATABASE` command from step 2.

# Study Environment Setup (Linux)

The commands below are for Ubuntu and Debian. For other distributions, use the equivalent package manager and service commands.

## 1. Install PHP and MySQL

Install PHP CLI, the required PHP extensions, and MySQL:

```bash
sudo apt update
sudo apt install php-cli php-mysql php-mbstring mysql-server
sudo systemctl enable --now mysql
```

Check the PHP version and confirm the required modules are enabled:

```bash
php -v
php -m
```

Make sure PHP 8.1 or later is installed and that `PDO`, `pdo_mysql`, and `mbstring` appear in the module list.

## 2. Create the database and application user

Open the MySQL client:

```bash
sudo mysql
```

Create the database and a dedicated local user. Replace the example password with a strong password of your choice:

```sql
CREATE DATABASE estudo_php
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

CREATE USER 'estudo_user'@'127.0.0.1' IDENTIFIED BY 'replace_with_a_strong_password';
GRANT ALL PRIVILEGES ON estudo_php.* TO 'estudo_user'@'127.0.0.1';
EXIT;
```

The project creates the `tarefas` and `mensagens` tables automatically on the first connection.

## 3. Configure and run the project

From the project directory, set the MySQL connection variables and start PHP’s built-in server:

```bash
export DB_HOST='127.0.0.1'
export DB_PORT='3306'
export DB_NAME='estudo_php'
export DB_USER='estudo_user'
export DB_PASSWORD='replace_with_a_strong_password'
php -S 127.0.0.1:8000
```

Visit <http://127.0.0.1:8000>. Keep the terminal open while using the application. These environment variables apply only to the current terminal session.

## Troubleshooting

- **`php` is not found:** install `php-cli` or make sure PHP is available in your `PATH`.
- **Missing MySQL driver:** install `php-mysql` and confirm `pdo_mysql` appears in `php -m`.
- **MySQL is not running:** check the service with `sudo systemctl status mysql` and start it with `sudo systemctl start mysql`.
- **Connection error:** verify the MySQL service and the `DB_*` values, including the username and password.
