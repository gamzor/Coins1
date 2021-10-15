<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Kirill\Coins\Block\Adminhtml\Edit\Tab\View;

use Magento\Backend\Block\Widget\Form\Generic;
use Kirill\Coins\Model\ResourceModel\Coins\CollectionFactory;

class Total extends Generic
{
    /**
     * @var \Magento\Store\Model\System\Store
     */
    protected $_systemStore;

    /**
     * @var \Kirill\Coins\Model\CoinsFactory
     */
    protected $coinsFactory;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $_customerFactory;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory ,
     * @param \Kirill\Coins\Model\CoinsFactory $coinsFactory
     * @param \Magento\Store\Model\System\Store $systemStore
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context                   $context,
        \Magento\Framework\Registry                               $registry,
        \Magento\Framework\Data\FormFactory                       $formFactory,
        \Kirill\Coins\Model\CoinsFactory                          $coinsFactory,
        \Kirill\Coins\Model\ResourceModel\Coins\CollectionFactory $collectionFactory,
        array                                                     $data = []
    )
    {
        $this->_coinsFactory = $coinsFactory;
        $this->collectionFactory = $collectionFactory;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /** Calculate all coins
     *
     * @return int
     */
    public function getTotal()
    {
        $total = 0;
        $collection = $this->collectionFactory->create();
        foreach ($collection as $item) {
            $sum = $item->getCoins();
            $total += $sum;
        }
        return $total;
    }
}
