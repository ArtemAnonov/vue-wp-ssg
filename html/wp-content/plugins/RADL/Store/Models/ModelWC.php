<?php


namespace RADL\Store\Models;

class ModelWC extends Model
{
    public $apiType = '/wc/v3/';

    public function __construct($prefetch_load = true)
    {
        parent::__construct($prefetch_load);
    }
}
