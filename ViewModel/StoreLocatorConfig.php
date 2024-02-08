<?php
namespace Gustav\Thesis\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Gustav\Thesis\Helper\Data as GoogleMapsApiKeyHelper;

class StoreLocatorConfig implements ArgumentInterface
{
    private GoogleMapsApiKeyHelper $googleMapsApiKeyHelper;

    public function __construct(GoogleMapsApiKeyHelper $googleMapsApiKeyHelper)
    {
        $this->googleMapsApiKeyHelper = $googleMapsApiKeyHelper;
    }

    public function getApiKey()
    {
        return $this->googleMapsApiKeyHelper->getApiKey();
    }
}
