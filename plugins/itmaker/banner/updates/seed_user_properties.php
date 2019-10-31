<?php namespace Itmaker\Banner\Updates;

use Lovata\Buddies\Models\Property;
use October\Rain\Database\Updates\Seeder;

class SeedUserProperties extends Seeder
{

    public function run()
    {
        $properties = [
            [
                'active'   => true,
                'name'     => 'Name',
                'code'     => 'billing_name',
                'type'     => 'input',
                'settings' => ['tab' => 'Billing'],
            ],
            [
                'active'   => true,
                'name'     => 'Last Name',
                'code'     => 'billing_last_name',
                'type'     => 'input',
                'settings' => ['tab' => 'Billing'],
            ],
            [
                'active'   => true,
                'name'     => 'Company',
                'code'     => 'billing_company',
                'type'     => 'input',
                'settings' => ['tab' => 'Billing'],
            ],
            [
                'active'   => true,
                'name'     => 'Country',
                'code'     => 'billing_country',
                'type'     => 'input',
                'settings' => ['tab' => 'Billing'],
            ],
            [
                'active'   => true,
                'name'     => 'Address',
                'code'     => 'billing_address',
                'type'     => 'input',
                'settings' => ['tab' => 'Billing'],
            ],
            [
                'active'   => true,
                'name'     => 'Address 2',
                'code'     => 'billing_address_2',
                'type'     => 'input',
                'settings' => ['tab' => 'Billing'],
            ],
            [
                'active'   => true,
                'name'     => 'City',
                'code'     => 'billing_city',
                'type'     => 'input',
                'settings' => ['tab' => 'Billing'],
            ],
            [
                'active'   => true,
                'name'     => 'State',
                'code'     => 'billing_state',
                'type'     => 'input',
                'settings' => ['tab' => 'Billing'],
            ],
            [
                'active'   => true,
                'name'     => 'Zip Code',
                'code'     => 'billing_postcode',
                'type'     => 'input',
                'settings' => ['tab' => 'Billing'],
            ],
            [
                'active'   => true,
                'name'     => 'Phone',
                'code'     => 'billing_phone',
                'type'     => 'input',
                'settings' => ['tab' => 'Billing'],
            ],
            [
                'active'   => true,
                'name'     => 'Email',
                'code'     => 'billing_email',
                'type'     => 'input',
                'settings' => ['tab' => 'Billing'],
            ],

            // Shipping Properties
            [
                'active'   => true,
                'name'     => 'Name',
                'code'     => 'shipping_name',
                'type'     => 'input',
                'settings' => ['tab' => 'Shipping'],
            ],
            [
                'active'   => true,
                'name'     => 'Last Name',
                'code'     => 'shipping_last_name',
                'type'     => 'input',
                'settings' => ['tab' => 'Shipping'],
            ],
            [
                'active'   => true,
                'name'     => 'Company',
                'code'     => 'shipping_company',
                'type'     => 'input',
                'settings' => ['tab' => 'Shipping'],
            ],
            [
                'active'   => true,
                'name'     => 'Country',
                'code'     => 'shipping_country',
                'type'     => 'input',
                'settings' => ['tab' => 'Shipping'],
            ],
            [
                'active'   => true,
                'name'     => 'Address',
                'code'     => 'shipping_address',
                'type'     => 'input',
                'settings' => ['tab' => 'Shipping'],
            ],
            [
                'active'   => true,
                'name'     => 'Address 2',
                'code'     => 'shipping_address_2',
                'type'     => 'input',
                'settings' => ['tab' => 'Shipping'],
            ],
            [
                'active'   => true,
                'name'     => 'City',
                'code'     => 'shipping_city',
                'type'     => 'input',
                'settings' => ['tab' => 'Shipping'],
            ],
            [
                'active'   => true,
                'name'     => 'State',
                'code'     => 'shipping_state',
                'type'     => 'input',
                'settings' => ['tab' => 'Shipping'],
            ],
            [
                'active'   => true,
                'name'     => 'Zip Code',
                'code'     => 'shipping_postcode',
                'type'     => 'input',
                'settings' => ['tab' => 'Shipping'],
            ],
            [
                'active'   => true,
                'name'     => 'Phone',
                'code'     => 'shipping_phone',
                'type'     => 'input',
                'settings' => ['tab' => 'Shipping'],
            ],
            [
                'active'   => true,
                'name'     => 'Email',
                'code'     => 'shipping_email',
                'type'     => 'input',
                'settings' => ['tab' => 'Shipping'],
            ],
        ];

        foreach ($properties as $property) {
            if(Property::where('code', $property['code'])->doesntExist()) {
                $model = new Property($property);
                $model->save();
            }
        }

    }

}
