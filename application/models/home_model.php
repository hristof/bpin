<?php

class Home_model extends CI_Model {

	public function __construct() {
		parent::__construct();
	}

	public function fb_login($fb_user_id)
	{
		$data = array('reg_type' => 1, 'facebook_id' => $fb_user_id);
		$this->db->insert('users', $data);
	}
	
}

/* End of file */