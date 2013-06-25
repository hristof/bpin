<?php

class Home_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function fb_login($fb_user_id)
	{
		$this->db->query('INSERT INTO users (reg_type, facebook_id) VALUES (?, ?)', array(1, $fb_user_id));
	}
	
}

/* End of file */