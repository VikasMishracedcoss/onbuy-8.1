<?php

/**
 * CedCommerce
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the End User License Agreement (EULA)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://cedcommerce.com/license-agreement.txt
 *
 * @category    Ced
 * @package     Ced_Onbuy
 * @author      CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright   Copyright CedCommerce (http://cedcommerce.com/)
 * @license     http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Onbuy\Ui\Component\Listing\Columns\Order;

use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\UrlInterface;

/**
 * Class Actions
 * @package Ced\Onbuy\Ui\Component\Listing\Columns\Order
 */
class Actions extends Column
{
    const URL_PATH_EDIT = 'sales/order/view';
    const URL_PATH_VIEW = 'onbuy/order/view';
    const URL_PATH_DELETE = 'onbuy/order/delete';

    /** @var UrlInterface */
    public $urlBuilder;

    protected $salesOrder;


    /**
     * Actions constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        \Magento\Sales\Model\Order $salesOrder,
        array $components = [],
        array $data = []
    )
    {
        $this->urlBuilder = $urlBuilder;
        $this->salesOrder = $salesOrder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {

        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {

                $this->salesOrder->loadByIncrementId($item['magento_increment_id']);

                $name = $this->getData('name');
                $response = isset($item['order_data']) ? $item['order_data'] :  json_encode(array('Response' => 'Order Data Not Found'));
                if (isset($item['id'])) {
                    $this->salesOrder->loadByIncrementId($item['magento_increment_id']);

                    $item[$name]['view'] = [
                        'label' => __('View Order'),
                        'class' => 'cedcommerce actions view',
                        'popup' => [
                            'title' => __("OnBuy Order #{$item['magento_increment_id']}"),
                            'message' => $response,
                            'type' => 'json',
                            'render' => 'html',
                        ],
                    ];
                    $item[$name]['edit'] = [
                        'href' => $this->urlBuilder->getUrl(
                            self::URL_PATH_EDIT,
                            [
                                'order_id' => $this->salesOrder->getId()
                            ]
                        ),
                        'label' => __('Edit'),
                        'class' => 'cedcommerce actions edit'
                    ];
                    $item[$name]['delete'] = [
                        'href' => $this->urlBuilder->getUrl(self::URL_PATH_DELETE, ['id' => $item['id']]),
                        'label' => __('Delete'),
                        'class' => 'cedcommerce actions delete'
                    ];
                }
            }
        }
        return $dataSource;
    }
}
