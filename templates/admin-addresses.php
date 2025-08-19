<?php
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

global $wpdb;

// Handle form submission
if (isset($_POST['action']) && $_POST['action'] === 'add_address') {
    check_admin_referer('add_address');
    
    $condominium_id = intval($_POST['condominium_id']);
    $address_text = sanitize_text_field($_POST['address_text']);
    
    $result = $wpdb->insert(
        $wpdb->prefix . 'water_meter_addresses',
        array(
            'condominium_id' => $condominium_id,
            'address_text' => $address_text
        ),
        array('%d', '%s')
    );
    
    if ($result) {
        echo '<div class="notice notice-success"><p>' . __('Address added successfully!', 'water-meter-readings') . '</p></div>';
    } else {
        echo '<div class="notice notice-error"><p>' . __('Error adding address.', 'water-meter-readings') . '</p></div>';
    }
}

// Handle deletion
if (isset($_GET['delete']) && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'delete_address')) {
    $address_id = intval($_GET['delete']);
    $wpdb->delete($wpdb->prefix . 'water_meter_addresses', array('id' => $address_id), array('%d'));
    echo '<div class="notice notice-success"><p>' . __('Address deleted successfully!', 'water-meter-readings') . '</p></div>';
}

// Get all condominiums
$condominiums = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}water_meter_condominiums ORDER BY name");

// Get selected condominium
$selected_condominium_id = isset($_GET['condominium_id']) ? intval($_GET['condominium_id']) : 0;

if ($selected_condominium_id) {
    $condominium = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}water_meter_condominiums WHERE id = %d",
        $selected_condominium_id
    ));
    
    // Get addresses for the selected condominium
    $addresses = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}water_meter_addresses WHERE condominium_id = %d ORDER BY address_text",
        $selected_condominium_id
    ));
}
?>

<div class="wrap">
    <h1><?php _e('Manage Addresses', 'water-meter-readings'); ?></h1>
    
    <!-- Condominium Selector -->
    <div class="condominium-selector">
        <label for="condominium-select"><?php _e('Select Condominium:', 'water-meter-readings'); ?></label>
        <select id="condominium-select">
            <option value=""><?php _e('-- Select Condominium --', 'water-meter-readings'); ?></option>
            <?php foreach ($condominiums as $condo): ?>
                <option value="<?php echo $condo->id; ?>" <?php selected($selected_condominium_id, $condo->id); ?>>
                    <?php echo esc_html($condo->name . ' (' . $condo->condominium_number . ')'); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    
    <?php if ($selected_condominium_id && $condominium): ?>
        <div class="condominium-info">
            <h2><?php echo esc_html($condominium->name); ?></h2>
            <p><strong><?php _e('Number:', 'water-meter-readings'); ?></strong> <?php echo esc_html($condominium->condominium_number); ?></p>
            <?php if ($condominium->address): ?>
                <p><strong><?php _e('Address:', 'water-meter-readings'); ?></strong> <?php echo esc_html($condominium->address); ?></p>
            <?php endif; ?>
        </div>
        
        <!-- Add New Address Form -->
        <div class="add-address-section">
            <h3><?php _e('Add New Address', 'water-meter-readings'); ?></h3>
            
            <form method="post" class="add-address-form">
                <?php wp_nonce_field('add_address'); ?>
                <input type="hidden" name="action" value="add_address">
                <input type="hidden" name="condominium_id" value="<?php echo $selected_condominium_id; ?>">
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="address_text"><?php _e('Address', 'water-meter-readings'); ?> *</label>
                        </th>
                        <td>
                            <input type="text" id="address_text" name="address_text" class="regular-text" required>
                            <p class="description"><?php _e('Enter the address (e.g., Haarantie 2 A1, Haarantie 2 A2)', 'water-meter-readings'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Address', 'water-meter-readings'); ?>">
                </p>
            </form>
        </div>
        
        <!-- Existing Addresses Table -->
        <div class="existing-addresses-section">
            <h3><?php _e('Existing Addresses', 'water-meter-readings'); ?></h3>
            
            <?php if ($addresses): ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Address', 'water-meter-readings'); ?></th>
                            <th><?php _e('Created', 'water-meter-readings'); ?></th>
                            <th><?php _e('Actions', 'water-meter-readings'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($addresses as $address): ?>
                            <tr>
                                <td><strong><?php echo esc_html($address->address_text); ?></strong></td>
                                <td><?php echo date('d.m.Y', strtotime($address->created_at)); ?></td>
                                <td>
                                    <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=water-meter-addresses&delete=' . $address->id . '&condominium_id=' . $selected_condominium_id), 'delete_address'); ?>" 
                                       class="button button-small button-link-delete" 
                                       onclick="return confirm('<?php _e('Are you sure you want to delete this address? This will also delete all associated water readings.', 'water-meter-readings'); ?>')">
                                        <?php _e('Delete', 'water-meter-readings'); ?>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p><?php _e('No addresses found for this condominium. Add your first address using the form above.', 'water-meter-readings'); ?></p>
            <?php endif; ?>
        </div>
        
    <?php else: ?>
        <div class="no-condominium-selected">
            <p><?php _e('Please select a condominium to manage its addresses.', 'water-meter-readings'); ?></p>
        </div>
    <?php endif; ?>
</div>

<script>
jQuery(document).ready(function($) {
    $('#condominium-select').on('change', function() {
        var condominiumId = $(this).val();
        if (condominiumId) {
            window.location.href = '<?php echo admin_url('admin.php?page=water-meter-addresses&condominium_id='); ?>' + condominiumId;
        }
    });
});
</script>

