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
 * UI for Add short code popup. responsible to show list of products items based on the keywords.
 * This Metabox enables users to choose template, Associate ID and Market place for which product is being added based on the
 * details selected by plugin user short code is generated.
 */

// HandleBar template
include AALB_ADMIN_ITEM_SEARCH_ITEMS_PATH;

$aalb_template_names = get_option( AALB_TEMPLATE_NAMES );
$config_loader = new Aalb_Config_Loader();
$aalb_marketplace_names = $config_loader->fetch_marketplaces();
$helper = new Aalb_Helper();
$aalb_store_id_names = $helper->get_store_ids_array();
$aalb_admin = new Aalb_Admin();
$aalb_admin->aalb_enqueue_styles();
$aalb_admin->aalb_enqueue_scripts();
?>
<!-- keeping css inline as css file does not load at plugin initialization  -->
<div id="aalb-admin-popup-container" style="display:none;">
<div class="aalb-admin-searchbox aalb-admin-popup-options">
        <input type="text" id="aalb-admin-popup-input-search" name="aalb-admin-popup-input-search"
               placeholder="<?php esc_attr_e( "Enter keyword(s)", 'amazon-associates-link-builder' ) ?>" onkeypress='aalb_submit_event(event,"aalb-btn-primary",this)'/>
        <button class="aalb-btn aalb-btn-primary" id="aalb-admin-popup-search-button" type="button"
            onclick="aalb_admin_popup_search_items()" style="margin-top:1%"><?php esc_html_e( "Search", 'amazon-associates-link-builder' ) ?>
        </button>
    </div><!--end .aalb-admin-popup-options-->
    <!-- start:  aalb-admin-popup-shortcode-options-->
    <div class="aalb-admin-popup-shortocde-wrapper">
        <div class="aalb-admin-popup-shortcode-options">
            <div class="aalb-admin-item-search-templates">
                <label><?php esc_html_e( "Ad Template", 'amazon-associates-link-builder' ) ?></label>
                <?php $aalb_default_template = get_option( AALB_DEFAULT_TEMPLATE, AALB_DEFAULT_TEMPLATE_NAME ); ?>
                <select id="aalb_template_names_list" name="aalb_template_names_list" >
                    <?php foreach ( $aalb_template_names as $aalb_template_name ) { ?>
                        <option value="<?= $aalb_template_name ?>" <?php selected( $aalb_default_template, $aalb_template_name ); ?>><?= $aalb_template_name ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="aalb-admin-popup-store">
                <label><?php esc_html_e( "Associate ID", 'amazon-associates-link-builder' ) ?></label>
                <?php $aalb_default_store_id = get_option( AALB_DEFAULT_STORE_ID, AALB_DEFAULT_STORE_ID_NAME ); ?>
                <select id="aalb-admin-popup-store-id" name="aalb-admin-popup-store-id" >
                    <?php foreach ( $aalb_store_id_names as $aalb_store_id ) { ?>
                        <option value="<?= $aalb_store_id ?>" <?php selected( $aalb_default_store_id, $aalb_store_id ); ?>> <?= $aalb_store_id ?> </option>
                    <?php } ?>
                </select>
            </div>
            <div class="aalb-admin-item-search-marketplaces">
                <label><?php esc_html_e( "Marketplace", 'amazon-associates-link-builder' ) ?></label>
                <?php $aalb_default_marketplace = get_option( AALB_DEFAULT_MARKETPLACE, AALB_DEFAULT_MARKETPLACE_NAME ); ?>
                <select id="aalb_marketplace_names_list" name="aalb_marketplace_names_list" >
                    <?php foreach ( $aalb_marketplace_names as $aalb_marketplace => $aalb_marketplace_abbr ) { ?>
                        <option value="<?= $aalb_marketplace ?>" <?php selected( $aalb_default_marketplace, $aalb_marketplace_abbr ); ?>><?= $aalb_marketplace_abbr ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
    </div><!--end .aalb-admin-popup-shortcode-options-->
    <div id="aalb-admin-popup-content">
        <div class="aalb-admin-alert aalb-admin-alert-info aalb-admin-item-search-loading">
            <div class="aalb-admin-icon"><i class="fa fa-spinner fa-pulse"></i></div>
            <?php esc_html_e( "Searching relevant products from Amazon", 'amazon-associates-link-builder' ) ?>
        </div><!--end .aalb-admin-item-search-loading-->
        <div class="aalb-admin-item-search">
            <?php esc_html_e( "Click to select product(s) to advertise", 'amazon-associates-link-builder' ) ?>
            <div class="aalb-admin-item-search-items"></div>
            <a href="#" target="_blank" id="aalb-admin-popup-more-results" class="pull-right"><?php esc_html_e( "Check more search results on Amazon", 'amazon-associates-link-builder' ) ?></a>
        </div><!--end .aalb-admin-item-serch-->
    </div><!--end .aalb-admin-popup-content-->
    <div class="aalb-selected">
        <label><?php esc_html_e( "List of Selected Products", 'amazon-associates-link-builder' ) ?></label>
    </div>

    <div class="aalb-add-shortcode-button">
        <button class="aalb-btn aalb-btn-primary" id="aalb-add-shortcode-button" type="button"><?php esc_html_e( "Add Shortcode", 'amazon-associates-link-builder' ) ?></button>
        <div id="aalb-add-shortcode-alert">
            <div class="aalb-admin-icon"><i class="fa fa-spinner fa-pulse"></i></div>
            <?php esc_html_e( "Creating shortcode. Please wait....", 'amazon-associates-link-builder' ) ?>
        </div>
        <div id="aalb-add-asin-error">
            <div id="aalb-add-template-asin-error"></div>
        </div>
    </div><!--end .aalb-add-shortcode-button-->
</div><!--end .aalb-admin-popup-container-->
<?php
?>