
<?php
$isPremium = isValidPremiumEdition();
$isBlog = isValidBlogEdition();
$hasValidLicense = isValidPremiumEdition() || isValidBlogEdition();
$settings = SPDSGVOWpStatisticsApi::getInstance()->getSettings();
$settings['useOwnCode'] = '1';
$apiInstance = SPDSGVOWpStatisticsApi::getInstance();
?>

<div class="card">
    <div class="card-header">
        <h4 class="card-title"><?= $apiInstance->getName(); ?></h4>
    </div>
    <div class="card-body">


            <form method="post" action="<?= SPDSGVOWpStatisticsIntegration::formURL() ?>">
                <input type="hidden" name="action" value="<?= SPDSGVOWpStatisticsIntegration::action() ?>">
                <?php wp_nonce_field(SPDSGVOWpStatisticsIntegration::action() . '-nonce'); ?>

                <?php
                spDsgvoWriteInput('switch', '', $apiInstance->getSlug().'_enable', $settings['enabled'],
                    sprintf(__('Use %s', 'shapepress-dsgvo'), $apiInstance->getName()),
                    '',
                    sprintf(__("Enabling shows %s. in the cookie popup if option Show cookie notice/popup although it is not needed is enabled. Furthermore the required text for the privacy policy gets added.",'shapepress-dsgvo'), $apiInstance->getName()). " ".__('Because WP Statistics date is stored in your local database, this integration is always set to on in the cookie popup. ','shapepress-dsgvo'));
                ?>


                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-block" value="<?= _e('Save changes', 'shapepress-dsgvo');?>">
                </div>
            </form>
    </div>
</div>
