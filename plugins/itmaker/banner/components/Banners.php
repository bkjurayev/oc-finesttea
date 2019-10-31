<?php namespace Itmaker\Banner\Components;

use Cms\Classes\ComponentBase;
use Itmaker\Banner\Models\Banner;

class Banners extends ComponentBase
{
    protected static $loadedBanners;

    public function componentDetails()
    {
        return [
            'name'        => 'itmaker.banner::lang.components.banners.name',
            'description' => 'itmaker.banner::lang.components.banners.description'
        ];
    }

    /**
     * @param string       $size
     * @param bool|integer $first
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|object|\Itmaker\Banner\Components\Banners[]|\Itmaker\Banner\Models\Banner
     */
    public function load($size, $first = true)
    {
        if (!self::$loadedBanners) {
            self::$loadedBanners = [];
        }

        $banners = Banner::whereHas(
            'size',
            function ($query) use ($size) {
                $query->where('name', $size);
            }
        )
            ->whereNotIn('id', self::$loadedBanners)
            ->orderBy('created_at', 'desc');

        if (!$banners->count()) {
            return $banners->get();
        }

        if ($first === true) {
            /** @var Banner $response */
            $response = $banners->first();
            self::$loadedBanners[] = $response->id;
        } else {
            /** @var \Illuminate\Database\Eloquent\Collection $response */
            $response = (is_int($first)) ? $banners->take($first)->get() : $banners->get();
            self::$loadedBanners = array_merge(self::$loadedBanners, $response->pluck('id')->all());
        }

        return $response;

    }

}