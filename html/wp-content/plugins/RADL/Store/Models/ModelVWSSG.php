<?php


namespace RADL\Store\Models;

use RADL\Store\Requests\RequestWC;
use Automattic\WooCommerce\RestApi\Server;
use WP_REST_Request;
use WP_REST_Server;

/**
 * Получение данных этих моделей происходит искллючительно по API из фронтенда
 * 
 * (!) - расширения дочернего класса Model скорее всего не совсем корректно, потому что
 * ряд методов может не использоваться...
 */
class ModelVWSSG extends Model
{
    public $apiType = '/vwssg/v1/';

    public function __construct($prefetch_load = true)
    {
        parent::__construct($prefetch_load);
        add_action('rest_api_init', [$this, 'register_rest_routes']);
    }

    public function register_rest_routes(WP_REST_Server $server)
    {
        register_rest_route(
            // substr(substr($this->apiType, 1), -8, 7), 
            $this->apiType,
            '/' . $this->route_base,
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [$this, 'get_items'],
                    'permission_callback' => [$this, 'get_items_permissions_check']
                ],
                // [
                //     'methods'             => WP_REST_Server::CREATABLE,
                //     'callback'            => [$this, 'create_item'],
                //     // 'permission_callback' => [$c, 'create_item_permissions_check'],
                // ],
            ]
        );
    }

    public function get_items(WP_REST_Request $request)
    {
        $params = $this->validate_params($request);
        $requestWC = new RequestWC($this->route_base, 'get', $params);
        return $requestWC->response['data'];
    }

    /**
     * Возвращает парамтеры (не уверен в эффетивности и лаконичности кода, но работает)
     */
    public function validate_params(WP_REST_Request $request)
    {
        $model = \RADL\Store::get_value('state.' . $this->type);
        $params = array_filter(
            $model->params,
            function ($value, $key) use ($request) {
                return ($request->has_param($key) && !empty($request[$key])) || !empty($value);
            },
            ARRAY_FILTER_USE_BOTH
        );
        foreach ($params as $key => $param) {
            if ($request->has_param($key)) {
                $params[$key] = $request[$key];
            }
        }
        return $params;
    }

    public function create_item(WP_REST_Request $request)
    {
        $data = $request->get_params();

        $requestWC = new RequestWC($this->route_base, 'post', $data);
    }

    /**
     * Надо бы сделать весь класс абстр
     */
    public function get_items_permissions_check($request)
    {
        return new \Exception('Вызов "абстрактного метода ***"');
    }
}
