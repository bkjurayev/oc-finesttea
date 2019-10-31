<?php
/**
 * thephonecellar.com
 * Created by alvaro.
 * User: alvaro
 * Date: 26/06/18
 * Time: 05:33 AM
 */

namespace Itmaker\Banner\Classes;


class PayPal_Express implements GatewayInterface
{
    use GatewayTrait;

    /**
     * @return \Omnipay\Common\Message\ResponseInterface
     */
    public function purchase()
    {
        $orderPaymentData = [
            'cancelUrl'     => $this->getCancelUrl(),
            'returnUrl'     => $this->getReturnUrl(),
            'amount'        => $this->order->total_price_value,
            'currency'      => $this->order->payment_method->gateway_currency,
            'transactionId' => $this->getTransactionId()
        ];

        return $this->gateway->purchase($orderPaymentData)->send();
    }
}