<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (APPPATH . '../assets/facebook-php-sdk/src/facebook.php');

function get_header()
{
	$CI = &get_instance();
	$CI->load->model('home_model');
	$data=array();
	
	if (!$CI->session->userdata('is_user_logged')) {
		$facebook = new Facebook(array(
		  'appId' => '619346231409615',
		  'secret' => 'e200943e63b812569bc3d731c383a4a8',
		));
		$fb_user = $facebook->getUser();

		if ($fb_user) {
			try {
				$user_details = $facebook->api('/me', 'GET', array('fields' => 'name,email'));
				$CI->session->set_userdata('name' => $user_details['name']);
				$CI->session->set_userdata('is_user_logged' => TRUE);
				$CI->home_model->fb_login($fb_user, $user_details['name'], $user_details['email']);
			} catch (FacebookApiException $e) {
				$fb_user = null;
			}
		}
		$CI->session->set_userdata('fb_id' => $fb_user);
		$CI->session->set_userdata('fb_id' => 'appid' => $facebook->getAppID());
	}
	
	$data['name'] = $CI->session->userdata('name');
	$data['is_user_logged'] = $CI->session->userdata('is_user_logged');
	$data['appid'] = $CI->session->userdata('appid');

	$CI->load->view('header', $data);
}

function get_footer()
{
	$CI = &get_instance();
	$CI->load->view('footer');
}

function redirect_logged()
{
	$CI = &get_instance();
	if ($CI->session->userdata('is_user_logged'))
		redirect(base_url());
}

function r_n_logged()
{
	$CI = &get_instance();
	if (! $CI->session->userdata('is_user_logged')) redirect(base_url());
}

/* End of file */