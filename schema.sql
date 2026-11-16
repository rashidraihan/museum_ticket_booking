-- Online Ticket Booking System of a Museum: database schema
-- One database, two tables (the original project used two separate databases).
-- Locally: create the database first, then import this file.
--   mysql -u root -e "CREATE DATABASE museum_db CHARACTER SET utf8mb4"
--   mysql -u root museum_db < schema.sql
-- On InfinityFree: open phpMyAdmin, select your database, then Import this file.

CREATE TABLE IF NOT EXISTS user_form (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email      VARCHAR(255) NOT NULL,
    password   VARCHAR(255) NOT NULL,              -- bcrypt hash from password_hash(), never plain text
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS book_form (
    id         INT UNSIGNED      NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name       VARCHAR(100)      NOT NULL,
    email      VARCHAR(255)      NOT NULL,
    phone      VARCHAR(20)       NOT NULL,
    address    VARCHAR(255)      NOT NULL,
    location   VARCHAR(100)      NOT NULL,
    visitors   SMALLINT UNSIGNED NOT NULL,
    arrivals   DATE              NOT NULL,
    created_at TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_arrivals (arrivals),
    KEY idx_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
