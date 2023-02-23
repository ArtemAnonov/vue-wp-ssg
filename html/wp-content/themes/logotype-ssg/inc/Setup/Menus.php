<?php

namespace Awps\Setup;

/**
 * Menus
 */
class Menus
{
    /**
     * register default hooks and actions for WordPress
     * @return
     */
    public function register()
    {
        add_action('after_setup_theme', array($this, 'menus'));
    }

    public function menus()
    {
        /*
            Register all your menus here
        */
        register_nav_menus(array(
            'top_header' => esc_html__('Top Header', 'awps'),
            'main_category' => esc_html__('Main-Category', 'awps'),
            'banners' => esc_html__('Banners', 'awps'),
            'footerShopingOnline' => esc_html__('footerShopingOnline', 'awps'),
            'footerForCustomers' => esc_html__('footerForCustomers', 'awps'),
            'footerCompany' => esc_html__('footerCompany', 'awps'),
            'profileSectionsList' => esc_html__('profileSectionsList', 'awps'),
            'wishlist' => esc_html__('wishlist', 'awps'),
            'profile' => esc_html__('profile', 'awps'),
            'orders' => esc_html__('orders', 'awps'),

        ));
    }
}
