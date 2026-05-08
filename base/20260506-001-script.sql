-- ============================================================================
-- DATABASE RESET
-- ============================================================================

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS user_activities;
DROP TABLE IF EXISTS user_regimes;
DROP TABLE IF EXISTS promo_codes;
DROP TABLE IF EXISTS wallets;
DROP TABLE IF EXISTS activities;
DROP TABLE IF EXISTS regimes;
DROP TABLE IF EXISTS parameters;
DROP TABLE IF EXISTS users;

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- USERS
-- ============================================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    gender CHAR(1) NOT NULL,
    height_cm DECIMAL(5,2) NOT NULL,
    weight_kg DECIMAL(5,2) NOT NULL,
    age INT NOT NULL,
    objective VARCHAR(50) NOT NULL,
    imc_value DECIMAL(5,2),
    imc_category VARCHAR(20),
    wallet_balance DECIMAL(20,2) DEFAULT 0.00,
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
    price_per_week DECIMAL(20,2) NOT NULL,
    duration_weeks INT NOT NULL,
    weight_change_percent DECIMAL(5,2),
    meat_percent DECIMAL(5,2) DEFAULT 0,
    fish_percent DECIMAL(5,2) DEFAULT 0,
    poultry_percent DECIMAL(5,2) DEFAULT 0,
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
    balance DECIMAL(10,2) DEFAULT 0.00,
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
    amount DECIMAL(20,2) NOT NULL,
    is_used TINYINT(1) DEFAULT 0,
    used_by_user_id INT NULL,
    used_at TIMESTAMP NULL,
    description VARCHAR(255),
    expires_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (used_by_user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================================================
-- USER REGIMES
-- ============================================================================
CREATE TABLE user_regimes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    regime_id INT NOT NULL,
    price_paid DECIMAL(20,2) NOT NULL,
    purchased_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    starts_at TIMESTAMP NOT NULL,
    ends_at TIMESTAMP NOT NULL,
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
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
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
-- DATA PARAMETERS
-- ============================================================================
INSERT INTO parameters (`key`, value, description) VALUES
('gold_price', '49.99', 'Prix adhésion Gold'),
('gold_discount_percent', '15', 'Réduction Gold'),
('wallet_min_topup', '5.00', 'Min recharge'),
('app_name', 'IMC & Régimes', 'Nom app'),
('app_version', '1.0.0', 'Version');

-- ============================================================================
-- DATA REGIMES
-- ============================================================================
INSERT INTO regimes (name, description, calorie_target, price_per_week, duration_weeks, weight_change_percent, meat_percent, fish_percent, poultry_percent, is_active) VALUES
('Régime Léger Cardio', 'Perte de poids', 1500, 12.99, 4, -2.5, 15, 30, 35, 1),
('Régime Équilibré', 'Maintenance', 2000, 14.99, 4, 0, 25, 20, 25, 1),
('Régime Protéiné Muscu', 'Prise masse', 2800, 16.99, 4, 3, 40, 25, 20, 1),
('Régime Méditerranéen', 'Sain', 2200, 18.99, 6, 0.5, 20, 35, 15, 1),
('Détox Printanier', 'Détox', 1800, 13.99, 3, -1.5, 10, 15, 20, 1);

-- ============================================================================
-- DATA ACTIVITIES
-- ============================================================================
INSERT INTO activities (name, description, calories_per_hour, intensity, equipment_needed, difficulty_level, is_active) VALUES
('Marche Rapide', 'Marche', 300, 'low', 'Chaussures', 'beginner', 1),
('Jogging', 'Course', 600, 'medium', 'Running shoes', 'intermediate', 1),
('Musculation', 'Poids', 400, 'high', 'Haltères', 'intermediate', 1),
('Yoga', 'Relax', 150, 'low', 'Tapis', 'beginner', 1),
('Natation', 'Piscine', 500, 'high', 'Maillot', 'intermediate', 1);