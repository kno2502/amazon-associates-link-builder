<?php

/*
Copyright 2016-2017 Amazon.com, Inc. or its affiliates. All Rights Reserved.

Licensed under the GNU General Public License as published by the Free Software Foundation,
Version 2.0 (the "License"). You may not use this file except in compliance with the License.
A copy of the License is located in the "license" file accompanying this file.

This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
either express or implied. See the License for the specific language governing permissions
and limitations under the License.
*/

/**
 * The class that manages all the events of the wordpress.
 *
 * @since      1.0.0
 * @package    AmazonAssociatesLinkBuilder
 * @subpackage AmazonAssociatesLinkBuilder/includes
 */
class Aalb_Manager {

    protected $hook_loader;
    protected $shortcode_loader;

    public function __construct() {
        $this->hook_loader = new Aalb_Hook_Loader();
        $this->shortcode_loader = new Aalb_Shortcode_Loader();

        //add the hooks specific to admin.
        $this->add_admin_hooks();

        //add the hooks for shortcode rendering.
        $this->register_shortcode_hooks();
    }

    /**
     * Add the hooks in the admin section
     *
     * @since 1.0.0
     */
    private function add_admin_hooks() {
        $aalb_admin = new Aalb_Admin();
        $this->hook_loader->add_action( 'admin_print_footer_scripts', $aalb_admin, 'add_quicktags' );
        $this->hook_loader->add_action( 'wp_ajax_get_item_search_result', $aalb_admin, 'get_item_search_result' );
        $this->hook_loader->add_action( 'wp_ajax_get_link_code', $aalb_admin, 'get_link_code' );
        $this->hook_loader->add_action( 'wp_ajax_get_custom_template_content', $aalb_admin, 'get_custom_template_content' );
        $this->hook_loader->add_action( 'media_buttons', $aalb_admin, 'admin_display_callback' );
        $this->hook_loader->add_action( 'admin_footer', $aalb_admin, 'admin_footer_callback' );
        $this->hook_loader->add_action( 'plugins_loaded', $aalb_admin, 'check_update' );

        $aalb_sidebar = new Aalb_Sidebar();
        $this->hook_loader->add_action( 'admin_init', $aalb_sidebar, 'register_cred_config_group' );
        $this->hook_loader->add_action( 'admin_menu', $aalb_sidebar, 'register_sidebar_config_page' );
    }

    /**
     * Add the hooks for the shortcode rendering.
     *
     * @since 1.0.0
     */
    private function register_shortcode_hooks() {
        $aalb_shortcode = $this->shortcode_loader->get_amazon_link_shortcode();
        $aalb_shortcode_text = $this->shortcode_loader->get_amazon_textlink_shortcode();
        $this->hook_loader->add_action( 'wp_enqueue_scripts', $aalb_shortcode, 'enqueue_styles' );
        $this->hook_loader->add_action( 'wp_enqueue_scripts', $aalb_shortcode_text, 'enqueue_styles' );
    }

    /**
     * Execute all the wordpress hooks and shortcodes.
     *
     * @since 1.0.0
     */
    public function execute() {
        $this->hook_loader->execute();
        $this->shortcode_loader->add_shortcode();
        Aalb_Content_Filter::attach();
    }

}

?>
