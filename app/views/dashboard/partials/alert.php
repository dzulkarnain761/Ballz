<?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success">
                    <iconify-icon icon="material-symbols:check-circle-outline-rounded" style="font-size: 1.3rem; flex-shrink: 0;"></iconify-icon>
                    <div><strong><?= $_SESSION['success']['title'] ?>!</strong> <?= $_SESSION['success']['message'] ?></div>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error">
                    <iconify-icon icon="material-symbols:error-outline-rounded" style="font-size: 1.3rem; flex-shrink: 0;"></iconify-icon>
                    <div><strong><?= $_SESSION['error']['title'] ?>!</strong> <?= $_SESSION['error']['message'] ?></div>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>