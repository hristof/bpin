<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<link  rel="stylesheet" type="text/css" href="<? echo base_url().'assets/css/main.css'; ?>">
	<link  rel="stylesheet" type="text/css" href="<? echo base_url().'assets/css/main.css';?>">
	<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
	<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
	<script src="http://code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
</head>
<body>
	<section id="box">
		<header>
			<h1><a href="<?=base_url();?>">Welcome to bpin</a></h1>
			<div class="login">
				<? if ($this->is_user_logged) { ?>
					<a href="<?=base_url().'boards';?>"><?=$this->name;?></a>,
					<a href="<?=base_url();?>/home/signout">Sign out</a>
				<? } else { ?>
					<a href="<? echo base_url().'home/register';?>">Registration</a>
					<a href="">Login</a>
					<a href="javascript:" onclick="FBLogin()">Login with Facebook</a>
				<? } ?>
				<div id="fb-root"></div>
				<script>
					window.fbAsyncInit = function() {
						FB.init({
							appId: '<?=FB_APP_ID;?>',
							cookie: true,
							xfbml: true,
							oauth: true
						});
					};
					(function() {
						var e = document.createElement('script'); e.async = true;
						e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
						document.getElementById('fb-root').appendChild(e);
					}());

					function FBLogin()
					{
						FB.login(function(response) {
							if (response.authResponse) {
								window.location="<?=base_url();?>/home/register_with_fb";
							}
						},{scope: 'email'});
					}
				</script>
			</div>
			<nav>
				<a href="">Home</a>
			</nav>
			<div class="clear"> </div>
		</header>