<?php
namespace Itmaker\Banner\Classes;


use Omnipay\Common\Exception\InvalidCreditCardException;
use Omnipay\Common\Exception\InvalidRequestException;

class CreditCard implements GatewayInterface
{
    use GatewayTrait;

    public function purchase()
    {
        try {
            $card = new \Omnipay\Common\CreditCard($this->order->payment_data_array);
            $card->validate();

            $paymentData = $this->order->payment_data_array;
            $paymentData['cc'] = $card->getNumberMasked();
            $this->order->payment_data = $paymentData;
            $this->order->save();

            return true;

        } catch (InvalidCreditCardException $e) {
           \Flash::error($e->getMessage());
        } catch (InvalidRequestException $e) {
           \Flash::error($e->getMessage());
        }

        return false;


        /*return $this->gateway->authorize(
            [
                'card'          => $card,
                'returnUrl'     => $this->getReturnUrl(),
                'cancelUrl'     => $this->getCancelUrl(),
                'amount'        => $this->order->total_price_value,
                'currency'      => $this->order->payment_method->gateway_currency,
                'transactionId' => $this->getTransactionId()
            ]
        )->send();*/
    }
}