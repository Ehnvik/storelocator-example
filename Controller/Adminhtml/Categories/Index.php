<?php

namespace Gustav\Thesis\Controller\Adminhtml\Categories;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    protected PageFactory $resultPageFactory;

    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }
    // The execute method is the main action method that Magento 2 will call when the route to this controller is accessed
    public function execute(): ResultInterface
    {
        return $this->resultPageFactory->create();
    }

    // Method to check if the current admin user has the permission to view this page
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Gustav_Thesis::manage_categories');
    }
}
