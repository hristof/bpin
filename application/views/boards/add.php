<? get_header();?>

<section class="body">
	<fieldset>
		<legend>Add board</legend>
		<form action="<?=base_url().'boards/add';?>" method="post">
			<?=validation_errors();?>
			<br>
			<div class="labels">
				<label for="title">Board title :</label>
				<br><br>
			</div>
			
			<div class="fields">
				<input type="text" name="title" id="title" value="<?=$this->input->post('title');?>">
				<br><br>
				<button type="submit">Add</button>
			</div>
		</form>
	</fieldset>
</section>

<? get_footer();?>