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
            margin-bottom: var(--spacing-lg);
        }

        .divider {
            display: flex;
            align-items: center;
            margin-bottom: var(--spacing-lg);
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

        .social-login {
            display: flex;
            flex-direction: column;
            gap: var(--spacing-md);
        }

        .btn-social {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 12px;
            border-radius: 16px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            border: 2px solid var(--border-color);
            color: var(--text-color);
            transition: all var(--transition-fast);
            background: transparent;
        }

        .btn-social:hover {
            background-color: var(--bg-color);
            border-color: var(--text-color);
            transform: translateY(-2px);
        }

        .btn-google { border-color: #DB4437; }
        .btn-google:hover { background-color: #DB4437; color: white; }
        .btn-google iconify-icon { color: #DB4437; font-size: 1.4rem; }
        .btn-google:hover iconify-icon { color: white; }

        .btn-facebook { border-color: #1877F2; }
        .btn-facebook:hover { background-color: #1877F2; color: white; }
        .btn-facebook iconify-icon { color: #1877F2; font-size: 1.4rem; }
        .btn-facebook:hover iconify-icon { color: white; }

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

            <div class="social-login">
                <a href="<?= ROOT ?>/auth/google" class="btn-social btn-google">
                    <iconify-icon icon="logos:google-icon"></iconify-icon>
                    Continue with Google
                </a>
                <a href="<?= ROOT ?>/auth/facebook" class="btn-social btn-facebook">
                    <iconify-icon icon="logos:facebook"></iconify-icon>
                    Continue with Facebook
                </a>
            </div>

            <div class="auth-footer">
                New to Ballz? Sign in with Google or Facebook to get started!
            </div>
        </div>
    </div>

    <script>
        // Simple dark mode support if needed (though it uses styles.css variables)
        const theme = localStorage.getItem('theme') || 'light';
        document.body.setAttribute('data-theme', theme);
    </script>
</body>
</html>
