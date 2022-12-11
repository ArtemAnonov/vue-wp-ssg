<?php

namespace RADL\Store\Models;

class ProductsTermsSizes extends BasedModelsWC
{
    public $route_base = 'products/attributes/4/terms';

    public $type = 'productsTermsSizes';
    
    public $specific_params = [
        "per_page" => 100,
    ];
}
