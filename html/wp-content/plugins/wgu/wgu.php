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
use WGU\TInvWL\TInvWL_Includes_API_Wishlist_Upgrade;

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
            remove_action('rest_api_init', [\TInvWL_API::class, 'register_routes'], 15 );

            add_action( 'rest_api_init', [$this, 'TInvWL_API_register_routes'], 15 );
            // remove_action('init', array(\WooCommerce::instance(), 'load_rest_api'));
            // add_action('init', array($this, 'load_rest_api'));

        }

        /**
         * Load REST API. (Это было для переопределения контроллеров, но по сути это не очень важно и нужно
         * (особенно для SSG), так как WCGB, например и так написал конечные точки для продуктов итц, для
         * получения особых результатов, типа выборка заказов для зареганного пользователя я вынес в отдельные
         * маршруты для моделей)
         */
        // public function load_rest_api()
        // {
        //     Server_Upgrade::instance()->init();
        // }

        public function TInvWL_API_register_routes() {
            global $wp_version;

            if ( version_compare( $wp_version, 4.4, '<' ) || ( ! defined( 'WC_VERSION' ) || version_compare( WC_VERSION, '2.6', '<' ) ) ) {
                return;
            }
            $controller = new TInvWL_Includes_API_Wishlist_Upgrade();
            $controller->register_routes();

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
    }

    WGU::init();
}
