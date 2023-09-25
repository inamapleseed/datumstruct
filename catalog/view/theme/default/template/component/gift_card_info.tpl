<div class="col-xs-12 col-sm-6 col-md-6 pd-t40 pd-b40 no-border">
	<div class="product-block marg-auto">
		<div class="product-image-block relative">
			<a 
				href="<?= $href; ?>" 
				title="<?= $name; ?>" 
				class="product-image image-container relative block" >
				<img 
					src="<?= $thumb; ?>" 
					alt="<?= $name; ?>" 
					title="<?= $name; ?>"
					class="img-responsive marg-auto" />	
			</a>
			<div class="absolute position-right-top"><div class="corner-badge"></div></div>
			<div class="absolute position-right-top z1"><div class="corner-badge-price"><?= $price_num ?></div></div>
		</div>
		<div class="bold pd-t30">
			<?= $name; ?>
		</div>
		<div class="pd-t15 pd-b15"><?= $description ?></div>
		<?php if ($price) { ?>
			<div class="bold">
				<?= $price; ?>
			</div>
		<?php } ?>
	</div>
</div>




