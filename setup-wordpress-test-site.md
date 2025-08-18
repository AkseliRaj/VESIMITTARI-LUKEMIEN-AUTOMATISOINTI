# WordPress Test Site Setup Guide

This guide will help you set up a local WordPress test site to try out the Water Meter Readings plugin.

## Option 1: Local Development Environment (Recommended)

### Using XAMPP (Windows)

1. **Download and Install XAMPP**:
   - Go to https://www.apachefriends.org/
   - Download XAMPP for Windows
   - Install with default settings

2. **Start XAMPP Services**:
   - Open XAMPP Control Panel
   - Start Apache and MySQL services
   - Both should show green status

3. **Download WordPress**:
   - Go to https://wordpress.org/download/
   - Download the latest version
   - Extract to `C:\xampp\htdocs\water-meter-test`

4. **Create Database**:
   - Open http://localhost/phpmyadmin
   - Create new database: `water_meter_test`
   - Character set: `utf8mb4_unicode_ci`

5. **Install WordPress**:
   - Open http://localhost/water-meter-test
   - Follow installation wizard
   - Database name: `water_meter_test`
   - Username: `root`
   - Password: (leave empty)
   - Database host: `localhost`

### Using Local by Flywheel (Easiest)

1. **Download Local**:
   - Go to https://localwp.com/
   - Download and install Local

2. **Create New Site**:
   - Open Local
   - Click "Create a new site"
   - Choose "Custom" setup
   - Site name: "Water Meter Test"
   - Choose admin username/password
   - Wait for installation to complete

## Option 2: Online WordPress Hosting

### Using WordPress.com (Free)

1. **Create WordPress.com Account**:
   - Go to https://wordpress.com/
   - Sign up for free account
   - Choose a subdomain (e.g., `watermetertest.wordpress.com`)

2. **Note**: WordPress.com has limitations for custom plugins

### Using Shared Hosting

1. **Choose Hosting Provider**:
   - Popular options: Bluehost, SiteGround, HostGator
   - Many offer one-click WordPress installation

2. **Install WordPress**:
   - Use hosting provider's WordPress installer
   - Follow their setup instructions

## Installing the Water Meter Readings Plugin

### Method 1: Manual Upload

1. **Create Plugin Directory**:
   ```
   wp-content/plugins/water-meter-readings/
   ```

2. **Upload Plugin Files**:
   - Copy all plugin files to the directory
   - Ensure proper file structure

3. **Activate Plugin**:
   - Go to WordPress Admin → Plugins
   - Find "Water Meter Readings"
   - Click "Activate"

### Method 2: ZIP Upload

1. **Create ZIP File**:
   - Select all plugin files
   - Create ZIP archive named `water-meter-readings.zip`

2. **Upload via WordPress**:
   - Go to WordPress Admin → Plugins → Add New
   - Click "Upload Plugin"
   - Choose the ZIP file
   - Click "Install Now"
   - Click "Activate Plugin"

## Testing the Plugin

### 1. Add Condominiums

1. **Access Admin**:
   - Go to WordPress Admin → Water Meters → Condominiums

2. **Add Test Condominiums**:
   - Condominium Number: `A001`
   - Name: `Test Building A`
   - Address: `123 Test Street`

   - Condominium Number: `B002`
   - Name: `Test Building B`
   - Address: `456 Test Avenue`

### 2. Create Public Page

1. **Create New Page**:
   - Go to Pages → Add New
   - Title: "Water Meter Readings"
   - Add shortcode: `[water_meter_form]`
   - Publish the page

### 3. Test Form Submission

1. **Submit Test Data**:
   - Go to your public page
   - Enter condominium number: `A001`
   - Hot water: `100.50`
   - Cold water: `200.75`
   - Notes: `Test reading`
   - Submit form

### 4. View Data and Charts

1. **Check Dashboard**:
   - Go to WordPress Admin → Water Meters
   - Select condominium from dropdown
   - View charts and data table

## Sample Test Data

### Condominiums to Add:
```
Number: A001
Name: Asunto Oy Testitalo A
Address: Mannerheimintie 1, Helsinki

Number: B002  
Name: Asunto Oy Testitalo B
Address: Aleksanterinkatu 5, Helsinki

Number: C003
Name: Asunto Oy Testitalo C
Address: Esplanadi 10, Helsinki
```

### Sample Readings:
```
A001:
- Hot: 100.50, Cold: 200.75 (Date: Today)
- Hot: 98.25, Cold: 198.50 (Date: Yesterday)
- Hot: 95.00, Cold: 195.25 (Date: 2 days ago)

B002:
- Hot: 150.00, Cold: 300.00 (Date: Today)
- Hot: 148.75, Cold: 298.50 (Date: Yesterday)

C003:
- Hot: 75.25, Cold: 150.50 (Date: Today)
```

## Troubleshooting

### Common Issues:

1. **Plugin Not Appearing**:
   - Check file permissions
   - Verify plugin directory structure
   - Check WordPress error logs

2. **Database Tables Not Created**:
   - Deactivate and reactivate plugin
   - Check database permissions
   - Verify MySQL is running

3. **Form Not Working**:
   - Check browser console for JavaScript errors
   - Verify jQuery is loaded
   - Check AJAX URL in browser network tab

4. **Charts Not Displaying**:
   - Check if Chart.js is loading
   - Verify data exists for selected condominium
   - Check browser console for errors

### Debug Mode:

Add to `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

## Next Steps

1. **Test All Features**:
   - Add multiple condominiums
   - Submit various readings
   - Test charts and data visualization
   - Try different scenarios

2. **Customize**:
   - Modify CSS for styling
   - Add more fields if needed
   - Customize chart colors and options

3. **Production Ready**:
   - Test thoroughly
   - Backup database
   - Deploy to production server

## Quick Start Commands (Local Development)

If you're using command line:

```bash
# Download WordPress
wget https://wordpress.org/latest.zip
unzip latest.zip
mv wordpress water-meter-test

# Set up database (if using MySQL command line)
mysql -u root -p
CREATE DATABASE water_meter_test CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit

# Copy plugin files
cp -r water-meter-readings/ water-meter-test/wp-content/plugins/
```

This setup will give you a fully functional WordPress test site to try out all the features of the Water Meter Readings plugin!
