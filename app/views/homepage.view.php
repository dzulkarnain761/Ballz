<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ballz | Bite-Sized Happiness</title>
    <meta name="description" content="Ballz - Bite-Sized Happiness. Delicious savory and sweet balls for every craving. Order now or download our app!">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Iconify -->
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    <link rel="stylesheet" href="<?= ROOT ?>/public/css/styles.css">
</head>
<body>
    <header class="header">
        <div class="container header-container">
            <a href="#" class="logo">
                <img src="<?= ROOT ?>/public/assets/logo-dark-removebg-preview.png" alt="Ballz" class="logo-img logo-img-dark">
                <img src="<?= ROOT ?>/public/assets/logo-light-removebg-preview.png" alt="Ballz" class="logo-img logo-img-light">
            </a>
            <nav class="nav">
                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Open Menu">
                    <iconify-icon icon="material-symbols:menu-rounded"></iconify-icon>
                </button>
                <ul class="nav-list" id="navList">
                    <li><a href="#menu">Menu</a></li>
                    <li><a href="#app">Get App</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="<?= ROOT ?>/dashboard/index">Dashboard</a></li>
                    <li><button id="theme-toggle" class="theme-toggle" aria-label="Toggle Dark Mode"><iconify-icon icon="material-symbols:dark-mode-outline"></iconify-icon></button></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-bg-shapes">
                <div class="hero-blob hero-blob-1"></div>
                <div class="hero-blob hero-blob-2"></div>
                <div class="hero-blob hero-blob-3"></div>
            </div>
            <div class="container hero-content">
                <div class="hero-badge">
                    <iconify-icon icon="material-symbols:local-fire-department-rounded"></iconify-icon>
                    <span>Malaysia's Favourite Bite-Sized Snack</span>
                </div>
                <h1 class="hero-title">Bite-Sized<br><span class="highlight">Happiness</span></h1>
                <p class="hero-subtitle">Crispy on the outside, bursting with flavour on the inside. Savory or sweet — we've got the perfect ball for every craving.</p>
                <div class="hero-actions">
                    <a href="#menu" class="btn btn-primary btn-lg">
                        <iconify-icon icon="material-symbols:restaurant-menu-rounded"></iconify-icon>
                        Explore Menu
                    </a>
                    <a href="#app" class="btn btn-outline btn-lg">
                        <iconify-icon icon="material-symbols:download-rounded"></iconify-icon>
                        Get the App
                    </a>
                </div>
                <div class="hero-stats">
                    <div class="hero-stat">
                        <strong>15+</strong>
                        <span>Flavours</span>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <strong>50K+</strong>
                        <span>Happy Customers</span>
                    </div>
                    <div class="hero-stat-divider"></div>
                    <div class="hero-stat">
                        <strong>4.9</strong>
                        <span><iconify-icon icon="material-symbols:star-rounded" style="color: var(--color-accent); vertical-align: -2px;"></iconify-icon> Rating</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Why Ballz Section -->
        <section class="features-section">
            <div class="container">
                <div class="features-header">
                    <span class="section-label">Why Ballz?</span>
                    <h2 class="section-title">Not Your Average Snack</h2>
                    <p class="section-desc">Every ball is handcrafted with premium ingredients and bursting with bold, unforgettable flavours.</p>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <iconify-icon icon="material-symbols:eco-rounded"></iconify-icon>
                        </div>
                        <h3>Fresh Ingredients</h3>
                        <p>Locally sourced, premium-quality ingredients in every single bite.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon feature-icon-orange">
                            <iconify-icon icon="material-symbols:local-fire-department-rounded"></iconify-icon>
                        </div>
                        <h3>Made to Order</h3>
                        <p>Fried fresh, never pre-made. Crispy, hot, and perfect every time.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon feature-icon-yellow">
                            <iconify-icon icon="material-symbols:delivery-dining-rounded"></iconify-icon>
                        </div>
                        <h3>Fast Delivery</h3>
                        <p>Order through our app and get your Ballz delivered in minutes.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon feature-icon-green">
                            <iconify-icon icon="material-symbols:loyalty-rounded"></iconify-icon>
                        </div>
                        <h3>Rewards Program</h3>
                        <p>Earn points with every order and unlock free Ballz and exclusive deals.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Menu Section -->
        <section id="menu" class="menu-section">
            <div class="container">
                <span class="section-label">The Menu</span>
                <h2 class="section-title">Our Ballz</h2>
                <div class="menu-toggle">
                    <button class="toggle-btn active" data-target="savory">
                        <iconify-icon icon="material-symbols:local-pizza-rounded"></iconify-icon>
                        Savory
                    </button>
                    <button class="toggle-btn" data-target="sweet">
                        <iconify-icon icon="material-symbols:cake-rounded"></iconify-icon>
                        Sweet
                    </button>
                </div>

                <div class="menu-grid active" id="savory">
                    <div class="menu-item-card">
                        <div class="menu-item-img-wrapper">
                            <img src="<?= ROOT ?>/public/assets/classic-cheese-bomb.png" alt="Classic Cheese Bomb" class="menu-item-img">
                            <span class="menu-item-badge">Bestseller</span>
                        </div>
                        <div class="menu-item-content">
                            <h3 class="menu-item-title">Classic Cheese Bomb</h3>
                            <p class="menu-item-desc">Crispy golden ball filled with molten mozzarella and cheddar, served with marinara sauce.</p>
                            <div class="menu-item-footer">
                                <span class="menu-item-price">RM8.90</span>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card">
                        <div class="menu-item-img-wrapper">
                            <img src="<?= ROOT ?>/public/assets/placeholder_food.png" alt="Mac & Cheese Truffle Bites" class="menu-item-img">
                            <span class="menu-item-badge menu-item-badge-new">New</span>
                        </div>
                        <div class="menu-item-content">
                            <h3 class="menu-item-title">Mac & Cheese Truffle Bites</h3>
                            <p class="menu-item-desc">Rich mac and cheese rolled into balls, panko-crusted, drizzled with truffle oil.</p>
                            <div class="menu-item-footer">
                                <span class="menu-item-price">RM10.90</span>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card">
                        <div class="menu-item-img-wrapper">
                            <img src="<?= ROOT ?>/public/assets/placeholder_food.png" alt="Spicy Arancini" class="menu-item-img">
                            <span class="menu-item-badge menu-item-badge-spicy">
                                <iconify-icon icon="material-symbols:local-fire-department-rounded"></iconify-icon> Spicy
                            </span>
                        </div>
                        <div class="menu-item-content">
                            <h3 class="menu-item-title">Spicy Arancini</h3>
                            <p class="menu-item-desc">Risotto balls with fiery nduja sausage and provolone, served with roasted pepper aioli.</p>
                            <div class="menu-item-footer">
                                <span class="menu-item-price">RM9.90</span>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card">
                        <div class="menu-item-img-wrapper">
                            <img src="<?= ROOT ?>/public/assets/placeholder_food.png" alt="Buffalo Chicken Poppers" class="menu-item-img">
                        </div>
                        <div class="menu-item-content">
                            <h3 class="menu-item-title">Buffalo Chicken Poppers</h3>
                            <p class="menu-item-desc">Spicy shredded chicken and cream cheese balls, served with ranch dip.</p>
                            <div class="menu-item-footer">
                                <span class="menu-item-price">RM9.50</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="menu-grid" id="sweet">
                    <div class="menu-item-card">
                        <div class="menu-item-img-wrapper">
                            <img src="<?= ROOT ?>/public/assets/placeholder_food.png" alt="Nutella Delight" class="menu-item-img">
                            <span class="menu-item-badge">Bestseller</span>
                        </div>
                        <div class="menu-item-content">
                            <h3 class="menu-item-title">Nutella Delight</h3>
                            <p class="menu-item-desc">Warm brioche ball injected with rich Nutella, dusted with powdered sugar.</p>
                            <div class="menu-item-footer">
                                <span class="menu-item-price">RM7.90</span>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card">
                        <div class="menu-item-img-wrapper">
                            <img src="<?= ROOT ?>/public/assets/placeholder_food.png" alt="Salted Caramel Crunch" class="menu-item-img">
                        </div>
                        <div class="menu-item-content">
                            <h3 class="menu-item-title">Salted Caramel Crunch</h3>
                            <p class="menu-item-desc">Fried dough ball filled with salted caramel cream, topped with pretzel bits.</p>
                            <div class="menu-item-footer">
                                <span class="menu-item-price">RM8.50</span>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card">
                        <div class="menu-item-img-wrapper">
                            <img src="<?= ROOT ?>/public/assets/placeholder_food.png" alt="Berry Bliss Bomboloni" class="menu-item-img">
                            <span class="menu-item-badge menu-item-badge-new">New</span>
                        </div>
                        <div class="menu-item-content">
                            <h3 class="menu-item-title">Berry Bliss Bomboloni</h3>
                            <p class="menu-item-desc">Italian doughnut hole filled with fresh berry compote and mascarpone.</p>
                            <div class="menu-item-footer">
                                <span class="menu-item-price">RM8.90</span>
                            </div>
                        </div>
                    </div>
                    <div class="menu-item-card">
                        <div class="menu-item-img-wrapper">
                            <img src="<?= ROOT ?>/public/assets/cinnamon-sugar-bites.png" alt="Cinnamon Sugar Bites" class="menu-item-img">
                        </div>
                        <div class="menu-item-content">
                            <h3 class="menu-item-title">Cinnamon Sugar Bites</h3>
                            <p class="menu-item-desc">Classic fluffy dough balls coated in cinnamon sugar, served with vanilla glaze.</p>
                            <div class="menu-item-footer">
                                <span class="menu-item-price">RM7.50</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- App Download Section -->
        <section id="app" class="app-section">
            <div class="container">
                <div class="app-content">
                    <div class="app-text">
                        <span class="section-label">Download Our App</span>
                        <h2 class="app-title">Get Ballz On<br>Your <span class="highlight">Phone</span></h2>
                        <p class="app-desc">Order ahead, earn rewards, and never miss a deal. The Ballz app puts bite-sized happiness right at your fingertips.</p>
                        <div class="app-features">
                            <div class="app-feature">
                                <div class="app-feature-icon">
                                    <iconify-icon icon="material-symbols:bolt-rounded"></iconify-icon>
                                </div>
                                <div>
                                    <strong>Quick Order</strong>
                                    <p>Skip the line — order and pay directly from the app.</p>
                                </div>
                            </div>
                            <div class="app-feature">
                                <div class="app-feature-icon">
                                    <iconify-icon icon="material-symbols:redeem-rounded"></iconify-icon>
                                </div>
                                <div>
                                    <strong>Earn Rewards</strong>
                                    <p>Collect points on every purchase and redeem for freebies.</p>
                                </div>
                            </div>
                            <div class="app-feature">
                                <div class="app-feature-icon">
                                    <iconify-icon icon="material-symbols:notifications-active-rounded"></iconify-icon>
                                </div>
                                <div>
                                    <strong>Exclusive Deals</strong>
                                    <p>Get app-only promotions and early access to new flavours.</p>
                                </div>
                            </div>
                        </div>
                        <div class="app-download-actions">
                            <a href="#" class="btn btn-primary btn-lg btn-download">
                                <iconify-icon icon="material-symbols:android-rounded"></iconify-icon>
                                Download APK
                            </a>
                            <div class="app-download-info">
                                <iconify-icon icon="material-symbols:verified-rounded"></iconify-icon>
                                <span>v2.1.0 &bull; 24MB &bull; Android 8.0+</span>
                            </div>
                        </div>
                    </div>
                    <div class="app-visual">
                        <div class="phone-mockup">
                            <div class="phone-frame">
                                <div class="phone-notch"></div>
                                <div class="phone-screen">
                                    <div class="phone-screen-header">
                                        <img src="<?= ROOT ?>/public/assets/logo-light-removebg-preview.png" alt="Ballz" class="phone-logo">
                                    </div>
                                    <div class="phone-screen-hero">
                                        <span class="phone-greeting">Hey there! 👋</span>
                                        <span class="phone-tagline">What are you craving?</span>
                                    </div>
                                    <div class="phone-screen-cards">
                                        <div class="phone-card">
                                            <div class="phone-card-img"></div>
                                            <span>Cheese Bomb</span>
                                            <span class="phone-card-price">RM8.90</span>
                                        </div>
                                        <div class="phone-card">
                                            <div class="phone-card-img phone-card-img-2"></div>
                                            <span>Nutella Delight</span>
                                            <span class="phone-card-price">RM7.90</span>
                                        </div>
                                    </div>
                                    <div class="phone-screen-nav">
                                        <iconify-icon icon="material-symbols:home-rounded"></iconify-icon>
                                        <iconify-icon icon="material-symbols:search-rounded"></iconify-icon>
                                        <iconify-icon icon="material-symbols:shopping-bag-rounded" class="phone-nav-active"></iconify-icon>
                                        <iconify-icon icon="material-symbols:person-rounded"></iconify-icon>
                                    </div>
                                </div>
                            </div>
                            <div class="phone-glow"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Section -->
        <section id="about" class="about-section">
            <div class="container">
                <div class="about-content">
                    <span class="section-label section-label-light">Our Story</span>
                    <h2>We Like Big Ballz</h2>
                    <p>And we cannot lie. Started in a tiny kitchen with a big dream, we're dedicated to bringing you the perfect bite-sized experience. Whether you're craving a cheesy explosion or a sweet treat, we've got the balls for you.</p>
                    <div class="about-stats">
                        <div class="about-stat">
                            <strong>2022</strong>
                            <span>Founded</span>
                        </div>
                        <div class="about-stat">
                            <strong>12</strong>
                            <span>Outlets</span>
                        </div>
                        <div class="about-stat">
                            <strong>50K+</strong>
                            <span>Customers</span>
                        </div>
                        <div class="about-stat">
                            <strong>100%</strong>
                            <span>Halal</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <img src="<?= ROOT ?>/public/assets/logo-light-removebg-preview.png" alt="Ballz" class="footer-logo">
                    <p>Bite-sized happiness, delivered fresh to your doorstep. Crispy, gooey, and impossible to resist.</p>
                    <div class="social-links">
                        <a href="#" aria-label="Instagram"><iconify-icon icon="mdi:instagram"></iconify-icon></a>
                        <a href="#" aria-label="TikTok"><iconify-icon icon="ic:baseline-tiktok"></iconify-icon></a>
                        <a href="#" aria-label="Facebook"><iconify-icon icon="mdi:facebook"></iconify-icon></a>
                    </div>
                </div>
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#menu">Menu</a></li>
                        <li><a href="#app">Download App</a></li>
                        <li><a href="#about">About Us</a></li>
                        <li><a href="<?= ROOT ?>/dashboard/index">Dashboard</a></li>
                    </ul>
                </div>
                <div class="footer-links">
                    <h4>Contact</h4>
                    <ul>
                        <li><a href="mailto:hello@ballz.my"><iconify-icon icon="material-symbols:mail-outline-rounded"></iconify-icon> hello@ballz.my</a></li>
                        <li><a href="tel:+60123456789"><iconify-icon icon="material-symbols:call-outline-rounded"></iconify-icon> +60 12-345 6789</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 Ballz Restaurant. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="<?= ROOT ?>/public/js/main.js"></script>
</body>
</html>
