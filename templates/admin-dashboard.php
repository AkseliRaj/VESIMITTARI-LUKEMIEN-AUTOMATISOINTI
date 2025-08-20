<?php
if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient permissions to access this page.'));
}

global $wpdb;

// Get all condominiums
$condominiums = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}water_meter_condominiums ORDER BY name");

// Get selected condominium
$selected_condominium_id = isset($_GET['condominium_id']) ? intval($_GET['condominium_id']) : 0;

if ($selected_condominium_id) {
    // Handle delete reading
    if (isset($_GET['delete_reading']) && isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'delete_reading')) {
        $reading_id = intval($_GET['delete_reading']);
        // Ensure the reading belongs to selected condominium
        $reading_to_delete = $wpdb->get_row($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}water_meter_readings WHERE id = %d AND condominium_id = %d",
            $reading_id,
            $selected_condominium_id
        ));
        if ($reading_to_delete) {
            $wpdb->delete($wpdb->prefix . 'water_meter_readings', array('id' => $reading_id), array('%d'));
            echo '<div class="notice notice-success"><p>' . __('Reading deleted successfully.', 'water-meter-readings') . '</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>' . __('Invalid reading or permission denied.', 'water-meter-readings') . '</p></div>';
        }
    }

    // Handle update reading
    if (isset($_POST['action']) && $_POST['action'] === 'update_reading') {
        check_admin_referer('update_reading');
        $reading_id = intval($_POST['reading_id']);
        $address_id = intval($_POST['address_id']);
        $reading_date = sanitize_text_field($_POST['reading_date']);
        $hot_water = floatval($_POST['hot_water']);
        $cold_water = floatval($_POST['cold_water']);
        $resident_name = isset($_POST['resident_name']) ? sanitize_text_field($_POST['resident_name']) : null;
        $notes = sanitize_textarea_field($_POST['notes']);

        // Validate reading belongs to selected condo and address within same condo
        $valid_reading = $wpdb->get_row($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}water_meter_readings WHERE id = %d AND condominium_id = %d",
            $reading_id,
            $selected_condominium_id
        ));
        $valid_address = $wpdb->get_row($wpdb->prepare(
            "SELECT id FROM {$wpdb->prefix}water_meter_addresses WHERE id = %d AND condominium_id = %d",
            $address_id,
            $selected_condominium_id
        ));

        if ($valid_reading && $valid_address) {
            $wpdb->update(
                $wpdb->prefix . 'water_meter_readings',
                array(
                    'address_id' => $address_id,
                    'reading_date' => $reading_date,
                    'hot_water' => $hot_water,
                    'cold_water' => $cold_water,
                    'resident_name' => $resident_name,
                    'notes' => $notes,
                ),
                array('id' => $reading_id),
                array('%d', '%s', '%f', '%f', '%s', '%s'),
                array('%d')
            );
            echo '<div class="notice notice-success"><p>' . __('Reading updated successfully.', 'water-meter-readings') . '</p></div>';
        } else {
            echo '<div class="notice notice-error"><p>' . __('Invalid data provided. Update failed.', 'water-meter-readings') . '</p></div>';
        }
    }

    $condominium = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}water_meter_condominiums WHERE id = %d",
        $selected_condominium_id
    ));
    
    // Selected address filter
    $selected_address_id = isset($_GET['address_id']) ? intval($_GET['address_id']) : 0;
    // Sorting by reading_date
    $order = isset($_GET['order']) && strtolower($_GET['order']) === 'asc' ? 'ASC' : 'DESC';
    // Get readings for the selected condominium (optionally filtered by address)
    if ($selected_address_id) {
        $readings = $wpdb->get_results($wpdb->prepare(
            "SELECT r.*, a.address_text 
             FROM {$wpdb->prefix}water_meter_readings r 
             LEFT JOIN {$wpdb->prefix}water_meter_addresses a ON r.address_id = a.id 
             WHERE r.condominium_id = %d AND r.address_id = %d
             ORDER BY r.reading_date $order, r.submitted_at $order",
            $selected_condominium_id,
            $selected_address_id
        ));
    } else {
        $readings = $wpdb->get_results($wpdb->prepare(
            "SELECT r.*, a.address_text 
             FROM {$wpdb->prefix}water_meter_readings r 
             LEFT JOIN {$wpdb->prefix}water_meter_addresses a ON r.address_id = a.id 
             WHERE r.condominium_id = %d 
             ORDER BY r.reading_date $order, r.submitted_at $order",
            $selected_condominium_id
        ));
    }
    // Addresses for filter
    $condo_addresses = $wpdb->get_results($wpdb->prepare(
        "SELECT id, address_text FROM {$wpdb->prefix}water_meter_addresses WHERE condominium_id = %d ORDER BY address_text",
        $selected_condominium_id
    ));

    // Load one reading to edit if requested
    $edit_reading_id = isset($_GET['edit_reading']) ? intval($_GET['edit_reading']) : 0;
    $reading_to_edit = null;
    if ($edit_reading_id) {
        $reading_to_edit = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}water_meter_readings WHERE id = %d AND condominium_id = %d",
            $edit_reading_id,
            $selected_condominium_id
        ));
    }
}
?>

<div class="wrap">
    <h1><?php _e('Water Meter Readings Dashboard', 'water-meter-readings'); ?></h1>
    
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
        <?php if ($selected_condominium_id && !empty($condo_addresses)): ?>
            <label for="address-filter" style="margin-left: 1rem;"><?php _e('Address:', 'water-meter-readings'); ?></label>
            <select id="address-filter">
                <option value="0"><?php _e('-- All addresses --', 'water-meter-readings'); ?></option>
                <?php foreach ($condo_addresses as $addr): ?>
                    <option value="<?php echo $addr->id; ?>" <?php selected(isset($selected_address_id)?$selected_address_id:0, $addr->id); ?>>
                        <?php echo esc_html($addr->address_text); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
    </div>
    
    <?php if ($selected_condominium_id && $condominium): ?>
        <div class="condominium-info">
            <h2><?php echo esc_html($condominium->name); ?></h2>
            <p><strong><?php _e('Number:', 'water-meter-readings'); ?></strong> <?php echo esc_html($condominium->condominium_number); ?></p>
            <?php if ($condominium->address): ?>
                <p><strong><?php _e('Address:', 'water-meter-readings'); ?></strong> <?php echo esc_html($condominium->address); ?></p>
            <?php endif; ?>
        </div>

        <?php if ($reading_to_edit): ?>
            <div class="edit-reading-section">
                <h3><?php _e('Edit Reading', 'water-meter-readings'); ?></h3>
                <form method="post">
                    <?php wp_nonce_field('update_reading'); ?>
                    <input type="hidden" name="action" value="update_reading">
                    <input type="hidden" name="reading_id" value="<?php echo intval($reading_to_edit->id); ?>">
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="address_id"><?php _e('Address', 'water-meter-readings'); ?></label></th>
                            <td>
                                <select name="address_id" id="address_id" required>
                                    <?php foreach ($condo_addresses as $addr): ?>
                                        <option value="<?php echo $addr->id; ?>" <?php selected($reading_to_edit->address_id, $addr->id); ?>>
                                            <?php echo esc_html($addr->address_text); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="resident_name"><?php _e('Name', 'water-meter-readings'); ?></label></th>
                            <td><input type="text" name="resident_name" id="resident_name" value="<?php echo esc_attr($reading_to_edit->resident_name); ?>" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="reading_date"><?php _e('Reading date', 'water-meter-readings'); ?></label></th>
                            <td><input type="date" name="reading_date" id="reading_date" value="<?php echo esc_attr($reading_to_edit->reading_date); ?>" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="hot_water"><?php _e('Hot Water', 'water-meter-readings'); ?></label></th>
                            <td><input type="number" step="0.01" min="0" name="hot_water" id="hot_water" value="<?php echo esc_attr($reading_to_edit->hot_water); ?>" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="cold_water"><?php _e('Cold Water', 'water-meter-readings'); ?></label></th>
                            <td><input type="number" step="0.01" min="0" name="cold_water" id="cold_water" value="<?php echo esc_attr($reading_to_edit->cold_water); ?>" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="notes"><?php _e('Notes', 'water-meter-readings'); ?></label></th>
                            <td><textarea name="notes" id="notes" rows="3" class="large-text"><?php echo esc_textarea($reading_to_edit->notes); ?></textarea></td>
                        </tr>
                    </table>
                    <p class="submit">
                        <input type="submit" class="button button-primary" value="<?php _e('Save changes', 'water-meter-readings'); ?>">
                        <a href="<?php echo esc_url(remove_query_arg('edit_reading')); ?>" class="button"><?php _e('Cancel', 'water-meter-readings'); ?></a>
                    </p>
                </form>
            </div>
        <?php endif; ?>
        
        <!-- Charts Section -->
        <div class="charts-section">
            <h3><?php _e('Water Consumption Charts', 'water-meter-readings'); ?></h3>
            
            <div class="chart-container">
                <canvas id="waterChart" width="400" height="200"></canvas>
            </div>
            
            <div class="chart-container">
                <canvas id="consumptionChart" width="400" height="200"></canvas>
            </div>
        </div>
        
        <!-- Readings Table -->
        <div class="readings-table-section">
            <h3><?php _e('Recent Readings', 'water-meter-readings'); ?></h3>
            
            <?php if ($readings): ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>
                                <?php 
                                    $base_url = admin_url('admin.php?page=water-meter-readings&condominium_id=' . $selected_condominium_id);
                                    if ($selected_address_id) { $base_url = add_query_arg('address_id', $selected_address_id, $base_url); }
                                    $toggle_order = (isset($_GET['order']) && strtolower($_GET['order']) === 'asc') ? 'desc' : 'asc';
                                    $order_link = add_query_arg('order', $toggle_order, $base_url);
                                ?>
                                <a href="<?php echo esc_url($order_link); ?>"><?php _e('Reading date', 'water-meter-readings'); ?></a>
                            </th>
                            <th><?php _e('Address', 'water-meter-readings'); ?></th>
                            <th><?php _e('Name', 'water-meter-readings'); ?></th>
                            <th><?php _e('Hot Water', 'water-meter-readings'); ?></th>
                            <th><?php _e('Cold Water', 'water-meter-readings'); ?></th>
                            <th><?php _e('Total', 'water-meter-readings'); ?></th>
                            <th><?php _e('Notes', 'water-meter-readings'); ?></th>
                            <th><?php _e('Actions', 'water-meter-readings'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $prev_hot = null;
                        $prev_cold = null;
                        foreach ($readings as $reading): 
                            $total = $reading->hot_water + $reading->cold_water;
                            $hot_diff = $prev_hot !== null ? $reading->hot_water - $prev_hot : 0;
                            $cold_diff = $prev_cold !== null ? $reading->cold_water - $prev_cold : 0;
                            $prev_hot = $reading->hot_water;
                            $prev_cold = $reading->cold_water;
                        ?>
                            <tr>
                                <td><?php echo date('d.m.Y', strtotime($reading->reading_date)); ?></td>
                                <td><?php echo esc_html($reading->address_text); ?></td>
                                <td><?php echo esc_html($reading->resident_name); ?></td>
                                <td>
                                    <?php echo number_format($reading->hot_water, 2); ?>
                                    <?php if ($hot_diff > 0): ?>
                                        <span class="consumption-diff">(+<?php echo number_format($hot_diff, 2); ?>)</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo number_format($reading->cold_water, 2); ?>
                                    <?php if ($cold_diff > 0): ?>
                                        <span class="consumption-diff">(+<?php echo number_format($cold_diff, 2); ?>)</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo number_format($total, 2); ?></td>
                                <td><?php echo esc_html($reading->notes); ?></td>
                                <td>
                                    <?php 
                                        $edit_url = add_query_arg(array(
                                            'page' => 'water-meter-readings',
                                            'condominium_id' => $selected_condominium_id,
                                            'edit_reading' => $reading->id,
                                            'address_id' => $selected_address_id,
                                            'order' => isset($_GET['order']) ? esc_attr($_GET['order']) : 'desc',
                                        ), admin_url('admin.php'));
                                        $delete_url = wp_nonce_url(add_query_arg(array(
                                            'page' => 'water-meter-readings',
                                            'condominium_id' => $selected_condominium_id,
                                            'delete_reading' => $reading->id,
                                            'address_id' => $selected_address_id,
                                            'order' => isset($_GET['order']) ? esc_attr($_GET['order']) : 'desc',
                                        ), admin_url('admin.php')), 'delete_reading');
                                    ?>
                                    <a class="button button-small" href="<?php echo esc_url($edit_url); ?>"><?php _e('Edit', 'water-meter-readings'); ?></a>
                                    <a class="button button-small button-link-delete" href="<?php echo esc_url($delete_url); ?>" onclick="return confirm('<?php _e('Are you sure you want to delete this reading?', 'water-meter-readings'); ?>');"><?php _e('Delete', 'water-meter-readings'); ?></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p><?php _e('No readings found for this condominium.', 'water-meter-readings'); ?></p>
            <?php endif; ?>
        </div>
        
        <script>
            // Chart data
            <?php 
            // Prepare chart data with chronological order independent of table sort
            if ($selected_address_id) {
                $chart_rows = $wpdb->get_results($wpdb->prepare(
                    "SELECT reading_date, hot_water, cold_water FROM {$wpdb->prefix}water_meter_readings WHERE condominium_id = %d AND address_id = %d ORDER BY reading_date ASC, submitted_at ASC",
                    $selected_condominium_id,
                    $selected_address_id
                ));
            } else {
                $chart_rows = $wpdb->get_results($wpdb->prepare(
                    "SELECT reading_date, hot_water, cold_water FROM {$wpdb->prefix}water_meter_readings WHERE condominium_id = %d ORDER BY reading_date ASC, submitted_at ASC",
                    $selected_condominium_id
                ));
            }
            ?>
            var chartData = <?php echo json_encode(array_map(function($r) {
                return [
                    'date' => date('d.m.Y', strtotime($r->reading_date)),
                    'hot_water' => floatval($r->hot_water),
                    'cold_water' => floatval($r->cold_water)
                ];
            }, $chart_rows)); ?>;
        </script>
        
    <?php else: ?>
        <div class="no-condominium-selected">
            <p><?php _e('Please select a condominium to view its water meter data.', 'water-meter-readings'); ?></p>
        </div>
    <?php endif; ?>
</div>
