CREATE TABLE IF NOT EXISTS menu_items (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    parent_id INT UNSIGNED NULL COMMENT 'ID du menu parent pour sous-menus',
    label VARCHAR(100) NOT NULL COMMENT 'Libellé du menu',
    route VARCHAR(255) NOT NULL COMMENT 'Route ou URL',
    icon VARCHAR(50) NULL COMMENT 'Classe d\'icône (ex: bi-house)',
    position INT NOT NULL DEFAULT 0 COMMENT 'Ordre d\'affichage',
    required_role VARCHAR(50) NULL COMMENT 'Rôle minimum requis',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES menu_items(id) ON DELETE CASCADE,
    INDEX idx_parent_id (parent_id),
    INDEX idx_position (position),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Menu par défaut
INSERT INTO menu_items (label, route, icon, position, required_role) VALUES
     ('Dashboard', '/dashboard', 'bi-speedometer2', 1, 'ROLE_USER'),
     ('Utilisateurs', '/users', 'bi-people', 2, 'ROLE_ADMIN'),
     ('Paramètres', '/settings', 'bi-gear', 3, 'ROLE_ADMIN');
