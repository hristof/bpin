<!doctype html>
<html xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
	<link  rel="stylesheet" type="text/css" href="<? echo base_url().'assets/css/main.css'; ?>">
</head>
<body>
	<section id="box">
		<header>
			<h1><a href="">bpin</a></h1>  <!--Този линк трябва да сочи към началната страница-->
			
			<nav>
				<a href="">Home</a>
				<a href="">About</a>
				<a href="">Contacts</a>
				<? if ($fb_user) { ?>
					<a href="">Hey <? echo $fb_name; ?></a>
				<? } else { ?>
					<fb:login-button></fb:login-button>
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
			</nav>
			<div class="clear"> </div>
		</header>