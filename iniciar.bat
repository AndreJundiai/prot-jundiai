@echo off
TITLE ProtJund App - Servidor de Desenvolvimento
echo ==============================================
echo Iniciando PHP Server e Vite (Tailwind)...
echo ==============================================
cd /d "%~dp0"

:: Inicia o servidor PHP em uma nova janela
start "ProtJund - Backend PHP" cmd /c "C:\Users\ANDR~1\.gemini\antigravity\scratch\php-bin\php.exe -S 127.0.0.1:8000 -t public server.php"

:: Inicia o Vite em uma nova janela
start "ProtJund - Frontend Vite" cmd /c "npm run dev"

echo Aguardando inicializacao dos servidores...
timeout /t 3 >nul

echo Abrindo o navegador...
start http://127.0.0.1:8000

echo.
echo ==============================================
echo Servidores estao rodando em navegadores/terminais separados.
echo Para encerrar, basta fechar as janelas que foram abertas.
echo ==============================================
pause
