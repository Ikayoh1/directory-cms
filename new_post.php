<?php
include '../includes/db.php';
include 'admin_header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $content = $_POST['content'];
    $status = $_POST['status'];

    $stmt = $pdo->prepare("INSERT INTO posts (title, slug, content, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $slug, $content, $status]);

    header("Location: posts.php");
    exit;
}
?>

<h1>Add New Blog Post</h1>

<form method="POST">
    <label>Title:</label><br>
    <input type="text" name="title" required><br><br>

    <label>Content:</label><br>
    <textarea name="content" rows="10" required></textarea><br><br>

    <label>Status:</label><br>
    <select name="status">
        <option value="draft">Draft</option>
        <option value="published">Published</option>
    </select><br><br>

    <button type="submit">Publish Post</button>
</form>

<?php include 'admin_footer.php'; ?>
