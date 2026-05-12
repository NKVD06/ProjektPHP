CREATE DATABASE IF NOT EXISTS travel_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE travel_app;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS countries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    capital VARCHAR(100) NOT NULL,
    continent VARCHAR(50) NOT NULL,
    population BIGINT NOT NULL,
    language VARCHAR(100),
    currency VARCHAR(50),
    description TEXT,
    image_url VARCHAR(500),
    best_season VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_continent (continent),
    INDEX idx_name (name),
    FULLTEXT INDEX idx_search (name, capital, language, description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;