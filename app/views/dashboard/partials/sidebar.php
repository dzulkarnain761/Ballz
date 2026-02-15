<aside class="sidebar" id="sidebar">
    <?php
        $currentUrl = $_GET['url'] ?? 'dashboard';
        $urlParts = explode('/', trim($currentUrl, '/'));
        $currentPage = $urlParts[1] ?? 'dashboard';
    ?>

    <!-- Sidebar brand -->
    <div class="sidebar-brand">
        <a href="<?= ROOT ?>/dashboard/index" class="sidebar-logo">
            <iconify-icon icon="material-symbols:sports-basketball" class="brand-icon"></iconify-icon>
            <span class="brand-text">BALLZ</span>
        </a>
        <button class="sidebar-close" id="sidebarClose" aria-label="Close sidebar">
            <iconify-icon icon="material-symbols:close-rounded"></iconify-icon>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="sidebar-nav-wrapper">
        <p class="nav-section-label">Management</p>
        <ul class="sidebar-nav">
            <li>
                <a href="<?= ROOT ?>/dashboard/index" class="<?= $currentPage === 'dashboard' ? 'active' : '' ?>">
                    <iconify-icon icon="material-symbols:dashboard-outline-rounded"></iconify-icon>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="<?= ROOT ?>/dashboard/categories" class="<?= $currentPage === 'categories' ? 'active' : '' ?>">
                    <iconify-icon icon="material-symbols:category-outline-rounded"></iconify-icon>
                    <span>Categories</span>
                </a>
            </li>
            <li>
                <a href="<?= ROOT ?>/dashboard/items" class="<?= $currentPage === 'items' ? 'active' : '' ?>">
                    <iconify-icon icon="material-symbols:fastfood-outline-rounded"></iconify-icon>
                    <span>Menu Items</span>
                </a>
            </li>
            <li>
                <a href="<?= ROOT ?>/dashboard/outlets" class="<?= $currentPage === 'outlets' ? 'active' : '' ?>">
                    <iconify-icon icon="material-symbols:storefront-outline-rounded"></iconify-icon>
                    <span>Outlets</span>
                </a>
            </li>
        </ul>

        <p class="nav-section-label">Sales</p>
        <ul class="sidebar-nav">
            <li>
                <a href="<?= ROOT ?>/dashboard/vouchers" class="<?= $currentPage === 'vouchers' ? 'active' : '' ?>">
                    <iconify-icon icon="material-symbols:confirmation-number-outline-rounded"></iconify-icon>
                    <span>Vouchers</span>
                </a>
            </li>
            <li>
                <a href="<?= ROOT ?>/dashboard/orders" class="<?= $currentPage === 'orders' ? 'active' : '' ?>">
                    <iconify-icon icon="material-symbols:shopping-cart-outline-rounded"></iconify-icon>
                    <span>Orders</span>
                </a>
            </li>
        </ul>

        <p class="nav-section-label">People</p>
        <ul class="sidebar-nav">
            <li>
                <a href="<?= ROOT ?>/dashboard/users" class="<?= $currentPage === 'users' ? 'active' : '' ?>">
                    <iconify-icon icon="material-symbols:group-outline-rounded"></iconify-icon>
                    <span>Customers</span>
                </a>
            </li>
        </ul>
    </nav>

    <!-- Sidebar footer -->
    <div class="sidebar-footer">
        <a href="<?= ROOT ?>/auth/logout" class="sidebar-logout">
            <iconify-icon icon="material-symbols:logout-rounded"></iconify-icon>
            <span>Logout</span>
        </a>
    </div>
</aside>