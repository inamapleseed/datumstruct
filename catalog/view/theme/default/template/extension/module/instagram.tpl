<div class="media_container">
    <?php if(!empty($instagrams)) {?>
    	<div class="media_heading" >
    		<hr>
    		<h3>Media</h3>
    	</div>
    	<div class="instagram media_container_inner">
    		<?php foreach ($instagrams as $instagram){ ?>
    		<div class="item <?php echo $hover_effect;?>">
    		    <?php if($instagram['media_type'] == 'IMAGE' || $instagram['media_type'] == 'CAROUSEL_ALBUM') { ?>
    			<a href="<?php echo $instagram['href'];?>" target="_blank" data-like="<?php echo $instagram['likes'];?>" title="<?php echo $instagram['text'];?>">
    				<i class="fa fa-heart" aria-hidden="true"></i>
    				<div style="background-image:url('<?php echo $instagram['img'];?>'); background-size:cover; background-position:center; background-repeat:no-repeat; padding-bottom:100%;"></div>
    			</a>
    			<?php } ?>
    			<?php if($instagram['media_type'] == 'VIDEO') { ?>
    			<a href="<?php echo $instagram['href'];?>" target="_blank" data-like="<?php echo $instagram['likes'];?>" title="<?php echo $instagram['text'];?>">
					<i class="fa fa-heart" aria-hidden="true"></i>
					<div>
					    <iframe src="<?php echo $instagram['img'];?>" class="ig-iframe" frameborder="0" allowfullscreen style="width: 100%"></iframe>
                    </div>
				</a>
				<?php } ?>
    		</div>
    		<?php } ?>
    
    	</div>
	<?php } ?>
</div>
<style>
	.instagram .slick-prev:before,
	.instagram .slick-next:before {
		color: <?php echo $color;
		?>;
	}

	.instagram h4 {
		text-align: <?php echo $text_align;
		?>
	}

	.instagram .item .fa:before {
		color: <?php echo $heart_color;
		?>
	}

	.instagram .item a:before {
		color: <?php echo $heart_text_color;
		?>
	}

	<?php if($center_mode): ?>.slick-slide {
		opacity: .2;
		transition: opacity .3s linear 0s;
	}

	.slick-slide.slick-active.slick-center {
		opacity: 1;
	}

	<?php endif;
	?>
</style>
<script type="text/javascript">
    $(document).ready(function() {
		initSlider4();
	});
	function initSlider4(){
	    $('.media_container .instagram').slick({
    		slidesToShow: <?php echo $slidesToShow;?>,
    		slidesToScroll: <?php echo $slidesToScroll ?>,
    		autoplay: <?php echo $autoplay; ?>,
    		autoplaySpeed: <?php echo $autoplaySpeed; ?>,
    		dots: <?php echo $dots; ?>,
    		arrows: <?php echo $arrows; ?>,
    		prevArrow:"<img alt='arrow' class='def-left' src='image/catalog/ds/homepage/left-min.png'><img alt='arrow' class='onhover' src='image/catalog/ds/homepage/left-min2.png'>",
    		nextArrow:"<img alt='arrow' class='def-right' src='image/catalog/ds/homepage/right-min2.png'><img alt='arrow' class='onhover' src='image/catalog/ds/homepage/right-min.png'>",
    		<?php echo ($center_mode) ? "centerMode : $center_mode," : ''; ?>
    		responsive: [{
    				breakpoint: 1024,
    				settings: {
    					slidesToShow: <?php echo $slidesToShow; ?>,
    					slidesToScroll: <?php echo $slidesToScroll ?>,
    					infinite: true,
    					arrows: false
    				}
    			},
    			{
    				breakpoint: 600,
    				settings: {
    					slidesToShow: <?php echo $slidesToShowTablet; ?>,
    					slidesToScroll: <?php echo $slidesToScrollTablet; ?>,
    					arrows: false,
    					dots: true,
    				}
    			},
    			{
    				breakpoint: 480,
    				settings: {
    					slidesToShow: <?php echo $slidesToShowCelphone; ?>,
    					slidesToScroll: <?php echo $slidesToScrollCelphone; ?>,
    					arrows: false,
    					dots: true,
    				}
    			}
    		]
    	});
	}
	
</script>