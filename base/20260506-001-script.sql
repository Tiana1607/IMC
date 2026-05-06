-- ============================================================================
-- Script SQL PostgreSQL — Application IMC & Régimes Alimentaires
-- Créé : 2026-05-06
-- ============================================================================

-- Supprimer les tables existantes (en cascade pour les FK)
DROP TABLE IF EXISTS user_activities CASCADE;
DROP TABLE IF EXISTS user_regimes CASCADE;
DROP TABLE IF EXISTS promo_codes CASCADE;
DROP TABLE IF EXISTS wallets CASCADE;
DROP TABLE IF EXISTS activities CASCADE;
DROP TABLE IF EXISTS regimes CASCADE;
DROP TABLE IF EXISTS parameters CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- ============================================================================
-- Table : users
-- Description : Comptes utilisateur avec infos santé
-- ============================================================================
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    name VARCHAR(100) NOT NULL,
    gender CHAR(1) NOT NULL CHECK (gender IN ('M', 'F', 'O')), -- M=Male, F=Female, O=Other
    height_cm DECIMAL(5, 2) NOT NULL CHECK (height_cm > 0),
    weight_kg DECIMAL(5, 2) NOT NULL CHECK (weight_kg > 0),
    age INT NOT NULL CHECK (age > 0),
    objective VARCHAR(50) NOT NULL CHECK (objective IN ('gain', 'loss', 'ideal')), -- Gains, Perte, Idéal
    imc_value DECIMAL(5, 2),
    imc_category VARCHAR(20),
    wallet_balance DECIMAL(20, 2) DEFAULT 0.00 CHECK (wallet_balance >= 0),
    is_gold BOOLEAN DEFAULT FALSE,
    is_admin BOOLEAN DEFAULT FALSE,
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
);

-- ============================================================================
-- Table : regimes
-- Description : Plans alimentaires avec composition et durée
-- ============================================================================
CREATE TABLE regimes (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    calorie_target INT NOT NULL CHECK (calorie_target > 0),
    price_per_week DECIMAL(20, 2) NOT NULL CHECK (price_per_week > 0),
    duration_weeks INT NOT NULL CHECK (duration_weeks > 0),
    weight_change_percent DECIMAL(5, 2), -- Pourcentage changement poids attendu
    meat_percent DECIMAL(5, 2) NOT NULL DEFAULT 0 CHECK (meat_percent >= 0 AND meat_percent <= 100),
    fish_percent DECIMAL(5, 2) NOT NULL DEFAULT 0 CHECK (fish_percent >= 0 AND fish_percent <= 100),
    poultry_percent DECIMAL(5, 2) NOT NULL DEFAULT 0 CHECK (poultry_percent >= 0 AND poultry_percent <= 100),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
);

-- ============================================================================
-- Table : activities
-- Description : Activités sportives suggérées par profil
-- ============================================================================
CREATE TABLE activities (
    id SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    calories_per_hour INT NOT NULL CHECK (calories_per_hour > 0),
    intensity VARCHAR(20) NOT NULL CHECK (intensity IN ('low', 'medium', 'high')),
    equipment_needed TEXT,
    difficulty_level VARCHAR(20) CHECK (difficulty_level IN ('beginner', 'intermediate', 'advanced')),
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
);

-- ============================================================================
-- Table : wallets
-- Description : Portefeuille utilisateur pour achats et crédits
-- ============================================================================
CREATE TABLE wallets (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    balance DECIMAL(10, 2) NOT NULL DEFAULT 0.00 CHECK (balance >= 0),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ============================================================================
-- Table : promo_codes
-- Description : Codes promo pour crédit portefeuille (ex: cartes cadeaux)
-- ============================================================================
CREATE TABLE promo_codes (
    id SERIAL PRIMARY KEY,
    code VARCHAR(20) UNIQUE NOT NULL,
    amount DECIMAL(20, 2) NOT NULL CHECK (amount > 0),
    is_used BOOLEAN DEFAULT FALSE,
    used_by_user_id INT,
    used_at TIMESTAMP,
    description VARCHAR(255),
    expires_at TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (used_by_user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================================================
-- Table : user_regimes
-- Description : Liaison Many-to-Many : quel utilisateur a acheté quel régime
-- ============================================================================
CREATE TABLE user_regimes (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    regime_id INT NOT NULL,
    price_paid DECIMAL(20, 2) NOT NULL CHECK (price_paid > 0),
    purchased_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    starts_at TIMESTAMP NOT NULL,
    ends_at TIMESTAMP NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (regime_id) REFERENCES regimes(id) ON DELETE RESTRICT,
    UNIQUE (user_id, regime_id, purchased_at)
);

-- ============================================================================
-- Table : user_activities
-- Description : Liaison Many-to-Many : quel utilisateur suit quelle activité
-- ============================================================================
CREATE TABLE user_activities (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    activity_id INT NOT NULL,
    recommended_duration_weeks INT CHECK (recommended_duration_weeks > 0),
    starts_at TIMESTAMP,
    ends_at TIMESTAMP,
    is_completed BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (activity_id) REFERENCES activities(id) ON DELETE RESTRICT
);

-- ============================================================================
-- Table : parameters
-- Description : Configuration globale pour admin (prix Gold, % remise, etc.)
-- ============================================================================
CREATE TABLE parameters (
    id SERIAL PRIMARY KEY,
    key VARCHAR(100) UNIQUE NOT NULL,
    value TEXT NOT NULL,
    description VARCHAR(255),
    updated_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (updated_by) REFERENCES users(id) ON DELETE SET NULL
);

-- ============================================================================
-- Index Additionnels pour Performance
-- ============================================================================
CREATE INDEX idx_users_created_at ON users(created_at);
CREATE INDEX idx_regimes_created_at ON regimes(created_at);
CREATE INDEX idx_promo_codes_expires_at ON promo_codes(expires_at);
CREATE INDEX idx_user_regimes_starts_at ON user_regimes(starts_at);
CREATE INDEX idx_user_regimes_ends_at ON user_regimes(ends_at);

-- ============================================================================
-- Données de Configuration Initiales (Parameters)
-- ============================================================================
INSERT INTO parameters (key, value, description) VALUES
    ('gold_price', '49.99', 'Prix adhésion Gold (une seule fois)'),
    ('gold_discount_percent', '15', 'Pourcentage réduction Gold sur régimes'),
    ('wallet_min_topup', '5.00', 'Montant minimum crédit portefeuille'),
    ('app_name', 'IMC & Régimes', 'Nom application'),
    ('app_version', '1.0.0', 'Version application');

-- ============================================================================
-- Données de Test 
-- ============================================================================

-- Régimes
INSERT INTO regimes (name, description, calorie_target, price_per_week, duration_weeks, weight_change_percent, meat_percent, fish_percent, poultry_percent, is_active)
VALUES
    ('Régime Léger Cardio', 'Régime hypocalorique pour perte de poids - 1500 kcal/jour', 1500, 12.99, 4, -2.5, 15.0, 30.0, 35.0, TRUE),
    ('Régime Équilibré', 'Régime équilibré pour maintenance - 2000 kcal/jour', 2000, 14.99, 4, 0.0, 25.0, 20.0, 25.0, TRUE),
    ('Régime Protéiné Muscu', 'Régime hyperprotéiné pour prise de masse - 2800 kcal/jour', 2800, 16.99, 4, 3.0, 40.0, 25.0, 20.0, TRUE),
    ('Régime Méditerranéen', 'Régime sain méditerranéen - 2200 kcal/jour', 2200, 18.99, 6, 0.5, 20.0, 35.0, 15.0, TRUE),
    ('Détox Printanier', 'Régime détox légumes fruits - 1800 kcal/jour', 1800, 13.99, 3, -1.5, 10.0, 15.0, 20.0, TRUE);

-- Activités
INSERT INTO activities (name, description, calories_per_hour, intensity, equipment_needed, difficulty_level, is_active)
VALUES
    ('Marche Rapide', 'Marche à rythme soutenu en plein air', 300, 'low', 'Chaussures de sport', 'beginner', TRUE),
    ('Jogging', 'Course à allure modérée', 600, 'medium', 'Chaussures running', 'intermediate', TRUE),
    ('Musculation', 'Entraînement poids et haltères', 400, 'high', 'Haltères, banc', 'intermediate', TRUE),
    ('Yoga', 'Séance yoga relaxation et flexibility', 150, 'low', 'Tapis yoga', 'beginner', TRUE),
    ('Natation', 'Nage libre piscine', 500, 'high', 'Maillot, bonnet', 'intermediate', TRUE);

-- Portefeuilles
INSERT INTO wallets (user_id, balance)
SELECT id, wallet_balance FROM users WHERE is_admin = FALSE;

INSERT INTO wallets (user_id, balance) SELECT (SELECT id FROM users WHERE is_admin = TRUE LIMIT 1), 100.00;

-- Codes Promo
INSERT INTO promo_codes (code, amount, description, is_used)
VALUES
    ('BIENVENUE10', 10.00, 'Bienvenue - 10€', FALSE),
    ('BIENVENUE10_2', 10.00, 'Bienvenue - 10€', FALSE),
    ('BIENVENUE10_3', 10.00, 'Bienvenue - 10€', FALSE),
    ('CADEAU50', 50.00, 'Code cadeau - 50€', FALSE),
    ('CADEAU50_2', 50.00, 'Code cadeau - 50€', FALSE),
    ('PARRAINAGE5', 5.00, 'Parrainage - 5€', FALSE),
    ('PARRAINAGE5_2', 5.00, 'Parrainage - 5€', FALSE),
    ('NOEL25', 25.00, 'Noël - 25€', FALSE),
    ('NOEL25_2', 25.00, 'Noël - 25€', FALSE),
    ('EASTER15', 15.00, 'Pâques - 15€', FALSE),
    ('SUMMER20', 20.00, 'Été - 20€', FALSE),
    ('SUMMER20_2', 20.00, 'Été - 20€', FALSE),
    ('STUDENT5', 5.00, 'Étudiant - 5€', FALSE),
    ('WORKER10', 10.00, 'Actif - 10€', FALSE),
    ('VIP100', 100.00, 'VIP - 100€', FALSE);

