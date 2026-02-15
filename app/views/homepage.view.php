<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ballz | Bite-Sized Happiness</title>
    <meta name="description" content="Ballz - Bite-Sized Happiness. Delicious savory and sweet balls for every craving.">
    
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
                <span class="logo-text">BALLZ</span>
            </a>
            <nav class="nav">
                <ul class="nav-list">
                    
                    <li><a href="#api">API</a></li>
                    <li><a href="<?= ROOT ?>/dashboard/index">Dashboard</a></li>
                   
                    <li><button id="theme-toggle" class="theme-toggle" aria-label="Toggle Dark Mode"><iconify-icon icon="material-symbols:dark-mode-outline"></iconify-icon></button></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="container hero-content">
                <h1 class="hero-title">Bite-Sized <br><span class="highlight">Happiness</span></h1>
                <p class="hero-subtitle">Crispy, gooey, sweet, and savory balls of perfection.</p>
                <div class="hero-actions">
                    <a href="#menu" class="btn btn-primary">Explore Menu</a>
                </div>
            </div>
        </section>

        <section id="menu" class="menu-section">
            <div class="container">
                <h2 class="section-title">Our Ballz</h2>
                <div class="menu-toggle">
                    <button class="toggle-btn active" data-target="savory">Savory</button>
                    <button class="toggle-btn" data-target="sweet">Sweet</button>
                </div>

                <div class="menu-grid active" id="savory">
                    <!-- Savory Items -->
                    <div class="menu-item-card">
                        <img src="<?= ROOT ?>/public/assets/classic-cheese-bomb.png" alt="Classic Cheese Bomb" class="menu-item-img">
                        <div class="menu-item-content">
                            <h3 class="menu-item-title">Classic Cheese Bomb</h3>
                            <p class="menu-item-desc">Crispy golden ball filled with molten mozzarella and cheddar, served with marinara sauce.</p>
                            <span class="menu-item-price">RM8.90</span>
                        </div>
                    </div>
                    <div class="menu-item-card">
                        <img src="<?= ROOT ?>/public/assets/placeholder_food.png" alt="Mac & Cheese Truffle Bites" class="menu-item-img">
                        <div class="menu-item-content">
                            <h3 class="menu-item-title">Mac & Cheese Truffle Bites</h3>
                            <p class="menu-item-desc">Rich mac and cheese rolled into balls, panko-crusted, drizzled with truffle oil.</p>
                            <span class="menu-item-price">RM10.90</span>
                        </div>
                    </div>
                    <div class="menu-item-card">
                        <img src="<?= ROOT ?>/public/assets/placeholder_food.png" alt="Spicy Arancini" class="menu-item-img">
                        <div class="menu-item-content">
                            <h3 class="menu-item-title">Spicy Arancini</h3>
                            <p class="menu-item-desc">Risotto balls with fiery nduja sausage and provolone, served with roasted pepper aioli.</p>
                            <span class="menu-item-price">RM9.90</span>
                        </div>
                    </div>
                    <div class="menu-item-card">
                        <img src="<?= ROOT ?>/public/assets/placeholder_food.png" alt="Buffalo Chicken Poppers" class="menu-item-img">
                        <div class="menu-item-content">
                            <h3 class="menu-item-title">Buffalo Chicken Poppers</h3>
                            <p class="menu-item-desc">Spicy shredded chicken and cream cheese balls, served with ranch dip.</p>
                            <span class="menu-item-price">RM9.50</span>
                        </div>
                    </div>
                </div>

                <div class="menu-grid" id="sweet">
                    <!-- Sweet Items -->
                    <div class="menu-item-card">
                        <img src="<?= ROOT ?>/public/assets/placeholder_food.png" alt="Nutella Delight" class="menu-item-img">
                        <div class="menu-item-content">
                            <h3 class="menu-item-title">Nutella Delight</h3>
                            <p class="menu-item-desc">Warm brioche ball injected with rich Nutella, dusted with powdered sugar.</p>
                            <span class="menu-item-price">RM7.90</span>
                        </div>
                    </div>
                    <div class="menu-item-card">
                        <img src="<?= ROOT ?>/public/assets/placeholder_food.png" alt="Salted Caramel Crunch" class="menu-item-img">
                        <div class="menu-item-content">
                            <h3 class="menu-item-title">Salted Caramel Crunch</h3>
                            <p class="menu-item-desc">Fried dough ball filled with salted caramel cream, topped with pretzel bits.</p>
                            <span class="menu-item-price">RM8.50</span>
                        </div>
                    </div>
                    <div class="menu-item-card">
                        <img src="<?= ROOT ?>/public/assets/placeholder_food.png" alt="Berry Bliss Bomboloni" class="menu-item-img">
                        <div class="menu-item-content">
                            <h3 class="menu-item-title">Berry Bliss Bomboloni</h3>
                            <p class="menu-item-desc">Italian doughnut hole filled with fresh berry compote and mascarpone.</p>
                            <span class="menu-item-price">RM8.90</span>
                        </div>
                    </div>
                    <div class="menu-item-card">
                        <img src="<?= ROOT ?>/public/assets/cinnamon-sugar-bites.png" alt="Cinnamon Sugar Bites" class="menu-item-img">
                        <div class="menu-item-content">
                            <h3 class="menu-item-title">Cinnamon Sugar Bites</h3>
                            <p class="menu-item-desc">Classic fluffy dough balls coated in cinnamon sugar, served with vanilla glaze.</p>
                            <span class="menu-item-price">RM7.50</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="about-section">
             <div class="container">
                <div class="about-content">
                    <h2>We Like Big Ballz</h2>
                    <p>And we cannot lie. Started in a tiny kitchen with a big dream, we're dedicated to bringing you the perfect bite-sized experience. Whether you're craving a cheesy explosion or a sweet treat, we've got the balls for you.</p>
                </div>
             </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 Ballz Restaurant. All rights reserved.</p>
            <div class="social-links">
                <a href="#" aria-label="Instagram"><iconify-icon icon="mdi:instagram"></iconify-icon></a>
                <a href="#" aria-label="TikTok"><iconify-icon icon="ic:baseline-tiktok"></iconify-icon></a>
            </div>
        </div>
    </footer>

    <script src="<?= ROOT ?>/public/js/main.js"></script>
</body>
</html>
