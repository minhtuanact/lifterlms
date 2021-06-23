<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die('Direct access not allowed');
}


/**
 * Class SimpleGoogleRecaptchaUninstall
 */
class SimpleGoogleRecaptchaUninstall
{
    /**
     * SimpleGoogleRecaptchaUninstall constructor.
     */
    public function __construct()
    {
        $options = ['site_key', 'secret_key', 'login_disable', 'version', 'badge_hide'];

        foreach ($options as $item) {
            delete_option(sprintf('sgr_%s', $item));
        }
    }
}

new SimpleGoogleRecaptchaUninstall();
