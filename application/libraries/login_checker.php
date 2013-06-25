<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

require_once (APPPATH . '../assets/facebook-php-sdk/src/facebook.php');

class Login_checker {
	
	var $CI="";
	
	public function login_checker()
	{
		$this->CI = &get_instance();
		$this->CI->load->model('home_model');
	}
	
	public function check()
	{
		$data=array();
		
		$facebook = new Facebook(array(
		  'appId' => '619346231409615',
		  'secret' => 'e200943e63b812569bc3d731c383a4a8',
		));
		$fb_user = $facebook->getUser();

		if ($fb_user) {
			try {
				$user_details = $facebook->api('/me', 'GET', array('fields' => 'name'));
				$data['fb_name'] = $user_details['name'];
				$this->CI->home_model->fb_login($fb_user, $user_details['name']);
			} catch (FacebookApiException $e) {
				$fb_user = null;
			}
		}
		$data['fb_user'] = $fb_user;
		$data['appid'] = $facebook->getAppID();
		
		return $data;
	}
}
?>