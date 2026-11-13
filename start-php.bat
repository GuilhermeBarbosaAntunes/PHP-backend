@echo off
setlocal
cd /d "%~dp0"

set "PHP=C:\Users\user\Documents\laragon\bin\php\php-8.3.36-Win32-vs16-x64\php.exe"
if not exist "%PHP%" (
    echo PHP nao foi encontrado em "%PHP%".
    pause
    exit /b 1
)

set "DB_HOST=127.0.0.1"
set "DB_PORT=3306"
set "DB_NAME=estudo_php"
set "DB_USER=root"
set "DB_PASSWORD=162534"

"%PHP%" -S 127.0.0.1:8000
pause
