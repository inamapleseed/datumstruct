<div class="news-search-block">
	<label class="control-label" for="input-search"><?php echo $entry_search; ?></label>
	<div class="row">
        <div class="col-sm-4">
			<?php if ($filter_name) { ?>
				<input type="text" name="filter_artname" value="<?php echo $filter_name; ?>" class="form-control" />
			<?php } else { ?>
				<input type="text" name="filter_artname" value="<?php echo $filter_name; ?>" onclick="this.value = '';" onkeydown="this.style.color = '000000'" style="color: #999;" class="form-control" />
			<?php } ?>
		</div>
		<div class="col-sm-3">
			<select name="filter_category_id" class="form-control" >
        		<option value="0"><?php echo $text_category; ?></option>
        		<?php foreach ($categories as $category_1) { ?>
        			<?php if ($category_1['category_id'] == $filter_category_id) { ?>
        				<option value="<?php echo $category_1['category_id']; ?>" selected="selected"><?php echo $category_1['name']; ?></option>
        			<?php } else { ?>
        				<option value="<?php echo $category_1['category_id']; ?>"><?php echo $category_1['name']; ?></option>
        			<?php } ?>
        			<?php foreach ($category_1['children'] as $category_2) { ?>
        				<?php if ($category_2['category_id'] == $filter_category_id) { ?>
        					<option value="<?php echo $category_2['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
        				<?php } else { ?>
        					<option value="<?php echo $category_2['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
        				<?php } ?>
        				<?php foreach ($category_2['children'] as $category_3) { ?>
        					<?php if ($category_3['category_id'] == $filter_category_id) { ?>
        						<option value="<?php echo $category_3['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
        					<?php } else { ?>
        						<option value="<?php echo $category_3['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
        					<?php } ?>
        				<?php } ?>
        			<?php } ?>
        		<?php } ?>
			</select>
		</div>
        <div class="col-sm-3">
          <label class="checkbox-inline">
            <?php if ($filter_sub_category) { ?>
            <input type="checkbox" name="filter_sub_category" value="1" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="filter_sub_category" value="1" />
            <?php } ?>
            <?php echo $text_sub_category; ?></label>
        </div>
    </div>
    <p>
        <label class="checkbox-inline">
          <?php if ($filter_description) { ?>
          <input type="checkbox" name="filter_description" value="1" id="description" checked="checked" />
          <?php } else { ?>
          <input type="checkbox" name="filter_description" value="1" id="description" />
          <?php } ?>
          <?php echo $entry_description; ?></label>
    </p>
    <input type="button" value="<?php echo $button_search; ?>" id="button-search" class="btn btn-primary" />
</div>

  <h2><?php echo $text_search; ?></h2>
  <?php if ($article) { ?>
	<div class="bnews-list<?php if ($display_s) { ?> bnews-list-2<?php } ?>">
		<?php foreach ($article as $articles) { ?>
			<div class="artblock<?php if ($display_s) { ?> artblock-2<?php } ?>">
				<?php if ($articles['name']) { ?>
					<div class="name"><a href="<?php echo $articles['href']; ?>"><?php echo $articles['name']; ?></a></div>
				<?php } ?>
				<div class="article-meta">
					<?php if ($articles['author']) { ?>
						<?php echo $text_posted_by; ?> <a href="<?php echo $articles['author_link']; ?>"><?php echo $articles['author']; ?></a> |
					<?php } ?>
					<?php if ($articles['date_added']) { ?>
						<?php if ($articles['author']) { ?><?php echo $text_posted_on; ?><?php } else { ?><?php echo $text_posted_pon; ?><?php } ?> <?php echo $articles['date_added']; ?> |
					<?php } ?>
					<?php if ($articles['du']) { ?>
						<?php echo $text_updated_on; ?> <?php echo $articles['du']; ?> |
					<?php } ?>
					<?php if ($articles['category']) { ?>
						<?php echo $text_posted_in; ?> <?php echo $articles['category']; ?> |
					<?php } ?>
				</div>
				<?php if ($articles['thumb']) { ?>
					<a href="<?php echo $articles['href']; ?>"><img class="article-image" align="left" src="<?php echo $articles['thumb']; ?>" title="<?php echo $articles['name']; ?>" alt="<?php echo $articles['name']; ?>" /></a>
				<?php } ?>
				<?php if ($articles['custom1']) { ?>
					<div class="custom1"><?php echo $articles['custom1']; ?></div>
				<?php } ?>
				<?php if ($articles['custom2']) { ?>
					<div class="custom2"><?php echo $articles['custom2']; ?></div>
				<?php } ?>
				<?php if ($articles['custom3']) { ?>
					<div class="custom3"><?php echo $articles['custom3']; ?></div>
				<?php } ?>
				<?php if ($articles['custom4']) { ?>
					<div class="custom4"><?php echo $articles['custom4']; ?></div>
				<?php } ?>
				<?php if ($articles['description']) { ?>
					<div class="description"><?php echo $articles['description']; ?></div>
				<?php } ?>
				<?php if ($articles['total_comments']) { ?>
				  <?php if (!$disqus_status && !$fbcom_status) { ?>
					<div class="total-comments"><?php echo $articles['total_comments']; ?> <?php echo $text_comments; ?> - <a href="<?php echo $articles['href']; ?>#comments"><?php echo $text_comments_v; ?></a></div>
				  <?php } elseif ($fbcom_status) { ?>
					<div class="total-comments"><fb:comments-count href="<?php echo $articles['href']; ?>"></fb:comments-count> <?php echo $text_comments; ?> - <a href="<?php echo $articles['href']; ?>#comments"><?php echo $text_comments_v; ?></a></div>
				  <?php } else { ?>
					<div class="total-comments"><a data-disqus-identifier="article_<?php echo $articles['article_id']; ?>" href="<?php echo $articles['href']; ?>#disqus_thread"><?php echo $text_comments_v; ?></a></div>
				  <?php } ?>
				<?php } ?>
				<?php if ($articles['button']) { ?>
					<div class="blog-button"><a class="button" href="<?php echo $articles['href']; ?>"><?php echo $button_more; ?></a></div>
				<?php } ?>
			</div>
		<?php } ?>
  </div>
  <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $pag_results; ?></div>
  </div>
  <?php } else { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <?php }?>

<script type="text/javascript"><!--
$('#button-search').bind('click', function() {
	url = 'index.php?route=news/search';
	
	var search = $('.news-search-block input[name=\'filter_artname\']').prop('value');
	
	if (search) {
		url += '&filter_artname=' + encodeURIComponent(search);
	}

	var category_id = $('.news-search-block select[name=\'filter_category_id\']').prop('value');
	
	if (category_id > 0) {
		url += '&filter_category_id=' + encodeURIComponent(category_id);
	}
	
	var sub_category = $('.news-search-block input[name=\'filter_sub_category\']:checked').prop('value');
	
	if (sub_category) {
		url += '&filter_sub_category=true';
	}
		
	var filter_description = $('.news-search-block input[name=\'filter_description\']:checked').prop('value');
	
	if (filter_description) {
		url += '&filter_description=true';
	}

	location = url;
});

$('.news-search-block input[name=\'filter_artname\']').bind('keydown', function(e) {
	if (e.keyCode == 13) {
		$('#button-search').trigger('click');
	}
});

--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('img.article-image').each(function(index, element) {
		var articleWidth = $(this).parent().parent().width() * 0.7;
		var imageWidth = $(this).width() + 10;
		if (imageWidth >= articleWidth) {
			$(this).attr("align","center");
			$(this).parent().addClass('bigimagein');
		}
	});
});
//--></script> 
<?php if ($disqus_status) { ?>
<script type="text/javascript">
var disqus_shortname = '<?php echo $disqus_sname; ?>';
(function () {
var s = document.createElement('script'); s.async = true;
s.type = 'text/javascript';
s.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
(document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
}());
</script>
<?php } ?>
<?php if ($fbcom_status) { ?>
<script type="text/javascript">
      window.fbAsyncInit = function() {
        FB.init({
          appId      : '<?php echo $fbcom_appid; ?>',
		  status     : true,
          xfbml      : true,
		  version    : 'v2.0'
        });
      };

      (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "//connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
       }(document, 'script', 'facebook-jssdk'));
</script>
<?php } ?>