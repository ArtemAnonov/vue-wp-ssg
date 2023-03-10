<?php

namespace WGU\Controllers;

class WC_REST_Products_Controller_Upgrade extends \WC_REST_Products_Controller
{
    /**
     * Register the routes for products.
     */
    public function register_routes()
    {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            array(

                array(
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => array($this, 'get_items'),
                    //WGU
                    'permission_callback' => '__return_true',
                    //WGU
                    'args'                => $this->get_collection_params(),
                ),

                array(
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => array($this, 'create_item'),
                    'permission_callback' => array($this, 'create_item_permissions_check'),
                    'args'                => $this->get_endpoint_args_for_item_schema(\WP_REST_Server::CREATABLE),
                ),
                'schema' => array($this, 'get_public_item_schema'),
            )
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/(?P<id>[\d]+)',
            array(
                'args'   => array(
                    'id' => array(
                        'description' => __('Unique identifier for the resource.', 'woocommerce'),
                        'type'        => 'integer',
                    ),
                ),
                array(
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => array($this, 'get_item'),
                    //WGU
                    'permission_callback' => '__return_true',
                    //WGU
                    'args'                => array(
                        'context' => $this->get_context_param(
                            array(
                                'default' => 'view',
                            )
                        ),
                    ),
                ),
                array(
                    'methods'             => \WP_REST_Server::EDITABLE,
                    'callback'            => array($this, 'update_item'),
                    'permission_callback' => array($this, 'update_item_permissions_check'),
                    'args'                => $this->get_endpoint_args_for_item_schema(\WP_REST_Server::EDITABLE),
                ),
                array(
                    'methods'             => \WP_REST_Server::DELETABLE,
                    'callback'            => array($this, 'delete_item'),
                    'permission_callback' => array($this, 'delete_item_permissions_check'),
                    'args'                => array(
                        'force' => array(
                            'default'     => false,
                            'description' => __('Whether to bypass trash and force deletion.', 'woocommerce'),
                            'type'        => 'boolean',
                        ),
                    ),
                ),
                'schema' => array($this, 'get_public_item_schema'),
            )
        );

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base . '/batch',
            array(
                array(
                    'methods'             => \WP_REST_Server::EDITABLE,
                    'callback'            => array($this, 'batch_items'),
                    'permission_callback' => array($this, 'batch_items_permissions_check'),
                    'args'                => $this->get_endpoint_args_for_item_schema(\WP_REST_Server::EDITABLE),
                ),
                'schema' => array($this, 'get_public_batch_schema'),
            )
        );
    }

    	/**
	 * Get a collection of posts.
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$query_args = $this->prepare_objects_query( $request );
		if ( is_wp_error( current( $query_args ) ) ) {
			return current( $query_args );
		}
		$query_results = $this->get_objects( $query_args );

		$objects = array();
		foreach ( $query_results['objects'] as $object ) {
			// if ( ! wc_rest_check_post_permissions( $this->post_type, 'read', $object->get_id() ) ) {
			// 	continue;
			// }

			$data      = $this->prepare_object_for_response( $object, $request );
			$objects[] = $this->prepare_response_for_collection( $data );
		}

		$page      = (int) $query_args['paged'];
		$max_pages = $query_results['pages'];

		$response = rest_ensure_response( $objects );
		$response->header( 'X-WP-Total', $query_results['total'] );
		$response->header( 'X-WP-TotalPages', (int) $max_pages );

		$base          = $this->rest_base;
		$attrib_prefix = '(?P<';
		if ( strpos( $base, $attrib_prefix ) !== false ) {
			$attrib_names = array();
			preg_match( '/\(\?P<[^>]+>.*\)/', $base, $attrib_names, PREG_OFFSET_CAPTURE );
			foreach ( $attrib_names as $attrib_name_match ) {
				$beginning_offset = strlen( $attrib_prefix );
				$attrib_name_end  = strpos( $attrib_name_match[0], '>', $attrib_name_match[1] );
				$attrib_name      = substr( $attrib_name_match[0], $beginning_offset, $attrib_name_end - $beginning_offset );
				if ( isset( $request[ $attrib_name ] ) ) {
					$base = str_replace( "(?P<$attrib_name>[\d]+)", $request[ $attrib_name ], $base );
				}
			}
		}
		$base = add_query_arg( $request->get_query_params(), rest_url( sprintf( '/%s/%s', $this->namespace, $base ) ) );

		if ( $page > 1 ) {
			$prev_page = $page - 1;
			if ( $prev_page > $max_pages ) {
				$prev_page = $max_pages;
			}
			$prev_link = add_query_arg( 'page', $prev_page, $base );
			$response->link_header( 'prev', $prev_link );
		}
		if ( $max_pages > $page ) {
			$next_page = $page + 1;
			$next_link = add_query_arg( 'page', $next_page, $base );
			$response->link_header( 'next', $next_link );
		}

		return $response;
	}

    /**
     * Make extra product orderby features supported by WooCommerce available to the WC API.
     * This includes 'price', 'popularity', and 'rating'.
     *
     * @param WP_REST_Request $request Request data.
     * @return array
     */
    public function prepare_objects_query($request)
    {
        $args = \WC_REST_CRUD_Controller::prepare_objects_query($request);
        // Set post_status.
        $args['post_status'] = $request['status'];

        // Taxonomy query to filter products by type, category,
        // tag, shipping class, and attribute.
        $tax_query = array();

        // Map between taxonomy name and arg's key.
        $taxonomies = array(
            'product_cat'            => 'category',
            'product_tag'            => 'tag',
            'product_shipping_class' => 'shipping_class',
        );

        // Set tax_query for each passed arg.
        foreach ($taxonomies as $taxonomy => $key) {
            if (!empty($request[$key])) {
                $tax_query[] = array(
                    'taxonomy' => $taxonomy,
                    'field'    => 'term_id',
                    'terms'    => $request[$key],
                );
            }
        }

        // Filter product type by slug.
        if (!empty($request['type'])) {
            $tax_query[] = array(
                'taxonomy' => 'product_type',
                'field'    => 'slug',
                'terms'    => $request['type'],
            );
        }

        $attr_prefix = 'pa_';
        $request_params = $request->get_params();
        $request_keys = array_keys($request_params);
        $attribute_keys = array_filter($request_keys, fn ($var) => preg_match("/^" . $attr_prefix . "[a-z]+/", $var));
        if (count($attribute_keys)) {
            $attribute_taxonomy_names = wc_get_attribute_taxonomy_names();
            $matched = array_intersect($attribute_keys, $attribute_taxonomy_names);

            if (count($matched) === count($attribute_keys)) {
                $attributes_for_tax_query = [
                    'relation' => 'AND',
                ];
                foreach ($attribute_keys as $key => $attribute_name) { // 0 => pa_brend, ...
                    $attributes_for_tax_query[] = [
                        'taxonomy' => $attribute_name,
                        'field'    => 'term_id',
                        'terms'    => $request_params[$attribute_name],
                    ];
                }
                $tax_query[] = $attributes_for_tax_query;
            } else {
                throw new \Exception("Invalid attribute", 1);
            }
        }

        // Build tax_query if taxonomies are set.
        if (!empty($tax_query)) {
            if (!empty($args['tax_query'])) {
                $args['tax_query'] = array_merge($tax_query, $args['tax_query']); // WPCS: slow query ok.
            } else {
                $args['tax_query'] = $tax_query; // WPCS: slow query ok.
            }
        }

        // Filter featured.
        if (is_bool($request['featured'])) {
            $args['tax_query'][] = array(
                'taxonomy' => 'product_visibility',
                'field'    => 'name',
                'terms'    => 'featured',
                'operator' => true === $request['featured'] ? 'IN' : 'NOT IN',
            );
        }

        // Filter by sku.
        if (!empty($request['sku'])) {
            $skus = explode(',', $request['sku']);
            // Include the current string as a SKU too.
            if (1 < count($skus)) {
                $skus[] = $request['sku'];
            }

            $args['meta_query'] = $this->add_meta_query( // WPCS: slow query ok.
                $args,
                array(
                    'key'     => '_sku',
                    'value'   => $skus,
                    'compare' => 'IN',
                )
            );
        }

        // Filter by tax class.
        if (!empty($request['tax_class'])) {
            $args['meta_query'] = $this->add_meta_query( // WPCS: slow query ok.
                $args,
                array(
                    'key'   => '_tax_class',
                    'value' => 'standard' !== $request['tax_class'] ? $request['tax_class'] : '',
                )
            );
        }

        // Price filter.
        if (!empty($request['min_price']) || !empty($request['max_price'])) {
            $args['meta_query'] = $this->add_meta_query($args, wc_get_min_max_price_meta_query($request));  // WPCS: slow query ok.
        }

        // Filter product by stock_status.
        if (!empty($request['stock_status'])) {
            $args['meta_query'] = $this->add_meta_query( // WPCS: slow query ok.
                $args,
                array(
                    'key'   => '_stock_status',
                    'value' => $request['stock_status'],
                )
            );
        }

        // Filter by on sale products.
        if (is_bool($request['on_sale'])) {
            $on_sale_key = $request['on_sale'] ? 'post__in' : 'post__not_in';
            $on_sale_ids = wc_get_product_ids_on_sale();

            // Use 0 when there's no on sale products to avoid return all products.
            $on_sale_ids = empty($on_sale_ids) ? array(0) : $on_sale_ids;

            $args[$on_sale_key] += $on_sale_ids;
        }

        // Force the post_type argument, since it's not a user input variable.
        if (!empty($request['sku'])) {
            $args['post_type'] = array('product', 'product_variation');
        } else {
            $args['post_type'] = $this->post_type;
        }

        $ordering_args   = WC()->query->get_catalog_ordering_args($args['orderby'], $args['order']);
        $args['orderby'] = $ordering_args['orderby'];
        $args['order']   = $ordering_args['order'];
        if ($ordering_args['meta_key']) {
            $args['meta_key'] = $ordering_args['meta_key']; // WPCS: slow query ok.
        }

        return $args;
    }
}
