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
 * Fired while making a GET request.
 *
 * Generic class that can be used by any class to get the data from the cache.
 * If the data is not available in the cache, a remote GET request is made.
 *
 * @since      1.0.0
 * @package    AmazonAssociatesLinkBuilder
 * @subpackage AmazonAssociatesLinkBuilder/includes
 */
class Aalb_Cache_Loader {

    public $loader;
    protected $helper;

    public function __construct( $loader ) {
        $this->loader = $loader;
        $this->helper = new Aalb_Helper();
    }

    /**
     * If the information is in the cache, then retrieve the information from the cache.
     * Else get the information by making a GET request.
     *
     * @param string $key Unique identification of the information.
     * @param string $url URL for making a request.
     *
     * @return string GET Response.
     */
    public function load( $key, $url ) {
        $info = $this->lookup( $key );
        if ( $info !== false ) {
            return $info;
        } else {
            return $this->load_and_save( $key, $url );
        }
    }

    /**
     * Lookup in the cache for a particular key.
     * If the key exists in the cache, the data is return.
     * Else false is returned.
     *
     * @param string $key Unique identification of the information.
     *
     * @return string  Data in the cache.
     */
    private function lookup( $key ) {
        return get_transient( $key );
    }

    /**
     * Load the information with a GET request and save it in the cache. Return the loaded information.
     *
     * @param string $key Unique identification of the information.
     * @param string $url URL for making a request.
     *
     * @return string  GET Response.
     */
    private function load_and_save( $key, $url ) {
        $info = $this->loader->load( $url );

        //use wordpress linkcode
        $info = str_replace( 'linkCode%3Dxm2', 'linkCode%3Dalb', $info );
        $info = str_replace( 'linkCode=xm2', 'linkCode=alb', $info );

        $this->helper->clear_expired_transients_at_intervals();
        set_transient( $key, $info, AALB_CACHE_FOR_ASIN_RAWINFO_TTL );

        return $info;
    }
}

?>
