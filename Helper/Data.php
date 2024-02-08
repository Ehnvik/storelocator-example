<?php

namespace Gustav\Thesis\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{

    // Retrieves the Google Maps API key from the store configuration
    public function getApiKey()
    {
        return $this->scopeConfig->getValue('gustav_thesis/general/google_maps_api_key', ScopeInterface::SCOPE_STORE);
    }
}
