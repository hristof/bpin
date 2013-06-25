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
				$user_details = $facebook->api('/me', 'GET', array('fields' => 'name,email', 'scope' => 'email,read_stream'));
				$data['fb_name'] = $user_details['name'];
				var_dump($data);
				$this->CI->home_model->fb_login($fb_user, $user_details['name'], $user_details['email']);
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