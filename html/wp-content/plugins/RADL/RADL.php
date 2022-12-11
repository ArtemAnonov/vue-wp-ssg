<?php
/*
Plugin Name: RADL Modificated by ArtBot
Description: Localizes a store of normalized WP REST API response data to a registered script. Useful for sharing data between Wordpress and front-end frameworks like Vue.js, React, and Angular that leverage the WP REST API.
Author:      Brandon Shiluk
Author URI:  https://github.com/bucky355
Version:     1.0.0
Text Domain: rest-api-data-localizer
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 */
defined('ABSPATH') || exit;

if (!class_exists('RADL')) { // && is_admin() === false

    class RADL
    {
        /**
         * Version
         * @var string
         */
        public static $version = '1.0.0';

        /**
         * Tracks all data requested - rendered output to be localized
         * @var Store
         */
        public static $store;

        /**
         * Undocumented function
         *
         * @param [type] $name
         * @param [type] $script_handle
         * @param array $schema - Store Value-type objects
         */
        public function __construct(array $schema, $name = '__VUE_WORDPRESS__', $script_handle = 'index.bundle.js')
        {
            $store = new RADL\Store($name, $script_handle, $schema);
            RADL\Store::$instance = $store;
            self::$store = $store;

            // (!) not run
            add_action('wp_footer', array('RADL', 'localize'));
        }

        /**
         * Render store (and replace values relevating objects/arrays)
         *
         * @return void
         */
        public static function render_store()
        {
            self::$store::rendered();
            array_walk_recursive(self::$store::$state, function ($item, $key) use (&$clone_state) {
                if ($item instanceof RADL\Store\Value) {
                    if ($item instanceof RADL\Store\Models\BasedModels) {
                        $clone = clone $item;
                        if (empty($clone->params)) $clone->params = (object) [];
                        if (empty($clone->items)) $clone->items = (object) [];
                        $clone_state['state'][$key] = $clone;
                    } elseif ($item instanceof RADL\Store\Callback) {
                        if ($key === 'routing') {
                            $clone_state['routing'] = clone $item;
                        } else {
                            $clone_state['state'][$key] = clone $item;
                        }
                    }
                }
            });

            return $clone_state;
        }



        /**
         * Передача отрендеренных данных во фронтенд
         *
         * @return void
         */
        public static function localize()
        {
            $clone_state = self::render_store();
            echo '<script>console.log(' . json_encode($clone_state) . ')</script>';
            wp_localize_script(self::$store->script_handle, self::$store->name, $clone_state);
        }

        private static function autoloader($class_name)
        {
            if (strpos($class_name, 'RADL') === 0) {
                require plugin_dir_path(__DIR__) . str_replace('\\', '/', str_replace('_', '-', $class_name)) . '.php';
            }
        }

        public static function init()
        {
            if (!defined('RADL_LOADED')) {
                spl_autoload_register(array('RADL', 'autoloader'));
                define('RADL_LOADED', true);
            }
        }
    }

    RADL::init();
}
