<?php

namespace Gustav\Thesis\Block\Frontend;

use Magento\Framework\View\Element\Template;
use Gustav\Thesis\ViewModel\StoreLocatorConfig;
use Gustav\Thesis\Model\ResourceModel\Stores\CollectionFactory as StoreCollectionFactory;

class Maps extends Template
{
    protected StoreLocatorConfig $storeLocatorConfig;
    protected StoreCollectionFactory $storeCollectionFactory;

    public function __construct(
        Template\Context $context,
        StoreLocatorConfig $storeLocatorConfig,
        StoreCollectionFactory $storeCollectionFactory,
        array $data = []
    ) {
        $this->storeLocatorConfig = $storeLocatorConfig;
        $this->storeCollectionFactory = $storeCollectionFactory;
        parent::__construct($context, $data);
    }

    public function getGoogleMapApiKey()
    {
        return $this->storeLocatorConfig->getApiKey();
    }

    public function getStores(): array
    {
        $collection = $this->storeCollectionFactory->create();
        return $collection->getItems();
    }
}
