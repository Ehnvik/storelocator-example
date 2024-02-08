<?php
namespace Gustav\Thesis\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

// Handles database operations for the relationship between stores and categories
class CategoriesRelation extends AbstractDb
{
    protected function _construct(): void
    {
        $this->_init('store_category_relation', null);
    }

    public function getStoreIdsByCategoryId($categoryId): array
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), 'store_id')
            ->where('category_id = ?', $categoryId);

        return $connection->fetchCol($select);
    }

    public function getCategoryIdsByStoreId($storeId)
    {
        $connection = $this->getConnection();
        $select = $connection->select()
            ->from($this->getMainTable(), 'category_id')
            ->where('store_id = ?', $storeId);

        return $connection->fetchCol($select);
    }


    public function deleteByStoreId($storeId): void
    {
        $connection = $this->getConnection();
        $table = $this->getMainTable();
        $where = ['store_id = ?' => $storeId];

        $connection->delete($table, $where);
    }

    public function saveRelation($storeId, $categoryId): void
    {
        $connection = $this->getConnection();
        $table = $this->getMainTable();

        $select = $connection->select()->from($table)
            ->where('store_id = ?', $storeId)
            ->where('category_id = ?', $categoryId);

        if (!$connection->fetchOne($select)) {
            $data = [
                'store_id' => $storeId,
                'category_id' => $categoryId
            ];
            $connection->insert($table, $data);
        }
    }
}
