<?php

namespace RADL\Store\Models\VWSSG;

use RADL\Store\Models\ModelVWSSG;
use RADL\Store\Requests\RequestWC;
use WP_REST_Request;

class Customers extends ModelVWSSG
{
    public $route_base = 'customers';

    public $type = 'customers';

    public $specific_params = [];

    public $settings = [
        "sensitive" => true,
        "JWTRequestConfig" => [
            "JWTMaintain" => true,
            "JWTReqired" => true,
        ]
    ];

    public function __construct($prefetch_load = false)
    {
        parent::__construct($prefetch_load);
    }

    public function get_items(WP_REST_Request $request)
    {
        // $params = $this->validate_params($request);
        $user_id = wp_get_current_user()->ID;
        // $params['customer'] = $user_id;
        $requestWC = new RequestWC($this->route_base . '/' . $user_id, 'get', []);
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
