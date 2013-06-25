<?php

//require_once (APPPATH . '../assets/facebook-php-sdk/src/facebook.php');

class Home extends CI_Controller {

	public function index()
	{
		$this->show();
	}

	public function show()
	{
		echo $APPPATH;
		$data=array();
		
		$facebook = new Facebook(array(
		  'appId' => '533324980010964',
		  'secret' => 'c72d04c0e80597709ec32bdb0e0d635c',
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