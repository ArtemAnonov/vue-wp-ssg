<?php

namespace RADL\Store\Models\WC;
use RADL\Store\Models\ModelWC;

class ProductsTermsBrands extends ModelWC
{
    public $route_base = 'products/attributes/1/terms';

    public $type = 'productsTermsBrands';
    
    public $specific_params = [
        // "per_page" => 100,
    ];
}
