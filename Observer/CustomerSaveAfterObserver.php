<?php

namespace Kirill\Coins\Observer;

use Magento\Customer\Model\ResourceModel\CustomerRepository;
use Magento\Framework\Event\ObserverInterface;
use Kirill\Coins\Model\CoinsRepository;
use Magento\Framework\Event\Observer;
use Magento\Customer\Model\Session;
class CustomerSaveAfterObserver implements ObserverInterface
{

    /**
     * @var CoinsRepository
     */
    protected $coinsRepository;

    /**
     * Constructor
     *
     * @param \Kirill\Coins\Model\CoinsRepository $coinsRepository
     */
    public function __construct(
        CoinsRepository $coinsRepository,
        CustomerRepository $customerRepository,
        Session $customerSession
) {
        $this->coinsRepository = $coinsRepository;
        $this->customerRepository = $customerRepository;
    }

    /** Save coins and change coins from form
     * @param Observer $observer
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function execute(Observer $observer)
    {

        /* @var $request \Magento\Framework\App\RequestInterface */
        $request = $observer->getRequest();
        $coins = $request->getPost('coins');
        if (!($coins['amount_coins'] == "" && $coins['comment'] == ""))
        {
            $oldcustomerCoins = $observer->getCustomer()->getCustomAttributes()['coins']->getValue();
            $balance = $this->coinsRepository->getNewInstance();
            $balance->addData(['coins' => $coins['amount_coins'], 'comment' => $coins['comment']]);
            if ($oldcustomerCoins >= 0) {
            $newcustomerCoins = $observer->getCustomer()->setCustomAttribute('coins',$oldcustomerCoins+$coins['amount_coins']);
                $this->coinsRepository->save($balance);
                $this->customerRepository->save($newcustomerCoins);
              }
            return;
        }
        return;
    }
}
