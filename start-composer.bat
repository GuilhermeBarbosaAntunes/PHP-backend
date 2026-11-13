@echo off
set "PHP_DIR=C:\Users\user\Documents\laragon\bin\php\php-8.3.36-Win32-vs16-x64"

if not exist "%PHP_DIR%\php.exe" (
    echo PHP nao foi encontrado em "%PHP_DIR%\php.exe".
    pause
    exit /b 1
)

set "PATH=%PHP_DIR%;%PATH%"
cd /d "%~dp0"

echo PHP disponivel nesta janela:
php -v
echo.
echo Esta janela ja pode usar php e composer.
cmd /k
