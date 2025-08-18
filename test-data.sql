-- Sample test data for Water Meter Readings plugin
-- Run this after installing WordPress and activating the plugin

-- Insert sample condominiums
INSERT INTO `wp_water_meter_condominiums` (`condominium_number`, `name`, `address`, `created_at`) VALUES
('A001', 'Asunto Oy Testitalo A', 'Mannerheimintie 1, 00100 Helsinki', NOW()),
('B002', 'Asunto Oy Testitalo B', 'Aleksanterinkatu 5, 00120 Helsinki', NOW()),
('C003', 'Asunto Oy Testitalo C', 'Esplanadi 10, 00130 Helsinki', NOW()),
('D004', 'Asunto Oy Testitalo D', 'Pohjoisesplanadi 15, 00140 Helsinki', NOW());

-- Insert sample water readings for A001
INSERT INTO `wp_water_meter_readings` (`condominium_id`, `hot_water`, `cold_water`, `notes`, `submitted_at`) VALUES
(1, 100.50, 200.75, 'Kuukauden lukemat', NOW()),
(1, 98.25, 198.50, 'Edellisen kuukauden lukemat', DATE_SUB(NOW(), INTERVAL 1 MONTH)),
(1, 95.00, 195.25, 'Kaksi kuukautta sitten', DATE_SUB(NOW(), INTERVAL 2 MONTH)),
(1, 92.75, 192.00, 'Kolme kuukautta sitten', DATE_SUB(NOW(), INTERVAL 3 MONTH));

-- Insert sample water readings for B002
INSERT INTO `wp_water_meter_readings` (`condominium_id`, `hot_water`, `cold_water`, `notes`, `submitted_at`) VALUES
(2, 150.00, 300.00, 'Kuukauden lukemat', NOW()),
(2, 148.75, 298.50, 'Edellisen kuukauden lukemat', DATE_SUB(NOW(), INTERVAL 1 MONTH)),
(2, 145.25, 295.75, 'Kaksi kuukautta sitten', DATE_SUB(NOW(), INTERVAL 2 MONTH));

-- Insert sample water readings for C003
INSERT INTO `wp_water_meter_readings` (`condominium_id`, `hot_water`, `cold_water`, `notes`, `submitted_at`) VALUES
(3, 75.25, 150.50, 'Kuukauden lukemat', NOW()),
(3, 73.50, 148.75, 'Edellisen kuukauden lukemat', DATE_SUB(NOW(), INTERVAL 1 MONTH)),
(3, 70.00, 145.25, 'Kaksi kuukautta sitten', DATE_SUB(NOW(), INTERVAL 2 MONTH)),
(3, 67.75, 142.50, 'Kolme kuukautta sitten', DATE_SUB(NOW(), INTERVAL 3 MONTH)),
(3, 65.25, 140.00, 'Neljä kuukautta sitten', DATE_SUB(NOW(), INTERVAL 4 MONTH));

-- Insert sample water readings for D004
INSERT INTO `wp_water_meter_readings` (`condominium_id`, `hot_water`, `cold_water`, `notes`, `submitted_at`) VALUES
(4, 200.00, 400.00, 'Kuukauden lukemat', NOW()),
(4, 198.50, 398.25, 'Edellisen kuukauden lukemat', DATE_SUB(NOW(), INTERVAL 1 MONTH));

-- Add some readings with notes about high consumption
INSERT INTO `wp_water_meter_readings` (`condominium_id`, `hot_water`, `cold_water`, `notes`, `submitted_at`) VALUES
(1, 105.75, 210.25, 'Korkea kulutus - mahdollisesti vuoto', DATE_SUB(NOW(), INTERVAL 15 DAY)),
(2, 155.50, 310.75, 'Korkea kulutus - vierailijoita', DATE_SUB(NOW(), INTERVAL 10 DAY)),
(3, 80.00, 160.25, 'Normaali kulutus', DATE_SUB(NOW(), INTERVAL 5 DAY));
