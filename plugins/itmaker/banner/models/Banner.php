<?php namespace Itmaker\Banner\Models;

use Cms\Classes\Page;
use Lovata\Shopaholic\Models\Product;
use Model;
use October\Rain\Database\Traits\Validation;

/**
 * Banner Model
 *
 * @property integer                                id
 * @property integer                                product_id
 * @property string                                 title
 * @property string                                 strip_title
 * @property string                                 escape_title
 * @property string                                 subtitle
 * @property string                                 strip_subtitle
 * @property string                                 escape_subtitle
 * @property string                                 pretitle
 * @property string                                 offer
 * @property string                                 button_label
 * @property string                                 bottom_caption
 * @property string                                 page
 * @property string                                 url
 * @property string                                 icon
 * @property string                                 link_type
 * @property string                                 text_pos
 * @property \Itmaker\Banner\Models\Size size
 * @property Product                                product
 * @property \System\Models\File                    image
 * @property \System\Models\File                    background
 */
class Banner extends Model
{
    use Validation;

    public $implement = ['@RainLab.Translate.Behaviors.TranslatableModel'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'itmaker_banner_banners';

    public $translatable = [
        'title',
        'subtitle',
        'pretitle',
        'offer',
        'button_label',
        'bottom_caption',
    ];

    public $rules = [
        'title' => 'required',
        'size_id'  => 'required'
    ];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['title', 'subtitle', 'pretitle', 'page', 'url', 'offer', 'button_label', 'bottom_caption'];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'product' => [Product::class],
        'size'    => [Size::class]
    ];
    public $attachOne = [
        'image'      => 'System\Models\File',
        'background' => 'System\Models\File',
    ];

    public function getPageOptions()
    {
        return Page::getNameList();
    }

    public function getIconOptions()
    {
        return [
            ""                               => ['None'],
            "tm-accesories"                  => ['Accesories', 'tm tm-accesories'],
            "tm-smartphone"                  => ['Smartphone', 'tm tm-smartphone'],
            "tm-shopping-bag"                => ['Shopping Bag', 'tm tm-shopping-bag'],
            "tm-grid"                        => ['Grid', 'tm tm-grid'],
            "tm-best-brands"                 => ['Best Brands', 'tm tm-best-brands'],
            "tm-arrow-up"                    => ['Arrow Up', 'tm tm-arrow-up'],
            "tm-arrow-right"                 => ['Arrow Right', 'tm tm-arrow-right'],
            "tm-arrow-left-products-header"  => ['Arrow Left Products Header', 'tm tm-arrow-left-products-header'],
            "tm-arrow-left"                  => ['Arrow Left', 'tm tm-arrow-left'],
            "tm-arrow-down"                  => ['Arrow Down', 'tm tm-arrow-down'],
            "tm-digital-camera"              => ['Digital Camera', 'tm tm-digital-camera'],
            "tm-dollar"                      => ['Dollar', 'tm tm-dollar'],
            "tm-favorites"                   => ['Favorites', 'tm tm-favorites'],
            "tm-feedback"                    => ['Feedback', 'tm tm-feedback'],
            "tm-free-delivery"               => ['Free Delivery', 'tm tm-free-delivery'],
            "tm-arrow-right-products-header" => ['Arrow Right Products Header', 'tm tm-arrow-right-products-header'],
            "tm-free-return"                 => ['Free Return', 'tm tm-free-return'],
            "tm-safe-payments"               => ['Safe Payments', 'tm tm-safe-payments'],
            "tm-order-tracking"              => ['Order Tracking', 'tm tm-order-tracking'],
            "tm-newsletter"                  => ['Newsletter', 'tm tm-newsletter'],
            "tm-map-marker"                  => ['Map Marker', 'tm tm-map-marker'],
            "tm-long-arrow-right"            => ['Long Arrow Right', 'tm tm-long-arrow-right'],
            "tm-long-arrow-left"             => ['Long Arrow Left', 'tm tm-long-arrow-left'],
            "tm-login-register"              => ['Login Register', 'tm tm-login-register'],
            "tm-desktop-pc"                  => ['Desktop Pc', 'tm tm-desktop-pc'],
            "tm-grid-small"                  => ['Grid Small', 'tm tm-grid-small'],
            "tm-breadcrumbs-arrow-right"     => ['Breadcrumbs Arrow Right', 'tm tm-breadcrumbs-arrow-right'],
            "tm-breadcrumbs-arrow-left"      => ['Breadcrumbs Arrow Left', 'tm tm-breadcrumbs-arrow-left'],
            "tm-call-us-footer"              => ['Call Us Footer', 'tm tm-call-us-footer'],
            "tm-laptop"                      => ['Laptop', 'tm tm-laptop'],
            "tm-listing"                     => ['Listing', 'tm tm-listing'],
            "tm-close"                       => ['Close', 'tm tm-close'],
            "tm-compare"                     => ['Compare', 'tm tm-compare'],
            "tm-listing-large"               => ['Listing Large', 'tm tm-listing-large'],
            "tm-listing-small"               => ['Listing Small', 'tm tm-listing-small'],
            "tm-departments-thin"            => ['Departments Thin', 'tm tm-departments-thin']
        ];
    }

    /*public function getTitleAttribute() {
        // don't use $this->title because it would call our self
        $title = $this->attributes['title']; // translated attribute
        debug($title);
        return strtoupper($title);
    }*/

    public function getStripTitleAttribute()
    {
        $value = $this->title;
        return (is_string($value)) ? $this->stripP($value) : $value;
    }

    public function getStripSubtitleAttribute()
    {
        $value = $this->subtitle;
        return (!is_null($value)) ? $this->stripP($value) : $value;
    }

    public function getEscapeTitleAttribute()
    {
        return strip_tags($this->title);
    }

    public function getEscapeSubtitleAttribute()
    {
        return strip_tags($this->subtitle);
    }

    public function stripP($value)
    {
        return preg_replace('%<p(.*?)>|</p>%s', '', $value);
    }

    public function getUrlAttribute($value = null)
    {
        switch ($this->link_type) {
            case 'product':
                if ($this->product) {
                    $value = Page::url('shop-product', ['slug' => $this->product->slug]);
                }
                break;

            case 'page':
                if ($this->page) {
                    $value = Page::url($this->page);
                }
                break;

        }

        return $value;
    }

}
