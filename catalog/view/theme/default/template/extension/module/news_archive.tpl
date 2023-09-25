<h3><?php echo $heading_title; ?></h3>
<div class="list-group">
	<?php $index = 0; ?>
       <?php foreach ($archives as $archive) { ?>
		 <?php $index++ ?>
		 <?php if ($index > 1 && (count($archive['month']) > 3 || count($archive) > 4) && (count($archive) > 2 || count($archive['month']) > 5)) { ?>
		   <span class="list-group-item hidesthemonths" style="cursor: pointer"><?php echo $archive['year']; ?>
			 <div class="list-group" style="display: none;">
			   <?php foreach ($archive['month'] as $month) { ?>
		         <a class="list-group-item" href="<?php echo $month['href']; ?>"><?php echo $month['name']; ?></a>
		       <?php } ?>
			 </div>
		   </span>
		 <?php } else { ?>
		   <?php foreach ($archive['month'] as $month) { ?>
		     <a href="<?php echo $month['href']; ?>" class="list-group-item"><?php echo $month['name']; ?></a>
		   <?php } ?>
		 <?php } ?>
       <?php } ?>
	  <?php echo !$archives ? 'No articles' : ''; ?>
</div>
<script type="text/javascript">
$(document).ready(function () {
	$('.hidesthemonths').on('click', function () {
		$(this).find('div').slideToggle('fast');
	});
});
</script>