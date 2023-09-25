<div class="product-block <?= $out_of_stock; ?>">
	<div class="product-image-block relative image-zoom-hover">
		<?php if($sticker && $sticker['name']){ ?>
			<li>
				<a 
				href="<?= $href; ?>" 
				title="<?= $name; ?>" 
				class="sticker absolute" 
				style="color: <?= $sticker['color']; ?>; background-color: <?= $sticker['background-color']; ?>">
					<?= $sticker['name']; ?>
				</a>
			</li>
		<?php } ?>
			<a 
				href="<?= $href; ?>" 
				title="<?= $name; ?>" 
				class="product-image image-container relative" >

				<img 
					src="<?= $thumb; ?>" 
					alt="<?= $name; ?>" 
					title="<?= $name; ?>"
					class="img-responsive" />
			</a>
	</div>

	<div class="product-name">
		<a href="<?= $href; ?>"><?= $name; ?></a>
	</div>

	<div class="product-details">
		<?php if ($price && !$enquiry) { ?>
			<div class="price">
				<?php if (!$special) { ?>
					<?= $price; ?>
				<?php } else { ?>
					<span class="price-new"><?= $special; ?></span>
					<span class="price-old"><?= $price; ?></span>
				<?php } ?>
				<?php if ($tax) { ?>
					<span class="price-tax"><?= $text_tax; ?> <?= $tax; ?></span>
				<?php } ?>
			</div>
		<?php } ?>

	</div>
	<?php if($options_product){ ?>
		<div class="options_product">
			<span><?php echo $options_product; ?></span>
		</div>
	<?php } ?>	
</div>