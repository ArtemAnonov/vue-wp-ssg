<?php

namespace WGU\WOO\Controllers;

class WC_REST_Products_Controller_Upgrade extends \WC_REST_Products_Controller
{
    /**
     *
     * @param WC_Product $product  Product instance.
     *
     * @return string
     */
    protected function get_categories_path($product)
    {

        $taxonomy = 'product_cat';
        $assoc = [];
        $terms_link_path = array_map(function ($term) use ($taxonomy) {
            $url = parse_url(get_term_link($term->term_id, $taxonomy));
            return $url['path'];
        }, wc_get_object_terms($product->get_id(), $taxonomy));
        $counts = array_map(function ($path) {
            return count(explode('/', $path));
        }, $terms_link_path);

        for ($i = 0; $i < count($terms_link_path); $i++) {
            $assoc[$terms_link_path[$i]] = $counts[$i];
        }
        $longest_path = array_search(max($counts), $assoc);
        return $longest_path;
    }

    /**
     *
     * @param WC_Product $product Product instance.
     * @param string     $context Request context. Options: 'view' and 'edit'.
     *
     * @return array
     */
    protected function get_product_data($product, $context = 'view')
    {
        $request = func_num_args() >= 3 ? func_get_arg(2) : new WP_REST_Request('', '', array('context' => $context));
        $fields  = $this->get_fields_for_response($request);

        $base_data = array();
        foreach ($fields as $field) {
            switch ($field) {
                case 'id':
                    $base_data['id'] = $product->get_id();
                    break;
                case 'name':
                    $base_data['name'] = $product->get_name($context);
                    break;
                case 'slug':
                    $base_data['slug'] = $product->get_slug($context);
                    break;
                case 'permalink':
                    $base_data['permalink'] = $product->get_permalink();
                    break;
                case 'date_created':
                    $base_data['date_created'] = wc_rest_prepare_date_response($product->get_date_created($context), false);
                    break;
                case 'date_created_gmt':
                    $base_data['date_created_gmt'] = wc_rest_prepare_date_response($product->get_date_created($context));
                    break;
                case 'date_modified':
                    $base_data['date_modified'] = wc_rest_prepare_date_response($product->get_date_modified($context), false);
                    break;
                case 'date_modified_gmt':
                    $base_data['date_modified_gmt'] = wc_rest_prepare_date_response($product->get_date_modified($context));
                    break;
                case 'type':
                    $base_data['type'] = $product->get_type();
                    break;
                case 'status':
                    $base_data['status'] = $product->get_status($context);
                    break;
                case 'featured':
                    $base_data['featured'] = $product->is_featured();
                    break;
                case 'catalog_visibility':
                    $base_data['catalog_visibility'] = $product->get_catalog_visibility($context);
                    break;
                case 'description':
                    $base_data['description'] = 'view' === $context ? wpautop(do_shortcode($product->get_description())) : $product->get_description($context);
                    break;
                case 'short_description':
                    $base_data['short_description'] = 'view' === $context ? apply_filters('woocommerce_short_description', $product->get_short_description()) : $product->get_short_description($context);
                    break;
                case 'sku':
                    $base_data['sku'] = $product->get_sku($context);
                    break;
                case 'price':
                    $base_data['price'] = $product->get_price($context);
                    break;
                case 'regular_price':
                    $base_data['regular_price'] = $product->get_regular_price($context);
                    break;
                case 'sale_price':
                    $base_data['sale_price'] = $product->get_sale_price($context) ? $product->get_sale_price($context) : '';
                    break;
                case 'date_on_sale_from':
                    $base_data['date_on_sale_from'] = wc_rest_prepare_date_response($product->get_date_on_sale_from($context), false);
                    break;
                case 'date_on_sale_from_gmt':
                    $base_data['date_on_sale_from_gmt'] = wc_rest_prepare_date_response($product->get_date_on_sale_from($context));
                    break;
                case 'date_on_sale_to':
                    $base_data['date_on_sale_to'] = wc_rest_prepare_date_response($product->get_date_on_sale_to($context), false);
                    break;
                case 'date_on_sale_to_gmt':
                    $base_data['date_on_sale_to_gmt'] = wc_rest_prepare_date_response($product->get_date_on_sale_to($context));
                    break;
                case 'on_sale':
                    $base_data['on_sale'] = $product->is_on_sale($context);
                    break;
                case 'purchasable':
                    $base_data['purchasable'] = $product->is_purchasable();
                    break;
                case 'total_sales':
                    $base_data['total_sales'] = $product->get_total_sales($context);
                    break;
                case 'virtual':
                    $base_data['virtual'] = $product->is_virtual();
                    break;
                case 'downloadable':
                    $base_data['downloadable'] = $product->is_downloadable();
                    break;
                case 'downloads':
                    $base_data['downloads'] = $this->get_downloads($product);
                    break;
                case 'download_limit':
                    $base_data['download_limit'] = $product->get_download_limit($context);
                    break;
                case 'download_expiry':
                    $base_data['download_expiry'] = $product->get_download_expiry($context);
                    break;
                case 'external_url':
                    $base_data['external_url'] = $product->is_type('external') ? $product->get_product_url($context) : '';
                    break;
                case 'button_text':
                    $base_data['button_text'] = $product->is_type('external') ? $product->get_button_text($context) : '';
                    break;
                case 'tax_status':
                    $base_data['tax_status'] = $product->get_tax_status($context);
                    break;
                case 'tax_class':
                    $base_data['tax_class'] = $product->get_tax_class($context);
                    break;
                case 'manage_stock':
                    $base_data['manage_stock'] = $product->managing_stock();
                    break;
                case 'stock_quantity':
                    $base_data['stock_quantity'] = $product->get_stock_quantity($context);
                    break;
                case 'in_stock':
                    $base_data['in_stock'] = $product->is_in_stock();
                    break;
                case 'backorders':
                    $base_data['backorders'] = $product->get_backorders($context);
                    break;
                case 'backorders_allowed':
                    $base_data['backorders_allowed'] = $product->backorders_allowed();
                    break;
                case 'backordered':
                    $base_data['backordered'] = $product->is_on_backorder();
                    break;
                case 'low_stock_amount':
                    $base_data['low_stock_amount'] = '' === $product->get_low_stock_amount() ? null : $product->get_low_stock_amount();
                    break;
                case 'sold_individually':
                    $base_data['sold_individually'] = $product->is_sold_individually();
                    break;
                case 'weight':
                    $base_data['weight'] = $product->get_weight($context);
                    break;
                case 'dimensions':
                    $base_data['dimensions'] = array(
                        'length' => $product->get_length($context),
                        'width'  => $product->get_width($context),
                        'height' => $product->get_height($context),
                    );
                    break;
                case 'shipping_required':
                    $base_data['shipping_required'] = $product->needs_shipping();
                    break;
                case 'shipping_taxable':
                    $base_data['shipping_taxable'] = $product->is_shipping_taxable();
                    break;
                case 'shipping_class':
                    $base_data['shipping_class'] = $product->get_shipping_class();
                    break;
                case 'shipping_class_id':
                    $base_data['shipping_class_id'] = $product->get_shipping_class_id($context);
                    break;
                case 'reviews_allowed':
                    $base_data['reviews_allowed'] = $product->get_reviews_allowed($context);
                    break;
                case 'average_rating':
                    $base_data['average_rating'] = 'view' === $context ? wc_format_decimal($product->get_average_rating(), 2) : $product->get_average_rating($context);
                    break;
                case 'rating_count':
                    $base_data['rating_count'] = $product->get_rating_count();
                    break;
                case 'upsell_ids':
                    $base_data['upsell_ids'] = array_map('absint', $product->get_upsell_ids($context));
                    break;
                case 'cross_sell_ids':
                    $base_data['cross_sell_ids'] = array_map('absint', $product->get_cross_sell_ids($context));
                    break;
                case 'parent_id':
                    $base_data['parent_id'] = $product->get_parent_id($context);
                    break;
                case 'purchase_note':
                    $base_data['purchase_note'] = 'view' === $context ? wpautop(do_shortcode(wp_kses_post($product->get_purchase_note()))) : $product->get_purchase_note($context);
                    break;
                case 'categories':
                    $base_data['categories'] = $this->get_taxonomy_terms($product);
                    break;
                case 'tags':
                    $base_data['tags'] = $this->get_taxonomy_terms($product, 'tag');
                    break;
                case 'images':
                    $base_data['images'] = $this->get_images($product);
                    break;
                case 'attributes':
                    $base_data['attributes'] = $this->get_attributes($product);
                    break;
                case 'default_attributes':
                    $base_data['default_attributes'] = $this->get_default_attributes($product);
                    break;
                case 'variations':
                    $base_data['variations'] = array();
                    break;
                case 'grouped_products':
                    $base_data['grouped_products'] = array();
                    break;
                case 'menu_order':
                    $base_data['menu_order'] = $product->get_menu_order($context);
                    break;
                case 'categories_path':
                    $base_data['categories_path'] = $this->get_categories_path($product);
                    break;
            }
        }

        $data = array_merge(
            $base_data,
            $this->fetch_fields_using_getters($product, $context, $fields)
        );

        return $data;
    }

    /**
     * Get the Product's schema, conforming to JSON Schema.
     *
     * @return array
     */
    public function get_item_schema()
    {
        $weight_unit    = get_option('woocommerce_weight_unit');
        $dimension_unit = get_option('woocommerce_dimension_unit');
        $schema         = array(
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => $this->post_type,
            'type'       => 'object',
            'properties' => array(
                'id'                    => array(
                    'description' => __('Unique identifier for the resource.', 'woocommerce'),
                    'type'        => 'integer',
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'name'                  => array(
                    'description' => __('Product name.', 'woocommerce'),
                    'type'        => 'string',
                    'context'     => array('view', 'edit'),
                ),
                'slug'                  => array(
                    'description' => __('Product slug.', 'woocommerce'),
                    'type'        => 'string',
                    'context'     => array('view', 'edit'),
                ),
                'permalink'             => array(
                    'description' => __('Product URL.', 'woocommerce'),
                    'type'        => 'string',
                    'format'      => 'uri',
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'date_created'          => array(
                    'description' => __("The date the product was created, in the site's timezone.", 'woocommerce'),
                    'type'        => 'date-time',
                    'context'     => array('view', 'edit'),
                ),
                'date_created_gmt'      => array(
                    'description' => __('The date the product was created, as GMT.', 'woocommerce'),
                    'type'        => 'date-time',
                    'context'     => array('view', 'edit'),
                ),
                'date_modified'         => array(
                    'description' => __("The date the product was last modified, in the site's timezone.", 'woocommerce'),
                    'type'        => 'date-time',
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'date_modified_gmt'     => array(
                    'description' => __('The date the product was last modified, as GMT.', 'woocommerce'),
                    'type'        => 'date-time',
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'type'                  => array(
                    'description' => __('Product type.', 'woocommerce'),
                    'type'        => 'string',
                    'default'     => 'simple',
                    'enum'        => array_keys(wc_get_product_types()),
                    'context'     => array('view', 'edit'),
                ),
                'status'                => array(
                    'description' => __('Product status (post status).', 'woocommerce'),
                    'type'        => 'string',
                    'default'     => 'publish',
                    'enum'        => array_merge(array_keys(get_post_statuses()), array('future')),
                    'context'     => array('view', 'edit'),
                ),
                'featured'              => array(
                    'description' => __('Featured product.', 'woocommerce'),
                    'type'        => 'boolean',
                    'default'     => false,
                    'context'     => array('view', 'edit'),
                ),
                'catalog_visibility'    => array(
                    'description' => __('Catalog visibility.', 'woocommerce'),
                    'type'        => 'string',
                    'default'     => 'visible',
                    'enum'        => array('visible', 'catalog', 'search', 'hidden'),
                    'context'     => array('view', 'edit'),
                ),
                'description'           => array(
                    'description' => __('Product description.', 'woocommerce'),
                    'type'        => 'string',
                    'context'     => array('view', 'edit'),
                ),
                'short_description'     => array(
                    'description' => __('Product short description.', 'woocommerce'),
                    'type'        => 'string',
                    'context'     => array('view', 'edit'),
                ),
                'sku'                   => array(
                    'description' => __('Unique identifier.', 'woocommerce'),
                    'type'        => 'string',
                    'context'     => array('view', 'edit'),
                ),
                'price'                 => array(
                    'description' => __('Current product price.', 'woocommerce'),
                    'type'        => 'string',
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'regular_price'         => array(
                    'description' => __('Product regular price.', 'woocommerce'),
                    'type'        => 'string',
                    'context'     => array('view', 'edit'),
                ),
                'sale_price'            => array(
                    'description' => __('Product sale price.', 'woocommerce'),
                    'type'        => 'string',
                    'context'     => array('view', 'edit'),
                ),
                'date_on_sale_from'     => array(
                    'description' => __("Start date of sale price, in the site's timezone.", 'woocommerce'),
                    'type'        => 'date-time',
                    'context'     => array('view', 'edit'),
                ),
                'date_on_sale_from_gmt' => array(
                    'description' => __('Start date of sale price, as GMT.', 'woocommerce'),
                    'type'        => 'date-time',
                    'context'     => array('view', 'edit'),
                ),
                'date_on_sale_to'       => array(
                    'description' => __("End date of sale price, in the site's timezone.", 'woocommerce'),
                    'type'        => 'date-time',
                    'context'     => array('view', 'edit'),
                ),
                'date_on_sale_to_gmt'   => array(
                    'description' => __("End date of sale price, in the site's timezone.", 'woocommerce'),
                    'type'        => 'date-time',
                    'context'     => array('view', 'edit'),
                ),
                'price_html'            => array(
                    'description' => __('Price formatted in HTML.', 'woocommerce'),
                    'type'        => 'string',
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'on_sale'               => array(
                    'description' => __('Shows if the product is on sale.', 'woocommerce'),
                    'type'        => 'boolean',
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'purchasable'           => array(
                    'description' => __('Shows if the product can be bought.', 'woocommerce'),
                    'type'        => 'boolean',
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'total_sales'           => array(
                    'description' => __('Amount of sales.', 'woocommerce'),
                    'type'        => 'integer',
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'virtual'               => array(
                    'description' => __('If the product is virtual.', 'woocommerce'),
                    'type'        => 'boolean',
                    'default'     => false,
                    'context'     => array('view', 'edit'),
                ),
                'downloadable'          => array(
                    'description' => __('If the product is downloadable.', 'woocommerce'),
                    'type'        => 'boolean',
                    'default'     => false,
                    'context'     => array('view', 'edit'),
                ),
                'downloads'             => array(
                    'description' => __('List of downloadable files.', 'woocommerce'),
                    'type'        => 'array',
                    'context'     => array('view', 'edit'),
                    'items'       => array(
                        'type'       => 'object',
                        'properties' => array(
                            'id'   => array(
                                'description' => __('File ID.', 'woocommerce'),
                                'type'        => 'string',
                                'context'     => array('view', 'edit'),
                            ),
                            'name' => array(
                                'description' => __('File name.', 'woocommerce'),
                                'type'        => 'string',
                                'context'     => array('view', 'edit'),
                            ),
                            'file' => array(
                                'description' => __('File URL.', 'woocommerce'),
                                'type'        => 'string',
                                'context'     => array('view', 'edit'),
                            ),
                        ),
                    ),
                ),
                'download_limit'        => array(
                    'description' => __('Number of times downloadable files can be downloaded after purchase.', 'woocommerce'),
                    'type'        => 'integer',
                    'default'     => -1,
                    'context'     => array('view', 'edit'),
                ),
                'download_expiry'       => array(
                    'description' => __('Number of days until access to downloadable files expires.', 'woocommerce'),
                    'type'        => 'integer',
                    'default'     => -1,
                    'context'     => array('view', 'edit'),
                ),
                'external_url'          => array(
                    'description' => __('Product external URL. Only for external products.', 'woocommerce'),
                    'type'        => 'string',
                    'format'      => 'uri',
                    'context'     => array('view', 'edit'),
                ),
                'button_text'           => array(
                    'description' => __('Product external button text. Only for external products.', 'woocommerce'),
                    'type'        => 'string',
                    'context'     => array('view', 'edit'),
                ),
                'tax_status'            => array(
                    'description' => __('Tax status.', 'woocommerce'),
                    'type'        => 'string',
                    'default'     => 'taxable',
                    'enum'        => array('taxable', 'shipping', 'none'),
                    'context'     => array('view', 'edit'),
                ),
                'tax_class'             => array(
                    'description' => __('Tax class.', 'woocommerce'),
                    'type'        => 'string',
                    'context'     => array('view', 'edit'),
                ),
                'manage_stock'          => array(
                    'description' => __('Stock management at product level.', 'woocommerce'),
                    'type'        => 'boolean',
                    'default'     => false,
                    'context'     => array('view', 'edit'),
                ),
                'stock_quantity'        => array(
                    'description' => __('Stock quantity.', 'woocommerce'),
                    'type'        => 'integer',
                    'context'     => array('view', 'edit'),
                ),
                'stock_status'          => array(
                    'description' => __('Controls the stock status of the product.', 'woocommerce'),
                    'type'        => 'string',
                    'default'     => 'instock',
                    'enum'        => array_keys(wc_get_product_stock_status_options()),
                    'context'     => array('view', 'edit'),
                ),
                'backorders'            => array(
                    'description' => __('If managing stock, this controls if backorders are allowed.', 'woocommerce'),
                    'type'        => 'string',
                    'default'     => 'no',
                    'enum'        => array('no', 'notify', 'yes'),
                    'context'     => array('view', 'edit'),
                ),
                'backorders_allowed'    => array(
                    'description' => __('Shows if backorders are allowed.', 'woocommerce'),
                    'type'        => 'boolean',
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'backordered'           => array(
                    'description' => __('Shows if the product is on backordered.', 'woocommerce'),
                    'type'        => 'boolean',
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'low_stock_amount'       => array(
                    'description' => __('Low Stock amount for the product.', 'woocommerce'),
                    'type'        => array('integer', 'null'),
                    'context'     => array('view', 'edit'),
                ),
                'sold_individually'     => array(
                    'description' => __('Allow one item to be bought in a single order.', 'woocommerce'),
                    'type'        => 'boolean',
                    'default'     => false,
                    'context'     => array('view', 'edit'),
                ),
                'weight'                => array(
                    /* translators: %s: weight unit */
                    'description' => sprintf(__('Product weight (%s).', 'woocommerce'), $weight_unit),
                    'type'        => 'string',
                    'context'     => array('view', 'edit'),
                ),
                'dimensions'            => array(
                    'description' => __('Product dimensions.', 'woocommerce'),
                    'type'        => 'object',
                    'context'     => array('view', 'edit'),
                    'properties'  => array(
                        'length' => array(
                            /* translators: %s: dimension unit */
                            'description' => sprintf(__('Product length (%s).', 'woocommerce'), $dimension_unit),
                            'type'        => 'string',
                            'context'     => array('view', 'edit'),
                        ),
                        'width'  => array(
                            /* translators: %s: dimension unit */
                            'description' => sprintf(__('Product width (%s).', 'woocommerce'), $dimension_unit),
                            'type'        => 'string',
                            'context'     => array('view', 'edit'),
                        ),
                        'height' => array(
                            /* translators: %s: dimension unit */
                            'description' => sprintf(__('Product height (%s).', 'woocommerce'), $dimension_unit),
                            'type'        => 'string',
                            'context'     => array('view', 'edit'),
                        ),
                    ),
                ),
                'shipping_required'     => array(
                    'description' => __('Shows if the product need to be shipped.', 'woocommerce'),
                    'type'        => 'boolean',
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'shipping_taxable'      => array(
                    'description' => __('Shows whether or not the product shipping is taxable.', 'woocommerce'),
                    'type'        => 'boolean',
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'shipping_class'        => array(
                    'description' => __('Shipping class slug.', 'woocommerce'),
                    'type'        => 'string',
                    'context'     => array('view', 'edit'),
                ),
                'shipping_class_id'     => array(
                    'description' => __('Shipping class ID.', 'woocommerce'),
                    'type'        => 'string',
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'reviews_allowed'       => array(
                    'description' => __('Allow reviews.', 'woocommerce'),
                    'type'        => 'boolean',
                    'default'     => true,
                    'context'     => array('view', 'edit'),
                ),
                'average_rating'        => array(
                    'description' => __('Reviews average rating.', 'woocommerce'),
                    'type'        => 'string',
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'rating_count'          => array(
                    'description' => __('Amount of reviews that the product have.', 'woocommerce'),
                    'type'        => 'integer',
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'related_ids'           => array(
                    'description' => __('List of related products IDs.', 'woocommerce'),
                    'type'        => 'array',
                    'items'       => array(
                        'type' => 'integer',
                    ),
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'upsell_ids'            => array(
                    'description' => __('List of up-sell products IDs.', 'woocommerce'),
                    'type'        => 'array',
                    'items'       => array(
                        'type' => 'integer',
                    ),
                    'context'     => array('view', 'edit'),
                ),
                'cross_sell_ids'        => array(
                    'description' => __('List of cross-sell products IDs.', 'woocommerce'),
                    'type'        => 'array',
                    'items'       => array(
                        'type' => 'integer',
                    ),
                    'context'     => array('view', 'edit'),
                ),
                'parent_id'             => array(
                    'description' => __('Product parent ID.', 'woocommerce'),
                    'type'        => 'integer',
                    'context'     => array('view', 'edit'),
                ),
                'purchase_note'         => array(
                    'description' => __('Optional note to send the customer after purchase.', 'woocommerce'),
                    'type'        => 'string',
                    'context'     => array('view', 'edit'),
                ),
                'categories'            => array(
                    'description' => __('List of categories.', 'woocommerce'),
                    'type'        => 'array',
                    'context'     => array('view', 'edit'),
                    'items'       => array(
                        'type'       => 'object',
                        'properties' => array(
                            'id'   => array(
                                'description' => __('Category ID.', 'woocommerce'),
                                'type'        => 'integer',
                                'context'     => array('view', 'edit'),
                            ),
                            'name' => array(
                                'description' => __('Category name.', 'woocommerce'),
                                'type'        => 'string',
                                'context'     => array('view', 'edit'),
                                'readonly'    => true,
                            ),
                            'slug' => array(
                                'description' => __('Category slug.', 'woocommerce'),
                                'type'        => 'string',
                                'context'     => array('view', 'edit'),
                                'readonly'    => true,
                            ),
                        ),
                    ),
                ),
                'tags'                  => array(
                    'description' => __('List of tags.', 'woocommerce'),
                    'type'        => 'array',
                    'context'     => array('view', 'edit'),
                    'items'       => array(
                        'type'       => 'object',
                        'properties' => array(
                            'id'   => array(
                                'description' => __('Tag ID.', 'woocommerce'),
                                'type'        => 'integer',
                                'context'     => array('view', 'edit'),
                            ),
                            'name' => array(
                                'description' => __('Tag name.', 'woocommerce'),
                                'type'        => 'string',
                                'context'     => array('view', 'edit'),
                                'readonly'    => true,
                            ),
                            'slug' => array(
                                'description' => __('Tag slug.', 'woocommerce'),
                                'type'        => 'string',
                                'context'     => array('view', 'edit'),
                                'readonly'    => true,
                            ),
                        ),
                    ),
                ),
                'images'                => array(
                    'description' => __('List of images.', 'woocommerce'),
                    'type'        => 'array',
                    'context'     => array('view', 'edit'),
                    'items'       => array(
                        'type'       => 'object',
                        'properties' => array(
                            'id'                => array(
                                'description' => __('Image ID.', 'woocommerce'),
                                'type'        => 'integer',
                                'context'     => array('view', 'edit'),
                            ),
                            'date_created'      => array(
                                'description' => __("The date the image was created, in the site's timezone.", 'woocommerce'),
                                'type'        => 'date-time',
                                'context'     => array('view', 'edit'),
                                'readonly'    => true,
                            ),
                            'date_created_gmt'  => array(
                                'description' => __('The date the image was created, as GMT.', 'woocommerce'),
                                'type'        => 'date-time',
                                'context'     => array('view', 'edit'),
                                'readonly'    => true,
                            ),
                            'date_modified'     => array(
                                'description' => __("The date the image was last modified, in the site's timezone.", 'woocommerce'),
                                'type'        => 'date-time',
                                'context'     => array('view', 'edit'),
                                'readonly'    => true,
                            ),
                            'date_modified_gmt' => array(
                                'description' => __('The date the image was last modified, as GMT.', 'woocommerce'),
                                'type'        => 'date-time',
                                'context'     => array('view', 'edit'),
                                'readonly'    => true,
                            ),
                            'src'               => array(
                                'description' => __('Image URL.', 'woocommerce'),
                                'type'        => 'string',
                                'format'      => 'uri',
                                'context'     => array('view', 'edit'),
                            ),
                            'name'              => array(
                                'description' => __('Image name.', 'woocommerce'),
                                'type'        => 'string',
                                'context'     => array('view', 'edit'),
                            ),
                            'alt'               => array(
                                'description' => __('Image alternative text.', 'woocommerce'),
                                'type'        => 'string',
                                'context'     => array('view', 'edit'),
                            ),
                        ),
                    ),
                ),
                'has_options'     => array(
                    'description' => __('Shows if the product needs to be configured before it can be bought.', 'woocommerce'),
                    'type'        => 'boolean',
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'attributes'            => array(
                    'description' => __('List of attributes.', 'woocommerce'),
                    'type'        => 'array',
                    'context'     => array('view', 'edit'),
                    'items'       => array(
                        'type'       => 'object',
                        'properties' => array(
                            'id'        => array(
                                'description' => __('Attribute ID.', 'woocommerce'),
                                'type'        => 'integer',
                                'context'     => array('view', 'edit'),
                            ),
                            'name'      => array(
                                'description' => __('Attribute name.', 'woocommerce'),
                                'type'        => 'string',
                                'context'     => array('view', 'edit'),
                            ),
                            'position'  => array(
                                'description' => __('Attribute position.', 'woocommerce'),
                                'type'        => 'integer',
                                'context'     => array('view', 'edit'),
                            ),
                            'visible'   => array(
                                'description' => __("Define if the attribute is visible on the \"Additional information\" tab in the product's page.", 'woocommerce'),
                                'type'        => 'boolean',
                                'default'     => false,
                                'context'     => array('view', 'edit'),
                            ),
                            'variation' => array(
                                'description' => __('Define if the attribute can be used as variation.', 'woocommerce'),
                                'type'        => 'boolean',
                                'default'     => false,
                                'context'     => array('view', 'edit'),
                            ),
                            'options'   => array(
                                'description' => __('List of available term names of the attribute.', 'woocommerce'),
                                'type'        => 'array',
                                'items'       => array(
                                    'type' => 'string',
                                ),
                                'context'     => array('view', 'edit'),
                            ),
                        ),
                    ),
                ),
                'default_attributes'    => array(
                    'description' => __('Defaults variation attributes.', 'woocommerce'),
                    'type'        => 'array',
                    'context'     => array('view', 'edit'),
                    'items'       => array(
                        'type'       => 'object',
                        'properties' => array(
                            'id'     => array(
                                'description' => __('Attribute ID.', 'woocommerce'),
                                'type'        => 'integer',
                                'context'     => array('view', 'edit'),
                            ),
                            'name'   => array(
                                'description' => __('Attribute name.', 'woocommerce'),
                                'type'        => 'string',
                                'context'     => array('view', 'edit'),
                            ),
                            'option' => array(
                                'description' => __('Selected attribute term name.', 'woocommerce'),
                                'type'        => 'string',
                                'context'     => array('view', 'edit'),
                            ),
                        ),
                    ),
                ),
                'variations'            => array(
                    'description' => __('List of variations IDs.', 'woocommerce'),
                    'type'        => 'array',
                    'context'     => array('view', 'edit'),
                    'items'       => array(
                        'type' => 'integer',
                    ),
                    'readonly'    => true,
                ),
                'grouped_products'      => array(
                    'description' => __('List of grouped products ID.', 'woocommerce'),
                    'type'        => 'array',
                    'items'       => array(
                        'type' => 'integer',
                    ),
                    'context'     => array('view', 'edit'),
                    'readonly'    => true,
                ),
                'menu_order'            => array(
                    'description' => __('Menu order, used to custom sort products.', 'woocommerce'),
                    'type'        => 'integer',
                    'context'     => array('view', 'edit'),
                ),
                'meta_data'             => array(
                    'description' => __('Meta data.', 'woocommerce'),
                    'type'        => 'array',
                    'context'     => array('view', 'edit'),
                    'items'       => array(
                        'type'       => 'object',
                        'properties' => array(
                            'id'    => array(
                                'description' => __('Meta ID.', 'woocommerce'),
                                'type'        => 'integer',
                                'context'     => array('view', 'edit'),
                                'readonly'    => true,
                            ),
                            'key'   => array(
                                'description' => __('Meta key.', 'woocommerce'),
                                'type'        => 'string',
                                'context'     => array('view', 'edit'),
                            ),
                            'value' => array(
                                'description' => __('Meta value.', 'woocommerce'),
                                'type'        => 'mixed',
                                'context'     => array('view', 'edit'),
                            ),
                        ),
                    ),
                ),
                'categories_path'            => array(
                    'description' => __('Test', 'woocommerce'),
                    'type'        => 'string',
                    'context'     => array('view', 'edit'),
                ),
            ),
        );
        return $this->add_additional_fields_schema($schema);
    }
}
