
<?php
$isPremium = isValidPremiumEdition();
$hasValidLicense = $isPremium;
$settings = SPDSGVOYoutubeApi::getInstance()->getSettings();
$apiInstance = SPDSGVOYoutubeApi::getInstance();
?>

<div class="card">
    <div class="card-header d-flex">
        <h4 class="card-title"><?= $apiInstance->getName(); ?></h4>
        <small class="ml-auto"><?= __('Content Blocker Id: ', 'shapepress-dsgvo') ?><?= $apiInstance->getSlug(); ?></small>
    </div>
    <div class="card-body">
        <div class="position-relative">
            <?php spDsgvoWritePremiumOverlayIfInvalid($hasValidLicense); ?>
            <form method="post" action="<?= SPDSGVOYoutubeIntegration::formURL() ?>">
                <input type="hidden" name="action" value="<?= SPDSGVOYoutubeIntegration::action() ?>">
                <?php wp_nonce_field(SPDSGVOYoutubeIntegration::action() . '-nonce'); ?>

                <?php
                spDsgvoWriteInput('switch', '', $apiInstance->getSlug().'_enable', $settings['enabled'],
                    sprintf(__('Use %s', 'shapepress-dsgvo'), $apiInstance->getName()),
                    '',
                    sprintf(__("Enabling handles the opt-in for embedded content. The content gets blocked until a user agrees to use %s.",'shapepress-dsgvo'), $apiInstance->getName()));
                ?>




                <div class="form-group">
                    <input type="submit" class="btn btn-primary btn-block" value="<?= _e('Save changes', 'shapepress-dsgvo');?>">
                </div>

                <?php
                spDsgvoWriteInput('text', '', 'sc_imprint', '[lw_content_block type=&quot;'.$apiInstance->getSlug().'&quot;] ... [/lw_content_block]',
                    __('Shortcode to (manually) block content of ', 'shapepress-dsgvo') . $apiInstance->getName(),
                    '',
                    sprintf(__('If automatic detection does not work, you can use this shortcode to block until your vistor do an opt-in. You need to put the content which you want to block within this shortcode tags (instead of ...) After opt-in, the content will be displayed. For instance [lw_content_block type="%s"] &ltdiv&gtPlace some nice links or images of/to %s&lt/div&gt [/lw_content_block]','shapepress-dsgvo'), $apiInstance->getSlug(),$apiInstance->getName()), true, 'border-0', '', false);
                ?>
            </form>
        </div>
    </div>
</div>
