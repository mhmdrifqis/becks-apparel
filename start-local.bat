@echo off
setlocal
title Becks Apparel - Local Development Launcher
color 0A

:: Pindah ke folder project
cd /d "%~dp0"

echo =========================================================
echo    BECKS APPAREL - LOCAL DEVELOPMENT STARTER
echo =========================================================
echo.

:: Cari path Python yang valid
set "PYTHON_EXE="
if exist "C:\Users\alfia\AppData\Local\Programs\Python\Python313\python.exe" (
    set "PYTHON_EXE=C:\Users\alfia\AppData\Local\Programs\Python\Python313\python.exe"
)
if not defined PYTHON_EXE if exist "D:\laragon\bin\python\python-3.10\python.exe" (
    set "PYTHON_EXE=D:\laragon\bin\python\python-3.10\python.exe"
)
if not defined PYTHON_EXE (
    where python >nul 2>&1
    if %ERRORLEVEL% equ 0 set "PYTHON_EXE=python"
)

if not defined PYTHON_EXE (
    echo [ERROR] Python tidak ditemukan di komputer!
    echo Silakan install Python atau pastikan path Python terdaftar.
    pause
    exit /b 1
)

:: Cek apakah port 8000 sudah ada yang mendengarkan
netstat -ano | findstr 127.0.0.1:8000 >nul 2>&1
if %ERRORLEVEL% equ 0 (
    echo [OK] Server NLP Chatbot sudah AKTIF di port 8000.
    goto launch_web
)

echo [*] Menyalakan Server NLP Chatbot server_nlp.py di port 8000...
start "Becks NLP Chatbot Server" "%PYTHON_EXE%" server_nlp.py

:: Beri jeda 2 detik agar server siap
ping -n 3 127.0.0.1 >nul

netstat -ano | findstr 127.0.0.1:8000 >nul 2>&1
if %ERRORLEVEL% equ 0 (
    echo [OK] Server NLP Chatbot BERHASIL AKTIF di http://127.0.0.1:8000/chatbot
) else (
    echo [INFO] Server NLP sedang booting di jendela terpisah.
)

:launch_web
echo.
echo =========================================================
echo  - Web App : http://becks-apparel-deploy.test
echo  - Chatbot : http://127.0.0.1:8000/chatbot
echo =========================================================
echo.
echo [*] Membuka website di browser...
start http://becks-apparel-deploy.test

echo.
echo Jendela server NLP jangan ditutup agar chatbot tetap bisa membalas.
ping -n 4 127.0.0.1 >nul
exit /b 0
