<div class="category_home">
	<div class="cat_text">
		<hr>
		<h3>Create Your Workspace Interior</h3>
	</div>	
	<div class="cat_container">
		<?php foreach ($categories as $category) { ?>
			<div class="cat_inner">
				<div class="cat_image">
					<a href="<?php echo $category['href']; ?>">
						<img src="<?php echo $category['thumb']; ?>" alt="<?php echo $category['name']; ?>" title="<?php echo $category['name']; ?>" />
					</a>
				</div>
				<a class="a" href="<?php echo $category['href']; ?>">
					<?php echo $category['name']; ?>
				</a>
			</div>	
		<?php } ?>
	</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
		initSlider3();
	});
		
	function initSlider3(){
		$('.cat_container').slick({
			dots: false,
			infinite: true,
			speed: 300,
			arrows: true,
			autoplay: false,
			centerMode: false,
			centerPadding: '60px',
			prevArrow:"<img alt='arrow' class='def-left' src='image/catalog/ds/homepage/left-min.png'><img alt='arrow' class='onhover' src='image/catalog/ds/homepage/left-min2.png'>",
			nextArrow:"<img alt='arrow' class='def-right' src='image/catalog/ds/homepage/right-min2.png'><img alt='arrow' class='onhover' src='image/catalog/ds/homepage/right-min.png'>",
			slidesToShow: 3,
			responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 2
				}
			},
			{
				breakpoint: 600,
				settings: {
					slidesToShow: 1
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1
				}
			}
			]
		});
	}
</script>
