<?php
namespace Gustav\Thesis\Model\ResourceModel\Categories;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Gustav\Thesis\Model\Categories as Model;
use Gustav\Thesis\Model\ResourceModel\Categories as ResourceModel;

class Collection extends AbstractCollection
{
    // Initialize collection model and resource model
    protected function _construct(): void
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
