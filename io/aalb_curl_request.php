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
 * Wrapper class over PHP curl Request
 *
 * @since      1.5.3
 * @package    AmazonAssociatesLinkBuilder
 * @subpackage AmazonAssociatesLinkBuilder/io
 */
class Aalb_Curl_Request {

    /*
     * Get last modified time of a file from a remote url
     *
     * @since 1.5.3
     *
     * @param $url URL of remote file
     *
     * @return string  last_modified_date on success else an Exception
     *
     * @throws Network_Call_Failure_Exception if curl call failed
     * @throws Unexpected_Network_Response_Exception if response is not as expeced and contains undefined values
     *
     */
    public function get_last_modified_date_of_remote_file( $url ) {
        $curl = curl_init( $url );

        //don't fetch the actual page, you only want headers
        curl_setopt( $curl, CURLOPT_NOBODY, true );

        //stop it from outputting stuff to stdout
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );

        // attempt to retrieve the modification date
        curl_setopt( $curl, CURLOPT_FILETIME, true );

        $result = curl_exec( $curl );

        if ( $result === false ) {
            throw new Network_Call_Failure_Exception( curl_error( $curl ) );
        }

        $timestamp = curl_getinfo( $curl, CURLINFO_FILETIME );
        if ( $timestamp != - 1 ) { //otherwise unknown
            return date( "Y-m-d H:i:s", $timestamp ); //etc
        } else {
            throw new Unexpected_Network_Response_Exception( "Unknown timestamp for remote file called from url: " . $url );
        }
    }

    /*
     * Downloads file from a remote url to a temporary files
     *
     * @since 1.5.3
     *
     * @param $url URL of remote file
     *
     * @return string temporray file after downloading from remote url
     *
     * @throws Network_Call_Failure_Exception if execution failed
     *
     */
    public function download_file_to_temporary_file( $url ) {
        $tmp_file = download_url( $url );
        if ( is_wp_error( $tmp_file ) ) {
            throw new Network_Call_Failure_Exception( "WP_ERROR: " . $tmp_file->get_error_message() );
        }

        return $tmp_file;
    }
}