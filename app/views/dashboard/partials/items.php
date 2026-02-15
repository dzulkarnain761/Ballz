<div class="dashboard-card">
    <div class="card-header-row">
        <h2>Manage Menu Items</h2>
    </div>

    <?php if (!isGuest()): ?>
    <div class="form-card">
        <h3>Add New Item</h3>
        <form action="<?= ROOT ?>/dashboard/item_add" method="POST">
            <div class="form-grid form-grid-2">
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
            <div class="form-grid form-grid-2">
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
    </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <?php if (!isGuest()): ?><th>Actions</th><?php endif; ?>
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
                        <?php if (!isGuest()): ?>
                        <td>
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <a href="<?= ROOT ?>/dashboard/item_edit/<?= $item['id'] ?>" class="btn btn-sm btn-edit">Edit</a>
                                <a href="<?= ROOT ?>/dashboard/item_delete/<?= $item['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
