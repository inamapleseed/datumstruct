<div class="manufacturer manufacturer-<?= $uqid; ?>">
	<?php if($manufacturers){ ?>
	<div class="slider-container">
		<div id="manuSlide<?= $uqid; ?>">
			<?php foreach($manufacturers as $manufacturer){ ?>
			<a href="<?= $manufacturer['href']; ?>" title="<?= $manufacturer['name']; ?>">
				<img class="img-responsive" src="<?= $manufacturer['image']; ?>" />
			</a>
			<?php } ?>
		</div>
	</div>
	<script type="text/javascript">
		$("#manuSlide<?= $uqid; ?>").slick({
			slidesToShow: 7,
			rows: 2,
			nextArrow: '<button type="button" class="slick-next"></button>',
			prevArrow: '<button type="button" class="slick-prev"></button>',
			responsive: [{
					breakpoint: 1700,
					settings: {
						slidesToShow: 6
					}
				},{
					breakpoint: 1520,
					settings: {
						slidesToShow: 5
					}
				},{
					breakpoint: 1200,
					settings: {
						slidesToShow: 3
					}
				},{
					breakpoint: 900,
					settings: {
						slidesToShow: 2
					}
				},{
					breakpoint: 480,
					settings: {
						slidesToShow: 2
					}
				}
			]
		});
	</script>
	<?php } ?>
</div>