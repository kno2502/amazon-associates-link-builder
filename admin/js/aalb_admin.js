/*
Copyright 2016-2016 Amazon.com, Inc. or its affiliates. All Rights Reserved.

Licensed under the GNU General Public License as published by the Free Software Foundation,
Version 2.0 (the "License"). You may not use this file except in compliance with the License.
A copy of the License is located in the "license" file accompanying this file.

This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
either express or implied. See the License for the specific language governing permissions
and limitations under the License.
*/

var template;
var TB_WIDTH,TB_HEIGHT;
var tb_remove;
var link_id = "";
var AALB_SHORTCODE_AMAZON_LINK=api_pref.AALB_SHORTCODE_AMAZON_LINK; //constant value from server side is reused here

jQuery(document).ready(function() {
  // http://stackoverflow.com/questions/5557641/how-can-i-reset-div-to-its-original-state-after-it-has-been-modified-by-java
  jQuery("#aalb-admin-popup-content").data('old-state', jQuery("#aalb-admin-popup-content").html());

  //Load the search result template
  jQuery.get( api_pref.template_url, function(data) {
    template = Handlebars.compile(data);
  });
  
  //Resize thickbox on window resize
  jQuery(window).on('resize',resize_thickbox);

  //Storing the tb_remove function of Thickbox.js
  var old_tb_remove = window.tb_remove;

  //Custom tb_remove function
  tb_remove = function() {
    //call actual tb_remove 
    old_tb_remove();
    //custom actions to execute
    jQuery(".aalb-selected-item").each(function(){
      aalb_remove_selected_item(this);
    });
  };
});

/**
 * Resizing thickbox on change in window dimensions
 * Setting a max width and height of 1280x800 px for readability and to lessen distortion
 */
function resize_thickbox(){ 
  TB_WIDTH=Math.min(1280,0.6*jQuery(window).width());
  TB_HEIGHT=Math.min(800,0.9*jQuery(window).height());
  jQuery(document).find('#TB_ajaxContent').width(TB_WIDTH-35).height(TB_HEIGHT-90);
  jQuery(document).find('#TB_window').width(TB_WIDTH).height(TB_HEIGHT);
  jQuery(document).find('#TB_window').css({marginLeft: '-' + TB_WIDTH / 2 + 'px',top: TB_HEIGHT/12});
  jQuery(document).find('#TB_window').removeClass();
}

/**
 * Ensure a button click on a return key press event
 * 
 * @param HTML_DOM_EVENT  event           OnKeyPress event
 * @param HTML_DOM_OBJECT button_to_click Button id to click on a return key press event
 */
function aalb_submit_event(event,button_to_click) {
  //Code for the RETURN key is 13
  if(event.keyCode==13) {
    event.preventDefault();
    jQuery('#'+button_to_click).click();
  }
}

/**
 * Removes the selected HTML element
 * 
 * @param HTML_DOM_OBJECT element HTML element to be removed 
 */
function aalb_remove_selected_item(element) {
  jQuery(element).remove();
}

/**
 * Display pop up thickbox
 */
function aalb_admin_show_create_shortcode_popup() {
  // http://stackoverflow.com/questions/5557641/how-can-i-reset-div-to-its-original-state-after-it-has-been-modified-by-java
  jQuery("#aalb-admin-popup-content").html(jQuery("#aalb-admin-popup-content").data('old-state'));
  var keywords = jQuery("#aalb-admin-input-search").val();
  if (keywords) {
    // Showing the TB and resetting the width - http://wordpress.stackexchange.com/questions/114107/thickbox-width-cant-be-changed-in-admin
    tb_show('Add Amazon Associates Link Builder Shortcode', '#TB_inline?inlineId=aalb-admin-popup-container',false); 
    resize_thickbox(); 

    // Getting the ItemSearch results
    aalb_admin_get_item_search_items(jQuery("#aalb-admin-input-search").val());
    jQuery("#aalb-admin-popup-input-search").attr('value',keywords);
    
  } else {
    alert("Please enter the keywords");
    jQuery("#aalb-admin-input-search").focus();
  }
  
}

/**
 * Search items from within the thickbox
 */
function aalb_admin_popup_search_items() {
  var keywords = jQuery("#aalb-admin-popup-input-search").val();
  jQuery("#aalb-admin-popup-content").html(jQuery("#aalb-admin-popup-content").data('old-state'));
  if (keywords) {
    // Getting the ItemSearch results
    aalb_admin_get_item_search_items(keywords);
    jQuery("#aalb-admin-popup-input-search").attr('value',keywords);
    
  } else {
    alert("Please enter the keywords");
    jQuery("#aalb-admin-popup-input-search").focus();
  }
}

/**
 * Search items for the keywords and display it in the pop up thickbox
 *
 * @param String keywords Items to search for.
 */
function aalb_admin_get_item_search_items(keywords) {
  marketplace = aalb_get_selected_marketplace();
  marketplace = marketplace ? marketplace : api_pref.marketplace;
  jQuery.get( api_pref.ajax_url, { "action" : api_pref.action, "item_search_nonce" : api_pref.item_search_nonce, "keywords" : keywords, "marketplace" : marketplace, "store_id" : api_pref.store_id}, function(xml) {
    var items_xml = jQuery(xml).find("Item");
    if(items_xml.length>0) {
      var items = [];
      var i=0;
      items_xml.each(function() {
        //selecting maximum of max_search_result_items elements
        if(i<api_pref.max_search_result_items){
          var item = {};
          item.asin = jQuery(this).find("ASIN").text();
          item.title = jQuery(this).find("Title").text();
          item.image = jQuery(this).find("LargeImage").first().find("URL").text();
          item.price = jQuery(this).find("LowestNewPrice").find("FormattedPrice").text();
          items.push(item);
        }
        i++;
      });

      var html = template(items);
      jQuery(".aalb-admin-item-search-items").append(html);
      jQuery("#aalb-admin-popup-more-results").attr('href',jQuery(xml).find("MoreSearchResultsUrl").text());
      jQuery(".aalb-admin-item-search-loading").slideUp("slow");
      jQuery(".aalb-admin-item-search").fadeIn("slow");
      jQuery(".aalb-admin-item-search-items-item").on("click", function() {
        var dataAsin = jQuery(this).attr("data-asin");
        var productImage = jQuery(this).find("img").attr("src");
        var productTitle = jQuery(this).find("div.aalb-admin-item-search-items-item-title").text();
        var productPrice = jQuery(this).find("div.aalb-admin-item-search-items-item-price").text();

        var selectedAsinHTML = '<div class="aalb-selected-item" onclick="aalb_remove_selected_item(this)"';
        selectedAsinHTML += ' data-asin="' + dataAsin + '">';
        selectedAsinHTML += '<div class="aalb-selected-item-img-wrap"><span class="aalb-selected-item-close">&times;</span>';
        selectedAsinHTML += '<img class="aalb-selected-item-img" src="' + productImage + '"></img></div>';
        selectedAsinHTML += '<div class="aalb-selected-item-title"><h3>' + productTitle + '</h3>';
        selectedAsinHTML += '<p class="aalb-selected-item-price">' + productPrice + '<br></p></div>';

        jQuery(".aalb-selected").append(selectedAsinHTML);
      });
    } else {
      errors_xml = jQuery(xml).find("Error");
      if(errors_xml.length > 0) {
        var htmlerror = "";
        errors_xml.each(function() {
          htmlerror += jQuery(this).find("Message").text() + "<br>";
        });
        jQuery(".aalb-admin-item-search-loading").html(htmlerror);
      } else {
        jQuery(".aalb-admin-item-search-loading").html(xml);
      }
    }
  });
  jQuery("#aalb-add-shortcode-button").unbind().click(function(){
    var selectedAsins=aalb_get_selected_asins();
    if(selectedAsins){
      jQuery("#aalb-add-shortcode-alert").fadeTo("fast",1);
      aalb_add_shortcode(AALB_SHORTCODE_AMAZON_LINK);
    }else{
      alert("Please select atleast one product for display");
    }
  });
}

/**
 * Adds the given shortcode to the editor
 * 
 * @param String Shortcode type to be added
 */
function aalb_add_shortcode(shortcodeName) {
  var shortcodeJson;
  if(shortcodeName == AALB_SHORTCODE_AMAZON_LINK) {
    var selectedAsins=aalb_get_selected_asins();
    var selectedTemplate=aalb_get_selected_template();
    var selectedStore=aalb_get_selected_store();
    var selectedMarketplace=aalb_get_selected_marketplace_abbreviation();
    
    shortcodeJson = {
      "name" : AALB_SHORTCODE_AMAZON_LINK,
      "params" : {
        "asins" : selectedAsins,
        "template" : selectedTemplate,
        "store" : selectedStore,
        "marketplace" : selectedMarketplace,
      }
    };
    aalb_get_link_id(shortcodeJson);
  }
  else {
    console.log("Invalid Shortcode provided!");
    return;
  }
}

/**
 * Handler function when the Add Shortcode button is clicked
 * and link id is retrieved.
 * 
 * @param Object shortcodeJson  Object describing the shortcode 
 */
function aalb_add_shortcode_click_handler(shortcodeJson) {
  aalb_create_shortcode(shortcodeJson);
  tb_remove();
}

/**
 * Builds shortcode from given JSON
 * 
 * @param Object shortcodeJson  Object describing the shortcode 
 *
 * @return String returns the Shortcode String
 */
function buildShortcode(shortcodeJson) {
  var shortcodeParamsString = "";
  for (var shortcodeParam in shortcodeJson.params) {
    if( shortcodeJson.params.hasOwnProperty(shortcodeParam) ) {
      shortcodeParamsString += " " + shortcodeParam + "='" + shortcodeJson.params[shortcodeParam] + "'";
    } 
  } 

  var shortcodeString = "[" + shortcodeJson.name + shortcodeParamsString + "]";
  return shortcodeString;
}

/**
 * Get unique link id whenever add shortcode button is clicked
 * 
 * @param Object shortcodeJson  Object describing the shortcode 
 */
function aalb_get_link_id(shortcodeJson){
  jQuery.post( api_pref.ajax_url, { "action" : "get_link_code", "shortcode_name":shortcodeJson.name, "shortcode_params":shortcodeJson.params}, function(json) {
    link_id = json;
    shortcodeJson.params.link_id=link_id;
    jQuery("#aalb-add-shortcode-alert").fadeTo("slow",0);
    aalb_add_shortcode_click_handler(shortcodeJson);
  });
}

/**
 * Add the shortcode to the display editor
 *
 * @param Object shortcodeJson  Object describing the shortcode 
 */
function aalb_create_shortcode(shortcodeJson) {
  send_to_editor(buildShortcode(shortcodeJson));
}

/**
 * Get the selected Asins
 *
 * @return String Selected Asins
 */
function aalb_get_selected_asins() {
  var selectedAsins = "";
  jQuery(".aalb-selected-item").each(function(){
    selectedAsins += jQuery(this).attr("data-asin") + ",";
  });
  return selectedAsins.slice(0,-1);
}

/**
 * Get the selected Template style
 *
 * @ereturn String Selected Template style
 */
function aalb_get_selected_template() {
  var selectedTemplate = "";
  var $selectedTemplate = jQuery("#aalb_template_names_list option:selected");
  if ($selectedTemplate.length > 0) {
    selectedTemplate = $selectedTemplate.val();
  }
  return selectedTemplate;
}

/**
 * Get the selected associate tag
 *
 * @return String Selected Associate tag
 */
function aalb_get_selected_store() {
  return jQuery('#aalb-admin-popup-store-id').val();
}

/**
 * Get the selected marketplace
 *
 * @return String Selected Marketplace to search the product
 */
function aalb_get_selected_marketplace() {
  var selectedMarketplace = "";
  var $selectedMarketplace = jQuery("#aalb_marketplace_names_list option:selected");
  if ($selectedMarketplace.length > 0) {
    selectedMarketplace = $selectedMarketplace.val();
  }
  return selectedMarketplace;
}

/**
 * Get the selected marketplace abbreviation
 *
 * @return String Selected Marketplace abbreviation for the shortcode
 */
function aalb_get_selected_marketplace_abbreviation() {
  var selectedMarketplace = "";
  var $selectedMarketplace = jQuery("#aalb_marketplace_names_list option:selected");
  if ($selectedMarketplace.length > 0) {
    selectedMarketplace = $selectedMarketplace.text();
  }
  return selectedMarketplace;
}
