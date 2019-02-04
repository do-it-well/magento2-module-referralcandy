<?php
namespace DIW\ReferralCandy\Block;

use Magento\Sales\Model\Order;

class OrderConfirmation extends \Magento\Framework\View\Element\Template
{
    protected $_checkoutSession;
    protected $_referralCandyHelper;
    protected $_coreDate;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Checkout\Model\Session $checkoutSession,
        \DIW\ReferralCandy\Helper\Data $referralCandyHelper,
        \Magento\Framework\Stdlib\DateTime $coreDate,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_checkoutSession = $checkoutSession;
        $this->_referralCandyHelper = $referralCandyHelper;
        $this->_coreDate = $coreDate;
    }

    protected function _beforeToHtml()
    {
        $this->prepareBlockData();
        return parent::_beforeToHtml();
    }

    protected function buildReferralCandyData(Order $order)
    {
        // The grand total is the full amount, taking into account all Taxes,
        // Discounts, and Shipping Cost.

        // The shipping subtotal is our attempt to calculate the amount of the
        // Grand Total which would relate to shipping costs.
        // The formula we use is:
        // [The full original shipping amount, including all taxes, before
        //  discounts have been applied]
        // Minus:
        // [ The amount that the shipping price was discounted ]

        // The shipping discount tax compensation amount is not taken into
        // consideration. That is: it assumes that shipping costs are not taxed,
        // or that the shipping discount is inclusive of any tax.

        // The item tax is our attempt to calculate the amount of tax which is
        // related only to items, not to shipping.

        // The commissionable amount is the grand total, excluding all shipping
        // related costs and item related taxes.

        // There may be some tax setting configurations where this is not the
        // case. In testing we were not able to find any such configurations,
        // and so those configurations, if they exist, are not handled.
        $shipping_subtotal = (
            $order->getShippingInclTax() -
            $order->getShippingDiscountAmount()
        );
        $item_tax = (
            $order->getTaxAmount() -
            $order->getShippingTaxAmount()
        );
        $commisionable_subtotal = (
            $order->getGrandTotal() -
            $item_tax -
            $shipping_subtotal
        );

        $unsigned = array(
            'order_id'  => (string)$order->getIncrementId(),
            'order_timestamp' => (string)$this->_coreDate->strToTime($order->getCreatedAt()),
            'order_subtotal' => number_format($commisionable_subtotal, 2, '.', ''),
            'order_currency_code' => (string)$order->getOrderCurrencyCode(),
            'customer_email' => (string)$order->getCustomerEmail(),
            'customer_firstname' => (string)(
                $order->getCustomerFirstname() ?
                    $order->getCustomerFirstname() :
                    $order->getBillingAddress()->getFirstname()
            ),
            'customer_lastname' => (string)(
                $order->getCustomerFirstname() ? // intentional check of firstname
                    $order->getCustomerLastname() :
                    $order->getBillingAddress()->getLastname()
            ),
            'referral_candy_app_id' => $this->_referralCandyHelper->getAppId()
        );

        return $this->signReferralCandyData($unsigned);
    }

    protected function signReferralCandyData($unsigned)
    {
        return array_merge(
            $unsigned,
            array('referral_candy_signature' =>
                $this->_referralCandyHelper->getSigned(array(
                    $unsigned['customer_email'],
                    $unsigned['customer_firstname'],
                    $unsigned['order_subtotal'],
                    $unsigned['order_timestamp']
                ))
            )
        );
    }

    protected function prepareBlockData()
    {
        if( !$this->_referralCandyHelper->getJavaScriptEnabled() ){
            $this->addData(['referral_candy_enabled' => false]);
            return;
        }

        $this->addData(['referral_candy_enabled' => true]);
        $order = $this->_checkoutSession->getLastRealOrder();
        if ($order) {
            $this->addData( $this->buildReferralCandyData($order) );
        }
    }
}
