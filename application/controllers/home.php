<?php

class Home extends Client_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->helper('captcha');
		$this->load->model('home_model');
	}

	public function index()
	{
		$this->load->model("pins_model");

		// Pagination
		$from=intval($this->uri->segment(3,0));
		$limit=1;
		$count=$this->pins_model->get_recent_pins_count();

		$config['base_url'] 		= base_url()."/home/index";
		$config['total_rows'] 		= $count;
		$config['per_page'] 		= $limit;
		$config['uri_segment'] 		= 3;
		$this->load->library('pagination', $config);
		// End pagination

		$data['pins'] =	$this->pins_model->get_recent_pins_list($from, $limit);
		$this->load->view('homepage', $data);
	}
	
	public function login()
	{
		redirect_logged();
		$this->form_validation->set_rules('uname', 'Username', 'trim|required');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|md5');
		
		if ($this->form_validation->run()) {
			$result = $this->home_model->login_check();
			if ($result!=null) {
				$newdata = array(
					'user_id' => $result['user_id'],
					'name' => $result['fullname'],
					'is_user_logged' => true);
				$this->session->sess_destroy();
				$this->session->sess_create();
				$this->session->set_userdata($newdata);
				redirect('boards');
			} else {
				$this->load->view('login', array('flag' => 1));
			}
		}
		
		$this->load->view('login', array('flag' => 0));
	}

	public function register()
	{
		redirect_logged();
		$this->set_rules_and_messages();
		if (! $this->form_validation->run()) {
			$word = $this->generateWord();
			$newdata = array('captcha' => $word);
			$this->session->sess_destroy();
			$this->session->sess_create();
			$this->session->set_userdata($newdata);
			$vals = array(
					'word'		 => $word,
					'img_path'	 => './captcha/',
					'img_url'	 => base_url().'captcha/',
					'font_path'	 => './assets/font.ttf',
					'img_width'	 => 140,
					'img_height' => 30,
					'expiration' => 300);
			$cap = create_captcha($vals);

			$this->load->view('registration', array('captcha' => $cap['image']));

		} else {
			$this->session->sess_destroy();

			$fullname = $this->input->post('fullname');
			$uname = $this->input->post('uname');
			$email = $this->input->post('email');
			$password = $this->input->post('password');

			$this->home_model->register($fullname, $uname, $email, $password);

			redirect();
		}
	}

	function register_with_fb()
	{
		redirect_logged();

		$facebook = new Facebook(array(
		  'appId'  => FB_APP_ID,
		  'secret' => FB_SECRET
		));
		$fb_user = $facebook->getUser();

		if($fb_user)
		{
			try {
				$user_details = $facebook->api('/me', 'GET', array('fields' => 'name,email'));

				// Try to get a fb user with this facebook_id
				$user = $this->home_model->get_fb_user($fb_user);
				if( ! $user)
				{
					// Add the fb user
					$user_id = $this->home_model->add_fb_user($fb_user, $user_details['name'], $user_details['email']);
					$user = $this->home_model->get_user($user_id);
				}

				$this->session->set_userdata('is_user_logged', TRUE);
				$this->session->set_userdata('user_id', $user->user_id);
				$this->session->set_userdata('name', 	$user->fullname);

			} catch (FacebookApiException $e){}

			redirect();
		}
	}

	public function signout()
	{
		$this->session->sess_destroy();
		redirect();
	}

	private function generateWord()
	{
		$lenght=6;
		$newlength=0;
		$newcode="";
		while($newlength<$lenght) {
			$x=1;
			$y=2;
			$p = rand($x,$y);
			if($p==1){$a=48;$b=57;}  // Numbers
			if($p==2){$a=65;$b=90;}  // UpperCase
			$part=chr(rand($a,$b));
			$newlength = $newlength + 1;
			$newcode = $newcode.$part;
		}

		return $newcode;
	}

	private function set_rules_and_messages()
	{
		$this->form_validation->set_rules('fullname', 'Full Name', 'trim|required|min_length[3]|max_legth[200]');
		$this->form_validation->set_rules('uname', 'Username', 'trim|required|min_length[3]|max_legth[200]|alpha_numeric|callback_usernameCheck');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[50]|callback_emailCheck');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[200]|alpha_numeric|matches[passwordconf]|md5');
		$this->form_validation->set_rules('passwordconf', 'Password Again', 'trim|required');
		$this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|callback_captchaCheck');

		$this->form_validation->set_message('matches', 'The two passwords didn\'t match');
		$this->form_validation->set_message('usernameCheck', 'The Username must be unique');
		$this->form_validation->set_message('emailCheck', 'There is already registered user with this email address');
		$this->form_validation->set_message('captchaCheck', 'Wrong captcha');
	}

	public function captchaCheck($str)
	{
		if ($str==$this->session->userdata('captcha')) return true;
		else return false;
	}

	public function usernameCheck($str)
	{
		return $this->home_model->username_check($str);
	}

	public function emailCheck($str)
	{
		return $this->home_model->email_check($str);
	}
}

/* End of file */