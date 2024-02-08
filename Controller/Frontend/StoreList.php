<?php
namespace Gustav\Thesis\Controller\Frontend;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\ResponseInterface;
use Gustav\Thesis\Model\ResourceModel\Stores\CollectionFactory;
use Gustav\Thesis\Model\ResourceModel\CategoriesRelation;

class StoreList implements HttpGetActionInterface
{
    protected $jsonFactory;
    protected $collectionFactory;
    protected $request;
    protected $categoriesRelation;

    public function __construct(
        RequestInterface $request,
        JsonFactory $jsonFactory,
        CollectionFactory $collectionFactory,
        CategoriesRelation $categoriesRelation
    ) {
        $this->request = $request;
        $this->jsonFactory = $jsonFactory;
        $this->collectionFactory = $collectionFactory;
        $this->categoriesRelation = $categoriesRelation;
    }

    // Controller for handling requests to list stores in the frontend
    public function execute()
    {
        $result = $this->jsonFactory->create();
        $page = (int) $this->request->getParam('page', 1);
        $pageSize = 5;
        $categoryId = $this->request->getParam('category');
        $searchQuery = $this->request->getParam('search', '');

        $collection = $this->collectionFactory->create();

        if ($categoryId) {
            $storeIds = $this->categoriesRelation->getStoreIdsByCategoryId($categoryId);
            $collection->addFieldToFilter('store_id', ['in' => $storeIds]);
        }

        if (!empty($searchQuery)) {
            // Apply search filter if search term is provided
            $collection->addFieldToFilter('store_name', ['like' => '%' . $searchQuery . '%']);
        }

        $collection->setPageSize($pageSize);
        $collection->setCurPage($page);

        // Prepare store data for response
        $stores = [];
        foreach ($collection as $store) {
            $stores[] = [
                'id' => $store->getStoreId(),
                'name' => $store->getStoreName(),
                'address' => $store->getAddress(),
                'city' => $store->getCity(),
                'postcode' => $store->getPostcode(),
                'country' => $store->getCountry(),
                'phone' => $store->getPhone(),
                'hours' => $store->getHours(),
                'latitude' => $store->getLatitude(),
                'longitude' => $store->getLongitude(),
            ];
        }

        // Set response data
        $result->setData([
            'stores' => $stores,
            'total_count' => $collection->getSize()
        ]);

        return $result;
    }
}
