<? get_header();?>

<? if($pin_added):?>
The pin was added!
<? endif;?>

<? if($pin_deleted):?>
The pin was removed!
<? endif;?>

<section class="body">
	<div class="listing"><?=$board['title'];?> &gt; Pin Listing</div>
	<br>
	<a href="<?=base_url();?>pins/add">Add pin</a>
	<div class="list">
		<ul>
			<? foreach($pins as $p):?>
			<li>
				<img src="<?=get_image_url($p->thumb, 200, 250);?>" alt="<?=$p->title;?>">
				<p class="description"><?=substr($p->title, 0, 70);?></p>
				<a href="<?=base_url();?>/pins/edit/<?=$p->pin_id;?>">Edit</a> |
				<a href="<?=base_url();?>/pins/delete/<?=$board['board_id'];?>/<?=$p->pin_id;?>">Delete</a>
			</li>
			<? endforeach;?>
		</ul>
		<div class="clear"> </div>
	</div>
</section>

<?=$this->pagination->create_links();?>

<? get_footer();?>