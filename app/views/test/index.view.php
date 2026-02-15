<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ballz | API Tester</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0f0f0f;
            --surface: #1a1a1a;
            --surface2: #252525;
            --border: #333;
            --text: #e5e5e5;
            --text-muted: #888;
            --accent: #f59e0b;
            --accent-hover: #d97706;
            --green: #22c55e;
            --red: #ef4444;
            --blue: #3b82f6;
            --purple: #a855f7;
            --orange: #f97316;
            --cyan: #06b6d4;
            --radius: 8px;
            --mono: 'JetBrains Mono', monospace;
            --sans: 'Inter', sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: var(--sans);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
        }

        /* ── Layout ── */
        .app {
            display: grid;
            grid-template-columns: 280px 1fr;
            min-height: 100vh;
        }

        /* ── Sidebar ── */
        .sidebar {
            background: var(--surface);
            border-right: 1px solid var(--border);
            padding: 20px 0;
            overflow-y: auto;
            position: sticky;
            top: 0;
            height: 100vh;
        }

        .sidebar-header {
            padding: 0 20px 20px;
            border-bottom: 1px solid var(--border);
            margin-bottom: 12px;
        }

        .sidebar-header h1 {
            font-size: 18px;
            font-weight: 700;
            color: var(--accent);
        }

        .sidebar-header p {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .endpoint-group {
            margin-bottom: 8px;
        }

        .group-title {
            padding: 8px 20px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--text-muted);
        }

        .endpoint-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 9px 20px;
            background: none;
            border: none;
            color: var(--text);
            font-family: var(--sans);
            font-size: 13px;
            cursor: pointer;
            text-align: left;
            transition: background 0.15s;
        }

        .endpoint-btn:hover {
            background: var(--surface2);
        }

        .endpoint-btn.active {
            background: var(--surface2);
            border-left: 3px solid var(--accent);
        }

        .method-badge {
            font-family: var(--mono);
            font-size: 10px;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 3px;
            min-width: 42px;
            text-align: center;
        }

        .method-GET { background: rgba(34,197,94,0.15); color: var(--green); }
        .method-POST { background: rgba(59,130,246,0.15); color: var(--blue); }
        .method-PUT { background: rgba(168,85,247,0.15); color: var(--purple); }
        .method-PATCH { background: rgba(249,115,22,0.15); color: var(--orange); }
        .method-DELETE { background: rgba(239,68,68,0.15); color: var(--red); }

        /* ── Main ── */
        .main {
            padding: 24px 32px;
            overflow-y: auto;
        }

        /* ── Config Bar ── */
        .config-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            align-items: center;
            flex-wrap: wrap;
        }

        .config-bar label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-muted);
            margin-right: 4px;
        }

        .config-bar input, .config-bar select {
            font-family: var(--mono);
            font-size: 12px;
            padding: 8px 12px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text);
            outline: none;
        }

        .config-bar input:focus, .config-bar select:focus {
            border-color: var(--accent);
        }

        #baseUrl { width: 320px; }
        #apiKey { width: 520px; }

        /* ── Request Panel ── */
        .request-panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            margin-bottom: 20px;
        }

        .request-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
        }

        .request-header h2 {
            font-size: 16px;
            font-weight: 600;
            flex: 1;
        }

        .request-url {
            display: flex;
            align-items: center;
            gap: 0;
            padding: 12px 20px;
            border-bottom: 1px solid var(--border);
        }

        .request-url select {
            font-family: var(--mono);
            font-size: 13px;
            font-weight: 700;
            padding: 10px 12px;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: var(--radius) 0 0 var(--radius);
            color: var(--green);
            outline: none;
            cursor: pointer;
        }

        .request-url input {
            flex: 1;
            font-family: var(--mono);
            font-size: 13px;
            padding: 10px 12px;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-left: none;
            color: var(--text);
            outline: none;
        }

        .request-url input:focus {
            border-color: var(--accent);
        }

        .btn-send {
            font-family: var(--sans);
            font-size: 13px;
            font-weight: 600;
            padding: 10px 24px;
            background: var(--accent);
            color: #000;
            border: none;
            border-radius: 0 var(--radius) var(--radius) 0;
            cursor: pointer;
            transition: background 0.15s;
            white-space: nowrap;
        }

        .btn-send:hover { background: var(--accent-hover); }
        .btn-send:disabled { opacity: 0.5; cursor: not-allowed; }

        /* ── Tabs ── */
        .tabs {
            display: flex;
            border-bottom: 1px solid var(--border);
        }

        .tab {
            padding: 10px 20px;
            font-size: 13px;
            font-weight: 500;
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            border-bottom: 2px solid transparent;
            transition: all 0.15s;
        }

        .tab:hover { color: var(--text); }
        .tab.active { color: var(--accent); border-bottom-color: var(--accent); }

        .tab-content {
            display: none;
            padding: 16px 20px;
        }

        .tab-content.active { display: block; }

        /* ── Body Editor ── */
        .body-editor {
            width: 100%;
            min-height: 200px;
            font-family: var(--mono);
            font-size: 13px;
            line-height: 1.6;
            padding: 12px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text);
            resize: vertical;
            outline: none;
            tab-size: 2;
        }

        .body-editor:focus {
            border-color: var(--accent);
        }

        /* ── Params Table ── */
        .params-table {
            width: 100%;
            border-collapse: collapse;
        }

        .params-table th {
            text-align: left;
            padding: 8px 12px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }

        .params-table td {
            padding: 6px 8px;
        }

        .params-table input {
            width: 100%;
            font-family: var(--mono);
            font-size: 12px;
            padding: 6px 10px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 4px;
            color: var(--text);
            outline: none;
        }

        .params-table input:focus {
            border-color: var(--accent);
        }

        .btn-add-param, .btn-remove-param {
            font-size: 12px;
            padding: 4px 10px;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 4px;
            color: var(--text-muted);
            cursor: pointer;
        }

        .btn-add-param:hover { border-color: var(--accent); color: var(--accent); }
        .btn-remove-param:hover { border-color: var(--red); color: var(--red); }

        /* ── Response Panel ── */
        .response-panel {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
        }

        .response-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 20px;
            border-bottom: 1px solid var(--border);
        }

        .response-header h3 {
            font-size: 14px;
            font-weight: 600;
        }

        .status-badge {
            font-family: var(--mono);
            font-size: 12px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 4px;
        }

        .status-2xx { background: rgba(34,197,94,0.15); color: var(--green); }
        .status-4xx { background: rgba(239,68,68,0.15); color: var(--red); }
        .status-5xx { background: rgba(239,68,68,0.15); color: var(--red); }
        .status-0xx { background: rgba(136,136,136,0.15); color: var(--text-muted); }

        .response-meta {
            margin-left: auto;
            display: flex;
            gap: 16px;
            font-size: 12px;
            color: var(--text-muted);
            font-family: var(--mono);
        }

        .response-body {
            padding: 16px 20px;
            max-height: 500px;
            overflow: auto;
        }

        .response-body pre {
            font-family: var(--mono);
            font-size: 12px;
            line-height: 1.6;
            white-space: pre-wrap;
            word-break: break-word;
            color: var(--text);
        }

        .response-empty {
            padding: 40px 20px;
            text-align: center;
            color: var(--text-muted);
            font-size: 13px;
        }

        /* ── JSON Syntax Highlighting ── */
        .json-key { color: #7dd3fc; }
        .json-string { color: #86efac; }
        .json-number { color: #fbbf24; }
        .json-boolean { color: #c084fc; }
        .json-null { color: #888; }

        /* ── Quick Fill Buttons ── */
        .quick-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        .btn-quick {
            font-family: var(--sans);
            font-size: 11px;
            font-weight: 500;
            padding: 5px 12px;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 20px;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-quick:hover {
            border-color: var(--accent);
            color: var(--accent);
        }

        /* ── Description ── */
        .endpoint-desc {
            padding: 12px 20px;
            font-size: 13px;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            line-height: 1.5;
        }

        .endpoint-desc code {
            font-family: var(--mono);
            font-size: 12px;
            padding: 1px 6px;
            background: var(--bg);
            border-radius: 3px;
            color: var(--accent);
        }

        /* ── History ── */
        .history-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            border-bottom: 1px solid var(--border);
            cursor: pointer;
            font-size: 12px;
        }

        .history-item:hover { background: var(--surface2); }

        .history-item .method-badge { font-size: 9px; }
        .history-item .url { color: var(--text-muted); font-family: var(--mono); flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .history-item .time { color: var(--text-muted); font-size: 11px; }

        /* ── Copy button ── */
        .btn-copy {
            font-size: 11px;
            padding: 4px 10px;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 4px;
            color: var(--text-muted);
            cursor: pointer;
        }
        .btn-copy:hover { border-color: var(--accent); color: var(--accent); }

        /* ── Loading spinner ── */
        .spinner {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 2px solid #333;
            border-top-color: var(--accent);
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Scrollbar ── */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
    </style>
</head>
<body>

<div class="app">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <h1>BALLZ API Tester</h1>
            <p>v1 REST API</p>
        </div>

        <!-- GET Endpoints -->
        <div class="endpoint-group">
            <div class="group-title">Menu</div>
            <button class="endpoint-btn" data-method="GET" data-path="/api/v1/menu" data-desc="Get all menu items (public, no API key needed)">
                <span class="method-badge method-GET">GET</span> Menu List
            </button>
            <button class="endpoint-btn" data-method="GET" data-path="/api/v1/menu/{id}" data-desc="Get a specific menu item by ID (public)" data-placeholder-id="item_id">
                <span class="method-badge method-GET">GET</span> Menu Item
            </button>
        </div>

        <div class="endpoint-group">
            <div class="group-title">Outlets</div>
            <button class="endpoint-btn" data-method="GET" data-path="/api/v1/outlets" data-desc="Get all outlets (public, no API key needed)">
                <span class="method-badge method-GET">GET</span> Outlets List
            </button>
            <button class="endpoint-btn" data-method="GET" data-path="/api/v1/outlets/{id}" data-desc="Get a specific outlet by ID (public)" data-placeholder-id="outlet_id">
                <span class="method-badge method-GET">GET</span> Outlet
            </button>
        </div>

        <div class="endpoint-group">
            <div class="group-title">Vouchers</div>
            <button class="endpoint-btn" data-method="GET" data-path="/api/v1/vouchers" data-desc="Get all vouchers (public, no API key needed)">
                <span class="method-badge method-GET">GET</span> Vouchers List
            </button>
            <button class="endpoint-btn" data-method="GET" data-path="/api/v1/vouchers/{id}" data-desc="Get a specific voucher by ID with rules (public)" data-placeholder-id="voucher_id">
                <span class="method-badge method-GET">GET</span> Voucher
            </button>
        </div>

        <div class="endpoint-group">
            <div class="group-title">Rewards</div>
            <button class="endpoint-btn" data-method="GET" data-path="/api/v1/rewards" data-desc="Get all reward items (public, no API key needed)">
                <span class="method-badge method-GET">GET</span> Rewards List
            </button>
            <button class="endpoint-btn" data-method="GET" data-path="/api/v1/rewards/{id}" data-desc="Get a specific reward item by ID (public)" data-placeholder-id="reward_id">
                <span class="method-badge method-GET">GET</span> Reward
            </button>
            <button class="endpoint-btn" data-method="GET" data-path="/api/v1/reward-transactions" data-desc="Get all reward transactions (requires API key)">
                <span class="method-badge method-GET">GET</span> All Transactions
            </button>
            <button class="endpoint-btn" data-method="GET" data-path="/api/v1/reward-transactions/{id}" data-desc="Get a specific reward transaction (requires API key)" data-placeholder-id="transaction_id">
                <span class="method-badge method-GET">GET</span> Transaction
            </button>
        </div>

        <div class="endpoint-group">
            <div class="group-title">Users</div>
            <button class="endpoint-btn" data-method="GET" data-path="/api/v1/users" data-desc="Get all users (requires API key). Returns users without passwords.">
                <span class="method-badge method-GET">GET</span> Users List
            </button>
            <button class="endpoint-btn" data-method="GET" data-path="/api/v1/users/{id}" data-desc="Get a specific user. Query: <code>include_orders=true</code>, <code>order_details=true</code> (requires API key)" data-placeholder-id="user_id">
                <span class="method-badge method-GET">GET</span> User
            </button>
            <button class="endpoint-btn" data-method="GET" data-path="/api/v1/users/{id}/orders" data-desc="Get all orders for a user. Query: <code>details=true</code> for full order details (requires API key)" data-placeholder-id="user_id">
                <span class="method-badge method-GET">GET</span> User Orders
            </button>
            <button class="endpoint-btn" data-method="GET" data-path="/api/v1/users/{id}/reward-transactions" data-desc="Get reward transactions for a user (requires API key)" data-placeholder-id="user_id">
                <span class="method-badge method-GET">GET</span> User Rewards
            </button>
        </div>

        <!-- POST Endpoints -->
        <div class="endpoint-group">
            <div class="group-title">Actions</div>
            <button class="endpoint-btn" data-method="POST" data-path="/api/v1/auth" data-desc="Check existing user or register new user via social provider (requires API key)" data-body='{
  "provider": "google",
  "provider_user_id": "123456789",
  "name": "John Doe",
  "email": "john@example.com",
  "phone": "+60123456789"
}'>
                <span class="method-badge method-POST">POST</span> Auth Check
            </button>
            <button class="endpoint-btn" data-method="POST" data-path="/api/v1/orders" data-desc="Create a new order with items and optional voucher codes (requires API key)" data-body='{
  "user_id": 1,
  "outlet_id": 1,
  "order_type": "pickup",
  "items": [
    {
      "menu_item_id": 1,
      "quantity": 2
    }
  ],
  "voucher_codes": []
}'>
                <span class="method-badge method-POST">POST</span> Create Order
            </button>
            <button class="endpoint-btn" data-method="POST" data-path="/api/v1/reward-transactions" data-desc="Create a reward transaction — earn or redeem points (requires API key)" data-body='{
  "user_id": 1,
  "type": "redeem",
  "reward_item_id": 1,
  "order_id": null
}'>
                <span class="method-badge method-POST">POST</span> Reward Transaction
            </button>
        </div>
    </aside>

    <!-- Main Panel -->
    <main class="main">
        <!-- Config Bar -->
        <div class="config-bar">
            <div>
                <label>Base URL</label>
                <input type="text" id="baseUrl" value="<?= ROOT ?>" placeholder="http://localhost/Ballz">
            </div>
            <div>
                <label>API Key</label>
                <input type="text" id="apiKey" value="" placeholder="Paste API key or leave empty for public endpoints">
            </div>
        </div>

        <!-- Request Panel -->
        <div class="request-panel">
            <div class="request-header">
                <h2 id="reqTitle">Select an endpoint</h2>
            </div>

            <div id="reqDesc" class="endpoint-desc" style="display:none;"></div>

            <div class="request-url">
                <select id="reqMethod">
                    <option value="GET">GET</option>
                    <option value="POST">POST</option>
                    <option value="PUT">PUT</option>
                    <option value="PATCH">PATCH</option>
                    <option value="DELETE">DELETE</option>
                </select>
                <input type="text" id="reqUrl" placeholder="/api/v1/menu">
                <button class="btn-send" id="btnSend" onclick="sendRequest()">Send</button>
            </div>

            <!-- Tabs -->
            <div class="tabs">
                <button class="tab active" data-tab="tab-params">Query Params</button>
                <button class="tab" data-tab="tab-body">Body</button>
                <button class="tab" data-tab="tab-headers">Headers</button>
            </div>

            <!-- Query Params -->
            <div class="tab-content active" id="tab-params">
                <table class="params-table">
                    <thead>
                        <tr><th>Key</th><th>Value</th><th></th></tr>
                    </thead>
                    <tbody id="paramsBody">
                        <tr>
                            <td><input type="text" class="param-key" placeholder="key"></td>
                            <td><input type="text" class="param-value" placeholder="value"></td>
                            <td><button class="btn-remove-param" onclick="removeParam(this)">&times;</button></td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn-add-param" onclick="addParam()" style="margin-top:8px;">+ Add Param</button>
            </div>

            <!-- Body -->
            <div class="tab-content" id="tab-body">
                <div class="quick-actions" id="quickActions" style="display: none;">
                    <span style="font-size:11px;color:var(--text-muted);margin-right:4px;">Templates:</span>
                </div>
                <textarea class="body-editor" id="reqBody" placeholder='{ "key": "value" }'></textarea>
            </div>

            <!-- Headers -->
            <div class="tab-content" id="tab-headers">
                <table class="params-table">
                    <thead>
                        <tr><th>Key</th><th>Value</th><th></th></tr>
                    </thead>
                    <tbody id="headersBody">
                        <tr>
                            <td><input type="text" class="header-key" value="Content-Type"></td>
                            <td><input type="text" class="header-value" value="application/json"></td>
                            <td><button class="btn-remove-param" onclick="removeParam(this)">&times;</button></td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn-add-param" onclick="addHeader()" style="margin-top:8px;">+ Add Header</button>
            </div>
        </div>

        <!-- Response Panel -->
        <div class="response-panel">
            <div class="response-header">
                <h3>Response</h3>
                <span class="status-badge status-0xx" id="resStatus" style="display:none;"></span>
                <div class="response-meta">
                    <span id="resTime"></span>
                    <span id="resSize"></span>
                </div>
                <button class="btn-copy" id="btnCopy" style="display:none;" onclick="copyResponse()">Copy</button>
            </div>
            <div id="resEmpty" class="response-empty">
                Hit <strong>Send</strong> to see the response here
            </div>
            <div class="response-body" id="resBody" style="display:none;">
                <pre id="resContent"></pre>
            </div>
        </div>

        <!-- History -->
        <div class="response-panel" style="margin-top: 20px;">
            <div class="response-header">
                <h3>History</h3>
                <button class="btn-copy" onclick="clearHistory()" style="margin-left:auto;">Clear</button>
            </div>
            <div id="historyList">
                <div class="response-empty" id="historyEmpty">No requests yet</div>
            </div>
        </div>
    </main>
</div>

<script>
// ── State ──
let rawResponse = '';
let history = [];

// ── Tab Switching ──
document.querySelectorAll('.tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
        tab.classList.add('active');
        document.getElementById(tab.dataset.tab).classList.add('active');
    });
});

// ── Sidebar Endpoint Selection ──
document.querySelectorAll('.endpoint-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        // Highlight active
        document.querySelectorAll('.endpoint-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const method = btn.dataset.method;
        const path = btn.dataset.path;
        const desc = btn.dataset.desc || '';
        const body = btn.dataset.body || '';
        const placeholderId = btn.dataset.placeholderId || '';

        // Set method
        document.getElementById('reqMethod').value = method;
        updateMethodColor();

        // Set URL (replace {id} placeholder with prompt if needed)
        let resolvedPath = path;
        if (path.includes('{id}')) {
            const matches = path.match(/\{id\}/g);
            if (matches) {
                const id = prompt(`Enter ${placeholderId || 'resource ID'}:`, '1');
                if (id !== null) {
                    resolvedPath = path.replace('{id}', id);
                }
            }
        }
        document.getElementById('reqUrl').value = resolvedPath;

        // Set title
        document.getElementById('reqTitle').textContent = btn.textContent.trim();

        // Set description
        const descEl = document.getElementById('reqDesc');
        if (desc) {
            descEl.innerHTML = desc;
            descEl.style.display = 'block';
        } else {
            descEl.style.display = 'none';
        }

        // Set body and switch tab if POST
        if (method === 'POST' || method === 'PUT' || method === 'PATCH') {
            document.getElementById('reqBody').value = body;
            // Switch to body tab
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.querySelector('[data-tab="tab-body"]').classList.add('active');
            document.getElementById('tab-body').classList.add('active');
        } else {
            document.getElementById('reqBody').value = '';
            // Switch to params tab
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.querySelector('[data-tab="tab-params"]').classList.add('active');
            document.getElementById('tab-params').classList.add('active');
        }
    });
});

// ── Method color ──
function updateMethodColor() {
    const sel = document.getElementById('reqMethod');
    const colors = { GET: '#22c55e', POST: '#3b82f6', PUT: '#a855f7', PATCH: '#f97316', DELETE: '#ef4444' };
    sel.style.color = colors[sel.value] || '#e5e5e5';
}
document.getElementById('reqMethod').addEventListener('change', updateMethodColor);
updateMethodColor();

// ── Send Request ──
async function sendRequest() {
    const baseUrl = document.getElementById('baseUrl').value.replace(/\/+$/, '');
    const apiKey = document.getElementById('apiKey').value.trim();
    const method = document.getElementById('reqMethod').value;
    const path = document.getElementById('reqUrl').value.trim();

    if (!path) {
        alert('Please enter a request URL');
        return;
    }

    // Build query params
    const params = new URLSearchParams();
    document.querySelectorAll('#paramsBody tr').forEach(row => {
        const key = row.querySelector('.param-key')?.value?.trim();
        const val = row.querySelector('.param-value')?.value?.trim();
        if (key) params.append(key, val || '');
    });

    let url = baseUrl + path;
    const qs = params.toString();
    if (qs) url += (url.includes('?') ? '&' : '?') + qs;

    // Build headers
    const headers = {};
    document.querySelectorAll('#headersBody tr').forEach(row => {
        const key = row.querySelector('.header-key')?.value?.trim();
        const val = row.querySelector('.header-value')?.value?.trim();
        if (key) headers[key] = val || '';
    });

    // Add API key header
    if (apiKey) {
        headers['Authorization'] = 'Bearer ' + apiKey;
    }

    // Build fetch options
    const opts = { method, headers };

    if (['POST', 'PUT', 'PATCH'].includes(method)) {
        const body = document.getElementById('reqBody').value.trim();
        if (body) {
            opts.body = body;
            if (!headers['Content-Type']) {
                headers['Content-Type'] = 'application/json';
            }
        }
    }

    // UI: loading state
    const btnSend = document.getElementById('btnSend');
    btnSend.disabled = true;
    btnSend.innerHTML = '<span class="spinner"></span>';

    const startTime = performance.now();

    try {
        const res = await fetch(url, opts);
        const elapsed = Math.round(performance.now() - startTime);
        const text = await res.text();
        const size = new Blob([text]).size;

        rawResponse = text;

        // Status badge
        const statusEl = document.getElementById('resStatus');
        statusEl.textContent = res.status + ' ' + res.statusText;
        statusEl.className = 'status-badge';
        if (res.status >= 200 && res.status < 300) statusEl.classList.add('status-2xx');
        else if (res.status >= 400 && res.status < 500) statusEl.classList.add('status-4xx');
        else if (res.status >= 500) statusEl.classList.add('status-5xx');
        else statusEl.classList.add('status-0xx');
        statusEl.style.display = 'inline-block';

        // Meta
        document.getElementById('resTime').textContent = elapsed + 'ms';
        document.getElementById('resSize').textContent = formatBytes(size);

        // Body
        document.getElementById('resEmpty').style.display = 'none';
        document.getElementById('resBody').style.display = 'block';
        document.getElementById('btnCopy').style.display = 'inline-block';

        // Try to pretty-print JSON
        try {
            const json = JSON.parse(text);
            document.getElementById('resContent').innerHTML = syntaxHighlight(JSON.stringify(json, null, 2));
        } catch {
            document.getElementById('resContent').textContent = text;
        }

        // Add to history
        addToHistory(method, path, res.status, elapsed);

    } catch (err) {
        document.getElementById('resEmpty').style.display = 'none';
        document.getElementById('resBody').style.display = 'block';
        document.getElementById('resContent').textContent = 'Request failed: ' + err.message;

        const statusEl = document.getElementById('resStatus');
        statusEl.textContent = 'Error';
        statusEl.className = 'status-badge status-5xx';
        statusEl.style.display = 'inline-block';

        document.getElementById('resTime').textContent = '';
        document.getElementById('resSize').textContent = '';
    }

    btnSend.disabled = false;
    btnSend.textContent = 'Send';
}

// ── Keyboard shortcut ──
document.addEventListener('keydown', (e) => {
    if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
        e.preventDefault();
        sendRequest();
    }
});

// ── JSON Syntax Highlighting ──
function syntaxHighlight(json) {
    json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    return json.replace(
        /("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g,
        function (match) {
            let cls = 'json-number';
            if (/^"/.test(match)) {
                if (/:$/.test(match)) {
                    cls = 'json-key';
                } else {
                    cls = 'json-string';
                }
            } else if (/true|false/.test(match)) {
                cls = 'json-boolean';
            } else if (/null/.test(match)) {
                cls = 'json-null';
            }
            return '<span class="' + cls + '">' + match + '</span>';
        }
    );
}

// ── Helpers ──
function formatBytes(bytes) {
    if (bytes < 1024) return bytes + ' B';
    if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
    return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
}

function copyResponse() {
    navigator.clipboard.writeText(rawResponse).then(() => {
        const btn = document.getElementById('btnCopy');
        btn.textContent = 'Copied!';
        setTimeout(() => btn.textContent = 'Copy', 1500);
    });
}

// ── Params / Headers ──
function addParam() {
    const tbody = document.getElementById('paramsBody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text" class="param-key" placeholder="key"></td>
        <td><input type="text" class="param-value" placeholder="value"></td>
        <td><button class="btn-remove-param" onclick="removeParam(this)">&times;</button></td>
    `;
    tbody.appendChild(tr);
}

function addHeader() {
    const tbody = document.getElementById('headersBody');
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><input type="text" class="header-key" placeholder="key"></td>
        <td><input type="text" class="header-value" placeholder="value"></td>
        <td><button class="btn-remove-param" onclick="removeParam(this)">&times;</button></td>
    `;
    tbody.appendChild(tr);
}

function removeParam(btn) {
    btn.closest('tr').remove();
}

// ── History ──
function addToHistory(method, path, status, time) {
    history.unshift({ method, path, status, time, ts: new Date() });
    if (history.length > 50) history.pop();
    renderHistory();
}

function renderHistory() {
    const list = document.getElementById('historyList');
    const empty = document.getElementById('historyEmpty');

    if (history.length === 0) {
        list.innerHTML = '';
        list.appendChild(empty);
        empty.style.display = 'block';
        return;
    }

    list.innerHTML = history.map((h, i) => `
        <div class="history-item" onclick="replayHistory(${i})">
            <span class="method-badge method-${h.method}">${h.method}</span>
            <span class="url">${h.path}</span>
            <span class="status-badge ${h.status >= 200 && h.status < 300 ? 'status-2xx' : h.status >= 400 ? 'status-4xx' : 'status-0xx'}">${h.status}</span>
            <span class="time">${h.time}ms</span>
        </div>
    `).join('');
}

function replayHistory(index) {
    const h = history[index];
    document.getElementById('reqMethod').value = h.method;
    document.getElementById('reqUrl').value = h.path;
    updateMethodColor();
}

function clearHistory() {
    history = [];
    renderHistory();
}

// ── Auto-fill API key from config (convenience) ──
document.getElementById('apiKey').value = '<?php if (defined("API_KEYS") && is_array(API_KEYS)) { $keys = API_KEYS; echo reset($keys); } ?>';
</script>

</body>
</html>
