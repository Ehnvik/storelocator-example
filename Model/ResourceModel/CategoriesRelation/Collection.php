<?php
namespace Gustav\Thesis\Model\ResourceModel\CategoriesRelation;

use Gustav\Thesis\Model\ResourceModel\CategoriesRelation;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        // The _init method establishes the link between the collection and the specific model (CategoriesRelation)
        $this->_init(
            \Gustav\Thesis\Model\CategoriesRelation::class,
            CategoriesRelation::class
        );
    }
}
