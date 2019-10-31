<?php namespace Itmaker\Banner\Models;

use Model;
use October\Rain\Database\Traits\Validation;

/**
 * Size Model
 *
 * @property integer             id
 * @property \System\Models\File image
 * @property string              name
 *
 */
class Size extends Model
{
    use Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'itmaker_banner_sizes';

    public $rules = [
        'name' => 'required|regex:/(^[0-9]+)(?:[xX])([0-9]+$)/m',
    ];

    /**
     * @var array Fillable fields
     */
    protected $fillable = ['name'];

    /**
     * @var array Relations
     */
    public $attachOne = ['image' => 'System\Models\File'];
}
