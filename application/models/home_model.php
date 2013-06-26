<?php

class Home_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function fb_login($fb_user_id, $fb_name, $email)
	{
		$this->db->query('INSERT IGNORE INTO users (reg_type, facebook_id, fullname, email) VALUES (?, ?, ?, ?)', array(1, $fb_user_id, $fb_name, $email));
	}
	
	public function register($fullname, $uname, $email, $password)
	{
		$this->db->query('INSERT INTO users (reg_type, fullname, username, email, password) VALUES (?, ?, ?, ?, ?)', array(0, $fullname, $uname, $email, $password));
	}
	
	public function usernameCheck($uname)
	{
		$result = $this->db->query('select * from users where username=?', array($uname));
		if ($result->row_array()==null)
			return true;
		else
			return false;
	}
	
	public function emailCheck($email)
	{
		$result = $this->db->query('select * from users where email=?', array($email));
		if ($result->row_array()==null)
			return true;
		else
			return false;
	}
	
}

/* End of file */