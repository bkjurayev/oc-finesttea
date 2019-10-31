<?php
/**
 * Banner Theme
 * Created by alvaro.
 * User: alvaro
 * Date: 27/02/18
 * Time: 06:14 AM
 */

namespace Itmaker\Banner\Components;


use Cms\Classes\ComponentBase;
use Cms\Classes\ComponentManager;
use Cms\Classes\Page;
use Lovata\Buddies\Facades\AuthHelper;
use Lovata\OrdersShopaholic\Models\Order;
use Lovata\OrdersShopaholic\Models\Status;
use Lovata\Shopaholic\Models\Settings;
use Itmaker\Banner\Classes\PaymentGateway;
use RainLab\Location\Models\Country;
use RainLab\Location\Models\State;
use System\Classes\PluginManager;

class ToolBox extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'itmaker.banner::lang.components.toolbox.name',
            'description' => 'itmaker.banner::lang.components.toolbox.description'
        ];
    }

    public function onRun()
    {
        $this->page['shopCurrency'] = Settings::getValue('currency');
        $pageId = $this->page->page->getId();
        if (in_array($pageId, ['shop-category-list', 'shop-brands', 'shop-search'])) {
            $this->onControlBar();
        }

        if ($pageId == 'account' && AuthHelper::check()) {
            /** @var \Lovata\Buddies\Components\UserPage $userPageComp */
            $userPageComp = $this->page->components['UserPage'];
            $userPageComp->setProperty('slug', AuthHelper::getUser()->id);

            $response = $this->onLoadPartial();

            if ($response instanceof \Illuminate\Http\RedirectResponse) {
                return $response;
            }
        }
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function hasComponent($name): bool
    {
        $manager = ComponentManager::instance();
        return $manager->hasComponent($name);
    }

    /**
     * @param $namespace
     *
     * @return bool
     */
    public function hasPlugin($namespace): bool
    {
        $manager = PluginManager::instance();
        return $manager->hasPlugin($namespace);
    }

    public function loadComponent($className, $alias = null, $options = [])
    {
        if ($this->hasComponent($className)) {
            if (!$alias) {
                $parts = explode('\\', $className);
                $alias = array_pop($parts);
            }
            $this->addComponent($className, $alias, $options);
        }
    }

    public function onControlBar()
    {
        /** @var \Lovata\Shopaholic\Components\CategoryPage $categoryPageComp */
        /** @var \Lovata\Shopaholic\Components\ProductList $productListComp */
        /** @var \Lovata\Toolbox\Components\Pagination $paginationComp */
        /** @var \Lovata\Shopaholic\Components\BrandPage $brandComp */

        $productListComp = $this->page->components['ProductList'];
        $paginationComp = $this->page->components['Pagination'];


        $view = post('view', \Cookie::get('shopProductView'));
        if ($view != \Cookie::get('shopProductListView')) {
            \Cookie::queue('shopProductListView', $view);
        }

        $sort = $productListComp->getSorting();
        if (\Cookie::get('shopProductListSort')) {
            $sort = \Cookie::get('shopProductListSort');
        }
        if ($sortnew = post('orderby')) {
            $sort = $sortnew;
        }

        if ($sort != \Cookie::get('shopProductListSort')) {
            \Cookie::queue('shopProductListSort', $sort);
        }

        $productList = $productListComp->make()->sort($sort)->active();
        $pageId = $this->page->page->getId();
        switch ($pageId) {
            case 'shop-category-list':
                $categoryPageComp = $this->page->components['CategoryPage'];
                /** @var \Lovata\Shopaholic\Classes\Item\CategoryItem $category */
                $category = $categoryPageComp->get();
                $productList = $productList->category($category->id);

                // Check for FilterShopaholic plugin
                if ($this->hasPlugin('Lovata.FilterShopaholic') && post('filters')) {
                    $obPropertyList = $category->product_filter_property;

                    // Filter by Brand
                    if ($filterBrand = post('filters.brand')) {
                        $productList->filterByBrandList($filterBrand);
                    }

                    // Filter by properties
                    if ($filterProperties = post('filters.property')) {
                        $productList->filterByProperty($filterProperties, $obPropertyList);
                    }

                    // Filter by price
                    if ($filterPrice = post('filters.price')) {
                        if (is_array($filterPrice) && array_has($filterPrice, ['from', 'to'])) {
                            $productList->filterByPrice(array_get($filterPrice, 'from'), array_get($filterPrice, 'to'));
                        }
                    }
                }

                if ($category->children->isNotEmpty()) {
                    foreach ($category->children as $obChildCategory) {
                        if ($obChildCategory->product_count) {
                            $childProductList = $productListComp->make()->sort($sort)->active()->category(
                                $obChildCategory->id
                            );
                            $productList = $productList->merge($childProductList->getIDList());
                        }
                    }
                }
                break;

            case 'shop-brands':
                $brandComp = $this->page->components['BrandPage'];
                $brand = $brandComp->get();
                $productList = $productList->brand($brand->id);
                break;

            case 'shop-search':
                if ($this->hasPlugin('Lovata\SearchShopaholic')) {
                    list($query, $categoryId) = array_pad(explode(':', $this->param('q')), 2, null);
                    $this->page['sSearch'] = $query;
                    $productList = $productList->search($query);
                    if ($categoryId) {
                        $this->page['productCat'] = $categoryId;
                        $productList = $productList->category($categoryId);
                    }
                }
                break;

            default:
                return null;
                break;
        }


        $iPage = $this->param('page', 1);
        $paginationList = $paginationComp->get($iPage, $productList->count());
        $arProductList = $productList->page($iPage, $paginationComp->getCountPerPage());

        $this->page['shopProductListView'] = $view;
        $this->page['shopProductListSort'] = $sort;
        $this->page['obProductList'] = $productList;
        $this->page['offerMinPrice'] = $productList->getOfferMinPrice();
        $this->page['offerMaxPrice'] = $productList->getOfferMaxPrice();
        $this->page['iPage'] = $iPage;
        $this->page['arPaginationList'] = $paginationList;
        $this->page['arProductList'] = $arProductList;

//        return ['#'.$view => $this->renderPartial('components/product-'.$view, $partialData)];
    }

    public function onProductSearch()
    {
        $query = post('s');
        if ($category = post('product_cat')) {
            $query .= ':'.$category;
        }

        $url = Page::url('shop-search', ['q' => $query]);

        return redirect($url);

//        if(!$this->hasPlugin('Lovata\SearchShopaholic')) {
//            \Flash::error();
//        }
    }

    public function onLoadPartial()
    {
        /** @var \Lovata\Buddies\Classes\Item\UserItem $obUser */
        /** @var \Lovata\OrdersShopaholic\Components\OrderPage $orderPage */

        $this->page['obUser'] = $obUser = $this->page->layout->components['UserData']->get();
        $orderPage = $this->page->components['OrderPage'];
//        if (post('section') == 'orders') {
//            $this->page['orders'] = $this->getUserOrders($obUser->id);
//        }

        $section = request()->segment(2);
        $param1 = request()->segment(3);
        $param2 = request()->segment(4);

        switch ($section) {
            case 'orders':
                $this->page['orders'] = $this->getUserOrders(AuthHelper::getUser()->id);
                if (strlen($param2) == 32) {
                    $order = $this->getUserOrders(AuthHelper::getUser()->id, $param2);
                    $returnUrl = Page::url(
                        'account',
                        [
                            'section' => 'orders',
                            'param1'  => 'order',
                            'param2'  => $order->secret_key
                        ]
                    );
                } else {
                    $returnUrl = Page::url('account', ['section' => 'orders']);
                }


                switch ($param1) {
                    case 'order':
                        $this->page['show_cc_form'] = !in_array(
                            class_basename($orderPage->getPaymentGateway()),
                            ['ExpressGateway']
                        );
                        if (isset($order)) {
                            $this->page['order'] = $order;
                        }
                        break;

                    case 'completed':
                        if (isset($order)) {
                            $orderPaymentMethod = $orderPage->obPaymentMethod;
                            $status = null;
                            if (!empty($orderPaymentMethod) && !empty($orderPaymentMethod->gateway_id)) {
                                if ($gateway = PaymentGateway::create(
                                    $orderPaymentMethod->gateway_id,
                                    $order,
                                    $orderPage->getPaymentGateway()
                                )) {
                                    $options = $order->payment_data_array;
                                    $options['amount'] = $order->total_price_value;
                                    $options['currency'] = $order->payment_method->gateway_currency;
                                    $options['transactionId'] = $order->secret_key;

                                    $response = $gateway->gateway
                                        ->completePurchase($options)
                                        ->send();

                                    if ($response->isSuccessful()) {
                                        $status = Status::where('code', Status::STATUS_COMPETE)->firstOrFail();
                                        $orderProperties = $order->property;
                                        $orderProperties['transactionid'] = $response->getTransactionReference();
                                        $order->property = $orderProperties;
                                        $order->payment_response = $response->getData();
                                    }

                                    \Flash::success($response->getMessage());

                                }
                            } else {
                                $status = Status::where('code', Status::STATUS_IN_PROGRESS)->firstOrFail();
                                \Flash::success('Your order payment is in progress');
                            }

                            if ($status) {
                                $order->status()->add($status);
                            }
                            $order->save();

                            return redirect($returnUrl);

                        }
                        break;
                    case 'canceled':
                        if (isset($order)) {
                            $status = Status::where('code', Status::STATUS_CANCELED)->firstOrFail();
                            $order->status()->add($status);
                            $order->save();

                            return redirect($returnUrl);
                        }
                        break;
                }
                break;
        }

        return true;
    }

    public function onCategoryFilter()
    {
        $products = [];
        $productsCount = post('products_take', 12);
        $productCollection = \Lovata\Shopaholic\Classes\Collection\ProductCollection::make()->active();

        if ($categoryId = post('cat')) {
            $category = \Lovata\Shopaholic\Models\Category::find($categoryId);
            $products = $productCollection->category($categoryId);
            if ($category) {
                if ($category->children->count()) {
                    foreach ($category->children as $childCategory) {
                        if ($childCategory->product->count()) {
                            $products = $products->merge($childCategory->product()->lists('id'));
                        }
                    }
                }
            }
            $products = $products->take($productsCount);
        } else {
            $products = $productCollection->take($productsCount);
        }

        return [
            '.categories-filter-products .products' => $this->renderPartial(
                'components/product-featured.htm',
                ['products' => $products]
            )
        ];
    }

    public function onOmnipay()
    {
        if (!$this->hasPlugin('Lovata.OmnipayShopaholic') || !$this->hasComponent('OrderPage')) {
            return null;
        }

        /** @var \Lovata\OrdersShopaholic\Components\OrderPage $orderPage */
        $orderPage = $this->page->components['OrderPage'];
        $orderPaymentMethod = $orderPage->obPaymentMethod;
        $order = $orderPage->obElement;

        $mainUrl = Page::url('account', ['section' => 'orders']);

        if (empty($order)) {
            return redirect($mainUrl)->with('message', 'Order is undefined');
        }

        $this->updatePaymentData($order);

        if (!empty($orderPaymentMethod) && !empty($orderPaymentMethod->gateway_id)) {
//            return redirect($cancelUrl)->with('message', 'Payment method is undefined for current order');
            if ($gateway = PaymentGateway::create(
                $orderPaymentMethod->gateway_id,
                $order,
                $orderPage->getPaymentGateway()
            )) {
                $response = $gateway->purchase();
                if ($response->isRedirect()) {
                    return redirect($response->getRedirectUrl());
                }

                \Flash::error($response->getMessage());

                return redirect()->refresh();
            }
        }


        // Use CreditCart method

        $gateway = PaymentGateway::create('CreditCard', $order);
        if ($gateway->purchase()) {
            return redirect($gateway->getReturnUrl());
        }

        return redirect($gateway->getCancelUrl());

    }

    protected function updatePaymentData($order)
    {
        /** @var \Lovata\Buddies\Models\User $user */
        $user = $order->user;
        $billingProperties = $user->property;
        $shippingProperties = $order->property;

        $returnUrl = Page::url(
            'account',
            [
                'section' => 'orders',
                'param1'  => 'completed',
                'param2'  => $order->secret_key
            ]
        );

        $cancelUrl = Page::url(
            'account',
            [
                'section' => 'orders',
                'param1'  => 'order',
                'param2'  => $order->secret_key
            ]
        );

        // Billing Country
        $bCountry = array_get($billingProperties, 'billing_country');
        $billCountry = ($bCountry) ? Country::where('code', $bCountry)->first() : null;

        // Billing State
        $bState = array_get($billingProperties, 'billing_state');
        $billState = ($bState) ? State::where('code', $bState)->first() : null;

        // Shipping Country
        $sCountry = array_get($shippingProperties, 'shipping_country', $bCountry);
        $shipCountry = ($sCountry) ? Country::where('code', $sCountry)->first() : null;

        // Shipping State
        $sState = array_get($shippingProperties, 'shipping_state', $bState);
        $shipState = ($sState) ? State::where('code', $sState)->first() : null;

        // Expiration Date
        list($month, $year) = explode('/', post('cc-exp', '/'));


        $orderPaymentData = [
            'cancelUrl'        => $cancelUrl,
            'returnUrl'        => $returnUrl,
            'firstName'        => post('cc-firstname', $user->name),
            'lastName'         => post('cc-lastname', $user->last_name),
            'number'           => post('cc-number'),
            'expiryMonth'      => $month,
            'expiryYear'       => $year,
            'cvv'              => post('cc-cvv'),
            'billingAddress1'  => array_get($billingProperties, 'billing_address'),
            'billingAddress2'  => array_get($billingProperties, 'billing_address_2'),
            'billingCity'      => array_get($billingProperties, 'billing_city'),
            'billingPostcode'  => array_get($billingProperties, 'billing_postcode'),
            'billingState'     => ($billState->exists) ? $billState->name : null,
            'billingCountry'   => ($billCountry->exists) ? $billCountry->name : null,
            'billingPhone'     => array_get($billingProperties, 'billing_phone', $user->phone),
            'shippingAddress1' => array_get($shippingProperties, 'shipping_address'),
            'shippingAddress2' => array_get($shippingProperties, 'shipping_address_2'),
            'shippingCity'     => array_get($shippingProperties, 'shipping_city'),
            'shippingPostcode' => array_get($shippingProperties, 'shipping_postcode'),
            'shippingState'    => ($shipState->exists) ? $shipState->name : null,
            'shippingCountry'  => ($shipCountry->exists) ? $shipCountry->name : null,
            'shippingPhone'    => array_get($shippingProperties, 'phone', $user->phone),
            'email'            => array_get($shippingProperties, 'email', $user->email)
        ];

        $order->payment_data = $orderPaymentData;
        $order->save();
    }

    /**
     * @param integer     $user_id
     * @param null|string $key
     *
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Lovata\OrdersShopaholic\Models\Order|\Lovata\OrdersShopaholic\Models\Order[]
     */
    public function getUserOrders($user_id, $key = null)
    {
        $order = Order::where('user_id', $user_id)->orderBy('created_at', 'desc');

        if ($key) {
            $order->getBySecretKey($key);
        }

        return ($key) ? $order->first() : $order->get();
    }

}