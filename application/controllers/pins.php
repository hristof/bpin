<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pins extends Client_Controller {

	function __construct()
	{
		parent::__construct();

		r_n_logged();
	}

    public function index($board_id=0)
    {
		$this->load->model("pins_model");
		$this->load->model('boards_model');

		// Check if the board exists
		$board=$this->boards_model->get($board_id);
		if( ! $board) redirect("boards");

		// Pagination
		$from=intval($this->uri->segment(4,0));
		$limit=1;
		$count=$this->pins_model->get_user_pins_count($board_id);

		$config['base_url'] 		= base_url()."/pins/index/{$board_id}";
		$config['total_rows'] 		= $count;
		$config['per_page'] 		= $limit;
		$config['uri_segment'] 		= 4;
		$this->load->library('pagination', $config);
		// End pagination

		$data['pin_added']			=	$this->get_flag('pin_added');
		$data['pin_deleted']		=	$this->get_flag('pin_deleted');
		$data['board']				= 	$board;
		$data['pins']				=	$this->pins_model->get_user_pins_list($board_id, $from, $limit);
		$this->load->view('pins/list', $data);
    }

	public function add()
	{
		$this->load->model("pins_model");
		$this->load->model('boards_model');

		$rules=array(
			array('field'=>'title',  		'rules'=>'trim|required|max_length[200]'),
			array('field'=>'board_id',  	'rules'=>'trim|required|is_natural_no_zero'),
			array('field'=>'image_url',  	'rules'=>'trim|required|valid_url'),
			array('field'=>'site_url',  	'rules'=>'trim|required|valid_url')
		);
		$this->load->library("form_validation", $rules);

		if($this->form_validation->run())
		{
			// Try to get the image
			$this->load->library('page_parser');
			$image=$this->page_parser->get_image($_POST['site_url'], $_POST['image_url']);

			if(is_array($image))
			{
				// Get a unique file name
				do{
					$img_name=md5(uniqid().rand()).$image['img_ext'];
					$path=UPL_PATH.'/'.$img_name;
				}
				while(file_exists($path));

				file_put_contents($path, $image['img_source']);
				create_thumbs($img_name, 'pin_images');

				// Add pin
				$this->pins_model->add($img_name);
				$this->boards_model->set_board_thumb($img_name, $_POST['board_id']);

				$this->set_flag('pin_added', TRUE);
			}

			redirect("pins/index/".$_POST['board_id']);
		}

		$data['boards']	= $this->boards_model->get_boards($this->user_id);
		$this->load->view('pins/add', $data);
	}

	public function edit($pin_id=0)
	{
		$this->load->model("pins_model");
		$this->load->model('boards_model');

		// Check if the pin exists
		$pin=$this->pins_model->get($pin_id);
		if( ! $pin) redirect("pins");

		$rules=array(
			array('field'=>'title',  		'rules'=>'trim|required|max_length[200]'),
			array('field'=>'board_id',  	'rules'=>'trim|required|is_natural_no_zero')
		);
		$this->load->library("form_validation", $rules);

		if($this->form_validation->run())
		{
			$this->pins_model->edit($pin_id);

			$this->set_flag('saved', TRUE);
			redirect("pins/edit/{$pin_id}");
		}

		$data['saved']	= $this->get_flag('saved');
		$data['pin']	= $this->pins_model->get($pin_id);
		$data['boards']	= $this->boards_model->get_boards($this->user_id);
		$this->load->view('pins/edit', $data);
	}

	public function delete($board_id=0, $pin_id=0)
	{
		$this->load->model("pins_model");
		$this->load->model('boards_model');

		// Check if the pin exists
		$pin=$this->pins_model->get($pin_id);
		if( ! $pin) redirect("pins/index/$board_id");

		// If the thumb of the pin is a thumb of the board
		$board=$this->boards_model->get($pin->board_id);
		if($board['thumb']==$pin->thumb)
		{
			$last_pin = $this->pins_model->get_last_from_board($pin->board_id);
			if($last_pin) $new_thumb = $last_pin->thumb;
			else $new_thumb='';

			$this->boards_model->set_board_thumb($new_thumb, $pin->board_id);
		}

		// Delete the pin itself
		$this->pins_model->delete($pin);

		$this->set_flag('pin_deleted', TRUE);
		redirect("pins/index/$board_id");
	}
}

/* End of file pins.php */
/* Location: ./application/controllers/pins.php */