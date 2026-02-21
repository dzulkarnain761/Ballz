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
    private $paymentModel;

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
        $this->paymentModel = new PaymentModel();
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
            case 'payments':
                $this->getPaymentsData();
                break;
            case 'payment-methods':
                $this->getPaymentMethods();
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
            case 'payments':
                $this->getPayment($resourceId);
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
            case 'login':
                $this->handleLogin();
                break;
            case 'refresh-token':
                $this->handleRefreshToken();
                break;
            case 'logout':
                $this->handleLogout();
                break;
            case 'orders':
                $this->handleCreateOrder();
                break;
            case 'reward-transactions':
                $this->handleCreateRewardTransaction();
                break;
            case 'payments':
                $this->handleInitiatePayment();
                break;
            case 'payment-callback':
                $this->handlePaymentCallback();
                break;
            default:
                $this->sendError('POST method not implemented for ' . $endpoint, 501);
        }
    }

    /**
     * POST /api/v1/login - Authenticate user with email and password
     * Returns JWT access token and refresh token
     * 
     * Request body:
     * {
     *   "email": "user@example.com",   (required)
     *   "password": "secret"            (required)
     * }
     * 
     * Response: JWT tokens + user data on success
     */
    private function handleLogin()
    {
        try {
            $data = $this->getRequestData();

            // Validate required fields
            if (empty($data['email'])) {
                $this->sendError('Email is required', 400);
                return;
            }

            if (empty($data['password'])) {
                $this->sendError('Password is required', 400);
                return;
            }

            // Validate email format
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->sendError('Invalid email format', 400);
                return;
            }

            // Authenticate
            $user = $this->userModel->authenticateByEmail($data['email'], $data['password']);

            if (!$user) {
                $this->sendError('Invalid email or password', 401);
                return;
            }

            // Check if user is active
            if ($user['status'] !== 'active') {
                $this->sendError('User account is blocked', 403);
                return;
            }

            // Remove sensitive data
            unset($user['password']);

            // Generate JWT tokens
            $response = $this->issueJwtTokens($user);

            $this->sendResponse($response, 'Login successful');

        } catch (Exception $e) {
            $this->sendError('Login failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * POST /api/v1/auth - Check existing user or register new user
     * Returns JWT access token and refresh token
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
     * - If user exists: returns JWT tokens + user data with is_new_user = false
     * - If new user: creates user and returns JWT tokens + data with is_new_user = true
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
                // User exists, return JWT tokens + user data
                $existingUser['is_new_user'] = false;
                unset($existingUser['password']);

                $response = $this->issueJwtTokens($existingUser);
                $this->sendResponse($response, 'User found');
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
            unset($newUser['password']);

            $response = $this->issueJwtTokens($newUser);
            $this->sendCreated($response, 'User registered successfully');

        } catch (Exception $e) {
            $this->sendError('Authentication failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * POST /api/v1/refresh-token - Get new access token using refresh token
     * 
     * Request body:
     * {
     *   "refresh_token": "string" (required)
     * }
     */
    private function handleRefreshToken()
    {
        try {
            $data = $this->getRequestData();

            if (empty($data['refresh_token'])) {
                $this->sendError('refresh_token is required', 400);
                return;
            }

            $rawToken = $data['refresh_token'];
            $tokenHash = JWT::hashRefreshToken($rawToken);

            // Find refresh token in database
            $storedToken = $this->userModel->findRefreshToken($tokenHash);

            if (!$storedToken) {
                $this->sendUnauthorized('Invalid refresh token.');
                return;
            }

            // Check if token is expired
            if (strtotime($storedToken['expires_at']) < time()) {
                // Clean up expired token
                $this->userModel->deleteRefreshToken($tokenHash);
                $this->sendUnauthorized('Refresh token has expired. Please login again.');
                return;
            }

            // Get user
            $user = $this->userModel->getOne($storedToken['user_id']);

            if (!$user) {
                $this->userModel->deleteRefreshToken($tokenHash);
                $this->sendError('User not found', 404);
                return;
            }

            if ($user['status'] !== 'active') {
                $this->userModel->deleteRefreshTokensByUserId($user['id']);
                $this->sendError('User account is blocked', 403);
                return;
            }

            // Delete old refresh token (rotation: one-time use)
            $this->userModel->deleteRefreshToken($tokenHash);

            // Issue new token pair
            unset($user['password']);
            $response = $this->issueJwtTokens($user);

            $this->sendResponse($response, 'Token refreshed successfully');

        } catch (Exception $e) {
            $this->sendError('Token refresh failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * POST /api/v1/logout - Revoke refresh token
     * 
     * Request body:
     * {
     *   "refresh_token": "string" (required)
     * }
     */
    private function handleLogout()
    {
        try {
            $data = $this->getRequestData();

            if (empty($data['refresh_token'])) {
                $this->sendError('refresh_token is required', 400);
                return;
            }

            $tokenHash = JWT::hashRefreshToken($data['refresh_token']);
            $this->userModel->deleteRefreshToken($tokenHash);

            $this->sendResponse(null, 'Logged out successfully');

        } catch (Exception $e) {
            $this->sendError('Logout failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Helper: Issue JWT access + refresh tokens for a user
     * Stores the refresh token hash in the database
     * 
     * @param array $user User data (must have 'id', 'email')
     * @return array Response with tokens + user data
     */
    private function issueJwtTokens($user)
    {
        // Create access token
        $accessToken = JWT::createAccessToken([
            'user_id' => (int)$user['id'],
            'email' => $user['email'] ?? null,
        ]);

        // Create refresh token
        $refreshData = JWT::createRefreshToken((int)$user['id']);

        // Store refresh token hash in database
        $this->userModel->storeRefreshToken(
            (int)$user['id'],
            $refreshData['token_hash'],
            $refreshData['expires_at']
        );

        return [
            'access_token' => $accessToken,
            'refresh_token' => $refreshData['token'],
            'token_type' => 'Bearer',
            'expires_in' => defined('ACCESS_TOKEN_LIFETIME') ? ACCESS_TOKEN_LIFETIME : 900,
            'user' => $user
        ];
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

    // ═══════════════════════════════════════════════════════════════
    // PAYMENT GATEWAY SIMULATION (FPX / DuitNow)
    // ═══════════════════════════════════════════════════════════════

    /**
     * FPX Bank list for simulation
     */
    private function getFpxBanks()
    {
        return [
            ['code' => 'MBB',  'name' => 'Maybank2u',          'status' => 'online'],
            ['code' => 'CIMB', 'name' => 'CIMB Clicks',         'status' => 'online'],
            ['code' => 'PBB',  'name' => 'Public Bank',         'status' => 'online'],
            ['code' => 'RHB',  'name' => 'RHB Now',             'status' => 'online'],
            ['code' => 'HLB',  'name' => 'Hong Leong Connect',  'status' => 'online'],
            ['code' => 'AMB',  'name' => 'AmOnline',            'status' => 'online'],
            ['code' => 'BIMB', 'name' => 'Bank Islam',          'status' => 'online'],
            ['code' => 'BSN',  'name' => 'BSN',                 'status' => 'online'],
            ['code' => 'OCBC', 'name' => 'OCBC Bank',           'status' => 'online'],
            ['code' => 'UOB',  'name' => 'UOB Bank',            'status' => 'online'],
            ['code' => 'HSBC', 'name' => 'HSBC Bank',           'status' => 'offline'],
            ['code' => 'SCB',  'name' => 'Standard Chartered',  'status' => 'offline'],
        ];
    }

    /**
     * GET /api/v1/payment-methods - Get available payment methods & FPX bank list
     * Public endpoint - no auth required
     */
    private function getPaymentMethods()
    {
        $response = [
            'payment_methods' => [
                [
                    'code' => 'fpx',
                    'name' => 'FPX Online Banking',
                    'description' => 'Pay using your bank\'s online banking',
                    'icon' => 'fpx',
                    'banks' => $this->getFpxBanks()
                ],
                [
                    'code' => 'duitnow',
                    'name' => 'DuitNow QR',
                    'description' => 'Scan & pay with any DuitNow-supported banking app',
                    'icon' => 'duitnow',
                    'banks' => [] // DuitNow doesn't need bank selection
                ]
            ]
        ];
        $this->sendResponse($response, 'Payment methods retrieved successfully');
    }

    /**
     * GET /api/v1/payments - Get all payments (admin) or user payments
     */
    private function getPaymentsData()
    {
        try {
            // If authenticated user, show their payments only
            $userId = $this->getAuthenticatedUserId();
            if ($userId) {
                $payments = $this->paymentModel->getByUserId($userId);
            } else {
                $payments = $this->paymentModel->getAll();
            }
            $this->sendResponse($payments ?? [], 'Payments retrieved successfully');
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve payments: ' . $e->getMessage(), 500);
        }
    }

    /**
     * GET /api/v1/payments/{id} - Get payment status by ID
     * Mobile app polls this to check payment result
     * 
     * @param int $paymentId
     */
    private function getPayment($paymentId)
    {
        try {
            $payment = $this->paymentModel->getById($paymentId);

            if (!$payment) {
                $this->sendError('Payment not found', 404);
                return;
            }

            // Check if payment has expired
            if (in_array($payment['status'], ['pending', 'processing']) && strtotime($payment['expires_at']) < time()) {
                $this->paymentModel->updateStatus($payment['id'], 'expired');
                $this->orderModel->updateStatus($payment['order_id'], 'cancelled');
                $payment['status'] = 'expired';
            }

            $this->sendResponse($payment, 'Payment retrieved successfully');
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve payment: ' . $e->getMessage(), 500);
        }
    }

    /**
     * POST /api/v1/payments - Initiate a payment
     * 
     * Request body:
     * {
     *   "order_id": 1,                              (required)
     *   "payment_method": "fpx" | "duitnow",        (required)
     *   "bank_code": "MBB"                           (required for FPX, optional for DuitNow)
     * }
     * 
     * Response:
     * {
     *   "payment_id": 1,
     *   "payment_ref": "BZ-FPX-20260221...",
     *   "payment_url": "https://..../payment/gateway?ref=...",
     *   "status": "pending",
     *   "expires_at": "2026-02-21 15:30:00"
     * }
     */
    private function handleInitiatePayment()
    {
        try {
            $data = $this->getRequestData();

            // ── Validate required fields ──
            if (empty($data['order_id'])) {
                $this->sendError('order_id is required', 400);
                return;
            }

            $validMethods = ['fpx', 'duitnow'];
            if (empty($data['payment_method']) || !in_array($data['payment_method'], $validMethods)) {
                $this->sendError('payment_method is required and must be "fpx" or "duitnow"', 400);
                return;
            }

            $paymentMethod = $data['payment_method'];

            // FPX requires bank_code
            if ($paymentMethod === 'fpx') {
                if (empty($data['bank_code'])) {
                    $this->sendError('bank_code is required for FPX payments', 400);
                    return;
                }
                // Validate bank code
                $validBanks = array_column($this->getFpxBanks(), 'code');
                if (!in_array($data['bank_code'], $validBanks)) {
                    $this->sendError('Invalid bank_code. Use GET /api/v1/payment-methods for available banks.', 400);
                    return;
                }
                // Check bank is online
                $banks = $this->getFpxBanks();
                foreach ($banks as $bank) {
                    if ($bank['code'] === $data['bank_code'] && $bank['status'] === 'offline') {
                        $this->sendError('Selected bank is currently offline. Please try another bank.', 503);
                        return;
                    }
                }
            }

            // ── Validate order exists ──
            $order = $this->orderModel->getById($data['order_id']);
            if (!$order) {
                $this->sendError('Order not found', 404);
                return;
            }

            // ── Validate order is in pending status ──
            if ($order['status'] !== 'pending') {
                $this->sendError('Order is not in pending status. Current status: ' . $order['status'], 400);
                return;
            }

            // ── Check for existing successful payment ──
            if ($this->paymentModel->hasSuccessfulPayment($data['order_id'])) {
                $this->sendError('Order has already been paid', 409);
                return;
            }

            // ── Expire any existing pending payments for this order ──
            $activePayment = $this->paymentModel->getActivePayment($data['order_id']);
            if ($activePayment) {
                $this->paymentModel->updateStatus($activePayment['id'], 'expired', 'Superseded by new payment attempt');
            }

            // ── Generate payment reference ──
            $paymentRef = PaymentModel::generatePaymentRef($paymentMethod);
            $expiresAt = date('Y-m-d H:i:s', strtotime('+15 minutes'));

            // ── Create payment record ──
            $paymentId = $this->paymentModel->create([
                'order_id' => $data['order_id'],
                'user_id' => $order['user_id'],
                'amount' => $order['final_total'],
                'payment_method' => $paymentMethod,
                'payment_ref' => $paymentRef,
                'bank_code' => $data['bank_code'] ?? null,
                'expires_at' => $expiresAt
            ]);

            if (!$paymentId) {
                $this->sendError('Failed to create payment', 500);
                return;
            }

            // ── Build payment URL (simulation page) ──
            $paymentUrl = ROOT . '/payment/gateway?ref=' . urlencode($paymentRef);

            $response = [
                'payment_id' => (int)$paymentId,
                'payment_ref' => $paymentRef,
                'payment_method' => $paymentMethod,
                'bank_code' => $data['bank_code'] ?? null,
                'amount' => (float)$order['final_total'],
                'currency' => 'MYR',
                'payment_url' => $paymentUrl,
                'status' => 'pending',
                'expires_at' => $expiresAt
            ];

            $this->sendCreated($response, 'Payment initiated successfully');

        } catch (Exception $e) {
            $this->sendError('Failed to initiate payment: ' . $e->getMessage(), 500);
        }
    }

    /**
     * POST /api/v1/payment-callback - Gateway callback (called by simulation page)
     * This simulates the payment gateway callback after user approves/rejects
     * 
     * Request body:
     * {
     *   "payment_ref": "BZ-FPX-20260221...",    (required)
     *   "status": "success" | "failed",          (required)
     *   "gateway_response": "Approved"           (optional)
     * }
     */
    private function handlePaymentCallback()
    {
        try {
            $data = $this->getRequestData();

            if (empty($data['payment_ref'])) {
                $this->sendError('payment_ref is required', 400);
                return;
            }

            $validStatuses = ['success', 'failed'];
            if (empty($data['status']) || !in_array($data['status'], $validStatuses)) {
                $this->sendError('status is required and must be "success" or "failed"', 400);
                return;
            }

            // Find payment
            $payment = $this->paymentModel->getByRef($data['payment_ref']);
            if (!$payment) {
                $this->sendError('Payment not found', 404);
                return;
            }

            // Check payment hasn't already been processed
            if (!in_array($payment['status'], ['pending', 'processing'])) {
                $this->sendError('Payment has already been processed. Status: ' . $payment['status'], 409);
                return;
            }

            // Check if payment has expired
            if (strtotime($payment['expires_at']) < time()) {
                $this->paymentModel->updateStatus($payment['id'], 'expired');
                $this->orderModel->updateStatus($payment['order_id'], 'cancelled');
                $this->sendError('Payment has expired', 410);
                return;
            }

            $newStatus = $data['status'];
            $gatewayResponse = $data['gateway_response'] ?? ($newStatus === 'success' ? 'Transaction approved' : 'Transaction declined by user');

            // ── Simulate processing delay (mark as processing briefly) ──
            $this->paymentModel->updateStatus($payment['id'], 'processing');

            // ── Update payment status ──
            $this->paymentModel->updateStatus($payment['id'], $newStatus, $gatewayResponse);

            // ── Update order status based on payment result ──
            if ($newStatus === 'success') {
                $this->orderModel->updateStatus($payment['order_id'], 'paid');
            } else {
                $this->orderModel->updateStatus($payment['order_id'], 'cancelled');
            }

            // ── Fetch updated payment ──
            $updatedPayment = $this->paymentModel->getById($payment['id']);

            $response = [
                'payment_id' => (int)$payment['id'],
                'payment_ref' => $payment['payment_ref'],
                'status' => $newStatus,
                'order_id' => (int)$payment['order_id'],
                'order_status' => $newStatus === 'success' ? 'paid' : 'cancelled',
                'gateway_response' => $gatewayResponse,
                'processed_at' => date('Y-m-d H:i:s')
            ];

            $this->sendResponse($response, 'Payment ' . ($newStatus === 'success' ? 'successful' : 'failed'));

        } catch (Exception $e) {
            $this->sendError('Payment callback failed: ' . $e->getMessage(), 500);
        }
    }

}