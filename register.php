<?php
session_start();
$db = new PDO('mysql:host=localhost;dbname=AVAIA', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        die('Passwords do not match');
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $db->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $stmt->execute([$username, $hashed_password]);
        header('Location: login.html');
    } catch (PDOException $e) {
        die('Registration failed: ' . $e->getMessage());
    }
}
?>