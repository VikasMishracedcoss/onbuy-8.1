<?php
/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement(EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_Onbuy
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CEDCOMMERCE(http://cedcommerce.com/)
 * @license     http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Onbuy\Model\Carrier;
use Magento\Quote\Model\Quote\Address\RateRequest;

class Shipbyonbuy extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface
{
    const REGISTRY_INDEX_SHIPPING_METHOD = 'onbuy_shipping_method';
    const REGISTRY_INDEX_SHIPPING_TOTAL = 'onbuy_shipping_total';
    /**
     * @var string
     */
    public $_code = 'shipbyonbuy';
    /**
     * @var \Psr\Log\LoggerInterface
     */
    public $_logger;
    /**
     * @var bool
     */
    public $_isFixed = true;
    
    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    public $_rateResultFactory;
    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    public $_rateMethodFactory;

    /**
     * Shipbyonbuy constructor.
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\State $appState,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
        \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
        array $data = []
    ) {

       
        $this->registry = $registry;
        $this->appState = $appState;
        $this->_rateResultFactory = $rateResultFactory;
        $this->_rateMethodFactory = $rateMethodFactory;
        $this->_logger = $logger;
        parent::__construct($scopeConfig, $rateErrorFactory,$logger, $data);
    }

    /**
     * @param RateRequest $request
     * @return bool|\Magento\Shipping\Model\Rate\Result
     */
    public function collectRates(RateRequest $request)
    {
        if (!$this->getConfigFlag('active')) {
            return false;
        }

        if ($this->appState->getAreaCode() != 'adminhtml' && $this->appState->getAreaCode() != 'crontab') {
            return false;
        }
        $price = $this->registry->registry(self::REGISTRY_INDEX_SHIPPING_TOTAL);
        //$price = $this->getConfigData('price');
        if (!$price)
            $price = 0;

        $handling = $this->getConfigFlag('handling');

        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->_rateResultFactory->create();

        /** @var \Magento\Quote\Model\Quote\Address\RateResult\Method $method */
        $method = $this->_rateMethodFactory->create();
        $method->setCarrier($this->_code);
        $method->setMethod($this->_code);

        $method->setCarrierTitle($this->getConfigData('title'));

        $shippingService = $this->registry->registry(self::REGISTRY_INDEX_SHIPPING_METHOD);
        $method->setMethodTitle(($shippingService) ? $shippingService : $this->getConfigData('name'));
        //$method->setMethodTitle($this->getConfigData('name'));

        $method->setPrice($price);
        $method->setCost(0);

        $result->append($method);
        return $result;
    }

    /**
     * @return array
     */
    public function getAllowedMethods() {
        return [$this->_code => $this->getConfigData('title')];
    }

    /**
     * @return string
     */
    public function getCode() {
        return $this->_code;
    }
}
