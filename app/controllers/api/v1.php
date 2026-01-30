<?php


class v1 extends Api
{
    private $categoryModel;
    private $itemModel;
    private $outletModel;
    private $voucherModel;
    private $userModel;

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
            default:
                $this->sendError('Endpoint not found', 404);
        }
    }

    /**
     * GET /api/v1/menu/{id} - Get specific menu item
     * Returns a single menu item
     */
    protected function handleGet($endpoint, $resourceId)
    {
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
            default:
                $this->sendError('Endpoint not found', 404);
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
            $this->sendResponse($newUser, 'User registered successfully', 201);

        } catch (Exception $e) {
            $this->sendError('Authentication failed: ' . $e->getMessage(), 500);
        }
    }

}