<?php
session_start();
$db = new PDO('mysql:host=localhost;dbname=AVAIA', 'root', '');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];
    $tag = $_POST['tag'];

    try {
        $stmt = $db->prepare('INSERT INTO confessions (content, tag) VALUES (?, ?)');
        $stmt->execute([$content, $tag]);
        header('Location: confessions.php');
    } catch (PDOException $e) {
        die('Submission failed: ' . $e->getMessage());
    }
}
?>