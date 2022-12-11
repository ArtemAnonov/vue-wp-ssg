<?php

namespace RADL\Store\Models;

class ProductsAttributes extends BasedModelsWC
{
    public $route_base = 'products/attributes';

    public $type = 'productsAttributes';
    
    public $specific_params = [
        "per_page" => 100,
    ];
}
