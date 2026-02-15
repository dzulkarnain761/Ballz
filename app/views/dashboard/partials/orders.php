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
                <?php if (empty($orders)): ?>
                    <tr><td colspan="7" class="empty-state">No orders found</td></tr>
                <?php endif; ?>
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
                            <div class="action-btns">
                                <button class="btn btn-sm btn-edit" onclick="openOrderStatusModal(<?= $order['id'] ?>, '<?= $order['status'] ?>', '#<?= $order['id'] ?> - <?= htmlspecialchars(addslashes($order['user_name'] ?? 'Guest')) ?>')">
                                    <iconify-icon icon="material-symbols:edit-outline-rounded"></iconify-icon> Status
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
<!-- Order Status Modal -->
<div class="modal-overlay" id="orderStatusModal">
    <div class="modal modal-sm">
        <div class="modal-header">
            <h3 id="orderStatusTitle">Update Order Status</h3>
            <button class="modal-close" onclick="closeModal('orderStatusModal')">&times;</button>
        </div>
        <form id="orderStatusForm" method="POST">
            <div class="modal-body">
                <p id="orderStatusInfo" style="margin-bottom: 16px; opacity: 0.7; font-size: 0.9rem;"></p>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="orderStatusSelect" class="form-control">
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeModal('orderStatusModal')">Cancel</button>
                <button type="submit" class="btn btn-primary">Update Status</button>
            </div>
        </form>
    </div>
</div>

<script>
function openOrderStatusModal(id, currentStatus, orderLabel) {
    const form = document.getElementById('orderStatusForm');
    document.getElementById('orderStatusTitle').textContent = 'Update Order Status';
    document.getElementById('orderStatusInfo').textContent = 'Order: ' + orderLabel;
    document.getElementById('orderStatusSelect').value = currentStatus;
    form.action = '<?= ROOT ?>/dashboard/orders/status/' + id;
    openModal('orderStatusModal');
}
</script>
<?php endif; ?>
