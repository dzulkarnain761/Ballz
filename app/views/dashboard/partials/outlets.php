<div class="dashboard-card">
    <div class="card-header-row">
        <h2>Manage Outlets</h2>
    </div>

    <?php if (!isGuest()): ?>
    <div class="form-card">
        <h3>Add New Outlet</h3>
        <form action="<?= ROOT ?>/dashboard/outlet_add" method="POST">
            <div class="form-grid form-grid-1-2">
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
            <div class="form-grid form-grid-3">
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
    </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Location</th>
                    <th>Status</th>
                    <?php if (!isGuest()): ?><th>Actions</th><?php endif; ?>
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
                        <?php if (!isGuest()): ?>
                        <td>
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <a href="<?= ROOT ?>/dashboard/outlet_edit/<?= $outlet['id'] ?>" class="btn btn-sm btn-edit">Edit</a>
                                <a href="<?= ROOT ?>/dashboard/outlet_delete/<?= $outlet['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                            </div>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
