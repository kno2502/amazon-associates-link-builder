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

//some commonly used UI functionality

function aalb_info_notice( $message ) {
    echo "<div class=\"notice notice-info is-dismissible\"><p>INFO - " . $message . "</p></div>";
}

function aalb_warning_notice( $message ) {
    echo "<div class=\"notice notice-warning\"><p>WARNING - " . $message . "</p></div>";
}

function aalb_error_notice( $message ) {
    echo "<div class=\"notice notice-error\"><p>ERROR - " . $message . "</p></div>";
}

function aalb_success_notice( $message ) {
    echo "<div class=\"notice notice-success is-dismissible\"><p>SUCCESS - " . $message . "</p></div>";
}

?>
