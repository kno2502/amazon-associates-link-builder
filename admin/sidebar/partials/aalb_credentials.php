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

include 'aalb_admin_ui_common.php';
$helper = new Aalb_Helper();
$aalb_store_id_names = $helper->get_store_ids_array();
wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'aalb_credentials_js', AALB_CREDENTIALS_JS, array( 'jquery' ) );
wp_localize_script( 'aalb_credentials_js', 'wp_opt', array( 'plugin_url' => AALB_PLUGIN_URL ) );
?>

<div class="wrap">
    <h2>Settings for <?= AALB_PROJECT_TITLE ?></h2>
    <br>
    <form method="post" action="options.php">
        <?php settings_fields( AALB_CRED_CONFIG_GROUP );
        do_settings_sections( AALB_CRED_CONFIG_GROUP ); ?>
        <table class="widefat fixed">
            <tr>
                <th scope="row" style="width:15%;">Access Key ID</th>
                <td style="vertical-align:middle;">
                    <input type="text" name="aalb_aws_access_key" required style="width:90%"
                            value="<?php echo esc_attr( openssl_decrypt( base64_decode( get_option( AALB_AWS_ACCESS_KEY ) ), AALB_ENCRYPTION_ALGORITHM, AALB_ENCRYPTION_KEY, 0, AALB_ENCRYPTION_IV ) ); ?>"/>
                </td>
                <td>Your Access Key ID that you generated after signing up for the Amazon Product Advertising API. If
                    you have not already signed up for the Amazon Product Advertising API, you can do so by following
                    instructions listed <a
                            href="http://docs.aws.amazon.com/AWSECommerceService/latest/DG/CHAP_GettingStarted.html"
                            target="_blank">here</a>.
                </td>
            </tr>
            <tr>
                <th scope="row" style="width:15%;">Secret Access Key</th>
                <?php $secret_key = get_option( AALB_AWS_SECRET_KEY );
                if ( $secret_key ) {
                    $secret_key = AALB_AWS_SECRET_KEY_MASK;
                }
                ?>
                <td style="vertical-align:middle;"><input type="password" name="aalb_aws_secret_key" required
                            style="width:90%" value="<?php echo esc_attr( $secret_key ); ?>"
                            autocomplete="off"/></td>
                <td>A key that is used in conjunction with the Access Key ID to cryptographically sign an API request.
                    To retrieve your Access Key ID or Secret Access Key, go to <a
                            href="https://affiliate-program.amazon.com/gp/advertising/api/detail/your-account.html"
                            target="_blank">Manage Your Account</a>. The plugin uses a default encryption key for
                    encrypting the Secret Key. You can change the key using AALB_ENCRYPTION_KEY parameter defined in
                    /aalb_config.php.
                </td>
            </tr>
            <tr>
                <th scope="row" style="width:15%;">Associate ID</th>
                <td style="width:90%;vertical-align:middle;">
                    <textarea type="text" id="aalb_store_id_names" name="aalb_store_id_names" style="width:90%"
                            value="<?php echo esc_attr( get_option( AALB_STORE_ID_NAMES ) ); ?>"
                            onchange="aalb_credentials_store_ids_onchange(this)"><?php echo esc_attr( get_option( AALB_STORE_ID_NAMES ) ); ?>
                    </textarea>
                </td>
                <td>Associate ID is used to monitor traffic and sales from your links to Amazon. You can add one store
                    id or tracking id per row. You are recommended to create a new tracking ID in your Amazon Associates
                    account for using it as Associate ID in the plugin.
                </td>
            </tr>
            <tr>
                <th scope="row" style="width:15%;">Default Associate ID</th>
                <td style="vertical-align:middle;">
                    <?php $default_store_id = get_option( AALB_DEFAULT_STORE_ID, AALB_DEFAULT_STORE_ID_NAME ); ?>
                    <select id="aalb_default_store_id" name="aalb_default_store_id" style="width:90%">
                        <?php
                        foreach ( $aalb_store_id_names as $store_id ) {
                            echo '<option value="' . $store_id . '"';
                            selected( $default_store_id, $store_id );
                            echo '>' . $store_id . '</option>\n';
                        }
                        ?>
                    </select>
                </td>
                <td>The Associate ID that will be used for tagging the affiliate links generated by the plugin if no tag
                    is specified in the short code.
                </td>
            </tr>
            <tr>
                <th scope="row" style="width:15%;">Default Marketplace</th>
                <td style="vertical-align:middle;">
                    <?php $default_marketplace = get_option( AALB_DEFAULT_MARKETPLACE, AALB_DEFAULT_MARKETPLACE_NAME ); ?>
                    <select name="aalb_default_marketplace" style="width:90%">
                        <?php
                        $config_loader = new Aalb_Config_Loader();
                        $aalb_marketplace_names = $config_loader->fetch_marketplaces();
                        foreach ( $aalb_marketplace_names as $marketplace ) {
                            echo '<option value="' . $marketplace . '"';
                            selected( $default_marketplace, $marketplace );
                            echo '>' . $marketplace . '</option>\n';
                        }
                        ?>
                    </select>
                </td>
                <td>Set the default Amazon marketplace based on the Amazon website that is identified in your Associates
                    Account (for instance, if you have signed up for Amazon.co.uk site, then your default marketplace
                    selection should be UK).
                </td>
            </tr>
            <tr>
                <th scope="row" style="width:15%;">Default Template</th>
                <td style="vertical-align:middle;">
                    <?php $default_template = get_option( AALB_DEFAULT_TEMPLATE, AALB_DEFAULT_TEMPLATE_NAME ); ?>
                    <select name="aalb_default_template" style="width:90%">
                        <?php
                        $templates = get_option( AALB_TEMPLATE_NAMES, $default_template );
                        foreach ( $templates as $template ) {
                            echo '<option value="' . $template . '"';
                            selected( $default_template, $template );
                            echo '>' . $template . '</option>\n';
                        }
                        ?>
                    </select>
                </td>
                <td>The ad template that will be used for rendering the ad if no template is specified in the short
                    code.
                </td>
            </tr>
        </table>
        <br>
        <table>
            <tr>
                <th scope="row" style="width:1%;">
                    <input id="aalb-terms-checkbox" type="checkbox" name="demo-checkbox" value="1"/>
                </th>
                <td style="vertical-align:middle;">
                    Check here to indicate that you have read and agree to the Amazon Associates Link Builder <a
                            href="https://s3.amazonaws.com/aalb-public-resources/documents/AssociatesLinkBuilder-ConditionsOfUse-2017-01-17.pdf"
                            target="_blank">Conditions of Use</a>.
                </td>
            </tr>
        </table>
        <?php submit_button( 'Save Changes', 'primary', 'submit', true, array( 'disabled' => 'disabled' ) ); ?>
    </form>
</div>
