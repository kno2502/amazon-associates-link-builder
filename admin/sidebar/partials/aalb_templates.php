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
$helper->aalb_initialize_wp_filesystem_api();

/**
 * Verifies whether file exists and is writable
 *
 * @since 1.0.0
 *
 * @param string $file Name of the file
 *
 * @return bool TRUE if file exists and is writable or if it's a new file which, throws exception otherwise
 */
function aalb_verify_file_is_writable( $file ) {
    if ( is_file( $file ) and ! is_writable( $file ) ) {
        throw new Exception( "Save Failed. The existing file " . $file . " is not writable." );
    }

    return true;
}

/**
 * Writes content to a file
 *
 * @since 1.0.0
 *
 * @param string $file Name of the file
 * @param string $content Content to be written
 *
 * @return bool  TRUE if write was successful, throws exception otherwise
 */
function aalb_write_to_file( $file, $content ) {
    global $wp_filesystem;
    if ( $wp_filesystem->put_contents( $file, $content ) === false ) {
        throw new Exception( "Save Failed. Error writing contents to file: " . $file );
    }

    return true;
}

/**
 * Saves new or updates existing template.
 * Writes template CSS and HTML into files.
 *
 * @since 1.0.0
 *
 * @param string $file Name of the file without .extension
 * @param string $content_html HTML content to be saved
 * @param string $content_css CSS content to be saved
 */
function aalb_save_template( $file, $content_html, $content_css ) {
    $file_mustache = $file . ".mustache";
    $file_css = $file . ".css";
    try {
        if ( ( aalb_verify_file_is_writable( $file_css ) and aalb_verify_file_is_writable( $file_mustache ) ) ) {
            //Both files are (existing and writable) or (a new file is specified)
            if ( aalb_write_to_file( $file_mustache, $content_html ) and aalb_write_to_file( $file_css, $content_css ) ) {
                //Both files were saved successfully
                //Else case never occurs as an exception is thrown in false case
                $saveFailed = false;
                aalb_success_notice( "Template Saved Successfully" );
            }
        }
    } catch ( Exception $e ) {
        throw new Exception( $e->getMessage() );
    }
}

/**
 * Finds any change in the current list of templates.
 * Flags a note if either a template is added or removed.
 *
 * @since 1.3
 *
 * @param array $aalb_current_template_names Current list of templates
 * @param array $aalb_template_names Refreshed list of templates
 */
function aalb_find_template_change( $aalb_current_template_names, $aalb_template_names ) {
    $templates_added = array_diff( $aalb_template_names, $aalb_current_template_names );
    $templates_deleted = array_diff( $aalb_current_template_names, $aalb_template_names );
    if ( sizeof( $templates_added ) > 0 ) {
        aalb_info_notice( sizeof( $templates_added ) . " template(s) added: " . implode( ', ', $templates_added ) );
    }
    if ( sizeof( $templates_deleted ) > 0 ) {
        aalb_info_notice( sizeof( $templates_deleted ) . " template(s) deleted: " . implode( ', ', $templates_deleted ) );
    }
}

//Flag to check if the save is failed.
$saveFailed = false;
$aalb_default_templates = explode( ",", AALB_AMAZON_TEMPLATE_NAMES );

$aalb_current_template_names = get_option( AALB_TEMPLATE_NAMES );

//Refresh templates
$helper->refresh_template_list();

$aalb_template_names = get_option( AALB_TEMPLATE_NAMES );
aalb_find_template_change( $aalb_current_template_names, $aalb_template_names );

if ( ! empty( $_POST["submit"] ) ) {
    $aalb_template_name = stripslashes( $_POST["aalb_template_name"] );
    $aalb_template_template_html_box = stripslashes( $_POST["aalb_template_template_html_box"] );
    $aalb_template_template_css_box = stripslashes( $_POST["aalb_template_template_css_box"] );
    $dir = AALB_TEMPLATE_DIR;
    $aalb_template_upload_dir = $helper->get_template_upload_directory();
    if ( $_POST["submit"] == "Save" ) {
        if ( $_POST["aalb_template_list"] == "new" ) {
            if ( empty( $aalb_template_name ) ) {
                aalb_error_notice( "Please define the template name for the new template" );
            } elseif ( ! ctype_alnum( str_replace( array( '-', '_' ), '', $aalb_template_name ) ) ) {
                //The template name can only be alphanumeric characters plus hyphens (-) and underscores (_)
                aalb_error_notice( "Save Failed. Only alphanumeric characters allowed for template name." );
            } else {
                if ( ! is_dir( $aalb_template_upload_dir ) or ! is_writable( $aalb_template_upload_dir ) ) {
                    aalb_error_notice( "Failed to create custom template. Please set up recursive Read-Write permissions for " . $helper->aalb_get_uploads_dir_path() );
                } else {
                    //Check if template of that name already exists
                    if ( in_array( $aalb_template_name, $aalb_template_names ) ) {
                        aalb_error_notice( "Save Failed. A template with the name \"" . $aalb_template_name . "\" already exists. Please specify some other name for the template" );
                        //Ensures state is saved even on save failures
                        $saveFailed = true;
                    } else {
                        try {
                            aalb_save_template( $aalb_template_upload_dir . $aalb_template_name, $aalb_template_template_html_box, $aalb_template_template_css_box );
                            array_push( $aalb_template_names, $aalb_template_name );
                            update_option( 'aalb_template_names', $aalb_template_names );
                        } catch ( Exception $e ) {
                            aalb_error_notice( $e->getMessage() );
                        }
                    }
                }
            }
        } else {
            try {
                if ( in_array( $aalb_template_name, $aalb_default_templates ) ) {
                    aalb_save_template( $dir . $aalb_template_name, $aalb_template_template_html_box, $aalb_template_template_css_box );
                } else {
                    aalb_save_template( $aalb_template_upload_dir . $aalb_template_name, $aalb_template_template_html_box, $aalb_template_template_css_box );
                }

                //clears the cached rendered templates whenever the template is modified
                $helper->clear_cache_for_template( $aalb_template_name );
            } catch ( Exception $e ) {
                aalb_error_notice( $e->getMessage() );
            }
        }
    } elseif ( $_POST["submit"] == "Remove" ) {
        if ( $_POST["aalb_template_list"] == "new" ) {
            aalb_error_notice( "Cannot remove new template. Please select a valid template to remove." );
        } else {
            $aalb_template_names = array_diff( $aalb_template_names, array( $aalb_template_name ) );
            update_option( 'aalb_template_names', $aalb_template_names );
            if ( in_array( $aalb_template_name, $aalb_default_templates ) ) {
                //If Default Amazon Template is Removed
                aalb_error_notice( "Couldn't remove Default Template" );
            } else {
                if ( unlink( $aalb_template_upload_dir . $aalb_template_name . ".mustache" ) ) {
                    aalb_success_notice( "Successfully removed Template HTML" );
                } else {
                    aalb_error_notice( "Couldn't remove Template HTML. Please manually remove " . $dir . $aalb_template_name . ".mustache" );
                }
                if ( unlink( $aalb_template_upload_dir . $aalb_template_name . ".css" ) ) {
                    aalb_success_notice( "Successfully removed Template CSS" );
                } else {
                    aalb_error_notice( "Couldn't remove Template CSS. Please manually remove " . $dir . $aalb_template_name . ".css" );
                }
            }

            $aalb_template_name = "";
            $aalb_template_template_html_box = "";
            $aalb_template_template_css_box = "";
        }
    }
}

wp_enqueue_script( 'jquery' );
wp_enqueue_script( 'codemirror_js', CODEMIRROR_JS );
wp_enqueue_script( 'codemirror_mode_xml_js', CODEMIRROR_MODE_XML_JS );
wp_enqueue_script( 'codemirror_mode_css_js', CODEMIRROR_MODE_CSS_JS );
wp_enqueue_style( 'codemirror_css', CODEMIRROR_CSS );

wp_enqueue_script( 'aalb_template_js', AALB_TEMPLATE_JS, array( 'jquery', 'codemirror_js', 'codemirror_mode_xml_js', 'codemirror_mode_css_js' ), AALB_PLUGIN_CURRENT_VERSION );
wp_localize_script( 'aalb_template_js', 'wp_opt', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'upload_url' => $helper->get_template_upload_directory(), 'plugin_url' => AALB_PLUGIN_URL, 'aalb_default_templates' => AALB_AMAZON_TEMPLATE_NAMES ) );

?>
<div class="wrap">
    <h2>Templates for <?= AALB_PROJECT_TITLE ?></h2>
    <br>
    <form method="post">
        <table class="widefat fixed">
            <tr>
                <th scope="row" style="width:15%;">Select Template</th>
                <td>
                    <select id="aalb_template_list_select" name="aalb_template_list" style="width:50%"
                            onchange="aalb_template_select_template_onchange(this)">
                        <option value="new">Create new template</option>
                        <?php
                        foreach ( $aalb_template_names as $aalb_template ) {
                            ?>
                            <option value="<?= $aalb_template ?>" <?php $saveFailed === false ? selected( $aalb_template, ( isset( $aalb_template_name ) ) ? $aalb_template_name : '' ) : selected( $aalb_template, "" ) ?>><?= $aalb_template ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <input type="button" name="clone" id="clone_template" class="button button-secondary" value="Clone"
                            onclick="clone_existing_template()" disabled>
                </td>
            </tr>
            <tr>
                <th scope="row" style="width:15%;">Set a name for your template</th>
                <td>
                    <input type="text" id="aalb_template_name" name="aalb_template_name" style="width:50%"
                            value="<?= ( isset( $aalb_template_name ) ) ? $aalb_template_name : '' ?>"/>
                    <span style="font-size:0.9em;">[<a
                                href="https://s3.amazonaws.com/aalb-public-resources/documents/AssociatesLinkBuilder-Guide-HowToCreateCustomTemplates.pdf"
                                target="_blank">Guide for creating custom templates</a>]</span>
                </td>
            </tr>
            <tr>
                <th scope="row" style="width:15%;">Add HTML for your template</th>
                <td><textarea id="aalb_template_template_html_box"
                            name="aalb_template_template_html_box"><?= ( isset( $aalb_template_template_html_box ) ) ? $aalb_template_template_html_box : '' ?></textarea>
                </td>
            </tr>
            <tr>
                <th scope="row" style="width:15%;">Add CSS for your template</th>
                <td><textarea id="aalb_template_template_css_box"
                            name="aalb_template_template_css_box"><?= ( isset( $aalb_template_template_css_box ) ) ? $aalb_template_template_css_box : '' ?></textarea>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input name="submit" id="submit_save" class="button button-primary" value="Save" type="submit">
            <input name="submit" id="submit_remove" class="button button-secondary" value="Remove" type="submit" disabled>
        </p>
    </form>
</div>
