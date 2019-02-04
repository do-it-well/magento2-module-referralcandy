<?php
namespace DIW\ReferralCandy\Plugin\Email;

class InvoiceIdentity
{
    protected $_referralCandyHelper;

    public function __construct(
        \DIW\ReferralCandy\Helper\Data $referralCandyHelper
    ) {
        $this->_referralCandyHelper = $referralCandyHelper;
    }

    public function afterGetEmailCopyTo(
        \Magento\Sales\Model\Order\Email\Container\InvoiceIdentity $subject,
        $result
    ) {
        $store_id = $subject->getStore()->getStoreId();
        if (!$this->_referralCandyHelper->getEmailEnabled($store_id)) return $result;

        $address = $this->_referralCandyHelper->getInvoiceBccAddress($store_id);
        if (!strlen($address)) return $result;

        return array_merge(
            $result ? (array)$result : array(),
            explode(',', $address)
        );
    }
}
