$(function() {
    
    // cart page remove cart position
    $(document).on("click", ".order_item button.del", function(event, contex) {
        var data = {
            'cart': [$(this).data('id')],
            'type': 'position',
        };
        
        $.request('onRemove', {
            'loading': $.oc.stripeLoadIndicator,
            'data': data,
            'update': {
                'order/cart-items': '#order-cartItems',
                'order/cart-content': '#cart-content'
            }
        });
    });
    
    
    // Product page, add to cart
    $(document).on('click', '.order_block .order_line a.btn', function(event, context) {
        var data = $(this).closest('.order_block').find('.card-item-value').data('data');
        
        //Send ajax request and update cart items
        $.request('Cart::onAdd', {
            'data': data,
            'loading': $.oc.stripeLoadIndicator,
            'update': {'order/cart-content': '#cart-content'}
        });
    });
    
    
    // Product item, add to cart
    $(document).on('click', '.add_to_cart button', function(event, context) {
        var data = $(this).closest('.add_to_cart').find('.card-item-value.active').data('data');
        
        //Send ajax request and update cart items
        $.request('Cart::onAdd', {
            'data': data,
            'loading': $.oc.stripeLoadIndicator,
            'update': {'order/cart-content': '#cart-content'}
        });
    });
    
    
    
    // filter & reset
    $(".js-range-slider").ionRangeSlider();
    $("p.reset").on("click", function() {
        $(this).closest('form').trigger("reset");
        $(".js-range-slider").data("ionRangeSlider").reset();
        
        var url = location.origin + location.pathname;
        history.pushState(null, null, url);
          
        $(this).request('ProductList::onAjaxRequest', {
            data: {},
            update: {
                'products/ajax-list': '#products-ajaxList',
                'products/infinity-loader': '#infinity-loader',
            },
        });
    });
    
    
    $("#ajaxFilterForm").on('submit', function(e, context) {
        e.preventDefault();
        
        var url = location.origin + location.pathname;
        history.pushState(null, null, url + '?' + $(this).serialize());
        
        $(this).request('ProductList::onAjaxRequest', {
            loading: $.oc.stripeLoadIndicator,
            update: {
                'products/ajax-list': '#products-ajaxList',
                'products/infinity-loader': '#infinity-loader',
            },
        });
    });
    
    $("#ajaxFilterForm input[type=checkbox]").on('change', function(e, context) {
        e.preventDefault();
        
        var url = location.origin + location.pathname;
        history.pushState(null, null, url + '?' + $(this).serialize());
        
        $(this).request('ProductList::onAjaxRequest', {
            loading: $.oc.stripeLoadIndicator,
            update: {
                'products/ajax-list': '#products-ajaxList',
                'products/infinity-loader': '#infinity-loader',
            },
        });
    });
    
    $(document).on('click', '.content .sort a', function(e, context) {
        e.preventDefault();
        var sort = $(this).data('sort');
        
        $(this).request('ProductList::onAjaxRequest', {
            loading: $.oc.stripeLoadIndicator,
            data: {sort: sort},
            update: {
                'products/ajax-list': '#products-ajaxList',
                'products/infinity-loader': '#infinity-loader',
            },
        });
    })
    
     
});