<?php namespace Itmaker\Banner\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Flash;
use Lang;
use Itmaker\Banner\Models\Size;

/**
 * Sizes Back-end Controller
 */
class Sizes extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Itmaker.Banner', 'banner', 'sizes');
    }

    /**
     * Deleted checked sizes.
     */
    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $sizeId) {
                if (!$size = Size::find($sizeId)) continue;
                $size->delete();
            }

            Flash::success(Lang::get('itmaker.banner::lang.sizes.delete_selected_success'));
        }
        else {
            Flash::error(Lang::get('itmaker.banner::lang.sizes.delete_selected_empty'));
        }

        return $this->listRefresh();
    }
}
