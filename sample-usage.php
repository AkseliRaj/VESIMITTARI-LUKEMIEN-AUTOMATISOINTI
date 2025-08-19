<?php
/**
 * Sample usage of the Water Meter Readings plugin
 * 
 * This file demonstrates how to use the water meter readings form
 * in your WordPress pages or posts.
 */

// Include WordPress
require_once('wp-config.php');

// Example 1: Basic usage in a page or post
echo '<h3>Example 1: Basic Form Usage</h3>';
echo '<p>Add this shortcode to any page or post:</p>';
echo '<code>[water_meter_form]</code>';

echo '<h3>Example 2: Form with Custom Styling</h3>';
echo '<p>You can also include the form directly in your theme:</p>';
echo '<pre><code>';
echo '&lt;?php
// Include the form in your theme
if (function_exists(\'do_shortcode\')) {
    echo do_shortcode(\'[water_meter_form]\');
}
?&gt;';
echo '</code></pre>';

echo '<h3>Example 3: Complete Page Example</h3>';
echo '<p>Here\'s how the form looks when used:</p>';

// Display the actual form
if (function_exists('do_shortcode')) {
    echo do_shortcode('[water_meter_form]');
}

echo '<h3>Admin Setup Instructions</h3>';
echo '<ol>';
echo '<li>Go to WordPress Admin → Water Meters → Condominiums</li>';
echo '<li>Add your condominiums with their numbers</li>';
echo '<li>Go to WordPress Admin → Water Meters → Addresses</li>';
echo '<li>Select a condominium and add addresses (e.g., "Haarantie 2 A1", "Haarantie 2 A2")</li>';
echo '<li>Users can now submit readings using the two-step form</li>';
echo '</ol>';

echo '<h3>Form Flow</h3>';
echo '<ol>';
echo '<li><strong>Step 1:</strong> User enters condominium number and clicks "Jatka"</li>';
echo '<li><strong>Step 2:</strong> User selects address from dropdown, enters date, and water readings</li>';
echo '<li><strong>Submit:</strong> Form submits the complete reading data</li>';
echo '</ol>';
?>
