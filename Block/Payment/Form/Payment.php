<?php

namespace Kirill\Coins\Block\Payment\Form;

class Payment extends \Magento\Payment\Block\Form
{
    /**
     * PDQ Payment template
     * This is used for both frontend and backend
     * I created two files named payment.phtml in the path view\adminhtml\templates\form and view\frontend\templates\form because it has different content.
     *
     * @var string
     */
    protected $_template = 'Kirill_Coins::form/payment.phtml';
}
