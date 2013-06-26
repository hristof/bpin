<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<link  rel="stylesheet" type="text/css" href="<? echo base_url().'assets/css/main.css'; ?>">
</head>
<body>
	<section id="box">
		<header>
			<h1><a href="<? echo base_url(); ?>">Welcome to bpin</a></h1>
			<div class="login">
				<? if ($fb_user) { ?>
					<a href=""><? echo $fb_name; ?></a>
				<? } else { ?>
					<a href="<? echo base_url().'home/register'; ?>">Registration</a>
					<a href="">Login</a>
					<fb:login-button perms="email"></fb:login-button>
				<? } ?>
				<div id="fb-root"></div>
				<script>
					window.fbAsyncInit = function() {
						FB.init({
							appId: '<?php echo $appid; ?>',
							cookie: true,
							xfbml: true,
							oauth: true
						});
						FB.Event.subscribe('auth.login', function(response) {
							window.location.reload();
						});
						FB.Event.subscribe('auth.logout', function(response) {
							window.location.reload();
						});
					};
					(function() {
						var e = document.createElement('script'); e.async = true;
						e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
						document.getElementById('fb-root').appendChild(e);
					}());
				</script>
			</div>
			<nav>
				<a href="">Home</a>
			</nav>
			<div class="clear"> </div>
		</header>