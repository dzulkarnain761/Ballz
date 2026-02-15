<div class="dashboard-card">
    <div class="card-header-row">
        <h2>Recent Orders</h2>
    </div>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Outlet</th>
                    <th>Total</th>
                    <th>Type</th>
                    <th>Status</th>
                    <?php if (!isGuest()): ?><th>Actions</th><?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= $order['id'] ?></td>
                        <td><?= htmlspecialchars($order['user_name'] ?? 'Guest') ?></td>
                        <td><?= htmlspecialchars($order['outlet_name']) ?></td>
                        <td>RM<?= number_format($order['final_total'], 2) ?></td>
                        <td><?= ucfirst($order['order_type']) ?> <?= $order['table_number'] ? '(Table '.$order['table_number'].')' : '' ?></td>
                        <td>
                            <?php
                                $statusClass = 'badge-danger';
                                if($order['status'] == 'completed') $statusClass = 'badge-success';
                                if($order['status'] == 'pending') $statusClass = 'badge-pending';
                                if($order['status'] == 'paid') $statusClass = 'badge-success';
                            ?>
                            <span class="badge <?= $statusClass ?>">
                                <?= ucfirst($order['status']) ?>
                            </span>
                        </td>
                        <?php if (!isGuest()): ?>
                        <td>
                            <form action="<?= ROOT ?>/dashboard/order_status/<?= $order['id'] ?>" method="POST" style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
                                <select name="status" class="form-control" style="padding: 6px 10px; width: auto; font-size: 0.8rem;">
                                    <option value="pending" <?= $order['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                    <option value="paid" <?= $order['status'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                                    <option value="completed" <?= $order['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                                    <option value="cancelled" <?= $order['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
