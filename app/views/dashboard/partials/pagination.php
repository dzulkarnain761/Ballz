<?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
<div class="pagination-wrapper">
    <div class="pagination-info">
        Showing <?= (($pagination['page'] - 1) * $pagination['perPage']) + 1 ?>–<?= min($pagination['page'] * $pagination['perPage'], $pagination['totalItems']) ?> of <?= $pagination['totalItems'] ?>
    </div>
    <div class="pagination">
        <?php if ($pagination['page'] > 1): ?>
            <a href="<?= $pagination['baseUrl'] ?>?page=<?= $pagination['page'] - 1 ?>" class="pagination-btn">
                <iconify-icon icon="material-symbols:chevron-left-rounded"></iconify-icon>
            </a>
        <?php else: ?>
            <span class="pagination-btn disabled">
                <iconify-icon icon="material-symbols:chevron-left-rounded"></iconify-icon>
            </span>
        <?php endif; ?>

        <?php
            $totalPages = $pagination['totalPages'];
            $currentPage = $pagination['page'];
            $range = 2;
            $startPage = max(1, $currentPage - $range);
            $endPage = min($totalPages, $currentPage + $range);

            if ($startPage > 1):
        ?>
            <a href="<?= $pagination['baseUrl'] ?>?page=1" class="pagination-btn">1</a>
            <?php if ($startPage > 2): ?>
                <span class="pagination-ellipsis">…</span>
            <?php endif; ?>
        <?php endif; ?>

        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
            <?php if ($i == $currentPage): ?>
                <span class="pagination-btn active"><?= $i ?></span>
            <?php else: ?>
                <a href="<?= $pagination['baseUrl'] ?>?page=<?= $i ?>" class="pagination-btn"><?= $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($endPage < $totalPages): ?>
            <?php if ($endPage < $totalPages - 1): ?>
                <span class="pagination-ellipsis">…</span>
            <?php endif; ?>
            <a href="<?= $pagination['baseUrl'] ?>?page=<?= $totalPages ?>" class="pagination-btn"><?= $totalPages ?></a>
        <?php endif; ?>

        <?php if ($pagination['page'] < $totalPages): ?>
            <a href="<?= $pagination['baseUrl'] ?>?page=<?= $pagination['page'] + 1 ?>" class="pagination-btn">
                <iconify-icon icon="material-symbols:chevron-right-rounded"></iconify-icon>
            </a>
        <?php else: ?>
            <span class="pagination-btn disabled">
                <iconify-icon icon="material-symbols:chevron-right-rounded"></iconify-icon>
            </span>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>
