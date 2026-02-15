<div class="dashboard-card">
    <div class="card-header-row">
        <h2>Manage Categories</h2>
    </div>

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
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <a href="<?= ROOT ?>/dashboard/category_edit/<?= $cat['id'] ?>" class="btn btn-sm btn-edit">Edit</a>
                                <a href="<?= ROOT ?>/dashboard/category_delete/<?= $cat['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
