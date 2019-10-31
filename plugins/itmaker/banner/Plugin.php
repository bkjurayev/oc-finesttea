<?php namespace Itmaker\Banner;

use Backend;
use Form;
use RainLab\Location\Models\Country;
use RainLab\Location\Models\State;
use System\Classes\PluginBase;

/**
 * Banner Plugin Information File
 */
class Plugin extends PluginBase
{
    protected static $nameCountryList;
    protected static $nameStateList;


    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'itmaker.banner::lang.plugin.name',
            'description' => 'itmaker.banner::lang.plugin.description',
            'author'      => 'Itmaker',
            'icon'        => 'icon-picture-o'
        ];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Itmaker\Banner\Components\Banners' => 'BannerBanners',
            'Itmaker\Banner\Components\ToolBox' => 'BannerToolBox',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'itmaker.banner.banners' => [
                'tab'   => 'itmaker.banner::lang.plugin.name',
                'label' => 'itmaker.banner::lang.permission.access'
            ],
            'itmaker.banner.sizes'   => [
                'tab'   => 'itmaker.banner::lang.plugin.name',
                'label' => 'itmaker.banner::lang.permission.access_sizes'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'banner' => [
                'label'       => 'itmaker.banner::lang.plugin.name',
                'url'         => Backend::url('itmaker/banner/banners'),
                'iconSvg'     => 'plugins/itmaker/banner/assets/images/icon.svg',
                'permissions' => ['itmaker.banner.*'],
                'order'       => 500,

                'sideMenu' => [
                    'banners'  => [
                        'label'       => 'itmaker.banner::lang.banners.menu_label',
                        'url'         => Backend::url('itmaker/banner/banners'),
                        'icon'        => 'icon-picture-o',
                        'permissions' => ['itmaker.banner.banners'],
                    ],
                    'sizes'    => [
                        'label'       => 'itmaker.banner::lang.sizes.menu_label',
                        'url'         => Backend::url('itmaker/banner/sizes'),
                        'icon'        => 'icon-crop',
                        'permissions' => ['itmaker.banner.sizes'],
                    ],
                    'settings' => [
                        'label' => 'itmaker.banner::lang.theme.settings',
                        'url'   => Backend::url('cms/themeoptions/update/itmaker-banner-store'),
                        'icon'  => 'icon-sliders'
                    ]
                ]
            ],
        ];
    }

    public function registerMarkupTags()
    {
        return [
            'functions' => [
                'collect'                => function ($values = null) {
                    return collect($values);
                },
                'uid'                    => function ($prefix = '', $more_entropy = false) {
                    return uniqid($prefix, $more_entropy);
                },
                'form_select_tm_country' => [$this, 'selectCountry'],
                'form_select_tm_state'   => [$this, 'selectState']
            ],
            'filters' => [
                'country' => [$this, 'getCountry'],
                'state' => [$this, 'getState'],
                'get_class' => function ($object) {
                    return get_class($object);
                },
                'class_basename' => function ($object) {
                    return class_basename($object);
                }
            ]
        ];
    }

    public function selectCountry($name, $selectedValue = null, $options = [])
    {
        return Form::select($name, self::getCountryNameList(), $selectedValue, $options);
    }

    public function selectState($name, $countryCode = null, $selectedValue = null, $options = [])
    {
        return Form::select($name, self::getStateNameList($countryCode), $selectedValue, $options);
    }

    private static function getCountryNameList()
    {
        if (self::$nameCountryList) {
            return self::$nameCountryList;
        }

        return self::$nameCountryList = Country::isEnabled()->orderBy('is_pinned', 'desc')->lists('name', 'code');
    }

    private static function getStateNameList($countryCode)
    {
        if (isset(self::$nameStateList[$countryCode])) {
            return self::$nameStateList[$countryCode];
        }

        return self::$nameStateList[$countryCode] = State::whereHas(
            'country',
            function ($query) use ($countryCode) {
                return $query->where('code', $countryCode);
            }
        )->lists('name', 'code');
    }

    public function getCountry($code, $attr = 'name')
    {
        $country = Country::where('code', $code)->first();
        return ($country) ? $country->{$attr} : null;
    }

    public function getState($code, $attr = 'name')
    {
        $state = State::where('code', $code)->first();
        return ($state) ? $state->{$attr} : null;
    }
}
