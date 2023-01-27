<?php

namespace RADL\Store\Models\WC;
use RADL\Store\Models\ModelWC;

class ProductsTermsColors extends ModelWC
{
    public $route_base = 'products/attributes/5/terms';

    public $type = 'productsTermsColors';
    
    public $specific_params = [
        // "per_page" => 100,
    ];
}
