<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= strtoupper($data['payment']['payment_method']) ?> Payment Gateway - Simulation</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            color: #333;
        }

        /* Header */
        .gateway-header {
            background: linear-gradient(135deg, #1a237e 0%, #283593 100%);
            color: white;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .gateway-header.duitnow {
            background: linear-gradient(135deg, #d32f2f 0%, #e53935 100%);
        }

        .gateway-header .logo {
            font-size: 24px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .gateway-header .subtitle {
            font-size: 12px;
            opacity: 0.85;
        }

        .gateway-header .sim-badge {
            margin-left: auto;
            background: rgba(255,255,255,0.2);
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Warning Banner */
        .sim-warning {
            background: #fff3cd;
            border-bottom: 1px solid #ffc107;
            padding: 8px 20px;
            font-size: 11px;
            color: #856404;
            text-align: center;
            font-weight: 500;
        }

        /* Payment Info Card */
        .payment-info {
            background: white;
            margin: 16px;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }

        .merchant-name {
            font-size: 14px;
            color: #666;
            margin-bottom: 4px;
        }

        .payment-amount {
            font-size: 32px;
            font-weight: 700;
            color: #1a237e;
            margin-bottom: 12px;
        }

        .payment-amount.duitnow {
            color: #d32f2f;
        }

        .payment-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }

        .detail-item {
            font-size: 12px;
        }

        .detail-label {
            color: #999;
            display: block;
        }

        .detail-value {
            color: #333;
            font-weight: 600;
        }

        /* Timer */
        .timer-bar {
            background: white;
            margin: 0 16px;
            border-radius: 12px;
            padding: 12px 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .timer-icon { font-size: 18px; }

        .timer-text {
            font-size: 13px;
            color: #666;
        }

        .timer-countdown {
            margin-left: auto;
            font-size: 16px;
            font-weight: 700;
            color: #e53935;
            font-variant-numeric: tabular-nums;
        }

        /* Bank Selection (FPX) */
        .section-title {
            margin: 20px 20px 10px;
            font-size: 14px;
            font-weight: 600;
            color: #555;
        }

        .bank-selected {
            background: white;
            margin: 0 16px;
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .bank-icon {
            width: 40px;
            height: 40px;
            background: #e8eaf6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .bank-name {
            font-weight: 600;
            font-size: 15px;
        }

        .bank-code {
            font-size: 12px;
            color: #999;
        }

        .bank-check {
            margin-left: auto;
            color: #4caf50;
            font-size: 20px;
        }

        /* DuitNow QR */
        .qr-container {
            background: white;
            margin: 16px;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        }

        .qr-code {
            width: 200px;
            height: 200px;
            margin: 16px auto;
            background: white;
            border: 2px solid #eee;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .qr-grid {
            width: 160px;
            height: 160px;
            display: grid;
            grid-template-columns: repeat(15, 1fr);
            grid-template-rows: repeat(15, 1fr);
            gap: 1px;
        }

        .qr-cell {
            background: #000;
            border-radius: 1px;
        }

        .qr-cell.white { background: white; }

        .qr-instruction {
            font-size: 13px;
            color: #666;
            margin-top: 12px;
        }

        /* Action Buttons */
        .actions {
            margin: 24px 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn {
            padding: 16px 24px;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn:active { transform: scale(0.98); }

        .btn-approve {
            background: #4caf50;
            color: white;
        }

        .btn-approve:hover { background: #43a047; }

        .btn-reject {
            background: white;
            color: #e53935;
            border: 2px solid #e53935;
        }

        .btn-reject:hover { background: #ffebee; }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* Processing Overlay */
        .overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.7);
            z-index: 100;
            align-items: center;
            justify-content: center;
        }

        .overlay.active { display: flex; }

        .overlay-content {
            background: white;
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            max-width: 320px;
            width: 90%;
        }

        .spinner {
            width: 48px;
            height: 48px;
            border: 4px solid #e0e0e0;
            border-top: 4px solid #1a237e;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        .overlay-text {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .overlay-sub {
            font-size: 13px;
            color: #999;
        }

        /* Result states */
        .result-container {
            text-align: center;
            padding: 40px 20px;
        }

        .result-icon {
            font-size: 64px;
            margin-bottom: 16px;
        }

        .result-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .result-message {
            font-size: 14px;
            color: #666;
            margin-bottom: 24px;
        }

        .result-success .result-title { color: #4caf50; }
        .result-failed .result-title { color: #e53935; }
        .result-expired .result-title { color: #ff9800; }

        /* Security footer */
        .security-footer {
            text-align: center;
            padding: 16px 20px 32px;
            font-size: 11px;
            color: #bbb;
        }

        .security-footer .lock { font-size: 14px; }

        /* Divider */
        .divider {
            height: 1px;
            background: #eee;
            margin: 12px 0;
        }
    </style>
</head>
<body>

<?php 
    $payment = $data['payment'];
    $method = $payment['payment_method'];
    $isFpx = $method === 'fpx';
    $isDuitNow = $method === 'duitnow';
    $isProcessed = $data['already_processed'];
    $bankName = '';
    if ($isFpx && !empty($payment['bank_code'])) {
        $bankNames = [
            'MBB' => 'Maybank2u', 'CIMB' => 'CIMB Clicks', 'PBB' => 'Public Bank',
            'RHB' => 'RHB Now', 'HLB' => 'Hong Leong Connect', 'AMB' => 'AmOnline',
            'BIMB' => 'Bank Islam', 'BSN' => 'BSN', 'OCBC' => 'OCBC Bank', 'UOB' => 'UOB Bank',
            'HSBC' => 'HSBC Bank', 'SCB' => 'Standard Chartered'
        ];
        $bankName = $bankNames[$payment['bank_code']] ?? $payment['bank_code'];
    }
?>

<!-- Header -->
<div class="gateway-header <?= $isDuitNow ? 'duitnow' : '' ?>">
    <div>
        <div class="logo"><?= $isFpx ? 'FPX' : 'DuitNow' ?></div>
        <div class="subtitle"><?= $isFpx ? 'Financial Process Exchange' : 'QR Payment' ?></div>
    </div>
    <div class="sim-badge">⚡ Simulation</div>
</div>

<!-- Simulation Warning -->
<div class="sim-warning">
    ⚠️ This is a simulated payment gateway for testing purposes only. No real money will be charged.
</div>

<?php if ($isProcessed): ?>
    <!-- Already Processed Result -->
    <div class="result-container result-<?= $payment['status'] ?>">
        <?php if ($payment['status'] === 'success'): ?>
            <div class="result-icon">✅</div>
            <div class="result-title">Payment Successful</div>
            <div class="result-message">Your payment of RM <?= number_format($payment['amount'], 2) ?> has been processed.<br>Ref: <?= htmlspecialchars($payment['payment_ref']) ?></div>
        <?php elseif ($payment['status'] === 'expired'): ?>
            <div class="result-icon">⏰</div>
            <div class="result-title">Payment Expired</div>
            <div class="result-message">This payment session has expired.<br>Please initiate a new payment.</div>
        <?php else: ?>
            <div class="result-icon">❌</div>
            <div class="result-title">Payment <?= ucfirst($payment['status']) ?></div>
            <div class="result-message">This payment has been <?= $payment['status'] ?>.<br>Ref: <?= htmlspecialchars($payment['payment_ref']) ?></div>
        <?php endif; ?>
    </div>

<?php else: ?>
    <!-- Payment Info -->
    <div class="payment-info">
        <div class="merchant-name">Payment to <strong>Ballz F&B</strong></div>
        <div class="payment-amount <?= $isDuitNow ? 'duitnow' : '' ?>">RM <?= number_format($payment['amount'], 2) ?></div>
        <div class="payment-details">
            <div class="detail-item">
                <span class="detail-label">Reference</span>
                <span class="detail-value"><?= htmlspecialchars($payment['payment_ref']) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Method</span>
                <span class="detail-value"><?= strtoupper($method) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Order #</span>
                <span class="detail-value"><?= $payment['order_id'] ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Date</span>
                <span class="detail-value"><?= date('d M Y, h:i A') ?></span>
            </div>
        </div>
    </div>

    <!-- Timer -->
    <div class="timer-bar">
        <span class="timer-icon">⏱️</span>
        <span class="timer-text">Session expires in</span>
        <span class="timer-countdown" id="countdown">--:--</span>
    </div>

    <?php if ($isFpx && !empty($payment['bank_code'])): ?>
        <!-- FPX: Selected Bank -->
        <div class="section-title">Selected Bank</div>
        <div class="bank-selected">
            <div class="bank-icon">🏦</div>
            <div>
                <div class="bank-name"><?= htmlspecialchars($bankName) ?></div>
                <div class="bank-code"><?= htmlspecialchars($payment['bank_code']) ?></div>
            </div>
            <div class="bank-check">✓</div>
        </div>
    <?php elseif ($isDuitNow): ?>
        <!-- DuitNow: QR Code Simulation -->
        <div class="qr-container">
            <div style="font-weight: 600; font-size: 15px; color: #d32f2f;">Scan with your banking app</div>
            <div class="qr-code">
                <div class="qr-grid" id="qrGrid"></div>
            </div>
            <div class="qr-instruction">Open your banking app → Scan QR → Approve payment</div>
            <div style="margin-top: 8px; font-size: 12px; color: #aaa;">Or use the buttons below to simulate</div>
        </div>
    <?php endif; ?>

    <!-- Action Buttons -->
    <div class="actions">
        <button class="btn btn-approve" id="btnApprove" onclick="processPayment('success')">
            ✓ Approve Payment
        </button>
        <button class="btn btn-reject" id="btnReject" onclick="processPayment('failed')">
            ✕ Reject / Cancel
        </button>
    </div>
<?php endif; ?>

<!-- Security Footer -->
<div class="security-footer">
    <div class="lock">🔒</div>
    <div>Secured by <?= $isFpx ? 'FPX (PayNet)' : 'DuitNow (PayNet)' ?> — Simulation Mode</div>
    <div style="margin-top: 4px;">Transaction ID: <?= htmlspecialchars($payment['payment_ref']) ?></div>
</div>

<!-- Processing Overlay -->
<div class="overlay" id="processingOverlay">
    <div class="overlay-content">
        <div class="spinner"></div>
        <div class="overlay-text" id="overlayText">Processing Payment...</div>
        <div class="overlay-sub" id="overlaySub">Please do not close this window</div>
    </div>
</div>

<!-- Result Overlay -->
<div class="overlay" id="resultOverlay">
    <div class="overlay-content">
        <div class="result-icon" id="resultIcon"></div>
        <div class="overlay-text" id="resultText"></div>
        <div class="overlay-sub" id="resultSub"></div>
        <div style="margin-top: 20px;">
            <button class="btn btn-approve" style="width: 100%;" onclick="closeGateway()">Done</button>
        </div>
    </div>
</div>

<?php if (!$isProcessed): ?>
<script>
    const PAYMENT_REF = '<?= htmlspecialchars($payment['payment_ref'], ENT_QUOTES) ?>';
    const CALLBACK_URL = '<?= $data['callback_url'] ?>';
    const EXPIRES_AT = new Date('<?= $payment['expires_at'] ?>').getTime();

    // Countdown timer
    function updateCountdown() {
        const now = Date.now();
        const remaining = Math.max(0, EXPIRES_AT - now);
        
        if (remaining <= 0) {
            document.getElementById('countdown').textContent = 'EXPIRED';
            document.getElementById('countdown').style.color = '#999';
            document.getElementById('btnApprove').disabled = true;
            document.getElementById('btnReject').disabled = true;
            return;
        }

        const min = Math.floor(remaining / 60000);
        const sec = Math.floor((remaining % 60000) / 1000);
        document.getElementById('countdown').textContent = 
            String(min).padStart(2, '0') + ':' + String(sec).padStart(2, '0');

        if (remaining < 60000) {
            document.getElementById('countdown').style.color = '#e53935';
        }
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);

    // Generate fake QR code pattern
    <?php if ($isDuitNow): ?>
    (function() {
        const grid = document.getElementById('qrGrid');
        for (let i = 0; i < 225; i++) {
            const cell = document.createElement('div');
            const row = Math.floor(i / 15);
            const col = i % 15;
            
            // QR corner patterns
            const isCorner = (row < 3 && col < 3) || (row < 3 && col > 11) || (row > 11 && col < 3);
            const isRandom = Math.random() > 0.5;
            
            cell.className = 'qr-cell' + ((isCorner || isRandom) ? '' : ' white');
            grid.appendChild(cell);
        }
    })();
    <?php endif; ?>

    // Process payment
    async function processPayment(status) {
        const btnApprove = document.getElementById('btnApprove');
        const btnReject = document.getElementById('btnReject');
        const overlay = document.getElementById('processingOverlay');

        // Disable buttons
        btnApprove.disabled = true;
        btnReject.disabled = true;

        // Show processing overlay
        overlay.classList.add('active');

        // Simulate bank processing delay (1.5-3 seconds)
        const delay = 1500 + Math.random() * 1500;
        await new Promise(r => setTimeout(r, delay));

        try {
            const response = await fetch(CALLBACK_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    payment_ref: PAYMENT_REF,
                    status: status,
                    gateway_response: status === 'success' 
                        ? 'Transaction approved by bank' 
                        : 'Transaction declined by user'
                })
            });

            const result = await response.json();

            // Hide processing overlay
            overlay.classList.remove('active');

            // Show result
            showResult(status, result);

        } catch (err) {
            overlay.classList.remove('active');
            showResult('error', { message: 'Network error: ' + err.message });
        }
    }

    function showResult(status, result) {
        const overlay = document.getElementById('resultOverlay');
        const icon = document.getElementById('resultIcon');
        const text = document.getElementById('resultText');
        const sub = document.getElementById('resultSub');

        if (status === 'success') {
            icon.textContent = '✅';
            text.textContent = 'Payment Successful';
            text.style.color = '#4caf50';
            sub.textContent = 'Your payment has been processed. You may close this window.';
        } else if (status === 'failed') {
            icon.textContent = '❌';
            text.textContent = 'Payment Declined';
            text.style.color = '#e53935';
            sub.textContent = 'The transaction was cancelled. You may close this window.';
        } else {
            icon.textContent = '⚠️';
            text.textContent = 'Error';
            text.style.color = '#ff9800';
            sub.textContent = result.message || 'Something went wrong. Please try again.';
        }

        overlay.classList.add('active');
    }

    function closeGateway() {
        // Try to post message to parent (for WebView integration)
        try {
            if (window.ReactNativeWebView) {
                window.ReactNativeWebView.postMessage(JSON.stringify({
                    type: 'payment_complete',
                    payment_ref: PAYMENT_REF
                }));
            } else if (window.parent !== window) {
                window.parent.postMessage({
                    type: 'payment_complete',
                    payment_ref: PAYMENT_REF
                }, '*');
            }
        } catch(e) {}

        // Try to close the window/tab
        window.close();
        
        // If window.close() doesn't work (common in mobile browsers),
        // redirect to a simple done page
        setTimeout(() => {
            document.body.innerHTML = '<div style="display:flex;align-items:center;justify-content:center;height:100vh;font-family:sans-serif;color:#666;text-align:center;padding:20px;"><div><div style="font-size:48px;margin-bottom:16px;">✓</div><div style="font-size:18px;font-weight:600;">You may close this window</div><div style="margin-top:8px;font-size:14px;">Return to the Ballz app to continue</div></div></div>';
        }, 500);
    }
</script>
<?php endif; ?>

</body>
</html>
