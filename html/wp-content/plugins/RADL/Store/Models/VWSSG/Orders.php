<?php

namespace RADL\Store\Models\VWSSG;

use WP_REST_Request;
use RADL\Store\Requests\RequestWC;
use RADL\Store\Models\ModelVWSSG;

class Orders extends ModelVWSSG
{
    public $route_base = 'orders';

    public $type = 'orders';

    public $specific_params = [];

    public $settings = [
        "sensitive" => false,
        "JWTRequestConfig" => [
            "JWTMaintain" => true,
            "JWTReqired" => true,
        ]
    ];

    public function __construct($prefetch_load = false)
    {
        parent::__construct($prefetch_load);
    }

    /**
     * Метод переопределён, для того чтобы пользоваетельский маршрут для orders получал заказы
     * исключительно текущего пользователя
     */
    public function get_items(WP_REST_Request $request)
    {
        $params = $this->validate_params($request);
        $user_id = wp_get_current_user()->ID;
        $params['customer'] = $user_id;
        $requestWC = new RequestWC($this->route_base, 'get', $params);
        return $requestWC->response['data'];
    }

    public function get_items_permissions_check($request)
    {
        $user_id = wp_get_current_user()->ID;
        if ($user_id === 0) {
            return new \WP_Error('abgvw_rest_cannot_view', 'Необходимо авторизоваться для получения списка заказов (пользовательского запроса текущего пользователя)', array('status' => rest_authorization_required_code()));
        }
        return true;
    }
}
