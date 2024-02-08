<?php
namespace Gustav\Thesis\Block\Adminhtml\Categories;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;

class DeleteButton implements ButtonProviderInterface
{
    protected $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function getButtonData(): array
    {
        $data = [];
        $categoryId = $this->getCategoryId();
        if ($categoryId) {
            $data = [
                'label' => __('Delete Category'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __('Are you sure you want to delete this category?')
                    . '\', \'' . $this->getDeleteUrl($categoryId) . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    public function getCategoryId()
    {
        return $this->context->getRequest()->getParam('category_id');
    }

    public function getDeleteUrl($categoryId): string
    {
        return $this->context->getUrlBuilder()->getUrl('*/*/delete', ['category_id' => $categoryId]);
    }
}
