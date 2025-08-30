<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Correct paths based on your file structure:
// edit_post.php is in the root, includes/db.php is in a subfolder 'includes'
include 'includes/db.php';
// includes/header.php is in the same 'includes' subfolder
include 'includes/header.php';

$id = $_GET['id'] ?? null;

// Validate ID early
if (!$id) {
    echo '<div class="error-message edit-form-container">Invalid Post ID. Please go back and select a post to edit.</div>';
    include 'includes/footer.php'; // Ensure footer is included even on error
    exit;
}

// Fetch existing post
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
    echo '<div class="error-message edit-form-container">Post not found. It might have been deleted or never existed.</div>';
    include 'includes/footer.php'; // Ensure footer is included even on error
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? ''; // Use null coalescing for safety
    $content = $_POST['content'] ?? '';
    $status = $_POST['status'] ?? 'draft'; // Default to draft if not set

    // Generate slug from title
    // Remove non-alphanumeric characters, convert to lowercase, trim hyphens
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));

    try {
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, slug = ?, content = ?, status = ? WHERE created_at = ?"); // Assuming created_at is unique or using ID below
        $stmt->execute([$title, $slug, $content, $status, $post['created_at']]); // Use original created_at for update

        // Redirect after successful update
        header("Location: posts.php?message=Post_Updated_Successfully");
        exit;
    } catch (PDOException $e) {
        // Log error and show a user-friendly message
        error_log("Error updating post: " . $e->getMessage());
        echo '<div class="error-message edit-form-container">An error occurred while saving your changes. Please try again.</div>';
    }
}
?>

<style>
    /* CSS variables for consistent theming */
    :root {
        --primary-color: #AD8B73;
        --accent-color: #CEAB93;
        --soft-background-color: #E3CAA5;
        --light-background-color: #FFFBE9;
        --text-color: #333;
        --light-text-color: #777;
        --error-color: #d9534f; /* A red color for errors */
    }

    /* Basic body styles for consistency, assumed from header/footer */
    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: var(--light-background-color);
        color: var(--text-color);
        margin: 0;
        padding: 20px;
        line-height: 1.6;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
    }

    .edit-form-container {
        max-width: 800px;
        width: 100%;
        margin: 40px 0; /* Adjust margin for flex centering */
        background: white; /* Changed to white for a cleaner form background */
        border: 1px solid var(--accent-color);
        border-radius: 10px;
        padding: 30px;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: var(--text-color);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        box-sizing: border-box; /* Include padding in width */
    }

    h1 {
        text-align: center;
        color: var(--primary-color);
        font-size: 2.2em;
        margin-bottom: 25px;
        border-bottom: 2px solid var(--accent-color);
        padding-bottom: 10px;
    }

    label {
        font-weight: bold;
        display: block;
        margin: 15px 0 8px; /* Slightly adjusted margins */
        color: var(--primary-color); /* Label color */
    }

    input[type="text"],
    textarea,
    select {
        width: calc(100% - 24px); /* Account for padding */
        padding: 12px;
        border: 1px solid var(--accent-color);
        border-radius: 6px;
        font-size: 1em;
        background-color: white;
        color: var(--text-color);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.08);
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    input[type="text"]:focus,
    textarea:focus,
    select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(173, 139, 115, 0.2); /* Soft focus glow */
        outline: none; /* Remove default outline */
    }

    textarea {
        resize: vertical;
        min-height: 150px; /* Give more initial space */
    }

    button {
        background-color: var(--primary-color);
        color: white;
        font-weight: bold;
        padding: 12px 25px; /* Increased padding */
        border: none;
        border-radius: 6px;
        font-size: 1.1em;
        margin-top: 30px; /* More space above button */
        cursor: pointer;
        display: inline-block;
        transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    button:hover {
        background-color: var(--accent-color);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .error-message {
        background-color: #fcebeb; /* Light red background */
        color: var(--error-color);
        border: 1px solid var(--error-color);
        padding: 15px;
        border-radius: 8px;
        text-align: center;
        font-weight: bold;
        margin-bottom: 20px;
    }
</style>

<div class="edit-form-container">
    <h1>📝 Edit Blog Post</h1>

    <form method="POST">
        <label for="title">Title:</label>
        <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($post['title'] ?? ''); ?>" required>

        <label for="content">Content:</label>
        <textarea name="content" id="content" rows="15" required><?php echo htmlspecialchars($post['content'] ?? ''); ?></textarea>

        <label for="status">Status:</label>
        <select name="status" id="status">
            <option value="draft" <?php if (($post['status'] ?? '') === 'draft') echo 'selected'; ?>>Draft</option>
            <option value="published" <?php if (($post['status'] ?? '') === 'published') echo 'selected'; ?>>Published</option>
        </select>

        <button type="submit">💾 Save Changes</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>