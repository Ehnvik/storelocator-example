<?php
namespace Gustav\Thesis\Controller\Adminhtml\Stores;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Gustav\Thesis\Model\StoresFactory;
use Gustav\Thesis\Model\ResourceModel\Stores as StoreResource;
use Magento\Framework\App\Request\DataPersistor;
 use Gustav\Thesis\Model\ResourceModel\CategoriesRelation as CategoryRelationResource;
 use Gustav\Thesis\Model\CategoriesRelationFactory;

 // Handles the save action for store data in the admin panel
class Save extends Action
{
    protected PageFactory $resultPageFactory;
    protected StoresFactory $storesFactory;
    protected StoreResource $storeResource;
    protected DataPersistor $dataPersistor;
     protected CategoriesRelationFactory $categoriesRelationFactory;
     protected CategoryRelationResource $categoryRelationResource;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        StoresFactory $storesFactory,
        StoreResource $storeResource,
        DataPersistor $dataPersistor,
        CategoriesRelationFactory $categoriesRelationFactory,
         CategoryRelationResource $categoryRelationResource
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->storesFactory = $storesFactory;
        $this->storeResource = $storeResource;
        $this->dataPersistor = $dataPersistor;
         $this->categoriesRelationFactory = $categoriesRelationFactory;
         $this->categoryRelationResource = $categoryRelationResource;
    }

    /**
     * Executes the save action for both creating new stores and updating existing ones
     * Validates and sanitizes input data, saves store data, manages category relations,
     * and redirects to the appropriate page based on the result
     */

    public function execute()
    {
        $redirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        $storeId = $data['store_id'] ?? null;

        if (!$data) {
            $this->messageManager->addErrorMessage(__('No data to save.'));
            return $redirect->setPath('*/*/index');
        }

        $data['store_name'] = htmlspecialchars(strip_tags($data['store_name']));
        $data['address'] = htmlspecialchars(strip_tags($data['address']));
        $data['city'] = htmlspecialchars(strip_tags($data['city']));

        $sanitizedPostcode = filter_var($data['postcode'], FILTER_SANITIZE_NUMBER_INT);
        $data['postcode'] = preg_replace('/[^0-9]/', '', $sanitizedPostcode);

        $data['country'] = htmlspecialchars(strip_tags($data['country']));
        $data['latitude'] = filter_var($data['latitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        $data['longitude'] = filter_var($data['longitude'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        if (!empty($data['phone'])) {
            $data['phone'] = htmlspecialchars(preg_replace('/[^+\d]/', '', $data['phone']));
        }

        if (!empty($data['hours'])) {
            $data['hours'] = htmlspecialchars($data['hours']);
        }

        $validationErrors = $this->validateData($data);

        if (!empty($validationErrors)) {
            foreach ($validationErrors as $error) {
                $this->messageManager->addErrorMessage($error);
            }

            $this->dataPersistor->set('storelocator_stores_form', $data);

            if ($storeId) {
                return $redirect->setPath('*/*/edit', ['store_id' => $storeId]);
            } else {
                return $redirect->setPath('*/*/newstore');
            }
        }

        try {
            $store = $this->storesFactory->create();
            $store->setData($data);
            $this->storeResource->save($store);

            $selectedCategories = (array) $this->getRequest()->getParam('category_ids', []);

            $this->saveStoreCategoryRelations($store->getId(), $selectedCategories);

            $this->messageManager->addSuccessMessage(__('The store has been successfully saved.'));
            $this->dataPersistor->clear('storelocator_stores_form');
            return $redirect->setPath('*/*/edit', ['store_id' => $store->getId()]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error occurred while saving the store: %1', $e->getMessage()));
            $this->dataPersistor->set('storelocator_stores_form', $data);

            if ($storeId) {
                return $redirect->setPath('*/*/edit', ['store_id' => $storeId]);
            } else {
                return $redirect->setPath('*/*/newstore');
            }
        }
    }

    private function saveStoreCategoryRelations($storeId, array $categoryIds): void
    {
        $this->categoryRelationResource->deleteByStoreId($storeId);

        foreach ($categoryIds as $categoryId) {
            $relation = $this->categoriesRelationFactory->create();
            $relation->setData([
                'store_id' => $storeId,
                'category_id' => $categoryId
            ]);
            $this->categoryRelationResource->saveRelation($storeId, $categoryId);
        }
    }


    private function validateData($data): array
    {
        $errors = [];

        if (empty($data['store_name'])) {
            $errors[] = __('Store name is required.');
        }

        if (empty($data['address'])) {
            $errors[] = __('Address is required.');
        }

         if (!preg_match("/^[a-zA-Z0-9, ]*$/", $data['city'])) {
             $errors[] = __('City contains invalid characters.');
         }

        if (!empty($data['phone']) && !preg_match('/^\+?\d+(-\d+)*$/', $data['phone'])) {
            $errors[] = __('Phone number format is invalid.');
        }

        if (!empty($data['postcode']) && !preg_match('/^\d{5}(-\d{4})?$/', $data['postcode'])) {
            $errors[] = __('Postcode format is invalid.');
        }

        if ((!is_numeric($data['latitude'])) || $data['latitude'] < -90 || $data['latitude'] > 90) {
            $errors[] = __('Latitude must be a number between -90 and 90.');
        }

        if ((!is_numeric($data['longitude'])) || $data['longitude'] < -180 || $data['longitude'] > 180) {
            $errors[] = __('Longitude must be a number between -180 and 180.');
        }

        return $errors;
    }
}


