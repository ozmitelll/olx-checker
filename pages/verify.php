<?php
require_once(__DIR__ . '/../config.php');

$code = $_GET['code'] ?? '';

if ($code) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE users SET is_verified = 1 WHERE verification_code = ?");
    $stmt->bind_param("s", $code);

    if ($stmt->execute() === TRUE) {
        echo "Ваш аккаунт успешно подтвержден!";
        header('Location: login.php');
        exit();
    } else {
        echo "Ошибка: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>
