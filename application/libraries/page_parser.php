<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('PARSER_MAX_IMAGES_RETURNED', 4);

class Page_parser{
	private $mc;
	private $CI;

	private $images;
	private $images_final;
	private $url;

	private $doc;
	private $doc_content;

	public function __construct()
	{
		$this->images=array();
		$this->images_final=array();

		$this->CI = & get_instance();

		require('application/libraries/MyCurl.php');
	}

	public function get($url)
	{
		$this->url=$url;

		$mc = new MyCurl(); //MyCurl object
		$this->mc=$mc;

		$i=0;
		while($i<3)
		{
			$i++;

			$contents = $mc->get($url); //get all data from this URL
			$c_parts=explode("\r\n\r\n",$contents,2); // split header from html
			if(count($c_parts)==2)
			{
				$head=$c_parts[0];
				$contents=$c_parts[1];
			}
			else $contents='';

			if($contents) break;
		}

		if( ! $contents)
		{
			return $this->parser_error('Cannot donwload page.');
		}

		$contents=str_replace('&amp;','&',$contents);

		$charset = preg_match('/charset=([a-zA-Z0-9-\"\']+)/', $head, $matches);
		if(count($matches)>=2) $charset = $matches[1];
		else $charset='';


		if($charset == '')
		{
			$charset = preg_match('/charset=([a-zA-Z0-9-\"\']+)/', $contents, $matches);
			if(count($matches)>=2) $charset = $matches[1];
			else $charset='';
		}

		if($charset == '')
		{
			$charset = preg_match('/encoding=([a-zA-Z0-9-\"\']+)/', $contents, $matches);
			if(count($matches)>=2) $charset = $matches[1];
			else $charset='';
		}

		$charset = str_replace('"', 	'', $charset);
		$charset = str_replace('\'', 	'', $charset);
		$charset = str_replace('>', 	'', $charset);
		$curl_parts=explode("\r\n\r\n", $contents, 2);

		$content = $contents;

		if( !empty($charset) AND strtolower($charset) != 'utf-8')
		{
			$content = iconv($charset,"UTF-8",$content);
		}
		else if( !empty($charset) AND strtolower($charset) == 'utf-8' )
		{
			$content = $content;
		}
		else
		{
			$content = iconv("Windows-1251","UTF-8",$content);
		}


		$content=str_ireplace("<head>",'<head><meta http-equiv=Content-Type content="text/html; charset=utf-8">',$content);
		$content=mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
		$doc = new DOMDocument();
		@$doc->loadHTML($content);

		$this->doc 			= $doc;
		$this->doc_content  = $content;

		if($doc->getElementsByTagName('title')->length>0)
		{
			 $title=$doc->getElementsByTagName('title')->item(0)->nodeValue;
			 $title=html_entity_decode($title, ENT_QUOTES, "UTF-8");
		}
		else $title="";

		if($doc->getElementsByTagName('base')->length>0)
		{
			$base=$doc->getElementsByTagName('base')->item(0)->getAttribute('href');
		}
		else $base="";

		$imgs=$doc->getElementsByTagName("img");

		for($i=0;$i<$imgs->length;$i++)
		{
			$src=$imgs->item($i)->getAttribute('src');
			if(!empty($src)) array_push($this->images, trim(str_replace(' ', '%20', $src)));
		}
		$this->images=array_unique($this->images, SORT_STRING);

		//fix array keys
		$nodes_tmp=array();
		foreach($this->images as $val) array_push($nodes_tmp, $val);
		$this->images=$nodes_tmp;

		if($base&&$this->_is_valid_url($base))
		{
			$base_orig=$base;
			if($base[strlen($base)-1]!='/') // Make base to end with '/'
			{
				$base=$base.'/';
				$base_orig=$base;
			}

			// Test base as it is - case 1.1
			$this->_test_img_urls($base);

			// Test with the domain of base - case 1.2
			$ps = parse_url($base_orig);
			$base = $ps['scheme'].'://'.$ps['host'].'/';

			$this->_test_img_urls($base);

			// Test to the last '/' of base - case 1.3
			$base=substr($base_orig, 0, strlen($base_orig)-1);
			$base=substr($base, 0, strripos($base,'/')+1);

			$this->_test_img_urls($base);
		}
		else
		{
			// Test with the domain of the url - case 2.1
			$ps = parse_url($url);
			$base = $ps['scheme'].'://'.$ps['host'].'/';

			$this->_test_img_urls($base);

			// Test to the last '/' of url - case 2.2
			$base=substr($url, 0, strripos($url,'/')+1);

			$this->_test_img_urls($base);
		}

		if(count($this->images_final)>0)
		{
			arsort($this->images_final);

			// Exclude some images - like the amazon sprite
			foreach($this->images_final as $img=>$i)
			{
				$amazon_sprite = 'BeaconSprite-US-01._V397411194_.png';
				if(substr_compare($img, $amazon_sprite, -strlen($amazon_sprite), strlen($amazon_sprite)) === 0)
				{
					unset($this->images_final[$img]);
				}
			}

			return array(
				'status' =>'ok',
				'title'	 =>trim($title),
				'images' => array_slice(array_keys($this->images_final), 0, PARSER_MAX_IMAGES_RETURNED)
			);
		}
		else{
			return array(
				'status'	=> 'ok',
				'title'		=> trim($title),
				'images'	=> array()
			);
		}
	}

	function _test_img_urls($base)
	{
		$imgs=$this->images;
		$img_str=' ';
		$nodes = array();
		foreach($imgs as $src)
		{
			$src = trim(str_replace(' ', '%20', $src));

			if( ! $this->_is_valid_url($src))
			{
				$src = preg_replace('/^\/\//', 'http://', $src);
				$src = preg_replace('/^\.\.\//', './', $src);

				if( ! $this->_is_valid_url($src))
				{
					$src = $base.$src;

					//Remove double slashes
					$src=str_replace("https://","",$src);
					$src=str_replace("http://","",$src);
					$src=str_replace("///","/",$src);
					$src=str_replace("//","/",$src);
					$src="http://".$src;
				}
			}
			array_push($nodes, $src);
		}
		$node_count = count($nodes);

		$curl_arr = array();
		$master = curl_multi_init();
			$headers = array(
			"Range: bytes=0-62768"
			);
			$headers=array();
		$mc=$this->mc;
		$fCookieFile = $mc->fCookieFile;

		for($i = 0; $i < $node_count; $i++)
		{
			$url =$nodes[$i];

			$curl_arr[$i] = curl_init($url);
			curl_setopt($curl_arr[$i], CURLOPT_HTTPHEADER, $headers);
			curl_setopt($curl_arr[$i], CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl_arr[$i], CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl_arr[$i], CURLOPT_COOKIEJAR, $fCookieFile);
			curl_setopt($curl_arr[$i], CURLOPT_COOKIEFILE, $fCookieFile);
			curl_setopt($curl_arr[$i], CURLOPT_MAXREDIRS, 5);
			curl_setopt($curl_arr[$i], CURLOPT_USERAGENT,  'Mozilla/5.0 (Windows; U; Windows NT 6.1; uk; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5');
			curl_multi_add_handle($master, $curl_arr[$i]);
		}

		do {
			curl_multi_exec($master,$running);
		} while($running > 0);

		$no_img_count=0;

		$removed_count=0;
		for($i = 0; $i < $node_count; $i++)
		{
			$results = curl_multi_getcontent  ( $curl_arr[$i]  );

			$im = @imagecreatefromstring($results);
			  if($im===FALSE){
				  //echo 'no image: '.$nodes[$i].'<br/>';
				  $no_img_count++;
				  if($no_img_count>$node_count/2) return FALSE;
				 // echo 'z'.$results.'z';
				  continue;
			  }
			  else{
				  // Image got successfully - remove the image from the queue
				  array_splice($this->images,$i-$removed_count,1);
				  $removed_count++;
			  }

			  $width = imagesx($im);
			  $height = imagesy($im);


			if($width>=120 && $height>=90 || $height>=120 && $width>=90)
			{
				$this->images_final[$nodes[$i]]=$width*$height;
			}
		}

		if($no_img_count>$node_count/2){
			return FALSE;
		}
	}

	function _is_valid_url($str)
	{
		$pattern = "/^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";
		if (!preg_match($pattern, $str))
		{
			return FALSE;
		}

		return TRUE;
    }

	function get_image($site_url, $img_url)
	{
		$mc = new MyCurl(); //MyCurl object

		$i=0;
		while($i<3)
		{
			$i++;

			$contents = $mc->get($site_url); //get all data from this URL
			$c_parts=explode("\r\n\r\n",$contents,2); // split header from html
			if(count($c_parts)==2)
			{
				$head=$c_parts[0];
				$contents=$c_parts[1];
			}
			else $contents='';

			if($contents) break;
		}

		if($contents)
		{
			// Get the image content and mime type
			$image = $mc->get_content_and_mime_type($img_url);
			if( ! is_array($image)) return FALSE;

			// To make sure that this is an image,
			// try to load the content as an image resource
			$im = @imagecreatefromstring($image['content']);
			if($im===FALSE) return FALSE;

			switch($image['mime_type'])
			{
				case 'image/jpeg':
				case 'image/pjpeg': $ext=".jpg"; break;
				case 'image/png':
				case 'image/x-png': $ext=".png"; break;
				case 'image/gif'  : $ext=".gif"; break;
				default: $ext=".jpg";
			}

			return array(
				'img_source'=>$image['content'],
				'img_ext'	=> $ext
			);
		}

		return FALSE;
	}

    function parser_error($error)
	{
		return json_encode(array('status'=>'error', 'message'=>$error));
	}
}

/* End of file page_parser.php */
/* Location: ./application/libraries/page_parser.php */
