	<section class="body">
		<fieldset>
			<legend>Registration</legend>
			<form action="<? echo base_url().'home/register'; ?>" method="post">
				<div class="labels">
					<label for="fullname">Full Name :</label> <br> <br>
					<label for="username">Username :</label> <br>	<br>
					<label for="email">Email :</label> <br>	<br>
					<label for="password">Password :</label> <br> <br>
					<label for="passwordconf">Password Again :</label> <br> <br>
				</div>
				
				<div class="fields">
					<input type="text" name="fullname" id="fullname"><br> <br>
					<input type="text" name="username" id="username"><br> <br>
					<input type="email" name="email" id="email"><br> <br>
					<input type="password" name="password" id="password"><br> <br>
					<input type="password" name="passwordconf" id="passwordconf"><br> <br>
					<button type="submit">Register</button>
				</div>
			</form>
		</fieldset>
	</section>
