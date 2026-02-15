<div class="dashboard-card">
    <div class="card-header-row">
        <h2>Manage Outlets <span class="badge badge-total"><?= $pagination['totalItems'] ?? 0 ?> total</span></h2>
        <?php if (!isGuest()): ?>
        <button class="btn btn-primary btn-add-new" onclick="openOutletModal()">
            <iconify-icon icon="material-symbols:add-rounded"></iconify-icon> Add Outlet
        </button>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <?php if (!isGuest()): ?><th>Actions</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($outlets)): ?>
                    <tr><td colspan="7" class="empty-state">No outlets found</td></tr>
                <?php endif; ?>
                <?php $rowNum = (($pagination['page'] - 1) * $pagination['perPage']); ?>
                <?php foreach ($outlets as $outlet): ?>
                    <tr>
                        <td class="row-number"><?= ++$rowNum ?></td>
                        <td><code><?= htmlspecialchars($outlet['code']) ?></code></td>
                        <td><?= htmlspecialchars($outlet['name']) ?></td>
                        <td><?= htmlspecialchars($outlet['city']) ?>, <?= htmlspecialchars($outlet['state']) ?></td>
                        <td><?= htmlspecialchars($outlet['phone'] ?? '-') ?></td>
                        <td>
                            <span class="badge <?= $outlet['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                                <?= $outlet['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <?php if (!isGuest()): ?>
                        <td>
                            <div class="action-btns">
                                <button class="btn btn-sm btn-edit" onclick='openOutletModal(<?= json_encode([
                                    "id" => $outlet["id"],
                                    "code" => $outlet["code"],
                                    "name" => $outlet["name"],
                                    "address" => $outlet["address"] ?? "",
                                    "city" => $outlet["city"] ?? "",
                                    "state" => $outlet["state"] ?? "",
                                    "phone" => $outlet["phone"] ?? "",
                                    "is_active" => $outlet["is_active"]
                                ]) ?>)'>
                                    <iconify-icon icon="material-symbols:edit-outline-rounded"></iconify-icon> Edit
                                </button>
                                <button class="btn btn-sm btn-delete" onclick="openDeleteModal('<?= ROOT ?>/dashboard/outlets/delete/<?= $outlet['id'] ?>', 'outlet')">
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
<!-- Outlet Add/Edit Modal -->
<div class="modal-overlay" id="outletModal">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 id="outletModalTitle">Add Outlet</h3>
            <button class="modal-close" onclick="closeModal('outletModal')">&times;</button>
        </div>
        <form id="outletForm" method="POST">
            <div class="modal-body">
                <div class="form-grid form-grid-1-2">
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" name="code" id="outletCode" class="form-control" required placeholder="BALLZ-01">
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" id="outletName" class="form-control" required placeholder="Outlet Name">
                    </div>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" id="outletAddress" class="form-control" required placeholder="Full address" rows="2"></textarea>
                </div>
                <div class="form-grid form-grid-3">
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" id="outletCity" class="form-control" placeholder="City">
                    </div>
                    <div class="form-group">
                        <label>State</label>
                        <input type="text" name="state" id="outletState" class="form-control" placeholder="State">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="text" name="phone" id="outletPhone" class="form-control" placeholder="Phone number">
                    </div>
                </div>
                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" id="outletActive" checked> Active
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeModal('outletModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" id="outletSubmitBtn">Add Outlet</button>
            </div>
        </form>
    </div>
</div>

<script>
function openOutletModal(data = null) {
    const form = document.getElementById('outletForm');
    const title = document.getElementById('outletModalTitle');
    const btn = document.getElementById('outletSubmitBtn');

    if (data && data.id) {
        title.textContent = 'Edit Outlet';
        btn.textContent = 'Update Outlet';
        form.action = '<?= ROOT ?>/dashboard/outlets/edit/' + data.id;
        document.getElementById('outletCode').value = data.code || '';
        document.getElementById('outletName').value = data.name || '';
        document.getElementById('outletAddress').value = data.address || '';
        document.getElementById('outletCity').value = data.city || '';
        document.getElementById('outletState').value = data.state || '';
        document.getElementById('outletPhone').value = data.phone || '';
        document.getElementById('outletActive').checked = data.is_active == 1;
    } else {
        title.textContent = 'Add Outlet';
        btn.textContent = 'Add Outlet';
        form.action = '<?= ROOT ?>/dashboard/outlets/add';
        form.reset();
        document.getElementById('outletActive').checked = true;
    }
    openModal('outletModal');
}
</script>
<?php endif; ?>
