<?php

namespace WGU\Controllers;

class WC_REST_Orders_Controller_Upgrade extends \WC_REST_Orders_Controller
{
  public function register_routes()
  {
    register_rest_route($this->namespace, '/' . $this->rest_base, array(
      array(
        'methods'             => \WP_REST_Server::READABLE,
        'callback'            => array($this, 'get_items'),
        'permission_callback' => array($this, 'get_items_permissions_check'),
        'args'                => $this->get_collection_params(),
      ),
      array(
        'methods'             => \WP_REST_Server::CREATABLE,
        'callback'            => array($this, 'create_item'),
        'permission_callback' => '__return_true',
        'args'                => array_merge($this->get_endpoint_args_for_item_schema(\WP_REST_Server::CREATABLE), array(
          'email' => array(
            'required' => true,
            'type'     => 'string',
            'description' => __('New user email address.', 'woocommerce'),
          ),
          'username' => array(
            'required' => 'no' === get_option('woocommerce_registration_generate_username', 'yes'),
            'description' => __('New user username.', 'woocommerce'),
            'type'     => 'string',
          ),
          'password' => array(
            'required' => 'no' === get_option('woocommerce_registration_generate_password', 'no'),
            'description' => __('New user password.', 'woocommerce'),
            'type'     => 'string',
          ),
        )),
      ),
      'schema' => array($this, 'get_public_item_schema'),
    ));

    register_rest_route($this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)', array(
      'args' => array(
        'id' => array(
          'description' => __('Unique identifier for the resource.', 'woocommerce'),
          'type'        => 'integer',
        ),
      ),
      array(
        'methods'             => \WP_REST_Server::READABLE,
        'callback'            => array($this, 'get_item'),
        'permission_callback' => array($this, 'get_item_permissions_check'),
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
            'default'     => false,
            'type'        => 'boolean',
            'description' => __('Required to be true, as resource does not support trashing.', 'woocommerce'),
          ),
          'reassign' => array(
            'default'     => 0,
            'type'        => 'integer',
            'description' => __('ID to reassign posts to.', 'woocommerce'),
          ),
        ),
      ),
      'schema' => array($this, 'get_public_item_schema'),
    ));

    register_rest_route($this->namespace, '/' . $this->rest_base . '/batch', array(
      array(
        'methods'             => \WP_REST_Server::EDITABLE,
        'callback'            => array($this, 'batch_items'),
        'permission_callback' => array($this, 'batch_items_permissions_check'),
        'args'                => $this->get_endpoint_args_for_item_schema(\WP_REST_Server::EDITABLE),
      ),
      'schema' => array($this, 'get_public_batch_schema'),
    ));
  }
}
