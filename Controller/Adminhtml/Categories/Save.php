<?php
namespace Gustav\Thesis\Controller\Adminhtml\Categories;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Gustav\Thesis\Model\CategoriesFactory;
use Gustav\Thesis\Model\ResourceModel\Categories as CategoryResource;
use Magento\Framework\App\Request\DataPersistor;

class Save extends Action
{
    protected PageFactory $resultPageFactory;
    protected CategoriesFactory $categoriesFactory;
    protected CategoryResource $categoryResource;
    protected DataPersistor $dataPersistor;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CategoriesFactory $categoriesFactory,
        CategoryResource $categoryResource,
        DataPersistor $dataPersistor
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->categoriesFactory = $categoriesFactory;
        $this->categoryResource = $categoryResource;
        $this->dataPersistor = $dataPersistor;
    }

    public function execute()
    {
        $redirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        // Extracts category ID from the data, if available
        $categoryId = $data['category_id'] ?? null;

        if (!$data) {
            // Adds an error message and redirects to the categories index page if no data is found.
            $this->messageManager->addErrorMessage(__('No data to save.'));
            return $redirect->setPath('*/*/index');
        }

        $data['category_name'] = htmlspecialchars(strip_tags($data['category_name']));

        $validationErrors = $this->validateData($data);

        if (!empty($validationErrors)) {
            // Adds each validation error as an error message
            foreach ($validationErrors as $error) {
                $this->messageManager->addErrorMessage($error);
            }

            $this->dataPersistor->set('storelocator_categories_form', $data);

            if ($categoryId) {
                return $redirect->setPath('*/*/edit', ['category_id' => $categoryId]);
            } else {
                return $redirect->setPath('*/*/newcategory');
            }
        }

        try {
            // Attempts to save the category data
            $category = $this->categoriesFactory->create();
            $category->setData($data);
            $this->categoryResource->save($category);

            $this->messageManager->addSuccessMessage(__('The category has been successfully saved.'));
            $this->dataPersistor->clear('storelocator_categories_form');
            return $redirect->setPath('*/*/edit', ['category_id' => $category->getId()]);
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error occurred while saving the category: %1', $e->getMessage()));
            $this->dataPersistor->set('storelocator_categories_form', $data);

            if ($categoryId) {
                return $redirect->setPath('*/*/edit', ['category_id' => $categoryId]);
            } else {
                return $redirect->setPath('*/*/newcategory');
            }
        }
    }

    // Validates the posted data and returns an array of error messages
    private function validateData($data): array
    {
        $errors = [];

        if (empty($data['category_name'])) {
            $errors[] = __('Category name is required.');
        }

        return $errors;
    }
}


