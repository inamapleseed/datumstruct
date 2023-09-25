<?php if ($is_category) { ?>

  <?php if ($ncategories) { ?>
  <h2><?php echo $text_refine; ?></h2>
  <div class="category-list" style="border-bottom: 2px solid #eee;">
    <?php if (count($ncategories) <= 5) { ?>
    <ul>
      <?php foreach ($ncategories as $ncategory) { ?>
      <li><a href="<?php echo $ncategory['href']; ?>"><?php echo $ncategory['name']; ?></a></li>
      <?php } ?>
    </ul>
    <?php } else { ?>
    <?php for ($i = 0; $i < count($ncategories);) { ?>
    <ul>
      <?php $j = $i + ceil(count($ncategories) / 4); ?>
      <?php for (; $i < $j; $i++) { ?>
      <?php if (isset($ncategories[$i])) { ?>
      <li><a href="<?php echo $ncategories[$i]['href']; ?>"><?php echo $ncategories[$i]['name']; ?></a></li>
      <?php } ?>
      <?php } ?>
    </ul>
    <?php } ?>
    <?php } ?>
  </div>
  <?php } ?>
<?php } ?>

<?php if ($article) { ?>
	<div class="brochures <?php if ($display_style) { ?> bnews-list-2<?php } ?>">
		<?php foreach ($article as $articles) { ?>
			<div class="brochure_container artblock<?php if ($display_style) { ?> artblock-2<?php } ?>">

				<?php if ($articles['thumb']): ?>
          <div class="brochure_image">
  					<!-- <a href="<?php echo $articles['href']; ?>"> -->
            <a>
              <img class="article-image" src="<?php echo $articles['thumb']; ?>" title="<?php echo $articles['name']; ?>" alt="<?php echo $articles['name']; ?>" />
            </a>
          </div>
          <?php else: ?>
            <div class="brochure_image">
              <i>Image Unavailable</i>
            </div>
				<?php endif ?>

        <?php if ($articles['name']) { ?>
          <div class="brochure_texts">
            <div class="name">
              <h4>
                <a>
                  <?php echo $articles['name']; ?>
                </a>
              </h4>
            </div>
          </div>
          <?php } ?>

  				<?php if ($articles['description']): ?>
  					<div class="description">
              <?php echo $articles['description']; ?>
            </div>
            <?//php else: ?>
            <!--<div class="description i">
              <i>Description Unavailable</i>
            </div>-->
  				<?php endif ?>
        <?php //debug($articles['download']); ?>

        <div class="dlbtn">
          <?php if ($articles['download']): ?>
            <a href="<?= $articles['download']; ?>" download="download" class="btn custom_button">
              Download Brochure
            </a>
            <?php else: ?>
            <a disabled href="<?= $articles['download']; ?>" download="download" class="btn custom_button">
              Brochure Unavailable
            </a>

          <?php endif ?>
        </div>

			</div>
		<?php } ?>
  </div>
  <div class="row">
        <div class="text-center"><?php echo $pagination; ?></div>
  </div>
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
		$(window).resize(function() {
		$('img.article-image').each(function(index, element) {
		var articleWidth = $(this).parent().parent().width() * 0.7;
		var imageWidth = $(this).width() + 10;
		if (imageWidth >= articleWidth) {
			$(this).attr("align","center");
			$(this).parent().addClass('bigimagein');
		}
		});
		});
	});
	//--></script> 
<?php } ?>
<?php if ($is_category) { ?>
  <?php if (!$ncategories && !$article) { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <?php } ?>
<?php } else { ?>
  <?php if (!$article) { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <?php } ?>
<?php } ?>