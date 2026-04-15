@echo off
TITLE ProtJund Dev Server
echo ==============================================
echo Iniciando Servidor Laravel (Porta 8000)...
echo ==============================================

cd /d "C:\Users\ANDR~1\.gemini\antigravity\scratch\prot-jundiai"
C:\Users\ANDR~1\.gemini\antigravity\scratch\php-bin\php.exe -S 127.0.0.1:8000 -t public C:\Users\ANDR~1\.gemini\antigravity\scratch\prot-jundiai\server.php
pause
