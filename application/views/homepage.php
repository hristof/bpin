<? get_header();?>

<section class="body">
	<div class="name">Recent Pins</div>
	<div class="list">
		<ul>
			<? foreach($pins as $p):?>
			<li>
				<img src="<?=get_image_url($p->thumb, 200, 250);?>" alt="<?=$p->title;?>">
				<p class="description"><?=substr($p->title, 0, 70);?></p>
			</li>
			<? endforeach;?>
		</ul>
		<div class="clear"> </div>
		<div class="button">
		<button class="loadMore" type="button" onclick="">Load More</button>
		</div>
	</div>
</section>

<?=$this->pagination->create_links();?>

<? get_footer();?>