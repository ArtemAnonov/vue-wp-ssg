<?php

namespace RADL\Store\Requests;
use Automattic\WooCommerce\HttpClient\HttpClientException;
use RADL\Store;
class RequestWC extends AbstractRequest
{

    public function __construct(
        string $route_base, 
        string $method, 
        array $params
    ){
        parent::__construct(
            $route_base, 
            $method, 
            $params,
        );
        $this->do_request();
        
    }

    public function get_response_data()
    {
        return $this->response['data'];
    }

    public function get_response_headers()
    {
        return $this->response['response'];
    }

    protected function do_request()
    {
        $method = $this->method;
        try {
            $this->response['data'] = Store::$woocommerce->$method($this->route_base, $this->params);
            // print_r($this->response);
            $this->response['response'] = Store::$woocommerce->http->getResponse();
            // echo '<script>console.log(' . json_encode( ['method' => $this->method, 'route' => $this->route_base, 'params' => $this->params, 'response' => $this->response] ) . ')</script>';
        } catch (HttpClientException $e) {
            print_r(json_encode( ['method' => $this->method, 'route' => $this->route_base, 'params' => $this->params, 'message' => $e->getMessage(), 'request' => $e->getRequest(), 'response' => $e->getResponse()] ));
            // echo '<script>console.log(' . json_encode( ['method' => $this->method, 'route' => $this->route_base, 'params' => $this->params, 'message' => $e->getMessage(), 'request' => $e->getRequest(), 'response' => $e->getResponse()] ) . ')</script>';
            // wp_die();
        }
    }



}
