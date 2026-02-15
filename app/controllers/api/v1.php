<?php


class v1 extends Api
{
    private $categoryModel;
    private $itemModel;
    private $outletModel;
    private $voucherModel;
    private $userModel;
    private $rewardModel;
    private $orderModel;

    public function __construct()
    {
        $this->initializeModels();
    }

    /**
     * Public entry point for API requests
     * Routes requests to appropriate handlers
     * 
     * @param string $endpoint The API endpoint to call
     * @param mixed $resourceId Optional resource ID
     */
    public function index($endpoint = null, $resourceId = null)
    {
        // Set endpoint BEFORE calling parent constructor (which validates API key)
        if ($endpoint) {
            $this->endpoint = $endpoint;
        }
        if ($resourceId) {
            $this->resourceId = $resourceId;
        }
        
        // Now call parent constructor - it will validate API key with correct endpoint
        parent::__construct();
        
        $this->route($this->endpoint, $this->method, $this->resourceId);
    }

    /**
     * Initialize all models needed for menu operations
     */
    private function initializeModels()
    {
        $this->categoryModel = new CategoryModel();
        $this->itemModel = new MenuItemModel();
        $this->outletModel = new OutletModel();
        $this->voucherModel = new VoucherModel();
        $this->userModel = new UserModel();
        $this->rewardModel = new RewardModel();
        $this->orderModel = new OrderModel();
    }

    /**
     * GET /api/v1/menu - Get complete menu data
     * Returns all categories and items
     */
    protected function handleGetList($endpoint)
    {
        switch ($endpoint) {
            case 'menu':
                $this->getMenuData();
                break;
            case 'outlets':
                $this->getOutletsData();
                break;
            case 'vouchers':
                $this->getVouchersData();
                break;
            case 'rewards':
                $this->getRewardsData();
                break;
            case 'reward-transactions':
                $this->getRewardTransactionsData();
                break;
            case 'users':
                $this->getUsersData();
                break;
            default:
                $this->sendError('Endpoint not found', 404);
        }
    }

    /**
     * GET /api/v1/menu/{id} - Get specific menu item
     * Returns a single menu item
     * Also handles nested resources like /users/{id}/orders
     */
    protected function handleGet($endpoint, $resourceId)
    {
        // Check for nested resource pattern
        $subResource = $this->getSubResource();
        $subResourceId = $this->getSubResourceId();
        
        // Handle nested resources
        if ($subResource) {
            $this->handleNestedGet($endpoint, $resourceId, $subResource, $subResourceId);
            return;
        }
        
        switch ($endpoint) {
            case 'menu':
                $this->getMenuItem($resourceId);
                break;
            case 'outlets':
                $this->getOutlet($resourceId);
                break;
            case 'vouchers':
                $this->getVoucher($resourceId);
                break;
            case 'rewards':
                $this->getReward($resourceId);
                break;
            case 'reward-transactions':
                $this->getRewardTransaction($resourceId);
                break;
            case 'users':
                $this->getUser($resourceId);
                break;
            default:
                $this->sendError('Endpoint not found', 404);
        }
    }

    /**
     * Handle nested resource GET requests
     * e.g., /users/{id}/orders, /users/{id}/orders/{orderId}
     * 
     * @param string $endpoint Parent endpoint
     * @param mixed $resourceId Parent resource ID
     * @param string $subResource Nested resource name
     * @param mixed $subResourceId Nested resource ID (optional)
     */
    protected function handleNestedGet($endpoint, $resourceId, $subResource, $subResourceId = null)
    {
        switch ($endpoint) {
            case 'users':
                if ($subResource === 'orders') {
                    $this->getUserOrders($resourceId, $subResourceId);
                } elseif ($subResource === 'reward-transactions') {
                    $this->getUserRewardTransactions($resourceId, $subResourceId);
                } else {
                    $this->sendError('Sub-resource not found', 404);
                }
                break;
            default:
                $this->sendError('Nested resource not supported for this endpoint', 404);
        }
    }

    /**
     * Get all menu data including categories and items
     */
    private function getMenuData()
    {
        try {
            $menuData = [
                'items' => $this->getAllItems()
            ];

            $this->sendResponse($menuData, 'Menu data retrieved successfully');
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve menu data: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get a specific menu item by ID with its images and options
     * 
     * @param int $itemId
     */
    private function getMenuItem($itemId)
    {
        try {
            $item = $this->itemModel->getById($itemId);
            
            if (!$item) {
                $this->sendError('Menu item not found', 404);
                return;
            }
            
            // images/options removed (source tables no longer exist)
            
            $this->sendResponse($item, 'Menu item retrieved successfully');
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve menu item: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get all outlets data
     */
    private function getOutletsData()
    {
        try {
            $outlets = $this->outletModel->getAll();
            $this->sendResponse($outlets ?? [], 'Outlets retrieved successfully');
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve outlets: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get a specific outlet by ID
     * 
     * @param int $outletId
     */
    private function getOutlet($outletId)
    {
        try {
            $outlet = $this->outletModel->getById($outletId);
            
            if (!$outlet) {
                $this->sendError('Outlet not found', 404);
                return;
            }
            
            $this->sendResponse($outlet, 'Outlet retrieved successfully');
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve outlet: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get all vouchers data
     */
    private function getVouchersData()
    {
        try {
            $vouchers = $this->voucherModel->getAll();
            $this->sendResponse($vouchers ?? [], 'Vouchers retrieved successfully');
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve vouchers: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get a specific voucher by ID
     * 
     * @param int $voucherId
     */
    private function getVoucher($voucherId)
    {
        try {
            $voucher = $this->voucherModel->getById($voucherId);
            
            if (!$voucher) {
                $this->sendError('Voucher not found', 404);
                return;
            }
            
            // Include voucher rules
            $rules = $this->voucherModel->getRulesByVoucherId($voucherId);
            if (is_array($voucher)) {
                $voucher['rules'] = $rules ?? [];
            } else {
                $voucher->rules = $rules ?? [];
            }
            
            $this->sendResponse($voucher, 'Voucher retrieved successfully');
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve voucher: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get all reward items data
     */
    private function getRewardsData()
    {
        try {
            $rewards = $this->rewardModel->getAll();
            $this->sendResponse($rewards ?? [], 'Reward items retrieved successfully');
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve reward items: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get a specific reward item by ID
     * 
     * @param int $rewardId
     */
    private function getReward($rewardId)
    {
        try {
            $reward = $this->rewardModel->getById($rewardId);
            
            if (!$reward) {
                $this->sendError('Reward item not found', 404);
                return;
            }
            
            $this->sendResponse($reward, 'Reward item retrieved successfully');
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve reward item: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get all users data
     */
    private function getUsersData()
    {
        try {
            $users = $this->userModel->getAll();
            
            // Remove sensitive data
            if ($users) {
                foreach ($users as &$user) {
                    unset($user['password']);
                }
            }
            
            $this->sendResponse($users ?? [], 'Users retrieved successfully');
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve users: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get a specific user by ID with their orders
     * 
     * GET /api/v1/users/{id}
     * Optional query params:
     *   - include_orders=true: Include user's order history
     *   - order_details=true: Include full order details (items, vouchers)
     * 
     * @param int $userId
     */
    private function getUser($userId)
    {
        try {
            $user = $this->userModel->getOne($userId);
            
            if (!$user) {
                $this->sendError('User not found', 404);
                return;
            }
            
            // Remove sensitive data
            unset($user['password']);
            
            // Check if orders should be included
            $includeOrders = $this->getQuery('include_orders', 'false') === 'true';
            $orderDetails = $this->getQuery('order_details', 'false') === 'true';
            
            if ($includeOrders) {
                if ($orderDetails) {
                    $user['orders'] = $this->orderModel->getUserOrdersWithDetails($userId);
                } else {
                    $user['orders'] = $this->orderModel->getByUserId($userId);
                }
            }
            
            $this->sendResponse($user, 'User retrieved successfully');
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve user: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/users/{userId}/orders - Get user orders
     * This handles nested resource: users/{id}/orders
     */
    private function getUserOrders($userId, $orderId = null)
    {
        try {
            // Verify user exists
            $user = $this->userModel->getOne($userId);
            
            if (!$user) {
                $this->sendError('User not found', 404);
                return;
            }
            
            $includeDetails = $this->getQuery('details', 'false') === 'true';
            
            if ($orderId) {
                // Get specific order
                $order = $this->orderModel->getOrderWithDetails($orderId);
                
                if (!$order) {
                    $this->sendError('Order not found', 404);
                    return;
                }
                
                // Verify order belongs to user
                if ($order['user_id'] != $userId) {
                    $this->sendError('Order does not belong to this user', 403);
                    return;
                }
                
                $this->sendResponse($order, 'Order retrieved successfully');
            } else {
                // Get all orders for user
                if ($includeDetails) {
                    $orders = $this->orderModel->getUserOrdersWithDetails($userId);
                } else {
                    $orders = $this->orderModel->getByUserId($userId);
                }
                
                $this->sendResponse($orders, 'User orders retrieved successfully');
            }
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve user orders: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get all reward transactions
     */
    private function getRewardTransactionsData()
    {
        try {
            $transactions = $this->rewardModel->getAllTransactions();
            $this->sendResponse($transactions ?? [], 'Reward transactions retrieved successfully');
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve reward transactions: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get a specific reward transaction by ID
     * 
     * @param int $transactionId
     */
    private function getRewardTransaction($transactionId)
    {
        try {
            $transaction = $this->rewardModel->getTransactionById($transactionId);
            
            if (!$transaction) {
                $this->sendError('Reward transaction not found', 404);
                return;
            }
            
            $this->sendResponse($transaction, 'Reward transaction retrieved successfully');
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve reward transaction: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/users/{userId}/reward-transactions - Get user reward transactions
     * Handles nested resource: users/{id}/reward-transactions and users/{id}/reward-transactions/{transactionId}
     */
    private function getUserRewardTransactions($userId, $transactionId = null)
    {
        try {
            // Verify user exists
            $user = $this->userModel->getOne($userId);
            
            if (!$user) {
                $this->sendError('User not found', 404);
                return;
            }
            
            if ($transactionId) {
                // Get specific transaction
                $transaction = $this->rewardModel->getTransactionById($transactionId);
                
                if (!$transaction) {
                    $this->sendError('Reward transaction not found', 404);
                    return;
                }
                
                // Verify transaction belongs to user
                if ($transaction['user_id'] != $userId) {
                    $this->sendError('Reward transaction does not belong to this user', 403);
                    return;
                }
                
                $this->sendResponse($transaction, 'Reward transaction retrieved successfully');
            } else {
                // Get all transactions for user
                $transactions = $this->rewardModel->getTransactionsByUserId($userId);
                $this->sendResponse([
                    'user_id' => (int)$userId,
                    'reward_points' => (int)($user['reward_points'] ?? 0),
                    'transactions' => $transactions ?? []
                ], 'User reward transactions retrieved successfully');
            }
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve user reward transactions: ' . $e->getMessage(), 500);
        }
    }

    /**
     * POST /api/v1/reward-transactions - Redeem reward points for a reward item
     * 
     * Request body:
     * {
     *   "user_id": 1,             (required)
     *   "reward_item_id": 2,      (required)
     *   "type": "redeem"          (required, must be "earn" or "redeem")
     *   "points": 100             (required for "earn", ignored for "redeem")
     *   "order_id": null          (optional, link to an order)
     * }
     * 
     * For "redeem": points are determined by the reward item's required_points.
     * For "earn": points must be specified in the request body.
     */
    private function handleCreateRewardTransaction()
    {
        try {
            $data = $this->getRequestData();

            // ── Validate required fields ──
            if (empty($data['user_id'])) {
                $this->sendError('user_id is required', 400);
                return;
            }

            if (empty($data['type']) || !in_array($data['type'], ['earn', 'redeem'])) {
                $this->sendError('type is required and must be "earn" or "redeem"', 400);
                return;
            }

            // ── Validate user exists and is active ──
            $user = $this->userModel->getOne($data['user_id']);
            if (!$user) {
                $this->sendError('User not found', 404);
                return;
            }
            if ($user['status'] !== 'active') {
                $this->sendError('User account is blocked', 403);
                return;
            }

            $type = $data['type'];
            $points = 0;
            $rewardItem = null;

            if ($type === 'redeem') {
                // ── Redeem: reward_item_id required ──
                if (empty($data['reward_item_id'])) {
                    $this->sendError('reward_item_id is required for redeem transactions', 400);
                    return;
                }

                $rewardItem = $this->rewardModel->getById($data['reward_item_id']);
                if (!$rewardItem) {
                    $this->sendError('Reward item not found', 404);
                    return;
                }

                $points = (int)$rewardItem['required_points'];

                // Check user has enough points
                $currentPoints = (int)($user['reward_points'] ?? 0);
                if ($currentPoints < $points) {
                    $this->sendError('Insufficient reward points. Required: ' . $points . ', Available: ' . $currentPoints, 400);
                    return;
                }

                // Deduct points from user
                $newPoints = $currentPoints - $points;
                $this->userModel->update($data['user_id'], [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'reward_points' => $newPoints,
                    'status' => $user['status']
                ]);
            } else {
                // ── Earn: points required ──
                if (empty($data['points']) || (int)$data['points'] < 1) {
                    $this->sendError('points is required and must be at least 1 for earn transactions', 400);
                    return;
                }

                $points = (int)$data['points'];

                // Add points to user
                $currentPoints = (int)($user['reward_points'] ?? 0);
                $newPoints = $currentPoints + $points;
                $this->userModel->update($data['user_id'], [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'reward_points' => $newPoints,
                    'status' => $user['status']
                ]);
            }

            // ── Validate optional order_id ──
            $orderId = $data['order_id'] ?? null;
            if ($orderId) {
                $order = $this->orderModel->getById($orderId);
                if (!$order) {
                    $this->sendError('Order not found', 404);
                    return;
                }
            }

            // ── Create the transaction ──
            $transactionId = $this->rewardModel->createTransaction([
                'user_id' => $data['user_id'],
                'order_id' => $orderId,
                'points' => $points,
                'type' => $type
            ]);

            if (!$transactionId) {
                $this->sendError('Failed to create reward transaction', 500);
                return;
            }

            // ── Build response ──
            $transaction = $this->rewardModel->getTransactionById($transactionId);
            $updatedUser = $this->userModel->getOne($data['user_id']);

            $response = [
                'transaction' => $transaction,
                'reward_points_balance' => (int)($updatedUser['reward_points'] ?? 0)
            ];

            if ($rewardItem) {
                $response['reward_item'] = [
                    'id' => $rewardItem['id'],
                    'item_name' => $rewardItem['item_name'],
                    'required_points' => $rewardItem['required_points']
                ];
            }

            $this->sendCreated($response, 'Reward transaction created successfully');

        } catch (Exception $e) {
            $this->sendError('Failed to create reward transaction: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get all categories with item count
     * 
     * @return array
     */
    private function getAllCategories()
    {
        $categories = $this->categoryModel->getAll();
        
        // Add item count to each category
        if ($categories) {
            foreach ($categories as &$category) {
                $categoryId = is_array($category) ? $category['id'] : $category->id;
                $itemCount = count(array_filter($this->itemModel->getAll(), 
                    function($item) use ($categoryId) {
                        $itemCategoryId = is_array($item) ? $item['category_id'] : $item->category_id;
                        return $itemCategoryId == $categoryId;
                    }
                ));
                
                if (is_array($category)) {
                    $category['item_count'] = $itemCount;
                } else {
                    $category->item_count = $itemCount;
                }
            }
        }
        
        return $categories ?? [];
    }

    /**
     * Get all menu items with category information
     * 
     * @return array
     */
    private function getAllItems()
    {
        $items = $this->itemModel->getAll();
        
        return $items ?? [];
    }

    /**
     * POST /api/v1/{endpoint} - Handle POST requests
     * 
     * @param string $endpoint
     */
    protected function handlePost($endpoint)
    {
        switch ($endpoint) {
            case 'auth':
                $this->handleAuthCheck();
                break;
            case 'orders':
                $this->handleCreateOrder();
                break;
            case 'reward-transactions':
                $this->handleCreateRewardTransaction();
                break;
            default:
                $this->sendError('POST method not implemented for ' . $endpoint, 501);
        }
    }

    /**
     * POST /api/v1/auth - Check existing user or register new user
     * 
     * Request body:
     * {
     *   "provider": "google" | "facebook",
     *   "provider_user_id": "string",
     *   "name": "string",
     *   "email": "string" (optional),
     *   "phone": "string" (optional)
     * }
     * 
     * Response:
     * - If user exists: returns user data with is_new_user = false
     * - If new user: creates user and returns data with is_new_user = true
     */
    private function handleAuthCheck()
    {
        try {
            $data = $this->getRequestData();

            // Validate required fields
            if (empty($data['provider'])) {
                $this->sendError('Provider is required', 400);
                return;
            }

            if (empty($data['provider_user_id'])) {
                $this->sendError('Provider user ID is required', 400);
                return;
            }

            if (empty($data['name'])) {
                $this->sendError('Name is required', 400);
                return;
            }

            $provider = strtolower($data['provider']);
            $providerUserId = $data['provider_user_id'];

            // Validate provider
            if (!in_array($provider, ['google', 'facebook'])) {
                $this->sendError('Invalid provider. Allowed: google, facebook', 400);
                return;
            }

            // Check if user exists by provider identity
            $existingUser = $this->userModel->findByProviderIdentity($provider, $providerUserId);

            if ($existingUser) {
                // User exists, return user data
                $existingUser['is_new_user'] = false;
                unset($existingUser['password']); // Remove sensitive data
                $this->sendResponse($existingUser, 'User found');
                return;
            }

            // User doesn't exist, register new user
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'] ?? null
            ];

            $newUser = $this->userModel->registerFromProvider($userData, $provider, $providerUserId);

            if (!$newUser) {
                $this->sendError('Failed to register user', 500);
                return;
            }

            $newUser['is_new_user'] = true;
            unset($newUser['password']); // Remove sensitive data
            $this->sendCreated($newUser, 'User registered successfully');

        } catch (Exception $e) {
            $this->sendError('Authentication failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * POST /api/v1/orders - Create a new order
     * 
     * Request body:
     * {
     *   "user_id": 1,                          (required)
     *   "outlet_id": 3,                         (required)
     *   "order_type": "pickup" | "dine_in",     (required)
     *   "items": [                              (required, min 1)
     *     {
     *       "menu_item_id": 1,
     *       "quantity": 2
     *     }
     *   ],
     *   "voucher_codes": ["BALLZ5"]             (optional, array of voucher codes)
     * }
     * 
     * Response: Full order details including calculated totals and applied vouchers
     */
    private function handleCreateOrder()
    {
        try {
            $data = $this->getRequestData();

            // ── Validate required fields ──
            if (empty($data['user_id'])) {
                $this->sendError('user_id is required', 400);
                return;
            }
            if (empty($data['outlet_id'])) {
                $this->sendError('outlet_id is required', 400);
                return;
            }
            if (empty($data['order_type']) || !in_array($data['order_type'], ['pickup', 'dine_in'])) {
                $this->sendError('order_type is required and must be "pickup" or "dine_in"', 400);
                return;
            }
            if (empty($data['items']) || !is_array($data['items']) || count($data['items']) === 0) {
                $this->sendError('items is required and must be a non-empty array', 400);
                return;
            }

            // ── Validate user exists ──
            $user = $this->userModel->getOne($data['user_id']);
            if (!$user) {
                $this->sendError('User not found', 404);
                return;
            }
            if ($user['status'] !== 'active') {
                $this->sendError('User account is blocked', 403);
                return;
            }

            // ── Validate outlet exists and is active ──
            $outlet = $this->outletModel->getById($data['outlet_id']);
            if (!$outlet) {
                $this->sendError('Outlet not found', 404);
                return;
            }
            if (!$outlet['is_active']) {
                $this->sendError('Outlet is not active', 400);
                return;
            }

            // ── Validate & resolve order items ──
            $orderItems = [];
            $subtotal = 0;

            foreach ($data['items'] as $index => $item) {
                if (empty($item['menu_item_id'])) {
                    $this->sendError("items[$index].menu_item_id is required", 400);
                    return;
                }
                if (empty($item['quantity']) || $item['quantity'] < 1) {
                    $this->sendError("items[$index].quantity must be at least 1", 400);
                    return;
                }

                $menuItem = $this->itemModel->getById($item['menu_item_id']);
                if (!$menuItem) {
                    $this->sendError("Menu item with ID {$item['menu_item_id']} not found", 404);
                    return;
                }
                if (!$menuItem['is_active']) {
                    $this->sendError("Menu item '{$menuItem['name']}' is currently unavailable", 400);
                    return;
                }

                $quantity = (int)$item['quantity'];
                $unitPrice = (float)$menuItem['price'];
                $totalPrice = round($unitPrice * $quantity, 2);

                $orderItems[] = [
                    'menu_item_id' => $menuItem['id'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                    'item_name' => $menuItem['name']
                ];

                $subtotal += $totalPrice;
            }

            $subtotal = round($subtotal, 2);

            // ── Validate & apply vouchers ──
            $discountTotal = 0;
            $appliedVouchers = [];
            $voucherCodes = $data['voucher_codes'] ?? [];

            if (!empty($voucherCodes) && is_array($voucherCodes)) {
                // Prevent duplicate voucher codes
                $voucherCodes = array_unique($voucherCodes);

                foreach ($voucherCodes as $code) {
                    $validation = $this->voucherModel->validateForOrder($code, $subtotal);

                    if (!$validation['valid']) {
                        $this->sendError("Voucher '$code': " . $validation['error'], 400);
                        return;
                    }

                    $voucher = $validation['voucher'];
                    $discount = $this->voucherModel->calculateDiscount($voucher, $subtotal);

                    $appliedVouchers[] = [
                        'voucher_id' => $voucher['id'],
                        'code' => $voucher['code'],
                        'name' => $voucher['name'],
                        'discount_type' => $voucher['discount_type'],
                        'discount_value' => $voucher['discount_value'],
                        'discount_applied' => $discount
                    ];

                    $discountTotal += $discount;
                }
            }

            $discountTotal = round(min($discountTotal, $subtotal), 2); // discount cannot exceed subtotal
            $finalTotal = round($subtotal - $discountTotal, 2);

            // ── Create the order ──
            $orderId = $this->orderModel->create([
                'user_id' => $data['user_id'],
                'outlet_id' => $data['outlet_id'],
                'order_type' => $data['order_type'],
                'subtotal' => $subtotal,
                'discount_total' => $discountTotal,
                'final_total' => $finalTotal,
                'status' => 'pending'
            ]);

            if (!$orderId) {
                $this->sendError('Failed to create order', 500);
                return;
            }

            // ── Insert order items ──
            foreach ($orderItems as $oi) {
                $success = $this->orderModel->addOrderItem([
                    'order_id' => $orderId,
                    'menu_item_id' => $oi['menu_item_id'],
                    'quantity' => $oi['quantity'],
                    'unit_price' => $oi['unit_price'],
                    'total_price' => $oi['total_price']
                ]);

                if (!$success) {
                    $this->sendError('Failed to add item to order', 500);
                    return;
                }
            }

            // ── Insert order vouchers ──
            foreach ($appliedVouchers as $av) {
                $success = $this->orderModel->addOrderVoucher([
                    'order_id' => $orderId,
                    'voucher_id' => $av['voucher_id'],
                    'discount_applied' => $av['discount_applied']
                ]);

                if (!$success) {
                    $this->sendError('Failed to apply voucher to order', 500);
                    return;
                }
            }

            // ── Award reward points (1 point per RM1 spent) ──
            $pointsEarned = (int)floor($finalTotal);
            if ($pointsEarned > 0) {
                $this->orderModel->addRewardTransaction([
                    'user_id' => $data['user_id'],
                    'order_id' => $orderId,
                    'points' => $pointsEarned,
                    'type' => 'earn'
                ]);

                // Update user reward points
                $newPoints = ($user['reward_points'] ?? 0) + $pointsEarned;
                $this->userModel->update($data['user_id'], [
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'phone' => $user['phone'],
                    'reward_points' => $newPoints,
                    'status' => $user['status']
                ]);
            }

            // ── Build response ──
            $order = $this->orderModel->getOrderWithDetails($orderId);
            $order['points_earned'] = $pointsEarned;

            $this->sendCreated($order, 'Order created successfully');

        } catch (Exception $e) {
            $this->sendError('Failed to create order: ' . $e->getMessage(), 500);
        }
    }

}