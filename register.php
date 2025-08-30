<?php
include 'includes/db.php';

$message = '';
$is_success_message = false; // Flag to determine message type

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username && $email && $password) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            // Check if username or email already exists
            $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
            $check_stmt->execute([$username, $email]);
            $count = $check_stmt->fetchColumn();

            if ($count > 0) {
                $message = "<i class=\"fas fa-exclamation-triangle\"></i> Username or email already exists. Please choose another.";
                $is_success_message = false;
            } else {
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password]);
                $message = "<i class=\"fas fa-check-circle\"></i> Registration successful! You can now log in.";
                $is_success_message = true;
                // Clear form fields after successful registration
                $_POST = array(); 
            }
        } catch (PDOException $e) {
            // Generic error for database issues, avoid exposing specific DB errors
            $message = "<i class=\"fas fa-exclamation-triangle\"></i> A server error occurred during registration. Please try again later.";
            $is_success_message = false;
            // In a real application, you'd log $e->getMessage() for debugging.
        }
    } else {
        $message = "<i class=\"fas fa-exclamation-triangle\"></i> Please fill in all required fields.";
        $is_success_message = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - ForTheStartups</title>
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
            max-width: 500px; /* Consistent with login page for form width */
            margin: 3rem auto;
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

        /* Message Styles (Success/Error) - Reusing .message class */
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

        /* Register Form (using .login-form class for consistency in style) */
        .register-form { /* New class name for specificity */
            background: var(--light);
            border: 1px solid var(--light-accent);
            padding: var(--spacing-xxxl);
            border-radius: var(--border-radius-xl);
            box-shadow: var(--box-shadow-md);
            display: grid;
            grid-template-columns: 1fr;
            gap: var(--spacing-lg);
        }

        .register-form label {
            font-weight: 600;
            margin-bottom: var(--spacing-xs);
            display: block;
            color: var(--dark);
            font-size: 0.95rem;
        }

        .register-form input[type="text"],
        .register-form input[type="email"], /* Added email input type */
        .register-form input[type="password"] {
            width: 100%;
            padding: var(--spacing-md);
            border: 2px solid var(--light-accent);
            border-radius: var(--border-radius-md);
            font-size: 1rem;
            transition: var(--transition-normal);
            background-color: var(--secondary);
            color: var(--dark);
        }

        .register-form input[type="text"]:focus,
        .register-form input[type="email"]:focus,
        .register-form input[type="password"]:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(173, 139, 115, 0.2);
            background-color: var(--light);
        }

        .register-form button[type="submit"] {
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

        .register-form button[type="submit"]:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(173, 139, 115, 0.4);
        }

        .login-link { /* Class for the login link below the register form */
            display: block;
            text-align: center;
            margin-top: var(--spacing-xxl);
            color: var(--dark);
            font-weight: 500;
            font-size: 1rem;
            text-decoration: underline;
            transition: color 0.3s ease;
        }

        .login-link:hover {
            color: var(--primary);
            text-decoration: none;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                padding: 0 var(--spacing-md);
                margin: 2rem auto;
            }

            h1 {
                font-size: 2rem;
                margin-bottom: var(--spacing-xl);
            }

            .register-form {
                padding: var(--spacing-xl);
                border-radius: var(--border-radius-lg);
            }

            .register-form input[type="text"],
            .register-form input[type="email"],
            .register-form input[type="password"] {
                padding: var(--spacing-sm);
                font-size: 0.95rem;
            }

            .register-form button[type="submit"] {
                padding: var(--spacing-sm) var(--spacing-lg);
                font-size: 1rem;
                margin-top: var(--spacing-md);
            }

            .login-link {
                margin-top: var(--spacing-xl);
            }
        }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="container">
    <h1>Create Your Account</h1>

    <?php if ($message): ?>
        <p class="message <?php echo $is_success_message ? 'success-message' : 'error-message'; ?>">
            <?php echo $message; ?>
        </p>
    <?php endif; ?>

    <form method="POST" class="register-form">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required autocomplete="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>">

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required autocomplete="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required autocomplete="new-password">

        <button type="submit">Register</button>
    </form>

    <a href="login.php" class="login-link">Already have an account? **Login here.**</a>
</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>