<?php
namespace Gustav\Thesis\Block\Adminhtml\Stores;

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
        $storeId = $this->getStoreId();
        if ($storeId) {
            $data = [
                'label' => __('Delete Store'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' . __('Are you sure you want to delete this store?')
                    . '\', \'' . $this->getDeleteUrl($storeId) . '\')',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    public function getStoreId()
    {
        return $this->context->getRequest()->getParam('store_id');
    }

    public function getDeleteUrl($storeId)
    {
        return $this->context->getUrlBuilder()->getUrl('*/*/delete', ['store_id' => $storeId]);
    }
}
