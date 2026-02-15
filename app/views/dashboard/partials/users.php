<div class="dashboard-card">
    <div class="card-header-row">
        <h2>Manage Customers</h2>
    </div>

    <div class="form-card">
        <h3>Add New Customer</h3>
        <form action="<?= ROOT ?>/dashboard/user_add" method="POST">
            <div class="form-grid form-grid-2">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="John Doe">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" placeholder="john@example.com">
                </div>
            </div>
            <div class="form-grid form-grid-3">
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" class="form-control" placeholder="012-3456789">
                </div>
                <div class="form-group">
                    <label>Reward Points</label>
                    <input type="number" name="reward_points" class="form-control" value="0">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="active">Active</option>
                        <option value="blocked">Blocked</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Add Customer</button>
        </form>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Contact</th>
                    <th>Points</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
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
                        <td>
                            <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                                <a href="<?= ROOT ?>/dashboard/user_edit/<?= $user['id'] ?>" class="btn btn-sm btn-edit">Edit</a>
                                <a href="<?= ROOT ?>/dashboard/user_delete/<?= $user['id'] ?>" class="btn btn-sm btn-delete" onclick="return confirm('Are you sure?')">Delete</a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
