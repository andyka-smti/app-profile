@echo off
setlocal enabledelayedexpansion
cd /d "%~dp0"
title Jalankan Aplikasi Portal Resmi Mobile (Flutter)

:: Konfigurasi PATH untuk PHP Laragon dan Flutter SDK
set "PATH=C:\laragon\bin\php\php-8.4.27-1-Win32-vs17-x64;C:\laragon\bin\composer;C:\Diklat\Bootcamp Vibe Coding\Develop\flutter\bin;C:\src\flutter\bin;%PATH%"
set "ROOT_DIR=%~dp0"
set "FRONTEND_DIR=%~dp0flutter_app"

:: Pastikan bekerja di folder frontend jika ada
if not exist "%FRONTEND_DIR%\pubspec.yaml" (
    echo [ERROR] Folder frontend tidak ditemukan di %FRONTEND_DIR%
    pause
    exit /b 1
)

:: Pastikan server backend Laravel berjalan di port 8000
netstat -ano | findstr LISTENING | findstr :8000 >nul 2>&1
if not errorlevel 1 goto backend_ok

echo [INFO] Menyalakan Backend Laravel API di background port 8000...
start "Laravel Backend API" /min cmd /c "cd /d "%ROOT_DIR%" && php artisan serve --host=127.0.0.1 --port=8000"
timeout /t 3 /nobreak >nul

:backend_ok

:start
cls
echo ============================================================
echo    JALANKAN APLIKASI PORTAL RESMI MOBILE (FLUTTER)
echo ============================================================
echo.
echo  [STATUS] Backend Laravel API : AKTIF (http://127.0.0.1:8000)
echo.
echo Pilih target perangkat untuk menjalankan aplikasi:
echo  [1] Jalankan di Browser Google Chrome (Frame iPhone ^& Android Mockup)
echo  [2] Jalankan sebagai Aplikasi Windows Desktop Native
echo  [3] Jalankan di Android (HP Fisik USB / Emulator Pixel 7)
echo  [4] Hubungkan dan Jalankan via Wi-Fi (Wireless Debugging)
echo  [5] Build File Installer APK Android (.apk)
echo  [0] Keluar
echo.
set "pilihan="
set /p "pilihan=Masukkan pilihan Anda (1-5, 0): "

if not defined pilihan goto start
set "pilihan=%pilihan: =%"
if "%pilihan%"=="1" goto run_chrome
if "%pilihan%"=="2" goto run_desktop
if "%pilihan%"=="3" goto run_usb
if "%pilihan%"=="4" goto wifi_connect
if "%pilihan%"=="5" goto build_apk
if "%pilihan%"=="0" goto exit

echo.
echo Pilihan tidak valid! Silakan masukkan angka yang tersedia.
echo.
timeout /t 2 >nul
goto start

:run_chrome
echo.
echo ============================================================
echo  Menjalankan Mobile Portal di Chrome dengan Frame Interaktif...
echo ============================================================
echo  [FITUR FRAME MOCKUP]:
echo  - Layar akan langsung menampilkan bingkai HP asli (Frame iPhone / Android)
echo  - Anda dapat mengubah tipe perangkat kapan saja lewat toolbar:
echo    * iPhone 13, iPhone 14 Pro, iPhone SE
echo    * Google Pixel 7, Samsung Galaxy, iPad Tablet
echo    * Putar rotasi layar (Portrait / Landscape)
echo    * Ganti mode tema (Light / Dark Mode)
echo ============================================================
echo.
cd /d "%FRONTEND_DIR%"
call flutter run -d chrome
goto end

:run_desktop
echo.
echo Menjalankan aplikasi Portal Resmi sebagai Windows Desktop...
cd /d "%FRONTEND_DIR%"
call flutter run -d windows
goto end

:run_usb
echo.
echo ============================================================
echo     MENJALANKAN DI PERANGKAT ANDROID (USB / EMULATOR)
echo ============================================================
echo.
set "ADB_EXE=adb.exe"
where adb >nul 2>&1
if %errorlevel% neq 0 (
  set "ADB_EXE=%LOCALAPPDATA%\Android\Sdk\platform-tools\adb.exe"
)

echo Memeriksa koneksi perangkat Android...
"%ADB_EXE%" devices >nul 2>&1
"%ADB_EXE%" devices
echo.

set "DEV_ID="
"%ADB_EXE%" devices > "%TEMP%\portal_adb_devices.txt" 2>nul

for /f "tokens=1,2" %%a in (%TEMP%\portal_adb_devices.txt) do (
  if "%%b"=="device" (
    set "DEV_ID=%%a"
  )
)
if exist "%TEMP%\portal_adb_devices.txt" del "%TEMP%\portal_adb_devices.txt" >nul 2>&1

if not "%DEV_ID%"=="" goto run_with_id

echo Tidak ditemukan HP Android terhubung via USB yang aktif.
echo Mencoba meluncurkan Android Emulator Pixel 7...
call flutter emulators --launch Pixel_7 >nul 2>&1
timeout /t 5 /nobreak >nul
cd /d "%FRONTEND_DIR%"
call flutter run -d Pixel_7
if %errorlevel% neq 0 (
  echo.
  call flutter run
)
goto end

:run_with_id
echo ============================================================
echo Perangkat Android Terdeteksi (ID: %DEV_ID%)
echo Menjalankan aplikasi di HP Android Anda...
echo ============================================================
echo.
cd /d "%FRONTEND_DIR%"
call flutter run -d %DEV_ID%
goto end

:wifi_connect
echo.
echo ============================================================
echo        KONEKSI WIRELESS DEBUGGING (ADB VIA WI-FI)
echo ============================================================
echo.
set "ADB_CMD=adb"
where adb >nul 2>&1
if %errorlevel% neq 0 (
  set "ADB_CMD=%LOCALAPPDATA%\Android\Sdk\platform-tools\adb.exe"
)

echo Pastikan HP dan Laptop Anda terhubung ke jaringan Wi-Fi yang sama.
echo Aktifkan "Wireless Debugging" di Opsi Developer HP Anda.
echo.
set "pairing="
set /p "pairing=Apakah Anda perlu melakukan Pairing perangkat baru? (y/t): "
if /i "%pairing%"=="y" goto do_pairing
goto do_connection

:do_pairing
echo.
echo Klik "Pair device with pairing code" pada menu HP Anda.
set "pair_ip="
set /p "pair_ip=Masukkan IP dan Port Pairing (Contoh: 192.168.1.50:43215): "
set "pair_code="
set /p "pair_code=Masukkan Pairing Code (6 angka): "
echo Menghubungkan pairing...
"%ADB_CMD%" pair %pair_ip% %pair_code%
echo.

:do_connection
echo.
echo Masukkan IP dan Port Koneksi utama yang tertera di menu HP Anda.
set "connect_ip="
set /p "connect_ip=Masukkan IP dan Port Koneksi (Contoh: 192.168.1.29:39431): "
echo Menghubungkan ke %connect_ip%...
"%ADB_CMD%" connect %connect_ip%
echo.
echo Memeriksa status koneksi perangkat:
"%ADB_CMD%" devices
echo.
echo Menjalankan aplikasi Portal Resmi via Wi-Fi...
cd /d "%FRONTEND_DIR%"
call flutter run -d %connect_ip%
goto end

:build_apk
echo.
echo ============================================================
echo         MEMULAI PROSES BUILD FILE INSTALLER APK
echo ============================================================
echo.
cd /d "%FRONTEND_DIR%"
call flutter build apk --debug
if exist "%FRONTEND_DIR%\build\app\outputs\flutter-apk\app-debug.apk" (
    echo.
    echo [SUKSES] File APK berhasil dibuat!
    echo Lokasi: %FRONTEND_DIR%\build\app\outputs\flutter-apk\app-debug.apk
    explorer.exe "%FRONTEND_DIR%\build\app\outputs\flutter-apk"
) else (
    echo.
    echo [ERROR] File APK belum berhasil dibuat.
)
goto end

:end
echo.
echo Aplikasi telah berhenti.
pause
goto start

:exit
exit /b 0
