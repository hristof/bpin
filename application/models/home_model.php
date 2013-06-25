<?php

class Home_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function fb_login($fb_user_id, $fb_name, $email)
	{
		$this->db->query('INSERT IGNORE INTO users (reg_type, facebook_id, fullname, email) VALUES (?, ?, ?, ?)', array(1, $fb_user_id, $fb_name, $email));
	}
	
}

/* End of file */