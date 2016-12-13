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
 *
 * Registers the shortcode with the wordpress for rendering the product information.
 * Makes only a single instance of Aalb_Shortcode for rendering all the shortcodes.
 *
 * @since      1.0.0
 * @package    AmazonAssociatesLinkBuilder
 * @subpackage AmazonAssociatesLinkBuilder/shortcode
 */
class Aalb_Shortcode_Loader {

  public $shortcode = null;

  /**
   * Create only a single instance of the Aalb Shortcode.
   * No need to create an instance for rendering each shortcode.
   *
   * @since     1.0.0
   * @return    Aalb_Shortcode    The instance of Aalb_Shortcode.
   */ 
  public function get_shortcode() {
    if(is_null($this->shortcode)) {
      return new Aalb_Shortcode();
    }
    return $this->shortcode;
  }

  /**
   * Register shortcode with Wordpress
   * 
   * @since    1.0.0
   */
  public function add_shortcode() {
    add_shortcode(AALB_SHORTCODE_AMAZON_LINK, array($this, 'shortcode_callback'));
  }

  /**
   * Disable shortcode 
   *
   * @since    1.0.0
   */
  public function remove_shortcode() {
    remove_shortcode(AALB_SHORTCODE_AMAZON_LINK);
  }

  /**
   * Callback function for rendering shortcode
   *
   *
   * @since     1.0.0
   * @param     array    $atts     Shortcode attributes and values.
   * @return    HTML               HTML for displaying the templates.
   */
  public function shortcode_callback($atts) {
    return $this->get_shortcode()->render($atts);
  }

}

?>
