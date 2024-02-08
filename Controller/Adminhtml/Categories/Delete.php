<?php
namespace Gustav\Thesis\Controller\Adminhtml\Categories;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Gustav\Thesis\Model\CategoriesFactory;
use Gustav\Thesis\Model\ResourceModel\Categories as CategoryResource;

class Delete extends Action
{
    protected CategoriesFactory $categoriesFactory;
    protected CategoryResource $categoryResource;

    public function __construct(
        Context $context,
        CategoriesFactory $categoriesFactory,
        CategoryResource $categoryResource
    ) {
        parent::__construct($context);
        $this->categoriesFactory = $categoriesFactory;
        $this->categoryResource = $categoryResource;
    }

    // The execute method is where the action to delete a category is performed
    public function execute()
    {
        $redirect = $this->resultRedirectFactory->create();
        $categoryId = $this->getRequest()->getParam('category_id');

        if (!$categoryId) {
            $this->messageManager->addErrorMessage(__('No category found to delete.'));
            return $redirect->setPath('*/*/index');
        }

        try {
            // Attempt to load the category using the provided ID. This is to ensure that the category exists before attempting deletion
            $category = $this->categoriesFactory->create();
            $this->categoryResource->load($category, $categoryId);

            if ($category->getId()) {
                $this->categoryResource->delete($category);
                $this->messageManager->addSuccessMessage(__('Category has been successfully deleted.'));
            } else {
                $this->messageManager->addErrorMessage(__('Category could not be found.'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('Error occurred while deleting the category: %1', $e->getMessage()));
        }

        return $redirect->setPath('*/*/index');
    }
}
