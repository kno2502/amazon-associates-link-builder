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
 * Fired during the plugin activation
 *
 * Gets the template names from the template directory and loads it into the database.
 *
 * @since      1.0.0
 * @package    AmazonAssociatesLinkBuilder
 * @subpackage AmazonAssociatesLinkBuilder/includes
 */
class Aalb_Activator {

  protected $helper;

  public function __construct() {
    $this->helper = new Aalb_Helper();
  }

  /**
   * Add the template names to the database from the filesystem.
   *
   * @since    1.0.0
   */
  public function load_templates() {
    $this->helper->refresh_template_list();
  }

  /**
   * Add the aws key options into the database on activation.
   * This solves the problem of encryption as wordpress called an update option before calling
   * add option while sanitizing.
   * https://codex.wordpress.org/Function_Reference/register_setting
   *
   * @since    1.0.0
   */
  public function load_db_keys() {
    if(!get_option(AALB_AWS_ACCESS_KEY)) {
      update_option(AALB_AWS_ACCESS_KEY, '');
    }
    if(!get_option(AALB_AWS_SECRET_KEY)) {
      update_option(AALB_AWS_SECRET_KEY, '');
    }
  }

  /**
   * Init store ids key and add todatabase.
   *
   * @since    1.0.0
   */
  public function load_store_ids() {
    update_option(AALB_STORE_IDS, '');
  }
}

?>
