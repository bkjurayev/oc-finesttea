<?php
/**
 * thephonecellar.com
 * Created by alvaro.
 * User: alvaro
 * Date: 26/06/18
 * Time: 05:43 AM
 */

namespace Itmaker\Banner\Classes;


use Lovata\OrdersShopaholic\Models\Order;

/**
 * Interface GatewayInterface
 *
 * @package Itmaker\Banner\Classes
 *
 * @property Order                            $order
 * @property string                           cancelUrl
 * @property string                           returnUrl
 * @property \Omnipay\Common\GatewayInterface gateway
 *
 * @method setCancelUrl($url = null)
 * @method string getCancelUrl()
 * @method setReturnUrl($url = null)
 * @method string getReturnUrl()
 */
interface GatewayInterface
{

    /**
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    public function purchase();

}