<?php

namespace RADL\Store\Requests;

class Request
{
    protected $attributes;

    protected $method;

    public static array $log_info = [];
    /**
     * 
     */
    public $apiType;

    /**
     * Parameters for request added if $id not defined
     * @var array
     */
    protected $params;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $headers;
    /**
     * WP core class used for REST requests
     * @var WP_REST_Request
     */
    protected $request;
    /**
     * WP core class used for REST responses
     * @var WP_REST_Response
     */
    protected $response;
    /**
     * Identifies endpoint to use for request
     * @var string
     */
    protected $route;
    /**
     * Route base needed to identify endpoint
     * @var string
     */
    protected $route_base;

    public function __construct( $route_base, $config, $apiType, $method, $attributes = [] )
    {
        $this->apiType = $apiType;
        $this->route_base = $route_base;
        $this->params = $config['params'];
        $this->headers = $config['headers'];
        $this->method = $method;
        $this->attributes = $attributes;

        $this->create_route();
        $this->create_request();
        $this->set_params();
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
        $this->request = new \WP_REST_Request( $this->method, $this->route, $this->attributes );
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

    protected function set_headers()
    {
        $this->request->set_headers($this->headers);
    }

    protected function do_request()
    {
        $this->response = \rest_do_request( $this->request );
        if ( $this->response->is_error() ) {
            echo '<script>console.log(' . json_encode( array( 'method' => $this->method, 'route' => $this->route, 'params' => $this->params, 'headers' => $this->headers, 'response' => $this->get_response_data() ) ) . ')</script>';
            wp_die();
        }
        \wp_reset_postdata();
    }

    // public static function get_log_info()
    // {
    //     echo '<script>console.log(' . json_encode( self::$log_info ) . ')</script>';
    // }


}
