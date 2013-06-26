<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (APPPATH . '../assets/facebook-php-sdk/src/facebook.php');

function get_header()
{
	$CI = &get_instance();
	$CI->load->view('header');
}

function get_footer()
{
	$CI = &get_instance();
	$CI->load->view('footer');
}

function redirect_logged()
{
	$CI = &get_instance();
	if ($CI->session->userdata('is_user_logged')) redirect(base_url());
}

function r_n_logged()
{
	$CI = &get_instance();
	if (! $CI->session->userdata('is_user_logged')) redirect(base_url());
}

/* End of file */