<div class="dashboard-card">
    <div class="card-header-row">
        <h2>Manage Vouchers</h2>
        <?php if (!isGuest()): ?>
        <button class="btn btn-primary btn-add-new" onclick="openVoucherModal()">
            <iconify-icon icon="material-symbols:add-rounded"></iconify-icon> Add Voucher
        </button>
        <?php endif; ?>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Discount</th>
                    <th>Min Order</th>
                    <th>Validity</th>
                    <th>Status</th>
                    <?php if (!isGuest()): ?><th>Actions</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($vouchers)): ?>
                    <tr><td colspan="7" class="empty-state">No vouchers found</td></tr>
                <?php endif; ?>
                <?php foreach ($vouchers as $vouch): ?>
                    <tr>
                        <td><code><?= htmlspecialchars($vouch['code']) ?></code></td>
                        <td><?= htmlspecialchars($vouch['name']) ?></td>
                        <td>
                            <?= $vouch['discount_type'] == 'fixed' ? 'RM' : '' ?><?= number_format($vouch['discount_value'], 2) ?><?= $vouch['discount_type'] == 'percentage' ? '%' : '' ?>
                        </td>
                        <td>RM<?= number_format($vouch['min_order_amount'] ?? 0, 2) ?></td>
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
                        <?php if (!isGuest()): ?>
                        <td>
                            <div class="action-btns">
                                <button class="btn btn-sm btn-edit" onclick='openVoucherModal(<?= json_encode([
                                    "id" => $vouch["id"],
                                    "code" => $vouch["code"],
                                    "name" => $vouch["name"],
                                    "description" => $vouch["description"] ?? "",
                                    "discount_type" => $vouch["discount_type"],
                                    "discount_value" => $vouch["discount_value"],
                                    "min_order_amount" => $vouch["min_order_amount"] ?? "",
                                    "start_date" => $vouch["start_date"] ?? "",
                                    "end_date" => $vouch["end_date"] ?? "",
                                    "is_active" => $vouch["is_active"]
                                ]) ?>)'>
                                    <iconify-icon icon="material-symbols:edit-outline-rounded"></iconify-icon> Edit
                                </button>
                                <button class="btn btn-sm btn-delete" onclick="openDeleteModal('<?= ROOT ?>/dashboard/vouchers/delete/<?= $vouch['id'] ?>', 'voucher')">
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
<!-- Voucher Add/Edit Modal -->
<div class="modal-overlay" id="voucherModal">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 id="voucherModalTitle">Add Voucher</h3>
            <button class="modal-close" onclick="closeModal('voucherModal')">&times;</button>
        </div>
        <form id="voucherForm" method="POST">
            <div class="modal-body">
                <div class="form-grid form-grid-1-2">
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" name="code" id="voucherCode" class="form-control" required placeholder="WELCOME20">
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" id="voucherName" class="form-control" required placeholder="Voucher Name">
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" id="voucherDescription" class="form-control" placeholder="Short description" rows="2"></textarea>
                </div>
                <div class="form-grid form-grid-3">
                    <div class="form-group">
                        <label>Discount Type</label>
                        <select name="discount_type" id="voucherDiscountType" class="form-control" required>
                            <option value="fixed">Fixed RM</option>
                            <option value="percentage">Percentage %</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Value</label>
                        <input type="number" step="0.01" name="discount_value" id="voucherDiscountValue" class="form-control" required placeholder="0.00">
                    </div>
                    <div class="form-group">
                        <label>Min Order (RM)</label>
                        <input type="number" step="0.01" name="min_order_amount" id="voucherMinOrder" class="form-control" placeholder="0.00">
                    </div>
                </div>
                <div class="form-grid form-grid-2">
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" id="voucherStartDate" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" id="voucherEndDate" class="form-control">
                    </div>
                </div>
                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="is_active" id="voucherActive" checked> Active
                    </label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeModal('voucherModal')">Cancel</button>
                <button type="submit" class="btn btn-primary" id="voucherSubmitBtn">Add Voucher</button>
            </div>
        </form>
    </div>
</div>

<script>
function openVoucherModal(data = null) {
    const form = document.getElementById('voucherForm');
    const title = document.getElementById('voucherModalTitle');
    const btn = document.getElementById('voucherSubmitBtn');

    if (data && data.id) {
        title.textContent = 'Edit Voucher';
        btn.textContent = 'Update Voucher';
        form.action = '<?= ROOT ?>/dashboard/vouchers/edit/' + data.id;
        document.getElementById('voucherCode').value = data.code || '';
        document.getElementById('voucherName').value = data.name || '';
        document.getElementById('voucherDescription').value = data.description || '';
        document.getElementById('voucherDiscountType').value = data.discount_type || 'fixed';
        document.getElementById('voucherDiscountValue').value = data.discount_value || '';
        document.getElementById('voucherMinOrder').value = data.min_order_amount || '';
        document.getElementById('voucherStartDate').value = data.start_date || '';
        document.getElementById('voucherEndDate').value = data.end_date || '';
        document.getElementById('voucherActive').checked = data.is_active == 1;
    } else {
        title.textContent = 'Add Voucher';
        btn.textContent = 'Add Voucher';
        form.action = '<?= ROOT ?>/dashboard/vouchers/add';
        form.reset();
        document.getElementById('voucherActive').checked = true;
    }
    openModal('voucherModal');
}
</script>
<?php endif; ?>
