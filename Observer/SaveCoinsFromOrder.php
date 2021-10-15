<?php

namespace Kirill\Coins\Observer;

use Kirill\Coins\Helper\Data;
use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Model\Order;
use Kirill\Coins\Model\CoinsRepository;
use \Magento\Framework\Event\Observer;
class SaveCoinsFromOrder implements ObserverInterface
{
    /**
     * Order Model
     *
     * @var Order $order
     */
    protected $order;
    /**
     * @var CoinsRepository
     */
    private CoinsRepository $coinsRepository;
    /**
     * @var Data
     */
    private Data $helper;

    public function __construct(
        Order                        $order,
        CoinsRepository               $coinsRepository,
        CustomerRepository $customerRepository,
        Data                         $helper
    )
    {
        $this->order = $order;
        $this->coinsRepository = $coinsRepository;
        $this->customerRepository = $customerRepository;
        $this->helper = $helper;
    }

    /** Save coins after order
     * @param Observer $observer
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute(Observer $observer)
    {
        $quoteMethod = $observer->getEvent()->getOrder()->getPayment()->getMethod();
        $order = $observer->getEvent()->getOrder()->getSubtotal();
        $customer = $observer->getEvent()->getQuote()->getCustomer();
        $oldcustomerCoins = $customer->getCustomAttributes()['coins']->getValue();
        $customerId = $observer->getEvent()->getOrder()->getCustomerId();
        $orderId = $observer->getEvent()->getOrder()->getId();
        $percent = 100 / ($this->helper->getPercent());
        $coins = (int)($order / $percent);
        if ($customerId && $quoteMethod != 'coins_payment_option') {
            $savedata = $this->coinsRepository->getNewInstance();
            $savedata->addData(['coins' => $coins, 'order_id' => $orderId, 'customer_id' => $customerId, 'comment' => 'Earn Coins from Order']);
            $this->coinsRepository->save($savedata);
            $newcustomerCoins = $customer->setCustomAttribute('coins',$oldcustomerCoins+$coins);
            $this->customerRepository->save($newcustomerCoins);

        }
    }
}
