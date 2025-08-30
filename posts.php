<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'includes/db.php';
include 'includes/header.php';  // Assumes your main header starts HTML and body tags

// Fetch posts
$stmt = $pdo->query("SELECT id, title, status, created_at FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    :root {
        --primary-color: #AD8B73;
        --accent-color: #CEAB93;
        --soft-background-color: #E3CAA5;
        --light-background-color: #FFFBE9;
        --text-color: #333;
        --light-text-color: #777;
    }

    body {
        background-color: var(--light-background-color);
    }

    .container {
        max-width: 900px;
        margin: 40px auto;
        background: var(--soft-background-color);
        border: 1px solid var(--accent-color);
        border-radius: 10px;
        padding: 30px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--text-color);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    h1 {
        text-align: center;
        color: var(--primary-color);
    }

    .button {
        background-color: var(--primary-color);
        color: white;
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: bold;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 20px;
    }

    .button:hover {
        background-color: var(--accent-color);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }

    th, td {
        padding: 14px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }

    th {
        background-color: var(--accent-color);
        color: white;
        text-transform: uppercase;
        font-size: 0.9em;
    }

    tr:nth-child(even) {
        background-color: #fdfdfd;
    }

    tr:hover {
        background-color: #f5f5f5;
    }

    .status-draft {
        color: var(--accent-color);
        font-weight: bold;
    }

    .status-published {
        color: var(--primary-color);
        font-weight: bold;
    }

    .empty-state {
        text-align: center;
        color: var(--light-text-color);
        font-style: italic;
        background: var(--light-background-color);
        border: 1px dashed var(--accent-color);
        border-radius: 6px;
        padding: 20px;
        margin-top: 20px;
    }
</style>

<div class="container">
    <h1>Manage Blog Posts</h1>

    <a href="new_post.php" class="button">+ Add New Post</a>

    <?php if (empty($posts)): ?>
        <p class="empty-state">No blog posts yet. Start publishing your first article!</p>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Published</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts as $post): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post['title']); ?></td>
                        <td>
                            <span class="<?php echo ($post['status'] == 'published') ? 'status-published' : 'status-draft'; ?>">
                                <?php echo htmlspecialchars(ucfirst($post['status'])); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars(date('M d, Y', strtotime($post['created_at']))); ?></td>
                        <td>
                            <a href="edit_post.php?id=<?php echo $post['id']; ?>">Edit</a> |
                            <a href="delete_post.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Delete this post?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
