<?php

/**
 *
 * This theme uses PSR-4 and OOP logic instead of procedural coding
 * Every function, hook and action is properly divided and organized inside related folders and files
 * Use the file `config/custom/custom.php` to write your custom functions
 *
 * @package awps
 */

namespace Awps;

use Exception;

final class Init
{
    /**
     * (!) - не понятно, рочему при запуске не админ запроса (вроде бы) не запускается
     * то что установлено... Была проблема с загрузкой VueWordpress класса, который по идее должен загружаться
     * только для неадминки
     * 
     * Store all the classes inside an array
     * @return array Full list of classes
     */
    public static function get_services()
    {
        $universal_services = [
            [Core\Tags::class, []],
            [Core\Sidebar::class, []],
            [Setup\Setup::class, []],
            [Setup\Menus::class, []],

            [Setup\Enqueue::class, []],
            [Custom\PostTypes::class, []],
            [Custom\Admin::class, []],
            [Custom\Extras::class, []],
            [Api\Customizer::class, []],
            [Api\Gutenberg::class, []],
            [Api\Widgets\TextWidget::class, []],
            [Plugins\ThemeJetpack::class, []],
            [Plugins\Acf::class, []],
            // [\MyTest\MyReflection\Reflection::class, []]
            [VueWordpress\VueWordpress::class, []],

        ];
        $public_services = [
        ];
        $admin_services = [];
        return is_admin() ?
            array_merge($universal_services, $public_services) :
            array_merge($universal_services, $admin_services);
    }

    /**
     * Loop through the classes, initialize them, and call the register() method if it exists
     * @return
     */
    public static function register_services()
    {
        foreach (self::get_services() as $array) {
            $service = self::instantiate($array[0], $array[1]);
            if (method_exists($service, 'register')) {
                $service->register();
            } else {
                new Exception("Method register() dont exist in class $array[0]");
            }
        }
    }

    /**
     * Initialize the class
     * @param  class $class 		class from the services array
     * @return class instance 		new instance of the class
     */
    private static function instantiate($class, $args)
    {
        return new $class($args);
    }
}
