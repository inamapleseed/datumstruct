<div class="partners_container">
	<div class="partners_heading">
		<hr>
		<h3>Partners</h3>
	</div>
	<div class="partners_container_inner">
		<?php foreach ($items as $partners): ?>
			<div class="partners">
				<img src="image/<?=$partners['image'];?>" title="<?=$partners['name'];?>" alt="<?=$partners['name'];?>">
			</div>	
		<?php endforeach ?>
	</div>
</div>
<!-- Contact Section -->
<div class="contact_home" >
	<div class="contact_map">
		<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.73968948207!2d103.967596114754!3d1.3323160990282792!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da3cd1a8f9a10d%3A0x7106afebe249406!2sDatumstruct%20(S)%20Pte%20Ltd!5e0!3m2!1sen!2sph!4v1572942913801!5m2!1sen!2sph" width="750" height="500" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
	</div>
	<div class="contact_texts" style="background-image: url(image/catalog/ds/homepage/visit-min.jpg); background-repeat: no-repeat; background-size: cover;">
		<div class="texts_inner">
			<hr>
			<h3>Visit Us</h3>
			<div  class="contact_address">
				<p><?=$store;?>&nbsp;Pte Ltd</p>
				<address>
					<?=$address;?>
				</address>
			</div>	
			<a href="index.php?route=information/contact" class="btn custom_button">
				Contact Us
			</a>
			<div class="open">
				<h4>Opening Hours</h4>
				<p>
					<?=$open;?>
				</p>	
				<p>
					<?=$comment;?>
				</p>
			</div>	
		</div>
	</div>
</div>
<!-- End of Contact Section -->
<script type="text/javascript">
		function initSlider6(){
			
		$('.partners_container_inner').slick({
			dots: false,
			infinite: true,
			speed: 300,
			slidesToShow: 6,
			autoplay: false,
			arrows: true,
			prevArrow:"<img alt='arrow' class='def-left' src='image/catalog/ds/homepage/left-min.png'><img alt='arrow' class='onhover' src='image/catalog/ds/homepage/left-min2.png'>",
			nextArrow:"<img alt='arrow' class='def-right' src='image/catalog/ds/homepage/right-min2.png'><img alt='arrow' class='onhover' src='image/catalog/ds/homepage/right-min.png'>",
			responsive: [
		    {
		      breakpoint: 1024,
		      settings: {
		        slidesToShow: 3,
		        slidesToScroll: 1,
		      }
		    },
		    {
		      breakpoint: 600,
		      settings: {
		        slidesToShow: 2,
		        slidesToScroll: 1
		      }
		    },
		    {
		      breakpoint: 480,
		      settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1
		      }
		    }
		  ]
		})
	};
$(document).ready(function() {
			initSlider6();
		});
</script>