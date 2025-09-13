-- Creating the database
CREATE DATABASE IF NOT EXISTS craftnexus;
USE craftnexus;

-- Table for artisans
CREATE TABLE artisans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone_number VARCHAR(20),
    skills TEXT NOT NULL,
    balance DECIMAL(10, 2) DEFAULT 0.00,
    jobs_completed INT DEFAULT 0,
    jobs_failed INT DEFAULT 0,
    portfolio_image VARCHAR(255),
    joined_date DATE NOT NULL,
    is_verified_artisan BOOLEAN DEFAULT FALSE,
    sudo_verified_status BOOLEAN DEFAULT FALSE,
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for non-artisans (entrepreneurs)
CREATE TABLE non_artisans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_verified_non_artisan BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for projects
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    artisan_id INT NOT NULL,
    non_artisan_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    status ENUM('Pending', 'In Progress', 'Completed', 'Failed') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (artisan_id) REFERENCES artisans(id),
    FOREIGN KEY (non_artisan_id) REFERENCES non_artisans(id)
);