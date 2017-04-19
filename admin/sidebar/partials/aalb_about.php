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

include 'aalb_admin_ui_common.php'; ?>
<div class="wrap">
    <h2><?= AALB_PROJECT_TITLE ?></h2>
    <div class="card" style="max-width:100%;">
        <h2>About Amazon Associates Program</h2>
        <p>
            The Amazon Associates Program is one of the original affiliate marketing programs. Available in geographies
            across the globe, the Amazon Associates Program has been partnering with content creators to help them
            monetize their passions since 1996. To learn more about the Amazon Associates Program, please click <a
                    target="_blank" href="https://affiliate-program.amazon.com/">here</a>.
        </p>
        <h2>About Amazon Associates Link Builder </h2>
        <p>
            Link Builder is the official free Amazon Associates Program plugin for WordPress. The plugin enables you to
            search for products in the Amazon catalog, access real-time price and availability information, and easily
            create links in your posts to products on Amazon.com. You will be able to generate text links, create custom
            ad units, or take advantage of out-of-the-box widgets that we’ve designed and included with the plugin.
        </p>
        <b>Note</b>
        <ul>
            <li>
                You must review and accept the Amazon Associates Link Builder <a
                        href="https://s3.amazonaws.com/aalb-public-resources/documents/AssociatesLinkBuilder-ConditionsOfUse-2017-01-17.pdf"
                        target="_blank">Conditions of Use</a>.
            </li>
            <li>
                The plugin is currently in beta form. We intend to regularly add new features and enhancements
                throughout the beta period and beyond, and welcome your feedback and input.
            </li>
        </ul>
        <h2>Getting Started</h2>
        <h3>Step 1 - Become an Associate</h3>
        <p>
            To become an Associate, create an Amazon Associates account using URL for your country:
        </p>
        <table border="0" cellpadding="10">
            <tr>
                <td><b>Brazil</b></td>
                <td>
                    <a href="https://associados.amazon.com.br/" target="_blank">https://associados.amazon.com.br/</a>
                </td>
            </tr>
            <tr>
                <td><b>Canada</b></td>
                <td>
                    <a href="https://associates.amazon.ca/" target="_blank">https://associates.amazon.ca/</a>
                </td>
            </tr>
            <tr>
                <td><b>China</b></td>
                <td>
                    <a href="https://associates.amazon.cn/" target="_blank">https://associates.amazon.cn/</a>
                </td>
            </tr>
            <tr>
                <td><b>France</b></td>
                <td>
                    <a href="http://partenaires.amazon.fr/" target="_blank">http://partenaires.amazon.fr/</a>
                </td>
            </tr>
            <tr>
                <td><b>Germany</b></td>
                <td>
                    <a href="https://partnernet.amazon.de/" target="_blank">https://partnernet.amazon.de/</a>
                </td>
            </tr>
            <tr>
                <td><b>India</b></td>
                <td>
                    <a href="http://affiliate-program.amazon.in/"
                            target="_blank">http://affiliate-program.amazon.in/</a>
                </td>
            </tr>
            <tr>
                <td><b>Italy</b></td>
                <td>
                    <a href="https://programma-affiliazione.amazon.it/" target="_blank">https://programma-affiliazione.amazon.it/</a>
                </td>
            </tr>
            <tr>
                <td><b>Japan</b></td>
                <td>
                    <a href="https://affiliate.amazon.co.jp/" target="_blank">https://affiliate.amazon.co.jp/</a>
                </td>
            </tr>
            <tr>
                <td><b>Mexico</b></td>
                <td>
                    <a href="https://afiliados.amazon.com.mx/" target="_blank">https://afiliados.amazon.com.mx/</a>
                </td>
            </tr>
            <tr>
                <td><b>Spain</b></td>
                <td>
                    <a href="https://afiliados.amazon.es/" target="_blank">https://afiliados.amazon.es/</a>
                </td>
            </tr>
            <tr>
                <td><b>United Kingdom</b></td>
                <td>
                    <a href="https://affiliate-program.amazon.co.uk/" target="_blank">https://affiliate-program.amazon.co.uk/</a>
                </td>
            </tr>
            <tr>
                <td><b>United States</b></td>
                <td>
                    <a href="https://affiliate-program.amazon.com/" target="_blank">https://affiliate-program.amazon.com/</a>
                </td>
            </tr>
        </table>
        <p>
            Your Associate ID works only in the country in which you register. If you’d like to be an Associate in more
            than one country, please register separately for each country.
        </p>
        <h3>Step 2 - Sign up for the Amazon Product Advertising API</h3>
        <p>
            Sign up for the Amazon Product Advertising API by following the instructions listed
            <a target="_blank" href="http://docs.aws.amazon.com/AWSECommerceService/latest/DG/CHAP_GettingStarted.html">here</a>.
            The Amazon Product Advertising API is a popular e-commerce service, powering Amazon-integrated experiences
            around the world, serving tens of thousands of applications and more than 1 billion API requests every day.
            It exposes a web-service, which allows Associates to programmatically search and look up items in the Amazon
            product catalog. The Link Builder plugin integrates the Product Advertising API, allowing you to access
            Amazon.com product catalog data without requiring additional software development.
        </p>
        <h3>Step 3 - Configure plugin for first use </h3>
        <p>
            Use the Associates Link Builder->Settings screen to configure the plugin.
        </p>
        <ol>
            <li>Set Access Key ID and Secret Access Key in the Settings section. These credentials are used to invoke
                requests to the Amazon Product Advertising API for fetching information on an item.
            </li>
            <li>Set default Associate ID. Associate ID is used to monitor traffic and sales from your links to Amazon.
                You can also define a list of valid Associate IDs (store ids or tracking ids). You should create a new
                tracking ID in your Amazon Associates account for using it as Associate ID in the plugin.
            </li>
            <li>Set the default Amazon marketplace based on the Amazon Associates Program for which you are registered
                (for example, if you’ve signed up for the Amazon Associates Program in UK, then your default marketplace
                selection should be UK) and select an appropriate template for rendering your ads.
            </li>
        </ol>
        <p>
            That's it! You’re all set to start adding Amazon affiliate links to your posts using the Amazon Associates
            Link Builder plugin!
        </p>
        <h2>User Guide</h2>
        <p>
            Review
            <a target="_blank" href="https://s3.amazonaws.com/aalb-public-resources/documents/AssociatesLinkBuilder-UserGuide.pdf">Link
                Builder User Guide</a> for more information on getting started and key features of the plugin.
        </p>
        <h2>Support</h2>
        <p>If you get stuck, or have any questions, you can ask for help in the <a href="https://wordpress.org/support/plugin/amazon-associates-link-builder"
                    target="_blank">Amazon Associates Link Builder plugin forum</a>.
        </p>
    </div>
</div>