<div class="dashboard-card">
    <div class="card-header-row">
        <h2>Manage Customers <span class="badge badge-total"><?= $pagination['totalItems'] ?? 0 ?> total</span></h2>
        <?php if (!isGuest()): ?>
        <button class="btn btn-primary btn-add-new" onclick="openUserModal()">
            <iconify-icon icon="material-symbols:add-rounded"></iconify-icon> Add Customer
        </button>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Points</th>
                    <th>Status</th>
                    <?php if (!isGuest()): ?><th>Actions</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr><td colspan="6" class="empty-state">No customers found</td></tr>
                <?php endif; ?>
                <?php $rowNum = (($pagination['page'] - 1) * $pagination['perPage']); ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="row-number"><?= ++$rowNum ?></td>
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
                        <?php if (!isGuest()): ?>
                        <td>
                            <div class="action-btns">
                                <button class="btn btn-sm btn-edit" onclick='openUserModal(<?= json_encode([
                                    "id" => $user["id"],
                                    "name" => $user["name"],
                                    "email" => $user["email"] ?? "",
                                    "phone" => $user["phone"] ?? "",
                                    "reward_points" => $user["reward_points"] ?? 0,
                                    "status" => $user["status"] ?? "active"
                                ]) ?>)'>
                                    <iconify-icon icon="material-symbols:edit-outline-rounded"></iconify-icon> Edit
                                </button>
                                <button class="btn btn-sm btn-delete" onclick="openDeleteModal('<?= ROOT ?>/dashboard/users/delete/<?= $user['id'] ?>', 'customer')">
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
    <?php include 'partials/pagination.php'; ?>
</div>

<?php if (!isGuest()): ?>
<!-- User Add/Edit Modal -->
<div class="modal-overlay" id="userModal">
    <div class="modal">
        <div class="modal-header">
            <h3 id="userModalTitle">Add Customer</h3>
            <button class="modal-close" onclick="closeModal('userModal')">&times;</button>
        </div>
        <form id="userForm" method="POST">
            <div class="modal-body">
                <div class="form-grid form-grid-2">
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" id="userName" class="form-control" required placeholder="John Doe">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="userEmail" class="form-control" placeholder="john@example.com">
                    </div>
                </div>
                <div class="form-grid form-grid-3">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" id="userPhone" class="form-control" placeholder="012-3456789">
                    </div>
                    <div class="form-group">
                        <label>Reward Points</label>
                        <input type="number" name="reward_points" id="userPoints" class="form-control" value="0">
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="userStatus" class="form-control">
                            <option value="active">Active</option>
                            <option value="blocked">Blocked</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeModal('userModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" id="userSubmitBtn">Add Customer</button>
            </div>
        </form>
    </div>
</div>

<script>
function openUserModal(data = null) {
    const form = document.getElementById('userForm');
    const title = document.getElementById('userModalTitle');
    const btn = document.getElementById('userSubmitBtn');

    if (data && data.id) {
        title.textContent = 'Edit Customer';
        btn.textContent = 'Update Customer';
        form.action = '<?= ROOT ?>/dashboard/users/edit/' + data.id;
        document.getElementById('userName').value = data.name || '';
        document.getElementById('userEmail').value = data.email || '';
        document.getElementById('userPhone').value = data.phone || '';
        document.getElementById('userPoints').value = data.reward_points || 0;
        document.getElementById('userStatus').value = data.status || 'active';
    } else {
        title.textContent = 'Add Customer';
        btn.textContent = 'Add Customer';
        form.action = '<?= ROOT ?>/dashboard/users/add';
        form.reset();
    }
    openModal('userModal');
}
</script>
<?php endif; ?>
