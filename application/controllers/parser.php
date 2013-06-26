<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Parser extends Client_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function get_url()
	{
		$rules=array(
			array('field'=>'url', 'rules'=>'trim|required|valid_url')
		);
		$this->load->library('form_validation', $rules);

		if($this->form_validation->run())
		{
			// Parse the URL
			$this->load->library('page_parser');
			$data = $this->page_parser->get($_POST['url']);

			echo json_encode($data);
		}
	}
}

/* End of file parser.php */
/* Location: ./application/controllers/parser.php */
