<?php

class SPDSGVOIntegration extends SPDSGVOAdminTab{

    public $integrationCategory = '';
    public $slug = '';
    public $title = '';
    public $isPremium = false;
    public $apiInstance = null;

    public function __construct(){
	    $this->isHidden = FALSE;
	    
		if(method_exists($this, 'boot')){
			$this->boot();
		}
	}

	public static function register(){
		$class = get_called_class();
		$self = new $class();

		if(method_exists($self, 'viewSubmit')){
			$action = $class::action();
			add_action("wp_ajax_{$action}", array($self, 'viewSubmit'));
			add_action("wp_ajax_nopriv_{$action}", array($self, 'viewSubmit'));
		}

		add_filter('sp_dsgvo_integrations_'.$self->integrationCategory, array($class, 'registerCallback'));

		if(self::isEnabled($self->slug)){
			add_filter('sp_dsgvo_integrations_safe_'.$self->integrationCategory, array($class, 'registerCallback'));
		}
	}

	public static function registerCallback($integrations){
		$class = get_called_class();
		$self = new $class;
		$integrations[$self->slug] = $self;
		return $integrations;
	}

	public static function getAllIntegrations($type, $safe = TRUE){
		if($safe){
			return apply_filters('sp_dsgvo_integrations_safe_'.$type, array());
		}else{
			return apply_filters('sp_dsgvo_integrations_'.$type, array());
		}
	}

	public static function action(){
		$class = get_called_class();
		return 'SPDSGVO-integration-'. self::slug(); 
	}

	public static function slug(){
		$class = get_called_class();
		$self = new $class;
		return $self->slug;
	}

	// Note: I did this because I don't
	// like the method name page() now, 
	// I prefur name view() instead. So
	// that's why this dirty function exists
	public function page(){
		if(method_exists($this, 'view')){
			$this->view();
		}
	}


	// -----------------------------------------------------
	// Helpers
	// -----------------------------------------------------
	public static function formURL(){
		return admin_url('/admin-ajax.php');
	}

	public static function isEnabled($slug){
		return SPDSGVOSettings::get('is_enabled_'.$slug) === SPDSGVOSettings::get('integration_enable_key');
	}

	public function has($key){
		return isset($_REQUEST[$key]);
	}

	public function get($key, $default = NULL, $stripslashes = TRUE){
		if($this->has($key)){
			if($stripslashes){
				return stripslashes($_REQUEST[$key]);
			}
			return $_REQUEST[$key];
		}
		return $default;
	}

	public function redirectBack(){
		ob_clean();
		header('Location: '. $_SERVER['HTTP_REFERER']);
		die();
	}
	
}