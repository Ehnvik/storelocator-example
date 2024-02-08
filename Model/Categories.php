<?php

namespace Gustav\Thesis\Model;

use Magento\Framework\Model\AbstractModel;
use Gustav\Thesis\Model\ResourceModel\Categories as ResourceModel;

// This class represents the data structure for a Category entity
class Categories extends AbstractModel
{
    /**
     * Initialize resource model
     */
    protected function _construct(): void
    {
        $this->_init(ResourceModel::class);
    }
}
