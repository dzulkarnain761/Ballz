<div class="dashboard-card">
    <div class="card-header-row">
        <h2>Manage Menu Items <span class="badge badge-total"><?= $pagination['totalItems'] ?? 0 ?> total</span></h2>
        <?php if (!isGuest()): ?>
        <button class="btn btn-primary btn-add-new" onclick="openItemModal()">
            <iconify-icon icon="material-symbols:add-rounded"></iconify-icon> Add Item
        </button>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Status</th>
                    <?php if (!isGuest()): ?><th>Actions</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr><td colspan="6" class="empty-state">No menu items found</td></tr>
                <?php endif; ?>
                <?php $rowNum = (($pagination['page'] - 1) * $pagination['perPage']); ?>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td class="row-number"><?= ++$rowNum ?></td>
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
                            <div class="action-btns">
                                <button class="btn btn-sm btn-edit" onclick="openItemModal(<?= $item['id'] ?>, '<?= htmlspecialchars(addslashes($item['name'])) ?>', <?= $item['category_id'] ?>, '<?= htmlspecialchars(addslashes($item['description'] ?? '')) ?>', <?= $item['price'] ?>, <?= $item['is_active'] ?>)">
                                    <iconify-icon icon="material-symbols:edit-outline-rounded"></iconify-icon> Edit
                                </button>
                                <button class="btn btn-sm btn-delete" onclick="openDeleteModal('<?= ROOT ?>/dashboard/items/delete/<?= $item['id'] ?>', 'item')">
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
    <?php include 'pagination.php'; ?>
</div>

<?php if (!isGuest()): ?>
<!-- Item Add/Edit Modal -->
<div class="modal-overlay" id="itemModal">
    <div class="modal">
        <div class="modal-header">
            <h3 id="itemModalTitle">Add Menu Item</h3>
            <button class="modal-close" onclick="closeModal('itemModal')">&times;</button>
        </div>
        <form id="itemForm" method="POST">
            <div class="modal-body">
                <div class="form-grid form-grid-2">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" id="itemName" class="form-control" required placeholder="e.g. Cheese Bomb">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category_id" id="itemCategory" class="form-control" required>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="itemDescription" class="form-control" placeholder="Item description" rows="3"></textarea>
                </div>
                <div class="form-grid form-grid-2">
                    <div class="form-group">
                        <label>Price (RM)</label>
                        <input type="number" step="0.01" name="price" id="itemPrice" class="form-control" required placeholder="8.90">
                    </div>
                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_active" id="itemActive" checked> Active
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeModal('itemModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" id="itemSubmitBtn">Add Item</button>
            </div>
        </form>
    </div>
</div>

<script>
function openItemModal(id = null, name = '', categoryId = '', description = '', price = '', isActive = 1) {
    const form = document.getElementById('itemForm');
    const title = document.getElementById('itemModalTitle');
    const btn = document.getElementById('itemSubmitBtn');

    if (id) {
        title.textContent = 'Edit Menu Item';
        btn.textContent = 'Update Item';
        form.action = '<?= ROOT ?>/dashboard/items/edit/' + id;
    } else {
        title.textContent = 'Add Menu Item';
        btn.textContent = 'Add Item';
        form.action = '<?= ROOT ?>/dashboard/items/add';
    }

    document.getElementById('itemName').value = name;
    document.getElementById('itemCategory').value = categoryId;
    document.getElementById('itemDescription').value = description;
    document.getElementById('itemPrice').value = price;
    document.getElementById('itemActive').checked = isActive == 1;
    openModal('itemModal');
}
</script>
<?php endif; ?>
