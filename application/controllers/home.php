<?php

require_once (APPPATH . '../assets/facebook-php-sdk/src/facebook.php');

class Home extends CI_Controller {

	public function index()
	{
		$this->show();
	}

	public function show()
	{
		$data=array();
		
		$facebook = new Facebook(array(
		  'appId' => '619346231409615',
		  'secret' => 'e200943e63b812569bc3d731c383a4a8',
		));
		$user = $facebook->getUser();

		if ($user) {
			try {
				$user_profile = $facebook->api('/me');
			} catch (FacebookApiException $e) {
				$user = null;
			}
		}
		$data['fbuser'] = $user;
		if ($user) {
		  $data['fblogoutUrl'] = $facebook->getLogoutUrl();
		} else {
		  $data['fbloginUrl'] = $facebook->getLoginUrl();
		}		
		
		$this->load->view('header', $data);
		$this->load->view('listing');
		$this->load->view('footer');
	}
}

?>