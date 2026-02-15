<div class="dashboard-card">
    <div class="card-header-row">
        <h2>Manage Categories</h2>
        <?php if (!isGuest()): ?>
        <button class="btn btn-primary btn-add-new" onclick="openCategoryModal()">
            <iconify-icon icon="material-symbols:add-rounded"></iconify-icon> Add Category
        </button>
        <?php endif; ?>
    </div>

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
                <?php if (empty($categories)): ?>
                    <tr><td colspan="3" class="empty-state">No categories found</td></tr>
                <?php endif; ?>
                <?php foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= htmlspecialchars($cat['name']) ?></td>
                        <td><?= htmlspecialchars($cat['description']) ?></td>
                        <?php if (!isGuest()): ?>
                        <td>
                            <div class="action-btns">
                                <button class="btn btn-sm btn-edit" onclick="openCategoryModal(<?= $cat['id'] ?>, '<?= htmlspecialchars(addslashes($cat['name'])) ?>', '<?= htmlspecialchars(addslashes($cat['description'])) ?>')">
                                    <iconify-icon icon="material-symbols:edit-outline-rounded"></iconify-icon> Edit
                                </button>
                                <button class="btn btn-sm btn-delete" onclick="openDeleteModal('<?= ROOT ?>/dashboard/categories/delete/<?= $cat['id'] ?>', 'category')">
                                    <iconify-icon icon="material-symbols:delete-outline-rounded"></iconify-icon> Delete
                                </button>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php if (!isGuest()): ?>
<!-- Category Add/Edit Modal -->
<div class="modal-overlay" id="categoryModal">
    <div class="modal">
        <div class="modal-header">
            <h3 id="categoryModalTitle">Add Category</h3>
            <button class="modal-close" onclick="closeModal('categoryModal')">&times;</button>
        </div>
        <form id="categoryForm" method="POST">
            <div class="modal-body">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" id="catName" class="form-control" required placeholder="e.g. Savory">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="catDescription" class="form-control" placeholder="Optional description" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeModal('categoryModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" id="categorySubmitBtn">Add Category</button>
            </div>
        </form>
    </div>
</div>

<script>
function openCategoryModal(id = null, name = '', description = '') {
    const modal = document.getElementById('categoryModal');
    const form = document.getElementById('categoryForm');
    const title = document.getElementById('categoryModalTitle');
    const btn = document.getElementById('categorySubmitBtn');

    if (id) {
        title.textContent = 'Edit Category';
        btn.textContent = 'Update Category';
        form.action = '<?= ROOT ?>/dashboard/categories/edit/' + id;
    } else {
        title.textContent = 'Add Category';
        btn.textContent = 'Add Category';
        form.action = '<?= ROOT ?>/dashboard/categories/add';
    }

    document.getElementById('catName').value = name;
    document.getElementById('catDescription').value = description;
    openModal('categoryModal');
}
</script>
<?php endif; ?>
