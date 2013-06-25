<?php

require_once (APPPATH . '../assets/facebook-php-sdk/src/facebook.php');

class Home extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model('home');
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
		$fbuser = $facebook->getUser();

		if ($fbuser) {
			try {
				$fbuser_name = $facebook->api('/me', 'GET', array('fields' => 'username'));
				//$this->home->fblogin($fbuser);
			} catch (FacebookApiException $e) {
				$user = null;
			}
		}
		$data['fbuser'] = $fbuser;
		$data['appid'] = $facebook->getAppID();
		
		$this->load->view('header', $data);
		$this->load->view('listing');
		$this->load->view('footer');
	}
}

/* End of file */