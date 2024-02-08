<?php

namespace Gustav\Thesis\Block\Adminhtml\Categories;

use Magento\Backend\Block\Widget\Context;
use \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

class BackButton implements ButtonProviderInterface
{
    protected Context $context;

    public function __construct(Context $context) {
        $this->context = $context;
    }

    public function getButtonData(): array
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }


    /**
     * Get URL for back (cancel) button
     *
     * @param string $routePath
     * @param array $routeParams
     * @return string
     */
    public function getUrl(string $routePath = '', array $routeParams = []): string
    {
        return $this->context->getUrlBuilder()->getUrl($routePath, $routeParams);
    }

    /**
     * Generate url for back button
     *
     * @return string
     */


    private function getBackUrl(): string
    {
        return $this->getUrl('*/*/index');
    }
}
