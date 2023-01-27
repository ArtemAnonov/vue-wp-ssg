<?php

namespace RADL\Store\Models\WC;
use RADL\Store\Models\ModelWC;

class PaymentGateways extends ModelWC
{
    public $route_base = 'payment_gateways';

    public $type = 'payment_gateways';

    public $specific_params = [

    ];

    public function __construct($prefetch_load = false)
    {
        parent::__construct($prefetch_load);
    }
}
