<?php
require_once(__DIR__ . '/../config.php');
require(__DIR__. '/../service/MailSender.php');
$mailSender = new MailSender();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"]; 

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $verificationCode = bin2hex(random_bytes(16));
    
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash, verification_code) VALUES (?, ?, ?,?)");
    $stmt->bind_param("ssss", $name, $email, $passwordHash, $verificationCode);

    if ($stmt->execute() === TRUE) {
        $verificationLink = "http://localhost:3000/pages/verify.php?code=$verificationCode";
        $mailSender->sendEmail($email,"Confirm your email!", "Click on this link, for confirm email: $verificationLink");
        header('Location: wait_verification.php');
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/index.css">
    <title>Registration</title>
</head>
<body>
    <h1>Registration</h1>
    <form method="POST" action="registrate.php">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        
        <input type="submit" value="Register">
    </form>
    <p>If you alredy have account, <a href="login.php">login here</a>.</p>
</body>
</html>