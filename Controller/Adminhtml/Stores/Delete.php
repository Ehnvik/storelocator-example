<?php
namespace Gustav\Thesis\Controller\Adminhtml\Stores;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Gustav\Thesis\Model\StoresFactory;
use Gustav\Thesis\Model\ResourceModel\Stores as StoreResource;

class Delete extends Action
{
    protected StoresFactory $storeFactory;
    protected StoreResource $storeResource;

    public function __construct(
        Context $context,
        StoresFactory $storeFactory,
        StoreResource $storeResource
    ) {
        parent::__construct($context);
        $this->storeFactory = $storeFactory;
        $this->storeResource = $storeResource;
    }

    public function execute()
    {
        $redirect = $this->resultRedirectFactory->create();
        // Retrieve the store ID from the request parameters
        $storeId = $this->getRequest()->getParam('store_id');

        if (!$storeId) {
            $this->messageManager->addErrorMessage(__('No store found to delete.'));
            return $redirect->setPath('*/*/index');
        }

        try {
            // Create a store model and load the store by ID
            $store = $this->storeFactory->create();
            $this->storeResource->load($store, $storeId);

            if ($store->getId()) {
                $this->storeResource->delete($store);
                $this->messageManager->addSuccessMessage(__('Store has been successfully deleted.'));
            } else {
                $this->messageManager->addErrorMessage(__('Store could not be found.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error occurred while deleting the store: %1', $e->getMessage()));
        }
        // Redirect back to the index page after operation completion.
        return $redirect->setPath('*/*/index');
    }
}
