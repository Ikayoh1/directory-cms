<?php include 'includes/header.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>About Us - ForTheStartups | Empowering Tomorrow's Builders</title>
    <meta name="description" content="Learn about ForTheStartups' mission to empower founders and entrepreneurs with curated startup tools. Discover how we're building the future of startup success.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .about-hero {
            background: linear-gradient(135deg, #F0E4D3 0%, #FAF7F3 100%);
            border-radius: 15px;
            padding: 3rem 2rem;
            text-align: center;
            margin-bottom: 3rem;
            position: relative;
            overflow: hidden;
        }
        
        .about-hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 100" fill="none"><path d="M0,20L48,25C95,30,190,40,286,45C381,50,476,50,571,40C667,30,762,10,857,15C952,20,1048,50,1095,65L1143,80L1143,0L1095,0C1048,0,952,0,857,0C762,0,667,0,571,0C476,0,381,0,286,0C190,0,95,0,48,0L0,0Z" fill="rgba(217,162,153,0.2)"/></svg>') no-repeat center top;
            background-size: cover;
            opacity: 0.3;
        }
        
        .about-hero-content {
            position: relative;
            z-index: 2;
        }
        
        .about-title {
            font-size: 2.5rem;
            margin-bottom: 1rem;
            background: linear-gradient(135deg, #8B4A47 0%, #A67B7B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .about-subtitle {
            font-size: 1.2rem;
            opacity: 0.8;
            max-width: 600px;
            margin: 0 auto 2rem;
            line-height: 1.6;
            color: #6B4E3D;
        }
        
        .mission-badge {
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
        
        .content-section {
            background: #FAF7F3;
            border-radius: 15px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(217,162,153,0.15);
            border: 1px solid rgba(220,197,178,0.3);
        }
        
        .section-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .section-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #D9A299 0%, #DCC5B2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8B4A47;
            font-size: 1.5rem;
        }
        
        .section-title {
            margin: 0;
            font-size: 1.8rem;
            color: #6B4E3D;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin: 2rem 0;
        }
        
        .feature-card {
            background: #F0E4D3;
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(220,197,178,0.3);
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(217,162,153,0.2);
            background: #FAF7F3;
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #DCC5B2 0%, #D9A299 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: #8B4A47;
            font-size: 1.8rem;
        }
        
        .feature-title {
            font-size: 1.3rem;
            margin-bottom: 1rem;
            color: #6B4E3D;
        }
        
        .feature-description {
            line-height: 1.6;
            opacity: 0.8;
            color: #6B4E3D;
        }
        
        .values-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            list-style: none;
            padding: 0;
            margin: 2rem 0;
        }
        
        .values-list li {
            background: #F0E4D3;
            border-radius: 10px;
            padding: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        
        .values-list li:hover {
            border-left-color: #D9A299;
            transform: translateX(5px);
            background: #FAF7F3;
        }
        
        .values-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #DCC5B2 0%, #D9A299 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #8B4A47;
            flex-shrink: 0;
            font-size: 1.2rem;
        }
        
        .values-content {
            flex: 1;
        }
        
        .values-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #6B4E3D;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }
        
        .stat-card {
            text-align: center;
            background: #F0E4D3;
            border-radius: 10px;
            padding: 1.5rem 1rem;
            transition: all 0.3s ease;
            border: 1px solid rgba(220,197,178,0.3);
        }
        
        .stat-card:hover {
            background: #FAF7F3;
            transform: scale(1.05);
        }
        
        .stat-number {
            font-size: 2.2rem;
            font-weight: bold;
            color: #D9A299;
            display: block;
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            font-size: 0.9rem;
            opacity: 0.8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6B4E3D;
        }
        
        .cta-section {
            background: linear-gradient(135deg, #D9A299 0%, #DCC5B2 100%);
            color: #8B4A47;
            border-radius: 15px;
            padding: 3rem 2rem;
            text-align: center;
            margin: 3rem 0;
        }
        
        .cta-title {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: #6B4E3D;
        }
        
        .cta-description {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            color: #6B4E3D;
        }
        
        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .cta-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem 2rem;
            background: rgba(250,247,243,0.3);
            border: 2px solid rgba(250,247,243,0.5);
            color: #6B4E3D;
            text-decoration: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .cta-button:hover {
            background: rgba(250,247,243,0.5);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139,74,71,0.2);
        }
        
        .cta-button.primary {
            background: #FAF7F3;
            color: #8B4A47;
            border-color: #FAF7F3;
        }
        
        .cta-button.primary:hover {
            background: rgba(250,247,243,0.9);
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #8B4A47;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border-radius: 25px;
            background: #F0E4D3;
            border: 1px solid rgba(220,197,178,0.3);
            transition: all 0.3s ease;
            margin-top: 2rem;
        }
        
        .back-link:hover {
            color: #6B4E3D;
            background: #FAF7F3;
            transform: translateX(-5px);
        }
        
        .team-mention {
            background: rgba(220,197,178,0.3);
            border: 1px solid rgba(217,162,153,0.4);
            border-radius: 10px;
            padding: 1.5rem;
            margin: 2rem 0;
            text-align: center;
        }
        
        .team-mention strong {
            color: #8B4A47;
        }
        
        /* General text colors */
        body {
            color: #6B4E3D;
        }
        
        p {
            color: #6B4E3D;
        }
        
        @media (max-width: 768px) {
            .about-hero {
                padding: 2rem 1rem;
            }
            
            .about-title {
                font-size: 2rem;
            }
            
            .content-section {
                padding: 2rem 1.5rem;
            }
            
            .feature-grid {
                grid-template-columns: 1fr;
            }
            
            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .values-list li {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
        }
        
        @media (prefers-reduced-motion: reduce) {
            * {
                animation: none !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <!-- Hero Section -->
    <div class="about-hero">
        <div class="about-hero-content">
            <h1 class="about-title">About ForTheStartups</h1>
            <p class="about-subtitle">
                Empowering founders, entrepreneurs, and makers with carefully curated tools 
                that transform ideas into successful ventures. We're not just a directory—we're 
                your strategic partner in building the future.
            </p>
            <div class="mission-badge">
                <i class="fas fa-rocket" aria-hidden="true"></i>
                <span>Empowering Tomorrow's Builders</span>
            </div>
        </div>
    </div>

    <!-- Our Story Section -->
    <div class="content-section">
        <div class="section-header">
            <div class="section-icon">
                <i class="fas fa-book-open" aria-hidden="true"></i>
            </div>
            <h2 class="section-title">Our Story</h2>
        </div>
        <p style="font-size: 1.1rem; line-height: 1.7; margin-bottom: 1.5rem;">
            ForTheStartups.com by iRedylabs Technologies was born from a simple frustration: <strong>why do founders spend countless hours 
            searching for the right tools instead of building their dreams?</strong>
        </p>
        <p style="line-height: 1.7; margin-bottom: 1.5rem;">
            We've walked in your shoes. We've felt the overwhelm of choosing between hundreds of marketing 
            platforms, project management tools, and development frameworks. That's why we created a different 
            kind of directory—one that prioritizes quality over quantity, community over commerce.
        </p>
        <div class="team-mention">
            <p><strong>Every tool in our directory is manually reviewed by our team of founders and makers.</strong> 
            We don't just list tools; we curate experiences that drive real results.</p>
        </div>
    </div>

    <!-- What Makes Us Different -->
    <div class="content-section">
        <div class="section-header">
            <div class="section-icon">
                <i class="fas fa-star" aria-hidden="true"></i>
            </div>
            <h2 class="section-title">What Makes Us Different</h2>
        </div>
        
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-user-check" aria-hidden="true"></i>
                </div>
                <h3 class="feature-title">Human-Curated</h3>
                <p class="feature-description">
                    Every tool goes through manual review by experienced founders. No bots, no algorithms—just real people who understand what works.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users" aria-hidden="true"></i>
                </div>
                <h3 class="feature-title">Community-Driven</h3>
                <p class="feature-description">
                    Our recommendations come from real founders who've used these tools to build successful companies. Authentic reviews, honest feedback.
                </p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-globe-africa" aria-hidden="true"></i>
                </div>
                <h3 class="feature-title">Global Focus</h3>
                <p class="feature-description">
                    Special emphasis on showcasing innovative tools from underserved markets, especially Africa and emerging startup ecosystems.
                </p>
            </div>
        </div>
    </div>

    <!-- Our Values -->
    <div class="content-section">
        <div class="section-header">
            <div class="section-icon">
                <i class="fas fa-heart" aria-hidden="true"></i>
            </div>
            <h2 class="section-title">Why We Exist</h2>
        </div>
        
        <ul class="values-list">
            <li>
                <div class="values-icon">
                    <i class="fas fa-clock" aria-hidden="true"></i>
                </div>
                <div class="values-content">
                    <div class="values-title">Save Founders Time</div>
                    <p>Reduce the weeks founders waste researching tools so they can focus on what matters: building their vision.</p>
                </div>
            </li>
            
            <li>
                <div class="values-icon">
                    <i class="fas fa-lightbulb" aria-hidden="true"></i>
                </div>
                <div class="values-content">
                    <div class="values-title">Champion Innovation</div>
                    <p>Showcase breakthrough products by small teams, indie makers, and overlooked creators who are changing the game.</p>
                </div>
            </li>
            
            <li>
                <div class="values-icon">
                    <i class="fas fa-seedling" aria-hidden="true"></i>
                </div>
                <div class="values-content">
                    <div class="values-title">Foster Ecosystems</div>
                    <p>Build thriving startup communities, especially in underserved markets where great ideas need better platforms.</p>
                </div>
            </li>
            
            <li>
                <div class="values-icon">
                    <i class="fas fa-handshake" aria-hidden="true"></i>
                </div>
                <div class="values-content">
                    <div class="values-title">Enable Success</div>
                    <p>Connect the right tools with the right founders at the right time to maximize their chances of success.</p>
                </div>
            </li>
        </ul>
    </div>

    <!-- Stats Section -->
    <div class="content-section">
        <div class="section-header">
            <div class="section-icon">
                <i class="fas fa-chart-line" aria-hidden="true"></i>
            </div>
            <h2 class="section-title">Impact by Numbers</h2>
        </div>
        
        <div class="stats-grid">
            <div class="stat-card">
                <span class="stat-number">500+</span>
                <span class="stat-label">Curated Tools</span>
            </div>
            <div class="stat-card">
                <span class="stat-number">50+</span>
                <span class="stat-label">Categories</span>
            </div>
            <div class="stat-card">
                <span class="stat-number">100%</span>
                <span class="stat-label">Free Access</span>
            </div>
            <div class="stat-card">
                <span class="stat-number">24h</span>
                <span class="stat-label">Review Time</span>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="cta-section">
        <h2 class="cta-title">Ready to Join Our Community?</h2>
        <p class="cta-description">
            Whether you're looking for the perfect tool for your startup or you've built something amazing 
            that other founders need to know about, we'd love to have you join our community.
        </p>
        <div class="cta-buttons">
            <a href="submit_tool.php" class="cta-button primary">
                <i class="fas fa-plus" aria-hidden="true"></i>
                Submit Your Tool
            </a>
            <a href="index.php" class="cta-button">
                <i class="fas fa-search" aria-hidden="true"></i>
                Explore Tools
            </a>
        </div>
    </div>

    <a href="index.php" class="back-link">
        <i class="fas fa-arrow-left" aria-hidden="true"></i>
        Back to Home
    </a>
</div>

<?php include 'includes/footer.php'; ?>
</body>
</html>