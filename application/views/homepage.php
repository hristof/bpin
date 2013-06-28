<? get_header();?>

<section class="body">
	<div class="name">Recent Pins</div>
	<div class="list">
		<ul>
			<? foreach($pins as $p):?>
			<li>
				<img src="<?=get_image_url($p->thumb, 200, 250);?>" alt="<?=$p->title;?>">
				<p class="description"><a href="<?=$p->link;?>" target="_blank"><?=substr($p->title, 0, 70);?></a></p>
			</li>
			<? endforeach;?>
		</ul>
		<div class="clear"> </div>
	</div>
</section>
<div class="links">
	<?=$this->pagination->create_links();?>
</div>
<? get_footer();?>