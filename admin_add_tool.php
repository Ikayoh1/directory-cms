<?php
include 'includes/db.php';


session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}


// Handle form submission
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $category = $_POST['category'] ?? '';
    $link = $_POST['link'] ?? '';
    $logo_filename = '';

    // Handle logo upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
        $logo_filename = time() . '_' . basename($_FILES['logo']['name']);
        // Ensure 'uploads/' directory exists and is writable
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        move_uploaded_file($_FILES['logo']['tmp_name'], $upload_dir . $logo_filename);
    }

    // Save to database
    try {
        $stmt = $pdo->prepare("INSERT INTO tools (name, description, category, link, logo) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $description, $category, $link, $logo_filename]);
        $message = "<i class=\"fas fa-check-circle\"></i> Tool added successfully!";
        // Clear form fields after successful submission (optional, but good UX)
        $_POST = array(); 
    } catch (PDOException $e) {
        $message = "<i class=\"fas fa-exclamation-triangle\"></i> Error adding tool: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Tool - ForTheStartups</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* --- Global Styles & Variables (Copied from previous response for consistency) --- */
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
            max-width: 800px; /* Slightly narrower container for forms */
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


        /* Admin Form */
        .admin-form {
            background: var(--light);
            border: 1px solid var(--light-accent);
            padding: var(--spacing-xxxl);
            border-radius: var(--border-radius-xl);
            box-shadow: var(--box-shadow-md);
            display: grid;
            grid-template-columns: 1fr; /* Single column layout */
            gap: var(--spacing-lg); /* Space between form fields */
        }

        .admin-form label {
            font-weight: 600;
            margin-bottom: var(--spacing-xs);
            display: block; /* Ensures label is on its own line */
            color: var(--dark);
            font-size: 0.95rem;
        }

        .admin-form input[type="text"],
        .admin-form input[type="url"],
        .admin-form input[type="file"],
        .admin-form textarea {
            width: 100%; /* Full width inputs */
            padding: var(--spacing-md);
            border: 2px solid var(--light-accent);
            border-radius: var(--border-radius-md);
            font-size: 1rem;
            transition: var(--transition-normal);
            background-color: var(--secondary); /* Light cream background for inputs */
            color: var(--dark);
        }

        .admin-form input[type="text"]:focus,
        .admin-form input[type="url"]:focus,
        .admin-form textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(173, 139, 115, 0.2); /* Focus ring with primary color */
            background-color: var(--light); /* White background on focus */
        }

        .admin-form textarea {
            min-height: 120px; /* Taller textarea */
            resize: vertical; /* Allow vertical resizing */
        }

        .admin-form button[type="submit"] {
            background-color: var(--primary);
            color: var(--light);
            border: none;
            padding: var(--spacing-md) var(--spacing-xxl);
            border-radius: var(--border-radius-md);
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: var(--transition-normal);
            margin-top: var(--spacing-lg); /* Space above button */
            width: auto; /* Button width adapts to content */
            align-self: start; /* Align button to the start if using grid */
            box-shadow: 0 4px 15px rgba(173, 139, 115, 0.3);
        }

        .admin-form button[type="submit"]:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(173, 139, 115, 0.4);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            margin-top: var(--spacing-xxl); /* More space above back link */
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

            .admin-form {
                padding: var(--spacing-xl);
                border-radius: var(--border-radius-lg);
            }

            .admin-form input[type="text"],
            .admin-form input[type="url"],
            .admin-form input[type="file"],
            .admin-form textarea {
                padding: var(--spacing-sm);
                font-size: 0.95rem;
            }

            .admin-form button[type="submit"] {
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
    <h1>Add New Startup Tool</h1>

    <?php if ($message): ?>
        <p class="message <?php echo strpos($message, 'Error') !== false ? 'error-message' : 'success-message'; ?>">
            <?php echo $message; ?>
        </p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="admin-form">
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

        <button type="submit">Add Tool</button>
    </form>

    <a href="index.php" class="back-link">
        <i class="fas fa-arrow-left"></i> Back to Homepage
    </a>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>