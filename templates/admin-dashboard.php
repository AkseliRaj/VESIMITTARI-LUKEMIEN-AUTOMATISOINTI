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
    $condominium = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}water_meter_condominiums WHERE id = %d",
        $selected_condominium_id
    ));
    
    // Selected address filter
    $selected_address_id = isset($_GET['address_id']) ? intval($_GET['address_id']) : 0;
    // Get readings for the selected condominium (optionally filtered by address)
    if ($selected_address_id) {
        $readings = $wpdb->get_results($wpdb->prepare(
            "SELECT r.*, a.address_text 
             FROM {$wpdb->prefix}water_meter_readings r 
             LEFT JOIN {$wpdb->prefix}water_meter_addresses a ON r.address_id = a.id 
             WHERE r.condominium_id = %d AND r.address_id = %d
             ORDER BY r.reading_date DESC, r.submitted_at DESC",
            $selected_condominium_id,
            $selected_address_id
        ));
    } else {
        $readings = $wpdb->get_results($wpdb->prepare(
            "SELECT r.*, a.address_text 
             FROM {$wpdb->prefix}water_meter_readings r 
             LEFT JOIN {$wpdb->prefix}water_meter_addresses a ON r.address_id = a.id 
             WHERE r.condominium_id = %d 
             ORDER BY r.reading_date DESC, r.submitted_at DESC",
            $selected_condominium_id
        ));
    }
    // Addresses for filter
    $condo_addresses = $wpdb->get_results($wpdb->prepare(
        "SELECT id, address_text FROM {$wpdb->prefix}water_meter_addresses WHERE condominium_id = %d ORDER BY address_text",
        $selected_condominium_id
    ));
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
                            <th><?php _e('Reading date', 'water-meter-readings'); ?></th>
                            <th><?php _e('Address', 'water-meter-readings'); ?></th>
                            <th><?php _e('Name', 'water-meter-readings'); ?></th>
                            <th><?php _e('Hot Water', 'water-meter-readings'); ?></th>
                            <th><?php _e('Cold Water', 'water-meter-readings'); ?></th>
                            <th><?php _e('Total', 'water-meter-readings'); ?></th>
                            <th><?php _e('Notes', 'water-meter-readings'); ?></th>
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
            var chartData = <?php echo json_encode(array_map(function($reading) {
                return [
                    'date' => date('d.m.Y', strtotime($reading->reading_date)),
                    'hot_water' => floatval($reading->hot_water),
                    'cold_water' => floatval($reading->cold_water)
                ];
            }, array_reverse($readings))); ?>;
        </script>
        
    <?php else: ?>
        <div class="no-condominium-selected">
            <p><?php _e('Please select a condominium to view its water meter data.', 'water-meter-readings'); ?></p>
        </div>
    <?php endif; ?>
</div>
