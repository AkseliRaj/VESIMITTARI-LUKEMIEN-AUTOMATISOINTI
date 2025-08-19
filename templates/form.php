<div class="water-meter-form-container">
    
    <!-- Step 1: Condominium Number -->
    <form id="step1-form" class="water-meter-form step-form" style="display: block;">
        <div class="form-group">
            <label for="condominium_number"><?php _e('Taloyhtiön numero', 'water-meter-readings'); ?> *</label>
            <input type="text" id="condominium_number" name="condominium_number" required>
        </div>
        
        <div class="form-group">
            <button type="submit" class="submit-btn"><?php _e('Jatka', 'water-meter-readings'); ?></button>
        </div>
    </form>
    
    <!-- Step 2: Water Meter Readings -->
    <form id="step2-form" class="water-meter-form step-form" style="display: none;">
        <div class="form-group">
            <label for="address_select"><?php _e('Osoite', 'water-meter-readings'); ?> *</label>
            <select id="address_select" name="address_id" required>
                <option value=""><?php _e('-- Valitse osoite --', 'water-meter-readings'); ?></option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="resident_name"><?php _e('Nimi (Etunimi ja sukunimi)', 'water-meter-readings'); ?></label>
            <input type="text" id="resident_name" name="resident_name" placeholder="Etunimi Sukunimi">
        </div>

        <div class="form-group">
            <label for="reading_date"><?php _e('Päivämäärä', 'water-meter-readings'); ?> *</label>
            <input type="date" id="reading_date" name="reading_date" required>
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
            <div class="form-buttons">
                <button type="button" id="back-btn" class="back-btn"><?php _e('Takaisin', 'water-meter-readings'); ?></button>
                <button type="submit" class="submit-btn"><?php _e('Lähetä lukemat', 'water-meter-readings'); ?></button>
            </div>
        </div>
    </form>
    
    <div id="form-message" class="form-message" style="display: none;"></div>
</div>
