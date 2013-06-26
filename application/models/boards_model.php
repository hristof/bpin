<?php

class Boards_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_boards($user_id)
	{
		$query = $this->db->query('SELECT * FROM boards WHERE user_id=?',
		array($user_id));

		return $query;
	}

	public function get($board_id)
	{
		$query = $this->db->query('SELECT * FROM boards WHERE board_id=? AND user_id=?',
		array($board_id, $this->user_id));

		return $query->row_array();
	}

	public function add($user_id)
	{
		$date_added = date('Y-m-d');
		$this->db->query('INSERT INTO boards VALUES (null, ?, ?, ?, ?)',
		array($user_id, $this->input->post('title'), '', $date_added) );
	}

	public function edit($board_id)
	{
		$this->db->query('UPDATE boards SET title=? WHERE board_id=?',
		array($this->input->post('title'), $board_id) );
	}

	public function delete($board_id)
	{
		// Delete the pins from the board
		$this->load->model('pins_model');

		$pins = $this->pins_model->get_all_from_board($board_id);
		foreach($pins as $p)
		{
			$this->pins_model->delete($p);
		}

		// Delete the board itself
		$this->db->query('DELETE FROM boards WHERE board_id=?',
		array($board_id));
	}

	public function set_board_thumb($thumb, $board_id)
	{
		$this->db->query("UPDATE boards SET thumb=? WHERE board_id=?",
		array($thumb, $board_id));
	}
}

/* End of file boards_model.php */
/* Location: ./application/models/boards_model.php */
