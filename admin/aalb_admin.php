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
 * The class responsible for handling all the functionalities in the admin area.
 * Enqueues the styles and scripts for post.php and post-new.php.
 * Fetches the marketplace endpoints from external json file.
 * Handles UI in the admin area by providing a meta box and an asin button in the html text editor.
 *
 * @since      1.0.0
 * @package    AmazonAssociatesLinkBuilder
 * @subpackage AmazonAssociatesLinkBuilder/admin
 */
class Aalb_Admin {

    private $paapi_helper;
    private $remote_loader;
    private $tracking_api_helper;
    private $helper;
    private $compatibility_helper;

    public function __construct() {
        $this->compatibility_helper = new Aalb_Compatibility_Helper();
        $this->paapi_helper = new Aalb_Paapi_Helper();
        $this->remote_loader = new Aalb_Remote_Loader();
        $this->tracking_api_helper = new Aalb_Tracking_Api_Helper();
        $this->helper = new Aalb_Helper();
    }

    /**
     * Checks whether PA-API Credentials are set
     *
     * @since 1.4.5
     * @return boolean true if PA-API credentials are set
     */
    public function aalb_is_paapi_credentials_set() {
        return !( get_option( AALB_AWS_ACCESS_KEY ) == '' or get_option( AALB_AWS_SECRET_KEY ) == '' );
    }

    /**
     * Enqueue CSS classes
     *
     * @since 1.4.6
     *
     */
    public function aalb_enqueue_styles() {
        wp_enqueue_style( 'aalb_basics_css', AALB_BASICS_CSS, array(), AALB_PLUGIN_CURRENT_VERSION );
        wp_enqueue_style( 'aalb_admin_css', AALB_ADMIN_CSS, array(), AALB_PLUGIN_CURRENT_VERSION );
        wp_enqueue_style( 'font_awesome_css', FONT_AWESOME_CSS );
        wp_enqueue_style( 'thickbox' );
    }

    /**
     * Enqueue JS files
     *
     * @since 1.4.6
     *
     */
    public function aalb_enqueue_scripts() {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'handlebars_js', HANDLEBARS_JS );
        wp_enqueue_script( 'aalb_sha2_js', AALB_SHA2_JS, array(), AALB_PLUGIN_CURRENT_VERSION );
        wp_enqueue_script( 'aalb_admin_js', AALB_ADMIN_JS, array( 'handlebars_js', 'jquery', 'aalb_sha2_js' ), AALB_PLUGIN_CURRENT_VERSION );
        wp_localize_script( 'aalb_admin_js', 'api_pref', $this->get_paapi_pref() );
        wp_localize_script( 'aalb_admin_js', 'aalb_strings', $this->get_aalb_strings() );
    }

    /**
     * Returns data to be localized in the script.
     * Makes the variable values in PHP to be used in Javascript.
     *
     * @since 1.0.0
     * @return array Data to be localized in the script
     */
    private function get_paapi_pref() {
        return array(
            'template_url' => AALB_ADMIN_ITEM_SEARCH_ITEMS_URL,
            'max_search_result_items' => AALB_MAX_SEARCH_RESULT_ITEMS,
            'store_id' => get_option( AALB_DEFAULT_STORE_ID ),
            'marketplace' => get_option( AALB_DEFAULT_MARKETPLACE ),
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'action' => 'get_item_search_result',
            'item_search_nonce' => wp_create_nonce( 'aalb-item-search-nonce' ),
            'AALB_SHORTCODE_AMAZON_LINK' => AALB_SHORTCODE_AMAZON_LINK,
            'AALB_SHORTCODE_AMAZON_TEXT' => AALB_SHORTCODE_AMAZON_TEXT,
            'IS_PAAPI_CREDENTIALS_SET' =>  $this->aalb_is_paapi_credentials_set()
        );
    }

    /**
     * Returns constant strings to be used in aalb_admin.js
     * Makes the variable values in PHP to be used in Javascript.
     *
     * @since 1.4.4
     * @return array Data to be localized in the script
     */
    private function get_aalb_strings() {
        return array(
            "template_asin_error"       => "Only one product can be selected for template",
            "no_asin_selected_error"    => "Please select at least one product for display",
            "empty_product_search_bar"  => "Please Enter a Product Name ",
            "short_code_create_failure" => "Failed to create Text Link shortcode. Editor has some text selected. Only one item can be selected while adding text links"
        );
    }

    /**
     * Checks if the plugin has been updated and calls required method
     *
     * @since 1.3
     */
    public function check_update() {
        if ( AALB_PLUGIN_CURRENT_VERSION !== get_option( AALB_PLUGIN_VERSION ) ) {
            $this->handle_plugin_update();
        }
    }

    /**
     * Block which runs whenever the plugin has been updated.
     * Refreshes the templates
     *
     * @since 1.3
     */
    public function handle_plugin_update() {
        if( $this->compatibility_helper->is_plugin_compatible() ) {
            //Clear all transients for price changes to reflect
            $this->helper->clear_cache_for_substring( '' );
            $this->helper->clear_expired_transients();

            global $wp_filesystem;
            $this->helper->aalb_initialize_wp_filesystem_api();
            $this->helper->refresh_template_list();
            update_option( AALB_PLUGIN_VERSION, AALB_PLUGIN_CURRENT_VERSION );
        } else {
            $this->compatibility_helper->aalb_deactivate();
        }
    }

    /**
     * Prints Search box to be displayed in Editor where user can type in keywords for search. @see aalb_editor_search_box.php
     * This callback is attached with "media_buttons" hook of wordpress. @see aalb_manager::add_admin_hooks()
     *
     * @since 1.4.3 Only prints search box displayed in editor.
     * @since 1.0.0 Prints the aalb-admin sidebar search box.
     */
    function admin_display_callback() {
        require( AALB_EDITOR_SEARCH_BOX );
    }

    /**
     * Prints  Popup box of the plugin used to create shortcode. @see aalb_meta_box.php
     * This callback is attached with "admin_footer" hook of wordpress. @see aalb_manager::add_admin_hooks()
     *
     * @since 1.4.3
     *
     */
    function admin_footer_callback() {
        require_once( AALB_META_BOX_PARTIAL );
    }

    /**
     * Asin button in text editor for putting the shortcode template
     *
     * @since 1.0.0
     */
    function add_quicktags() {
        if ( wp_script_is( 'quicktags' ) ) {
            ?>
            <script type="text/javascript">
                QTags.addButton( 'aalb_asin_button', 'asins', '[amazon_link asins="" template="" marketplace="" link_id=""]', '', '', 'Amazon Link' );
            </script>
            <?php
        }
    }

    /**
     * Supports the ajax request for item search.
     *
     * @since 1.0.0
     */
    public function get_item_search_result() {
        $nonce = $_GET['item_search_nonce'];

        //verify the user making the request.
        if ( ! wp_verify_nonce( $nonce, 'aalb-item-search-nonce' ) ) {
            die( 'Not authorised to make a request' );
        }

        //Only allow users who can edit post to make the request.
        if ( current_user_can( 'edit_posts' ) ) {
            $url = $this->paapi_helper->get_item_search_url( $_GET['keywords'], $_GET['marketplace'], $_GET['store_id'] );
            try {
                echo $this->remote_loader->load( $url );
            } catch ( Exception $e ) {
                echo $this->paapi_helper->get_error_message( $e->getMessage() );
            }
        }

        wp_die();
    }

    /**
     * Supports the ajax request for get link id API
     *
     * @since 1.0.0
     */
    public function get_link_code() {

        $shortcode_params_json_string = $_POST['shortcode_params'];
        $shortcode_name = $_POST['shortcode_name'];

        echo $this->tracking_api_helper->get_link_id( $shortcode_name, $shortcode_params_json_string );
        wp_die();
    }

    /**
     * Supports the ajax request for getting template contents for custom templates
     *
     * @since 1.3
     */
    public function get_custom_template_content() {
        global $wp_filesystem;
        $this->helper->aalb_initialize_wp_filesystem_api();
        $base_path = $this->helper->get_template_upload_directory();
        if ( current_user_can( 'edit_posts' ) ) {
            $css_file = $_POST['css'];
            $real_css_file = realpath( $css_file );
            $mustache_file = $_POST['mustache'];
            $real_mustache_file = realpath( $mustache_file );
            if ( $real_css_file === false || $real_mustache_file === false || strpos( $real_css_file, $base_path ) !== 0 || strpos( $real_mustache_file, $base_path ) !== 0 ) {
                //If base path is not a prefix of the realpath, this means that a directry traversal was attempted
                die( 'Not authorised to make request template content or Directory Traversal Attempted.' );
            } else {
                //No vulnerability. Get file contents.
                $css_file_content = $wp_filesystem->get_contents( $css_file );
                $mustache_file_content = $wp_filesystem->get_contents( $mustache_file );

                $response = array( "css" => $css_file_content, "mustache" => $mustache_file_content );
                echo json_encode( $response );
            }
        } else {
            die( 'Not authorised to make request' );
        }
        wp_die();
    }
}

?>