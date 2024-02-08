<?php

namespace Gustav\Thesis\Controller\Adminhtml\Categories;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Gustav\Thesis\Model\ResourceModel\Categories\CollectionFactory as CategoriesCollectionFactory;
use Gustav\Thesis\Model\ResourceModel\Categories as CategoryResource;

class MassDelete extends Action
{
    /**
     * @var CategoriesCollectionFactory
     */
    protected $collectionFactory;

    /**
     * @var CategoryResource
     */
    protected $categoryResource;

    /**
     * @var Filter
     */
    protected $filter;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param CategoriesCollectionFactory $collectionFactory
     * @param CategoryResource $categoryResource
     * @param Filter $filter
     */
    public function __construct(
        Context                       $context,
        CategoriesCollectionFactory $collectionFactory,
        CategoryResource          $categoryResource,
        Filter                        $filter
    )
    {
        $this->collectionFactory = $collectionFactory;
        $this->categoryResource = $categoryResource;
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
            // Applying the mass action filter to the category collection
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $categoriesDeleted = 0;

            // Looping through the collection and deleting each category.
            foreach ($collection->getItems() as $category) {
                $this->categoryResource->delete($category);
                $categoriesDeleted++;
            }

            if ($categoriesDeleted) {
                $this->messageManager->addSuccessMessage(
                    __('A total of %1 categories have been deleted.', $categoriesDeleted)
                );
            }
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage(__('An error occurred while deleting categories.'));
        }

        // Redirecting back to the categories index page.
        $redirect->setPath('*/*/index');
        return $redirect;
    }
}
