<?php
namespace Gustav\Thesis\Controller\Adminhtml\Categories;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

class NewCategory extends Action
{
    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $resultPage = $this->resultPageFactory->create(); // Creates a new result page instance
        $resultPage->getConfig()->getTitle()->prepend(__('New Category')); // Sets the page title to "New Category"

        return $resultPage;
    }
}
