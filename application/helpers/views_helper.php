<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once (APPPATH . '../assets/facebook-php-sdk/src/facebook.php');

function get_header()
{
	$CI = &get_instance();
	$CI->load->view('etc/header');
}

function get_footer()
{
	$CI = &get_instance();
	$CI->load->view('etc/footer');
}

function redirect_logged()
{
	$CI = &get_instance();
	if ($CI->session->userdata('is_user_logged')) redirect();
}

function r_n_logged()
{
	$CI = &get_instance();
	if (! $CI->session->userdata('is_user_logged')) redirect();
}

function get_image($image, $w, $h)
{
	if(!empty($image))
	{
		$arr 		= 	explode('.', $image);
		$filename 	=	$arr[0];
		$extension	=	$arr[1];

		return $filename.'_'.$w.'_'.$h.'.'.$extension;
	}
}

function get_image_path($image, $w, $h)
{
	if(!empty($image))
	{
		$arr 		= 	explode('.', $image);
		$filename 	=	$arr[0];
		$extension	=	$arr[1];

		return UPL_PATH.'/'.$filename.'_'.$w.'_'.$h.'.'.$extension;
	}
}

function get_image_url($image, $w='', $h='')
{
	if($w=='' && $h=='') return  base_url().UPL_PATH.'/'.$image;
	else return base_url().UPL_PATH.'/'.get_image($image, $w, $h);
}

function create_thumbs($file='', $group='')
{
	$ci=& get_instance();
	if( ! $file || ! $group) return;

	// If $group is array, don't search in the config file,
	// $group should be an array of sizes
	if( is_array($group))
	{
		$sizes = $group;
	}
	else
	{
		// Load the config file
		$ci->config->load('thumbnails', TRUE);
		$site_thumbs = $ci->config->item('site_thumbs', 'thumbnails');

		// Check if the specified group exists in the config file
		if( ! isset($site_thumbs[$group])) return;
		else $sizes = $site_thumbs[$group];
	}

	// Increase memory and load image library
	ini_set('memory_limit', '256M');
	$ci->load->library('image_moo');

	// Load the image
	$image = $ci->image_moo->load(UPL_PATH.'/'.$file);

	// Generate the specified thumbs
	foreach($sizes as $size)
	{
		if($size[0]=='resize')
		{
			$image->resize($size[1], $size[2])->save(get_image_path($file, $size[1], $size[2]));
		}
		elseif($size[0]=='resize_crop')
		{
			$image->resize_crop($size[1], $size[2])->save(get_image_path($file, $size[1], $size[2]));
		}
	}
}

function delete_thumbs($file, $group)
{
	$ci=& get_instance();
	if( ! $file || ! $group) return;

	// If $group is array, don't search in the config file,
	// $group should be an array of sizes
	if( is_array($group))
	{
		$sizes = $group;
	}
	else
	{
		// Load the config file
		$ci->config->load('thumbnails', TRUE);
		$site_thumbs = $ci->config->item('site_thumbs', 'thumbnails');

		// Check if the specified group exists in the config file
		if( ! isset($site_thumbs[$group])) return;
		else $sizes = $site_thumbs[$group];
	}

	// Remove the main file
	@unlink(UPL_PATH.$file);

	// And remove it's thumbs
	foreach($sizes as $size)
	{
		@unlink(get_image_path($file, $size[1], $size[2]));
	}
}

function remove_html($obj)
{
	if(is_string($obj))
	{
		return htmlspecialchars($obj, ENT_QUOTES);
	}
	elseif(is_object($obj) && get_class($obj)=="CI_DB_mysql_result")
	{
		$obj_n->result=$obj->result();
		$obj_n->num_rows=$obj->num_rows();

		foreach($obj_n->result as $row)
		{
			foreach($row as $key=>$value) $row->$key=htmlspecialchars($value, ENT_QUOTES);
		}

		return $obj_n;
	}
	elseif(is_object($obj))
	{
		foreach($obj as $key=>$value)
		{
			if(is_object($obj[$key])) remove_html($obj[$key]);
			else $obj->$key=htmlspecialchars($value, ENT_QUOTES);
		}
		return $obj;
	}
	elseif(is_array($obj))
	{
		foreach($obj as $key=>$value)
		{
			if(is_array($obj[$key])) remove_html($obj[$key]);
			else $obj[$key]=htmlspecialchars($value, ENT_QUOTES);
		}
		return $obj;
	}
}

// debug

function sql_e()
{
	$ci=& get_instance();
	echo $ci->db->last_query();
	echo $ci->db->_error_message();
}

/* End of file */