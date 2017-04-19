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
 * Helper class for Paapi
 *
 * @since      1.0.0
 * @package    AmazonAssociatesLinkBuilder
 * @subpackage AmazonAssociatesLinkBuilder/lib/php/Paapi
 */
class Aalb_Paapi_Helper {

    /**
     * Returns the item lookup URL for asins
     *
     * @param string $asin Asin value.
     * @param string $marketplaces Marketplace to search the products.
     * @param string $tracking_id Associate tag.
     *
     * @return string Signed URL for item lookup.
     */
    function get_item_lookup_url( $asin, $marketplace, $tracking_id ) {
        $params = array(
            "Operation" => "ItemLookup", "ItemId" => "$asin", "IdType" => "ASIN", "ResponseGroup" => "Images,ItemAttributes,OfferFull", "AssociateTag" => "$tracking_id",
        );
        $url = $this->aws_signed_url( $params, $marketplace );

        return $url;
    }

    /**
     * Returns signed URL for Paapi request
     *
     * @since 1.0.0
     *
     * @param array $params Paapi parameters.
     * @param string $marketplace Marketplace to search the product.
     *
     * @return string Signed URL.
     */
    function aws_signed_url( $params, $marketplace ) {
        $access_key_id = openssl_decrypt( base64_decode( get_option( AALB_AWS_ACCESS_KEY ) ), AALB_ENCRYPTION_ALGORITHM, AALB_ENCRYPTION_KEY, 0, AALB_ENCRYPTION_IV );
        $secret_key = openssl_decrypt( base64_decode( get_option( AALB_AWS_SECRET_KEY ) ), AALB_ENCRYPTION_ALGORITHM, AALB_ENCRYPTION_KEY, 0, AALB_ENCRYPTION_IV );
        $host = $marketplace;

        $method = 'GET';
        $uri = PAAPI_URI;

        $params["Service"] = PAAPI_SERVICE;
        $params["AWSAccessKeyId"] = $access_key_id;
        $params["Timestamp"] = gmdate( 'Y-m-d\TH:i:s\Z' );
        $params["Version"] = PAAPI_VERSION;

        ksort( $params );

        $canonicalized_query = array();
        foreach ( $params as $param => $value ) {
            $param = str_replace( "%7E", "~", rawurlencode( $param ) );
            $value = str_replace( "%7E", "~", rawurlencode( $value ) );
            $canonicalized_query[] = $param . "=" . $value;
        }

        $canonicalized_query = implode( "&", $canonicalized_query );

        $string_to_sign = $method . "\n" . $host . "\n" . $uri . "\n" . $canonicalized_query;
        $signature = base64_encode( hash_hmac( "sha256", $string_to_sign, $secret_key, true ) );
        $signature = str_replace( "%7E", "~", rawurlencode( $signature ) );

        $signed_url = PAAPI_TRANSFER_PROTOCOL . $host . $uri . PAAPI_URL_QUERY_SEPARATOR . $canonicalized_query . "&Signature=" . $signature;

        return $signed_url;
    }

    /**
     * Returns the item search URL for search keywords
     *
     * @param string $search_keywords Search keywords of the products.
     * @param string $marketplaces Marketplace to search the products.
     * @param string $tracking_id Associate tag.
     *
     * @return string Signed URL for item search.
     */
    function get_item_search_url( $search_keywords, $marketplace, $tracking_id ) {
        $params = array(
            "Operation" => "ItemSearch", "SearchIndex" => "All", "Keywords" => "$search_keywords", "ResponseGroup" => "Images,ItemAttributes,Offers", "AssociateTag" => "$tracking_id",
        );
        $url = $this->aws_signed_url( $params, $marketplace );

        return $url;
    }

    /**
     * PA-API error messages to display in case of request errors
     *
     * @param string $error code Error code of the request.
     *
     * @return string PA-API error message.
     */
    function get_error_message( $error ) {
        switch ( $error ) {
            case HTTP_BAD_REQUEST:
                return HTTP_BAD_REQUEST_MESSAGE;
            case HTTP_FORBIDDEN:
                return HTTP_FORBIDDEN_MESSAGE;
            case HTTP_REQUEST_URI_TOO_LONG:
                return HTTP_REQUEST_URI_TOO_LONG_MESSAGE;
            case HTTP_INTERNAL_SERVER_ERROR:
                return HTTP_INTERNAL_SERVER_ERROR_MESSAGE;
            case HTTP_THROTTLE:
                return HTTP_THROTTLE_MESSAGE;
            default:
                return $error;
        }
    }

}

?>
