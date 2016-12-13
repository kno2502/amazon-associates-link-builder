<?php

/*
Copyright 2016-2016 Amazon.com, Inc. or its affiliates. All Rights Reserved.

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

  public function __construct() {
    $this->paapi_helper = new Aalb_Paapi_Helper();
    $this->remote_loader = new Aalb_Remote_Loader();
    $this->tracking_api_helper = new Aalb_Tracking_Api_Helper();
    add_action('admin_notices', array($this, 'aalb_plugin_activation')) ;
  }

  /**
   * Show warning message if the AWS Credentials are not yet set upon activation
   *
   * @since    1.0.0
   */
  public function aalb_plugin_activation() {
    if(get_option(AALB_AWS_ACCESS_KEY) == '' or get_option(AALB_AWS_SECRET_KEY) == '') {
      echo "<div class=\"notice notice-error\"><h3>Amazon Associates Link Builder Important Message!</h3><p>Please Note - You need to add your Access Key ID and Secret Access Key in the plugin settings page for adding links to Amazon using Amazon Associates Link Builder plugin.</p></div>";
    }
  }

  /**
   * Adding CSS for post and post-new pages
   *
   * @since    1.0.0
   * @param    string    $hook    The name of the WordPress action that is being registered.
   */
  public function enqueue_styles($hook) {
    if(WP_POST != $hook && WP_POST_NEW != $hook){return;}
    wp_enqueue_style('aalb_basics_css', AALB_BASICS_CSS );
    wp_enqueue_style('aalb_admin_css', AALB_ADMIN_CSS);
    wp_enqueue_style('font_awesome_css', FONT_AWESOME_CSS);
    wp_enqueue_style('thickbox');
  }

  /**
   * Adding JS for post and post-new pages
   *
   * @since    1.0.0
   * @param    string    $hook    The name of the WordPress action that is being registered.
   */
  public function enqueue_scripts($hook) {
    if(WP_POST != $hook && WP_POST_NEW != $hook){return;}
    wp_enqueue_style('thickbox');
    wp_enqueue_script('jquery');
    wp_enqueue_script('handlebars_js', HANDLEBARS_JS);
    wp_enqueue_script('aalb_sha2_js', AALB_SHA2_JS);

    wp_enqueue_script('aalb_admin_js', AALB_ADMIN_JS, array('handlebars_js', 'jquery', 'aalb_sha2_js'));
    wp_enqueue_style('thickbox');
    wp_localize_script('aalb_admin_js', 'api_pref', $this->get_paapi_pref());
  }

  /**
   * Returns data to be localized in the script.
   * Makes the variable values in PHP to be used in Javascript.
   *
   * @since     1.0.0
   * @return    array    Data to be localized in the script
   */
  private function get_paapi_pref() {
    return array(
      'template_url' => AALB_ADMIN_ITEM_SEARCH_ITEMS_URL,
      'max_search_result_items' => AALB_MAX_SEARCH_RESULT_ITEMS,
      'store_id' => get_option(AALB_DEFAULT_STORE_ID),
      'marketplace' => get_option(AALB_DEFAULT_MARKETPLACE),
      'ajax_url' => admin_url('admin-ajax.php'),
      'action' => 'get_item_search_result',
      'item_search_nonce' => wp_create_nonce('aalb-item-search-nonce'),
      'AALB_SHORTCODE_AMAZON_LINK' => AALB_SHORTCODE_AMAZON_LINK
    );
  }

  /**
   * Prints the aalb-admin sidebar search box.
   *
   * @since    1.0.0
   * @param    WP_Post    $post    The object for the current post/page.
   */
  function admin_display_callback($post) {
    require_once(AALB_META_BOX_PARTIAL);
  }

  /**
   * Asin button in text editor for putting the shortcode template
   *
   * @since    1.0.0
   */
  function add_quicktags() {
    if (wp_script_is('quicktags')){
    ?>
      <script type="text/javascript">
        QTags.addButton( 'aalb_asin_button', 'asins', '[amazon_link asins="" template="" marketplace="" link_id=""]', '', '', 'Amazon Link');
      </script>
    <?php
    }
  }

  /**
   * Supports the ajax request for item search.
   *
   * @since    1.0.0
   */
  public function get_item_search_result() {
    $nonce = $_GET['item_search_nonce'];

    //verify the user making the request.
    if(!wp_verify_nonce($nonce, 'aalb-item-search-nonce')) {
      die('Not authorised to make a request');
    }

    //Only allow users who can edit post to make the request.
    if(current_user_can('edit_posts')) {
      $url = $this->paapi_helper->get_item_search_url($_GET['keywords'], $_GET['marketplace'], $_GET['store_id']);
      try{
        echo $this->remote_loader->load($url);
      } catch(Exception $e){
        echo $this->paapi_helper->get_error_message($e->getMessage());
      }
    }

    wp_die();
  }

  /**
   * Supports the ajax request for get link id API
   *
   * @since    1.0.0
   */
  public function get_link_code() {

    $shortcode_params_json_string = $_POST['shortcode_params'];
    $shortcode_name = $_POST['shortcode_name'];

    echo $this->tracking_api_helper->get_link_id($shortcode_name, $shortcode_params_json_string);
    wp_die();
  }
}
?>
