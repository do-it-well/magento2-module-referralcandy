<?php
namespace DIW\ReferralCandy\Model\Config\Source;

class ConnectionType implements \Magento\Framework\Option\ArrayInterface
{
 public function toOptionArray()
 {
  return [
    ['value' => 'email', 'label' => __('Email')],
    ['value' => 'javascript', 'label' => __('JavaScript')]
  ];
 }
}
