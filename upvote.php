<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$tool_id = intval($_POST['tool_id'] ?? 0);

if ($tool_id > 0) {
    try {
        $stmt = $pdo->prepare("INSERT INTO tool_upvotes (tool_id, user_id) VALUES (?, ?)");
        $stmt->execute([$tool_id, $user_id]);
    } catch (PDOException $e) {
        // Ignore duplicate upvote
    }
}

header('Location: index.php');
exit;
