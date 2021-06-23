<?php
if (!defined('ABSPATH')) {
    exit;
}

class SupportController extends BaseController
{
   
    public function __construct($alias)
    {
        parent::__construct( __("Support", 'wp-live-chat-support'),$alias);
    }

    public function view($return_html = false, $add_wrapper=true)
    {
	    return $this->load_view(plugin_dir_path(__FILE__) . "support_view.php",$return_html,$add_wrapper);
    }

}
?>