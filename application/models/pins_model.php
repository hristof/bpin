<?php

class Pins_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get($pin_id)
	{
		$query = $this->db->query("SELECT * FROM pins WHERE pin_id=? AND user_id=?",
		array($pin_id, $this->user_id));

		return $query->row();
	}

	public function get_user_pins_count($board_id)
	{
		$query = $this->db->query("SELECT COUNT(*) AS cnt FROM pins WHERE board_id=?",
		array($board_id));

		return $query->row()->cnt;
	}

	public function get_user_pins_list($board_id)
	{
		$query = $this->db->query("SELECT * FROM pins WHERE board_id=?",
		array($board_id));

		return $query->result();
	}

	public function add($image)
	{
		$_P=remove_html($_POST);

		$this->db->query('INSERT INTO pins SET board_id=?, user_id=?, title=?, thumb=?, link=?, date_added=?',
		array($_P['board_id'], $this->user_id, $_P['title'], $image, $_P['site_url'], time()));

		return $this->db->insert_id();
	}

	public function edit($pin_id)
	{
		$_P=remove_html($_POST);

		$this->db->query('UPDATE pins SET board_id=?, title=? WHERE pin_id=?',
		array($_P['board_id'], $_P['title'], $pin_id));
	}
}

/* End of file pins_model.php */
/* Location: ./application/models/pins_model.php */
