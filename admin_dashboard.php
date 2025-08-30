<?php
session_start();
include 'includes/db.php';

// Admin-only protection
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle approval or deletion actions
if (isset($_GET['action'], $_GET['id'])) {
    $tool_id = intval($_GET['id']);
    if ($_GET['action'] === 'approve') {
        $stmt = $pdo->prepare("UPDATE tools SET status = 'approved' WHERE id = ?");
        $stmt->execute([$tool_id]);
    } elseif ($_GET['action'] === 'delete') {
        $stmt = $pdo->prepare("DELETE FROM tools WHERE id = ?");
        $stmt->execute([$tool_id]);
    }
    header('Location: admin_dashboard.php');
    exit;
}

// Fetch pending tools
$stmt = $pdo->query("SELECT * FROM tools WHERE status = 'pending' ORDER BY created_at DESC");
$pending_tools = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - ForTheStartups</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container">
    <h1>Admin Dashboard</h1>
    <h3>Pending Tools for Approval</h3>

    <?php if (count($pending_tools) === 0): ?>
        <p>No pending tools.</p>
    <?php else: ?>
        <div class="tool-list">
            <?php foreach ($pending_tools as $tool): ?>
                <div class="tool-item">
                    <?php if ($tool['logo']): ?>
                        <img src="uploads/<?php echo htmlspecialchars($tool['logo']); ?>" 
                             alt="<?php echo htmlspecialchars($tool['name']); ?>" 
                             class="tool-logo">
                    <?php endif; ?>

                    <h2><?php echo htmlspecialchars($tool['name']); ?></h2>
                    <p><?php echo htmlspecialchars($tool['description']); ?></p>

                    <a href="?action=approve&id=<?php echo $tool['id']; ?>" 
                       class="visit-tool-btn" 
                       onclick="return confirm('Approve this tool?');">
                       Approve
                    </a>

                    <a href="?action=delete&id=<?php echo $tool['id']; ?>" 
                       class="delete-btn" 
                       onclick="return confirm('Are you sure you want to delete this tool?');">
                       Delete
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
