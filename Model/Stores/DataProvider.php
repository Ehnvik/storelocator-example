<?php
namespace Gustav\Thesis\Model\Stores;

use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Gustav\Thesis\Model\ResourceModel\Stores\CollectionFactory;
use Gustav\Thesis\Model\ResourceModel\CategoriesRelation;

// This class is responsible for providing data for the UI components
class DataProvider extends AbstractDataProvider
{
    private $request;
    private $categoriesRelation;

    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        CategoriesRelation $categoriesRelation,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->request = $request;
        $this->categoriesRelation = $categoriesRelation;
    }

    public function getData(): array
    {
        if (!empty($this->loadedData)) {
            return $this->loadedData;
        }

        $data = parent::getData();

        $storeId = $this->request->getParam('store_id');
        if ($storeId) {
            $storeData = $this->collection->getItemById($storeId)->getData();

            $categoryIds = $this->categoriesRelation->getCategoryIdsByStoreId($storeId);
            $storeData['category_ids'] = $categoryIds;

            if ($storeData) {
                $this->loadedData[$storeId] = $storeData;
            }
        }

        return $this->loadedData ?: $data;
    }

}
