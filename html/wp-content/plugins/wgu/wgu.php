<?php

/**
 * Plugin Name: WGU
 * Plugin URI: https://www.yourwebsiteurl.com/
 * Description: Upgrade controllers WOO.
 * Version: 1.0
 * Author: ArtBot
 * Author URI: http://yourwebsiteurl.com/
 **/

use WGU\Server_Upgrade;
use Automattic\WooCommerce\Blocks\Domain\Bootstrap;
use Automattic\WooCommerce\Blocks\Package;
use Automattic\WooCommerce\StoreApi\StoreApi;

if (!class_exists('WGU')) {

    class WGU
    {
        /**
         * Version
         * @var string
         */
        public static $version = '1.0.0';


        /**
         * Отмена action для конкрентой существующей функции
         */
        public function __construct()
        {

            remove_action('init', array(\WooCommerce::instance(), 'load_rest_api'));
            add_action('init', array($this, 'load_rest_api'));
        }

        /**
         * Load REST API.
         */
        public function load_rest_api()
        {
            Server_Upgrade::instance()->init();
        }

        private static function autoloader($class_name)
        {
            if (strpos($class_name, 'WGU') === 0) {
                require plugin_dir_path(__DIR__) . str_replace('\\', '/', str_replace('_', '-', strtolower($class_name))) . '.php';
            }
        }

        public static function init()
        {
            if (!defined('WGU_LOADED')) {
                spl_autoload_register(array('WGU', 'autoloader'));
                define('WGU_LOADED', true);
            }
        }

        public function FunctionName()
        {
            self::container()->get( Authentication::class )->init();
            self::container()->get( Legacy::class )->init();
            self::container()->get( RoutesController::class )->register_all_routes();
        }
    }

    WGU::init();
}
