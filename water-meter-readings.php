<?php
/**
 * Plugin Name: Water Meter Readings
 * Plugin URI: https://github.com/your-username/water-meter-readings
 * Description: A simple water meter readings management system for condominiums
 * Version: 1.0.0
 * Author: Your Name
 * License: GPL v2 or later
 * Text Domain: water-meter-readings
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('WMR_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WMR_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('WMR_VERSION', '1.0.0');

// Main plugin class
class WaterMeterReadings {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        // Load text domain
        load_plugin_textdomain('water-meter-readings', false, dirname(plugin_basename(__FILE__)) . '/languages');
        
        // Add shortcode for the form
        add_shortcode('water_meter_form', array($this, 'render_form'));
        
        // Add AJAX handlers
        add_action('wp_ajax_submit_water_reading', array($this, 'submit_water_reading'));
        add_action('wp_ajax_nopriv_submit_water_reading', array($this, 'submit_water_reading'));
        
        // Add admin menu
        add_action('admin_menu', array($this, 'admin_menu'));
    }
    
    public function activate() {
        $this->create_tables();
    }
    
    public function deactivate() {
        // Cleanup if needed
    }
    
    public function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Condominiums table
        $table_condominiums = $wpdb->prefix . 'water_meter_condominiums';
        $sql_condominiums = "CREATE TABLE $table_condominiums (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            condominium_number varchar(50) NOT NULL,
            name varchar(255) NOT NULL,
            address text,
            created_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY condominium_number (condominium_number)
        ) $charset_collate;";
        
        // Water readings table
        $table_readings = $wpdb->prefix . 'water_meter_readings';
        $sql_readings = "CREATE TABLE $table_readings (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            condominium_id mediumint(9) NOT NULL,
            hot_water decimal(10,2) NOT NULL,
            cold_water decimal(10,2) NOT NULL,
            notes text,
            submitted_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY condominium_id (condominium_id),
            FOREIGN KEY (condominium_id) REFERENCES $table_condominiums(id) ON DELETE CASCADE
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_condominiums);
        dbDelta($sql_readings);
    }
    
    public function enqueue_scripts() {
        wp_enqueue_style('water-meter-readings', WMR_PLUGIN_URL . 'assets/css/style.css', array(), WMR_VERSION);
        wp_enqueue_script('water-meter-readings', WMR_PLUGIN_URL . 'assets/js/script.js', array('jquery'), WMR_VERSION, true);
        wp_localize_script('water-meter-readings', 'wmr_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wmr_nonce')
        ));
    }
    
    public function admin_enqueue_scripts($hook) {
        if (strpos($hook, 'water-meter-readings') !== false) {
            wp_enqueue_style('water-meter-readings-admin', WMR_PLUGIN_URL . 'assets/css/admin.css', array(), WMR_VERSION);
            wp_enqueue_script('water-meter-readings-admin', WMR_PLUGIN_URL . 'assets/js/admin.js', array('jquery'), WMR_VERSION, true);
            wp_enqueue_script('chart-js', 'https://cdn.jsdelivr.net/npm/chart.js', array(), '3.9.1', true);
        }
    }
    
    public function admin_menu() {
        add_menu_page(
            __('Water Meter Readings', 'water-meter-readings'),
            __('Water Meters', 'water-meter-readings'),
            'manage_options',
            'water-meter-readings',
            array($this, 'admin_page'),
            'dashicons-chart-area',
            30
        );
        
        add_submenu_page(
            'water-meter-readings',
            __('Condominiums', 'water-meter-readings'),
            __('Condominiums', 'water-meter-readings'),
            'manage_options',
            'water-meter-condominiums',
            array($this, 'condominiums_page')
        );
    }
    
    public function render_form() {
        ob_start();
        include WMR_PLUGIN_PATH . 'templates/form.php';
        return ob_get_clean();
    }
    
    public function submit_water_reading() {
        check_ajax_referer('wmr_nonce', 'nonce');
        
        $condominium_number = sanitize_text_field($_POST['condominium_number']);
        $hot_water = floatval($_POST['hot_water']);
        $cold_water = floatval($_POST['cold_water']);
        $notes = sanitize_textarea_field($_POST['notes']);
        
        global $wpdb;
        
        // Check if condominium exists
        $condominium = $wpdb->get_row($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}water_meter_condominiums WHERE condominium_number = %s",
            $condominium_number
        ));
        
        if (!$condominium) {
            wp_die(json_encode(array('success' => false, 'message' => 'Condominium not found')));
        }
        
        // Insert reading
        $result = $wpdb->insert(
            $wpdb->prefix . 'water_meter_readings',
            array(
                'condominium_id' => $condominium->id,
                'hot_water' => $hot_water,
                'cold_water' => $cold_water,
                'notes' => $notes
            ),
            array('%d', '%f', '%f', '%s')
        );
        
        if ($result) {
            wp_die(json_encode(array('success' => true, 'message' => 'Reading submitted successfully')));
        } else {
            wp_die(json_encode(array('success' => false, 'message' => 'Error submitting reading')));
        }
    }
    
    public function admin_page() {
        include WMR_PLUGIN_PATH . 'templates/admin-dashboard.php';
    }
    
    public function condominiums_page() {
        include WMR_PLUGIN_PATH . 'templates/admin-condominiums.php';
    }
}

// Initialize the plugin
new WaterMeterReadings();
