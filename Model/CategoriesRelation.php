<?php
namespace Gustav\Thesis\Model;

use Magento\Framework\Model\AbstractModel;

// Model for Category-Store relationships, representing the association between stores and their categories
class CategoriesRelation extends AbstractModel
{
    protected function _construct(): void
    {
        $this->_init(ResourceModel\CategoriesRelation::class);
    }
}
