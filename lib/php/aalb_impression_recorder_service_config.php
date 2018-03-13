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

/**
 * Class for storing impression recorder Service configuration
 * Configration been been stored as static to have single config array. Although PHP is single threaded but in one request
 * there can be multiple shortcodes and so multiple calls to get impression config for every shortcode.
 *
 * @since      1.6.0
 * @package    AmazonAssociatesLinkBuilder
 * @subpackage AmazonAssociatesLinkBuilder/lib/php
 */
class Aalb_Impression_Recorder_Service_Config {
    public static $configuration = array(
        US   => array(
            "service_endpoint" => "ir-na.amazon-adsystem.com",
            "org_unit_id"      => 1,
        ),
        UK   => array(
            "service_endpoint" => "ir-uk.amazon-adsystem.com",
            "org_unit_id"      => 2,
        ),
        DE   => array(
            "service_endpoint" => "ir-de.amazon-adsystem.com",
            "org_unit_id"      => 3,
        ),
        "FR" => array(
            "service_endpoint" => "ir-fr.amazon-adsystem.com",
            "org_unit_id"      => 8,
        ),
        "JP" => array(
            "service_endpoint" => "ir-jp.amazon-adsystem.com",
            "org_unit_id"      => 9,
        ),
        "CA" => array(
            "service_endpoint" => "ir-ca.amazon-adsystem.com",
            "org_unit_id"      => 15,
        ),
        "CN" => array(
            "service_endpoint" => "ir-cn.amazon-adsystem.com",
            "org_unit_id"      => 28,
        ),
        "IT" => array(
            "service_endpoint" => "ir-it.amazon-adsystem.com",
            "org_unit_id"      => 29,
        ),
        "ES" => array(
            "service_endpoint" => "ir-es.amazon-adsystem.com",
            "org_unit_id"      => 30,
        ),
        "IN" => array(
            "service_endpoint" => "ir-in.amazon-adsystem.com",
            "org_unit_id"      => 31,
        ),
        "BR" => array(
            "service_endpoint" => "ir-br.amazon-adsystem.com",
            "org_unit_id"      => 32,
        ),
        "MX" => array(
            "service_endpoint" => "ir-mx.amazon-adsystem.com",
            "org_unit_id"      => 33,
        )
    );
}

?>