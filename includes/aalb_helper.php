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
 * Hepler class for commonly used functions in the plugin.
 *
 * @since      1.0.0
 * @package    AmazonAssociatesLinkBuilder
 * @subpackage AmazonAssociatesLinkBuilder/includes
 */
class Aalb_Helper{

  /**
   * Build key for storing rendered template in cache.
   *
   * @since     1.0.0
   * @param     string    $asins          List of hyphen separated asins.
   * @param     string    $marketplace    Marketplace of the asin to look into.
   * @param     string    $store          The identifier of the store to be used for current adunit
   * @param     string    $template       Template to render the display unit.
   * @return    string                    Template cache key.
   */
  public function build_template_cache_key($asins, $marketplace, $store, $template) {
    return 'aalb' . '-' . $asins . '-' . $marketplace . '-' . $store . '-' . $template;
  }

  /**
   * Build key for storing product information in cache.
   *
   * @since     1.0.0
   * @param     string    $asins          List of hyphen separated asins.
   * @param     string    $marketplace    Marketplace of the asin to look into.
   * @param     string    $store          The identifier of the store to be used for current adunit
   * @return    string                    Products information cache key.
   */
  public function build_products_cache_key($asins, $marketplace, $store) {
    return 'aalb' . '-' . $asins . '-' . $marketplace . '-' . $store;
  }

  /**
   * Clears the cache for the given template name
   *
   * @since    1.0.0
   * @param    string    $template    The template to clear the cache for
   */
  public function clear_cache_for_template($template) {
    $this->clear_cache_for_substring($template);
  }

  /**
   * Clear the cache for keys which contain the given substring
   *
   * @since    1.0.0
   * @param    string    $substring    The subtring which is a part of the keys to be cleared
   */
  public function clear_cache_for_substring($substring) {
    global $wpdb;

    $statement = 'DELETE from wp_options
        WHERE option_name like %s or option_name like %s';
    $transient_timeout_cache = '_transient_timeout_aalb%' . $substring . '%';
    $transient_cache = '_transient_aalb%' . $substring . '%';
    $prepared_statement = $wpdb->prepare($statement, $transient_timeout_cache, $transient_cache);

    try {
      $wpdb->query($prepared_statement);
    } catch(Exception $e) {
      error_log('Unable to clear cache. Query to clear cache for substring ' . $substring . ' failed with the Exception ' . $e->getMessage());
    }
  }

  /**
   * Clear the dead expired transients from cache at intervals
   *
   * @since    1.0.0
   */
  public function clear_expired_transients_at_intervals() {
    $randomNumber = rand(1,50);
    // Clear the expired transients approximately once in 50 requests.
    if($randomNumber == 25) {
      $this->clear_expired_transients();
    }
  }

  /**
   * Clear the dead expired transients from cache
   *
   * @since    1.0.0
   */
  public function clear_expired_transients() {
    global $wpdb;

    $transients_prefix  = esc_sql( "_transient_timeout_aalb%" );
    $sql = $wpdb -> prepare (
      '
        SELECT option_name
        FROM wp_options
        WHERE option_name LIKE %s
      ',
      $transients_prefix
    );
    $transients = $wpdb -> get_col( $sql );
    foreach( $transients as $transient ) {
      // Strip away the WordPress prefix in order to arrive at the transient key.
      $key = str_replace( '_transient_timeout_', '', $transient );
      delete_transient($key);
    }
    wp_cache_flush();
  }

  /**
   * Displays error messages in preview mode only
   *
   * @since    1.0.0
   * @param    string    $error_message    Error message to be displayed
   */
  public function show_error_in_preview($error_message) {
    if (is_preview()) {
      //If it's preview mode
      echo "<br><font color='red'><b>" . $error_message . "</b></font>";
    }
  }

  /**
   * Returns the Store IDs Array.
   * Returns AALB_DEFAULT_STORE_ID_NAME if the nothing is specified.
   *
   * @since    1.0.0
   */
  public function get_store_ids_array(){
    return explode("\r\n", strlen(get_option(AALB_STORE_ID_NAMES))?get_option(AALB_STORE_ID_NAMES):AALB_DEFAULT_STORE_ID_NAME);
  }

  /**
   * Fetches the current plugins version number
   *
   * @since    1.0.0
   * @return string Version number of the plugin
   */
  function get_plugin_version() {
    return AALB_PLUGIN_VERSION;
  }

  /**
   * Fetches the Wordpress version number
   *
   * @since    1.0.0
   * @return string Version number of Wordpress
   */
  function get_wordpress_version() {
    global $wp_version;
    return $wp_version;
  }
}
?>
