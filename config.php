<?php

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '123123');
define('DB_NAME', 'olx_checker');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Create users table
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL
)";

if ($conn->query($sql_users) === FALSE) {
    echo "Error creating 'users' table: " . $conn->error;
}

// Create subscriptions table 
$sql_subscriptions = "CREATE TABLE IF NOT EXISTS subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    price INT NOT NULL,
    listening_url VARCHAR(255) NOT NULL
)";

if ($conn->query($sql_subscriptions) === FALSE) {
    echo "Error creating 'subscriptions' table: " . $conn->error;
}

// Create user_subscriptions table
$sql_user_subscriptions = "CREATE TABLE IF NOT EXISTS user_subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    subscription_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (subscription_id) REFERENCES subscriptions(id)
)";

if ($conn->query($sql_user_subscriptions) === FALSE) {
    echo "Error creating 'user_subscriptions' table: " . $conn->error;
}

$conn->close();