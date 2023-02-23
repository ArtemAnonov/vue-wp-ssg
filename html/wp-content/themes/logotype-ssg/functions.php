<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);

// echo phpinfo();
// xdebug_info();
// setcookie("TestCookie", 'v');
// add_action('wp', function(){
//     status_header(200);
// });
// add_action( 'send_headers', 'my_headers' );

// function my_headers() {
// }
// header("test: value");
// header("Content-Security-Policy: default-src 'self'");

/**
 *
 * This theme uses PSR-4 and OOP logic instead of procedural coding
 * Every function, hook and action is properly divided and organized inside related folders and files
 * Use the file `inc/Custom/Custom.php` to write your custom functions
 *
 * @package awps
 */

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) :
    require_once dirname(__FILE__) . '/vendor/autoload.php';
endif;

if (class_exists('Awps\\Init')) {
    Awps\Init::register_services();
}

// exec();

function vue_wordpress_min_read($content)
{
    $length = count(explode(' ', $content)) + 1;
    $time = $length / 200;

    if (is_float($time)) {
        $time = ceil($time);
    }

    return $time . 'min read';
}

function get_static_html_page(string $page_path = '')
{
    $page_path = !empty($page_path) ? $page_path : '/' . get_page_uri();
    include get_template_directory() . '/vue-vite-ssg/dist/static' . $page_path  . '.html';
}

// use YooKassa\Client;

// $client = new Client();
// $client->setAuth('Идентификатор магазина', 'Секретный ключ');



function dd($value, $file = '', $line = '')
{
    echo '<pre><b>', $line, $file, '</b><br>', print_r($value, 1), '</pre>';
}


// use YooKassa\Client;

// $client = new Client();
// $client->setAuth('969689', 'test_3k7dfmi02Hmpl1lW2TvdhU9BurQBfiOzoNdval-5Fh4');
// $payment = $client->createPayment(
//     array(
//         'amount' => array(
//             'value' => 100.0,
//             'currency' => 'RUB',
//         ),
//         'confirmation' => array(
//             'type' => 'redirect',
//             'return_url' => 'http://localhost',
//         ),
//         'capture' => true,
//         'description' => 'Заказ №1',
//     ),
//     uniqid('', true)
// );
// $payments = $client->getPayments(['status' => 'succeeded']);
/**
 * Declaring WooCommerce support in themes
 *
 * @return void
 */
function mytheme_add_woocommerce_support()
{
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'mytheme_add_woocommerce_support');

wp_localize_script('wp-api', 'wpApiSettings', array('root' => esc_url_raw(rest_url()), 'nonce' => wp_create_nonce('wp_rest')));
wp_enqueue_script('wp-api');
