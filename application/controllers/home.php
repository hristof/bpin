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
				$user_details = $facebook->api('/me', 'GET', array('fields' => 'name'));
				$data['fb_name'] = $user_details['name'];
				$this->home_model->fb_login($fb_user, $user_details['name']);
			} catch (FacebookApiException $e) {
				$fb_user = null;
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