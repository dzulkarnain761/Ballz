<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Ballz</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    
    <!-- Iconify -->
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    <link rel="stylesheet" href="<?= ROOT ?>/public/css/styles.css">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: var(--spacing-lg);
            background: linear-gradient(135deg, var(--bg-color) 0%, var(--card-bg) 100%);
        }

        .auth-card {
            background-color: var(--card-bg);
            padding: var(--spacing-xl);
            border-radius: 32px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            text-align: center;
            border: 1px solid var(--border-color);
        }

        .auth-logo {
            margin-bottom: var(--spacing-lg);
            display: inline-block;
        }

        .auth-title {
            font-family: var(--font-heading);
            font-size: 2rem;
            color: var(--text-color);
            margin-bottom: var(--spacing-sm);
        }

        .auth-subtitle {
            color: var(--text-color);
            opacity: 0.6;
            margin-bottom: var(--spacing-lg);
        }

        .auth-form {
            text-align: left;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--text-color);
            font-size: 0.9rem;
        }

        .input-group {
            position: relative;
            margin-bottom: var(--spacing-md);
        }

        .input-group iconify-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-color);
            opacity: 0.4;
            font-size: 1.2rem;
        }

        .auth-input {
            width: 100%;
            padding: 14px 14px 14px 48px;
            border-radius: 16px;
            border: 2px solid var(--border-color);
            background: var(--bg-color);
            color: var(--text-color);
            font-family: inherit;
            font-size: 1rem;
            transition: border-color var(--transition-fast);
        }

        .auth-input:focus {
            outline: none;
            border-color: var(--color-primary);
        }

        .auth-btn {
            width: 100%;
            margin-top: var(--spacing-md);
            margin-bottom: var(--spacing-md);
        }

        .divider {
            display: flex;
            align-items: center;
            margin-bottom: var(--spacing-md);
            color: var(--text-color);
            opacity: 0.4;
            font-size: 0.9rem;
        }

        .divider::before, .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: var(--border-color);
        }

        .divider::before { margin-right: 16px; }
        .divider::after { margin-left: 16px; }

        .btn-guest {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 14px;
            border-radius: 16px;
            border: 2px solid var(--border-color);
            background: transparent;
            color: var(--text-color);
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            transition: all 0.2s ease;
            margin-bottom: var(--spacing-lg);
        }

        .btn-guest:hover {
            background-color: var(--bg-color);
            border-color: var(--text-color);
            transform: translateY(-2px);
        }

        .btn-guest iconify-icon {
            font-size: 1.3rem;
            opacity: 0.7;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: var(--spacing-md);
            font-size: 0.9rem;
        }
        .alert-error { background: #fee; border: 1px solid #fcc; color: #c33; }
        .alert-success { background: #efe; border: 1px solid #cfc; color: #363; }

        .auth-footer {
            margin-top: var(--spacing-lg);
            font-size: 0.95rem;
            color: var(--text-color);
            opacity: 0.8;
        }

        .auth-footer a {
            color: var(--color-primary);
            text-decoration: none;
            font-weight: 700;
        }

        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="auth-card">
            <a href="<?= ROOT ?>" class="auth-logo">
                <span class="logo-text">BALLZ</span>
            </a>
            
            <h1 class="auth-title">Welcome Back!</h1>
            <p class="auth-subtitle">Log in to continue</p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form class="auth-form" method="POST" action="<?= ROOT ?>/auth/login">
                <?= csrfInput() ?>

                <label class="form-label" for="username">Username</label>
                <div class="input-group">
                    <iconify-icon icon="mdi:account-outline"></iconify-icon>
                    <input type="text" id="username" name="username" class="auth-input" placeholder="Enter your username" required autofocus>
                </div>

                <label class="form-label" for="password">Password</label>
                <div class="input-group">
                    <iconify-icon icon="mdi:lock-outline"></iconify-icon>
                    <input type="password" id="password" name="password" class="auth-input" placeholder="Enter your password" required>
                </div>

                <button type="submit" class="btn btn-primary auth-btn">Log In</button>
            </form>

            <div class="divider">or</div>

            <a href="<?= ROOT ?>/auth/guest" class="btn-guest">
                <iconify-icon icon="mdi:account-eye-outline"></iconify-icon>
                Continue as Guest
            </a>
        </div>
    </div>

    <script>
        // Simple dark mode support if needed (though it uses styles.css variables)
        const theme = localStorage.getItem('theme') || 'light';
        document.body.setAttribute('data-theme', theme);
    </script>
</body>
</html>
