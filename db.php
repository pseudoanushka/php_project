<?php
/**
 * LifeTrack: Personal Decision Analyzer
 * Database Connection & Setup
 * 
 * Supports MySQL (preferred) and SQLite (fallback).
 * Demonstrates PHP variables, data types, and database operations.
 */

// ============================================================
// DATABASE CONFIGURATION (String & Integer data types)
// ============================================================
$db_host = "localhost";        // string
$db_name = "lifetrack";        // string
$db_user = "root";             // string
$db_pass = "";                 // string
$db_port = 3306;               // integer
$use_sqlite = true;            // boolean — set to false if MySQL is available

// ============================================================
// DATABASE CONNECTION
// ============================================================
$pdo = null;

if ($use_sqlite) {
    // SQLite — no server needed, file-based
    $sqlite_path = __DIR__ . '/data/lifetrack.db';
    
    // Create data directory if it doesn't exist
    if (!is_dir(__DIR__ . '/data')) {
        mkdir(__DIR__ . '/data', 0777, true);
    }
    
    try {
        $pdo = new PDO("sqlite:" . $sqlite_path);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("SQLite Connection Failed: " . $e->getMessage());
    }
} else {
    // MySQL connection
    try {
        $pdo = new PDO(
            "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4",
            $db_user,
            $db_pass
        );
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("MySQL Connection Failed: " . $e->getMessage());
    }
}

// ============================================================
// CREATE TABLES (if they don't exist)
// ============================================================

if ($use_sqlite) {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            age INTEGER NOT NULL,
            created_at DATETIME DEFAULT (datetime('now','localtime'))
        )
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS entries (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            sleep_hours REAL NOT NULL,
            study_hours REAL NOT NULL,
            exercise_minutes INTEGER NOT NULL,
            junk_food_count INTEGER NOT NULL,
            water_glasses INTEGER NOT NULL,
            screen_time REAL NOT NULL,
            mood TEXT NOT NULL DEFAULT 'neutral',
            score INTEGER DEFAULT 0,
            category TEXT DEFAULT 'Unknown',
            fun_feedback TEXT DEFAULT '',
            entry_date DATE NOT NULL,
            created_at DATETIME DEFAULT (datetime('now','localtime')),
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )
    ");
} else {
    // MySQL table creation
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            age INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");

    $pdo->exec("
        CREATE TABLE IF NOT EXISTS entries (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            sleep_hours DECIMAL(3,1) NOT NULL,
            study_hours DECIMAL(3,1) NOT NULL,
            exercise_minutes INT NOT NULL,
            junk_food_count INT NOT NULL,
            water_glasses INT NOT NULL,
            screen_time DECIMAL(3,1) NOT NULL,
            mood VARCHAR(20) NOT NULL DEFAULT 'neutral',
            score INT DEFAULT 0,
            category VARCHAR(20) DEFAULT 'Unknown',
            fun_feedback TEXT DEFAULT NULL,
            entry_date DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    ");
}

// ============================================================
// ENSURE DEFAULT USER EXISTS
// ============================================================
$stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
$row = $stmt->fetch();
if ($row['count'] == 0) {
    $stmt = $pdo->prepare("INSERT INTO users (name, age) VALUES (?, ?)");
    $stmt->execute(["Guest User", 20]);
}
