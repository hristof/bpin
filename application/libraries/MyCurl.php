<?php

	/*
	  Class: MyCurl
	  Description: provides a simple tool to GET/POST data with help of CURL library
	*/

	class MyCurl
	{
		public $getHeaders = true;//headers will be added to output
		public $getContent = true; //contens will be added to output
		public $followRedirects = true; //should the class go to another URL, if the current is "HTTP/1.1 302 Moved Temporarily"

		public $fCookieFile;
		private $fSocket;
		private $fe;

		function MyCurl()
		{
			$this->fCookieFile = tempnam("/tmp", "g_");
		}

		function init()
		{
			return $this->fSocket = curl_init();
		}

		function setopt($opt, $value)
		{
			return curl_setopt($this->fSocket, $opt, $value);
		}

		function load_defaults()
		{
			$this->setopt(CURLOPT_RETURNTRANSFER, 1);
			$this->setopt(CURLOPT_FOLLOWLOCATION, $this->followRedirects);
			$this->setopt(CURLOPT_VERBOSE, false);
			$this->setopt(CURLOPT_SSL_VERIFYPEER, false);
			$this->setopt(CURLOPT_SSL_VERIFYHOST, false);
			$this->setopt(CURLOPT_HEADER, $this->getHeaders);
			$this->setopt(CURLOPT_NOBODY, !$this->getContent);
			$this->setopt(CURLOPT_COOKIEJAR, $this->fCookieFile);
			$this->setopt(CURLOPT_COOKIEFILE, $this->fCookieFile);
			$this->setopt(CURLOPT_CONNECTTIMEOUT, 15);
			$this->setopt(CURLOPT_TIMEOUT, 15);
			$this->setopt(CURLOPT_MAXREDIRS, 5);
			$this->setopt(CURLOPT_USERAGENT,  'Mozilla/5.0 (Windows; U; Windows NT 6.1; uk; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5');
			$this->setopt(CURLOPT_POST, 1);
			$this->setopt(CURLOPT_CUSTOMREQUEST,'POST');
			//$this->setopt(CURLOPT_VERBOSE, true);
			//$this->setopt(CURLOPT_STDERR, stdout );
		}

		function destroy()
		{
			return curl_close($this->fSocket);
		}

		function head($url)
		{
			$this->init();

			if($this->fSocket)
			{
				$this->getHeaders = true;
				$this->getContent = true;
				$this->load_defaults();

				$ps = parse_url($url);
				$base = $ps['scheme'].'://'.$ps['host'].'/';
				$this->setopt(CURLOPT_REFERER, $base);
				$this->setopt(CURLOPT_POST, 0);
				$this->setopt(CURLOPT_CUSTOMREQUEST,'GET');
				$this->setopt(CURLOPT_URL, $url);
				$result = curl_exec($this->fSocket);
				$this->destroy();

				return $result;
			}

			return 0;
		}

		function get_content_and_mime_type($url)
		{
			$this->init();

			if($this->fSocket)
			{
				$this->load_defaults();
				$this->getHeaders = true;
				$this->getContent = true;

				$ps = parse_url($url);
				$base = $ps['scheme'].'://'.$ps['host'].'/';
				$this->setopt(CURLOPT_REFERER, $base);
				$this->setopt(CURLOPT_POST, 0);
				$this->setopt(CURLOPT_CUSTOMREQUEST,'GET');
				$this->setopt(CURLOPT_URL, $url);
				$result = curl_exec($this->fSocket);

				$mime = curl_getinfo($this->fSocket, CURLINFO_CONTENT_TYPE);
				$header_size = curl_getinfo($this->fSocket, CURLINFO_HEADER_SIZE);

				$this->destroy();

				$body = substr($result, $header_size);

				return array(
					'mime_type'	=> $mime,
					'content'	=> $body
				);
			}

			return 0;
		}

		function get($url)
		{
			$this->init();

			if($this->fSocket)
			{
				$this->load_defaults();

				$ps = parse_url($url);
				$base = $ps['scheme'].'://'.$ps['host'].'/';
				$this->setopt(CURLOPT_REFERER, $base);
				$this->setopt(CURLOPT_POST, 0);
				$this->setopt(CURLOPT_CUSTOMREQUEST,'GET');
				$this->setopt(CURLOPT_URL, $url);
				$result = curl_exec($this->fSocket);
				$this->destroy();

				return $result;
			}

			return 0;
		}

		function post($url, $post_data, $arr_headers=array(), &$http_code)
		{
			$this->init();
			if($this->fSocket)
			{
				$post_data = $this->compile_post_data($post_data);
				$this->load_defaults();

				if(!empty($post_data))
					$this->setopt(CURLOPT_POSTFIELDS, $post_data);

				if(!empty($arr_headers))
					$this->setopt(CURLOPT_HTTPHEADER, $arr_headers);

				$this->setopt(CURLOPT_URL, $url);

				  $result = curl_exec($this->fSocket);
				  $http_code = curl_getinfo($this->fSocket, CURLINFO_HTTP_CODE);
				  $this->destroy();

				return $result;
			}

			return 0;
		}

		function compile_post_data($post_data)
		{
			$o="";
			if(!empty($post_data))
			  foreach ($post_data as $k=>$v)
				$o.= $k."=".urlencode($v)."&";
			return substr($o,0,-1);
		}

		function get_parsed($result, $bef, $aft="")
		{
			$line=1;
			$len = strlen($bef);
			$pos_bef = strpos($result, $bef);


			if($pos_bef===false)	return "";

			$pos_bef += $len;

			if(empty($aft))
			{ //try to search up to the end of line
			  $pos_aft = strpos($result, "\n", $pos_bef);
			  if($pos_aft===false)
				$pos_aft = strpos($result, "\r\n", $pos_bef);
			}
			else
			  $pos_aft = strpos($result, $aft, $pos_bef);

			if($pos_aft!==false)
			  $rez = substr($result, $pos_bef, $pos_aft-$pos_bef);
			else
			  $rez = substr($result, $pos_bef);

			return $rez;
		}

	}

?>