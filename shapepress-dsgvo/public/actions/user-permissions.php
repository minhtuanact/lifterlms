<?php

Class SPDSGVOUserPermissionsAction extends SPDSGVOAjaxAction{

    protected $action = 'user-permissions';

    protected function run(){

        /* i592995 */
        $meta = array();
        //error_log('SPDSGVOUserPermissionsAction: updating settings');
        $version = $this->get('version', NULL, FALSE);

        if($version == 'alt') {
            $services_alt = $this->get('services', NULL, FALSE);
            $services = array();
            foreach($services_alt as $serv) {
                $services[$serv['name']] = $serv['value'];
            }
        } else {
            $services = $this->get('services', NULL, FALSE);
        }

        /* i592995 */

        if(is_array($services)){
            foreach($services as $slug => $service){
                $meta[$slug] = ($service == '1')? '1' : '0';
//                 error_log($slug);
//                 error_log(($service == '1')? '1' : '0');
            }
        }

        if($this->user){
            update_user_meta($this->user->ID, 'sp_dsgvo_user_permissions', $meta);
            createLog("{$this->user->user_email} " . __('updated their user permissions', 'shapepress-dsgvo'));
        }

        if(!isset($services['cookies']) || $services['cookies'] != '1') {
            $past = time() - 3600;
            foreach ( $_COOKIE as $key => $value)
            {
                setcookie( $key, '', 1, '/' );
                unset($_COOKIE[$key]);
            }
            header('Location: '. home_url() .'?v='.time());
        } else {
            setcookie('sp_dsgvo_user_permissions', json_encode($meta), (time()+(365*24*60*60)), '/');
            header('Location: '. get_page_link(SPDSGVOSettings::get('user_permissions_page')) .'?v='.time());
        }
        die;
    }
}

SPDSGVOUserPermissionsAction::listen();
