<?php
/**
 * thephonecellar.com
 * Created by alvaro.
 * User: alvaro
 * Date: 09/07/18
 * Time: 05:22 AM
 */

namespace Itmaker\Banner\Classes;


class PaymentGateway
{
    /**
     * @param string                                $gatewayId
     * @param \Lovata\OrdersShopaholic\Models\Order $order
     * @param \Omnipay\Common\GatewayInterface      $gateway
     *
     * @return \Itmaker\Banner\Classes\GatewayInterface | boolean
     */
    public static function create($gatewayId, $order, $gateway= null)
    {
        if (\File::exists(plugins_path("itmaker/banner/classes/{$gatewayId}.php"))) {
            $classname = 'Itmaker\\Banner\\Classes\\'.$gatewayId;
            return new $classname($order, $gateway);
        }

        return false;
    }

}