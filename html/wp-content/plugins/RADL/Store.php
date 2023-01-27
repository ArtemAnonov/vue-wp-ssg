<?php

namespace RADL;

use Exception;
use RADL\Store\Value;
use RADL\Store\Callback;
use RADL\Store\Models\AbstractModel;
use Automattic\WooCommerce\Client;

class Store
{
    public static $woocommerce;

    /**
     * Handle for script where Store will be localized
     * @var string
     */
    public $script_handle;

    /**
     * Single state tree for all requested data - initialized from $schema
     * @var array
     */
    public static $state;

    public static $state_localize;

    /**
     * Will be name of Store variable in script
     * @var string
     */
    public $name;

    public static $instance;

    /**
     * Undocumented function
     *
     * @param string $name
     * @param string $script_handle
     * @param array $schema
     */
    public function __construct( $name = '', $script_handle = '', array $schema = [] )
    {
        $this->name = $name;
        $this->script_handle = $script_handle;
        $this->create_woocommerce_client();
        self::$state = $schema;
    }

    public static function get_instance()
    {
        if (empty(self::$instance)) {
            self::$instance = new Store();
        }
        return self::$instance;
    }

    public static function post($key_path, $params = [], $data = [])
    {
        $value = self::key_value($key_path);
        if ($value instanceof Value) {
            $value = $value->post($params, $data);
        } else {
            throw new \Exception("Неподходящий класс", 1);
        }
        return $value;
    }

    // /**
    //  * Получение свойства state первого уровня вложенности
    //  *
    //  * @param [type] $key_path
    //  * @param [type] $args
    //  * @return void
    //  */
    // public static function get($key_path, $params = [], $key = null)
    // {
    //     $value = self::key_value($key_path);
    //     if ($value instanceof Value) {
    //         $value = $value->get($params, $key);
    //     } else {
    //         throw new \Exception("Неподходящий класс", 1);
    //     }
    //     return $value;
    // }

    public static function get_by_id($key_path, $params = [], $key = null)
    {
        $value = self::key_value($key_path);
        if ($value instanceof Value) {
            $value = $value->get_by_id($params, $key);
        } else {
            throw new \Exception("Неподходящий класс", 1);
        }
        return $value;
    }

    public static function get_all($key_path, $config = [])
    {
        $value = self::key_value($key_path);
        if ($value instanceof Value) {
            $value = $value->get_all($config);
        } else {
            throw new \Exception("Неподходящий класс, дан $value::class", 1);
        }
        return $value;
    }

    /**
     * Undocumented function
     *
     * @param [type] $key_path
     * @return Value
     */
    public static function get_value($key_path) : Value
    {
        $value = self::key_value($key_path);
        if ($value instanceof Value) {
            return $value;
        }
    }

    public static function get_all_by_one_prop($key_path, $key, $input_value)
    {
        $value = self::key_value($key_path);
        if ($value instanceof Value)  $value = $value->get_all_by_one_prop($key, $input_value);
        else throw new \Exception("Неподходящий класс", 1);
        return $value;
    }


    public static function rendered()
    {
        self::render_values();
        return self::$state;
    }

    /**
     * Методом переопределения value получаем необходимый на Value
     *
     * @param [type] $key_path
     * @return void
     */
    public static function key_value( $key_path )
    {
        $value = self::$state;
        foreach ( explode( '.', $key_path ) as $key ) {
            if ( $key !== ''  ) { //&& in_array($key, $value)
                $value = $value[$key];
            } else {
                new \Exception("Искомый ключ: $key не найден в массиве");
            }
        }

        return $value;
    }

    /**
     * $item->render() возвращает объект типа Value
     * 
     * Метод отдаёт во фронтенд мутированный объект
     *
     * @return void
     */
    public static function render_values()
    {
        array_walk_recursive( self::$state, function ( &$item ) {
            if ($item instanceof Value) {
                $item = $item->render();
            }
        });
    }

    private function create_woocommerce_client() {
        Store::$woocommerce = new Client(
            get_site_url(),
            WOO_CK,
            WOO_SC,
            [
                'wp_api' => true,
                'version' => 'wc/v3',
                'oauth_only' => true,
            ]
       );
    }

    public function get_state_json_log()
    {
        echo '<script>console.log(' . json_encode( self::$state ) . ')</script>';
    }
}
