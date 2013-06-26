<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Pins extends Client_Controller {

	function __construct()
	{
		parent::__construct();

		r_n_logged();
	}

    public function index()
    {
		/*$this->load->model("pins_model");

		$page=intval($this->uri->segment(4,0));
		$limit=10;
		$count=$this->pins_model->get_user_pins_count();

		$config['base_url'] 		= BASE."/pins/index";
		$config['total_rows'] 		= $count;
		$config['per_page'] 		= $limit;
		$config['uri_segment'] 		= 4;
		$config['cur_tag_open']		='<a href="#" class="active">';
		$config['cur_tag_close']	='</a>';
		$this->load->library("pagination",$config);

		$data["pag"]			    =	$count>$limit;
		$data['magazine_deleted']	=	$this->get_flag('magazine_deleted');
		$data['magazine_bulk']		=	$this->get_flag('magazine_bulk');
		$data["pins"]			=	$this->pins_model->get_list($page,$limit);
		$this->load->view('pins/list',$data);*/
    }

	public function add()
	{
		$this->load->model("pins_model");

		/*$rules=array(
			array('field'=>'title',  		'rules'=>'trim|required|max_length[300]'),
			array('field'=>'content',  		'rules'=>'trim|required'),
			array('field'=>'url',  		    'rules'=>'trim|required|valid_url'),
			array('field'=>'city',  		'rules'=>'required')
		);
		$this->load->library("form_validation", $rules);

		if($this->form_validation->run())
		{
			$config['upload_path'] 		= './uploads/';
			$config['allowed_types'] 	= 'jpg|png';
			$config['max_size']			= '6144';
			$config['encrypt_name'] 	= TRUE;

			$this->load->library('upload', $config);
			if ($this->upload->do_upload("cover"))
			{
				$file=$this->upload->data();
				$image=$file['file_name'];

				$this->_create_image_files($image);
			} else {
				$image = '';
			}

			// Add the magazine in the DB
			$magazine_id = $this->pins_model->add($image);

			// Add cities for magazine
			foreach($_POST['city'] as $city_id) {
				$this->pins_model->add_cities($magazine_id, $city_id);
			}

			$this->set_flag('magazine_added', TRUE);
			redirect("pins");
		}

		$data['cities']		=	$this->cities_model->get_all_list();*/
		$this->load->view('pins/add');
	}

	public function edit($magazine_id=0)
	{
		$this->load->model("pins_model");
		$this->load->model("cities_model");

		// Check if the magazine exists
		$magazine=$this->pins_model->get($magazine_id);
		if( ! $magazine) redirect("pins");

		$rules=array(
			array('field'=>'title',  		'rules'=>'trim|required|max_length[300]'),
			array('field'=>'content',  		'rules'=>'trim|required'),
			array('field'=>'url',  		    'rules'=>'trim|required|valid_url'),
			array('field'=>'city',  		'rules'=>'required')
		);
		$this->load->library("form_validation", $rules);

		if($this->form_validation->run())
		{
			$config['upload_path'] 		= './uploads/';
			$config['allowed_types'] 	= 'jpg|png';
			$config['max_size']			= '6144';
			$config['encrypt_name'] 	= TRUE;

			$this->load->library('upload', $config);
			if ($this->upload->do_upload("cover"))
			{
				// Delete old images
				$this->_delete_image_files($magazine->cover);

				// Create the magazine images
				$file=$this->upload->data();
				$image=$file['file_name'];

				$this->_create_image_files($image);

			}  else {
				$image = $magazine->cover;
			}

			$this->pins_model->edit($magazine_id, $image);

			// Add cities for magazine
			foreach($_POST['city'] as $city_id) {
				$this->pins_model->add_cities($magazine_id, $city_id);
			}

			$this->set_flag('saved', TRUE);
			redirect("pins/edit/{$magazine_id}");
		}

		$mag_cities = $this->pins_model->get_cities($magazine_id);
		$magazine_cities_arr = array();
		foreach($mag_cities as $mag_city) {
			$magazine_cities_arr[] = $mag_city->city_id;
		}

		$data['saved']			 =	$this->get_flag('saved');
		$data["magazine"]		 =	$this->pins_model->get($magazine_id);
		$data['magazine_cities'] =  $magazine_cities_arr;
		$data['cities']			 =	$this->cities_model->get_all_list();
		$this->load->view('pins/edit', $data);
	}

	public function delete($magazine_id=0)
	{
		$this->load->model("pins_model");

		// Check if the magazine exists
		$magazine=$this->pins_model->get($magazine_id);
		if( ! $magazine_id || ! $magazine) redirect("pins");

		// Delete the image files
		$this->_delete_image_files($magazine->cover);

		// Delete the magazine itself
		$this->pins_model->delete($magazine_id);

		$this->set_flag('magazine_deleted', TRUE);
		redirect("pins");
	}
}

/* End of file pins.php */
/* Location: ./application/controllers/pins.php */