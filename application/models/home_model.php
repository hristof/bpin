<?php

class Home_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	function get_user($user_id)
	{
		$query = $this->db->query("SELECT * FROM users WHERE user_id = ?",
		array($user_id));

		return $query->row();
	}

	function get_fb_user($user_id)
	{
		$query = $this->db->query("SELECT * FROM users WHERE facebook_id = ?",
		array($user_id));

		return $query->row();
	}

	public function add_fb_user($fb_user_id, $fb_name, $email)
	{
		$this->db->query('INSERT INTO users (reg_type, facebook_id, fullname, email) VALUES (?, ?, ?, ?)',
		array(1, $fb_user_id, $fb_name, $email));

		return $this->db->insert_id();
	}

	public function register($fullname, $uname, $email, $password)
	{
		$this->db->query('INSERT INTO users (reg_type, fullname, username, email, password) VALUES (?, ?, ?, ?, ?)',
		array(0, $fullname, $uname, $email, $password));
	}

	public function username_check($uname)
	{
		$result = $this->db->query('SELECT * FROM users WHERE username=?',
		array($uname));
		
		if ($result->row_array()==null) return true;
		else return false;
	}

	public function email_check($email)
	{
		$result = $this->db->query('SELECT * FROM users WHERE email=?', array($email));
		if ($result->row_array()==null) return true;
		else return false;
	}
	
	
	public function login_check() {
		$query = $this->db->query('SELECT * FROM users WHERE username=? and password=?',
		array($this->input->post('username'), $this->input->post('password')) );
		
		return $query->row_array();
	}

}

/* End of file */