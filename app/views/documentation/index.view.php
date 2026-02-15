<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Documentation | BALLZ</title>
    <meta name="description" content="Ballz REST API Documentation - Endpoints, authentication, and usage guide.">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;700;900&family=DM+Sans:wght@400;500;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <!-- Iconify -->
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    <link rel="stylesheet" href="<?= ROOT ?>/public/css/styles.css">

    <style>
        /* ── API Docs Layout ── */
        .docs-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .docs-sidebar {
            width: 280px;
            min-width: 280px;
            background: var(--card-bg);
            border-right: 1px solid var(--border-color);
            padding: var(--spacing-lg) 0;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .docs-sidebar-header {
            padding: 0 var(--spacing-lg);
            margin-bottom: var(--spacing-lg);
        }

        .docs-sidebar-header a {
            text-decoration: none;
        }

        .docs-sidebar-header .logo-text {
            font-size: 1.3rem;
        }

        .docs-sidebar-header .docs-label {
            font-family: var(--font-body);
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--color-primary);
            margin-top: 2px;
        }

        .docs-nav-group {
            margin-bottom: var(--spacing-md);
        }

        .docs-nav-group-title {
            font-family: var(--font-heading);
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--text-color);
            opacity: 0.5;
            padding: var(--spacing-sm) var(--spacing-lg);
        }

        .docs-nav-link {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: 8px var(--spacing-lg);
            text-decoration: none;
            color: var(--text-color);
            font-size: 0.875rem;
            font-weight: 500;
            transition: all var(--transition-fast);
            border-left: 3px solid transparent;
        }

        .docs-nav-link:hover {
            background: var(--bg-color);
            color: var(--color-primary);
        }

        .docs-nav-link.active {
            color: var(--color-primary);
            border-left-color: var(--color-primary);
            background: var(--bg-color);
        }

        .docs-nav-link iconify-icon {
            font-size: 1.1rem;
            opacity: 0.7;
        }

        /* ── Main Content ── */
        .docs-main {
            flex: 1;
            padding: var(--spacing-xl) var(--spacing-lg);
            max-width: 900px;
        }

        .docs-section {
            margin-bottom: var(--spacing-xl);
            scroll-margin-top: var(--spacing-lg);
        }

        .docs-section h2 {
            font-family: var(--font-heading);
            font-size: 1.75rem;
            font-weight: 900;
            margin-bottom: var(--spacing-md);
            color: var(--text-color);
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .docs-section h2 iconify-icon {
            color: var(--color-primary);
        }

        .docs-section h3 {
            font-family: var(--font-heading);
            font-size: 1.15rem;
            font-weight: 700;
            margin: var(--spacing-lg) 0 var(--spacing-sm);
            color: var(--text-color);
        }

        .docs-section p {
            margin-bottom: var(--spacing-md);
            line-height: 1.8;
            opacity: 0.85;
        }

        /* ── Base URL Badge ── */
        .base-url {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 10px 16px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.9rem;
            margin-bottom: var(--spacing-lg);
            color: var(--color-primary);
            font-weight: 500;
        }

        .base-url .label {
            font-family: var(--font-body);
            font-weight: 700;
            font-size: 0.7rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--text-color);
            opacity: 0.5;
        }

        /* ── Method Badges ── */
        .method-badge {
            display: inline-block;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 4px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .method-get { background: #d4edda; color: #155724; }
        .method-post { background: #cce5ff; color: #004085; }
        .method-put { background: #fff3cd; color: #856404; }
        .method-patch { background: #e2e3f1; color: #383d6e; }
        .method-delete { background: #f8d7da; color: #721c24; }

        [data-theme="dark"] .method-get { background: #1a3a1a; color: #7ddf7d; }
        [data-theme="dark"] .method-post { background: #1a2a3a; color: #7dbfff; }
        [data-theme="dark"] .method-put { background: #3a3420; color: #e6c860; }
        [data-theme="dark"] .method-patch { background: #2a2a3a; color: #a0a0e0; }
        [data-theme="dark"] .method-delete { background: #3a1a1a; color: #ff7d7d; }

        /* ── Public Badge ── */
        .public-badge {
            display: inline-block;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 10px;
            background: #d4edda;
            color: #155724;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-left: 6px;
        }

        [data-theme="dark"] .public-badge {
            background: #1a3a1a;
            color: #7ddf7d;
        }

        .auth-badge {
            display: inline-block;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 10px;
            background: #fff3cd;
            color: #856404;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-left: 6px;
        }

        [data-theme="dark"] .auth-badge {
            background: #3a3420;
            color: #e6c860;
        }

        /* ── Endpoint Card ── */
        .endpoint-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            margin-bottom: var(--spacing-md);
            overflow: hidden;
            transition: all var(--transition-fast);
        }

        .endpoint-card:hover {
            border-color: var(--color-primary);
            box-shadow: 0 2px 12px rgba(211, 84, 0, 0.08);
        }

        .endpoint-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            cursor: pointer;
            user-select: none;
        }

        .endpoint-path {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.875rem;
            font-weight: 500;
            flex: 1;
        }

        .endpoint-desc {
            font-size: 0.8rem;
            opacity: 0.6;
            margin-left: auto;
            white-space: nowrap;
        }

        .endpoint-toggle {
            font-size: 1.2rem;
            opacity: 0.4;
            transition: transform var(--transition-fast);
        }

        .endpoint-card.open .endpoint-toggle {
            transform: rotate(180deg);
        }

        .endpoint-body {
            display: none;
            padding: 0 20px 20px;
            border-top: 1px solid var(--border-color);
        }

        .endpoint-card.open .endpoint-body {
            display: block;
        }

        .endpoint-body p {
            margin: var(--spacing-md) 0 var(--spacing-sm);
            font-size: 0.85rem;
        }

        .endpoint-body h4 {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: var(--spacing-md);
            margin-bottom: var(--spacing-sm);
            opacity: 0.6;
        }

        /* ── Code Blocks ── */
        .code-block {
            background: #1e1e2e;
            color: #cdd6f4;
            border-radius: 8px;
            padding: 16px 20px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            line-height: 1.7;
            overflow-x: auto;
            margin: var(--spacing-sm) 0 var(--spacing-md);
            position: relative;
        }

        .code-block .comment { color: #6c7086; }
        .code-block .key { color: #89b4fa; }
        .code-block .string { color: #a6e3a1; }
        .code-block .number { color: #fab387; }
        .code-block .keyword { color: #cba6f7; }
        .code-block .url { color: #f9e2af; }

        .copy-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            background: rgba(255,255,255,0.1);
            border: none;
            color: #cdd6f4;
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 0.7rem;
            cursor: pointer;
            opacity: 0;
            transition: opacity var(--transition-fast);
        }

        .code-block:hover .copy-btn {
            opacity: 1;
        }

        .copy-btn:hover {
            background: rgba(255,255,255,0.2);
        }

        /* ── Params Table ── */
        .params-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.85rem;
            margin: var(--spacing-sm) 0;
        }

        .params-table th {
            text-align: left;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 8px 12px;
            background: var(--bg-color);
            opacity: 0.6;
            border-radius: 4px;
        }

        .params-table td {
            padding: 8px 12px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: top;
        }

        .params-table code {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.8rem;
            background: var(--bg-color);
            padding: 2px 6px;
            border-radius: 4px;
            color: var(--color-primary);
        }

        .param-required {
            color: #e74c3c;
            font-size: 0.7rem;
            font-weight: 700;
        }

        .param-optional {
            color: #95a5a6;
            font-size: 0.7rem;
        }

        /* ── Response Format ── */
        .response-label {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 4px;
            margin-bottom: 4px;
        }

        .response-success { background: #d4edda; color: #155724; }
        .response-error { background: #f8d7da; color: #721c24; }

        [data-theme="dark"] .response-success { background: #1a3a1a; color: #7ddf7d; }
        [data-theme="dark"] .response-error { background: #3a1a1a; color: #ff7d7d; }

        /* ── Auth Info Box ── */
        .info-box {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-left: 4px solid var(--color-primary);
            border-radius: 8px;
            padding: 16px 20px;
            margin: var(--spacing-md) 0;
        }

        .info-box.warning {
            border-left-color: #e67e22;
        }

        .info-box.info {
            border-left-color: #3498db;
        }

        .info-box p {
            margin: 0;
            font-size: 0.85rem;
        }

        .info-box strong {
            display: block;
            margin-bottom: 4px;
        }

        /* ── Status Codes Table ── */
        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: var(--spacing-sm);
            margin: var(--spacing-md) 0;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: 10px 14px;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.85rem;
        }

        .status-code {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .status-2xx { color: #27ae60; }
        .status-4xx { color: #e67e22; }
        .status-5xx { color: #e74c3c; }

        /* ── Rate Limit Badge ── */
        .rate-limit-info {
            display: flex;
            gap: var(--spacing-md);
            flex-wrap: wrap;
            margin: var(--spacing-md) 0;
        }

        .rate-limit-item {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: 10px 16px;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 0.85rem;
        }

        .rate-limit-item strong {
            font-family: 'JetBrains Mono', monospace;
            color: var(--color-primary);
        }

        /* ── Mobile Sidebar Toggle ── */
        .docs-mobile-toggle {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 20px;
            width: 50px;
            height: 50px;
            background: var(--color-primary);
            color: #fff;
            border: none;
            border-radius: 50%;
            font-size: 1.5rem;
            cursor: pointer;
            z-index: 200;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            align-items: center;
            justify-content: center;
        }

        /* ── Back to Top ── */
        .back-to-top {
            position: fixed;
            bottom: 80px;
            right: 20px;
            width: 40px;
            height: 40px;
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 100;
            transition: all var(--transition-fast);
            color: var(--text-color);
        }

        .back-to-top:hover {
            background: var(--color-primary);
            color: #fff;
            border-color: var(--color-primary);
        }

        .back-to-top.visible {
            display: flex;
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .docs-sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                z-index: 150;
                transition: left 0.3s ease;
                box-shadow: none;
            }

            .docs-sidebar.open {
                left: 0;
                box-shadow: 4px 0 24px rgba(0,0,0,0.2);
            }

            .docs-mobile-toggle {
                display: flex;
            }

            .docs-main {
                padding: var(--spacing-lg) var(--spacing-md);
            }

            .endpoint-desc {
                display: none;
            }

            .rate-limit-info {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile sidebar toggle -->
    <button class="docs-mobile-toggle" id="docsSidebarToggle" aria-label="Toggle navigation">
        <iconify-icon icon="material-symbols:menu-rounded"></iconify-icon>
    </button>

    <!-- Back to top -->
    <button class="back-to-top" id="backToTop" aria-label="Back to top">
        <iconify-icon icon="material-symbols:arrow-upward-rounded"></iconify-icon>
    </button>

    <div class="docs-wrapper">
        <!-- ─── Sidebar ─── -->
        <aside class="docs-sidebar" id="docsSidebar">
            <div class="docs-sidebar-header">
                <a href="<?= ROOT ?>/">
                    <div class="logo-text">BALLZ</div>
                    <div class="docs-label">API Documentation</div>
                </a>
            </div>

            <nav>
                <div class="docs-nav-group">
                    <div class="docs-nav-group-title">Getting Started</div>
                    <a href="#overview" class="docs-nav-link active">
                        <iconify-icon icon="material-symbols:info-outline-rounded"></iconify-icon> Overview
                    </a>
                    <a href="#authentication" class="docs-nav-link">
                        <iconify-icon icon="material-symbols:key-outline-rounded"></iconify-icon> Authentication
                    </a>
                    <a href="#rate-limiting" class="docs-nav-link">
                        <iconify-icon icon="material-symbols:speed-outline-rounded"></iconify-icon> Rate Limiting
                    </a>
                    <a href="#response-format" class="docs-nav-link">
                        <iconify-icon icon="material-symbols:code-rounded"></iconify-icon> Response Format
                    </a>
                    <a href="#status-codes" class="docs-nav-link">
                        <iconify-icon icon="material-symbols:error-outline-rounded"></iconify-icon> Status Codes
                    </a>
                </div>

                <div class="docs-nav-group">
                    <div class="docs-nav-group-title">Endpoints</div>
                    <a href="#menu" class="docs-nav-link">
                        <iconify-icon icon="material-symbols:restaurant-rounded"></iconify-icon> Menu
                    </a>
                    <a href="#outlets" class="docs-nav-link">
                        <iconify-icon icon="material-symbols:storefront-outline-rounded"></iconify-icon> Outlets
                    </a>
                    <a href="#vouchers" class="docs-nav-link">
                        <iconify-icon icon="material-symbols:confirmation-number-outline-rounded"></iconify-icon> Vouchers
                    </a>
                    <a href="#rewards" class="docs-nav-link">
                        <iconify-icon icon="material-symbols:star-outline-rounded"></iconify-icon> Rewards
                    </a>
                    <a href="#reward-transactions" class="docs-nav-link">
                        <iconify-icon icon="material-symbols:swap-vert-rounded"></iconify-icon> Reward Transactions
                    </a>
                    <a href="#users" class="docs-nav-link">
                        <iconify-icon icon="material-symbols:person-outline-rounded"></iconify-icon> Users
                    </a>
                    <a href="#orders" class="docs-nav-link">
                        <iconify-icon icon="material-symbols:receipt-long-outline-rounded"></iconify-icon> Orders
                    </a>
                    <a href="#auth-endpoint" class="docs-nav-link">
                        <iconify-icon icon="material-symbols:login-rounded"></iconify-icon> Auth
                    </a>
                </div>

                <div class="docs-nav-group">
                    <div class="docs-nav-group-title">Links</div>
                    <a href="<?= ROOT ?>/" class="docs-nav-link">
                        <iconify-icon icon="material-symbols:home-outline-rounded"></iconify-icon> Home
                    </a>
                    <a href="<?= ROOT ?>/dashboard" class="docs-nav-link">
                        <iconify-icon icon="material-symbols:dashboard-outline-rounded"></iconify-icon> Dashboard
                    </a>
                </div>
            </nav>

            <div style="padding: var(--spacing-lg); margin-top: auto;">
                <button id="docsThemeToggle" class="theme-toggle" aria-label="Toggle Dark Mode">
                    <iconify-icon icon="material-symbols:dark-mode-outline"></iconify-icon>
                </button>
            </div>
        </aside>

        <!-- ─── Main Content ─── -->
        <div class="docs-main">

            <!-- Overview -->
            <section id="overview" class="docs-section">
                <h2><iconify-icon icon="material-symbols:info-outline-rounded"></iconify-icon> Overview</h2>
                <p>The Ballz API is a RESTful service providing access to the restaurant's menu, outlets, vouchers, rewards, users, and orders. All responses are returned in JSON format.</p>

                <div class="base-url">
                    <span class="label">Base URL</span>
                    <?= ROOT ?>/api/v1
                </div>

                <div class="info-box info">
                    <p><strong>API Version: v1</strong>All endpoints are prefixed with <code>/api/v1/</code>. The API supports <code>GET</code> and <code>POST</code> HTTP methods.</p>
                </div>
            </section>

            <!-- Authentication -->
            <section id="authentication" class="docs-section">
                <h2><iconify-icon icon="material-symbols:key-outline-rounded"></iconify-icon> Authentication</h2>
                <p>Most endpoints require an API key for access. Some endpoints are public and do not require authentication.</p>

                <h3>Sending Your API Key</h3>
                <p>Include your API key in one of the following ways (in order of preference):</p>

                <div class="code-block">
<span class="comment"># Option 1: Authorization header (recommended)</span>
Authorization: Bearer <span class="string">your_api_key_here</span>

<span class="comment"># Option 2: X-API-Key header</span>
X-API-Key: <span class="string">your_api_key_here</span>

<span class="comment"># Option 3: Query parameter (least secure)</span>
<span class="url">GET /api/v1/users?api_key=your_api_key_here</span>
                </div>

                <h3>Public Endpoints</h3>
                <p>The following endpoints are publicly accessible without an API key:</p>
                <div style="display: flex; gap: 8px; flex-wrap: wrap; margin: var(--spacing-sm) 0;">
                    <code style="font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; background: var(--bg-color); padding: 4px 10px; border-radius: 6px;">/menu</code>
                    <code style="font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; background: var(--bg-color); padding: 4px 10px; border-radius: 6px;">/outlets</code>
                    <code style="font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; background: var(--bg-color); padding: 4px 10px; border-radius: 6px;">/vouchers</code>
                    <code style="font-family: 'JetBrains Mono', monospace; font-size: 0.85rem; background: var(--bg-color); padding: 4px 10px; border-radius: 6px;">/rewards</code>
                </div>

                <div class="info-box warning">
                    <p><strong><iconify-icon icon="material-symbols:warning-outline-rounded" style="vertical-align: middle;"></iconify-icon> Security Notice</strong>Never expose your API key in client-side code. Always use server-side requests when calling protected endpoints.</p>
                </div>
            </section>

            <!-- Rate Limiting -->
            <section id="rate-limiting" class="docs-section">
                <h2><iconify-icon icon="material-symbols:speed-outline-rounded"></iconify-icon> Rate Limiting</h2>
                <p>API requests are rate-limited per client IP address to ensure fair usage.</p>

                <div class="rate-limit-info">
                    <div class="rate-limit-item">
                        <iconify-icon icon="material-symbols:timer-outline-rounded"></iconify-icon>
                        <span>Window: <strong>1 hour</strong></span>
                    </div>
                    <div class="rate-limit-item">
                        <iconify-icon icon="material-symbols:data-usage-rounded"></iconify-icon>
                        <span>Limit: <strong>100 requests</strong></span>
                    </div>
                </div>

                <h3>Rate Limit Headers</h3>
                <p>Every response includes the following headers:</p>

                <table class="params-table">
                    <thead>
                        <tr><th>Header</th><th>Description</th></tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><code>X-RateLimit-Limit</code></td>
                            <td>Maximum requests allowed per window</td>
                        </tr>
                        <tr>
                            <td><code>X-RateLimit-Remaining</code></td>
                            <td>Remaining requests in current window</td>
                        </tr>
                        <tr>
                            <td><code>X-RateLimit-Reset</code></td>
                            <td>Unix timestamp when the window resets</td>
                        </tr>
                        <tr>
                            <td><code>Retry-After</code></td>
                            <td>Seconds to wait (only when limit exceeded)</td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <!-- Response Format -->
            <section id="response-format" class="docs-section">
                <h2><iconify-icon icon="material-symbols:code-rounded"></iconify-icon> Response Format</h2>
                <p>All API responses follow a consistent JSON structure.</p>

                <h3>Success Response</h3>
                <div class="response-label response-success">200 OK</div>
                <div class="code-block">
{
    <span class="key">"status"</span>: <span class="string">"success"</span>,
    <span class="key">"message"</span>: <span class="string">"Menu data retrieved successfully"</span>,
    <span class="key">"code"</span>: <span class="number">200</span>,
    <span class="key">"timestamp"</span>: <span class="string">"2026-02-15 12:00:00"</span>,
    <span class="key">"data"</span>: { ... }
}
                </div>

                <h3>Error Response</h3>
                <div class="response-label response-error">4xx / 5xx</div>
                <div class="code-block">
{
    <span class="key">"status"</span>: <span class="string">"error"</span>,
    <span class="key">"message"</span>: <span class="string">"Resource not found"</span>,
    <span class="key">"code"</span>: <span class="number">404</span>,
    <span class="key">"timestamp"</span>: <span class="string">"2026-02-15 12:00:00"</span>
}
                </div>
            </section>

            <!-- Status Codes -->
            <section id="status-codes" class="docs-section">
                <h2><iconify-icon icon="material-symbols:error-outline-rounded"></iconify-icon> Status Codes</h2>
                <div class="status-grid">
                    <div class="status-item">
                        <span class="status-code status-2xx">200</span> Success
                    </div>
                    <div class="status-item">
                        <span class="status-code status-2xx">201</span> Created
                    </div>
                    <div class="status-item">
                        <span class="status-code status-2xx">204</span> No Content
                    </div>
                    <div class="status-item">
                        <span class="status-code status-4xx">400</span> Bad Request
                    </div>
                    <div class="status-item">
                        <span class="status-code status-4xx">401</span> Unauthorized
                    </div>
                    <div class="status-item">
                        <span class="status-code status-4xx">403</span> Forbidden
                    </div>
                    <div class="status-item">
                        <span class="status-code status-4xx">404</span> Not Found
                    </div>
                    <div class="status-item">
                        <span class="status-code status-4xx">405</span> Method Not Allowed
                    </div>
                    <div class="status-item">
                        <span class="status-code status-4xx">422</span> Validation Error
                    </div>
                    <div class="status-item">
                        <span class="status-code status-4xx">429</span> Rate Limited
                    </div>
                    <div class="status-item">
                        <span class="status-code status-5xx">500</span> Server Error
                    </div>
                    <div class="status-item">
                        <span class="status-code status-5xx">501</span> Not Implemented
                    </div>
                </div>
            </section>

            <!-- ─── ENDPOINTS ─── -->

            <!-- Menu -->
            <section id="menu" class="docs-section">
                <h2><iconify-icon icon="material-symbols:restaurant-rounded"></iconify-icon> Menu</h2>
                <p>Access the complete restaurant menu including all items and categories.</p>

                <!-- GET /menu -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/api/v1/menu</span>
                        <span class="public-badge">Public</span>
                        <span class="endpoint-desc">Get all menu items</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Returns the complete menu data with all items.</p>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">GET</span> <span class="url"><?= ROOT ?>/api/v1/menu</span>
                        </div>

                        <h4>Response</h4>
                        <div class="response-label response-success">200 OK</div>
                        <div class="code-block">
{
    <span class="key">"status"</span>: <span class="string">"success"</span>,
    <span class="key">"message"</span>: <span class="string">"Menu data retrieved successfully"</span>,
    <span class="key">"code"</span>: <span class="number">200</span>,
    <span class="key">"timestamp"</span>: <span class="string">"2026-02-15 12:00:00"</span>,
    <span class="key">"data"</span>: {
        <span class="key">"items"</span>: [
            {
                <span class="key">"id"</span>: <span class="number">1</span>,
                <span class="key">"name"</span>: <span class="string">"Classic Cheese Bomb"</span>,
                <span class="key">"price"</span>: <span class="number">8.90</span>,
                <span class="key">"category_id"</span>: <span class="number">1</span>,
                ...
            }
        ]
    }
}
                        </div>
                    </div>
                </div>

                <!-- GET /menu/{id} -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/api/v1/menu/{id}</span>
                        <span class="public-badge">Public</span>
                        <span class="endpoint-desc">Get a single menu item</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Returns a specific menu item by its ID.</p>

                        <h4>Path Parameters</h4>
                        <table class="params-table">
                            <thead><tr><th>Param</th><th>Type</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><code>id</code></td>
                                    <td>integer</td>
                                    <td><span class="param-required">required</span> Menu item ID</td>
                                </tr>
                            </tbody>
                        </table>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">GET</span> <span class="url"><?= ROOT ?>/api/v1/menu/1</span>
                        </div>

                        <h4>Error Response</h4>
                        <div class="response-label response-error">404 Not Found</div>
                        <div class="code-block">
{
    <span class="key">"status"</span>: <span class="string">"error"</span>,
    <span class="key">"message"</span>: <span class="string">"Menu item not found"</span>,
    <span class="key">"code"</span>: <span class="number">404</span>,
    <span class="key">"timestamp"</span>: <span class="string">"2026-02-15 12:00:00"</span>
}
                        </div>
                    </div>
                </div>
            </section>

            <!-- Outlets -->
            <section id="outlets" class="docs-section">
                <h2><iconify-icon icon="material-symbols:storefront-outline-rounded"></iconify-icon> Outlets</h2>
                <p>Access restaurant outlet locations and details.</p>

                <!-- GET /outlets -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/api/v1/outlets</span>
                        <span class="public-badge">Public</span>
                        <span class="endpoint-desc">Get all outlets</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Returns a list of all restaurant outlet locations.</p>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">GET</span> <span class="url"><?= ROOT ?>/api/v1/outlets</span>
                        </div>
                    </div>
                </div>

                <!-- GET /outlets/{id} -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/api/v1/outlets/{id}</span>
                        <span class="public-badge">Public</span>
                        <span class="endpoint-desc">Get a single outlet</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Returns a specific outlet by its ID.</p>

                        <h4>Path Parameters</h4>
                        <table class="params-table">
                            <thead><tr><th>Param</th><th>Type</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><code>id</code></td>
                                    <td>integer</td>
                                    <td><span class="param-required">required</span> Outlet ID</td>
                                </tr>
                            </tbody>
                        </table>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">GET</span> <span class="url"><?= ROOT ?>/api/v1/outlets/1</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Vouchers -->
            <section id="vouchers" class="docs-section">
                <h2><iconify-icon icon="material-symbols:confirmation-number-outline-rounded"></iconify-icon> Vouchers</h2>
                <p>Access available promotional vouchers and their rules.</p>

                <!-- GET /vouchers -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/api/v1/vouchers</span>
                        <span class="public-badge">Public</span>
                        <span class="endpoint-desc">Get all vouchers</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Returns a list of all available vouchers.</p>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">GET</span> <span class="url"><?= ROOT ?>/api/v1/vouchers</span>
                        </div>
                    </div>
                </div>

                <!-- GET /vouchers/{id} -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/api/v1/vouchers/{id}</span>
                        <span class="public-badge">Public</span>
                        <span class="endpoint-desc">Get a voucher with rules</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Returns a specific voucher by its ID, including its associated rules.</p>

                        <h4>Path Parameters</h4>
                        <table class="params-table">
                            <thead><tr><th>Param</th><th>Type</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><code>id</code></td>
                                    <td>integer</td>
                                    <td><span class="param-required">required</span> Voucher ID</td>
                                </tr>
                            </tbody>
                        </table>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">GET</span> <span class="url"><?= ROOT ?>/api/v1/vouchers/1</span>
                        </div>

                        <h4>Response</h4>
                        <div class="response-label response-success">200 OK</div>
                        <div class="code-block">
{
    <span class="key">"status"</span>: <span class="string">"success"</span>,
    <span class="key">"data"</span>: {
        <span class="key">"id"</span>: <span class="number">1</span>,
        <span class="key">"code"</span>: <span class="string">"WELCOME10"</span>,
        ...
        <span class="key">"rules"</span>: [
            {
                <span class="key">"id"</span>: <span class="number">1</span>,
                <span class="key">"voucher_id"</span>: <span class="number">1</span>,
                ...
            }
        ]
    }
}
                        </div>
                    </div>
                </div>
            </section>

            <!-- Rewards -->
            <section id="rewards" class="docs-section">
                <h2><iconify-icon icon="material-symbols:star-outline-rounded"></iconify-icon> Rewards</h2>
                <p>Access the reward items catalog.</p>

                <!-- GET /rewards -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/api/v1/rewards</span>
                        <span class="public-badge">Public</span>
                        <span class="endpoint-desc">Get all reward items</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Returns a list of all available reward items.</p>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">GET</span> <span class="url"><?= ROOT ?>/api/v1/rewards</span>
                        </div>
                    </div>
                </div>

                <!-- GET /rewards/{id} -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/api/v1/rewards/{id}</span>
                        <span class="public-badge">Public</span>
                        <span class="endpoint-desc">Get a single reward item</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Returns a specific reward item by its ID.</p>

                        <h4>Path Parameters</h4>
                        <table class="params-table">
                            <thead><tr><th>Param</th><th>Type</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><code>id</code></td>
                                    <td>integer</td>
                                    <td><span class="param-required">required</span> Reward item ID</td>
                                </tr>
                            </tbody>
                        </table>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">GET</span> <span class="url"><?= ROOT ?>/api/v1/rewards/1</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Reward Transactions -->
            <section id="reward-transactions" class="docs-section">
                <h2><iconify-icon icon="material-symbols:swap-vert-rounded"></iconify-icon> Reward Transactions</h2>
                <p>Manage reward point transactions — earn points from orders and redeem them for reward items. <span class="auth-badge">API Key Required</span></p>

                <!-- GET /reward-transactions -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/api/v1/reward-transactions</span>
                        <span class="auth-badge">Auth</span>
                        <span class="endpoint-desc">Get all reward transactions</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Returns a list of all reward transactions across all users, ordered by most recent first.</p>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">GET</span> <span class="url"><?= ROOT ?>/api/v1/reward-transactions</span>
<span class="comment"># Header:</span>
Authorization: Bearer <span class="string">your_api_key</span>
                        </div>

                        <h4>Response</h4>
                        <div class="response-label response-success">200 OK</div>
                        <div class="code-block">
{
    <span class="key">"status"</span>: <span class="string">"success"</span>,
    <span class="key">"message"</span>: <span class="string">"Reward transactions retrieved successfully"</span>,
    <span class="key">"code"</span>: <span class="number">200</span>,
    <span class="key">"data"</span>: [
        {
            <span class="key">"id"</span>: <span class="number">1</span>,
            <span class="key">"user_id"</span>: <span class="number">1</span>,
            <span class="key">"order_id"</span>: <span class="number">5</span>,
            <span class="key">"points"</span>: <span class="number">20</span>,
            <span class="key">"type"</span>: <span class="string">"earn"</span>,
            <span class="key">"created_at"</span>: <span class="string">"2026-02-15 12:00:00"</span>,
            <span class="key">"user_name"</span>: <span class="string">"John Doe"</span>,
            <span class="key">"user_email"</span>: <span class="string">"john@example.com"</span>
        }
    ]
}
                        </div>
                    </div>
                </div>

                <!-- GET /reward-transactions/{id} -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/api/v1/reward-transactions/{id}</span>
                        <span class="auth-badge">Auth</span>
                        <span class="endpoint-desc">Get a single transaction</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Returns a specific reward transaction by its ID.</p>

                        <h4>Path Parameters</h4>
                        <table class="params-table">
                            <thead><tr><th>Param</th><th>Type</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><code>id</code></td>
                                    <td>integer</td>
                                    <td><span class="param-required">required</span> Reward transaction ID</td>
                                </tr>
                            </tbody>
                        </table>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">GET</span> <span class="url"><?= ROOT ?>/api/v1/reward-transactions/1</span>
<span class="comment"># Header:</span>
Authorization: Bearer <span class="string">your_api_key</span>
                        </div>

                        <h4>Error Response</h4>
                        <div class="response-label response-error">404 Not Found</div>
                        <div class="code-block">
{
    <span class="key">"status"</span>: <span class="string">"error"</span>,
    <span class="key">"message"</span>: <span class="string">"Reward transaction not found"</span>,
    <span class="key">"code"</span>: <span class="number">404</span>
}
                        </div>
                    </div>
                </div>

                <!-- GET /users/{id}/reward-transactions -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/api/v1/users/{id}/reward-transactions</span>
                        <span class="auth-badge">Auth</span>
                        <span class="endpoint-desc">Get user's transactions</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Returns all reward transactions for a specific user, along with their current points balance.</p>

                        <h4>Path Parameters</h4>
                        <table class="params-table">
                            <thead><tr><th>Param</th><th>Type</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><code>id</code></td>
                                    <td>integer</td>
                                    <td><span class="param-required">required</span> User ID</td>
                                </tr>
                            </tbody>
                        </table>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">GET</span> <span class="url"><?= ROOT ?>/api/v1/users/1/reward-transactions</span>
<span class="comment"># Header:</span>
Authorization: Bearer <span class="string">your_api_key</span>
                        </div>

                        <h4>Response</h4>
                        <div class="response-label response-success">200 OK</div>
                        <div class="code-block">
{
    <span class="key">"status"</span>: <span class="string">"success"</span>,
    <span class="key">"message"</span>: <span class="string">"User reward transactions retrieved successfully"</span>,
    <span class="key">"code"</span>: <span class="number">200</span>,
    <span class="key">"data"</span>: {
        <span class="key">"user_id"</span>: <span class="number">1</span>,
        <span class="key">"reward_points"</span>: <span class="number">150</span>,
        <span class="key">"transactions"</span>: [
            {
                <span class="key">"id"</span>: <span class="number">3</span>,
                <span class="key">"user_id"</span>: <span class="number">1</span>,
                <span class="key">"order_id"</span>: <span class="keyword">null</span>,
                <span class="key">"points"</span>: <span class="number">1000</span>,
                <span class="key">"type"</span>: <span class="string">"redeem"</span>,
                <span class="key">"created_at"</span>: <span class="string">"2026-02-15 14:00:00"</span>
            },
            {
                <span class="key">"id"</span>: <span class="number">1</span>,
                <span class="key">"user_id"</span>: <span class="number">1</span>,
                <span class="key">"order_id"</span>: <span class="number">5</span>,
                <span class="key">"points"</span>: <span class="number">20</span>,
                <span class="key">"type"</span>: <span class="string">"earn"</span>,
                <span class="key">"created_at"</span>: <span class="string">"2026-02-15 12:00:00"</span>
            }
        ]
    }
}
                        </div>
                    </div>
                </div>

                <!-- GET /users/{id}/reward-transactions/{transactionId} -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/api/v1/users/{id}/reward-transactions/{transactionId}</span>
                        <span class="auth-badge">Auth</span>
                        <span class="endpoint-desc">Get specific user transaction</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Returns a specific reward transaction for a user. Verifies the transaction belongs to the specified user.</p>

                        <h4>Path Parameters</h4>
                        <table class="params-table">
                            <thead><tr><th>Param</th><th>Type</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><code>id</code></td>
                                    <td>integer</td>
                                    <td><span class="param-required">required</span> User ID</td>
                                </tr>
                                <tr>
                                    <td><code>transactionId</code></td>
                                    <td>integer</td>
                                    <td><span class="param-required">required</span> Reward transaction ID</td>
                                </tr>
                            </tbody>
                        </table>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">GET</span> <span class="url"><?= ROOT ?>/api/v1/users/1/reward-transactions/3</span>
<span class="comment"># Header:</span>
Authorization: Bearer <span class="string">your_api_key</span>
                        </div>

                        <h4>Error Responses</h4>
                        <div class="response-label response-error">404 Not Found</div>
                        <p>User or reward transaction not found.</p>
                        <div class="response-label response-error">403 Forbidden</div>
                        <p>Reward transaction does not belong to the specified user.</p>
                    </div>
                </div>

                <!-- POST /reward-transactions -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-path">/api/v1/reward-transactions</span>
                        <span class="auth-badge">Auth</span>
                        <span class="endpoint-desc">Create a reward transaction</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Creates a new reward transaction. Use <code>type: "redeem"</code> to redeem points for a reward item, or <code>type: "earn"</code> to manually award points. Note: earn transactions are created automatically when placing orders (1 point per RM1 spent).</p>

                        <h4>Request Body</h4>
                        <table class="params-table">
                            <thead><tr><th>Field</th><th>Type</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><code>user_id</code></td>
                                    <td>integer</td>
                                    <td><span class="param-required">required</span> Customer's user ID</td>
                                </tr>
                                <tr>
                                    <td><code>type</code></td>
                                    <td>string</td>
                                    <td><span class="param-required">required</span> <code>earn</code> or <code>redeem</code></td>
                                </tr>
                                <tr>
                                    <td><code>reward_item_id</code></td>
                                    <td>integer</td>
                                    <td><span class="param-required">required for redeem</span> The reward item to redeem. Points deducted = item's <code>required_points</code></td>
                                </tr>
                                <tr>
                                    <td><code>points</code></td>
                                    <td>integer</td>
                                    <td><span class="param-required">required for earn</span> Number of points to award (min 1). Ignored for redeem.</td>
                                </tr>
                                <tr>
                                    <td><code>order_id</code></td>
                                    <td>integer</td>
                                    <td><span class="param-optional">optional</span> Link the transaction to an existing order</td>
                                </tr>
                            </tbody>
                        </table>

                        <h4>Example Request (Redeem)</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">POST</span> <span class="url"><?= ROOT ?>/api/v1/reward-transactions</span>
<span class="comment"># Header:</span>
Authorization: Bearer <span class="string">your_api_key</span>
Content-Type: application/json

{
    <span class="key">"user_id"</span>: <span class="number">1</span>,
    <span class="key">"type"</span>: <span class="string">"redeem"</span>,
    <span class="key">"reward_item_id"</span>: <span class="number">2</span>
}
                        </div>

                        <h4>Example Request (Earn)</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">POST</span> <span class="url"><?= ROOT ?>/api/v1/reward-transactions</span>
<span class="comment"># Header:</span>
Authorization: Bearer <span class="string">your_api_key</span>
Content-Type: application/json

{
    <span class="key">"user_id"</span>: <span class="number">1</span>,
    <span class="key">"type"</span>: <span class="string">"earn"</span>,
    <span class="key">"points"</span>: <span class="number">50</span>,
    <span class="key">"order_id"</span>: <span class="number">10</span>
}
                        </div>

                        <h4>Response (Redeem)</h4>
                        <div class="response-label response-success">201 Created</div>
                        <div class="code-block">
{
    <span class="key">"status"</span>: <span class="string">"success"</span>,
    <span class="key">"message"</span>: <span class="string">"Reward transaction created successfully"</span>,
    <span class="key">"code"</span>: <span class="number">201</span>,
    <span class="key">"data"</span>: {
        <span class="key">"transaction"</span>: {
            <span class="key">"id"</span>: <span class="number">3</span>,
            <span class="key">"user_id"</span>: <span class="number">1</span>,
            <span class="key">"order_id"</span>: <span class="keyword">null</span>,
            <span class="key">"points"</span>: <span class="number">1000</span>,
            <span class="key">"type"</span>: <span class="string">"redeem"</span>,
            <span class="key">"created_at"</span>: <span class="string">"2026-02-15 14:00:00"</span>,
            <span class="key">"user_name"</span>: <span class="string">"John Doe"</span>,
            <span class="key">"user_email"</span>: <span class="string">"john@example.com"</span>
        },
        <span class="key">"reward_points_balance"</span>: <span class="number">150</span>,
        <span class="key">"reward_item"</span>: {
            <span class="key">"id"</span>: <span class="number">2</span>,
            <span class="key">"item_name"</span>: <span class="string">"Chocolate Milk"</span>,
            <span class="key">"required_points"</span>: <span class="number">1000</span>
        }
    }
}
                        </div>

                        <h4>Validation Rules</h4>
                        <div class="info-box">
                            <p>
                                <strong>Transaction Validation</strong>
                                &bull; User must exist and be <code>active</code> (not blocked)<br>
                                &bull; For <code>redeem</code>: reward item must exist and user must have enough points<br>
                                &bull; For <code>earn</code>: points must be at least 1<br>
                                &bull; If <code>order_id</code> is provided, the order must exist
                            </p>
                        </div>

                        <h4>Error Responses</h4>
                        <div class="response-label response-error">400 Bad Request</div>
                        <p>Missing or invalid fields, insufficient points.</p>
                        <div class="response-label response-error">404 Not Found</div>
                        <p>User, reward item, or order not found.</p>
                        <div class="response-label response-error">403 Forbidden</div>
                        <p>User account is blocked.</p>
                    </div>
                </div>
            </section>

            <!-- Users -->
            <section id="users" class="docs-section">
                <h2><iconify-icon icon="material-symbols:person-outline-rounded"></iconify-icon> Users</h2>
                <p>Access user accounts and their order history. <span class="auth-badge">API Key Required</span></p>

                <!-- GET /users -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/api/v1/users</span>
                        <span class="auth-badge">Auth</span>
                        <span class="endpoint-desc">Get all users</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Returns a list of all users. Sensitive data (passwords) is excluded from the response.</p>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">GET</span> <span class="url"><?= ROOT ?>/api/v1/users</span>
<span class="comment"># Header:</span>
Authorization: Bearer <span class="string">your_api_key</span>
                        </div>
                    </div>
                </div>

                <!-- GET /users/{id} -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/api/v1/users/{id}</span>
                        <span class="auth-badge">Auth</span>
                        <span class="endpoint-desc">Get a single user</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Returns a specific user by ID. Optionally include their order history.</p>

                        <h4>Path Parameters</h4>
                        <table class="params-table">
                            <thead><tr><th>Param</th><th>Type</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><code>id</code></td>
                                    <td>integer</td>
                                    <td><span class="param-required">required</span> User ID</td>
                                </tr>
                            </tbody>
                        </table>

                        <h4>Query Parameters</h4>
                        <table class="params-table">
                            <thead><tr><th>Param</th><th>Type</th><th>Default</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><code>include_orders</code></td>
                                    <td>string</td>
                                    <td><code>false</code></td>
                                    <td><span class="param-optional">optional</span> Set to <code>true</code> to include user's orders</td>
                                </tr>
                                <tr>
                                    <td><code>order_details</code></td>
                                    <td>string</td>
                                    <td><code>false</code></td>
                                    <td><span class="param-optional">optional</span> Set to <code>true</code> to include full order details (items, vouchers)</td>
                                </tr>
                            </tbody>
                        </table>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">GET</span> <span class="url"><?= ROOT ?>/api/v1/users/5?include_orders=true&order_details=true</span>
<span class="comment"># Header:</span>
Authorization: Bearer <span class="string">your_api_key</span>
                        </div>
                    </div>
                </div>

                <!-- GET /users/{id}/orders -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/api/v1/users/{id}/orders</span>
                        <span class="auth-badge">Auth</span>
                        <span class="endpoint-desc">Get user's orders</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Returns all orders for a specific user. Supports nested resource access.</p>

                        <h4>Path Parameters</h4>
                        <table class="params-table">
                            <thead><tr><th>Param</th><th>Type</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><code>id</code></td>
                                    <td>integer</td>
                                    <td><span class="param-required">required</span> User ID</td>
                                </tr>
                            </tbody>
                        </table>

                        <h4>Query Parameters</h4>
                        <table class="params-table">
                            <thead><tr><th>Param</th><th>Type</th><th>Default</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><code>details</code></td>
                                    <td>string</td>
                                    <td><code>false</code></td>
                                    <td><span class="param-optional">optional</span> Set to <code>true</code> to include full order details</td>
                                </tr>
                            </tbody>
                        </table>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">GET</span> <span class="url"><?= ROOT ?>/api/v1/users/5/orders?details=true</span>
<span class="comment"># Header:</span>
Authorization: Bearer <span class="string">your_api_key</span>
                        </div>
                    </div>
                </div>

                <!-- GET /users/{id}/orders/{orderId} -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-get">GET</span>
                        <span class="endpoint-path">/api/v1/users/{id}/orders/{orderId}</span>
                        <span class="auth-badge">Auth</span>
                        <span class="endpoint-desc">Get specific user order</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Returns a specific order for a user. Verifies the order belongs to the specified user.</p>

                        <h4>Path Parameters</h4>
                        <table class="params-table">
                            <thead><tr><th>Param</th><th>Type</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><code>id</code></td>
                                    <td>integer</td>
                                    <td><span class="param-required">required</span> User ID</td>
                                </tr>
                                <tr>
                                    <td><code>orderId</code></td>
                                    <td>integer</td>
                                    <td><span class="param-required">required</span> Order ID</td>
                                </tr>
                            </tbody>
                        </table>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">GET</span> <span class="url"><?= ROOT ?>/api/v1/users/5/orders/42</span>
<span class="comment"># Header:</span>
Authorization: Bearer <span class="string">your_api_key</span>
                        </div>

                        <h4>Error Responses</h4>
                        <div class="response-label response-error">404 Not Found</div>
                        <p>User or order not found.</p>
                        <div class="response-label response-error">403 Forbidden</div>
                        <p>Order does not belong to the specified user.</p>
                    </div>
                </div>
            </section>

            <!-- Orders -->
            <section id="orders" class="docs-section">
                <h2><iconify-icon icon="material-symbols:receipt-long-outline-rounded"></iconify-icon> Orders</h2>
                <p>Create and manage customer orders. <span class="auth-badge">API Key Required</span></p>

                <!-- POST /orders -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-path">/api/v1/orders</span>
                        <span class="auth-badge">Auth</span>
                        <span class="endpoint-desc">Create an order</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Creates a new order with items and optional vouchers. The server calculates all totals from menu item prices — client-submitted prices are ignored for security. Reward points are automatically awarded (1 point per RM1 spent).</p>

                        <h4>Request Body</h4>
                        <table class="params-table">
                            <thead><tr><th>Field</th><th>Type</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><code>user_id</code></td>
                                    <td>integer</td>
                                    <td><span class="param-required">required</span> Customer's user ID</td>
                                </tr>
                                <tr>
                                    <td><code>outlet_id</code></td>
                                    <td>integer</td>
                                    <td><span class="param-required">required</span> Outlet ID to place the order at</td>
                                </tr>
                                <tr>
                                    <td><code>order_type</code></td>
                                    <td>string</td>
                                    <td><span class="param-required">required</span> <code>pickup</code> or <code>dine_in</code></td>
                                </tr>
                                <tr>
                                    <td><code>items</code></td>
                                    <td>array</td>
                                    <td><span class="param-required">required</span> Array of order items (min 1). Each item: <code>{ "menu_item_id": int, "quantity": int }</code></td>
                                </tr>
                                <tr>
                                    <td><code>voucher_codes</code></td>
                                    <td>array</td>
                                    <td><span class="param-optional">optional</span> Array of voucher code strings to apply</td>
                                </tr>
                            </tbody>
                        </table>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">POST</span> <span class="url"><?= ROOT ?>/api/v1/orders</span>
<span class="comment"># Header:</span>
Authorization: Bearer <span class="string">your_api_key</span>
Content-Type: application/json

{
    <span class="key">"user_id"</span>: <span class="number">1</span>,
    <span class="key">"outlet_id"</span>: <span class="number">3</span>,
    <span class="key">"order_type"</span>: <span class="string">"pickup"</span>,
    <span class="key">"items"</span>: [
        { <span class="key">"menu_item_id"</span>: <span class="number">1</span>, <span class="key">"quantity"</span>: <span class="number">2</span> },
        { <span class="key">"menu_item_id"</span>: <span class="number">5</span>, <span class="key">"quantity"</span>: <span class="number">1</span> }
    ],
    <span class="key">"voucher_codes"</span>: [<span class="string">"BALLZ5"</span>]
}
                        </div>

                        <h4>Response</h4>
                        <div class="response-label response-success">201 Created</div>
                        <div class="code-block">
{
    <span class="key">"status"</span>: <span class="string">"success"</span>,
    <span class="key">"message"</span>: <span class="string">"Order created successfully"</span>,
    <span class="key">"code"</span>: <span class="number">201</span>,
    <span class="key">"timestamp"</span>: <span class="string">"2026-02-15 12:00:00"</span>,
    <span class="key">"data"</span>: {
        <span class="key">"id"</span>: <span class="number">1</span>,
        <span class="key">"user_id"</span>: <span class="number">1</span>,
        <span class="key">"outlet_id"</span>: <span class="number">3</span>,
        <span class="key">"outlet_name"</span>: <span class="string">"Ballz Johor Bahru"</span>,
        <span class="key">"order_type"</span>: <span class="string">"pickup"</span>,
        <span class="key">"subtotal"</span>: <span class="number">25.70</span>,
        <span class="key">"discount_total"</span>: <span class="number">5.00</span>,
        <span class="key">"final_total"</span>: <span class="number">20.70</span>,
        <span class="key">"status"</span>: <span class="string">"pending"</span>,
        <span class="key">"items"</span>: [
            {
                <span class="key">"menu_item_id"</span>: <span class="number">1</span>,
                <span class="key">"item_name"</span>: <span class="string">"Classic Cheese Bomb"</span>,
                <span class="key">"quantity"</span>: <span class="number">2</span>,
                <span class="key">"unit_price"</span>: <span class="number">8.90</span>,
                <span class="key">"total_price"</span>: <span class="number">17.80</span>
            },
            {
                <span class="key">"menu_item_id"</span>: <span class="number">5</span>,
                <span class="key">"item_name"</span>: <span class="string">"Nutella Delight"</span>,
                <span class="key">"quantity"</span>: <span class="number">1</span>,
                <span class="key">"unit_price"</span>: <span class="number">7.90</span>,
                <span class="key">"total_price"</span>: <span class="number">7.90</span>
            }
        ],
        <span class="key">"vouchers"</span>: [
            {
                <span class="key">"voucher_code"</span>: <span class="string">"BALLZ5"</span>,
                <span class="key">"voucher_name"</span>: <span class="string">"RM5 OFF"</span>,
                <span class="key">"discount_applied"</span>: <span class="number">5.00</span>
            }
        ],
        <span class="key">"points_earned"</span>: <span class="number">20</span>
    }
}
                        </div>

                        <h4>Validation Rules</h4>
                        <div class="info-box">
                            <p>
                                <strong>Order Validation</strong>
                                &bull; User must exist and be <code>active</code> (not blocked)<br>
                                &bull; Outlet must exist and be active<br>
                                &bull; Each menu item must exist and be active<br>
                                &bull; Item quantity must be at least 1<br>
                                &bull; Vouchers must be active, within validity dates, and meet minimum order amount<br>
                                &bull; Total discount cannot exceed the subtotal
                            </p>
                        </div>

                        <h4>Error Responses</h4>
                        <div class="response-label response-error">400 Bad Request</div>
                        <p>Missing or invalid fields, inactive items/outlets, voucher validation failures.</p>
                        <div class="response-label response-error">404 Not Found</div>
                        <p>User, outlet, or menu item not found.</p>
                        <div class="response-label response-error">403 Forbidden</div>
                        <p>User account is blocked.</p>
                    </div>
                </div>
            </section>

            <!-- Auth Endpoint -->
            <section id="auth-endpoint" class="docs-section">
                <h2><iconify-icon icon="material-symbols:login-rounded"></iconify-icon> Auth</h2>
                <p>Authenticate users via social login providers. Automatically registers new users. <span class="auth-badge">API Key Required</span></p>

                <!-- POST /auth -->
                <div class="endpoint-card">
                    <div class="endpoint-header" onclick="this.parentElement.classList.toggle('open')">
                        <span class="method-badge method-post">POST</span>
                        <span class="endpoint-path">/api/v1/auth</span>
                        <span class="auth-badge">Auth</span>
                        <span class="endpoint-desc">Login or register user</span>
                        <iconify-icon class="endpoint-toggle" icon="material-symbols:expand-more-rounded"></iconify-icon>
                    </div>
                    <div class="endpoint-body">
                        <p>Checks if a user exists by their social provider identity. If the user exists, returns their data. If not, automatically registers and returns the new user.</p>

                        <h4>Request Body</h4>
                        <table class="params-table">
                            <thead><tr><th>Field</th><th>Type</th><th>Description</th></tr></thead>
                            <tbody>
                                <tr>
                                    <td><code>provider</code></td>
                                    <td>string</td>
                                    <td><span class="param-required">required</span> Social provider: <code>google</code> or <code>facebook</code></td>
                                </tr>
                                <tr>
                                    <td><code>provider_user_id</code></td>
                                    <td>string</td>
                                    <td><span class="param-required">required</span> User's unique ID from the provider</td>
                                </tr>
                                <tr>
                                    <td><code>name</code></td>
                                    <td>string</td>
                                    <td><span class="param-required">required</span> User's display name</td>
                                </tr>
                                <tr>
                                    <td><code>email</code></td>
                                    <td>string</td>
                                    <td><span class="param-optional">optional</span> User's email address</td>
                                </tr>
                                <tr>
                                    <td><code>phone</code></td>
                                    <td>string</td>
                                    <td><span class="param-optional">optional</span> User's phone number</td>
                                </tr>
                            </tbody>
                        </table>

                        <h4>Example Request</h4>
                        <div class="code-block">
<button class="copy-btn" onclick="copyCode(this)">Copy</button>
<span class="keyword">POST</span> <span class="url"><?= ROOT ?>/api/v1/auth</span>
<span class="comment"># Header:</span>
Authorization: Bearer <span class="string">your_api_key</span>
Content-Type: application/json

{
    <span class="key">"provider"</span>: <span class="string">"google"</span>,
    <span class="key">"provider_user_id"</span>: <span class="string">"118234567890"</span>,
    <span class="key">"name"</span>: <span class="string">"John Doe"</span>,
    <span class="key">"email"</span>: <span class="string">"john@example.com"</span>,
    <span class="key">"phone"</span>: <span class="string">"+60123456789"</span>
}
                        </div>

                        <h4>Response (Existing User)</h4>
                        <div class="response-label response-success">200 OK</div>
                        <div class="code-block">
{
    <span class="key">"status"</span>: <span class="string">"success"</span>,
    <span class="key">"message"</span>: <span class="string">"User found"</span>,
    <span class="key">"code"</span>: <span class="number">200</span>,
    <span class="key">"data"</span>: {
        <span class="key">"id"</span>: <span class="number">5</span>,
        <span class="key">"name"</span>: <span class="string">"John Doe"</span>,
        <span class="key">"email"</span>: <span class="string">"john@example.com"</span>,
        <span class="key">"is_new_user"</span>: <span class="keyword">false</span>,
        ...
    }
}
                        </div>

                        <h4>Response (New User)</h4>
                        <div class="response-label response-success">201 Created</div>
                        <div class="code-block">
{
    <span class="key">"status"</span>: <span class="string">"success"</span>,
    <span class="key">"message"</span>: <span class="string">"User registered successfully"</span>,
    <span class="key">"code"</span>: <span class="number">201</span>,
    <span class="key">"data"</span>: {
        <span class="key">"id"</span>: <span class="number">12</span>,
        <span class="key">"name"</span>: <span class="string">"John Doe"</span>,
        <span class="key">"email"</span>: <span class="string">"john@example.com"</span>,
        <span class="key">"is_new_user"</span>: <span class="keyword">true</span>,
        ...
    }
}
                        </div>
                    </div>
                </div>
            </section>

        </div><!-- /.docs-main -->
    </div><!-- /.docs-wrapper -->

    <script>
        // ── Theme Toggle ──
        const docsThemeToggle = document.getElementById('docsThemeToggle');
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.documentElement.setAttribute('data-theme', savedTheme);

        docsThemeToggle.addEventListener('click', () => {
            const current = document.documentElement.getAttribute('data-theme');
            const next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('theme', next);
        });

        // ── Mobile Sidebar ──
        const sidebarToggle = document.getElementById('docsSidebarToggle');
        const sidebar = document.getElementById('docsSidebar');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });

        // Close sidebar when clicking a link on mobile
        sidebar.querySelectorAll('.docs-nav-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    sidebar.classList.remove('open');
                }
            });
        });

        // ── Active Nav Link on Scroll ──
        const sections = document.querySelectorAll('.docs-section');
        const navLinks = document.querySelectorAll('.docs-nav-link[href^="#"]');

        const observerOptions = {
            rootMargin: '-20% 0px -70% 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    navLinks.forEach(link => link.classList.remove('active'));
                    const activeLink = document.querySelector(`.docs-nav-link[href="#${entry.target.id}"]`);
                    if (activeLink) activeLink.classList.add('active');
                }
            });
        }, observerOptions);

        sections.forEach(section => observer.observe(section));

        // ── Back to Top ──
        const backToTop = document.getElementById('backToTop');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 400) {
                backToTop.classList.add('visible');
            } else {
                backToTop.classList.remove('visible');
            }
        });

        backToTop.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        // ── Copy Code ──
        function copyCode(btn) {
            const codeBlock = btn.parentElement;
            const text = codeBlock.innerText.replace('Copy', '').trim();
            navigator.clipboard.writeText(text).then(() => {
                btn.textContent = 'Copied!';
                setTimeout(() => btn.textContent = 'Copy', 1500);
            });
        }

        // ── Smooth Scroll ──
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });
    </script>
</body>
</html>
