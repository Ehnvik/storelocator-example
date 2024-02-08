<?php
namespace Gustav\Thesis\Model\Categories;

use Magento\Framework\Data\OptionSourceInterface;
use Gustav\Thesis\Model\ResourceModel\Categories\CollectionFactory as CategoriesCollectionFactory;

class OptionsProvider implements OptionSourceInterface
{
    /**
     * @var CategoriesCollectionFactory
     */
    private $categoriesCollectionFactory;

    /**
     * Constructor
     *
     * @param CategoriesCollectionFactory $categoriesCollectionFactory
     */
    public function __construct(CategoriesCollectionFactory $categoriesCollectionFactory)
    {
        $this->categoriesCollectionFactory = $categoriesCollectionFactory;
    }

    /**
     * Get category options
     *
     * @return array
     */

    // Generate an array of options for category dropdowns
    public function toOptionArray(): array
    {
        $collection = $this->categoriesCollectionFactory->create();
        $options = [];

        foreach ($collection as $category) {
            $options[] = [
                'label' => $category->getCategoryName(),
                'value' => $category->getId(),
            ];
        }

        return $options;
    }
}
