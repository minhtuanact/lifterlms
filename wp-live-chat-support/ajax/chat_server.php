<?php

if ( ! defined( 'ABSPATH' ) ) {
	die();
}

add_action( 'wp_ajax_nopriv_wplc_verify_agents_session', 'wplc_verify_agents_session' );
add_action( 'wp_ajax_nopriv_wplc_set_session_status', 'wplc_set_session_status' );

function wplc_verify_agents_session(){
	$headers = getallheaders();
	if(!array_key_exists('X-Api-Key',$headers) || $headers['X-Api-Key']!="012345678901" ){
		header("HTTP/1.1 401 Unauthorized");
		exit;
	}
	if(!isset($_POST["agentCode"]) || empty($_POST["agentCode"]) )
	{
		die( TCXAjaxResponse::error_ajax_respose( "Invalid Request" ) );
	}

	$agent_id = get_transient("wplc_agent_code_".$_POST["agentCode"]);

	if(TCXAgentsHelper::agent_is_online($agent_id))
	{
		$result = new stdClass();
		$result->agentID = $agent_id;
		die(TCXAjaxResponse::success_ajax_respose($result));
	}
	else
	{
		header("HTTP/1.1 401 Unauthorized");
		exit;
	}
}

function wplc_set_session_status(){
	if(!isset($_POST["sessionId"]) || empty($_POST["sessionId"]) || !isset($_POST["lastAccess"]) || empty($_POST["lastAccess"])  )
	{
		die( TCXAjaxResponse::error_ajax_respose( "Invalid Request" ) );
	}
	global $wpdb;
	$chat = TCXChatData::get_chat_by_session($wpdb,sanitize_text_field($_POST["sessionId"]));
	TCXChatHelper::update_chat_status($chat,sanitize_text_field($_POST["lastAccess"]));

	die(TCXAjaxResponse::success_ajax_respose($_POST["sessionId"]));
}