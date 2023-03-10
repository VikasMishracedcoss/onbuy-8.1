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
 * @package   Ced_Groupon
 * @author    CedCommerce Core Team <connect@cedcommerce.com>
 * @copyright Copyright CEDCOMMERCE(http://cedcommerce.com/)
 * @license   http://cedcommerce.com/license-agreement.txt
 */

namespace Ced\Onbuy\Helper;


class Logger extends \Ced\Integrator\Helper\Logger
{
    public $mutelevel = 100;
    /**
     * DB logger, dependencies can be updated here, such as model.
     * Logger constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Ced\Integrator\Model\LogFactory $log
     * @param string $name
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Ced\Integrator\Model\LogFactory $log,
        $name = 'Onbuy'
    ) {
        parent::__construct($log, $name);
    }

}
