<?php

namespace RADL\Store\Models;

/**
 * 
 */
abstract class BaseModel
{
    public $apiType;

    protected $specific_single_params = [];

    public $single_params = [];

    public $specific_params = [];

    public $params = [];

    public $type;

    public $route_base;

    public $settings = [
        "sensitive" => false,
        "JWTRequestConfig" => [
            "JWTMaintain" => true,
            "JWTReqired" => true,
        ]
    ];
}
