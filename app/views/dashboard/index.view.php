<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | BALLZ</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <link rel="stylesheet" href="<?= ROOT ?>/public/css/styles.css">
</head>
<body class="dashboard-body">
    <!-- Mobile overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="dashboard-wrapper">
        <?php include 'partials/sidebar.php'; ?>

        <div class="dashboard-main">
            <!-- Top header bar -->
            <header class="dashboard-header">
                <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                    <iconify-icon icon="material-symbols:menu-rounded"></iconify-icon>
                </button>
                <div class="header-title">
                    <?php
                        $tab = $tab ?? '';
                        $pageTitles = [
                            'categories' => 'Categories',
                            'items' => 'Menu Items',
                            'outlets' => 'Outlets',
                            'vouchers' => 'Vouchers',
                            'orders' => 'Orders',
                            'users' => 'Customers',
                        ];
                        echo $pageTitles[$tab] ?? 'Dashboard';
                    ?>
                </div>
                <div class="header-actions">
                    <a href="<?= ROOT ?>/" class="header-link" title="View site">
                        <iconify-icon icon="material-symbols:open-in-new-rounded"></iconify-icon>
                    </a>
                </div>
            </header>

            <!-- Main content area -->
            <main class="main-content">
                <?php include 'partials/alert.php'; ?>

                <?php
                    if ($tab == 'categories') {
                        include 'partials/categories.php';
                    } elseif ($tab == 'items') {
                        include 'partials/items.php';
                    } elseif ($tab == 'outlets') {
                        include 'partials/outlets.php';
                    } elseif ($tab == 'vouchers') {
                        include 'partials/vouchers.php';
                    } elseif ($tab == 'orders') {
                        include 'partials/orders.php';
                    } elseif ($tab == 'users') {
                        include 'partials/users.php';
                    } else {
                        include 'partials/welcome.php';
                    }
                ?>
            </main>
        </div>
    </div>

    <!-- Global Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal modal-sm">
            <div class="modal-header modal-header-danger">
                <h3>
                    <iconify-icon icon="material-symbols:warning-outline-rounded"></iconify-icon>
                    Confirm Delete
                </h3>
                <button class="modal-close" onclick="closeModal('deleteModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p id="deleteModalMessage">Are you sure you want to delete this item? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeModal('deleteModal')">Cancel</button>
                <a href="#" class="btn btn-delete-confirm" id="confirmDeleteBtn">
                    <iconify-icon icon="material-symbols:delete-outline-rounded"></iconify-icon> Delete
                </a>
            </div>
        </div>
    </div>

    <script src="<?= ROOT ?>/public/js/main.js"></script>
</body>
</html>
