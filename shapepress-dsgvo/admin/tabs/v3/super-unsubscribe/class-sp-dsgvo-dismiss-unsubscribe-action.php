<?php
/* i592995 */
Class SPDSGVODismissUnsubscribeAction extends SPDSGVOAjaxAction{

    protected $action = 'admin-dismiss-unsubscribe';

    protected function run(){
        $id = $this->get('id');
        wp_delete_post($id);
        die();
    }
}

SPDSGVODismissUnsubscribeAction::listen();

/* i592995 */
