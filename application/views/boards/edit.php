<? get_header();?>

<section class="body">
	<fieldset>
		<legend>Edit board</legend>
		<form action="<?=base_url().'boards/edit';?>" method="post">
			<? validation_errors();?>
			<br>
			<div class="labels">
				<label for="title">Board title :</label>
				<br><br>
			</div>
			
			<div class="fields">
				<input type="text" title="title" id="title" value="<?=$title;?>">
				<br><br>
				<button type="submit">Edit</button>
			</div>
		</form>
	</fieldset>
</section>

<? get_footer();?>