<aside class="sidebar">
            <?php
                // Auto-detect current active tab from URL
                $currentUrl = $_GET['url'] ?? 'dashboard';
                $urlParts = explode('/', trim($currentUrl, '/'));
                $currentPage = $urlParts[1] ?? 'dashboard';
            ?>
            <ul class="sidebar-nav">
                <li>
                    <a href="<?= ROOT ?>/dashboard/categories" class="<?= $currentPage === 'categories' ? 'active' : '' ?>">
                        <iconify-icon icon="material-symbols:category-outline"></iconify-icon> Categories
                    </a>
                </li>
                <li>
                    <a href="<?= ROOT ?>/dashboard/items" class="<?= $currentPage === 'items' ? 'active' : '' ?>">
                        <iconify-icon icon="material-symbols:fastfood-outline"></iconify-icon> Menu Items
                    </a>
                </li>

                <li>
                    <a href="<?= ROOT ?>/dashboard/outlets" class="<?= $currentPage === 'outlets' ? 'active' : '' ?>">
                        <iconify-icon icon="material-symbols:storefront-outline"></iconify-icon> Outlets
                    </a>
                </li>
                <li>
                    <a href="<?= ROOT ?>/dashboard/vouchers" class="<?= $currentPage === 'vouchers' ? 'active' : '' ?>">
                        <iconify-icon icon="material-symbols:confirmation-number-outline"></iconify-icon> Vouchers
                    </a>
                </li>
                <li>
                    <a href="<?= ROOT ?>/dashboard/orders" class="<?= $currentPage === 'orders' ? 'active' : '' ?>">
                        <iconify-icon icon="material-symbols:shopping-cart-outline"></iconify-icon> Orders
                    </a>
                </li>

                <li>
                    <a href="<?= ROOT ?>/dashboard/users" class="<?= $currentPage === 'users' ? 'active' : '' ?>">
                        <iconify-icon icon="material-symbols:person-outline"></iconify-icon> Customers
                    </a>
                </li>
            </ul>
        </aside>