<?php

/**
 * Plugin Name: ssg-generator
 * Plugin URI: https://www.yourwebsiteurl.com/
 * Description: Upgrade controllers WOO.
 * Version: 1.0
 * Author: ArtBot
 * Author URI: http://yourwebsiteurl.com/
 **/

if (!class_exists('SSGGenerator')) {

    class SSGGenerator
    {
        /**
         * Version
         * @var string
         */
        public static $version = '1.0.0';



        public function __construct()
        {
            add_action('admin_menu', 'SSGGenerator_setup_menu');

            function SSGGenerator_setup_menu()
            {
                add_menu_page('SSG Generator Page', 'SSGGenerator', 'edit_theme_options', 'ssg-generator', 'admin_menu_page');
            }

            function admin_menu_page()
            {
                include dirname(__FILE__) . '/admin/admin-page.php';
            }
        }


        private static function autoloader($class_name)
        {
            if (strpos($class_name, 'SSGGenerator') === 0) {
                require plugin_dir_path(__DIR__) . str_replace('\\', '/', str_replace('_', '-', strtolower($class_name))) . '.php';
            }
        }

        public static function init()
        {
            if (!defined('SSGGenerator_LOADED')) {
                spl_autoload_register(array('SSGGenerator', 'autoloader'));
                define('SSGGenerator_LOADED', true);
            }
        }
    }

    SSGGenerator::init();
}
