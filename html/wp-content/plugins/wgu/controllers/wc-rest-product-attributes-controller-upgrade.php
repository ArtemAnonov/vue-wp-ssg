<?php

namespace WGU\Controllers;

class WC_REST_Product_Attributes_Controller_Upgrade extends \WC_REST_Product_Attributes_Controller
{

	public function register_routes()
	{
		register_rest_route(
			$this->namespace,
			'/' . $this->rest_base,
			array(
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array($this, 'get_items'),
					'permission_callback' => '__return_true',
					'args'                => $this->get_collection_params(),
				),
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array($this, 'create_item'),
					'permission_callback' => array($this, 'create_item_permissions_check'),
					'args'                => array_merge(
						$this->get_endpoint_args_for_item_schema(\WP_REST_Server::CREATABLE),
						array(
							'name' => array(
								'description' => __('Name for the resource.', 'woocommerce'),
								'type'        => 'string',
								'required'    => true,
							),
						)
					),
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
						'context' => $this->get_context_param(array('default' => 'view')),
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
							'default'     => true,
							'type'        => 'boolean',
							'description' => __('Required to be true, as resource does not support trashing.', 'woocommerce'),
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
}
