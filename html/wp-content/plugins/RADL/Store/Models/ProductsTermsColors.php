<?php

namespace RADL\Store\Models;

class ProductsTermsColors extends BasedModelsWC
{
    public $route_base = 'products/attributes/5/terms';

    public $type = 'productsTermsColors';
    
    public $specific_params = [
        "per_page" => 100,
    ];
}
