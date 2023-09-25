<?= $header; ?>
<div class="inner-prod"  data-aos="fade-left">
		<div class="container">
		<?= $content_top; ?>
		<ul class="breadcrumb">
			<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li><a href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['text']; ?></a></li>
			<?php } ?>
		</ul>
		<div class="row"><?= $column_left; ?>

		<?php if ($column_left && $column_right) { ?>
			<?php $class = 'col-sm-6'; ?>
		<?php } elseif ($column_left || $column_right) { ?>
			<?php $class = 'col-sm-9'; ?>
		<?php } else { ?>
			<?php $class = 'col-sm-12'; ?>
		<?php } ?>

		<div id="content" class="<?= $class; ?>">
			<h2 class="hidden"><?= $heading_title; ?></h2>
			<div class="row">
				<?php if ($column_left || $column_right) { ?>
					<?php $class = 'col-sm-6'; ?>
				<?php } else { ?>
					<?php $class = 'col-sm-6'; ?>
				<?php } ?>
				<div class="<?= $class; ?>">
					<?php include_once('product_image.tpl'); ?>
				</div>

				<?php if ($column_left || $column_right) { ?>
					<?php $class = 'col-sm-6 prod-desc'; ?>
				<?php } else { ?>
					<?php $class = 'col-sm-6 prod-desc'; ?>
				<?php } ?>

				<div class="<?= $class; ?>">
					<?php include_once('product_description.tpl'); ?>
				</div>
			</div>

			<?php //if ($products) include_once('product_related.tpl'); ?>
			<?= $related_products_slider; ?>

</div>
<?= $column_right; ?></div>
<?= $content_bottom; ?>
</div>
</div>
<input type="text" name="upc" class="hidden" id="upc" value="<?= $upc; ?>">

<script type="text/javascript"><!--
	<?php if ($humanscale == true) { ?>
		// $('header').addClass('d-none');
		// $('.page-banner').addClass('d-none');
		// $('#footer-area').addClass('d-none');
	<?php } ?>
	$('select[name=\'recurring_id\'], input[name="quantity"]').change(function(){
		$.ajax({
			url: 'index.php?route=product/product/getRecurringDescription',
			type: 'post',
			data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
			dataType: 'json',
			beforeSend: function() {
				$('#recurring-description').html('');
			},
			success: function(json) {
				$('.alert, .text-danger').remove();

				if (json['success']) {
					$('#recurring-description').html(json['success']);
				}
			}
		});
	});
	//--></script>
	<?php if(!$enquiry){ ?>
		<script type="text/javascript">
			$('#button-cart').on('click', function() {

				human = "<?= $humanscale; ?>";
							
				if($('#input-quantity').val() > 0) {

		        $.ajax({
		        	url: 'index.php?route=checkout/cart/add',
		        	type: 'post',
		        	data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
		        	dataType: 'json',
		        	beforeSend: function () {
		        		$('#button-cart').button('loading');
		        	},
		        	complete: function () {
		        		$('#button-cart').button('reset');
		        	},
		        	success: function (json) {
		        		$('.alert, .text-danger').remove();
		        		$('.form-group').removeClass('has-error');

		        		if (json['error']) {
		        			if (json['error']['option']) {
		        				for (i in json['error']['option']) {
		        					var element = $('#input-option' + i.replace('_', '-'));

		        					if (element.parent().hasClass('input-group')) {
		        						element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
		        					} else {
		        						element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
		        					}
		        				}
		        			}

		        			if (json['error']['recurring']) {
		        				$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
		        			}

							// Highlight any found errors
							$('.text-danger').parent().addClass('has-error');
						}

						if (json['success']) {
							//$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');


						if (human == 1) {
							window.location.href = "<?= $checkout; ?>";
						} else {
							swal({
								title: json['success_title'],
								html: json['success'],
								type: "success"
							});

						}

							


							setTimeout(function () {
								$('#cart-quantity-total').text(json['total_quantity']);
								$('#cart-total').text(json['total']);
							}, 100);

							$('#cart > ul').load('index.php?route=common/cart/info ul > *');
						}

						if(json['error_stock_add']){
							swal({
								title: json['error_stock_add_title'],
								html: json['error_stock_add'],
								type: "error"
							});
						}

						if(json['error_outofstock']){
							swal({
								title: json['error_outofstock_title'],
								html: json['error_outofstock'],
								type: "error"
							});
						}
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
		    }
		    
			});

			$('#button-cart1').on('click', function() {

				human = "<?= $upc; ?>";
							
				if($('#input-quantity').val() > 0) {

		        $.ajax({
		        	url: 'index.php?route=checkout/cart/add',
		        	type: 'post',
		        	data: $('#product input[type=\'text\'], #upc, #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
		        	dataType: 'json',
		        	beforeSend: function () {
		        		$('#button-cart').button('loading');
		        	},
		        	complete: function () {
		        		$('#button-cart').button('reset');
		        	},
		        	success: function (json) {
		        		$('.alert, .text-danger').remove();
		        		$('.form-group').removeClass('has-error');

		        		if (json['error']) {
		        			if (json['error']['option']) {
		        				for (i in json['error']['option']) {
		        					var element = $('#input-option' + i.replace('_', '-'));

		        					if (element.parent().hasClass('input-group')) {
		        						element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
		        					} else {
		        						element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
		        					}
		        				}
		        			}

		        			if (json['error']['recurring']) {
		        				$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
		        			}

							// Highlight any found errors
							$('.text-danger').parent().addClass('has-error');
						}

						if (json['success']) {
							//$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');


						if (human == 1) {
							window.location.href = "<?= $checkout; ?>";
						} else {
							swal({
								title: json['success_title'],
								html: json['success'],
								type: "success"
							});

						}

							


							setTimeout(function () {
								$('#cart-quantity-total').text(json['total_quantity']);
								$('#cart-total').text(json['total']);
							}, 100);

							$('#cart > ul').load('index.php?route=common/cart/info ul > *');
						}

						if(json['error_stock_add']){
							swal({
								title: json['error_stock_add_title'],
								html: json['error_stock_add'],
								type: "error"
							});
						}

						if(json['error_outofstock']){
							swal({
								title: json['error_outofstock_title'],
								html: json['error_outofstock'],
								type: "error"
							});
						}
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
		    }
		    
			});
		</script>
<?php }else{ ?>
	<script type="text/javascript">
		$('#button-enquiry').on('click', function () {
			if ($('#input-quantity').val()  > 0) {
				$.ajax({
					url: 'index.php?route=enquiry/cart/add',
					type: 'post',
					data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
					dataType: 'json',
					beforeSend: function () {
						$('#button-enquiry').button('loading');
					},
					complete: function () {
						$('#button-enquiry').button('reset');
					},
					success: function (json) {
						$('.alert, .text-danger').remove();
						$('.form-group').removeClass('has-error');

						if (json['error']) {
							if (json['error']['option']) {
								for (i in json['error']['option']) {
									var element = $('#input-option' + i.replace('_', '-'));

									if (element.parent().hasClass('input-group')) {
										element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
									} else {
										element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
									}
								}
							}

							if (json['error']['recurring']) {
								$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
							}

						// Highlight any found errors
						$('.text-danger').parent().addClass('has-error');
					}

					if (json['success']) {
						//$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

						swal({
							title: json['success_title'],
							html: json['success'],
							type: "success"
						});

						setTimeout(function () {
							$('#enquiry-quantity-total').text(json['total_quantity']);
							$('#enquiry-total').text(json['total']);
						}, 100);

						$('#enquiry > ul').load('index.php?route=common/enquiry/info ul > *');
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
			}
		});
		//--></script>
	<?php } ?>
	
	<script>
        let buttons = document.querySelectorAll(".selectZoom");
        let i = 0, length = buttons.length;
        for (i; i < length; i++) {
            if (document.addEventListener) {
                buttons[i].addEventListener("click", function() {
                    $.removeData($('#zoom_01'), 'elevateZoom');//remove zoom instance from image
                    $.removeData($('#zoom_01'), 'zoomImage');//remove zoom instance from image
                    $('.zoomContainer').remove();// remove zoom container from DOM
                });
            } else {
                buttons[i].attachEvent("onclick", function() {
                    // use buttons[i] to target clicked button
                });
            };
        };
        
        let main_images = document.querySelectorAll(".main_images");
        let z = 0, main_images_length = main_images.length;
        for (z; z < main_images_length; z++) {
            if (document.addEventListener) {
                main_images[z].addEventListener("mouseover", function() {
                    //Re-create
                    $('.slick-active #zoom_01').elevateZoom({
                        zoomType: "inner",
                        cursor: "crosshair",
                        zoomWindowFadeIn: 500,
                        zoomWindowFadeOut: 750
                    });
                });
            } else {
                main_images[z].attachEvent("onclick", function() {
                    // use buttons[i] to target clicked button
                });
            };
        };
    </script>

<?= $footer; ?>
<script>
	<?php if ($humanscale == 1) { ?>
		$('header').addClass('d-none');
		$('.page-banner').addClass('d-none');
		$('#footer-area').addClass('d-none');
	<?php } ?>
</script>
