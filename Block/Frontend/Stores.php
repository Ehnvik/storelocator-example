<?php
namespace Gustav\Thesis\Block\Frontend;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Gustav\Thesis\Model\ResourceModel\Stores\CollectionFactory as StoreCollectionFactory;
use Gustav\Thesis\Model\ResourceModel\Categories\CollectionFactory as CategoryCollectionFactory;

class Stores extends Template
{
    protected $storeCollectionFactory;
    protected $categoryCollectionFactory;

    public function __construct(
        Context $context,
        StoreCollectionFactory $storeCollectionFactory,
        CategoryCollectionFactory $categoryCollectionFactory,
        array $data = []
    ) {
        $this->storeCollectionFactory = $storeCollectionFactory;
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        parent::__construct($context, $data);
    }


    public function getStores(): array
    {
        $collection = $this->storeCollectionFactory->create();
        $collection->getSelect()->join(
            ['relation' => 'store_category_relation'],
            'main_table.store_id = relation.store_id',
            []
        )->join(
            ['categories' => 'store_categories'],
            'relation.category_id = categories.category_id',
            ['category_name']
        );

        $collection->setPageSize(5);
        $collection->setCurPage(1);

        return $collection->getItems();
    }


    public function getCategories(): array
    {
        $collection = $this->categoryCollectionFactory->create();

        return $collection->getItems();
    }

}
