<?php
session_start();
include 'includes/db.php';

// Restrict to logged-in users
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

function create_slug($string) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    return $slug;
}

$slug = create_slug($name);


$message = '';
$is_success_message = false; // Flag to determine message type

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $link = trim($_POST['link'] ?? '');
    $logo_filename = '';

    if ($name && $description && $link) {
        // ✅ Generate slug AFTER capturing name
        $slug = create_slug($name);

        // Handle logo upload
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
            $logo_filename = time() . '_' . basename($_FILES['logo']['name']);
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            if (!move_uploaded_file($_FILES['logo']['tmp_name'], $upload_dir . $logo_filename)) {
                $message = "<i class=\"fas fa-exclamation-triangle\"></i> Error uploading logo. Please try again.";
                $is_success_message = false;
            }
        }

        if (empty($message)) {
            try {
                // ✅ Include slug in your INSERT statement
                $stmt = $pdo->prepare("INSERT INTO tools (name, slug, description, category, link, logo, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
                $stmt->execute([$name, $slug, $description, $category, $link, $logo_filename]);

                $message = "<i class=\"fas fa-check-circle\"></i> Thank you! Your tool has been submitted and is awaiting approval.";
                $is_success_message = true;
                $_POST = array();
            } catch (PDOException $e) {
                $message = "<i class=\"fas fa-exclamation-triangle\"></i> A server error occurred during submission. Please try again later.";
                $is_success_message = false;
                // error_log($e->getMessage());
            }
        }
    } else {
        $message = "<i class=\"fas fa-exclamation-triangle\"></i> Please fill in all required fields (Tool Name, Description, Website Link).";
        $is_success_message = false;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Tool - ForTheStartups</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- Global Styles & Variables (Copied for consistency) --- */
        :root {
            /* Color Palette */
            --primary: #AD8B73; /* Earthy Brown */
            --primary-hover: #8B6B4F; /* Darker Earthy Brown */
            --secondary: #FFFBE9; /* Light Cream */
            --dark: #3B3B3B; /* Dark Gray for text */
            --light: #FFFFFF; /* White */
            --accent: #CEAB93; /* Muted Orange-Brown */
            --light-accent: #E3CAA5; /* Lighter Muted Orange-Brown */
            --text-light: rgba(255, 255, 255, 0.9); /* Light text for dark backgrounds */
            --success-bg: #D4EDDA; /* Light green for success */
            --success-text: #155724; /* Dark green for success */
            --error-bg: #F8D7DA; /* Light red for error */
            --error-text: #721C24; /* Dark red for error */

            /* Spacing & Sizing */
            --spacing-xs: 5px;
            --spacing-sm: 10px;
            --spacing-md: 15px;
            --spacing-lg: 20px;
            --spacing-xl: 25px;
            --spacing-xxl: 30px;
            --spacing-xxxl: 40px;

            /* UI Elements */
            --border-radius-sm: 4px;
            --border-radius-md: 8px;
            --border-radius-lg: 12px;
            --border-radius-xl: 16px;
            --border-radius-pill: 50px;
            --box-shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.05);
            --box-shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
            --box-shadow-lg: 0 8px 20px rgba(0, 0, 0, 0.1);

            /* Transitions */
            --transition-fast: all 0.2s ease-out;
            --transition-normal: all 0.3s ease;
        }

        /* --- Base Styles --- */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Fira Sans', 'Droid Sans', 'Helvetica Neue', sans-serif;
            background-color: var(--secondary);
            color: var(--dark);
            line-height: 1.6;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .container {
            width: 90%;
            max-width: 800px; /* Wider container for forms with more fields */
            margin: 2rem auto;
            padding: 0 var(--spacing-lg);
        }

        a {
            color: var(--primary);
            text-decoration: none;
            transition: var(--transition-normal);
        }

        a:hover {
            text-decoration: none;
            color: var(--primary-hover);
        }

        /* --- Page Specific Styles --- */
        h1 {
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: var(--spacing-xxxl);
            color: var(--primary);
            letter-spacing: -0.8px;
        }

        /* Message Styles (Success/Error) */
        .message {
            padding: var(--spacing-md);
            border-radius: var(--border-radius-md);
            margin-bottom: var(--spacing-xxl);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            box-shadow: var(--box-shadow-sm);
        }

        .success-message {
            background-color: var(--success-bg);
            color: var(--success-text);
            border: 1px solid rgba(212, 237, 218, 0.7);
        }

        .error-message {
            background-color: var(--error-bg);
            color: var(--error-text);
            border: 1px solid rgba(245, 198, 203, 0.7);
        }

        .message i {
            font-size: 1.2em;
        }

        /* Submit Tool Form (using .admin-form style for consistency) */
        .submit-tool-form { /* Changed class name for specificity */
            background: var(--light);
            border: 1px solid var(--light-accent);
            padding: var(--spacing-xxxl);
            border-radius: var(--border-radius-xl);
            box-shadow: var(--box-shadow-md);
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--spacing-lg);
        }

        .submit-tool-form label {
            font-weight: 600;
            margin-bottom: var(--spacing-xs);
            display: block;
            color: var(--dark);
            font-size: 0.95rem;
        }

        .submit-tool-form input[type="text"],
        .submit-tool-form input[type="url"],
        .submit-tool-form input[type="file"],
        .submit-tool-form textarea {
            width: 100%;
            padding: var(--spacing-md);
            border: 2px solid var(--light-accent);
            border-radius: var(--border-radius-md);
            font-size: 1rem;
            transition: var(--transition-normal);
            background-color: var(--secondary);
            color: var(--dark);
        }

        .submit-tool-form input[type="text"]:focus,
        .submit-tool-form input[type="url"]:focus,
        .submit-tool-form textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(173, 139, 115, 0.2);
            background-color: var(--light);
        }

        .submit-tool-form textarea {
            min-height: 120px;
            resize: vertical;
        }

        .submit-tool-form button[type="submit"] {
            background-color: var(--primary);
            color: var(--light);
            border: none;
            padding: var(--spacing-md) var(--spacing-xxl);
            border-radius: var(--border-radius-md);
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: var(--transition-normal);
            margin-top: var(--spacing-lg);
            width: auto;
            align-self: start;
            box-shadow: 0 4px 15px rgba(173, 139, 115, 0.3);
        }

        .submit-tool-form button[type="submit"]:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(173, 139, 115, 0.4);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            margin-top: var(--spacing-xxl);
            color: var(--dark);
            font-weight: 500;
            font-size: 1rem;
        }

        .back-link:hover {
            color: var(--primary);
            text-decoration: underline;
        }

        .back-link i {
            margin-right: var(--spacing-xs);
            font-size: 0.9rem;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 0 var(--spacing-md);
            }

            h1 {
                font-size: 2rem;
                margin-bottom: var(--spacing-xl);
            }

            .submit-tool-form {
                padding: var(--spacing-xl);
                border-radius: var(--border-radius-lg);
            }

            .submit-tool-form input[type="text"],
            .submit-tool-form input[type="url"],
            .submit-tool-form input[type="file"],
            .submit-tool-form textarea {
                padding: var(--spacing-sm);
                font-size: 0.95rem;
            }

            .submit-tool-form button[type="submit"] {
                padding: var(--spacing-sm) var(--spacing-lg);
                font-size: 1rem;
                margin-top: var(--spacing-md);
            }
        }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container">
    <h1>Submit Your Startup Tool</h1>

    <?php if ($message): ?>
        <p class="message <?php echo $is_success_message ? 'success-message' : 'error-message'; ?>">
            <?php echo $message; ?>
        </p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="submit-tool-form">
        <label for="name">Tool Name:</label>
        <input type="text" id="name" name="name" required value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">

        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>

        <label for="category">Category:</label>
        <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($_POST['category'] ?? ''); ?>">

        <label for="link">Website Link:</label>
        <input type="url" id="link" name="link" required value="<?php echo htmlspecialchars($_POST['link'] ?? ''); ?>">

        <label for="logo">Logo (optional):</label>
        <input type="file" id="logo" name="logo" accept="image/*">

        <button type="submit">Submit Tool</button>
    </form>

    <a href="index.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Homepage
    </a>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>