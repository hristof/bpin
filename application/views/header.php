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
				<? if ($this->is_user_logged) { ?>
					<a href=""><?=$this->name;?>, <a href="<?=base_url();?>/home/signout">Sign out</a></a>
				<? } else { ?>
					<a href="<? echo base_url().'home/register'; ?>">Registration</a>
					<a href="">Login</a>
					<fb:login-button perms="email"></fb:login-button>
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
						FB.Event.subscribe('auth.login', function(response) {
							window.location='<?=base_url();?>/home/register_with_fb';
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