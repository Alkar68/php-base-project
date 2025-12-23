CREATE TABLE IF NOT EXISTS config (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    config_key VARCHAR(100) NOT NULL UNIQUE COMMENT 'Clé de configuration',
    config_value TEXT NOT NULL COMMENT 'Valeur de configuration',
    description VARCHAR(255) NULL COMMENT 'Description de la configuration',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_key (config_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Configurations par défaut
INSERT INTO config (config_key, config_value, description) VALUES
    ('maintenance_mode', '0', 'Mode maintenance (0=désactivé, 1=activé)');
