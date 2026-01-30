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
<body>
    <header class="header">
        <div class="container header-container">
            <a href="<?= ROOT ?>/dashboard" class="logo">
                <span class="logo-text">BALLZ ADMIN</span>
            </a>
            <nav class="nav">
                <ul class="nav-list">
                    <li><a href="<?= ROOT ?>/home">View Site</a></li>
                    <li><button id="theme-toggle" class="theme-toggle"><iconify-icon icon="material-symbols:dark-mode-outline"></iconify-icon></button></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="dashboard-container container">
        <?php include 'partials/sidebar.php'; ?>

        <main class="main-content">
            <?php include 'partials/alert.php'; ?>

            <?php if (($tab ?? '') == 'categories'): ?>
                <div class="dashboard-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2>Manage Categories</h2>
                    </div>
                    
                    <form action="<?= ROOT ?>/dashboard/category_add" method="POST" style="margin-bottom: 30px; padding: 20px; border: 1px solid var(--border-color); border-radius: 15px;">
                        <h3>Add New Category</h3>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Savory">
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" placeholder="Optional description"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Category</button>
                    </form>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($categories as $cat): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($cat['name']) ?></td>
                                        <td><?= htmlspecialchars($cat['description']) ?></td>
                                        <td>
                                            <a href="<?= ROOT ?>/dashboard/category_edit/<?= $cat['id'] ?>" class="btn btn-sm" style="background: var(--color-accent); color: var(--text-color);">Edit</a>
                                            <a href="<?= ROOT ?>/dashboard/category_delete/<?= $cat['id'] ?>" class="btn btn-sm" style="background: #e74c3c; color: white;" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif (($tab ?? '') == 'category_edit'): ?>
                <div class="dashboard-card">
                    <h2>Edit Category</h2>
                    <form action="<?= ROOT ?>/dashboard/category_edit/<?= $category['id'] ?>" method="POST">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($category['name']) ?>" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control"><?= htmlspecialchars($category['description']) ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Category</button>
                        <a href="<?= ROOT ?>/dashboard/categories" class="btn" style="background: #95a5a6; color: white;">Cancel</a>
                    </form>
                </div>

            <?php elseif (($tab ?? '') == 'items'): ?>
                <div class="dashboard-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2>Manage Menu Items</h2>
                    </div>

                    <form action="<?= ROOT ?>/dashboard/item_add" method="POST" style="margin-bottom: 30px; padding: 20px; border: 1px solid var(--border-color); border-radius: 15px;">
                        <h3>Add New Item</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" required placeholder="e.g. Cheese Bomb">
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select name="category_id" class="form-control" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" placeholder="Item description"></textarea>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>Price (RM)</label>
                                <input type="number" step="0.01" name="price" class="form-control" required placeholder="8.90">
                            </div>
                            <div class="form-group" style="display: flex; align-items: flex-end; padding-bottom: 12px;">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="is_active" checked style="width: 20px; height: 20px;"> Active
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Item</button>
                    </form>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($item['name']) ?></td>
                                        <td><?= htmlspecialchars($item['category_name']) ?></td>
                                        <td>RM<?= number_format($item['price'], 2) ?></td>
                                        <td>
                                            <span class="badge <?= $item['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                                                <?= $item['is_active'] ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= ROOT ?>/dashboard/item_edit/<?= $item['id'] ?>" class="btn btn-sm" style="background: var(--color-accent); color: var(--text-color);">Edit</a>
                                            <a href="<?= ROOT ?>/dashboard/item_delete/<?= $item['id'] ?>" class="btn btn-sm" style="background: #e74c3c; color: white;" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif (($tab ?? '') == 'item_edit'): ?>
                <div class="dashboard-card">
                    <h2>Edit Menu Item</h2>
                    <form action="<?= ROOT ?>/dashboard/item_edit/<?= $item['id'] ?>" method="POST">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($item['name']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select name="category_id" class="form-control" required>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= $item['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control"><?= htmlspecialchars($item['description']) ?></textarea>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>Price (RM)</label>
                                <input type="number" step="0.01" name="price" class="form-control" value="<?= $item['price'] ?>" required>
                            </div>
                            <div class="form-group" style="display: flex; align-items: flex-end; padding-bottom: 12px;">
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" name="is_active" <?= $item['is_active'] ? 'checked' : '' ?> style="width: 20px; height: 20px;"> Active
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Item</button>
                        <a href="<?= ROOT ?>/dashboard/items" class="btn" style="background: #95a5a6; color: white;">Cancel</a>
                    </form>
                </div>

            <?php elseif (($tab ?? '') == 'outlets'): ?>
                <div class="dashboard-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2>Manage Outlets</h2>
                    </div>

                    <form action="<?= ROOT ?>/dashboard/outlet_add" method="POST" style="margin-bottom: 30px; padding: 20px; border: 1px solid var(--border-color); border-radius: 15px;">
                        <h3>Add New Outlet</h3>
                        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" class="form-control" required placeholder="BALLZ-01">
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" required placeholder="Outlet Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control" required placeholder="Full address"></textarea>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="city" class="form-control" placeholder="City">
                            </div>
                            <div class="form-group">
                                <label>State</label>
                                <input type="text" name="state" class="form-control" placeholder="State">
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" placeholder="Phone number">
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="is_active" checked style="width: 20px; height: 20px;"> Active
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Outlet</button>
                    </form>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($outlets as $outlet): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($outlet['code']) ?></td>
                                        <td><?= htmlspecialchars($outlet['name']) ?></td>
                                        <td><?= htmlspecialchars($outlet['city']) ?>, <?= htmlspecialchars($outlet['state']) ?></td>
                                        <td>
                                            <span class="badge <?= $outlet['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                                                <?= $outlet['is_active'] ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= ROOT ?>/dashboard/outlet_edit/<?= $outlet['id'] ?>" class="btn btn-sm" style="background: var(--color-accent); color: var(--text-color);">Edit</a>
                                            <a href="<?= ROOT ?>/dashboard/outlet_delete/<?= $outlet['id'] ?>" class="btn btn-sm" style="background: #e74c3c; color: white;" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif (($tab ?? '') == 'outlet_edit'): ?>
                <div class="dashboard-card">
                    <h2>Edit Outlet</h2>
                    <form action="<?= ROOT ?>/dashboard/outlet_edit/<?= $outlet['id'] ?>" method="POST">
                        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" class="form-control" value="<?= htmlspecialchars($outlet['code']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($outlet['name']) ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <textarea name="address" class="form-control" required><?= htmlspecialchars($outlet['address']) ?></textarea>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" name="city" class="form-control" value="<?= htmlspecialchars($outlet['city']) ?>">
                            </div>
                            <div class="form-group">
                                <label>State</label>
                                <input type="text" name="state" class="form-control" value="<?= htmlspecialchars($outlet['state']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($outlet['phone']) ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="is_active" <?= $outlet['is_active'] ? 'checked' : '' ?> style="width: 20px; height: 20px;"> Active
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Outlet</button>
                        <a href="<?= ROOT ?>/dashboard/outlets" class="btn" style="background: #95a5a6; color: white;">Cancel</a>
                    </form>
                </div>

            <?php elseif (($tab ?? '') == 'vouchers'): ?>
                <div class="dashboard-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2>Manage Vouchers</h2>
                    </div>

                    <form action="<?= ROOT ?>/dashboard/voucher_add" method="POST" style="margin-bottom: 30px; padding: 20px; border: 1px solid var(--border-color); border-radius: 15px;">
                        <h3>Add New Voucher</h3>
                        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" class="form-control" required placeholder="WELCOME20">
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" required placeholder="Voucher Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" placeholder="Short description"></textarea>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>Discount Type</label>
                                <select name="discount_type" class="form-control" required>
                                    <option value="fixed">Fixed RM</option>
                                    <option value="percentage">Percentage %</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Value</label>
                                <input type="number" step="0.01" name="discount_value" class="form-control" required placeholder="0.00">
                            </div>
                            <div class="form-group">
                                <label>Min Order</label>
                                <input type="number" step="0.01" name="min_order_amount" class="form-control" placeholder="0.00">
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="is_active" checked style="width: 20px; height: 20px;"> Active
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Voucher</button>
                    </form>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Discount</th>
                                    <th>Validity</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($vouchers as $vouch): ?>
                                    <tr>
                                        <td><code><?= htmlspecialchars($vouch['code']) ?></code></td>
                                        <td><?= htmlspecialchars($vouch['name']) ?></td>
                                        <td>
                                            <?= $vouch['discount_type'] == 'fixed' ? 'RM' : '' ?><?= number_format($vouch['discount_value'], 2) ?><?= $vouch['discount_type'] == 'percentage' ? '%' : '' ?>
                                        </td>
                                        <td>
                                            <small>
                                                <?= $vouch['start_date'] ?: 'Anytime' ?> to <br>
                                                <?= $vouch['end_date'] ?: 'No Limit' ?>
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge <?= $vouch['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                                                <?= $vouch['is_active'] ? 'Active' : 'Inactive' ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= ROOT ?>/dashboard/voucher_edit/<?= $vouch['id'] ?>" class="btn btn-sm" style="background: var(--color-accent); color: var(--text-color);">Edit</a>
                                            <a href="<?= ROOT ?>/dashboard/voucher_delete/<?= $vouch['id'] ?>" class="btn btn-sm" style="background: #e74c3c; color: white;" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif (($tab ?? '') == 'voucher_edit'): ?>
                <div class="dashboard-card">
                    <h2>Edit Voucher</h2>
                    <form action="<?= ROOT ?>/dashboard/voucher_edit/<?= $voucher['id'] ?>" method="POST">
                        <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 20px;">
                            <div class="form-group">
                                <label>Code</label>
                                <input type="text" name="code" class="form-control" value="<?= htmlspecialchars($voucher['code']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($voucher['name']) ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control"><?= htmlspecialchars($voucher['description']) ?></textarea>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>Discount Type</label>
                                <select name="discount_type" class="form-control" required>
                                    <option value="fixed" <?= $voucher['discount_type'] == 'fixed' ? 'selected' : '' ?>>Fixed RM</option>
                                    <option value="percentage" <?= $voucher['discount_type'] == 'percentage' ? 'selected' : '' ?>>Percentage %</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Value</label>
                                <input type="number" step="0.01" name="discount_value" class="form-control" value="<?= $voucher['discount_value'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Min Order</label>
                                <input type="number" step="0.01" name="min_order_amount" class="form-control" value="<?= $voucher['min_order_amount'] ?>">
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="<?= $voucher['start_date'] ?>">
                            </div>
                            <div class="form-group">
                                <label>End Date</label>
                                <input type="date" name="end_date" class="form-control" value="<?= $voucher['end_date'] ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                <input type="checkbox" name="is_active" <?= $voucher['is_active'] ? 'checked' : '' ?> style="width: 20px; height: 20px;"> Active
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Voucher</button>
                        <a href="<?= ROOT ?>/dashboard/vouchers" class="btn" style="background: #95a5a6; color: white;">Cancel</a>
                    </form>
                </div>

            <?php elseif (($tab ?? '') == 'orders'): ?>
                <div class="dashboard-card">
                    <h2>Recent Orders</h2>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Customer</th>
                                    <th>Outlet</th>
                                    <th>Total</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td>#<?= $order['id'] ?></td>
                                        <td><?= htmlspecialchars($order['user_name'] ?? 'Guest') ?></td>
                                        <td><?= htmlspecialchars($order['outlet_name']) ?></td>
                                        <td>RM<?= number_format($order['final_total'], 2) ?></td>
                                        <td><?= ucfirst($order['order_type']) ?> <?= $order['table_number'] ? '(Table '.$order['table_number'].')' : '' ?></td>
                                        <td>
                                            <?php
                                                $statusClass = 'badge-danger';
                                                if($order['status'] == 'completed') $statusClass = 'badge-success';
                                                if($order['status'] == 'pending') $statusClass = ''; // Default/Gray
                                                if($order['status'] == 'paid') $statusClass = 'badge-success'; // Maybe blue if defined
                                            ?>
                                            <span class="badge <?= $statusClass ?>" style="<?= $order['status'] == 'pending' ? 'background: #95a5a6; color: white;' : '' ?>">
                                                <?= ucfirst($order['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <form action="<?= ROOT ?>/dashboard/order_status/<?= $order['id'] ?>" method="POST" style="display: flex; gap: 5px;">
                                                <select name="status" class="form-control" style="padding: 5px; width: auto; font-size: 0.8rem;">
                                                    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                                    <option value="paid" <?= $order['status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                                                    <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                                    <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                                </select>
                                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>





            <?php elseif (($tab ?? '') == 'users'): ?>
                <div class="dashboard-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                        <h2>Manage Customers</h2>
                    </div>

                    <form action="<?= ROOT ?>/dashboard/user_add" method="POST" style="margin-bottom: 30px; padding: 20px; border: 1px solid var(--border-color); border-radius: 15px;">
                        <h3>Add New Customer</h3>
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="name" class="form-control" required placeholder="John Doe">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" placeholder="john@example.com">
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" placeholder="012-3456789">
                            </div>
                            <div class="form-group">
                                <label>Reward Points</label>
                                <input type="number" name="reward_points" class="form-control" value="0">
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="blocked">Blocked</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Customer</button>
                    </form>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Contact</th>
                                    <th>Points</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user['name']) ?></td>
                                        <td>
                                            <small>
                                                <?= htmlspecialchars($user['email']) ?><br>
                                                <?= htmlspecialchars($user['phone']) ?>
                                            </small>
                                        </td>
                                        <td><?= $user['reward_points'] ?></td>
                                        <td>
                                            <span class="badge <?= $user['status'] == 'active' ? 'badge-success' : 'badge-danger' ?>">
                                                <?= ucfirst($user['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?= ROOT ?>/dashboard/user_edit/<?= $user['id'] ?>" class="btn btn-sm" style="background: var(--color-accent); color: var(--text-color);">Edit</a>
                                            <a href="<?= ROOT ?>/dashboard/user_delete/<?= $user['id'] ?>" class="btn btn-sm" style="background: #e74c3c; color: white;" onclick="return confirm('Are you sure?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            <?php elseif (($tab ?? '') == 'user_edit'): ?>
                <div class="dashboard-card">
                    <h2>Edit Customer</h2>
                    <form action="<?= ROOT ?>/dashboard/user_edit/<?= $user['id'] ?>" method="POST">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>">
                            </div>
                        </div>
                        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>">
                            </div>
                            <div class="form-group">
                                <label>Reward Points</label>
                                <input type="number" name="reward_points" class="form-control" value="<?= $user['reward_points'] ?>">
                            </div>
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="active" <?= $user['status'] == 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="blocked" <?= $user['status'] == 'blocked' ? 'selected' : '' ?>>Blocked</option>
                                </select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Update Customer</button>
                        <a href="<?= ROOT ?>/dashboard/users" class="btn" style="background: #95a5a6; color: white;">Cancel</a>
                    </form>
                </div>




                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn" style="background: var(--color-primary); color: white;">Add Option</button>
                    </form>
                </div>

            <?php else: ?>
                <div class="dashboard-card" style="text-align: center; padding: 50px;">
                    <iconify-icon icon="material-symbols:admin-panel-settings-outline" style="font-size: 5rem; color: var(--color-primary);"></iconify-icon>
                    <h2 style="margin-top: 20px;">Welcome to Ballz Admin</h2>
                    <p>Select a section from the sidebar to manage your ballz.</p>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <script src="<?= ROOT ?>/public/js/main.js"></script>
</body>
</html>
