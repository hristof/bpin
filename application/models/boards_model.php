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
		$query = $this->db->query('SELECT * FROM boards WHERE board_id=?',
		array($board_id));
		
		return $query->row_array();
	}
	
	public function add($user_id)
	{
		$date_added = date('Y-m-d', time());
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
		$this->db->query('DELETE FROM boards WHERE board_id=?',
		array($board_id));
		$this->db->query('DELETE FROM pins WHERE board_id=?',
		array($board_id));
	}
	
}

/* End of file */