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
 * @category  Ced
 * @package   Ced_Cdiscount
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE(http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Onbuy\Block\Adminhtml\Config\Field;

use Magento\Framework\Api\SearchCriteriaBuilder;

/**
 * HTML select element block with customer groups options
 */
class OnbuyCarriers extends \Magento\Framework\View\Element\Html\Select
{
    /**
     * @var
     */
    private $_shippingMethod;

    private  $searchCriteriaBuilder;

    private  $_shipMethod;

    private $methods;

    private $order;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Context $context,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        \Magento\Shipping\Model\Config\Source\Allmethods $methods,
        \Ced\Onbuy\Model\Config\OnbuyTracks $order,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->methods = $methods;
        $this->order = $order;
    }

    /**
     * @return array|null
     */
    protected function _getShippingMethod()
    {
        if ($this->_shippingMethod === null) {
            $this->_shippingMethod = $this->order->getOptionArray();
        }
        return $this->_shippingMethod;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            foreach ($this->_getShippingMethod() as  $method) {
                $this->addOption($method['value'], addslashes($method['label']));
            }
        }
        return parent::_toHtml();
    }
}
