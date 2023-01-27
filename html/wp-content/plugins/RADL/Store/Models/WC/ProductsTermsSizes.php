<?php

namespace RADL\Store\Models\WC;
use RADL\Store\Models\ModelWC;

class ProductsTermsSizes extends ModelWC
{
    public $route_base = 'products/attributes/4/terms';

    public $type = 'productsTermsSizes';
    
    public $specific_params = [
        // "per_page" => 100,
    ];
}
