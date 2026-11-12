@echo off
rem Sobe o Desenrola em http://localhost:8000.
rem Liga as extensoes do PHP so durante esta execucao, sem alterar a instalacao.
rem Uso: rodar-local.bat
rem Sem abrir o navegador: rodar-local.bat --sem-navegador

setlocal EnableDelayedExpansion
chcp 65001 >nul
cd /d "%~dp0"

set "PORTA=8000"
set "EXTENSOES=pdo_pgsql pgsql curl mbstring openssl"

echo.
echo === Desenrola: ambiente local ===
echo.

rem 1. Escolhe o PHP mais novo instalado
rem XAMPP e outros pacotes costumam deixar um PHP antigo na frente no PATH.
set "PHP_BIN="
set "PHP_ID=0"
set "PHP_VER="
set "PHP_TMP=%TEMP%\desenrola_php.txt"
set "PF86=%ProgramFiles(x86)%"

for /f "delims=" %%p in ('where php 2^>nul') do call :avaliar_php "%%p"
for /d %%d in ("%LOCALAPPDATA%\Microsoft\WinGet\Packages\PHP.PHP*") do (
  if exist "%%~d\php.exe" call :avaliar_php "%%~d\php.exe"
)
for %%p in ("C:\php\php.exe" "%ProgramFiles%\PHP\php.exe" "%PF86%\PHP\php.exe") do (
  if exist "%%~p" call :avaliar_php "%%~p"
)

if not defined PHP_BIN goto :sem_php

"%PHP_BIN%" -r "exit(PHP_VERSION_ID >= 80400 ? 0 : 1);"
if errorlevel 1 goto :php_antigo

echo [OK] PHP %PHP_VER%
echo      %PHP_BIN%

rem 2. Pasta real do PHP, para achar as extensoes
"%PHP_BIN%" -r "echo dirname(PHP_BINARY);" > "%PHP_TMP%"
set /p PHP_DIR= < "%PHP_TMP%"
del "%PHP_TMP%" >nul 2>&1

set "ARGS="
if exist "%PHP_DIR%\ext\" set ARGS=-d "extension_dir=%PHP_DIR%\ext"

rem 3. Liga apenas as extensoes que ainda estao desligadas
for %%e in (%EXTENSOES%) do (
  "%PHP_BIN%" -r "exit(extension_loaded('%%e') ? 0 : 1);"
  if errorlevel 1 set ARGS=!ARGS! -d extension=%%e
)

rem 4. Confere se todas carregaram
set "FALTANDO="
for %%e in (%EXTENSOES%) do (
  "%PHP_BIN%" !ARGS! -r "exit(extension_loaded('%%e') ? 0 : 1);" 2>nul
  if errorlevel 1 set "FALTANDO=!FALTANDO! %%e"
)
if defined FALTANDO goto :sem_extensao
echo [OK] Extensoes: %EXTENSOES%

rem 5. Certificados para HTTPS, usados pelo Gemini
if exist "cacert.pem" (
  set ARGS=!ARGS! -d "curl.cainfo=%CD%\cacert.pem" -d "openssl.cafile=%CD%\cacert.pem"
  echo [OK] cacert.pem encontrado
) else (
  echo [AVISO] Sem cacert.pem: o Gemini pode falhar neste computador. O banco nao e afetado.
  echo         Baixe https://curl.se/ca/cacert.pem e salve nesta pasta.
)

rem 6. Configuracao do banco
if exist ".env" (
  echo [OK] .env encontrado
) else (
  echo [AVISO] Sem .env: o site nao conecta ao Supabase.
  echo         Copie .env.example para .env e preencha os dados.
)

rem 7. Porta livre
netstat -ano | findstr /r /c:":%PORTA% .*LISTENING" >nul
if not errorlevel 1 goto :porta_ocupada

rem 8. Sobe o site
echo.
echo Site em http://localhost:%PORTA%
echo Para parar, pressione Ctrl+C.
echo.
if /i not "%~1"=="--sem-navegador" start "" "http://localhost:%PORTA%"
"%PHP_BIN%" %ARGS% -S localhost:%PORTA% -t public public/index.php
goto :fim

:avaliar_php
"%~1" -r "echo PHP_VERSION_ID, ' ', PHP_VERSION;" > "%PHP_TMP%" 2>nul
if errorlevel 1 goto :eof
set "CANDIDATO="
set /p CANDIDATO= < "%PHP_TMP%"
for /f "tokens=1,*" %%a in ("!CANDIDATO!") do (
  if %%a GTR !PHP_ID! (
    set "PHP_ID=%%a"
    set "PHP_VER=%%b"
    set "PHP_BIN=%~1"
  )
)
goto :eof

:sem_php
echo [ERRO] PHP nao encontrado.
echo.
echo Instale com o comando abaixo, feche este terminal e rode de novo:
echo     winget install --id PHP.PHP.8.5 -e
goto :erro

:php_antigo
echo [ERRO] O projeto precisa do PHP 8.4 ou mais novo.
echo.
echo Atualize com o comando abaixo e rode de novo:
echo     winget install --id PHP.PHP.8.5 -e
goto :erro

:sem_extensao
echo [ERRO] Nao foi possivel ligar:%FALTANDO%
echo.
echo Confira se esses arquivos existem na pasta ext do PHP.
echo Pasta do PHP: %PHP_DIR%
goto :erro

:porta_ocupada
echo [ERRO] A porta %PORTA% ja esta em uso.
echo.
echo Feche o outro servidor e rode de novo.
goto :erro

:erro
echo.
if /i not "%~1"=="--sem-navegador" pause
endlocal
exit /b 1

:fim
endlocal
exit /b 0
