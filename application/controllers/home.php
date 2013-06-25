<?php

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
		get_header();
		$this->load->view('homepage');
		$this->load->view('footer');
	}
	
	public function register()
	{
		get_header();
		$this->load->view('registration');
		$this->load->view('footer');
	}
}

/* End of file */