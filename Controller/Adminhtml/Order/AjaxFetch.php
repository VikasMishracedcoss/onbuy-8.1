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
 * @package   Ced_Onbuy
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE(http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Onbuy\Controller\Adminhtml\Order;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\Controller\Result\JsonFactory;
use Ced\Onbuy\Helper\Onbuy;
use Ced\Onbuy\Helper\Data;
use Ced\Onbuy\Helper\Order;
use Ced\Onbuy\Helper\Logger;

/**
 * Class AjaxFetch
 * @package Ced\Onbuy\Controller\Adminhtml\Product
 */
class AjaxFetch extends Action
{
    /**
     * @var PageFactory
     */
    public $resultPageFactory;
    /**
     * @var JsonFactory
     */
    public $resultJsonFactory;

    public $trademeHelper;
    /**
     * @var Data
     */
    public $dataHelper;

    /**
     * @var Order
     */
    public $orderHelper;
    /**
     * @var Logger
     */
    public $logger;

    /**
     * @var \Ced\Onbuy\Helper\MultiAccount
     */
    protected $multiAccountHelper;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * Startupload constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param JsonFactory $resultJsonFactory
     * @param Onbuy $trademeHelper
     * @param Data $dataHelper
     * @param Logger $logger
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        JsonFactory $resultJsonFactory,
        Onbuy $trademeHelper,
        Data $dataHelper,
        Order $orderHelper,
        Logger $logger,
        \Magento\Framework\Registry $coreRegistry,
        \Ced\Onbuy\Helper\MultiAccount $multiAccountHelper
    ) {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->trademeHelper = $trademeHelper;
        $this->dataHelper = $dataHelper;
        $this->orderHelper = $orderHelper;
        $this->logger = $logger;
        $this->_coreRegistry = $coreRegistry;
        $this->multiAccountHelper = $multiAccountHelper;
    }

    /**
     * @return $this
     */
    public function execute()
    {
        $resultJson = $this->resultJsonFactory->create();
        $message = [];
        $message['error'] = "";
        $message['success'] = "";
        $key = $this->getRequest()->getParam('index');
        $page = $this->getRequest()->getParam('page', 1);
        $totalChunk = $this->_session->getOrderAccountChunks();
        $index = $key + 1;
        
        if (count($totalChunk) <= $index) {
            $this->_session->unsOrderAccountChunks();
        }
        try {
            $accountIds = $totalChunk[$key];
            $accountsName = $this->multiAccountHelper->getAllAccounts(true)
                ->addFieldToFilter('id', array('in' => $accountIds))
                ->getColumnValues('account_code');
            $accountIdsString = implode(', ', $accountsName);
            $resultData = $this->orderHelper->fetchOrders($accountIds);
            if (isset($resultData['error'])) {
                $message['error'] = "Batch $index for $accountIdsString accounts : " . $resultData['error'];
            } else {
                $message['success'] = "Batch $index for $accountIdsString accounts fetched Successfully.";
            }
        } catch (\Exception $e) {
            $message['error'] = $e->getMessage();
            $this->logger->addError($message['error'], ['path' => __METHOD__]);
        }
        return $resultJson->setData($message);
    }
}
