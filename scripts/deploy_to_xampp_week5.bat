@echo off
REM Deploy Week5 BankingSystem to XAMPP htdocs and import the DB
SETLOCAL ENABLEDELAYEDEXPANSION
set "SRC=%~dp0..\Week5"
set "XAMPP_DIR=C:\xampp"
set "DEST=%XAMPP_DIR%\htdocs\BankingSystem\Week5"

if not exist "%XAMPP_DIR%" (
  echo XAMPP not found at %XAMPP_DIR%. Please install XAMPP or update the script.
  pause
  exit /b 1
)

echo Creating destination folder...
mkdir "%XAMPP_DIR%\htdocs\BankingSystem" 2>nul

echo Copying files from "%SRC%" to "%DEST%"...
robocopy "%SRC%" "%DEST%" /MIR /NFL /NDL /NJH /NJS /NC /NP

if %errorlevel% GEQ 8 (
  echo Robocopy failed with an error.
) else (
  echo Files copied successfully.
)

echo Importing database Week5 (requires MySQL to be running)...
set "SQLFILE=%~dp0..\Week5\database\Week5db.sql"
set "MYSQL_BIN=%XAMPP_DIR%\mysql\bin\mysql.exe"

if not exist "%MYSQL_BIN%" (
  echo MySQL binary not found at %MYSQL_BIN%. Ensure XAMPP is installed in %XAMPP_DIR%.
  pause
  exit /b 1
)

"%MYSQL_BIN%" -u root < "%SQLFILE%"
if %errorlevel% NEQ 0 (
  echo Database import might have failed. Ensure MySQL is running and credentials are correct.
) else (
  echo Database imported successfully.
)

echo Done. Open http://localhost/BankingSystem/Week5/ in your browser.
pause
exit /b 0
