<?php

class Payment extends Controller
{
    protected $viewFolder = '';

    /**
     * GET /payment/gateway?ref=BZ-FPX-...
     * Renders the simulated FPX/DuitNow payment gateway page
     * This is opened in a mobile WebView
     */
    public function gateway()
    {
        $paymentRef = $_GET['ref'] ?? null;

        if (!$paymentRef) {
            http_response_code(400);
            echo "Missing payment reference.";
            exit;
        }

        $paymentModel = new PaymentModel();
        $payment = $paymentModel->getByRef($paymentRef);

        if (!$payment) {
            http_response_code(404);
            echo "Payment not found.";
            exit;
        }

        // Check if already processed
        if (!in_array($payment['status'], ['pending', 'processing'])) {
            $data = [
                'payment' => $payment,
                'already_processed' => true,
                'root' => ROOT
            ];
            $this->viewFolder = '';
            require_once "../app/views/payment/gateway.view.php";
            exit;
        }

        // Check if expired
        if (strtotime($payment['expires_at']) < time()) {
            $paymentModel->updateStatus($payment['id'], 'expired');
            $orderModel = new OrderModel();
            $orderModel->updateStatus($payment['order_id'], 'cancelled');
            $payment['status'] = 'expired';
            $data = [
                'payment' => $payment,
                'already_processed' => true,
                'root' => ROOT
            ];
            require_once "../app/views/payment/gateway.view.php";
            exit;
        }

        // FPX bank list
        $fpxBanks = [
            ['code' => 'MBB',  'name' => 'Maybank2u',          'logo' => '🏦'],
            ['code' => 'CIMB', 'name' => 'CIMB Clicks',         'logo' => '🏦'],
            ['code' => 'PBB',  'name' => 'Public Bank',         'logo' => '🏦'],
            ['code' => 'RHB',  'name' => 'RHB Now',             'logo' => '🏦'],
            ['code' => 'HLB',  'name' => 'Hong Leong Connect',  'logo' => '🏦'],
            ['code' => 'AMB',  'name' => 'AmOnline',            'logo' => '🏦'],
            ['code' => 'BIMB', 'name' => 'Bank Islam',          'logo' => '🏦'],
            ['code' => 'BSN',  'name' => 'BSN',                 'logo' => '🏦'],
        ];

        $data = [
            'payment' => $payment,
            'banks' => $fpxBanks,
            'already_processed' => false,
            'root' => ROOT,
            'callback_url' => ROOT . '/api/v1/payment-callback'
        ];

        require_once "../app/views/payment/gateway.view.php";
        exit;
    }

    public function index()
    {
        header("Location: " . ROOT);
        exit;
    }
}
