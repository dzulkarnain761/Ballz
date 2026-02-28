<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu | Ballz - Bite-Sized Happiness</title>
    <meta name="description" content="Explore the full Ballz menu — savory balls, sweet balls, drinks and more. Fresh ingredients, bold flavours.">

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
            <a href="<?= ROOT ?>" class="logo">
                <img src="<?= ROOT ?>/public/assets/logo-light-removebg-preview.png" alt="Ballz" class="logo-img logo-img-dark">
                <img src="<?= ROOT ?>/public/assets/logo-dark-removebg-preview.png" alt="Ballz" class="logo-img logo-img-light">
            </a>
            <nav class="nav">
                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Open Menu">
                    <iconify-icon icon="material-symbols:menu-rounded"></iconify-icon>
                </button>
                <ul class="nav-list" id="navList">
                    <li><a href="<?= ROOT ?>">Home</a></li>
                    <li><a href="<?= ROOT ?>/menu">Menu</a></li>
                    <li><a href="<?= ROOT ?>/dashboard/index">Dashboard</a></li>
                    <li><button id="theme-toggle" class="theme-toggle" aria-label="Toggle Dark Mode"><iconify-icon icon="material-symbols:dark-mode-outline"></iconify-icon></button></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <!-- Menu Hero -->
        <section class="menu-hero">
            <div class="menu-hero-bg">
                <div class="hero-blob hero-blob-1"></div>
                <div class="hero-blob hero-blob-2"></div>
            </div>
            <div class="container menu-hero-content">
                <span class="section-label">Our Menu</span>
                <h1 class="menu-hero-title">Explore Our <span class="highlight">Flavours</span></h1>
                <p class="menu-hero-subtitle">From crispy savory bites to sweet indulgences — there's a ball for every mood.</p>
            </div>
        </section>

        <!-- Menu Content -->
        <section class="menu-page-section">
            <div class="container">
                <!-- Category Filter Tabs -->
                <div class="menu-filter-bar">
                    <button class="menu-filter-btn active" data-category="all">
                        <iconify-icon icon="material-symbols:grid-view-rounded"></iconify-icon>
                        All
                    </button>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <button class="menu-filter-btn" data-category="<?= htmlspecialchars($cat['name']) ?>">
                                <?= htmlspecialchars($cat['name']) ?>
                            </button>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Menu Grid -->
                <div class="menu-grid" id="menuGrid">
                    <?php if (!empty($menuItems)): ?>
                        <?php foreach ($menuItems as $item): ?>
                            <?php if ($item['is_active']): ?>
                                <div class="menu-card" data-category="<?= htmlspecialchars($item['category_name']) ?>">
                                    <div class="menu-card-img-wrap">
                                        <img 
                                            src="<?= ROOT ?>/public/assets/<?= htmlspecialchars($item['img_path']) ?>" 
                                            alt="<?= htmlspecialchars($item['name']) ?>" 
                                            class="menu-card-img"
                                            loading="lazy"
                                        >
                                        <span class="menu-card-badge"><?= htmlspecialchars($item['category_name']) ?></span>
                                    </div>
                                    <div class="menu-card-body">
                                        <h3 class="menu-card-title"><?= htmlspecialchars($item['name']) ?></h3>
                                        <p class="menu-card-desc"><?= htmlspecialchars($item['description']) ?></p>
                                        <div class="menu-card-footer">
                                            <span class="menu-card-price">RM <?= number_format($item['price'], 2) ?></span>
                                            
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="menu-empty">
                            <iconify-icon icon="material-symbols:restaurant-menu-rounded" style="font-size: 3rem; opacity: 0.3;"></iconify-icon>
                            <p>No menu items available right now. Check back soon!</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Empty State for Filters -->
                <div class="menu-empty menu-filter-empty" id="menuFilterEmpty" style="display: none;">
                    <iconify-icon icon="material-symbols:search-off-rounded" style="font-size: 3rem; opacity: 0.3;"></iconify-icon>
                    <p>No items found in this category.</p>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="menu-cta-section">
            <div class="container">
                <div class="menu-cta-card">
                    <div class="menu-cta-content">
                        <h2>Order via the <span class="highlight">Ballz App</span></h2>
                        <p>Skip the queue — order ahead, earn rewards, and get exclusive app-only deals.</p>
                        <div class="menu-cta-actions">
                            <a href="<?= ROOT ?>/#app" class="btn btn-primary btn-lg">
                                <iconify-icon icon="material-symbols:download-rounded"></iconify-icon>
                                Download App
                            </a>
                        </div>
                    </div>
                    <div class="menu-cta-icon">
                        <iconify-icon icon="material-symbols:phone-iphone-rounded"></iconify-icon>
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
                        <li><a href="<?= ROOT ?>/home/menu">Menu</a></li>
                        <li><a href="<?= ROOT ?>/#app">Download App</a></li>
                        <li><a href="<?= ROOT ?>/#about">About Us</a></li>
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
