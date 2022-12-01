<?php

namespace RADL\Store\Requests;

class RequestWPOne extends RequestWP
{
    public $id;

    public function __construct($route_base, $params, $apiType, $id)
    {
        
        $this->apiType = $apiType;
        $this->route_base = $route_base;
        $this->params = $params;
        $this->id = $id;

        $this->create_route();
        $this->create_request();
        $this->set_params();
        $this->do_request();
    }

    protected function create_route()
    {
        $route = $this->apiType . $this->route_base . '/' . $this->id;
        $this->route = $route;
    }

}
