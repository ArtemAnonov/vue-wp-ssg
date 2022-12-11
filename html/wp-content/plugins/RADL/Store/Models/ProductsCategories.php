<?php

namespace RADL\Store\Models;

class ProductsCategories extends BasedModelsWC
{
    public $route_base = 'products/categories';

    public $type = 'productsCategories';

    public $specific_params = [
        "per_page" => 100,
        "exclude" => [17],
    ];

}
