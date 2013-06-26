<? get_header();?>

<section class="body">
	<div class="listing">Boards Listing</div>
	<div class="list">
		<ul>
			<? foreach($boards->result() as $board) {?>
				<li>
					<img src="<?=$board->thumb;?>" />
					<p class="description"><?=$board->title;?></p>
					<p class="date"><?=$board->date_added;?></p>
					<a href="<?=base_url().'pins/index/'.$board->board_id;?>">View board</a>
					<a href="<?=base_url().'boards/edit/'.$board->board_id;?>">Edit board</a>
					<a href="#" onclick="if (confirm('Are you sure?')) parent.location='<?=base_url().'boards/delete/'.$board->board_id;?>'">Delete board</a>
				</li>
			<?}?>
		</ul>
		<button type="button" onclick="parent.location='<?=base_url().'boards/add';?>'">Add</button>
	<div class="clear"> </div>
</section>

<? get_footer();?>