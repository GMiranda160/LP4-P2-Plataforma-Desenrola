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

rem 1. PHP instalado
where php >nul 2>&1
if errorlevel 1 goto :sem_php

for /f "delims=" %%v in ('php -r "echo PHP_VERSION;"') do set "VERSAO=%%v"
echo [OK] PHP %VERSAO%

php -r "exit(PHP_VERSION_ID >= 80400 ? 0 : 1);"
if errorlevel 1 goto :php_antigo

rem 2. Pasta real do PHP, para achar as extensoes
for /f "delims=" %%d in ('php -r "echo dirname(PHP_BINARY);"') do set "PHP_DIR=%%d"

set "ARGS="
if exist "%PHP_DIR%\ext\" set ARGS=-d "extension_dir=%PHP_DIR%\ext"

rem 3. Liga apenas as extensoes que ainda estao desligadas
for %%e in (%EXTENSOES%) do (
  php -r "exit(extension_loaded('%%e') ? 0 : 1);"
  if errorlevel 1 set ARGS=!ARGS! -d extension=%%e
)

rem 4. Confere se todas carregaram
set "FALTANDO="
for %%e in (%EXTENSOES%) do (
  php !ARGS! -r "exit(extension_loaded('%%e') ? 0 : 1);" 2>nul
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
php %ARGS% -S localhost:%PORTA% -t public public/index.php
goto :fim

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
