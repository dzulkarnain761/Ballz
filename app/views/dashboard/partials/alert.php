<?php if (isset($_SESSION['success'])): ?>
                <div style="background: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                    <strong><?= $_SESSION['success']['title'] ?>!</strong> <?= $_SESSION['success']['message'] ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div style="background: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                    <strong><?= $_SESSION['error']['title'] ?>!</strong> <?= $_SESSION['error']['message'] ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>