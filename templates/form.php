<div class="water-meter-form-container">
    <h2><?php _e('Vesimittarin lukemien syöttö', 'water-meter-readings'); ?></h2>
    
    <form id="water-meter-form" class="water-meter-form">
        <div class="form-group">
            <label for="condominium_number"><?php _e('Taloyhtiön numero', 'water-meter-readings'); ?> *</label>
            <input type="text" id="condominium_number" name="condominium_number" required>
        </div>
        
        <div class="form-group">
            <label for="hot_water"><?php _e('Kuuma vesi', 'water-meter-readings'); ?> *</label>
            <input type="number" id="hot_water" name="hot_water" step="0.01" min="0" required>
        </div>
        
        <div class="form-group">
            <label for="cold_water"><?php _e('Kylmä vesi', 'water-meter-readings'); ?> *</label>
            <input type="number" id="cold_water" name="cold_water" step="0.01" min="0" required>
        </div>
        
        <div class="form-group">
            <label for="notes"><?php _e('Muuta', 'water-meter-readings'); ?></label>
            <textarea id="notes" name="notes" rows="3" placeholder="<?php _e('Voit kirjoittaa esim. syyn isolle kulutuksen määrälle', 'water-meter-readings'); ?>"></textarea>
        </div>
        
        <div class="form-group">
            <button type="submit" class="submit-btn"><?php _e('Lähetä lukemat', 'water-meter-readings'); ?></button>
        </div>
    </form>
    
    <div id="form-message" class="form-message" style="display: none;"></div>
</div>
