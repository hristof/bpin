<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

require_once (APPPATH . '../assets/facebook-php-sdk/src/facebook.php');

function get_header()
{
	$CI = &get_instance();
	$CI->load->model('home_model');
	$data=array();
	
	$facebook = new Facebook(array(
	  'appId' => '619346231409615',
	  'secret' => 'e200943e63b812569bc3d731c383a4a8',
	));
	$fb_user = $facebook->getUser();

	if ($fb_user) {
		try {
			$user_details = $facebook->api('/me', 'GET', array('fields' => 'name,email'));
			$data['fb_name'] = $user_details['name'];
			$CI->home_model->fb_login($fb_user, $user_details['name'], $user_details['email']);
		} catch (FacebookApiException $e) {
			$fb_user = null;
		}
	}
	$data['fb_user'] = $fb_user;
	$data['appid'] = $facebook->getAppID();
	
	$CI->load->view('header', $data);
}

function get_footer()
{
	$CI = &get_instance();
	$CI->load->view('footer');
}

function check_for_logged_user()
{
	$CI = &get_instance();
	
	$facebook = new Facebook(array(
	  'appId' => '619346231409615',
	  'secret' => 'e200943e63b812569bc3d731c383a4a8',
	));
	$fb_user = $facebook->getUser();
	if ($fb_user) {
		try {
			$user_details = $facebook->api('/me');
		} catch (FacebookApiException $e) {
			$fb_user = null;
		}
	}
	
	if ($fb_user || $CI->session->userdata('logged_in'))
		redirect(base_url());
}

/* End of file */