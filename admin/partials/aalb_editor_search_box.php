<!--
Copyright 2016-2017 Amazon.com, Inc. or its affiliates. All Rights Reserved.

Licensed under the GNU General Public License as published by the Free Software Foundation,
Version 2.0 (the "License"). You may not use this file except in compliance with the License.
A copy of the License is located in the "license" file accompanying this file.

This file is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
either express or implied. See the License for the specific language governing permissions
and limitations under the License.
*/
-->

<!--

  UI for Search box shown in WordPress editors. User can type in keyword and trigger add short code box.

-->

<div class="aalb-admin-inline aalb-admin-searchbox">
    <img src=<?= AALB_ADMIN_ICON ?> class="aalb-admin-searchbox-amzlogo"
        style="height: 30px; width: 30px; padding: 0px;">
    <input type="text" class="aalb-admin-input-search"
        name="aalb-admin-input-search"
        placeholder="Enter keyword(s)"
        onkeypress='aalb_submit_event(event,"aalb-admin-button-create-amazon-shortcode",this)'/>
    <a class="button aalb-admin-button-create-amazon-shortcode" title="Add Amazon Associates Link Builder Shortcode"
        onclick="aalb_admin_show_create_shortcode_popup(this)">Search
    </a>
</div>