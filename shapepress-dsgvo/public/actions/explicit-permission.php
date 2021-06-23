<?php

Class SPDSGVOExplicitPermissionAction extends SPDSGVOAjaxAction{

    protected $action = 'explicit-permission';

    protected function run(){

        if($this->get('permission') === 'granted'){
            setcookie('sp_dsgvo_explicit_permission_given', wp_hash(SPDSGVOSettings::get('terms_conditions')), (time()+(365*24*60*60)), '/');
            setcookie('sp_dsgvo_explicit_permission_declined', '', (time()-60), '/');

            if(is_user_logged_in()){
                update_user_meta($this->user->ID, 'sp_dsgvo_explicit_permission_granted', '1');
                update_user_meta($this->user->ID, 'sp_dsgvo_explicit_permission_date', date("Y-m-d H:i:s"));
                update_user_meta($this->user->ID, 'sp_dsgvo_explicit_permission_hash', wp_hash(SPDSGVOSettings::get('terms_conditions')));
                update_user_meta($this->user->ID, 'sp_dsgvo_explicit_permission_ip_address', $_SERVER['REMOTE_ADDR']?:($_SERVER['HTTP_X_FORWARDED_FOR']?:$_SERVER['HTTP_CLIENT_IP']) );
                
                delete_user_meta($this->user->ID, 'sp_dsgvo_explicit_permission_declined');
            }

            do_action('sp_dsgvo_explicit_permission_granted', (is_user_logged_in() ? wp_get_current_user() : NULL));
        }else{

            setcookie('sp_dsgvo_explicit_permission_declined', '1', (time()+(365*24*60*60)), '/');
            setcookie('sp_dsgvo_explicit_permission_given', '', (time()-60), '/');

            if(is_user_logged_in()){
                update_user_meta($this->user->ID, 'sp_dsgvo_explicit_permission_declined', '1');

                delete_user_meta($this->user->ID, 'sp_dsgvo_explicit_permission_granted');
                delete_user_meta($this->user->ID, 'sp_dsgvo_explicit_permission_date');
                delete_user_meta($this->user->ID, 'sp_dsgvo_explicit_permission_hash');
                delete_user_meta($this->user->ID, 'sp_dsgvo_explicit_permission_ip_address');
            }

            do_action('sp_dsgvo_explicit_permission_declined', (is_user_logged_in() ? wp_get_current_user() : NULL));
        }

    	wp_redirect(home_url());
		exit;
    }
}

SPDSGVOExplicitPermissionAction::listen();