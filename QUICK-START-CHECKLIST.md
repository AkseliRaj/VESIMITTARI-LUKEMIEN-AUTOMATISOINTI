# Quick Start Checklist - Water Meter Readings Plugin

## Installation & Setup

### 1. Plugin Installation
- [ ] Upload plugin files to `/wp-content/plugins/water-meter-readings/`
- [ ] Activate the plugin in WordPress Admin → Plugins
- [ ] Verify database tables are created automatically

### 2. Admin Configuration

#### Step 1: Add Condominiums
- [ ] Go to WordPress Admin → Water Meters → Condominiums
- [ ] Add your first condominium:
  - **Number**: Enter unique identifier (e.g., "A001", "B002")
  - **Name**: Enter display name (e.g., "Haarantie 2")
  - **Address**: Enter physical address (optional)
- [ ] Add additional condominiums as needed

#### Step 2: Add Addresses
- [ ] Go to WordPress Admin → Water Meters → Addresses
- [ ] Select a condominium from the dropdown
- [ ] Add addresses for that condominium:
  - **Address**: Enter address text (e.g., "Haarantie 2 A1", "Haarantie 2 A2", "Haarantie 2 B1")
- [ ] Repeat for all condominiums

### 3. Public Form Setup
- [ ] Create a new page or post
- [ ] Add the shortcode: `[water_meter_form]`
- [ ] Publish the page
- [ ] Test the form flow:
  - Enter condominium number
  - Verify address dropdown appears
  - Submit a test reading

### 4. Testing the System
- [ ] Submit a test reading through the public form
- [ ] Verify data appears in admin dashboard
- [ ] Check charts and tables display correctly
- [ ] Test responsive design on mobile devices

## Form Flow for Users

### Step 1: Condominium Number
1. User enters condominium number (e.g., "A001")
2. Clicks "Jatka" (Continue)
3. System validates condominium and loads addresses

### Step 2: Reading Details
1. User selects address from dropdown
2. Enters reading date (defaults to today)
3. Enters hot water reading
4. Enters cold water reading
5. Adds optional notes
6. Clicks "Lähetä lukemat" (Submit readings)

## Admin Features

### Dashboard
- [ ] View water consumption charts
- [ ] See readings table with address information
- [ ] Track consumption over time

### Condominium Management
- [ ] Add new condominiums
- [ ] Edit existing condominiums
- [ ] Delete condominiums (with confirmation)

### Address Management
- [ ] Add addresses to condominiums
- [ ] Edit address names
- [ ] Delete addresses (with confirmation)

## Troubleshooting

### Common Issues
- [ ] **Form not working**: Check if jQuery is loaded
- [ ] **No addresses showing**: Verify addresses are added in admin
- [ ] **Charts not displaying**: Check if data exists for selected condominium
- [ ] **Database errors**: Verify WordPress database permissions

### Testing Checklist
- [ ] Test with different condominium numbers
- [ ] Test with various address selections
- [ ] Test form validation (empty fields, invalid numbers)
- [ ] Test responsive design on different screen sizes
- [ ] Verify data integrity in admin dashboard

## Security Notes
- [ ] All form submissions use WordPress nonces
- [ ] Input data is properly sanitized
- [ ] Database queries use prepared statements
- [ ] Admin functions check user capabilities

## Performance Tips
- [ ] Limit number of addresses per condominium for better performance
- [ ] Regular database maintenance for large datasets
- [ ] Consider caching for frequently accessed data

---

**Need Help?**
- Check the main README.md file
- Review WordPress error logs
- Test with default WordPress theme
- Disable other plugins to check for conflicts
