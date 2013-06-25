<?php

class Home extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('login_checker');
		$this->load->model('home_model');
	}

	public function index()
	{
		$this->show();
	}

	public function show()
	{
		$data = $this->login_checker->check();
		
		$this->load->view('header', $data);
		$this->load->view('homepage');
		$this->load->view('footer');
	}
	
	public function register()
	{
		$data = $this->login_checker->check();
		
		$this->load->view('header', $data);
		$this->load->view('registration');
		$this->load->view('footer');
	}
}

/* End of file */