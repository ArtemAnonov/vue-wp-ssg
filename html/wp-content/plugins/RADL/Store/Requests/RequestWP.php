<?php

namespace RADL\Store\Requests;
use Automattic\WooCommerce\HttpClient\HttpClientException;
class RequestWP extends AbstractRequest
{
    /**
     *  namespace
     */
    public $apiType;

    protected $attributes;
    /**
     * Identifies endpoint to use for request
     * @var string
     */
    protected $route;

    public function __construct(
        string $route_base, 
        string $method, 
        array $params, 
        string $apiType, 
        array $attributes = []
    ){
        parent::__construct(
            $route_base, 
            $method, 
            $params,
        );
        $this->apiType = $apiType;
        $this->attributes = $attributes;

        $this->create_route();
        $this->create_request();
        $this->set_params();
        $this->do_request();
    }

    public function get_response_data()
    {
        $server = \rest_get_server();
        return $server->response_to_data( $this->response, false );
    }

    public function get_response_headers()
    {
        return $this->response->get_headers();
    }

    protected function create_request()
    {
        $this->request = new \WP_REST_Request($this->method, $this->route, $this->attributes);
    }

    protected function create_route()
    {
        $route = $this->apiType . $this->route_base;
        $this->route = $route;
    }

    protected function set_params()
    {
        $this->request->set_query_params($this->params);
    }

    protected function do_request()
    {
        try {
            $this->response = \rest_do_request($this->request);
            if ( $this->response->is_error() ) {
                throw new \Exception();
            }
            \wp_reset_postdata();
            echo '<script>console.log(' . json_encode( array('method' => $this->method, 'route' => $this->route, 'params' => $this->params, 'response' => $this->get_response_data(), 'request' => $this->request ) ) . ')</script>';

        } catch (\Throwable $th) {
            echo '<script>console.log(' . json_encode( array( 'th' => $th, 'method' => $this->method, 'route' => $this->route, 'params' => $this->params, 'response' => $this->get_response_data() ) ) . ')</script>';
            wp_die();
        }

    }

}
