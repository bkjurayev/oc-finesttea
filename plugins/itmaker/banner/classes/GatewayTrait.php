<?php
/**
 * thephonecellar.com
 * Created by alvaro.
 * User: alvaro
 * Date: 26/06/18
 * Time: 05:50 AM
 */

namespace Itmaker\Banner\Classes;


use Cms\Classes\Page;
use Lovata\OrdersShopaholic\Models\Order;

trait GatewayTrait
{
    /** @var \Lovata\OrdersShopaholic\Models\Order */
    protected $order;
    /** @var string */
    protected $cancelUrl;
    /** @var string */
    protected $returnUrl;
    /** @var \Omnipay\Common\GatewayInterface */
    public $gateway;

    public function __construct(Order $order, $gateway)
    {
        $this->gateway = $gateway;
        $this->order = $order;
    }

    public function setCancelUrl($url = null)
    {
        if(is_string($url)) {
            $this->cancelUrl = $url;
        } else {
            $this->cancelUrl = Page::url(
                'account',
                [
                    'section' => 'orders',
                    'param1'  => 'order',
                    'param2'  => $this->order->secret_key
                ]
            );
        }
    }

    /**
     * @return string
     */
    public function getCancelUrl()
    {
        if(!$this->cancelUrl && $this->order) {
            $this->setCancelUrl();
        }

        return $this->cancelUrl;
    }

    public function setReturnUrl($url = null)
    {
        if(is_string($url)) {
            $this->returnUrl = $url;
        } else {
            $this->returnUrl = Page::url(
                'account',
                [
                    'section' => 'orders',
                    'param1'  => 'completed',
                    'param2'  => $this->order->secret_key
                ]
            );
        }
    }

    /**
     * @return string
     */
    public function getReturnUrl()
    {
        if(!$this->returnUrl && $this->order) {
            $this->setReturnUrl();
        }

        return $this->returnUrl;
    }

    /**
     * @return null|string
     */
    public function getTransactionId()
    {
        return ($this->order) ? $this->order->secret_key : null;
    }

}