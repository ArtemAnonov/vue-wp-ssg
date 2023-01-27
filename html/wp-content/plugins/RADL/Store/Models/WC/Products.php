<?php

namespace RADL\Store\Models\WC;
use RADL\Store\Models\ModelWC;

class Products extends ModelWC
{
    protected $specific_single_params = [
        // 'context' => 'view',
    ];

    public $route_base = 'products';

    public $type = 'products';

    public $specific_params = [
        // '_fields' => [
        //     'id',
        //     'slug',
        //     'name',
        //     'price',
        //     'regular_price',
        //     'sale_price',
        //     'images',
        //     'attributes',
        //     'categories',
        //     'date_modified_gmt',
        //     'date_modified',
        //     'date_created_gmt',
        //     'date_created',
        // ],
        // "per_page" => 8,
        "page" => 1,
        "category" => "",
        "slug" => "",
        "min_price" => null,
        "max_price" => null,
        "pa_brand" => [],
        "pa_tcvet" => [],
        "pa_material" => [],
        "type" => 'simple',
        "order" => "",
        "orderby" => "",

    ];

    public function __construct() {
        parent::__construct();
        $this->set_current_page();
    }

}
