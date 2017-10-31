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

$aalb_admin = new Aalb_Admin();
$aalb_admin->aalb_enqueue_styles();
$aalb_admin->aalb_enqueue_scripts();

/*
 * Below is an example of context to be passed to the below template
 *
{
   "ad_template_label":"Ad Template",
   "searchbox_placeholder":"Enter keyword(s)",
   "search_button_label":"Search",
   "associate_id_label":"Associate ID",
   "marketplace_label":"Marketplace",
   "text_shown_during_search":"Searching relevant products from Amazon",
   "click_to_select_products_label":"Click to select product(s) to advertise",
   "check_more_on_amazon_text":"Check more search results on Amazon",
   "selected_products_list_label":"List of Selected Products",
   "text_shown_during_shortcode_creation":"Creating shortcode. Please wait....",
   "add_shortcode_button_label":"Add Shortcode",
   "templates_list":[
      "PriceLink",
      "ProductAd",
      "ProductCarousel",
      "ProductGrid",
      "ProductLink"
   ],
   "default_template":"ProductAd",
   "marketplace_list":[
      "IN",
      "UK"
   ],
   "default_marketplace":"IN",
   "default_store_id_list":[
      "store-1",
      "store-2"
   ],
   "default_store_id":"store-1"
}
 */

?>
    <!--ToDO: Remove inline & event binding & styling(except the one for aalb-admin-popup-container)-->
    <!-- keeping css inline as css file does not load at plugin initialization  -->
    <div id="aalb-admin-popup-container" style="display:none;">
        <script id="aalb-search-pop-up-hbs" type="text/x-handlebars-template">
            <div class="aalb-admin-searchbox aalb-admin-popup-options">
                <input type="text" id="aalb-admin-popup-input-search" name="aalb-admin-popup-input-search"
                    placeholder="{{searchbox_placeholder}}" onkeypress='aalb_submit_event(event,"aalb-btn-primary",this)' />
                <button class="aalb-btn aalb-btn-primary" id="aalb-admin-popup-search-button" type="button"
                    onclick="aalb_admin_popup_search_items()" style="margin-top:1%">{{search_button_label}}
                </button>
            </div><!--end .aalb-admin-popup-options-->
            <!-- start:  aalb-admin-popup-shortcode-options-->
            <div class="aalb-admin-popup-shortocde-wrapper">
                <div class="aalb-admin-popup-shortcode-options">
                    <div class="aalb-admin-item-search-templates">
                        <label title="{{templates_help_content}}">{{ad_template_label}}<i class="fa fa-info-circle aalb-info-icon" aria-hidden="true"></i></label>
                        <select id="aalb_template_names_list" name="aalb_template_names_list">
                            {{#each templates_list}}
                            <option value="{{this}}" {{selected this ..
                            /default_template}} {{this}}>{{this}}</option>
                            {{/each}}
                        </select>
                    </div>
                    <div class="aalb-admin-item-search-marketplaces">
                        <label title="{{marketplace_help_content}}">{{marketplace_label}}<i class="fa fa-info-circle aalb-info-icon" aria-hidden="true"></i></label>
                        <select id="aalb_marketplace_names_list" name="aalb_marketplace_names_list">
                            {{#each marketplace_list}}
                            <option value="{{this}}" {{selected this ..
                            /default_marketplace}}>{{this}}</option>
                            {{/each}}
                        </select>
                    </div>
                    <div class="aalb-admin-popup-store">
                        <label title="{{tracking_id_help_content}}">{{associate_id_label}}<i class="fa fa-info-circle aalb-info-icon" aria-hidden="true"></i></label>
                        <select id="aalb-admin-popup-store-id" name="aalb-admin-popup-store-id">
                            {{#each default_store_id_list}}
                            <option value="{{this}}" {{selected this ..
                            /default_store_id}}>{{this}}</option>
                            {{/each}}
                        </select>
                    </div>
                </div>
            </div><!--end .aalb-admin-popup-shortcode-options-->
            <div id="aalb-admin-popup-content">
                <div class="aalb-admin-alert aalb-admin-alert-info aalb-admin-item-search-loading">
                    <div class="aalb-admin-icon"><i class="fa fa-spinner fa-pulse"></i></div>
                    {{text_shown_during_search}}
                </div><!--end .aalb-admin-item-search-loading-->
                <div class="aalb-admin-item-search">
                    {{click_to_select_products_label}}
                    <div class="aalb-admin-item-search-items"></div>
                    <a href="#" target="_blank" id="aalb-admin-popup-more-results" class="pull-right">{{check_more_on_amazon_text}}</a>
                </div><!--end .aalb-admin-item-serch-->
            </div><!--end .aalb-admin-popup-content-->
            <div class="aalb-selected">
                <label>{{selected_products_list_label}}</label>
            </div>

            <div class="aalb-add-shortcode-button">
                <button class="aalb-btn aalb-btn-primary" id="aalb-add-shortcode-button" type="button">{{add_shortcode_button_label}}</button>
                <div id="aalb-add-shortcode-alert">
                    <div class="aalb-admin-icon"><i class="fa fa-spinner fa-pulse"></i></div>
                    {{text_shown_during_shortcode_creation}}
                </div>
                <div id="aalb-add-asin-error">
                    <div id="aalb-add-template-asin-error"></div>
                </div>
            </div><!--end .aalb-add-shortcode-button-->
        </script>
    </div><!--end .aalb-admin-popup-container-->
<?php
?>