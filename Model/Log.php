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

namespace Ced\Onbuy\Model;


class Log extends \Magento\Framework\Model\AbstractModel
{
    const TABLE_NAME = 'onbuy_logs';
    const ID_FIELD_NAME = 'id';

    /**
     * @return  void
     */
    public function _construct()
    {
        $this->_init(\Ced\Onbuy\Model\ResourceModel\Log::class);
    }
}
