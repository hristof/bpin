<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Boards extends Client_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('boards_model');
		r_n_logged();
	}

    public function index()
    {
		$data = array('boards' => $this->boards_model->get_boards($this->user_id));
		$this->load->view('boards/list',$data);
    }

	public function add()
	{
		$rules=array(
			array('field'=>'title',  		'rules'=>'trim|required|max_length[200]')
		);
		$this->load->library('form_validation', $rules);
		
		if($this->form_validation->run()) {
			$this->boards_model->add($this->user_id);
			redirect('boards');
		}
		
		$this->load->view('boards/add');
	}

	public function edit($board_id=0)
	{
		$board=$this->boards_model->get($board_id);
		if(! $board) redirect('boards');

		$rules=array(
			array('field'=>'title',  		'rules'=>'trim|required|max_length[300]')
		);
		$this->load->library('form_validation', $rules);

		if($this->form_validation->run())
		{
			$this->boards_model->edit($board_id);			
			redirect('boards');
		}
		
		$data['title'] = $board['title'];
		$this->load->view('boards/edit', $data);
	}

	public function delete($magazine_id=0)
	{
		$this->boards_model->delete($magazine_id);
		redirect("boards");
	}
}

/* End of file boards.php */
/* Location: ./application/controllers/boards.php */