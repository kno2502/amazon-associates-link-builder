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
 * Update the Default Store ID dropdown onchange
 *
 * @param HTML DOM OBJECT element list of store IDs in "Store IDs" field
 */
function aalb_credentials_store_ids_onchange( element ) {
    var aalb_store_ids = element.value;
    var aalb_store_ids_array = aalb_store_ids.trim().split( /[\n]+/ );
    //Selected Store ID
    var aalb_store_ids_selected = jQuery( '#aalb_default_store_id' ).val();
    var aalb_store_id_dropdown_html = "";
    for ( var i = 0; i < aalb_store_ids_array.length; i++ ) {
        if ( aalb_store_ids_array[ i ].length > 0 ) {
            aalb_store_ids_array[ i ] = aalb_store_ids_array[ i ].trim();
            aalb_store_id_dropdown_html += "<option value='" + aalb_store_ids_array[ i ] + "'>" + aalb_store_ids_array[ i ] + "</option>\n";
        } else {
            //Remove empty lines
            aalb_store_ids_array.splice( i, 1 );
        }
    }
    jQuery( '#aalb_store_id_names' ).val( aalb_store_ids_array.join( "\r\n" ) );
    //Set the HTML of select dropdown with updated store ids
    jQuery( '#aalb_default_store_id' ).html( aalb_store_id_dropdown_html );
    //Select the previously selected option value if it exists in the new list
    if ( aalb_store_ids_selected.length > 0 && jQuery.inArray( aalb_store_ids_selected, aalb_store_ids_array ) > 0 ) {
        jQuery( '#aalb_default_store_id' ).val( aalb_store_ids_selected );
    } else {
        //If no value is selected, automatically selects first element.
        jQuery( "#aalb_default_store_id" ).val( jQuery( "#aalb_default_store_id option:first" ).val() );
    }
}
/**
 * OnClick Handler for aalb terms and conditions checkbox.
 *
 */
jQuery( '#aalb-terms-checkbox' ).click( function() {
    if ( jQuery( this ).is( ':checked' ) ) {
        jQuery( "#submit" ).removeAttr( 'disabled' );
    } else {
        jQuery( '#submit' ).attr( 'disabled', 'disabled' );
    }
} );
