	<section class="body">
		<fieldset>
			<legend>Registration</legend>
			<form action="<? echo base_url().'home/register'; ?>" method="post">
				<? echo validation_errors()."<br>";?>
				<div class="labels">
					<label for="fullname">Full Name :</label> <br> <br>
					<label for="uame">Username :</label> <br>	<br>
					<label for="email">Email :</label> <br>	<br>
					<label for="password">Password :</label> <br> <br>
					<label for="passwordconf">Password Again :</label> <br> <br>
					<? echo $captcha; ?>
				</div>
				
				<div class="fields">
					<input type="text" name="fullname" id="fullname" value="<? echo $this->input->post('fullname'); ?>"><br> <br>
					<input type="text" name="uname" id="uname" value="<? echo $this->input->post('uname'); ?>"><br> <br>
					<input type="email" name="email" id="email" value="<? echo $this->input->post('email'); ?>"><br> <br>
					<input type="password" name="password" id="password"><br> <br>
					<input type="password" name="passwordconf" id="passwordconf"><br> <br>
					<input type="text" name="captcha" id="captcha"><br> <br>
					<button type="submit">Register</button>
				</div>
			</form>
		</fieldset>
	</section>
