<?php

namespace RADL\Store\Models\WC;
use RADL\Store\Models\ModelWC;

class ProductsTermsMaterials extends ModelWC
{
    public $route_base = 'products/attributes/3/terms';

    public $type = 'productsTermsMaterials';
    
    public $specific_params = [
        // "per_page" => 100,
    ];
}
