<?php
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

global $wpdb;

// Handle form submission
if (isset($_POST['action']) && $_POST['action'] === 'add_condominium') {
    check_admin_referer('add_condominium');
    
    $condominium_number = sanitize_text_field($_POST['condominium_number']);
    $name = sanitize_text_field($_POST['name']);
    $address = sanitize_textarea_field($_POST['address']);
    
    $result = $wpdb->insert(
        $wpdb->prefix . 'water_meter_condominiums',
        array(
            'condominium_number' => $condominium_number,
            'name' => $name,
            'address' => $address
        ),
        array('%s', '%s', '%s')
    );
    
    if ($result) {
        echo '<div class="notice notice-success"><p>' . __('Condominium added successfully!', 'water-meter-readings') . '</p></div>';
    } else {
        echo '<div class="notice notice-error"><p>' . __('Error adding condominium.', 'water-meter-readings') . '</p></div>';
    }
}

// Handle deletion
if (isset($_GET['delete']) && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'delete_condominium')) {
    $condominium_id = intval($_GET['delete']);
    $wpdb->delete($wpdb->prefix . 'water_meter_condominiums', array('id' => $condominium_id), array('%d'));
    echo '<div class="notice notice-success"><p>' . __('Condominium deleted successfully!', 'water-meter-readings') . '</p></div>';
}

// Get all condominiums
$condominiums = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}water_meter_condominiums ORDER BY name");
?>

<div class="wrap">
    <h1><?php _e('Manage Condominiums', 'water-meter-readings'); ?></h1>
    
    <!-- Add New Condominium Form -->
    <div class="add-condominium-section">
        <h2><?php _e('Add New Condominium', 'water-meter-readings'); ?></h2>
        
        <form method="post" class="add-condominium-form">
            <?php wp_nonce_field('add_condominium'); ?>
            <input type="hidden" name="action" value="add_condominium">
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="condominium_number"><?php _e('Condominium Number', 'water-meter-readings'); ?> *</label>
                    </th>
                    <td>
                        <input type="text" id="condominium_number" name="condominium_number" class="regular-text" required>
                        <p class="description"><?php _e('Unique identifier for the condominium (e.g., A001, B002)', 'water-meter-readings'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="name"><?php _e('Name', 'water-meter-readings'); ?> *</label>
                    </th>
                    <td>
                        <input type="text" id="name" name="name" class="regular-text" required>
                        <p class="description"><?php _e('Display name for the condominium', 'water-meter-readings'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="address"><?php _e('Address', 'water-meter-readings'); ?></label>
                    </th>
                    <td>
                        <textarea id="address" name="address" class="large-text" rows="3"></textarea>
                        <p class="description"><?php _e('Physical address of the condominium (optional)', 'water-meter-readings'); ?></p>
                    </td>
                </tr>
            </table>
            
            <p class="submit">
                <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Condominium', 'water-meter-readings'); ?>">
            </p>
        </form>
    </div>
    
    <!-- Existing Condominiums Table -->
    <div class="existing-condominiums-section">
        <h2><?php _e('Existing Condominiums', 'water-meter-readings'); ?></h2>
        
        <?php if ($condominiums): ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php _e('Number', 'water-meter-readings'); ?></th>
                        <th><?php _e('Name', 'water-meter-readings'); ?></th>
                        <th><?php _e('Address', 'water-meter-readings'); ?></th>
                        <th><?php _e('Created', 'water-meter-readings'); ?></th>
                        <th><?php _e('Actions', 'water-meter-readings'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($condominiums as $condominium): ?>
                        <tr>
                            <td><strong><?php echo esc_html($condominium->condominium_number); ?></strong></td>
                            <td><?php echo esc_html($condominium->name); ?></td>
                            <td><?php echo esc_html($condominium->address); ?></td>
                            <td><?php echo date('d.m.Y', strtotime($condominium->created_at)); ?></td>
                            <td>
                                <a href="<?php echo admin_url('admin.php?page=water-meter-readings&condominium_id=' . $condominium->id); ?>" class="button button-small">
                                    <?php _e('View Data', 'water-meter-readings'); ?>
                                </a>
                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=water-meter-condominiums&delete=' . $condominium->id), 'delete_condominium'); ?>" 
                                   class="button button-small button-link-delete" 
                                   onclick="return confirm('<?php _e('Are you sure you want to delete this condominium? This will also delete all associated water readings.', 'water-meter-readings'); ?>')">
                                    <?php _e('Delete', 'water-meter-readings'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><?php _e('No condominiums found. Add your first condominium using the form above.', 'water-meter-readings'); ?></p>
        <?php endif; ?>
    </div>
</div>
