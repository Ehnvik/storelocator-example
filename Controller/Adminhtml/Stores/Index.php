<?php
namespace Gustav\Thesis\Controller\Adminhtml\Stores;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;

class Index extends Action
{
    /**
     * @var PageFactory
     */
    protected PageFactory $resultPageFactory;

    /**
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
    }

    /**
     *
     * @return ResultInterface
     */

    // Executes the action: Generates and returns the page displaying the stores list
    public function execute(): ResultInterface
    {
        return $this->resultPageFactory->create();
    }

    // Checks permission for the action based on ACL configuration
    protected function _isAllowed(): bool
    {
        return $this->_authorization->isAllowed('Gustav_Thesis::manage_stores');
    }
}
