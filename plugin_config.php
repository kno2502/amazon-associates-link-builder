<?php

/*
Copyright 2016-2018 Amazon.com, Inc. or its affiliates. All Rights Reserved.

Licensed under the GNU General Public License as published by the Free Software Foundation,
Version 2.0 (the "License"). You may not use this file except in compliance with the License.
A copy of the License is located in the "license" file accompanying this file.

This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
either express or implied. See the License for the specific language governing permissions
and limitations under the License.
*/

define( 'AALB_PLUGIN_NAME', 'Amazon Associates Link Builder' );
//PHP version compatible for AALB plugin
define( 'AALB_PLUGIN_MINIMUM_SUPPORTED_PHP_VERSION', '5.4.0' );

//paths
define( 'AALB_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'AALB_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

//Directories
define( 'AALB_TEMPLATE_DIR', AALB_PLUGIN_DIR . 'template/' );
define( 'AALB_ADMIN_DIR', AALB_PLUGIN_DIR . 'admin/' );
define( 'AALB_SIDEBAR_DIR', AALB_PLUGIN_DIR . 'admin/sidebar/' );
define( 'AALB_INCLUDES_DIR', AALB_PLUGIN_DIR . 'includes/' );
define( 'AALB_PAAPI_DIR', AALB_PLUGIN_DIR . 'lib/php/Paapi/' );
define( 'AALB_SHORTCODE_DIR', AALB_PLUGIN_DIR . 'shortcode/' );
define( 'AALB_LIBRARY_DIR', AALB_PLUGIN_DIR . 'lib/php/' );
define( 'AALB_SIDEBAR_HELPER_DIR', AALB_PLUGIN_DIR . 'admin/sidebar/partials/helper/' );
define( 'AALB_IP_2_COUNTRY_DIR', AALB_PLUGIN_DIR . 'ip2country/' );
define( 'AALB_EXCEPTIONS_DIR', AALB_PLUGIN_DIR . 'exceptions/' );
define( 'AALB_IO_DIR', AALB_PLUGIN_DIR . 'io/' );
define( 'AALB_HELPER_DIR', AALB_PLUGIN_DIR . 'helper/' );
define( 'AALB_CONFIGURATION_DIR', AALB_PLUGIN_DIR . 'configuration/' );
define( 'AALB_RENDERING_DIR', AALB_PLUGIN_DIR . 'rendering/' );
define( 'AALB_CACHE_DIR', AALB_PLUGIN_DIR . 'cache/' );
define( 'AALB_VIEW_PARTIALS_DIR', AALB_PLUGIN_DIR . 'view/partials/' );
define( 'AALB_VIEW_SIDEBAR_PARTIALS_DIR', AALB_PLUGIN_DIR . 'view/sidebar_partials/' );
define( 'AALB_JS_DIR', AALB_PLUGIN_DIR . 'js/' );
define( 'AALB_CSS_DIR', AALB_PLUGIN_DIR . 'css/' );
define( 'AALB_CONSTANTS_DIR', AALB_PLUGIN_DIR . 'constants/' );
define( 'AALB_TEMPLATE_UPLOADS_FOLDER', 'amazon-associates-link-builder/template/' );

//Classes
define( 'AALB_ABOUT_PHP', AALB_VIEW_SIDEBAR_PARTIALS_DIR . 'about.php' );
define( 'AALB_CREDENTIALS_PHP', AALB_VIEW_SIDEBAR_PARTIALS_DIR . 'credentials.php' );
define( 'AALB_TEMPLATE_PHP', AALB_VIEW_SIDEBAR_PARTIALS_DIR . 'templates.php' );
define( 'AALB_META_BOX_PARTIAL', AALB_VIEW_PARTIALS_DIR . 'meta_box.php' );
define( 'AALB_EDITOR_SEARCH_BOX', AALB_VIEW_PARTIALS_DIR . 'editor_search_box.php' );
define( 'AALB_AUTOLOADER', AALB_PLUGIN_DIR . 'includes/autoloader.php' );
define( 'AALB_COMPATIBILITY_HELPER', AALB_HELPER_DIR . 'aalb_compatibility_helper.php' );
define( 'AALB_INITIALIZER', AALB_INCLUDES_DIR . 'aalb_initializer.php' );
//Configuration JSON
define( 'AALB_MARKETPLACE_CONFIG_JSON', AALB_CONFIGURATION_DIR . 'marketplace_config.json' );
//Mustache Templates
define( 'AALB_ADMIN_ITEM_SEARCH_ITEMS_PATH', AALB_VIEW_PARTIALS_DIR . 'admin_item_search_items.php' );

//Templates Directory
define( 'AALB_TEMPLATE_URL', AALB_PLUGIN_URL . 'template/' );

//Local Styles
define( 'AALB_ADMIN_CSS', AALB_PLUGIN_URL . 'css/aalb_admin.css' );
define( 'AALB_CREDENTIALS_CSS', AALB_PLUGIN_URL . 'css/aalb_credentials.css' );
define( 'AALB_BASICS_CSS', AALB_PLUGIN_URL . 'css/aalb_basics.css' );

//Local Scripts
define( 'AALB_SHA2_JS', AALB_PLUGIN_URL . 'lib/js/jssha2/sha2.js' );
define( 'AALB_ADMIN_JS', AALB_PLUGIN_URL . 'js/aalb_admin.js' );
define( 'AALB_TEMPLATE_JS', AALB_PLUGIN_URL . 'js/aalb_template.js' );
define( 'AALB_CREDENTIALS_JS', AALB_PLUGIN_URL . 'js/aalb_credentials.js' );

/**
 * Icons
 */
define( 'AALB_SECURE_HOSTNAME', 'https://images-na.ssl-images-amazon.com/' );
define( 'AALB_NORMAL_HOSTNAME', 'http://g-ecx.images-amazon.com/' );
define( 'AALB_ICON_LOCATION', 'images/G/01/PAAPI/AmazonAssociatesLinkBuilder/icon-2._V276841048_.png' );
define( 'AALB_ADMIN_ICON_LOCATION', 'images/G/01/PAAPI/AmazonAssociatesLinkBuilder/amazon_icon._V506839993_.png' );
//AALB_ICON URL is generated by wordpress at run-time by checking the remotehost's encryption. Image source has different URLs depending upon the encryption used.
if ( is_ssl() ) {
    define( 'AALB_ICON', AALB_SECURE_HOSTNAME . AALB_ICON_LOCATION );
} else {
    define( 'AALB_ICON', AALB_NORMAL_HOSTNAME . AALB_ICON_LOCATION );
}
define( 'AALB_ADMIN_ICON', AALB_SECURE_HOSTNAME . AALB_ADMIN_ICON_LOCATION );

//Geolite DB Retry Durations
define( 'AALB_GEOLITE_DB_DOWNLOAD_RETRY_DURATION_MIN', 30 * MINUTE_IN_SECONDS );
define( 'AALB_GEOLITE_DB_DOWNLOAD_RETRY_DURATION_MAX', 2 * DAY_IN_SECONDS );
define( 'AALB_GEOLITE_DB_DOWNLOAD_RETRY_DURATION_ON_SUCCESS', 3 * DAY_IN_SECONDS );
define( 'AALB_GEOLITE_DB_MAX_ALLOWED_AGE', 60 * DAY_IN_SECONDS );

//Caching Requirements
//====================
//As defined by the Product Advertising API License Agreement at https://affiliate-program.amazon.com/gp/advertising/api/detail/agreement.html,
//Dated Jul 22, 2016, Section 4(n) and 4(o), caching of product information is permitted upto a maximum of 24-hours.
//Further, if the product price is not refreshed every one hour, the displayed price should be accompanied with a timestamp when the price was read.
//Note that the plugin uses a two tier cache. It caches the ASINs as well as the rendered templates.
//At any given time the sum of ASIN cache TTL and Rendered AdUnit cache TTL should be less than or equal to one hour.
//The below configuration is compliant with the License Agreement. Any modification may result in the violation of the license agreement.
define( 'AALB_CACHE_FOR_ASIN_RAWINFO_TTL', 30 * MINUTE_IN_SECONDS );
define( 'AALB_CACHE_FOR_ASIN_ADUNIT_TTL', 30 * MINUTE_IN_SECONDS );

define( 'AALB_SETTINGS_PAGE_URL', admin_url( 'admin.php?page=associates-link-builder-settings' ) );
define( 'TRUE', 'true' );
?>
