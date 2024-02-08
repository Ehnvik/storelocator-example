<?php

namespace Gustav\Thesis\Controller\Adminhtml\Stores;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Gustav\Thesis\Model\ResourceModel\Stores\CollectionFactory as StoresCollectionFactory;
use Gustav\Thesis\Model\ResourceModel\Stores as StoreResource;


// Handles the 'Mass Delete' action from the admin panel for stores. This allows the user to delete multiple stores
class MassDelete extends Action
{
    /**
     * @var StoresCollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var StoreResource
     */
    protected $storeResource;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param StoresCollectionFactory $collectionFactory
     * @param StoreResource $storeResource
     * @param Filter $filter
     */
    public function __construct(
        Context                       $context,
        StoresCollectionFactory $collectionFactory,
        StoreResource          $storeResource,
        Filter                        $filter
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->storeResource = $storeResource;
        $this->filter = $filter;
        parent::__construct($context);
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $storesDeleted = 0;

            foreach ($collection->getItems() as $store) {
                $this->storeResource->delete($store);
                $storesDeleted++;
            }

            if ($storesDeleted) {
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 store(s) have been deleted.', $storesDeleted)
                );
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while deleting stores.'));
        }

        $redirect->setPath('*/*/index');
        return $redirect;
    }
}
