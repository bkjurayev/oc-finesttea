<?php namespace Itmaker\Banner\Updates;

use October\Rain\Database\Updates\Seeder;
use Itmaker\Banner\Models\Size;

class SeedSizes extends Seeder
{

    public function run()
    {
        $sizes = [
            '258x703',
            '273x273',
            '287x166',
            '287x210',
            '300x204',
            '414x256',
            '420x260',
            '500x465',
            '565x174',
            '566x273',
            '566x566',
            '700x510',
            '724x960',
            '750x563',
            '860x260',
            '812x430',
            '870x484',
            '900x566',
            '1016x532',
            '1126x440',
            '1300x260',
            '1450x236',
            '1920x563',
            '1920x650',
            '1920x900',
        ];
        $path = plugins_path('itmaker/banner/assets/images/banners');

        foreach ($sizes as $size) {
            $model = new Size;
            $model->name = $size;
            $model->image = $path.'/'.$size.'.png';
            $model->save();
        }

    }

}
