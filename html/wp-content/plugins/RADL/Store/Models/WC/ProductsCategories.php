<?php

namespace RADL\Store\Models\WC;
use RADL\Store\Models\ModelWC;

class ProductsCategories extends ModelWC
{
    public $route_base = 'products/categories';

    public $type = 'productsCategories';

    public $specific_params = [
        // "per_page" => 100,
        "exclude" => [17],
    ];

}
