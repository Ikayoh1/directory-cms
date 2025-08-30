<?php
include 'includes/db.php';
include 'includes/header.php';

// Get categories with tool counts
$stmt = $pdo->query("
    SELECT 
        category, 
        COUNT(*) as tool_count 
    FROM tools 
    WHERE category IS NOT NULL AND category != '' 
    GROUP BY category 
    ORDER BY tool_count DESC, category ASC
");
$categories = $stmt->fetchAll();

// Get total tools count
$total_stmt = $pdo->query("SELECT COUNT(*) as total FROM tools WHERE category IS NOT NULL AND category != ''");
$total_tools = $total_stmt->fetch()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Browse Categories - ForTheStartups | Discover Tools by Category</title>
    <meta name="description" content="Explore startup tools organized by category. Find the perfect tools for marketing, development, productivity, design, and more to grow your business.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .categories-hero {
            background: linear-gradient(135deg, #F0E4D3 0%, #FAF7F3 100%);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }
        
        .categories-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="none"><path d="M0,60L48,65C95,70,190,80,286,75C381,70,476,50,571,45C667,40,762,50,857,55C952,60,1048,70,1095,75L1143,80L1143,0L1095,0C1048,0,952,0,857,0C762,0,667,0,571,0C476,0,381,0,286,0C190,0,95,0,48,0L0,0Z" fill="rgba(217,162,153,0.1)"/></svg>') no-repeat center top;
            background-size: cover;
        }
        
        .categories-hero-content {
            position: relative;
            z-index: 2;
        }
        
        .categories-title {
            font-size: 2.8rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #8B4A47 0%, #A67B7B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .categories-subtitle {
            font-size: 1.2rem;
            color: #6B4E3D;
            opacity: 0.9;
            max-width: 600px;
            margin: 0 auto 2rem;
            line-height: 1.6;
        }
        
        .stats-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(217,162,153,0.2);
            border: 1px solid rgba(217,162,153,0.4);
            padding: 0.75rem 1.5rem;
            border-radius: 30px;
            font-weight: 600;
            color: #8B4A47;
            margin-top: 1rem;
        }
        
        .search-section {
            background: #FAF7F3;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 3rem;
            box-shadow: 0 4px 20px rgba(217,162,153,0.1);
            border: 1px solid rgba(220,197,178,0.3);
        }
        
        .search-container {
            display: flex;
            gap: 1rem;
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }
        
        .search-input {
            flex: 1;
            padding: 1rem 1rem 1rem 3rem;
            border: 2px solid rgba(220,197,178,0.3);
            border-radius: 50px;
            font-size: 1rem;
            background: #F0E4D3;
            color: #6B4E3D;
            transition: all 0.3s ease;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #D9A299;
            box-shadow: 0 0 0 3px rgba(217,162,153,0.2);
        }
        
        .search-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #8B4A47;
            font-size: 1.1rem;
        }
        
        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .category-card {
            background: #FAF7F3;
            border-radius: 15px;
            padding: 2rem;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            border: 1px solid rgba(220,197,178,0.3);
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
        
        .category-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #D9A299 0%, #DCC5B2 100%);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }
        
        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(217,162,153,0.2);
            background: #F0E4D3;
        }
        
        .category-card:hover::before {
            transform: scaleX(1);
        }
        
        .category-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #DCC5B2 0%, #D9A299 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            color: #8B4A47;
        }
        
        .category-name {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #6B4E3D;
        }
        
        .category-count {
            font-size: 0.9rem;
            color: #8B4A47;
            opacity: 0.8;
            margin-bottom: 1rem;
        }
        
        .category-description {
            font-size: 0.9rem;
            color: #6B4E3D;
            opacity: 0.7;
            line-height: 1.4;
        }
        
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background: #FAF7F3;
            border-radius: 15px;
            border: 1px solid rgba(220,197,178,0.3);
        }
        
        .empty-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #DCC5B2 0%, #D9A299 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 2rem;
            color: #8B4A47;
        }
        
        .empty-title {
            font-size: 1.5rem;
            color: #6B4E3D;
            margin-bottom: 1rem;
        }
        
        .empty-description {
            color: #8B4A47;
            opacity: 0.8;
            max-width: 400px;
            margin: 0 auto;
        }
        
        .back-section {
            text-align: center;
            margin-top: 3rem;
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
        }
        
        .back-link:hover {
            color: #6B4E3D;
            background: #FAF7F3;
            transform: translateX(-5px);
        }
        
        .quick-actions {
            background: linear-gradient(135deg, #DCC5B2 0%, #D9A299 100%);
            border-radius: 15px;
            padding: 2.5rem;
            text-align: center;
            margin: 3rem 0;
        }
        
        .quick-actions-title {
            font-size: 1.5rem;
            color: #6B4E3D;
            margin-bottom: 1rem;
        }
        
        .quick-actions-description {
            color: #6B4E3D;
            opacity: 0.9;
            margin-bottom: 2rem;
        }
        
        .quick-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .quick-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 1.5rem;
            background: rgba(250,247,243,0.3);
            border: 2px solid rgba(250,247,243,0.5);
            color: #6B4E3D;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .quick-button:hover {
            background: rgba(250,247,243,0.6);
            transform: translateY(-2px);
        }
        
        .quick-button.primary {
            background: #FAF7F3;
            border-color: #FAF7F3;
            color: #8B4A47;
        }
        
        .quick-button.primary:hover {
            background: rgba(250,247,243,0.9);
        }
        
        @media (max-width: 768px) {
            .categories-hero {
                padding: 2rem 1rem;
            }
            
            .categories-title {
                font-size: 2.2rem;
            }
            
            .search-container {
                flex-direction: column;
            }
            
            .categories-grid {
                grid-template-columns: 1fr;
                gap: 1rem;
            }
            
            .category-card {
                padding: 1.5rem;
            }
            
            .quick-buttons {
                flex-direction: column;
                align-items: center;
            }
        }
        
        .category-filter {
            opacity: 0;
            animation: fadeIn 0.3s ease forwards;
        }
        
        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }
        
        .hidden {
            display: none;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Hero Section -->
    <div class="categories-hero">
        <div class="categories-hero-content">
            <h1 class="categories-title">Explore Categories</h1>
            <p class="categories-subtitle">
                Discover the perfect tools for your startup organized by category. 
                From marketing to development, find everything you need to build and grow.
            </p>
            <div class="stats-badge">
                <i class="fas fa-layer-group" aria-hidden="true"></i>
                <span><?php echo count($categories); ?> Categories • <?php echo $total_tools; ?> Tools</span>
            </div>
        </div>
    </div>

    <!-- Search Section -->
    <div class="search-section">
        <div class="search-container">
            <i class="fas fa-search search-icon" aria-hidden="true"></i>
            <input 
                type="text" 
                class="search-input" 
                placeholder="Search categories..."
                id="categorySearch"
                autocomplete="off"
            >
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="categories-grid" id="categoriesGrid">
        <?php
        if ($categories) {
            // Category icon mapping
            $category_icons = [
                'Marketing' => 'fas fa-bullhorn',
                'Development' => 'fas fa-code',
                'Design' => 'fas fa-paint-brush',
                'Productivity' => 'fas fa-tasks',
                'Analytics' => 'fas fa-chart-bar',
                'Communication' => 'fas fa-comments',
                'Finance' => 'fas fa-dollar-sign',
                'Sales' => 'fas fa-handshake',
                'Project Management' => 'fas fa-project-diagram',
                'Social Media' => 'fas fa-share-alt',
                'Email' => 'fas fa-envelope',
                'CRM' => 'fas fa-users',
                'E-commerce' => 'fas fa-shopping-cart',
                'Security' => 'fas fa-shield-alt',
                'HR' => 'fas fa-user-tie',
                'Legal' => 'fas fa-gavel',
                'Hosting' => 'fas fa-server',
                'Testing' => 'fas fa-bug',
                'Automation' => 'fas fa-robot',
                'AI' => 'fas fa-brain'
            ];
            
            // Category descriptions
            $category_descriptions = [
                'Marketing' => 'Promote your startup and reach your audience',
                'Development' => 'Build and deploy your product',
                'Design' => 'Create beautiful user experiences',
                'Productivity' => 'Streamline workflows and boost efficiency',
                'Analytics' => 'Track performance and gain insights',
                'Communication' => 'Connect with team and customers',
                'Finance' => 'Manage money and financial operations',
                'Sales' => 'Convert leads and grow revenue',
                'Project Management' => 'Organize tasks and manage projects',
                'Social Media' => 'Build your online presence',
                'Email' => 'Effective email marketing and communication',
                'CRM' => 'Manage customer relationships',
                'E-commerce' => 'Sell products and services online',
                'Security' => 'Protect your business and data',
                'HR' => 'Manage people and hiring',
                'Legal' => 'Handle legal and compliance needs',
                'Hosting' => 'Deploy and host your applications',
                'Testing' => 'Quality assurance and testing tools',
                'Automation' => 'Automate repetitive tasks',
                'AI' => 'Leverage artificial intelligence'
            ];
            
            foreach ($categories as $cat) {
                $category_name = htmlspecialchars($cat['category']);
                $tool_count = $cat['tool_count'];
                $icon = isset($category_icons[$category_name]) ? $category_icons[$category_name] : 'fas fa-folder';
                $description = isset($category_descriptions[$category_name]) ? $category_descriptions[$category_name] : 'Explore tools in this category';
                $tool_text = $tool_count == 1 ? 'tool' : 'tools';
                
                echo '<a class="category-card category-filter" href="index.php?category=' . urlencode($category_name) . '" data-category="' . strtolower($category_name) . '">';
                echo '<div class="category-icon"><i class="' . $icon . '" aria-hidden="true"></i></div>';
                echo '<div class="category-name">' . $category_name . '</div>';
                echo '<div class="category-count">' . $tool_count . ' ' . $tool_text . '</div>';
                echo '<div class="category-description">' . $description . '</div>';
                echo '</a>';
            }
        } else {
            echo '<div class="empty-state">';
            echo '<div class="empty-icon"><i class="fas fa-folder-open" aria-hidden="true"></i></div>';
            echo '<div class="empty-title">No Categories Yet</div>';
            echo '<div class="empty-description">Categories will appear here once tools are added to the directory.</div>';
            echo '</div>';
        }
        ?>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h2 class="quick-actions-title">Can't Find What You're Looking For?</h2>
        <p class="quick-actions-description">
            Submit your favorite tool or browse all tools to discover hidden gems.
        </p>
        <div class="quick-buttons">
            <a href="submit_tool.php" class="quick-button primary">
                <i class="fas fa-plus" aria-hidden="true"></i>
                Submit a Tool
            </a>
            <a href="index.php" class="quick-button">
                <i class="fas fa-th-large" aria-hidden="true"></i>
                Browse All Tools
            </a>
        </div>
    </div>

    <!-- Back Link -->
    <div class="back-section">
        <a href="index.php" class="back-link">
            <i class="fas fa-arrow-left" aria-hidden="true"></i>
            Back to Home
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('categorySearch');
    const categoryCards = document.querySelectorAll('.category-filter');
    
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        
        categoryCards.forEach(card => {
            const categoryName = card.getAttribute('data-category');
            const categoryText = card.textContent.toLowerCase();
            
            if (categoryName.includes(searchTerm) || categoryText.includes(searchTerm)) {
                card.style.display = 'flex';
                card.style.opacity = '0';
                setTimeout(() => {
                    card.style.opacity = '1';
                }, 50);
            } else {
                card.style.display = 'none';
            }
        });
        
        // Check if any categories are visible
        const visibleCards = Array.from(categoryCards).filter(card => 
            card.style.display !== 'none'
        );
        
        // You could add a "no results" message here if needed
    });
});
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>