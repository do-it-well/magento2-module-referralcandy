<?php
namespace DIW\ReferralCandy\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class Data extends AbstractHelper
{
	public function getConfigValue($key, $storeId = null)
	{
		return $this->scopeConfig->getValue(
			'referralcandy/tracking/'.$key,
			ScopeInterface::SCOPE_STORE,
			$storeId
		);
	}

	public function getEnabled($storeId = null)
	{
		return $this->getConfigValue('enable', $storeId);
	}

	public function getConnectionType($storeId = null)
	{
		return $this->getConfigValue('connection_type', $storeId);
	}

	public function getEmailEnabled($storeId = null)
	{
		return (
			$this->getEnabled($storeId) &&
			$this->getConnectionType($storeId) === 'email' &&
			strlen($this->getInvoiceBccAddress($storeId))
		);
	}

	public function getJavaScriptEnabled($storeId = null)
	{
		return (
			$this->getEnabled($storeId) &&
			$this->getConnectionType($storeId) === 'javascript' &&
			strlen($this->getAppId($storeId)) &&
			strlen($this->getSecretKey($storeId))
		);
	}

	public function getAppId($storeId = null)
	{
		return (string)($this->getConfigValue('app_id', $storeId));
	}

	public function getSecretKey($storeId = null)
	{
		return (string)($this->getConfigValue('secret_key', $storeId));
	}

	public function getInvoiceBccAddress($storeId = null)
	{
		if (
			$this->scopeConfig->getValue(
				'sales_email/invoice/copy_method',
				ScopeInterface::SCOPE_STORE,
				$storeId
			) !== 'bcc'
		){
			return '';
		}

		return (string)($this->getConfigValue('invoice_bcc_address', $storeId));
	}

	public function getSigned($data, $storeId = null)
	{
		return md5(implode(
			',',
			array_merge(
				(array)$data,
				array($this->getSecretKey($storeId))
			)
		));
	}
}
