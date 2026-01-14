<?php


class v1 extends Api
{
    private $categoryModel;
    private $itemModel;
    private $imageModel;
    private $optionModel;

    public function __construct()
    {
        parent::__construct();
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
        // If endpoint is passed by the router, use it
        if ($endpoint) {
            $this->endpoint = $endpoint;
        }
        if ($resourceId) {
            $this->resourceId = $resourceId;
        }
        
        $this->route($this->endpoint, $this->method, $this->resourceId);
    }

    /**
     * Initialize all models needed for menu operations
     */
    private function initializeModels()
    {
        $this->categoryModel = new CategoryModel();
        $this->itemModel = new MenuItemModel();
        $this->imageModel = new MenuImageModel();
        $this->optionModel = new MenuOptionModel();
    }

    /**
     * GET /api/v1/menu - Get complete menu data
     * Returns all categories, items, images, and options
     */
    protected function handleGetList($endpoint)
    {
        if ($endpoint === 'menu') {
            $this->getMenuData();
        } else {
            $this->sendError('Endpoint not found', 404);
        }
    }

    /**
     * GET /api/v1/menu/{id} - Get specific menu item
     * Returns a single menu item with its images and options
     */
    protected function handleGet($endpoint, $resourceId)
    {
        if ($endpoint === 'menu') {
            $this->getMenuItem($resourceId);
        } else {
            $this->sendError('Endpoint not found', 404);
        }
    }

    /**
     * Get all menu data including categories, items, images, and options
     */
    private function getMenuData()
    {
        try {
            $menuData = [
                'items' => $this->getAllItems(),
                'images' => $this->getAllImages()
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
            
            // Add images and options to the item
            $images = $this->getItemImages($itemId);
            $options = $this->getItemOptions($itemId);
            
            if (is_array($item)) {
                $item['images'] = $images;
                $item['options'] = $options;
            } else {
                $item->images = $images;
                $item->options = $options;
            }
            
            $this->sendResponse($item, 'Menu item retrieved successfully');
        } catch (Exception $e) {
            $this->sendError('Failed to retrieve menu item: ' . $e->getMessage(), 500);
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
        
        // Add images and options to each item
        if ($items) {
            foreach ($items as &$item) {
                $itemId = is_array($item) ? $item['id'] : $item->id;
                $images = $this->getItemImages($itemId);
                $options = $this->getItemOptions($itemId);
                
                if (is_array($item)) {
                    $item['images'] = $images;
                    $item['options'] = $options;
                } else {
                    $item->images = $images;
                    $item->options = $options;
                }
            }
        }
        
        return $items ?? [];
    }

    /**
     * Get all menu images
     * 
     * @return array
     */
    private function getAllImages()
    {
        return $this->imageModel->getAll() ?? [];
    }

    /**
     * Get all menu option groups
     * 
     * @return array
     */
    private function getAllOptions()
    {
        return $this->optionModel->getGroups() ?? [];
    }

    /**
     * Get images for a specific menu item
     * Returns placeholder image if no images exist
     * 
     * @param int $itemId
     * @return array
     */
    private function getItemImages($itemId)
    {
        $allImages = $this->getAllImages();
        $images = array_filter($allImages, function($image) use ($itemId) {
            $imageItemId = is_array($image) ? $image['menu_item_id'] : $image->menu_item_id;
            return $imageItemId == $itemId;
        });
        
        // If no images found, return placeholder
        if (empty($images)) {
            return [
                [
                    'id' => null,
                    'menu_item_id' => $itemId,
                    'image_path' => 'placeholder_food.png',
                    'is_placeholder' => true
                ]
            ];
        }
        
        return $images;
    }

    /**
     * Get option groups for a specific menu item
     * 
     * @param int $itemId
     * @return array
     */
    private function getItemOptions($itemId)
    {
        return $this->optionModel->getGroups($itemId) ?? [];
    }
}