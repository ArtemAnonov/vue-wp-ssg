<?php

namespace RADL\Store\Models;

class ProductsTermsBrands extends BasedModelsWC
{
    public $route_base = 'products/attributes/1/terms';

    public $type = 'productsTermsBrands';
    
    public $specific_params = [
        "per_page" => 100,
    ];
}
