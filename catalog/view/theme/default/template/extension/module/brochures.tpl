<div class="brochures" data-aos="zoom-in-down">
	<?php foreach ($files as $file): ?>
		<div class="brochure_container">
			<div class="brochure_image">
				<a href="<?=$file['link'];?>" download>
					<img alt="image" src="image/<?=$file['image'];?>">
				</a>
			</div>	
			<div class="brochure_texts">
				<a href="<?=$file['link'];?>" download>
					<h4><?=$file['name'];?></h4>
				</a>	
				<p>
					<?=$file['description'];?>
				</p>
				<a class="btn custom_button" href="<?=$file['link'];?>" download>
					Download Brochure
				</a>
			</div>
		</div>
	<?php endforeach ?>
</div>
