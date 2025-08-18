# 🚀 Quick Start Checklist - WordPress Test Site

## ✅ Prerequisites
- [ ] XAMPP installed and running (Apache + MySQL)
- [ ] WordPress downloaded
- [ ] Plugin files ready

## 🛠️ Setup Steps

### 1. WordPress Installation
- [ ] Extract WordPress to `C:\xampp\htdocs\water-meter-test\`
- [ ] Open http://localhost/phpmyadmin
- [ ] Create database: `water_meter_test`
- [ ] Open http://localhost/water-meter-test
- [ ] Complete WordPress installation
- [ ] Note down admin username/password

### 2. Plugin Installation
- [ ] Copy plugin files to `wp-content/plugins/water-meter-readings/`
- [ ] Go to WordPress Admin → Plugins
- [ ] Activate "Water Meter Readings" plugin
- [ ] Verify plugin appears in admin menu

### 3. Add Test Data (Optional)
- [ ] Open phpMyAdmin
- [ ] Select `water_meter_test` database
- [ ] Import `test-data.sql` file
- [ ] Or manually add condominiums through admin

### 4. Create Public Page
- [ ] Go to Pages → Add New
- [ ] Title: "Water Meter Readings"
- [ ] Add shortcode: `[water_meter_form]`
- [ ] Publish page

## 🧪 Testing Checklist

### Admin Features
- [ ] Access Water Meters → Condominiums
- [ ] Add new condominium (A001, Test Building)
- [ ] Access Water Meters → Dashboard
- [ ] Select condominium from dropdown
- [ ] View charts and data table

### Public Features
- [ ] Visit public page with form
- [ ] Submit test reading (A001, 100.50, 200.75)
- [ ] Verify success message
- [ ] Check data appears in admin dashboard

### Charts and Data
- [ ] View water levels chart
- [ ] View consumption chart
- [ ] Check consumption differences in table
- [ ] Test responsive design on mobile

## 🔧 Troubleshooting

### Common Issues
- [ ] Plugin not appearing → Check file permissions
- [ ] Database tables missing → Deactivate/reactivate plugin
- [ ] Form not working → Check browser console
- [ ] Charts not showing → Verify Chart.js loading

### Debug Mode
Add to `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

## 📊 Sample Test Data

### Condominiums
```
A001 - Asunto Oy Testitalo A
B002 - Asunto Oy Testitalo B  
C003 - Asunto Oy Testitalo C
D004 - Asunto Oy Testitalo D
```

### Test Readings
```
A001: Hot 100.50, Cold 200.75
B002: Hot 150.00, Cold 300.00
C003: Hot 75.25, Cold 150.50
```

## 🎯 Success Criteria
- [ ] WordPress site loads at http://localhost/water-meter-test
- [ ] Plugin appears in admin menu
- [ ] Can add condominiums
- [ ] Public form works
- [ ] Charts display data
- [ ] Responsive design works

## 📞 Next Steps
- [ ] Test all features thoroughly
- [ ] Customize styling if needed
- [ ] Add more test data
- [ ] Consider production deployment

---

**Need Help?** Check the detailed setup guide in `setup-wordpress-test-site.md`
