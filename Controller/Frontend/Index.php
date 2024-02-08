<?php
namespace Gustav\Thesis\Controller\Frontend;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Index implements HttpGetActionInterface
{
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    private Context $context;

    /**
     * Constructor
     *
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->context = $context;
    }

    /**
     * @return Page
     */

    // Executes action based on incoming request and returns a result page
    public function execute()
    {
        return $this->resultPageFactory->create();
    }
}
