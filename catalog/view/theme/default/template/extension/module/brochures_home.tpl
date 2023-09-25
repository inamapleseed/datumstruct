<div class="brochure_home_cont" >
	<div class="brochure_home_inner" >
		<div class="bh_text">
			<hr>
			<h3>
				Brochure Offering
			</h3>
		</div>
		<div class="brochure_img_container">
			<?php foreach ($repeater as $i => $j): ?>
				<?php if ($i <= 1): ?>
					<div class="brochure_img">
						<a href="index.php?route=news/ncategory">
							<!-- <img alt="image" id="<?=$i;?>" src="image/<?=$f['image'];?>"> -->
							<img src="image/<?=$j['image'];?>" id="<?=$i;?>" alt="image">
						</a>	
					</div>	
				<?php endif ?>
			<?php endforeach ?>
		</div>	
		<div class="a">	
			<a class="btn custom_button" href="index.php?route=news/ncategory">
				Show More
			</a>
		</div>	
	</div>
</div>
<!-- MEDIA SECTION -->
<!--<div class="media_container" >
	<div class="media_heading" >
		<hr>
		<h3>Media</h3>
	</div>
	<div class="media_container_inner">
		<?php foreach ($items['items'] as $media): ?>
			<div class="media_outer">
				<div class="media">
					<?=html_entity_decode($media['desc']); ?>
				</div>
				<div class="a">
					<a target="_blank" href="" class="btn custom_button">
						View More
					</a>
				</div>			
			</div>	
		<?php endforeach ?>
	</div>
</div>-->
<script type="text/javascript">
	$(document).ready(function(){
	   // initSlider4();
	    
		$('.media_container_inner iframe').each(function(){

			var url = decodeURIComponent($(this).attr('src'));
			var a = $(this).parent().parent().parent().find('div.a a');

			var href = getParameterByName('href', url);
			
			a.attr('href', href);
		});

	});	

	function getParameterByName(name, url) {
	  name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	  var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
	      results = regex.exec(url);
	  return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
	}
	
// 	function initSlider4(){
// 		$('.media_container_inner').slick({
// 			dots: false,
// 			infinite: true,
// 			speed: 300,
// 			slidesToShow: 3,
// 			autoplay: false,
// 			arrows: true,
// 			prevArrow:"<img alt='arrow' class='def-left' src='image/catalog/ds/homepage/left-min.png'><img alt='arrow' class='onhover' src='image/catalog/ds/homepage/left-min2.png'>",
// 			nextArrow:"<img alt='arrow' class='def-right' src='image/catalog/ds/homepage/right-min2.png'><img alt='arrow' class='onhover' src='image/catalog/ds/homepage/right-min.png'>",
// 			responsive: [
// 		    {
// 		      breakpoint: 1024,
// 		      settings: {
// 		        slidesToShow: 2,
// 		        slidesToScroll: 1,
// 		      }
// 		    },
// 		    {
// 		      breakpoint: 600,
// 		      settings: {
// 		        slidesToShow: 2,
// 		        slidesToScroll: 1
// 		      }
// 		    },
// 		    {
// 		      breakpoint: 480,
// 		      settings: {
// 		        slidesToShow: 1,
// 		        slidesToScroll: 1
// 		      }
// 		    }
// 		  ]
// 		})
// 	}
</script>
