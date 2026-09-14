@echo off
echo === Restoring Database sistem_penjualan ===
echo.

echo Step 1: Drop and recreate database...
C:\xampp\mysql\bin\mysql.exe -u root -e "DROP DATABASE IF EXISTS sistem_penjualan; CREATE DATABASE sistem_penjualan CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

echo Step 2: Importing backup SQL...
C:\xampp\mysql\bin\mysql.exe -u root sistem_penjualan < "c:\xampp\htdocs\Sistem_Penjualan\database\sistem_penjualan.sql"

if %ERRORLEVEL% == 0 (
    echo.
    echo SUCCESS! Database restored from backup.
) else (
    echo.
    echo ERROR! Restore failed. Check the output above.
)
pause
