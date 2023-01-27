<?php

namespace RADL\Store\Models\WC;
use RADL\Store\Models\ModelWC;

class ProductsAttributes extends ModelWC
{
    public $route_base = 'products/attributes';

    public $type = 'productsAttributes';
    
    public $specific_params = [
        // "per_page" => 100,
    ];
}
