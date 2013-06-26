<? get_header();?>

<section class="body">
	<fieldset>
		<legend>Add Pin</legend>

		<form action="" method="post">
			<img src="<?=get_image_url($pin->thumb, 150, 150);?>" alt=" ">

			<b>Title:<b><br>
			<input type="text" name="title" value="<?=$pin->title;?>">

			<br>

			<b>Board:</b><br>
			<select name="board_id">
				<? foreach($boards->result() as $b):?>
				<option value="<?=$b->board_id;?>"
				<?=$pin->board_id==$b->board_id ? 'selected="selected"':'';?>>
					<?=$b->title;?>
				</option>
				<? endforeach;?>
			</select>

			<br>

			<input type="submit" value="Edit">
		</form>
	</fieldset>
</section>

<? get_footer();?>