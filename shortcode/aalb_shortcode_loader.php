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
 *
 * Registers the shortcode with the wordpress for rendering the product information.
 * Makes only a single instance of Aalb_Shortcode for rendering all the shortcodes.
 *
 * @since      1.0.0
 * @package    AmazonAssociatesLinkBuilder
 * @subpackage AmazonAssociatesLinkBuilder/shortcode
 */
class Aalb_Shortcode_Loader {

    public $shortcode_link = null;
    public $shortcode_textlink = null;

    /**
     * Register shortcode with Wordpress
     *
     * @since 1.0.0
     */
    public function add_shortcode() {
        add_shortcode( AALB_SHORTCODE_AMAZON_LINK, array( $this, 'amazon_link_shortcode_callback' ) );
        add_shortcode( AALB_SHORTCODE_AMAZON_TEXT, array( $this, 'amazon_textlink_shortcode_callback' ) );
    }

    /**
     * Disable shortcode
     *
     * @since 1.0.0
     */
    public function remove_shortcode() {
        remove_shortcode( AALB_SHORTCODE_AMAZON_LINK );
        remove_shortcode( AALB_SHORTCODE_AMAZON_TEXT );
    }

    /**
     * Callback function for rendering amazon_link shortcode
     *
     *
     * @since 1.0.0
     *
     * @param array $atts Shortcode attributes and values.
     *
     * @return HTML HTML for displaying the templates.
     */
    public function amazon_link_shortcode_callback( $atts ) {
        return $this->get_amazon_link_shortcode()->render( $atts );
    }

    /**
     * Create only a single instance of the Aalb Shortcode.
     * No need to create an instance for rendering each shortcode.
     *
     * @since 1.0.0
     * @return Aalb_Shortcode The instance of Aalb_Shortcode.
     */
    public function get_amazon_link_shortcode() {
        if ( is_null( $this->shortcode_link ) ) {
            return new Aalb_Shortcode();
        }

        return $this->shortcode_link;
    }

    /**
     * Callback function for rendering amazon_textlink shortcode
     *
     *
     * @since 1.4
     *
     * @param array $atts Shortcode attributes and values.
     *
     * @return HTML HTML for displaying the templates.
     */
    public function amazon_textlink_shortcode_callback( $atts ) {
        return $this->get_amazon_textlink_shortcode()->render( $atts );
    }

    /**
     * Create only a single instance of the Aalb TextLink Shortcode.
     * No need to create an instance for rendering each shortcode.
     *
     * @since 1.4
     * @return Aalb_Shortcode_Text The instance of Aalb_Shortcode_Text.
     */
    public function get_amazon_textlink_shortcode() {
        if ( is_null( $this->shortcode_textlink ) ) {
            return new Aalb_Shortcode_Text();
        }

        return $this->shortcode_textlink;
    }

}

?>
