<?php

require_once (APPPATH . '../assets/facebook-php-sdk/src/facebook.php');

class Home extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('home_model');
	}

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
		$fb_user = $facebook->getUser();

		if ($fb_user) {
			try {
				$fb_username = $facebook->api('/me', 'GET', array('fields' => 'username'));
				var_dump($fb_username);
				$data['fb_username'] = $fb_username;
				$this->home_model->fb_login($fb_user);
			} catch (FacebookApiException $e) {
				$user = null;
			}
		}
		$data['fb_user'] = $fb_user;
		$data['appid'] = $facebook->getAppID();
		
		$this->load->view('header', $data);
		$this->load->view('listing');
		$this->load->view('footer');
	}
}

/* End of file */