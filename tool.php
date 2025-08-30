<?php
// Ensure this path is correct for your includes folder relative to this file
include 'includes/db.php';

$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    http_response_code(400);
    echo "Invalid URL.";
    exit;
}

// Fetch tool using slug
$stmt = $pdo->prepare("SELECT * FROM tools WHERE slug = ?");
$stmt->execute([$slug]);
$tool = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tool) {
    http_response_code(404);
    echo "Tool not found.";
    exit;
}

// Set page variables
$is_tool_detail_page = true;
$page_title = htmlspecialchars($tool['name']) . ' | ForTheStartups';
$page_description = htmlspecialchars(substr($tool['description'], 0, 155));

// Include your header file
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $page_title; ?></title>
    <meta name="description" content="<?php echo $page_description; ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* General Body and Container */
        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #F8F5F1; /* Light background for the whole site */
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Hero Section */
        .tool-hero {
            background: linear-gradient(135deg, #F0E4D3 0%, #FAF7F3 100%);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }

        .tool-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="none"><path d="M0,60L48,65C95,70,190,80,286,75C381,70,476,50,571,45C667,40,762,50,857,55C952,60,1048,70,1095,75L1143,80L1143,0L1095,0C1048,0,952,0,857,0C762,0,667,0,571,0C476,0,381,0,286,0C190,0,95,0,48,0L0,0Z" fill="rgba(217,162,153,0.1)"/></svg>') no-repeat center top;
            background-size: cover;
        }

        .tool-hero-info {
            position: relative;
            z-index: 2;
        }

        .tool-hero h1 {
            font-size: 2.8rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #8B4A47 0%, #A67B7B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .tool-hero .category-badge {
            display: inline-block;
            background: rgba(217,162,153,0.2);
            border: 1px solid rgba(217,162,153,0.4);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            color: #8B4A47;
            margin-top: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .tool-hero .short-description {
            font-size: 1.2rem;
            color: #6B4E3D;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }

        .tool-main-image {
            max-width: 150px;
            height: auto;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            margin-top: 1.5rem;
            border: 1px solid rgba(220,197,178,0.3);
        }

        .tool-logo-placeholder {
            width: 150px;
            height: 150px;
            background: linear-gradient(135deg, #DCC5B2 0%, #D9A299 100%);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 1.5rem auto 0;
            font-size: 3rem;
            color: #8B4A47;
            font-weight: bold;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            border: 1px solid rgba(220,197,178,0.3);
        }

        /* Content Sections */
        .content-section {
            background: #FAF7F3;
            border-radius: 15px;
            padding: 2.5rem;
            margin-bottom: 2.5rem;
            box-shadow: 0 4px 20px rgba(217,162,153,0.1);
            border: 1px solid rgba(220,197,178,0.3);
            color: #6B4E3D;
        }

        .content-section h2 {
            font-size: 2rem;
            color: #8B4A47;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid rgba(217,162,153,0.3);
        }

        .content-section p {
            margin-bottom: 1rem;
            line-height: 1.7;
        }

        .content-section strong {
            color: #8B4A47;
        }

        .content-section ul {
            list-style: none;
            padding: 0;
            margin-top: 1rem;
        }

        .content-section ul li {
            background: rgba(217,162,153,0.1);
            border-left: 4px solid #D9A299;
            padding: 0.8rem 1.2rem;
            margin-bottom: 0.8rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .content-section ul li::before {
            content: "\f00c"; /* FontAwesome check icon */
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            color: #8B4A47;
            font-size: 0.9rem;
        }

        /* Call to Action */
        .tool-call-to-action {
            text-align: center;
            padding: 3rem;
        }

        .button {
            display: inline-flex;
            align-items: center;
            gap: 0.7rem;
            padding: 1.2rem 2.5rem;
            background: linear-gradient(135deg, #8B4A47 0%, #A67B7B 100%);
            color: #fff;
            text-decoration: none;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
            box-shadow: 0 8px 20px rgba(139, 74, 71, 0.2);
        }

        .button:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 25px rgba(139, 74, 71, 0.3);
            background: linear-gradient(135deg, #A67B7B 0%, #8B4A47 100%);
        }

        .button.disabled {
            background: #ccc;
            cursor: not-allowed;
            box-shadow: none;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #8B4A47;
            text-decoration: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            background: #F0E4D3;
            border: 1px solid rgba(220,197,178,0.3);
            transition: all 0.3s ease;
            font-weight: 500;
            margin-top: 2rem;
        }

        .back-link:hover {
            color: #6B4E3D;
            background: #FAF7F3;
            transform: translateX(-5px);
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .tool-hero {
                padding: 2rem 1rem;
            }

            .tool-hero h1 {
                font-size: 2.2rem;
            }

            .tool-hero .short-description {
                font-size: 1rem;
            }

            .content-section {
                padding: 1.5rem;
            }

            .content-section h2 {
                font-size: 1.8rem;
            }

            .button {
                padding: 1rem 2rem;
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <main class="tool-detail-page-content">
        <section class="tool-hero">
            <div class="tool-hero-info">
                <h1><?php echo htmlspecialchars($tool['name'] ?? 'Tool Name'); ?></h1>

                <?php if (!empty($tool['category'])): ?>
                    <div class="category-badge"><?php echo htmlspecialchars($tool['category']); ?></div>
                <?php endif; ?>

                <p class="short-description">
                    <?php echo htmlspecialchars($tool['description'] ?? 'No description available'); ?>
                </p>

                <?php if (!empty($tool['logo'])): ?>
                    <img src="uploads/<?php echo htmlspecialchars($tool['logo']); ?>"
                         alt="<?php echo htmlspecialchars($tool['name']); ?> Logo"
                         class="tool-main-image"
                         onerror="this.style.display='none';"
                         tabindex="0">
                <?php else: ?>
                    <div class="tool-logo-placeholder" tabindex="0">
                        <span><?php echo !empty($tool['name']) ? strtoupper(substr(htmlspecialchars($tool['name']), 0, 2)) : 'NA'; ?></span>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <section class="tool-description content-section">
            <h2>About <?php echo htmlspecialchars($tool['name'] ?? 'this tool'); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($tool['description'] ?? 'No detailed description available.')); ?></p>

            <?php if (!empty($tool['category'])): ?>
                <p><strong>Category:</strong> <?php echo htmlspecialchars($tool['category']); ?></p>
            <?php endif; ?>

            <?php if (!empty($tool['features'])): ?>
                <p><strong>Key Features:</strong></p>
                <ul>
                    <?php
                    $features = explode("\n", $tool['features']);
                    foreach ($features as $feature):
                        if (!empty(trim($feature))):
                    ?>
                        <li><?php echo htmlspecialchars(trim($feature)); ?></li>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </ul>
            <?php endif; ?>

            <p><strong>Status:</strong>
                <span style="color: <?php echo ($tool['status'] ?? '') === 'approved' ? 'green' : '#B8860B'; ?>;">
                    <?php echo ucfirst(htmlspecialchars($tool['status'] ?? 'unknown')); ?>
                </span>
            </p>

            <?php if (!empty($tool['created_at'])): ?>
                <p><strong>Added:</strong> <?php echo date('F j, Y', strtotime($tool['created_at'])); ?></p>
            <?php endif; ?>

            <?php if (!empty($tool['website'])): ?>
                <p><strong>Website:</strong>
                    <a href="<?php echo htmlspecialchars($tool['website']); ?>" target="_blank" rel="noopener noreferrer">
                        <?php echo htmlspecialchars($tool['website']); ?>
                    </a>
                </p>
            <?php endif; ?>
        </section>

        <section class="tool-call-to-action content-section">
            <?php if (!empty($tool['link'])): ?>
                <a href="<?php echo htmlspecialchars($tool['link']); ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="button">
                    <i class="fas fa-external-link-alt"></i> Visit <?php echo htmlspecialchars($tool['name'] ?? 'Tool'); ?>
                </a>
            <?php else: ?>
                <p class="button disabled">
                    <i class="fas fa-unlink"></i> Visit Link Not Available
                </p>
            <?php endif; ?>

            <div>
                <a href="index.php" class="back-link">
                    <i class="fas fa-arrow-left"></i> Back to All Tools
                </a>
            </div>
        </section>
    </main>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>