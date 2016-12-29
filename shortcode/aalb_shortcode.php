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
 * Fired when a shortcode is there in the post page.
 *
 * Gets the product information by making a Paapi request and renders the HTML
 *
 * @since      1.0.0
 * @package    AmazonAssociatesLinkBuilder
 * @subpackage AmazonAssociatesLinkBuilder/shortcode
 */

class Aalb_Shortcode {

  protected $paapi_helper;
  protected $template_engine;
  protected $helper;
  protected $config_loader;
  protected $validation_helper;
  protected $tracking_api_helper;

  public function __construct() {
    $this->template_engine = new Aalb_Template_Engine();
    $this->paapi_helper = new Aalb_Paapi_Helper();
    $this->helper = new Aalb_Helper();
    $this->config_loader = new Aalb_Config_Loader();
    $this->validation_helper = new Aalb_Validation_Helper();
    $this->tracking_api_helper = new Aalb_Tracking_Api_Helper();
  }

  /**
   * Add basic styles
   *
   * @since    1.0.0
   */
  public function enqueue_styles() {
    wp_enqueue_style('aalb_basics_css', AALB_BASICS_CSS );
  }

  /**
   * Add CSS for the template
   *
   * @since    1.0.0
   */
  public function enqueue_template_styles($template_name) {
    $aalb_default_templates = explode(",", AALB_AMAZON_TEMPLATE_NAMES);
    if(in_array($template_name, $aalb_default_templates)) {
      wp_enqueue_style('aalb_template' . $template_name . '_css', AALB_TEMPLATE_URL . $template_name . '.css' );
    } else {
      $aalb_template_upload_url = $this->helper->get_template_upload_directory_url();
      wp_enqueue_style('aalb_template' . $template_name . '_css', $aalb_template_upload_url . $template_name . '.css' );
    }
  }

  /**
   * The function responsible for rendering the shortcode.
   * Makes a GET request and calls the render_xml to render the response.
   *
   * @since     1.0.0
   * @param     array    $atts    Shortcode attribute and values.
   * @return    HTML              Rendered html to display.
   */
  public function render($atts) {
    try {
      $shortcode_attributes = $this->get_shortcode_attributes($atts);

      $validated_link_id = $this->get_validated_link_id($shortcode_attributes['link_id']);
      $validated_marketplace = $this->get_validated_marketplace($shortcode_attributes['marketplace']);
      $validated_asins = $this->get_validated_asins($shortcode_attributes['asins']);
      $validated_template = $this->get_validated_template($shortcode_attributes['template']);
      $validated_store_id = $this->get_validated_store_id($shortcode_attributes['store']);

      $marketplace = $this->get_marketplace_endpoint($validated_marketplace);
      $url = $this->paapi_helper->get_item_lookup_url($validated_asins, $marketplace, $validated_store_id);
      $asins = $this->format_asins($validated_asins);
      $products_key = $this->helper->build_products_cache_key($asins, $marketplace, $validated_store_id);
      $products_template_key = $this->helper->build_template_cache_key($asins, $marketplace, $validated_store_id, $validated_template );

      $impression_params = $this->tracking_api_helper->get_impression_params($validated_link_id, AALB_SHORTCODE_AMAZON_LINK, $shortcode_attributes);
      $click_url = $this->tracking_api_helper->get_click_url($impression_params);
      $this->tracking_api_helper->insert_pixel($impression_params);
      $this->enqueue_template_styles($validated_template);

      return str_replace(array('[[CLICK_URL_PREFIX]]','[[UNIQUE_ID]]'), array($click_url, str_replace('.','-',$products_template_key)), $this->template_engine->render($products_template_key, $products_key, $validated_template, $url, $validated_marketplace));
    } catch (Exception $e) {
        error_log($this->paapi_helper->get_error_message($e->getMessage()));
    }
    
  }

  /**
   * Returns default shortcode attributes if not mentioned
   *
   * @since     1.0.0
   * @param     array    $atts    Shortcode attributes.
   * @return    array             Default shortcode attributes if not mentioned.
   */
  private function get_shortcode_attributes($atts) {
    $shortcode_attributes=shortcode_atts(array(
      'asins' => null,
      'marketplace' => get_option(AALB_DEFAULT_MARKETPLACE),
      'store' => get_option(AALB_DEFAULT_STORE_ID),
      'template' => get_option(AALB_DEFAULT_TEMPLATE),
      'link_id' => null
    ),$atts);
    return $shortcode_attributes;
  }

  /**
   * Format comma separated asins into hypen separated asins for building key.
   * Checks for more spaces and trims it.
   *
   * @since     1.0.0
   * @param     string    $asins    Comma separated asins.
   * @return    string              Hyphen separated asins.
   */
  private function format_asins($asins) {
    return preg_replace('/[ ,]+/', '-', trim($asins));
  }

  /**
   * Get marketplace endpoint for marketplace abbreviation
   *
   * @since     1.0.0
   * @param     string     $marketplace_abbr        Marketplace Abbreviation from shortcode
   * @return    string     $marketplace_endpoint    Marketplace endpoint
   */
  public function get_marketplace_endpoint($marketplace_abbr) {
    $marketplace_endpoint = "";
    $aalb_marketplace_names = $this->config_loader->fetch_marketplaces();
    $marketplace_endpoint = array_search($marketplace_abbr, $aalb_marketplace_names);
    return $marketplace_endpoint;
  }

  /**
   * Get validated link-id
   * Checks if the link id we got from the api is valid or not and returns
   * validated link-id. In case of invalid marketplace, it returns empty string.
   *
   * @since     1.0.0
   * @param     string    $marketplace           Marketplace from shortcode
   * @return    string    $validated_template    Validated marketplace
   */
  public function get_validated_link_id($link_id) {
    $validated_link_id = $link_id;
    if(!$this->validation_helper->validate_link_id($link_id)) {
      //If the link id is not valid, return empty string
      $validated_link_id = '';
    }
    return $validated_link_id;
  }

  /**
   * Get validated marketplace.
   * Checks if a marketplace abbreviation from shortcode is valid and returns
   * validated marketplace. In case of invalid marketplace, it returns default marketplace.
   *
   * @since     1.0.0
   * @param     string    $marketplace           Marketplace from shortcode
   * @return    string    $validated_template    Validated marketplace
   */
  public function get_validated_marketplace($marketplace) {
    //Changing case of the marketplace to upper. Ensures case insensitivity
    $validated_marketplace = strtoupper($marketplace);
    if(!$this->validation_helper->validate_marketplace($marketplace)) {
      //If the marketplace is not valid, return default marketplace
      $validated_marketplace = get_option(AALB_DEFAULT_MARKETPLACE);
    }
    return $validated_marketplace;
  }

  /**
   * Get validated asin list
   * Drops invalid asin from the list
   *
   * @since     1.0.0
   * @param     string    $asins    List of asins from shortcode
   * @return    string              List of validated asins
   */
  public function get_validated_asins($asins) {
    //Creates array of asins in the shortcode
    $asins_array = explode(',', $asins);
    foreach ($asins_array as $asin) {
      if (!$this->validation_helper->validate_asin($asin)) {
        //Drop Invalid ASIN out of list of asins
        $asins_array = array_diff($asins_array, array($asin));
        //Show error message regarding incorrect asin in preview mode only
        $this->helper->show_error_in_preview("The ASIN: '" . $asin . "' is invalid.");
      }
    }
    return implode(',',$asins_array);
  }

  /**
   * Get validated template.
   * Checks if a template is valid, returns default template otherwise
   *
   * @since     1.0.0
   * @param     string    $template              Template name from shortcode
   * @return    string    $validated_template    Validated template name
   */
  public function get_validated_template($template) {
   $validated_template = $template;
   if (!$this->validation_helper->validate_template_name($template)) {
     //Return Default template in case of invalid template name
     $validated_template = get_option(AALB_DEFAULT_TEMPLATE);
     //Show error message regarding incorrect asin in preview mode only
     $this->helper->show_error_in_preview("The template: '" . $template . "' is invalid. Using default template '" . $validated_template . "'.");
   }
   return $validated_template;
  }

  /**
   * Get validated store id.
   * Checks if a store id is valid, returns default store id otherwise
   *
   * @since     1.0.0
   * @param     string    $store_id              Store ID from shortcode
   * @return    string    $validated_store_id    Validated Store ID
   */
  public function get_validated_store_id($store_id) {
   $validated_store_id = $store_id;
   if (!$this->validation_helper->validate_store_id($store_id)) {
     //Return Default store id in case of invalid store id
     $validated_store_id = get_option(AALB_DEFAULT_STORE_ID,AALB_DEFAULT_STORE_ID_NAME);
     //Show error message regarding incorrect asin in preview mode only
     $this->helper->show_error_in_preview("The Associate tag '" . $store_id . "' is not present in the list of valid tags. Associate tag has been updated to '" . $validated_store_id . "'. Please check your Associate tag selection or contact the administrator to add a new tag.");
   }
   return $validated_store_id;
  }

}

?>
