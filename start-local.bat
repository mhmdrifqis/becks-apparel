@echo off
title Becks Apparel - Local Development Launcher
color 0A

echo =========================================================
echo    BECKS APPAREL - LOCAL DEVELOPMENT STARTER
echo =========================================================
echo.

:: 1. Cek apakah port 8000 (NLP Server) sudah aktif
netstat -ano | findstr :8000 >nul 2>&1
if %ERRORLEVEL% equ 0 (
    echo [OK] Server NLP Chatbot sudah aktif di port 8000.
) else (
    echo [*] Menyalakan Server NLP Chatbot di background (Port 8000)...
    start /min "Becks NLP Server" python server_nlp.py
    timeout /t 2 /nobreak >nul
    echo [OK] Server NLP Chatbot berhasil dinyalakan!
)

echo.
echo =========================================================
echo  1. Web App : http://becks-apparel-deploy.test
echo  2. Chatbot : http://127.0.0.1:8000/chatbot
echo =========================================================
echo.
echo Membuka website di browser...
start http://becks-apparel-deploy.test

echo.
echo Tekan tombol apa saja untuk menutup jendela launcher ini...
pause >nul
