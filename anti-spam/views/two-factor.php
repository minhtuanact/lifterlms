<?php
/**
 * @var array $data
 * @var WP_User[] $user_list
 */

$user_list = $data['user_list']
?><div class="wt-dashboard-container wtitan-section-disabled">

    <div class="wt-dashboard-container ">
        <div class="wt-row">
            <div class="col-md-12 wt-block-gutter important-block manual-block">
                <div class="wt-dashboard-block" style="padding-top:10px;min-height:auto!important;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="title">
                                <span class="step"><?php echo sprintf(__('Step %d', 'titan-security'), 1) ?></span>
                                <span class="title"><?php echo __('Get the App', 'titan-security') ?></span>
                            </div>
                            <div class="content">
                                <?php echo __('Download 2FA Auth app to your smartphone to start using tokens',
                                    'two-factor-auth') ?>
                                <div class="store-links">
                                    <a target="_blank"
                                       href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2"
                                       class="store-link wt-get-it-on-google-play"></a>
                                    <a target="_blank" href="https://apps.apple.com/ru/app/google-authenticator/id388497605"
                                       class="store-link wt-download-on-appstore"></a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="title">
                                <span class="step"><?php echo sprintf(__('Step %d', 'titan-security'), 2) ?></span>
                                <span class="title"><?php echo __('Scan QR code', 'titan-security') ?></span>
                            </div>
                            <div class="content">
                                <?php echo __('Please, scan the following QR code with your app', 'titan-security') ?>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="title">
                                <span class="step"><?php echo sprintf(__('Step %d', 'titan-security'), 3) ?></span>
                                <span class="title"><?php echo __('Enter token', 'titan-security') ?></span>
                            </div>
                            <div class="content">
                                <?php echo __('Enter the 6-digit token generated by the app and enable TOTP protection',
                                    'titan-security') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="wt-dashboard-container ">
        <div class="wt-row">
            <div class="col-md-12 wt-block-gutter wt-register-2fa">
                <div class="wt-dashboard-block" style="padding-top:10px;min-height:auto!important;">
                    <div class="row">
                        <div class="col-md-12 wt-dashboard-block-header">
                            <h4 style="margin: 0 10px;"><?=__('2FA activation', 'two-factor-auth')?></h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 qr-block">
                            <img class="wtfa-qr-code" id="qr-code" src="https://chart.googleapis.com/chart?chs=220x220&chld=M%7C0&cht=qr&chl=Buy%20me%20pls" alt="">
                            <button id="qr-refresh" data-process-message="<?php echo __('Updating...', 'titan-security') ?>" disabled>
                                <span class="dashicons dashicons-update" style="padding-top: 5px;"></span>
                                <?php echo __('Refresh', 'titan-security') ?>
                            </button>
                        </div>
                        <div class="col-lg-10 qr-answer-block">
                            <div class="action-description">
                                <h4><?php echo __('Scan QR code', 'titan-security') ?></h4>
                                <div class="text">
                                    <p><?php echo __('Please, scan the following QR code with your app',
                                            'titan-security') ?></p>
                                </div>
                                <div class="buttons">
                                    <input type="text" id="code" disabled>
                                    <button id="send-code" class="register_2fa_app button primary" disabled
                                            data-process-message="<?php echo __('Sending...', 'titan-security') ?>">
                                        <?php echo __('Send auth code', 'titan-security') ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if ( current_user_can( 'list_users' ) ): ?>
        <div class="wt-dashboard-container">
            <div class="wt-row">
                <div class="col-md-12 wt-block-gutter">
                    <div class="wt-dashboard-block tfa-users" style="min-height: auto !important;">
                        <div class="row">
                            <div class="col-md-12 wt-dashboard-block-header">
                                <h4><?=__( 'Users', 'titan-security' )?></h4>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="wtitan-scanner-vulner-table-container">
                                        <table class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <td><?=__( 'Username', 'titan-security' )?></td>
                                                <td><?=__( 'Two-Factor Enabled?', 'titan-security' )?></td>
                                                <td><?=__( 'Actions', 'titan-security' )?></td>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php foreach( $user_list as $u ): ?>
                                                <tr>
                                                    <td><?=$u->user_login?></td>
                                                    <td><?=__( 'No', 'titan-security')?></td>
                                                    <td>
                                                            <button data-action="change-2fa-state" data-value="on"
                                                                    data-user-id="<?=$u->ID?>"
                                                                    class="button" disabled
                                                                    data-process-message="<?=__('Enabling...', 'titan-security')?>">
                                                                <?=__('Enable', 'titan-security')?>
                                                            </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>