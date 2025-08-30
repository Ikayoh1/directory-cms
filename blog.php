<?php
include 'includes/db.php';

// Fetch published posts
$stmt = $pdo->query("SELECT id, title, slug, content, featured_image, created_at 
                     FROM posts 
                     WHERE status = 'published' 
                     ORDER BY created_at DESC");

$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Blog - ForTheStartups | Startup Insights & Resources</title>
    <meta name="description" content="Discover valuable insights, guides, and resources for startups. Stay updated with the latest trends and advice for growing your business.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        /* Reusing variables from header.php for consistency */
        :root {
            --primary: #AD8B73; /* Earthy Brown */
            --primary-hover: #8B6B4F; /* Darker Earthy Brown */
            --secondary: #FFFBE9; /* Light Cream */
            --dark: #3B3B3B; /* Dark Gray for text */
            --light: #FFFFFF; /* White */
            --accent: #CEAB93; /* Muted Orange-Brown */
            --light-accent: #E3CAA5; /* Lighter Muted Orange-Brown */
            --text-light: rgba(255, 255, 255, 0.9); /* Light text for dark backgrounds */
            --spacing-xs: 5px;
            --spacing-sm: 10px;
            --spacing-md: 15px;
            --spacing-lg: 20px;
            --spacing-xl: 25px;
            --spacing-xxl: 30px;
            --spacing-xxxl: 40px;
            --border-radius-sm: 4px;
            --border-radius-md: 8px;
            --border-radius-lg: 12px;
            --box-shadow-md: 0 4px 12px rgba(0, 0, 0, 0.08);
            --box-shadow-lg: 0 8px 20px rgba(0, 0, 0, 0.1);
            --transition-normal: all 0.3s ease;
        }

        .blog-hero {
            background: linear-gradient(135deg, var(--accent) 0%, var(--light-accent) 100%);
            border-radius: var(--border-radius-lg);
            padding: 3rem 2rem;
            text-align: center;
            margin-bottom: var(--spacing-xxxl);
            position: relative;
            overflow: hidden;
            box-shadow: var(--box-shadow-md);
        }

        .blog-hero-content {
            position: relative;
            z-index: 2;
        }

        .blog-hero h1 {
            font-size: 2.8rem;
            margin-bottom: var(--spacing-md);
            color: var(--dark);
            background: -webkit-linear-gradient(135deg, var(--dark) 0%, #5B5B5B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .blog-hero p {
            font-size: 1.2rem;
            color: var(--dark);
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .blog-list {
            max-width: 1200px;
            margin: 0 auto var(--spacing-xxxl) auto;
            padding: 0 var(--spacing-lg);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: var(--spacing-xxl);
        }

        .blog-item {
            background: var(--light);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow-md);
            padding: var(--spacing-xxl);
            transition: var(--transition-normal);
            border: 1px solid rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .blog-item:hover {
            transform: translateY(-5px);
            box-shadow: var(--box-shadow-lg);
        }

        .blog-item h2 {
            font-size: 1.8rem;
            margin-bottom: var(--spacing-sm);
            line-height: 1.3;
        }

        .blog-item h2 a {
            text-decoration: none;
            color: var(--primary);
            transition: var(--transition-normal);
        }

        .blog-item h2 a:hover {
            color: var(--primary-hover);
        }

        .blog-excerpt {
            font-size: 1rem;
            color: var(--dark);
            opacity: 0.8;
            margin-bottom: var(--spacing-lg);
            line-height: 1.6;
            flex-grow: 1; /* Allows excerpt to take available space */
        }

        .blog-item .button {
            display: inline-block;
            padding: var(--spacing-sm) var(--spacing-lg);
            background-color: var(--primary);
            color: var(--light);
            text-decoration: none;
            border-radius: var(--border-radius-md);
            transition: var(--transition-normal);
            font-weight: 500;
            text-align: center;
            align-self: flex-start; /* Aligns button to the left */
        }

        .blog-item .button:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: var(--light);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--box-shadow-md);
            margin: var(--spacing-xxxl) auto;
            max-width: 600px;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }

        .empty-state p {
            font-size: 1.1rem;
            color: var(--dark);
            opacity: 0.8;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .blog-hero {
                padding: 2rem 1rem;
                margin-bottom: var(--spacing-xxl);
            }

            .blog-hero h1 {
                font-size: 2rem;
            }

            .blog-hero p {
                font-size: 1rem;
            }

            .blog-list {
                grid-template-columns: 1fr;
                gap: var(--spacing-xl);
                padding: 0 var(--spacing-md);
            }

            .blog-item {
                padding: var(--spacing-xl);
            }

            .blog-item h2 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
<main class="container">
    <div class="blog-hero">
        <div class="blog-hero-content">
            <h1>Startup Insights & Resources</h1>
            <p>
                Dive into our collection of articles, guides, and tips designed to help
                startups thrive. Learn about industry trends, best practices, and innovative strategies.
            </p>
        </div>
    </div>

    <div class="blog-list">
        <?php if (empty($posts)): ?>
            <div class="empty-state">
                <p>No blog posts yet. Check back soon!</p>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <article class="blog-item">
                    <div>
                        <h2>
                            <a href="/blog/<?php echo htmlspecialchars($post['slug']); ?>">
                                <?php echo htmlspecialchars($post['title']); ?>
                            </a>
                        </h2>

                        <?php if (!empty($post['featured_image'])): ?>
                            <img src="/uploads/<?php echo htmlspecialchars($post['featured_image']); ?>"
                                 alt="<?php echo htmlspecialchars($post['title']); ?>"
                                 style="max-width: 100%; height: auto; border-radius: var(--border-radius-md); margin-bottom: var(--spacing-md);">
                        <?php endif; ?>

                        <p class="blog-excerpt">
                            <?php
                            $excerpt = strip_tags($post['content']);
                            echo substr($excerpt, 0, 200) . '...';
                            ?>
                        </p>
                    </div>
                    <a href="/blog/<?php echo htmlspecialchars($post['slug']); ?>" class="button">
                        Read More
                    </a>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>