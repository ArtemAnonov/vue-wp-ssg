<?php

namespace RADL\Store\Models\WC;
use RADL\Store\Models\ModelWC;

class Wishlist extends ModelWC
{
    public $route_base = 'wishlist';

    public $type = 'wishlist';

    public $specific_params = [

    ];

    public function __construct($prefetch_load = false)
    {
        parent::__construct($prefetch_load);
    }
}
