<?php
include 'includes/db.php';

// Get slug from URL
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    http_response_code(400);
    echo "Invalid URL.";
    exit;
}

// Fetch the post by slug
$stmt = $pdo->prepare("SELECT * FROM posts WHERE slug = ? AND status = 'published'");
$stmt->execute([$slug]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    http_response_code(404);
    echo "Blog post not found.";
    exit;
}

include 'includes/header.php';
?>

<main class="blog-post-content">
    <article>
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>

        <?php if (!empty($post['featured_image'])): ?>
            <img src="/uploads/<?php echo htmlspecialchars($post['featured_image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" style="max-width: 100%; border-radius: 8px;">
        <?php endif; ?>

        <div class="post-body">
            <?php echo nl2br($post['content']); ?>
        </div>
    </article>

    <div class="back-link">
        <a href="/blog.php" class="button">← Back to Blog</a>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
