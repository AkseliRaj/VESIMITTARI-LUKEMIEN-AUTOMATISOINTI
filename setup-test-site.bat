@echo off
echo ========================================
echo WordPress Water Meter Test Site Setup
echo ========================================
echo.

echo This script will help you set up a WordPress test site
echo for the Water Meter Readings plugin.
echo.

echo Prerequisites:
echo 1. XAMPP installed and running
echo 2. WordPress downloaded
echo 3. Plugin files ready
echo.

pause

echo.
echo Step 1: Checking XAMPP...
if not exist "C:\xampp\htdocs" (
    echo ERROR: XAMPP not found in C:\xampp
    echo Please install XAMPP first: https://www.apachefriends.org/
    pause
    exit /b 1
)

echo XAMPP found! Please make sure Apache and MySQL are running.
echo.

echo Step 2: Creating test site directory...
if not exist "C:\xampp\htdocs\water-meter-test" (
    mkdir "C:\xampp\htdocs\water-meter-test"
    echo Created directory: C:\xampp\htdocs\water-meter-test
) else (
    echo Directory already exists: C:\xampp\htdocs\water-meter-test
)

echo.
echo Step 3: WordPress Installation
echo.
echo Please follow these steps:
echo 1. Download WordPress from https://wordpress.org/download/
echo 2. Extract WordPress files to C:\xampp\htdocs\water-meter-test\
echo 3. Open http://localhost/phpmyadmin
echo 4. Create database: water_meter_test
echo 5. Open http://localhost/water-meter-test
echo 6. Complete WordPress installation
echo.

pause

echo.
echo Step 4: Plugin Installation
echo.
echo Please follow these steps:
echo 1. Copy all plugin files to: C:\xampp\htdocs\water-meter-test\wp-content\plugins\water-meter-readings\
echo 2. Go to WordPress Admin -^> Plugins
echo 3. Activate "Water Meter Readings" plugin
echo.

pause

echo.
echo Step 5: Testing Setup
echo.
echo Once WordPress and plugin are installed:
echo 1. Go to WordPress Admin -^> Water Meters -^> Condominiums
echo 2. Add a test condominium (e.g., A001, Test Building)
echo 3. Create a page with shortcode: [water_meter_form]
echo 4. Test the form submission
echo 5. View data in the admin dashboard
echo.

echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo Your test site should be available at:
echo http://localhost/water-meter-test
echo.
echo WordPress Admin:
echo http://localhost/water-meter-test/wp-admin
echo.

pause
