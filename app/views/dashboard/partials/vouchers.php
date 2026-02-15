<div class="dashboard-card">
    <div class="card-header-row">
        <h2>Manage Vouchers</h2>
    </div>

    <div class="form-card">
        <h3>Add New Voucher</h3>
        <form action="<?= ROOT ?>/dashboard/voucher_add" method="POST">
            <div class="form-grid form-grid-1-2">
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
            <div class="form-grid form-grid-3">
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
            <div class="form-grid form-grid-2">
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
    </div>

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
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <a href="<?= ROOT ?>/dashboard/voucher_edit/<?= $vouch['id'] ?>" class="btn btn-sm btn-edit">Edit</a>
                                <a href="<?= ROOT ?>/dashboard/voucher_delete/<?= $vouch['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
