<?php if($social_icons){ ?>
	<div class="footer-social-icons">
		<div class="social">
			<?php foreach($social_icons as $icon){ ?>
				<a href="<?= $icon['link']; ?>" title="<?= $icon['title']; ?>" alt="
				      <?= $icon['title']; ?>" target="_blank">
					<img src="<?= $icon['icon']; ?>" title="<?= $icon['title']; ?>" class="img-responsive" alt="<?= $icon['title']; ?>" />
				</a>
			<?php } ?>
		</div>  
	</div>
<?php } ?>
<div class="post_container">
	<?php foreach ($items as $i => $it): ?>
		<div class="post"  data-aos="zoom-in-up" id="<?=$i;?>">
			<div class="post_inner">
				<?=html_entity_decode($it['desc'])?>
			</div>
			<div class="a">
				<a target="_blank" href="" class="btn custom_button">
					View More
				</a>
			</div>
		</div>
	<?php endforeach ?>
</div>
<?php if ($i >= 3): ?>
	<div id="pagination-container"></div>
<?php endif ?>

<script type="text/javascript">
	$(document).ready(function(){
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
</script>


<script type="text/javascript">
	let items = $(".post_container .post");
		let numItems = items.length;
		let perPage = 3;

		items.slice(perPage).hide();

		$('#pagination-container').pagination({
		items: numItems,
		itemsOnPage: perPage,
		hrefTextPrefix: '',
		prevText: "&lt;",
		nextText: "&gt;",
		onPageClick: function (pageNumber) {
		    var showFrom = perPage * (pageNumber - 1);
		    var showTo = showFrom + perPage;

			$(".simple-pagination a").each(function(e){
				$(this).removeAttr('href');
			});

		    items.hide().slice(showFrom, showTo).show();
		}
		});

		$(".simple-pagination a").removeAttr("href");

		$(document).ready(function(){
			$("#pagination-container span").each(function(){
				let prev = $(this).text();
				$(this).text(prev.replace("prev", ""));
			});

			$("#pagination-container a").each(function(){
				let next = $(this).text();
				$(this).text(next.replace("next", ""));
			});
		})
</script>