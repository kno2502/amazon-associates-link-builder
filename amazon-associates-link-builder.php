<?php
/**
 * @package AmazonAssociatesLinkBuilder
 *
 */

/*
Plugin Name: Amazon Associates Link Builder
Description: Amazon Associates Link Builder is the official free Amazon Associates Program plugin for WordPress. The plugin enables you to search for products in the Amazon catalog, access real-time price and availability information, and easily create links in your posts to products on Amazon.com. You will be able to generate text links, create custom ad units, or take advantage of out-of-the-box widgets that we’ve designed and included with the plugin.
Version: 1.6.0
Author: Amazon Associates Program
Author URI: https://affiliate-program.amazon.com/
License: GPLv2
Text Domain: amazon-associates-link-builder
Domain Path: /languages/
*/

/*
Copyright 2016-2018 Amazon.com, Inc. or its affiliates. All Rights Reserved.

Licensed under the GNU General Public License as published by the Free Software Foundation,
Version 2.0 (the "License"). You may not use this file except in compliance with the License.
A copy of the License is located in the "license" file accompanying this file.

This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
either express or implied. See the License for the specific language governing permissions
and limitations under the License.
*/

if ( ! defined( 'WPINC' ) ) {
    die;
}

require_once( plugin_dir_path( __FILE__ ) . 'aalb_config.php' );

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'aalb_add_action_links' );
function aalb_add_action_links( $links ) {
    $mylinks = array(
        '<a href="' . admin_url( 'admin.php?page=associates-link-builder-about' ) . '">' . esc_html__( "About", 'amazon-associates-link-builder' ) . '</a>',
        '<a href="' . admin_url( 'admin.php?page=associates-link-builder-settings' ) . '">' . esc_html__( "Settings", 'amazon-associates-link-builder' ) . '</a>',
        '<a href="' . admin_url( 'admin.php?page=associates-link-builder-templates' ) . '">' . esc_html__( "Templates", 'amazon-associates-link-builder' ) . '</a>',
    );

    return array_merge( $links, $mylinks );
}

/**
 * Autoload the files required for the plugin.
 *
 * @since 1.0.0
 */
function aalb_autoload() {
    //Load the autoloader for mustache.
    require_once( MUSTACHE_AUTOLOADER_PHP );
    Mustache_Autoloader::register();

    require_once( AALB_PLUGIN_DIR . 'vendor/autoload.php' );

    //Load the autoloader for plugin files.
    require_once( AALB_AUTOLOADER );
    Aalb_Autoloader::register();
}

aalb_autoload();

register_activation_hook( __FILE__, array( new Aalb_Activator(), 'activate' ) );

/**
 * The code to run on deactivation
 *
 * CAUTION: Any function present here should contain code that is compatible with at least PHP 5.3(even lower if possible) so
 * that anyone not meeting compatibility requirements for min php versions gets deactivated successfully.
 *
 * @since 1.0.0
 */
function aalb_deactivate() {
    $aalb_deactivator = new Aalb_Deactivator();
    $aalb_deactivator->remove_cache();
}

register_deactivation_hook( __FILE__, 'aalb_deactivate' );

/**
 * The code to run on uninstalltion
 *
 * @since 1.0.0
 */
function aalb_uninstall() {
    $aalb_deactivator = new Aalb_Deactivator();
    $aalb_deactivator->remove_settings();
    $aalb_deactivator->remove_uploads_dir();
}

register_uninstall_hook( __FILE__, 'aalb_uninstall' );

/**
 * Execute the plugin
 *
 * @since 1.0.0
 */
function aalb_execute() {
    $aalb_manager = new Aalb_Manager();
    $aalb_manager->execute();
}

add_action( 'plugins_loaded', 'aalb_plugin_load_textdomain' );

/**
 * Adds a text-domain to facilitate translation feature
 * @since 1.4.8
 */
function aalb_plugin_load_textdomain() {
    load_plugin_textdomain( 'amazon-associates-link-builder', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

//To use the function is_plugin_active()
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

/*
 * Minimum WP version supported in these activation, deactivation and updates check: 2.8.0
 * Minimum PHP version supported in these activation, deactivation and updates check: 5.3
 */
if ( is_plugin_active( plugin_basename( AALB_PLUGIN_DIR . 'amazon-associates-link-builder.php' ) ) ) {
    $compatibility_helper = new Aalb_Compatibility_Helper();
    if ( $compatibility_helper->is_plugin_compatible() ) {
        //All functions to be called from main file should be put inside this check
        aalb_execute();
    } else {
        $compatibility_helper->aalb_deactivate();
    }
}

?>
