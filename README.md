# Water Meter Readings WordPress Plugin

A comprehensive WordPress plugin for managing water meter readings for condominiums. This plugin provides a simple form for residents to submit their water meter readings and an admin interface for managing condominiums and viewing consumption data with charts and graphs.

## Features

- **Two-Step Public Form**: User-friendly form with condominium number validation and address selection
- **Address Management**: Admin interface to manage addresses within each condominium
- **Condominium Management**: Admin interface to add and manage condominiums
- **Data Visualization**: Interactive charts showing water consumption over time
- **Date Tracking**: Each reading includes a specific date for better tracking
- **Responsive Design**: Works on desktop and mobile devices
- **Finnish Language Support**: Interface in Finnish

## Installation

1. **Upload the Plugin**:
   - Copy all plugin files to `/wp-content/plugins/water-meter-readings/` directory
   - Or zip the plugin folder and upload via WordPress admin

2. **Activate the Plugin**:
   - Go to WordPress Admin → Plugins
   - Find "Water Meter Readings" and click "Activate"

3. **Database Setup**:
   - The plugin will automatically create the necessary database tables on activation

## Usage

### For Residents (Public Form)

1. **Add the Form to a Page**:
   - Create a new page or post
   - Add the shortcode: `[water_meter_form]`
   - Publish the page

2. **Submit Readings (Two-Step Process)**:
   - **Step 1**: Enter condominium number and click "Jatka"
   - **Step 2**: Select address from dropdown, enter date, and input water readings
   - Add optional notes
   - Submit the form

### For Administrators

1. **Access Admin Interface**:
   - Go to WordPress Admin → Water Meters

2. **Manage Condominiums**:
   - Go to Water Meters → Condominiums
   - Add new condominiums with unique numbers
   - View and delete existing condominiums

3. **Manage Addresses**:
   - Go to Water Meters → Addresses
   - Select a condominium from the dropdown
   - Add addresses (e.g., "Haarantie 2 A1", "Haarantie 2 A2")
   - View and delete existing addresses

4. **View Data and Charts**:
   - Go to Water Meters → Dashboard
   - Select a condominium from the dropdown
   - View water consumption charts and tables with address information

## Database Structure

The plugin creates three main tables:

### `wp_water_meter_condominiums`
- `id`: Primary key
- `condominium_number`: Unique identifier (e.g., A001, B002)
- `name`: Display name
- `address`: Physical address (optional)
- `created_at`: Timestamp

### `wp_water_meter_addresses`
- `id`: Primary key
- `condominium_id`: Foreign key to condominiums table
- `address_text`: Address text (e.g., "Haarantie 2 A1")
- `created_at`: Timestamp

### `wp_water_meter_readings`
- `id`: Primary key
- `condominium_id`: Foreign key to condominiums table
- `address_id`: Foreign key to addresses table
- `reading_date`: Date of the reading
- `hot_water`: Hot water reading (decimal)
- `cold_water`: Cold water reading (decimal)
- `notes`: Additional notes (text)
- `submitted_at`: Timestamp

## File Structure

```
water-meter-readings/
├── water-meter-readings.php          # Main plugin file
├── templates/
│   ├── form.php                      # Public form template (two-step)
│   ├── admin-dashboard.php           # Admin dashboard template
│   ├── admin-condominiums.php        # Condominiums management template
│   └── admin-addresses.php           # Addresses management template
├── assets/
│   ├── css/
│   │   ├── style.css                 # Public form styles
│   │   └── admin.css                 # Admin interface styles
│   └── js/
│       ├── script.js                 # Public form JavaScript (two-step)
│       └── admin.js                  # Admin interface JavaScript
└── README.md                         # This file
```

## Customization

### Styling
- Modify `assets/css/style.css` for public form styling
- Modify `assets/css/admin.css` for admin interface styling

### Language
- The plugin uses WordPress translation functions
- Create language files in `/languages/` directory for additional languages

### Charts
- Charts are generated using Chart.js
- Modify `assets/js/admin.js` to customize chart appearance and behavior

## Security Features

- **Nonce Verification**: All form submissions are protected with WordPress nonces
- **Input Sanitization**: All user inputs are properly sanitized
- **SQL Prepared Statements**: Database queries use prepared statements
- **Capability Checks**: Admin functions check for proper user capabilities

## Requirements

- WordPress 5.0 or higher
- PHP 7.4 or higher
- MySQL 5.6 or higher
- jQuery (included with WordPress)

## Troubleshooting

### Form Not Working
- Check if jQuery is loaded
- Verify the shortcode is correctly placed
- Check browser console for JavaScript errors

### Charts Not Displaying
- Ensure Chart.js is loading properly
- Check if there's data for the selected condominium
- Verify JavaScript console for errors

### Database Issues
- Check WordPress database permissions
- Verify table creation on plugin activation
- Check for plugin conflicts

## Support

For support and questions:
1. Check this README file
2. Review WordPress error logs
3. Test with default WordPress theme
4. Disable other plugins to check for conflicts

## License

This plugin is licensed under the GPL v2 or later.

## Changelog

### Version 1.1.0
- **Two-Step Form**: Implemented user-friendly two-step form process
- **Address Management**: Added address management system for condominiums
- **Date Tracking**: Added date field for each reading
- **Enhanced Admin Interface**: Added addresses management page
- **Improved UX**: Better form flow with validation and address selection

### Version 1.0.0
- Initial release
- Public form for water meter readings
- Admin interface for condominium management
- Charts and data visualization
- Finnish language support