<?php

namespace RADL\Store\Requests;
use Automattic\WooCommerce\HttpClient\HttpClientException;
abstract class AbstractRequest
{
    protected $method;

    /**
     * Parameters for request added if $id not defined
     * @var array
     */
    protected $params;

    protected $request;

    public $response;

    /**
     * Route base needed to identify endpoint
     * @var string
     */
    protected $route_base;

    public function __construct(
        $route_base, 
        $method, 
        $params
    ) {
        $this->route_base = $route_base;
        $this->method = $method;
        $this->params = $params;
    }

    abstract protected function do_request();

    abstract public function get_response_data();

    abstract public function get_response_headers();

}
