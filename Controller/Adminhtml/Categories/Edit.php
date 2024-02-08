<?php
namespace Gustav\Thesis\Controller\Adminhtml\Categories;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Gustav\Thesis\Model\CategoriesFactory;
use Gustav\Thesis\Model\ResourceModel\Categories as CategoryResource;

class Edit extends Action
{
    protected $resultPageFactory;
    protected CategoriesFactory $categoriesFactory;
    protected CategoryResource $categoryResource;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        CategoriesFactory $categoriesFactory,
        CategoryResource $categoryResource
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->categoriesFactory = $categoriesFactory;
        $this->categoryResource = $categoryResource;
    }

    public function execute()
    {
        $categoryId = $this->getRequest()->getParam('category_id');
        $resultPage = $this->resultPageFactory->create();
        $title = $resultPage->getConfig()->getTitle();

        if ($categoryId) {
            $category = $this->categoriesFactory->create();
            $this->categoryResource->load($category, $categoryId);

            // If the category with the provided ID does not exist, show an error message and redirect
            if (!$category->getId()) {
                $this->messageManager->addErrorMessage(__('This category no longer exists.'));
                return $this->resultRedirectFactory->create()->setPath('*/*/');
            }

            $title->prepend(__($category->getCategoryName()));
        } else {
            $title->set(__('New Category'));
        }

        // Return the result page object to render the edit or new category UI
        return $resultPage;
    }
}
