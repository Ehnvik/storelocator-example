<?php
namespace Gustav\Thesis\Model\ResourceModel\Stores;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Gustav\Thesis\Model\Stores as Model;
use Gustav\Thesis\Model\ResourceModel\Stores as ResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct(): void
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
