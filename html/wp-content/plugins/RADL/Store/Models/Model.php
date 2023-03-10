<?php

namespace RADL\Store\Models;

use RADL\Store\Value;
use RADL\Store\Requests\RequestWP;
use RADL\Store\Requests\RequestWC;
use RADL\Store\Requests\RequestWPOne;

/**
 * render()->get()->request()
 * 
 * Функция get получает items, загружая их, если их нет в наличии  
 * 
 * В ОТЛИЧИЕ ОТ WP, WC ИСПОЛЬЗУЕТ СТРОКОВЫЕ ЗНАЧЕНИЯ ПАРАМЕТРОВ ЗАПРОСА
 * ВМЕСТО 'category' => 25 --- 'category' => '25'
 * 
 */
abstract class Model extends BaseModel implements Value
{
    public $params = [
        "per_page" => 100,
        // "exclude" => [],
        // "order" => "",
        // "orderby" => "",

    ];

    public $items = [];
    /**
     * Keeps track of requests made and associated response data
     * @var array
     */
    public $requests = [];
    /**
     * Undocumented variable
     *
     * @var boolean
     */
    protected $prefetch_load; // = false

    public $settings = [
        "sensitive" => false,
        "JWTRequestConfig" => [
            "JWTMaintain" => true,
            "JWTReqired" => true,
        ]
    ];

    /**
     * Undocumented function
     */
    public function __construct($prefetch_load = false)
    {
        $this->prefetch_load = $prefetch_load;
        $this->params = array_merge($this->specific_params, $this->params);
        $this->single_params = array_merge($this->specific_single_params, $this->single_params);
    }

    public function post(array $params, $data)
    {
        $this->request($params, null, $data, 'POST');
    }

    public function get_all(array $params)
    {
        if (empty($params)) $params = $this->params;
        $requested = $this->get_requested($params);
        if (is_null($requested)) {
            $this->request($params);
            $requested = $this->get_requested($params);
        }
        return $this->ids_to_items($requested['data']);
    }

    public function get_by_id($params, $id)
    {
        if (empty($params)) $params = $this->single_params;
        if (!isset($this->items[$id])) {
            $this->request($params, $id);
        }
        return $this->items[$id];
    }

    protected function add_item($val, $key)
    {
        if (!isset($this->items[$key])) {
            $this->items[$key] = $val;
        }
    }

    protected function add_items(array $items, $args, array $headers)
    {
        $id_key = $this->id_key_from_items($items);
        $request = array(
            'params' => $args,
            'data' => [],
        );

        if ($id_key) {
            foreach ($items as $item) {
                $this->add_item($item, $item[$id_key]);
                array_push($request['data'], $item[$id_key]);
            }
        } else {
            foreach ($items as $key => $val) {
                $this->add_item($val, $key);
                array_push($request['data'], $key);
            }
        }
        if (isset($headers['X-WP-Total'])) {
            $request['total'] = $headers['X-WP-Total'];
            $request['totalPages'] = $headers['X-WP-TotalPages'];
        }
        array_push($this->requests, $request);
    }


    public function get_all_by_one_prop($key, $input_value)
    {
        if (empty($this->items)) {
            $this->get_all($this->params);
        }
        return array_values(array_filter($this->items, fn ($item) => $item[$key] == $input_value));
    }

    /**
     * Поиск реквеста
     *
     * @param array $args
     * @return void
     */
    protected function get_requested(array $params)
    {
        foreach ($this->requests as $request) {
            if ($request['params'] == $params) {
                return $request;
            }
        }
    }

    public function get_request()
    {
        return $this->requests[0];
    }

    /**
     * Заполнение правильными значениями data в request
     *
     * @param array $items
     * @return void
     */
    protected function id_key_from_items(array $items)
    {
        if (count($items)) {
            $item = array_values($items)[0];
            if (is_array($item)) {
                if (isset($item['id'])) {
                    return 'id';
                } elseif (isset($item['slug'])) {
                    return 'slug';
                }
            }
        }
    }

    protected function ids_to_items(array $ids)
    {
        $items = [];
        foreach ($ids as $id) {
            array_push($items, $this->items[$id]);
        }
        return $items;
    }

    /**
     * метод render() используется в начале. Проблема заключается в том, что
     * во фронтенд передаётся объект с значениями [], если в массиве нет значений и {"ключ": "значение"},
     * если массив не пуст. Решением является то, что для фронтенда мы отдаем мутированные объекты,
     * для которых пустые массивы подменияются пустыми объектами
     *
     * @return void
     */
    public function render()
    {
        if ($this->prefetch_load === true) {
            $this->get_all($this->params);
        }

        // $this->basedRequest = (object) [
        //     'route_base' => $this->route_base,
        //     'type' => $this->type,
        //     'apiType' => $this->apiType
        // ];

        return $this; //[$this, $clone]
    }

    /**
     * Пагинация с помощью query
     *
     * @return void
     */
    protected function set_current_page()
    {
        $prop_name = $this->type . "_page";
        if (array_key_exists($prop_name, $_GET)) {
            $this->params['page'] = (int) $_GET[$prop_name];
        }
    }

    /**
     * Отсечение пустых параметров производится для запроса, в requests же попадают 
     * все параметры, что необходимо для успешного поиска
     *
     * @param [type] $params
     * @return void
     */
    protected function request($params, $id = null, $data = [], $method = 'get')
    {
        $not_empty_params = array_filter($params, fn ($param) => !empty($param)); // (*)
        if ($this->apiType === '/racr/v1/') {

            $request = new RequestWC(
                $this->route_base,
                $method,
                $not_empty_params //, 'headers' => ! empty($config['headers']) ? $config['headers'] : []
            );
            $data = $request->get_response_data();
            $headers = []; // $request->get_response_headers()
            $this->add_items($data, $params, $headers);
        } else {
            if (is_null($id)) {
                $request = new RequestWP(
                    $this->route_base,
                    $method,
                    $not_empty_params,
                    $this->apiType,
                    []
                );
                $data = $request->get_response_data();
                $headers = $request->get_response_headers();
                $this->add_items($data, $params, $headers);
            } else {
                // $request = new RequestWPOne(
                //     $this->apiType, 
                //     ['params' => $not_empty_params, 
                //     'headers' => $config['headers']], 
                //     $this->route_base, $id
                // );
                // $data = $request->get_response_data();
                // // $headers = $request->get_response_headers();
                // $this->add_item($data, $id);
            }
        }
    }
}
