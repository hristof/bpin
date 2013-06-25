<!doctype html>
<html>
<head>
	<link  rel="stylesheet" type="text/css" href="<? echo base_url().'assets/css/main.css';?>">
</head>
<body>
	<section id="box">
		<header>
			<h1><a href="">bpin</a></h1>  <!--Този линк трябва да сочи към началната страница-->
			
			<nav>
				<a href="">Home</a>
				<a href="">About</a>
				<a href="">Contacts</a>
				<? if ($fbuser) { ?>
					<a href="<?php echo $fblogoutUrl;?>">ФБ Изход</a>
				<? } else { ?>
					<a href="<?php echo $fbloginUrl;?>">ФБ Вход</a>
				<? } ?>
			</nav>
			<div class="clear"> </div>
		</header>