<?php

class Client_Controller extends CI_Controller{
	public $is_user_logged;
	public $user_id;
	public $name;

	function __construct()
	{
		parent::__construct();

		$this->is_user_logged	=	$this->session->userdata("is_user_logged");
		$this->user_id			=	$this->session->userdata("user_id");
		$this->name				=	$this->session->userdata("name");
	}

	function set_flag($key, $value)
	{
		$this->session->set_flashdata($key, $value);
	}

	function get_flag($key)
	{
		return 	$this->session->flashdata($key);
	}
}