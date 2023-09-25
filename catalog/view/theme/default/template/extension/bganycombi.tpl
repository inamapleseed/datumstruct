<style type="text/css">
.bganycombirib{position:absolute;left:15px;top:0;z-index:1;overflow:hidden;width:150px;height:150px;text-align:right}
.bganycombirib span{font-size:12px;font-weight:700;color:#FFF;text-transform:uppercase;text-align:center;line-height:25px;transform:rotate(-45deg);-webkit-transform:rotate(-45deg);width:200px;display:block;background:#79A70A;background:linear-gradient(#F70505 0%,#8F0808 100%);box-shadow:0 3px 10px -5px rgba(0,0,0,1);position:absolute;top:40px;left:-40px}
.bganycombirib span::before{content:"";position:absolute;left:0;top:100%;z-index:-1;border-left:3px solid #8F0808;border-right:3px solid transparent;border-bottom:3px solid transparent;border-top:3px solid #8F0808}
.bganycombirib span::after{content:"";position:absolute;right:0;top:100%;z-index:-1;border-left:3px solid transparent;border-right:3px solid #8F0808;border-bottom:3px solid transparent;border-top:3px solid #8F0808}
</style>
 
<script language="javascript" src="index.php?route=<?php echo $modpath;?>/getjson&v=<?php echo rand(); ?>"></script>
<script language="javascript">  
$('.product-thumb').find('.button-group button:nth-child(1)').each(function() {
	if($( this ).attr('onclick')) { 
		var product_id = $( this ).attr('onclick').match(/\d+/);
 		if(bganycombi_products_data[product_id]) {
			$( this ).parent().parent().find('.caption').prepend('<div class="bganycombirib"><span>'+ bganycombi_products_data[product_id] + '</span></div>');
		} else if (bganycombi_products_data['all'] == 1) {
			$( this ).parent().parent().find('.caption').prepend('<div class="bganycombirib"><span>'+ bganycombi_products_data['ribbontext']+ '</span></div>');
		}
	}
});

<?php if($product_route == 1) { ?>
	// for product details page //

	if(bganycombi_products_data[<?php echo $product_id;?>]) {
		//$( '#content' ).prepend('<div class="bganycombirib"><span>'+ bganycombi_products_data[<?php echo $product_id;?>] + '</span></div>');
		$( '.product-image-column' ).prepend('<div class="bganycombirib"><span>'+ bganycombi_products_data[<?php echo $product_id;?>] + '</span></div>');
	} else if (bganycombi_products_data['all'] == 1) {
		//$( '#content' ).prepend('<div class="bganycombirib"><span>'+ bganycombi_products_data['ribbontext']+ '</span></div>');
		$( '.product-image-column' ).prepend('<div class="bganycombirib"><span>'+ bganycombi_products_data['ribbontext']+ '</span></div>');
	} 
	
	<?php /*if($bganycombi_info) { ?>
	<?php foreach($bganycombi_info as $bganyinfo) { ?>
		
		<?php if($bganyinfo['display_offer_at'] == 1) { ?>
			$( 'ul.nav-tabs' ).prepend('<div class="well"><h3><?php echo $bganyinfo['offer_heading_text']; ?></h3><?php echo $bganyinfo['offer_content']; ?></div>');
		<?php } ?> 
		
		<?php if($bganyinfo['display_offer_at'] == 2) { ?>
			$( '#product' ).prepend('<button type="button" class="btn btn-info button" data-toggle="modal" data-target="#bganycombi_Modal<?php echo $bganyinfo['bganycombi_id']; ?>"><?php echo $bganyinfo['offer_heading_text'];?></button>');
		<?php } ?>
		
		<?php if($bganyinfo['display_offer_at'] == 3) { ?>
			$( 'ul.nav-tabs' ).append('<li><a href="#tab-tab_bganycombi<?php echo $bganyinfo['bganycombi_id']; ?>" data-toggle="tab"><?php echo $bganyinfo['offer_heading_text'];?></a></li>');
			$( '.tab-content' ).append('<div class="tab-pane" id="tab-tab_bganycombi<?php echo $bganyinfo['bganycombi_id']; ?>"><?php echo $bganyinfo['offer_content'];?></div>');
		<?php } ?>
		
	<?php } ?> 
	<?php }*/ ?>  
	
<?php } ?> 
</script>  

<?php /*if($product_route == 1) { ?>
<?php if($bganycombi_info) { ?>
<?php foreach($bganycombi_info as $bganyinfo) { ?>
	
<?php if($bganyinfo['display_offer_at'] == 2) { ?>
<div id="bganycombi_Modal<?php echo $bganyinfo['bganycombi_id']; ?>" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo $bganyinfo['offer_heading_text'];?></h4>
      </div>
      <div class="modal-body">
        <?php echo $bganyinfo['offer_content'];?>
      </div>
    </div>
  </div>
</div>
<?php } ?> 

<?php } ?> 
<?php } ?> 
<?php }*/ ?>