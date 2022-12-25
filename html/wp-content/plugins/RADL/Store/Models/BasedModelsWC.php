<?php


namespace RADL\Store\Models;

use RADL\Store\Requests\RequestWC;
use Automattic\WooCommerce\RestApi\Server;
use WP_REST_Request;
use WP_REST_Server;

class BasedModelsWC extends BasedModels
{
    public $apiType = '/wc/v3/';

    public function __construct($prefetch_load = true)
    {
        parent::__construct($prefetch_load);
    }

    public function register_rest_routes(WP_REST_Server $server) {
        register_rest_route( 
            substr(substr($this->apiType, 1), -8, 7), 
            '/' . $this->route_base, 
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [$this, 'get_items'],
                ],
                [
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => [$this, 'create_item'],
					// 'permission_callback' => [$c, 'create_item_permissions_check'],
				],
            ]
        );
    }

    public function get_items(WP_REST_Request $request) {
        $model = \RADL\Store::get_value('state.' . $this->type);
        $params = array_filter(
            $model->params, 
            function($value, $key) use ($request) {
                return ($request->has_param($key) && ! empty($request[$key])) || ! empty($value);
            },  
            ARRAY_FILTER_USE_BOTH
        );
        foreach ($params as $key => $param) {
            if ($request->has_param($key)) {
                $params[$key] = $request[$key];
            }
        }

        // print_r($value->params);

        $requestWC = new RequestWC($this->route_base, 'get', $params);
        return $requestWC->response['data'];
    }

    public function create_item(WP_REST_Request $request)
    {
        $data = $request->get_params();
        
        $requestWC = new RequestWC($this->route_base, 'post', $data);
    }

}


