-- ============================================================================
-- DATABASE RESET
-- ============================================================================
SET
    FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS activity_objectives;

DROP TABLE IF EXISTS regime_objectives;

DROP TABLE IF EXISTS user_activities;

DROP TABLE IF EXISTS user_regimes;

DROP TABLE IF EXISTS offers;

DROP TABLE IF EXISTS promo_codes;

DROP TABLE IF EXISTS wallets;

DROP TABLE IF EXISTS activities;

DROP TABLE IF EXISTS regimes;

DROP TABLE IF EXISTS parameters;

DROP TABLE IF EXISTS users;

SET
    FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- USERS
-- ============================================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    gender CHAR(1) NOT NULL,
    height_cm DECIMAL(5, 2) NOT NULL,
    weight_kg DECIMAL(5, 2) NOT NULL,
    age INT NOT NULL,
    objective VARCHAR(50) NOT NULL,
    imc_value DECIMAL(5, 2),
    imc_category VARCHAR(20),
    wallet_balance DECIMAL(20, 2) DEFAULT 0.00,
    is_gold TINYINT(1) DEFAULT 0,
    is_admin TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================================
-- REGIMES
-- ============================================================================
CREATE TABLE regimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    calorie_target INT NOT NULL,
    price_per_week DECIMAL(20, 2) NOT NULL,
    duration_weeks INT NOT NULL,
    weight_change_percent DECIMAL(5, 2),
    meat_percent DECIMAL(5, 2) DEFAULT 0,
    fish_percent DECIMAL(5, 2) DEFAULT 0,
    poultry_percent DECIMAL(5, 2) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
 
-- ============================================================================
-- ACTIVITIES
-- ============================================================================
CREATE TABLE activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    calories_per_hour INT NOT NULL,
    intensity VARCHAR(20) NOT NULL,
    equipment_needed TEXT,
    difficulty_level VARCHAR(20),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================================
-- WALLETS
-- ============================================================================
CREATE TABLE wallets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    balance DECIMAL(10, 2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================================
-- PROMO CODES
-- ============================================================================
CREATE TABLE promo_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(20) UNIQUE NOT NULL,
    amount DECIMAL(20, 2) NOT NULL,
    is_used TINYINT(1) DEFAULT 0,
    used_by_user_id INT NULL,
    used_at TIMESTAMP NULL,
    description VARCHAR(255),
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (used_by_user_id) REFERENCES users(id) ON DELETE
    SET
        NULL
);

-- ============================================================================
-- OFFERS
-- ============================================================================
CREATE TABLE offers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    label VARCHAR(100),
    discount_percent INT DEFAULT 0,
    description TEXT,
    cta_text VARCHAR(255),
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================================================
-- USER REGIMES
-- ============================================================================
CREATE TABLE user_regimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    regime_id INT NOT NULL,
    price_paid DECIMAL(20, 2) NOT NULL,
    purchased_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    starts_at TIMESTAMP NULL,
    ends_at TIMESTAMP NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_purchase (user_id, regime_id, purchased_at),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE RESTRICT
);

-- ============================================================================
-- USER ACTIVITIES
-- ============================================================================
CREATE TABLE user_activities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    activity_id INT NOT NULL,
    recommended_duration_weeks INT,
    starts_at TIMESTAMP NULL,
    ends_at TIMESTAMP NULL,
    is_completed TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE RESTRICT
);

-- ============================================================================
-- PARAMETERS
-- ============================================================================
CREATE TABLE parameters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(100) UNIQUE NOT NULL,
    value TEXT NOT NULL,
    description VARCHAR(255),
    updated_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE
    SET
        NULL
);

-- ============================================================================
-- REGIME OBJECTIVES
-- ============================================================================
CREATE TABLE regime_objectives (
    regime_id INT NOT NULL,
    objective VARCHAR(50) NOT NULL,
    -- 'loss', 'gain', 'ideal'
    PRIMARY KEY(regime_id, objective),
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE CASCADE
);

-- ============================================================================
-- ACTIVITY OBJECTIVES
-- ============================================================================
CREATE TABLE activity_objectives (
    activity_id INT NOT NULL,
    objective VARCHAR(50) NOT NULL,
    PRIMARY KEY(activity_id, objective),
    FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE CASCADE
);

-- ============================================================================
-- INDEXES
-- ============================================================================
CREATE INDEX idx_users_created_at ON users(created_at);

CREATE INDEX idx_regimes_created_at ON regimes(created_at);

CREATE INDEX idx_promo_codes_expires_at ON promo_codes(expires_at);

CREATE INDEX idx_user_regimes_starts_at ON user_regimes(starts_at);

CREATE INDEX idx_user_regimes_ends_at ON user_regimes(ends_at);

-- ============================================================================
-- DATA USERS
-- ============================================================================
INSERT INTO users (
    id, email, password_hash, name, gender, height_cm, weight_kg, age, objective,
    imc_value, imc_category, wallet_balance, is_gold, is_admin, is_active, last_login,
    created_at, updated_at
) VALUES
    (1, 'admin@gmail.com', '$2y$10$4f5grljIYI7uX9WDcNQa7eHGz/V1geYAsIdjICFgdHVosd8kzAfZm', 'admin', 'M', 158.00, 52.00, 19, 'imc_ideal', 20.83, 'Normal', 0.00, 0, 1, 1, '2026-05-10 07:20:18', '2026-05-10 07:20:18', '2026-05-10 07:20:18'),
    (2, 'rova@gmail.com', '$2y$10$chENiDiRk5W6R256rdA.j.Dja1As.dC.s.UDwd.DwQO2S9gnzGcxm', 'rova', 'F', 163.00, 52.00, 19, 'imc_ideal', 19.57, 'Normal', 0.00, 0, 0, 1, '2026-05-09 10:38:23', '2026-05-09 10:38:23', '2026-05-09 10:38:23'),
    (3, 'jeremie@gmail.com', '$2y$10$u0fPHbKSKhI6HPrcTRJXmuJ3QGB9vMDk13hQGqlFnkefGAP6CTZ26', 'jeremie', 'M', 170.00, 48.00, 23, 'augmenter_poids', 16.61, 'Sous-poids', 0.00, 0, 0, 1, '2026-05-09 15:50:06', '2026-05-09 15:50:06', '2026-05-09 15:50:06'),
    (4, 'sarah@gmail.com', '$2y$10$.j5UiT0UHBfa.Fk3qMhO7ORvtyJQLmyHngBWBP8i5bjdxe7xhsGUC', 'sarah', 'F', 168.00, 74.00, 27, 'reduire_poids', 26.22, 'Surpoids', 0.00, 0, 0, 1, NULL, '2026-05-10 07:20:18', '2026-05-10 07:20:18'),
    (5, 'mine@gmail.com', '$2y$10$gSu4v/1m3.FyfjCncc4F4OsXO3C4mmlAqwDsS4rz7hDqHVMkiK5h6', 'mine', 'M', 182.00, 66.00, 25, 'augmenter_poids', 19.92, 'Normal', 0.00, 0, 0, 1, NULL, '2026-05-10 07:20:18', '2026-05-10 07:20:18');

-- ============================================================================
-- DATA REGIMES
-- ============================================================================
INSERT INTO regimes (
    id, name, description, calorie_target, price_per_week, duration_weeks,
    weight_change_percent, meat_percent, fish_percent, poultry_percent, is_active,
    created_at, updated_at
) VALUES
    (1, 'Régime Léger Cardio', 'Perte de poids', 1500, 12.99, 4, -2.5, 15, 30, 35, 1, '2026-05-10 07:20:18', '2026-05-10 07:20:18'),
    (2, 'Régime Équilibré', 'Maintenance', 2000, 14.99, 4, 0, 25, 20, 25, 1, '2026-05-10 07:20:18', '2026-05-10 07:20:18'),
    (3, 'Régime Protéiné Muscu', 'Prise masse', 2800, 16.99, 4, 3, 40, 25, 20, 1, '2026-05-10 07:20:18', '2026-05-10 07:20:18'),
    (4, 'Régime Méditerranéen', 'Sain', 2200, 18.99, 6, 0.5, 20, 35, 15, 1, '2026-05-10 07:20:18', '2026-05-10 07:20:18'),
    (5, 'Détox Printanier', 'Détox', 1800, 13.99, 3, -1.5, 10, 15, 20, 1, '2026-05-10 07:20:18', '2026-05-10 07:20:18');

-- ============================================================================
-- DATA ACTIVITIES
-- ============================================================================
INSERT INTO activities (
    id, name, description, calories_per_hour, intensity, equipment_needed,
    difficulty_level, is_active, created_at, updated_at
) VALUES
    (1, 'Marche Rapide', 'Marche', 300, 'low', 'Chaussures', 'beginner', 1, '2026-05-10 07:20:18', '2026-05-10 07:20:18'),
    (2, 'Jogging', 'Course', 600, 'medium', 'Running shoes', 'intermediate', 1, '2026-05-10 07:20:18', '2026-05-10 07:20:18'),
    (3, 'Musculation', 'Poids', 400, 'high', 'Haltères', 'intermediate', 1, '2026-05-10 07:20:18', '2026-05-10 07:20:18'),
    (4, 'Yoga', 'Relax', 150, 'low', 'Tapis', 'beginner', 1, '2026-05-10 07:20:18', '2026-05-10 07:20:18'),
    (5, 'Natation', 'Piscine', 500, 'high', 'Maillot', 'intermediate', 1, '2026-05-10 07:20:18', '2026-05-10 07:20:18');

-- ============================================================================
-- DATA OFFERS
-- ============================================================================
INSERT INTO offers (
    id, name, label, discount_percent, description, cta_text, is_active, created_at, updated_at
) VALUES
    (1, 'Pass Gold', 'Offre limitée', 15, 'Bénéficiez d''une remise exclusive de 15% sur tous les régimes personnalisés.', 'Être un membre Gold', 1, '2026-05-10 07:20:18', '2026-05-10 07:20:18');

-- ============================================================================
-- DATA PROMO CODES
-- ============================================================================
INSERT INTO promo_codes (
    id, code, amount, is_used, used_by_user_id, used_at, description, expires_at, created_at
) VALUES
    (1, 'WELCOME5', 5.00, 0, NULL, NULL, 'Welcome bonus', NULL, '2026-05-10 07:20:18'),
    (2, 'VITAL-10', 10.00, 0, NULL, NULL, 'Promo 10€', NULL, '2026-05-10 07:20:18'),
    (3, 'GIFT50', 50.00, 0, NULL, NULL, 'Grand cadeau', NULL, '2026-05-10 07:20:18'),
    (4, 'USED-TEST', 20.00, 1, 2, '2026-05-09 13:20:18', 'Used by test user', NULL, '2026-05-10 07:20:18'),
    (5, 'START-15', 15.00, 0, NULL, NULL, 'Bonus demarrage', NULL, '2026-05-10 07:20:18'),
    (6, 'MOVE-05', 5.00, 0, NULL, NULL, 'Code activite', NULL, '2026-05-10 07:20:18'),
    (7, 'FIT-20', 20.00, 0, NULL, NULL, 'Code fitness', NULL, '2026-05-10 07:20:18'),
    (8, 'HEALTH-25', 25.00, 0, NULL, NULL, 'Code sante', NULL, '2026-05-10 07:20:18'),
    (9, 'BOOST-30', 30.00, 0, NULL, NULL, 'Boost portefeuille', NULL, '2026-05-10 07:20:18'),
    (10, 'CARDIO-08', 8.00, 0, NULL, NULL, 'Code cardio', NULL, '2026-05-10 07:20:18'),
    (11, 'MUSCU-12', 12.00, 0, NULL, NULL, 'Code musculation', NULL, '2026-05-10 07:20:18'),
    (12, 'ZEN-07', 7.00, 0, NULL, NULL, 'Code bien-etre', NULL, '2026-05-10 07:20:18'),
    (13, 'PLUS-18', 18.00, 0, NULL, NULL, 'Recharge plus', NULL, '2026-05-10 07:20:18'),
    (14, 'POWER-22', 22.00, 0, NULL, NULL, 'Code puissance', NULL, '2026-05-10 07:20:18'),
    (15, 'SPRING-11', 11.00, 0, NULL, NULL, 'Offre saisonniere', NULL, '2026-05-10 07:20:18');

-- ============================================================================
-- DATA WALLETS
-- ============================================================================
INSERT INTO wallets (
    id, user_id, balance, created_at, updated_at
) VALUES
    (1, 1, 0.00, '2026-05-10 07:20:18', '2026-05-10 07:20:18'),
    (2, 2, 100.00, '2026-05-10 07:20:18', '2026-05-10 07:20:18'),
    (3, 3, 10.00, '2026-05-10 07:20:18', '2026-05-10 07:20:18');

-- Email                     | Mot de passe\n------------------------------------------------------------
-- admin@gmail.com           | AdminVital2026
-- rova@gmail.com            | RovaVital2026
-- jeremie@gmail.com         | JeremieVital2026
-- sarah@gmail.com           | SarahVital2026
-- mine@gmail.com            | MineVital2026