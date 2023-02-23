<?php

namespace WGU\TInvWL;

/**
 * REST API plugin class Upg
 *
 * @since             1.13.0
 * @package           WGU
 */

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
	die;
}

use WP_REST_Server;

/**
 * REST API plugin class Upgrade
 * 
 * Задача класса расширить возможности конечной точки...((()))
 */
class TInvWL_Includes_API_Wishlist_Upgrade extends \TInvWL_Includes_API_Wishlist
{
	/**
	 * Хотел написать другую конечную точку, но решил изменить метод-обработчик
	 */
	// public function register_routes()
	// {
	//   parent::register_routes();
	//   // 
	// 	register_rest_route($this->namespace, '/' . $this->rest_base . '/remove_product/(?P<item_id>[\d]+)', array(
	// 		array(
	// 			'methods' => WP_REST_Server::READABLE,
	// 			'callback' => array($this, 'wishlist_remove_product'),
	// 			'permission_callback' => '__return_true',
	// 		),
	// 	));
	// }

	/**
	 * Get wishlist(s) data by user ID.
	 *
	 * @param WP_REST_Request $request Request object.
	 *
	 * @return mixed|WP_Error|WP_REST_Response
	 */
	public function wishlist_get_by_user($request)
	{
		try {
			$user_id = isset($request['user_id']) ? absint($request['user_id']) : 0;
			$wl = new \TInvWL_Wishlist();
			if (!empty($user_id)) {

				if (!$this->user_id_exists($user_id)) {
					throw new \WC_REST_Exception('ti_woocommerce_wishlist_api_wishlist_user_not_exists', __('WordPress user does not exist.', 'ti-woocommerce-wishlist'), 400);
				}

				$wishlists = $wl->get_by_user($user_id);

				if (!$wishlists) {
					throw new \WC_REST_Exception('ti_woocommerce_wishlist_api_wishlist_not_found', __('No wishlists found for this user.', 'ti-woocommerce-wishlist'), 400);
				}

				$response = array();
				foreach ($wishlists as $wishlist) {
					$response[] = $this->prepare_wishlist_data($wishlist);
				}

				return rest_ensure_response($response);
			} else {
				$wl->add_sharekey_default();
				// Возможно не помешает здесь дописать получнение вишлиста
			}
		} catch (\WC_REST_Exception $e) {
			return new \WP_Error($e->getErrorCode(), $e->getMessage(), array('status' => $e->getCode()));
		}
	}
}
