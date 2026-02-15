<div class="dashboard-card">
    <div class="card-header-row">
        <h2>Manage Categories</h2>
    </div>

    <?php if (!isGuest()): ?>
    <div class="form-card">
        <h3>Add New Category</h3>
        <form action="<?= ROOT ?>/dashboard/category_add" method="POST">
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
    </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Description</th>
                    <?php if (!isGuest()): ?><th>Actions</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= htmlspecialchars($cat['name']) ?></td>
                        <td><?= htmlspecialchars($cat['description']) ?></td>
                        <?php if (!isGuest()): ?>
                        <td>
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <a href="<?= ROOT ?>/dashboard/category_edit/<?= $cat['id'] ?>" class="btn btn-sm btn-edit">Edit</a>
                                <a href="<?= ROOT ?>/dashboard/category_delete/<?= $cat['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
