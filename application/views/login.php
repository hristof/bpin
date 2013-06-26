<? get_header();?>

<section class="body">
	<fieldset>
		<legend>Login</legend>
		<form action="<?=base_url().'home/logn';?>" method="post">
			<? if(validation_errors() || $flag) echo "<p>Invalid login. Please try again.</p>";?>
			<br>
			<div class="labels">
				<label for="uame">Username :</label>
				<br><br>
				<label for="password">Password :</label>
				<br><br>
			</div>
			
			<div class="fields">
				<input type="text" name="uname" id="uname" value="<?=$this->input->post('uname');?>">
				<br><br>
				<input type="password" name="password" id="password">
				<br><br>
				<button type="submit">Login</button>
			</div>
		</form>
	</fieldset>
</section>

<? get_footer();?>