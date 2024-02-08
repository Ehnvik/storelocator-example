<?php
namespace Gustav\Thesis\Controller\Adminhtml\Stores;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Gustav\Thesis\Model\StoresFactory;
use Gustav\Thesis\Model\ResourceModel\Stores as StoreResource;

class Edit extends Action
{
    protected $resultPageFactory;
    protected StoresFactory $storeFactory;
    protected StoreResource $storeResource;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        StoresFactory $storeFactory,
        StoreResource $storeResource
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->storeFactory = $storeFactory;
        $this->storeResource = $storeResource;
    }

    public function execute()
    {
        $storeId = $this->getRequest()->getParam('store_id');
        $resultPage = $this->resultPageFactory->create();
        $title = $resultPage->getConfig()->getTitle();

        // Checks if a store ID is provided, implying an edit action on an existing store
        if ($storeId) {
            $store = $this->storeFactory->create();
            $this->storeResource->load($store, $storeId);

            // If the store cannot be found by ID, display an error message and redirect.
            if (!$store->getId()) {
                $this->messageManager->addErrorMessage(__('This store no longer exists.'));
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }

            $title->prepend(__($store->getStoreName()));
        } else {
            $title->set(__('New Store'));
        }

        return $resultPage;
    }
}
