    <style>
        .sfsi-footer-container {
            margin: 0 auto;
            text-align: center;
            width: 72%;
        }

        .sfsi-footer-pointing-to-premium-plugin {
            float: left;
            background: #fff;
            padding: 33px 0px;
            color: #414951 !important;
        }

        .sfsi-footer-pointing-heading {
            margin-top: 40px;
            text-align: center;
            padding-bottom: 15px;
        }

        .sfsi-footer-pointing-heading p {
            font-size: 23px;
            font-weight: 600;
        }

        .sfsi-footer-pointing-heading .sfsi-green-heading {
            color: #12a252;
        }

        .sfsi-footer-pointing-content {
            background: #fff;
        }

        .sfsi-footer-left-section {
            width: 57%;
            position: relative;
            min-height: 1px;
            float: left;
            padding-right: 15px;
            padding-left: 15px;
            margin-left: 15px;
        }

        .sfsi-footer-right-section {
            width: 34%;
            position: relative;
            min-height: 1px;
            float: left;
            padding-right: 15px;
            padding-left: 15px;
            text-align: center;
        }

        .sfsi-equal-col-md-6 {
            width: 50%;
            position: relative;
            min-height: 1px;
            float: left;
        }

        .sfsi-left-image-section {
            width: 52px;
            display: inline-block;
            text-align: center;
            height: 43px;
            vertical-align: middle;
        }

        .sfsi-li-right-content {
            text-align: left;
            padding-left: 25px;
            font-size: 16px;
            font-weight: 600;
            vertical-align: middle;
            width: calc(100% - 95px);
            display: inline-block;
        }

        .sfsi-footer-left-section ul li:not(:last-child) {
            margin-bottom: 20px;
        }

        .sfsi-checkout-premium-plugin-button {
            font-size: 18px;
            text-align: center;
            background: #12a252;
            color: #FFF !important;
            padding: 11px 30px;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
            font-weight: 700;
        }

        .so-much-more {
            float: left;
            width: 100%;
            text-align: center;
            font-size: 17px;
            margin-top: 28px !important;
            font-weight: 700 !important;
        }

        .so-much-more a {
            color: #12a252;
        }

        .sfsi-footer-bottom-text {
            float: left;
            width: 99%;
            text-align: center;
            color: #414951 !important;
            padding-top: 15px !important;
            margin-bottom: 50px !important;
        }

        .sfsi-footer-bottom-text p {
            font-size: 18px;
        }

        @media(min-width: 320px) and (max-width: 480px) {
            .sfsi-footer-container {
                width: 100% !important;
            }

            .sfsi-footer-bottom-text {
                display: inline-block;
                float: left !important;
                width: 100% !important;
            }
        }

        @media (min-width: 320px) and (max-width: 767px) {
            .sfsi-equal-col-xs-12 {
                width: 100%;
            }

            .sfsi-footer-left-section {
                width: 76%;
            }

            .sfsi-footer-right-section {
                width: 88%;
                padding-top: 30px;
            }

            .sfsi-right-video iframe {
                height: 125px;
            }

            .sfsi-checkout-premium-plugin-button {
                font-size: 16px;
            }

            .sfsi-footer-bottom-text {
                float: unset !important;
                width: 65%;
                margin-left: auto;
                margin-right: auto;
            }
        }

        @media(min-width: 768px) and (max-width: 1023px) {
            .sfsi-footer-container {
                width: 100%;
            }

            .sfsi-footer-left-section {
                width: 95%;
            }

            .sfsi-footer-right-section {
                width: 95%;
                margin-top: 20px;
            }

            .sfsi-footer-pointing-heading p {
                font-size: 20px;
            }
        }

        @media(min-width: 1024px) and (max-width: 1032px) {
            .sfsi-footer-container {
                width: 100%;
            }

            .sfsi-footer-left-section {
                width: 56%;
                margin-left: 0px !important;
            }

            .sfsi-footer-right-section {
                width: 34%;
            }
        }

        @media(min-width: 1033px) and (max-width: 1050px) {
            .sfsi-footer-left-section {
                width: 56%;
            }

            .sfsi-footer-right-section {
                width: 35%;
            }
        }

        @media(min-width: 1051px) and (max-width: 1115px) {
            .sfsi-footer-left-section {
                width: 52%;
            }

            .sfsi-footer-right-section {
                width: 39%;
            }
        }

        @media(min-width: 1116px) and (max-width: 1223px) {
            .sfsi-footer-left-section {
                width: 52%;
            }

            .sfsi-footer-right-section {
                width: 40%;
            }
        }

        @media(min-width: 1224px) and (max-width: 1347px) {
            .sfsi-footer-left-section {
                width: 50%;
            }

            .sfsi-footer-right-section {
                width: 43%;
            }
        }

        @media(min-width: 1348px) and (max-width: 2300px) {
            .sfsi-footer-left-section {
                width: 51%;
            }

            .sfsi-footer-right-section {
                width: 40%;
            }
        }

        @media (max-width: 1023px) and (min-width: 768px) {
            .welcometext {
                width: 100%;
            }
        }
    </style>

    <!-- <div style="clear:both">
        <?php // $noncea = wp_create_nonce("sfsi_plus_installDate"); ?>
        <label style="font-size: 17px;">Installed date: </label>
        <input type="text" name="sfsi_plus_installDate" placeholder="date" value="<?php // echo get_option("sfsi_plus_installDate"); ?>">
        <button type="button" id="sfsi_plus_installDate" data-nonce="<?php // echo $noncea; ?>"> click</button>
    </div> -->

    <!-- <div style="clear:both">
    <?php  // $noncec = wp_create_nonce("sfsi_plus_currentDate");
    ?>
    <label style="font-size: 17px;">Current date: </label>
    <input type="text" name="sfsi_plus_currentDate" placeholder="date" value="<?php // echo get_option("sfsi_plus_currentDate"); ?>">
    <button type="button" id="sfsi_plus_currentDate" data-nonce="<?php // echo $noncec; ?>"> click</button>
</div> -->
    <!-- <div style="clear:both">
        <?php // $noncec = wp_create_nonce("sfsi_plus_showNextBannerDate"); ?>
        <label style="font-size: 17px;">show next banner in (ex: 1 seconds,1 minute,1 hour,1 day ): </label>
        <input type="text" name="sfsi_plus_showNextBannerDate" placeholder="no of days" value="<?php // echo get_option("sfsi_plus_showNextBannerDate"); ?>">
        <button type="button" id="sfsi_plus_showNextBannerDate" data-nonce="<?php // echo $noncec; ?>"> click</button>
    </div>
    <div style="clear:both">
        <?php // $noncec = wp_create_nonce("sfsi_plus_cycleDate"); ?>
        <label style="font-size: 17px;">Cycle in (ex: 1 seconds,1 minute,1 hour,1 day ): </label>
        <input type="text" name="sfsi_plus_cycleDate" placeholder="no of days" value="<?php // echo get_option("sfsi_plus_cycleDate"); ?>">
        <button type="button" id="sfsi_plus_cycleDate" data-nonce="<?php // echo $noncec; ?>"> click</button>
    </div> -->
    <!-- <div style="clear:both">
        <?php // $noncec = wp_create_nonce("sfsi_plus_loyaltyDate"); ?>
        <label style="font-size: 17px;">Loyalty in (ex: 1 seconds,1 minute,1 hour,1 day ): </label>
        <input type="text" name="sfsi_plus_loyaltyDate" placeholder="no of days" value="<?php // echo get_option("sfsi_plus_loyaltyDate"); ?>">
        <button type="button" id="sfsi_plus_loyaltyDate" data-nonce="<?php // echo $noncec; ?>"> click</button>
    </div>
    <?php
    // $sfsi_plus_banner_global_firsttime_offer = maybe_unserialize(get_option('sfsi_plus_banner_global_firsttime_offer', false));
    // $sfsi_plus_banner_global_pinterest = maybe_unserialize(get_option('sfsi_plus_banner_global_pinterest', false));
    // $sfsi_plus_banner_global_social = maybe_unserialize(get_option('sfsi_plus_banner_global_social', false));
    // $sfsi_plus_banner_global_load_faster = maybe_unserialize(get_option('sfsi_plus_banner_global_load_faster', false));
    // $sfsi_plus_banner_global_shares = maybe_unserialize(get_option('sfsi_plus_banner_global_shares', false));
    // $sfsi_plus_banner_global_gdpr = maybe_unserialize(get_option('sfsi_plus_banner_global_gdpr', false));
    // $sfsi_plus_banner_global_http = maybe_unserialize(get_option('sfsi_plus_banner_global_http', false));
    // $sfsi_plus_banner_global_upgrade = maybe_unserialize(get_option('sfsi_plus_banner_global_upgrade', false));
    ?>

    <div style="clear:both">
        <?php // $noncef = wp_create_nonce("sfsi_plus_banner_global_firsttime_offer"); ?>
        <label style="font-size: 17px;">Dismiss Firsttime offer time:</label>
        <input type="text" name="sfsi_plus_banner_global_firsttime_offer" placeholder="date" value="<?php // echo $sfsi_plus_banner_global_firsttime_offer['timestamp'] ?>">
        <button type="button" id="sfsi_plus_banner_global_firsttime_offer" data-nonce="<?php // echo $noncef; ?>" style="display: <?php // echo $sfsi_plus_banner_global_firsttime_offer['is_active'] == "yes" ? "inline-block" : "none" ?>;"> This banner will appear</button>
    </div>

    <div style="clear:both">
        <?php // $noncep = wp_create_nonce("sfsi_plus_banner_global_pinterest"); ?>
        <label style="font-size: 17px;">Dismiss Pinterest time: </label>
        <input type="text" name="sfsi_plus_banner_global_pinterest" placeholder="date" value="<?php // echo $sfsi_plus_banner_global_pinterest['timestamp'] ?>">
        <button type="button" id="sfsi_plus_banner_global_pinterest" data-nonce="<?php // echo $noncep; ?>" style="display: <?php // echo $sfsi_plus_banner_global_pinterest['is_active'] == "yes" ? "inline-block" : "none" ?>;"> This banner will appear</button>
    </div>

    <div style="clear:both">
        <?php // $noncem = wp_create_nonce("sfsi_plus_banner_global_social"); ?>
        <label style="font-size: 17px;">Dismiss Mobile time: </label>
        <input type="text" name="sfsi_plus_banner_global_social" placeholder="date" value="<?php // echo $sfsi_plus_banner_global_social['timestamp'] ?>">
        <button type="button" id="sfsi_plus_banner_global_social" data-nonce="<?php // echo $noncem; ?>" style="display: <?php // echo $sfsi_plus_banner_global_social['is_active'] == "yes" ? "inline-block" : "none" ?>;"> This banner will appear</button>
    </div> -->

    <!-- <div style="clear:both">
        <?php // $noncel = wp_create_nonce("sfsi_plus_banner_global_load_faster"); ?>
        <label style="font-size: 17px;">Dismiss Load faster time: </label>
        <input type="text" name="sfsi_plus_banner_global_load_faster" placeholder="date" value="<?php // echo $sfsi_plus_banner_global_load_faster['timestamp'] ?>">
        <button type="button" id="sfsi_plus_banner_global_load_faster" data-nonce="<?php // echo $noncel; ?>" style="display: <?php // echo $sfsi_plus_banner_global_load_faster['is_active'] == "yes" ? "inline-block" : "none" ?>;"> This banner will appear</button>
    </div>

    <div style="clear:both">
        <?php  // $nonces = wp_create_nonce("sfsi_plus_banner_global_shares"); ?>
        <label style="font-size: 17px;">Dismiss Shares time: </label>
        <input type="text" name="sfsi_plus_banner_global_shares" placeholder="date" value="<?php // echo $sfsi_plus_banner_global_shares['timestamp'] ?>">
        <button type="button" id="sfsi_plus_banner_global_shares" data-nonce="<?php // echo $nonces; ?>" style="display: <?php // echo $sfsi_plus_banner_global_shares['is_active'] == "yes" ? "inline-block" : "none" ?>;"> This banner will appear</button>
    </div>

    <div style="clear:both">
        <?php // $nonceg = wp_create_nonce("sfsi_plus_banner_global_gdpr"); ?>
        <label style="font-size: 17px;">Dismiss Gdpr time: </label>
        <input type="text" name="sfsi_plus_banner_global_gdpr" placeholder="date" value="<?php // echo $sfsi_plus_banner_global_gdpr['timestamp'] ?>">
        <button type="button" id="sfsi_plus_banner_global_gdpr" data-nonce="<?php // echo $nonceg; ?>" style="display: <?php // echo $sfsi_plus_banner_global_gdpr['is_active'] == "yes" ? "inline-block" : "none" ?>;"> This banner will appear</button>
    </div>

    <div style="clear:both">
        <?php // $nonceh = wp_create_nonce("sfsi_plus_banner_global_http"); ?>
        <label style="font-size: 17px;">Dismiss Http time: </label>
        <input type="text" name="sfsi_plus_banner_global_http" placeholder="date" value="<?php // echo $sfsi_plus_banner_global_http['timestamp'] ?>">
        <button type="button" id="sfsi_plus_banner_global_http" data-nonce="<?php // echo $nonceh; ?>" style="display: <?php // echo $sfsi_plus_banner_global_http['is_active'] == "yes" ? "inline-block" : "none" ?>;"> This banner will appear</button>
    </div>

    <div style="clear:both">
        <?php // $nonceu = wp_create_nonce("sfsi_plus_banner_global_upgrade"); ?>
        <label style="font-size: 17px;">Dismiss Loyalty time: </label>
        <input type="text" name="sfsi_plus_banner_global_upgrade" placeholder="date" value="<?php // echo $sfsi_plus_banner_global_upgrade['timestamp'] ?>">
        <button type="button" id="sfsi_plus_banner_global_upgrade" data-nonce="<?php // echo $nonceu; ?>" style="display: <?php // echo $sfsi_plus_banner_global_upgrade['is_active'] == "yes" ? "inline-block" : "none" ?>;"> This banner will appear</button>
    </div> -->
