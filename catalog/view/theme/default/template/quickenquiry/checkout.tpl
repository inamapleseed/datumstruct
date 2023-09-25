<?= $header; ?>
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
      <h2><?= $overwrite_checkout_heading_title; ?></h2> 
	  <!-- Start -->
	  <div id="warning-messages"></div>
	  <div id="success-messages"></div>
	  <?php if ($mobile_stylesheet) { ?>
	  <link rel="stylesheet" media="screen and (min-width: 701px) and (max-width: 99999px)" href="<?= $stylesheet; ?>" />
	  <link rel="stylesheet" media="screen and (min-width: 1px) and (max-width: 700px)" href="<?= $mobile_stylesheet; ?>" />
	  <?php } else { ?>
	  <link rel="stylesheet" href="<?= $stylesheet; ?>" />
	  <?php } ?>

	  <?php if ($html_header) { ?>
	  	<?= $html_header; ?>
	  <?php } ?>

	  <div id="quickcheckout-countdown"></div>

	  <div id="quickcheckoutconfirm">

		<div id="quickcheckout-disable">
		  <?php if (!$logged && $login_module) { ?>
		  <div class="quickcheckoutmid" id="login-box">
			<div id="checkout">
			  <div class="quickcheckout-heading"><i class="fa fa-sign-in"></i> <?= $text_checkout_option; ?></div>
			  <div class="quickcheckout-content"><?= $login; ?></div>
			</div>
			<div class="or"><?= $text_or; ?></div>
		  </div>
		  <?php } ?>
		  <div class="quickcheckoutleft">
			<div id="payment-address">
			  <div class="quickcheckout-heading"><i class="fa fa-user"></i> <?= !$logged ? $text_checkout_account : $text_checkout_payment_address; ?></div>
			  <div class="quickcheckout-content"><?= isset($guest) ? $guest : $payment_address; ?></div>
			</div>
			<?php if ($shipping_required) { ?>
			<div id="shipping-address">
			  <div class="quickcheckout-heading"><i class="fa fa-user"></i> <?= $text_checkout_shipping_address; ?></div>
			  <div class="quickcheckout-content"><?= isset($shipping_address) ? $shipping_address : ''; ?></div>
			</div>
			<?php } ?>
		  </div>
		  <div class="quickcheckoutright">
			<?php if ($shipping_required) { ?>
			<div id="shipping-method"<?= !$shipping_module ? ' style="display:none;"' : ''; ?>>
			  <div class="quickcheckout-heading"><i class="fa fa-truck"></i> <?= $text_checkout_shipping_method; ?></div>
			  <div class="quickcheckout-content"></div>
			</div>
			<?php } ?>
			<div id="payment-method"<?= !$payment_module ? ' style="display:none;"' : ''; ?>>
			  <div class="quickcheckout-heading"><i class="fa fa-commenting" aria-hidden="true"></i>
 <?= $overwrite_checkout_payment_method; ?></div>
			  <div class="quickcheckout-content"></div>
			</div>
		  </div>
		  <?php if ($quickcheckout_layout == '2') { ?>
			<div class="quickcheckoutleft">
			  <?php if ($cart_module) { ?>
			  <div id="cart1">
				<div class="quickcheckout-content" style="border:none; padding: 0px;"></div>
			  </div>
			  <?php } ?>
			</div>
			<div style="clear:right;"></div>
			<div class="quickcheckoutright">
			  <?php if ($voucher_module || $coupon_module || $reward_module) { ?>
			  <div id="voucher">
				<div class="quickcheckout-content" style="border:none; padding: 0px;overflow: hidden;"><?= $voucher; ?></div>
			  </div>
			  <?php } ?>
			</div>
		  <?php } elseif ($quickcheckout_layout == '1' || $quickcheckout_layout == '3') { ?>
			<?php if ($cart_module || $voucher_module || $coupon_module || $reward_module) { ?>
			<div class="quickcheckoutleft extra-width">
			  <?php if ($cart_module) { ?>
			  <div id="cart1">
				<div class="quickcheckout-content" style="border:none; padding: 0px;"></div>
			  </div>
			  <?php } ?>
			  <?php if ($voucher_module || $coupon_module || $reward_module) { ?>
			  <div id="voucher">
				<div class="quickcheckout-content" style="border:none; padding: 0px;overflow: hidden;"><?= $voucher; ?></div>
			  </div>
			  <?php } ?>
			</div>
			<?php } ?>
		  <?php } ?>
		  <div style="clear: both;"></div>
		</div><!-- Close Quick Checkout Disable Div -->

		<div class="quickcheckoutmid">
		  <div id="terms">
			<div class="quickcheckout-content text-right"><?= $terms; ?></div>
		  </div>
		</div>
	  </div><!-- Close QuickCheckoutConfirm div -->

	  <?php if ($html_footer) { ?>
	  <?= $html_footer; ?>
	  <?php } ?>
	  <!-- End -->
	</div>
  <?= $column_right; ?></div>
  <?= $content_bottom; ?>
</div>

<?php if ($custom_css) { ?>
<style type="text/css">
	<?= $custom_css; ?>
</style>
<?php } ?>
<script type="text/javascript"><!--
<?php if ($countdown && $countdown_end) { ?>
$('#quickcheckout-countdown').countdown({
	timezone: <?= $timezone; ?>,
	until: new Date('<?= $countdown_end; ?>'),
    layout: '<?= addslashes($countdown_before); ?><b><?= $countdown_timer; ?></b> {desc}',
    description: '<?= addslashes($countdown_after); ?>'
});
<?php } ?>
<?php if ($load_screen) { ?>
$(window).load(function() {
    $.blockUI({
		message: '<h1 style="color:#ffffff;"><?= $text_please_wait; ?></h2>',
		css: {
			border: 'none',
			padding: '15px',
			backgroundColor: '#000000',
			'-webkit-border-radius': '10px',
			'-moz-border-radius': '10px',
			'-khtml-border-radius': '10px',
			'border-radius': '10px',
			opacity: .8,
			color: '#ffffff'
		}
	});
	
	setTimeout(function() {
		$.unblockUI();
	}, 2000);
});
<?php } ?>

<?php if (!$logged) { ?>
	<?php if ($save_data) { ?>
	// Save form data
	$(document).on('change', '#payment-address input[type=\'text\'], #payment-address select', function() {
		$.ajax({
			url: 'index.php?route=quickenquiry/checkout/save&type=payment',
			type: 'post',
			data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select, #payment-address textarea'),
			dataType: 'json',
			cache: false,
			success: function(json) {
				// No action needed
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	});

	$(document).on('change', '#shipping-address input[type=\'text\'], #shipping-address select', function() {
		$.ajax({
			url: 'index.php?route=quickenquiry/checkout/save&type=shipping',
			type: 'post',
			data: $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'password\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address input[type=\'hidden\'], #shipping-address select, #shipping-address textarea'),
			dataType: 'json',
			cache: false,
			success: function(json) {
				// No action needed
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	});
	<?php } ?>
	
	<?php if ($login_module) { ?>
	// Login Form Clicked
	$(document).on('click', '#button-login', function() {
		$.ajax({
			url: 'index.php?route=quickenquiry/login/validate',
			type: 'post',
			data: $('#checkout #login :input'),
			dataType: 'json',
			cache: false,
			beforeSend: function() {
				$('#button-login').prop('disabled', true);
				$('#button-login').button('loading');
			},
			complete: function() {
				$('#button-login').prop('disabled', false);
				$('#button-login').button('reset');
			},
			success: function(json) {
				$('.alert').remove();

				if (json['redirect']) {
					location = json['redirect'];
				} else if (json['error']) {
					$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
					
					$('html, body').animate({ scrollTop: 0 }, 'slow');
					
					$('.alert-danger').fadeIn('slow');
				}
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	});
	<?php } ?>

// Validate Register
function validateRegister() {
	$.ajax({
		url: 'index.php?route=quickenquiry/register/validate',
		type: 'post',
		data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select, #payment-address textarea'),
		dataType: 'json',
		cache: false,
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-payment-method').prop('disabled', false);
				$('#button-payment-method').button('reset');
				$('#terms input[type=\'checkbox\']').prop('checked', false);
				
				$('.fa-spinner').remove();
				
				$('html, body').animate({ scrollTop: 0 }, 'slow');
							
				if (json['error']['warning']) {
					$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
					
					$('.alert-danger').fadeIn('slow');
				}

				<?php if ($text_error) { ?>
					if (json['error']['password']) {
						$('#payment-address input[name=\'password\']').after('<div class="text-danger">' + json['error']['password'] + '</div>');
					}

					if (json['error']['confirm']) {
						$('#payment-address input[name=\'confirm\']').after('<div class="text-danger">' + json['error']['confirm'] + '</div>');
					}
				<?php } ?>
				<?php if ($highlight_error) { ?>
					if (json['error']['password']) {
						$('#payment-address input[name=\'password\']').css('border', '1px solid #f00').css('background', '#F8ACAC');
					}

					if (json['error']['confirm']) {
						$('#payment-address input[name=\'confirm\']').css('border', '1px solid #f00').css('background', '#F8ACAC');
					}
				<?php } ?>
			} else {
				<?php if ($shipping_required) { ?>
				var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').val();

				if (shipping_address) {
					validateShippingMethod();
				} else {
					validateGuestShippingAddress();
				}
				<?php } else {?>
				validatePaymentMethod();
				<?php } ?>
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

// Validate Guest Payment Address
function validateGuestAddress() {
	$.ajax({
		url: 'index.php?route=quickenquiry/guest/validate',
		type: 'post',
		data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address select, #payment-address textarea'),
		dataType: 'json',
		cache: false,
		success: function(json) {		
			$('.alert, .text-danger').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-payment-method').prop('disabled', false);
				$('#button-payment-method').button('reset');
				$('#terms input[type=\'checkbox\']').prop('checked', false);
				
				$('.fa-spinner').remove();
				
				$('html, body').animate({ scrollTop: 0 }, 'slow');
			
				if (json['error']['warning']) {
					$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
					
					$('.alert-danger').fadeIn('slow');
				}

				<?php if ($text_error) { ?>
					for (i in json['error']) {
						var element = $('#input-payment-' + i.replace('_', '-'));
						
						if ($(element).parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
						} else {
							$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
						}
					}
				<?php } ?>
				<?php if ($highlight_error) { ?>
					for (i in json['error']) {
						var element = $('#input-payment-' + i.replace('_', '-'));

						$(element).css('border', '1px solid #f00').css('background', '#F8ACAC');
					}
				<?php } ?>
			} else {
				var create_account = $('#payment-address input[name=\'create_account\']:checked').val();

				<?php if ($shipping_required) { ?>
				var shipping_address = $('#payment-address input[name=\'shipping_address\']:checked').val();

				if (create_account) {
					validateRegister();
				} else {
					if (shipping_address) {
						validateShippingMethod();
					} else {
						validateGuestShippingAddress();
					}
				}
				<?php } else { ?>
				if (create_account) {
					validateRegister();
				} else {
					validatePaymentMethod();
				}
				<?php } ?>
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

// Validate Guest Shipping Address
function validateGuestShippingAddress() {
	$.ajax({
		url: 'index.php?route=quickenquiry/guest_shipping/validate',
		type: 'post',
		data: $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address select, #shipping-address textarea'),
		dataType: 'json',
		cache: false,
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-payment-method').prop('disabled', false);
				$('#button-payment-method').button('reset');
				$('#terms input[type=\'checkbox\']').prop('checked', false);
				
				$('.fa-spinner').remove();
				
				$('html, body').animate({ scrollTop: 0 }, 'slow');
				
				if (json['error']['warning']) {
					$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
					
					$('.alert-danger').fadeIn('slow');
				}

				<?php if ($text_error) { ?>
					for (i in json['error']) {
						var element = $('#input-shipping-' + i.replace('_', '-'));
						
						if ($(element).parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
						} else {
							$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
						}
					}
				<?php } ?>
				<?php if ($highlight_error) { ?>
					for (i in json['error']) {
						var element = $('#input-shipping-' + i.replace('_', '-'));

						$(element).css('border', '1px solid #f00').css('background', '#F8ACAC');
					}
				<?php } ?>
			} else {
				validateShippingMethod();
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

// Confirm Payment
$(document).on('click', '#button-payment-method', function() {
	$('#button-payment-method').prop('disabled', true);
	$('#button-payment-method').button('loading');
	
	$('#button-payment-method').after('<i class="fa fa-spinner fa-spin"></i>');
	
	validateGuestAddress();
});
<?php } else { ?>
// Validate Payment Address
function validatePaymentAddress() {
	$.ajax({
		url: 'index.php?route=quickenquiry/payment_address/validate',
		type: 'post',
		data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select, #payment-address textarea'),
		dataType: 'json',
		cache: false,
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-payment-method').prop('disabled', false);
				$('#button-payment-method').button('reset');
				$('#terms input[type=\'checkbox\']').prop('checked', false);
				
				$('.fa-spinner').remove();
				
				$('html, body').animate({ scrollTop: 0 }, 'slow');
				
				if (json['error']['warning']) {
					$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
					
					$('.alert-danger').fadeIn('slow');
				}

				<?php if ($text_error) { ?>
					for (i in json['error']) {
						var element = $('#input-payment-' + i.replace('_', '-'));
						
						if ($(element).parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
						} else {
							$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
						}
					}
				<?php } ?>
				<?php if ($highlight_error) { ?>
					for (i in json['error']) {
						var element = $('#input-payment-' + i.replace('_', '-'));

						$(element).css('border', '1px solid #f00').css('background', '#F8ACAC');
					}
				<?php } ?>
			} else {
				<?php if ($shipping_required) { ?>
					validateShippingAddress();
				<?php } else { ?>
					validatePaymentMethod();
				<?php } ?>
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

<?php if ($shipping_required) { ?>
// Validate Shipping Address
function validateShippingAddress() {
	$.ajax({
		url: 'index.php?route=quickenquiry/shipping_address/validate',
		type: 'post',
		data: $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'password\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address select, #shipping-address textarea'),
		dataType: 'json',
		cache: false,
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-payment-method').prop('disabled', false);
				$('#button-payment-method').button('reset');
				$('#terms input[type=\'checkbox\']').prop('checked', false);
				
				$('.fa-spinner').remove();
				
				$('html, body').animate({ scrollTop: 0 }, 'slow');
				
				if (json['error']['warning']) {
					$('#warning-messages').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"> ' + json['error']['warning'] + '</div>');
					
					$('.alert-danger').fadeIn('slow');
				}

				<?php if ($text_error) { ?>
					for (i in json['error']) {
						var element = $('#input-shipping-' + i.replace('_', '-'));
						
						if ($(element).parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
						} else {
							$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
						}
					}
				<?php } ?>
				<?php if ($highlight_error) { ?>
					for (i in json['error']) {
						var element = $('#input-shipping-' + i.replace('_', '-'));

						$(element).css('border', '1px solid #f00').css('background', '#F8ACAC');
					}
				<?php } ?>
			} else {
				validateShippingMethod();
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}
<?php } ?>

// Confirm payment
$(document).on('click', '#button-payment-method', function() {
	$('#button-payment-method').prop('disabled', true);
	$('#button-payment-method').button('loading');
	
	$('#button-payment-method').after('<i class="fa fa-spinner fa-spin"></i>');
	
	validatePaymentAddress();
});
<?php } ?> // Close if logged php

// Payment Method
function reloadPaymentMethod() {
	$.ajax({
		url: 'index.php?route=quickenquiry/payment_method',
		type: 'post',
		data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select, #payment-address textarea, #payment-method input[type=\'text\'], #payment-method input[type=\'checkbox\']:checked, #payment-method input[type=\'radio\']:checked, #payment-method input[type=\'hidden\'], #payment-method select, #payment-method textarea'),
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			moduleLoad($('#payment-method'), <?= $loading_display; ?>);
		},
		success: function(html) {
			moduleLoaded($('#payment-method'), <?= $loading_display; ?>);
			
			$('#payment-method .quickcheckout-content').html(html);
			
			<?php if ($load_screen) { ?>
			$.unblockUI();
			<?php } ?>
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

function reloadPaymentMethodById(address_id) {
	$.ajax({
		url: 'index.php?route=quickenquiry/payment_method&address_id=' + address_id,
		type: 'post',
		data: $('#payment-method input[type=\'checkbox\']:checked, #payment-method input[type=\'radio\']:checked, #payment-method input[type=\'hidden\'], #payment-method select, #payment-method textarea'),
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			moduleLoad($('#payment-method'), <?= $loading_display; ?>);
		},
		success: function(html) {
			moduleLoaded($('#payment-method'), <?= $loading_display; ?>);
			
			$('#payment-method .quickcheckout-content').html(html);
			
			<?php if ($load_screen) { ?>
			$.unblockUI();
			<?php } ?>
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

// Validate Payment Method
function validatePaymentMethod() {
	$.ajax({
		url: 'index.php?route=quickenquiry/payment_method/validate',
		type: 'post',
		data: $('#payment-method select, #payment-method input[type=\'radio\']:checked, #payment-method input[type=\'checkbox\']:checked, #payment-method textarea'),
		dataType: 'json',
		cache: false,
		success: function(json) {
			$('.alert, .text-danger').remove();

			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				$('#button-payment-method').prop('disabled', false);
				$('#button-payment-method').button('reset');
				$('#terms input[type=\'checkbox\']').prop('checked', false);
				
				$('.fa-spinner').remove();
				
				$('html, body').animate({ scrollTop: 0 }, 'slow');
				
				if (json['error']['warning']) {
					$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
					
					$('.alert-danger').fadeIn('slow');
				}
			} else {
				validateTerms();
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

// Shipping Method
<?php if ($shipping_required) { ?>
	function reloadShippingMethod(type) {
		if (type == 'payment') {
			var post_data = $('#payment-address input[type=\'text\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select, #payment-address textarea, #shipping-method input[type=\'text\'], #shipping-method input[type=\'checkbox\']:checked, #shipping-method input[type=\'radio\']:checked, #shipping-method input[type=\'hidden\'], #shipping-method select, #shipping-method textarea');
		} else {
			var post_data = $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address input[type=\'hidden\'], #shipping-address select, #shipping-address textarea, #shipping-method input[type=\'text\'], #shipping-method input[type=\'checkbox\']:checked, #shipping-method input[type=\'radio\']:checked, #shipping-method input[type=\'hidden\'], #shipping-method select, #shipping-method textarea');
		}
		
		$.ajax({
			url: 'index.php?route=quickenquiry/shipping_method',
			type: 'post',
			data: post_data,
			dataType: 'html',
			cache: false,
			beforeSend: function() {
				moduleLoad($('#shipping-method'), <?= $loading_display; ?>);
			},
			success: function(html) {
				moduleLoaded($('#shipping-method'), <?= $loading_display; ?>);
				
				$('#shipping-method .quickcheckout-content').html(html);
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	}
	
	function reloadShippingMethodById(address_id) {
		$.ajax({
			url: 'index.php?route=quickenquiry/shipping_method&address_id=' + address_id,
			type: 'post',
			data: $('#shipping-method input[type=\'text\'], #shipping-method input[type=\'checkbox\']:checked, #shipping-method input[type=\'radio\']:checked, #shipping-method input[type=\'hidden\'], #shipping-method select, #shipping-method textarea'),
			dataType: 'html',
			cache: false,
			beforeSend: function() {
				moduleLoad($('#shipping-method'), <?= $loading_display; ?>);
			},
			success: function(html) {
				moduleLoaded($('#shipping-method'), <?= $loading_display; ?>);
				
				$('#shipping-method .quickcheckout-content').html(html);
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	}

	// Validate Shipping Method
	function validateShippingMethod() {
		$.ajax({
			url: 'index.php?route=quickenquiry/shipping_method/validate',
			type: 'post',
			data: $('#shipping-method select, #shipping-method input[type=\'radio\']:checked, #shipping-method textarea, #shipping-method input[type=\'text\']'),
			dataType: 'json',
			cache: false,
			success: function(json) {
				$('.alert, .text-danger').remove();

				if (json['redirect']) {
					location = json['redirect'];
				} else if (json['error']) {
					$('#button-payment-method').prop('disabled', false);
					$('#button-payment-method').button('reset');
					$('#terms input[type=\'checkbox\']').prop('checked', false);
					
					$('.fa-spinner').remove();
					
					$('html, body').animate({ scrollTop: 0 }, 'slow');
				
					if (json['error']['warning']) {
						$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
					
						$('.alert-danger').fadeIn('slow');
					}
				} else {
					if(typeof validateTimeslot == 'function'){
						validateTimeslot();
					}
					else{
						validatePaymentMethod();
					}
				}
			},
			<?php if ($debug) { ?>
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
			<?php } ?>
		});
	}
<?php } ?>

// Validate confirm button
function validateTerms() {
	$.ajax({
		url: 'index.php?route=quickenquiry/terms/validate',
		type: 'post',
		data: $('#terms input[type=\'checkbox\']:checked'),
		dataType: 'json',
		cache: false,
		success: function(json) {
			if (json['redirect']) {
				location = json['redirect'];
			}
		
			if (json['error']) {
				$('#button-payment-method').prop('disabled', false);
				$('#button-payment-method').button('reset');
				$('#terms input[type=\'checkbox\']').prop('checked', false);
				
				$('.fa-spinner').remove();
				
				$('html, body').animate({ scrollTop: 0 }, 'slow');
				
				if (json['error']['warning']) {
					$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');
					
					$('.alert-danger').fadeIn('slow');
				}
			} else {
				loadConfirm();
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

// Load confirm
function loadConfirm() {
	$.ajax({
		url: 'index.php?route=quickenquiry/confirm',
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			<?php if ($confirmation_page) { ?>
				$('html, body').animate({ scrollTop: 0 }, 'slow');
			
				<?php if ($slide_effect) { ?>
				$('#quickcheckoutconfirm').slideUp('slow');
				<?php } else { ?>
				$('#quickcheckoutconfirm').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></div>');
				<?php } ?>
			
				<?php if ($load_screen) { ?>
				$.blockUI({
					message: '<h1 style="color:#ffffff;"><?= $text_please_wait; ?></h2>',
					css: {
						border: 'none',
						padding: '15px',
						backgroundColor: '#000000',
						'-webkit-border-radius': '10px',
						'-moz-border-radius': '10px',
						'-khtml-border-radius': '10px',
						'border-radius': '10px',
						opacity: .8,
						color: '#ffffff'
					}
				});
				<?php } ?>
			<?php } ?>
		},
		success: function(html) {
			<?php if ($confirmation_page) { ?>
				<?php if ($load_screen) { ?>
				$.unblockUI();
				<?php } ?>
				
				$('#quickcheckoutconfirm').hide().html(html);
				
				<?php if (!$auto_submit) { ?>
					<?php if ($slide_effect) { ?>
					$('#quickcheckoutconfirm').slideDown('slow');
					<?php } else { ?>
					$('#quickcheckoutconfirm').show();
					<?php } ?>
				<?php } else { ?>
				$('#quickcheckoutconfirm').after('<div class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></div>');
				<?php } ?>
			<?php } else { ?>
				$('#terms .terms').hide();
				$('#payment').html(html).slideDown('fast');
				
				<?php if ($auto_submit) { ?>
				$('#payment').hide().after('<div class="text-center"><i class="fa fa-spinner fa-spin fa-5x"></i></div>');
				<?php } ?>
				
				$('html, body').animate({ scrollTop: $('#terms').offset().top }, 'slow');
				
				disableCheckout();
			<?php } ?>
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

// Load cart
<?php if ($cart_module) { ?>
function loadCart() {
	$.ajax({
		url: 'index.php?route=quickenquiry/cart',
		dataType: 'html',
		cache: false,
		beforeSend: function() {
			$('.tooltip').remove();
			
			moduleLoad($('#cart1'), <?= $loading_display; ?>);
		},
		success: function(html) {
			moduleLoaded($('#cart1'), <?= $loading_display; ?>);
			
			$('#cart1 .quickcheckout-content').html(html);
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
}

	<?php if (!$shipping_required) { ?>
	$(document).ready(function(){
		loadCart();
	});
	<?php } ?>
<?php } ?>

<?php if ($voucher_module || $coupon_module || $reward_module) { ?>
// Validate Coupon
$(document).on('click', '#button-coupon', function() {
	$.ajax({
		url: 'index.php?route=quickenquiry/voucher/validateCoupon',
		type: 'post',
		data: $('#coupon-content :input'),
		dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#button-coupon').prop('disabled', true);
			$('#button-coupon').after('<i class="fa fa-spinner fa-spin"></i>');
		},
		complete: function() {
			$('#button-coupon').prop('disabled', false);
			$('#coupon-content .fa-spinner').remove();
		},
		success: function(json) {
			$('.alert').remove();
			
			$('html, body').animate({ scrollTop: 0 }, 'slow');

			if (json['success']) {
				$('#success-messages').prepend('<div class="alert alert-success" style="display:none;"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
				
				$('.alert-success').fadeIn('slow');
			} else if (json['error']) {
				$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');

				$('.alert-danger').fadeIn('slow');
			}

			<?php if (!$logged) { ?>
				if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
					reloadPaymentMethod();
					
					<?php if ($shipping_required) { ?>
					reloadShippingMethod('payment');
					<?php } ?>
				} else {
					reloadPaymentMethod();
					
					<?php if ($shipping_required) { ?>
					reloadShippingMethod('shipping');
					<?php } ?>
				}
			<?php } else { ?>
				if ($('#payment-address input[name=\'payment_address\']:checked').val() == 'new') {
					reloadPaymentMethod();
				} else {
					reloadPaymentMethodById($('#payment-address select[name=\'address_id\']').val());
				}
				
				<?php if ($shipping_required) { ?>
				if ($('#shipping-address input[name=\'shipping_address\']:checked').val() == 'new') {
					reloadShippingMethod('shipping');
				} else {
					reloadShippingMethodById($('#shipping-address select[name=\'address_id\']').val());
				}
				<?php } ?>
			<?php } ?>
			
			<?php if (!$shipping_required) { ?>
			loadCart();
			<?php } ?>
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
});

$(document).on('click', '#button-voucher', function() {
	$.ajax({
		url: 'index.php?route=quickenquiry/voucher/validateVoucher',
		type: 'post',
		data: $('#voucher-content :input'),
		dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#button-voucher').prop('disabled', true);
			$('#button-voucher').after('<i class="fa fa-spinner fa-spin"></i>');
		},
		complete: function() {
			$('#button-voucher').prop('disabled', false);
			$('#voucher-content .fa-spinner').remove();
		},
		success: function(json) {
			$('.alert').remove();
			
			$('html, body').animate({ scrollTop: 0 }, 'slow');

			if (json['success']) {
				$('#success-messages').prepend('<div class="alert alert-success" style="display:none;"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
				
				$('.alert-success').fadeIn('slow');
			} else if (json['error']) {
				$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');

				$('.alert-danger').fadeIn('slow');
			}

			<?php if (!$logged) { ?>
				if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
					reloadPaymentMethod();
					
					<?php if ($shipping_required) { ?>
					reloadShippingMethod('payment');
					<?php } ?>
				} else {
					reloadPaymentMethod();
					
					<?php if ($shipping_required) { ?>
					reloadShippingMethod('shipping');
					<?php } ?>
				}
			<?php } else { ?>
				if ($('#payment-address input[name=\'payment_address\']:checked').val() == 'new') {
					reloadPaymentMethod();
				} else {
					reloadPaymentMethodById($('#payment-address select[name=\'address_id\']').val());
				}
				
				<?php if ($shipping_required) { ?>
				if ($('#shipping-address input[name=\'shipping_address\']:checked').val() == 'new') {
					reloadShippingMethod('shipping');
				} else {
					reloadShippingMethodById($('#shipping-address select[name=\'address_id\']').val());
				}
				<?php } ?>
			<?php } ?>
			
			<?php if (!$shipping_required) { ?>
			loadCart();
			<?php } ?>
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
});

$(document).on('click', '#button-reward', function() {
	$.ajax({
		url: 'index.php?route=quickenquiry/voucher/validateReward',
		type: 'post',
		data: $('#reward-content :input'),
		dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#button-reward').prop('disabled', true);
			$('#button-reward').after('<i class="fa fa-spinner fa-spin"></i>');
		},
		complete: function() {
			$('#button-reward').prop('disabled', false);
			$('#reward-content .fa-spinner').remove();
		},
		success: function(json) {
			$('.alert').remove();
			
			$('html, body').animate({ scrollTop: 0 }, 'slow');

			if (json['success']) {
				$('#success-messages').prepend('<div class="alert alert-success" style="display:none;"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
				
				$('.alert-success').fadeIn('slow');
			} else if (json['error']) {
				$('#warning-messages').prepend('<div class="alert alert-danger" style="display: none;"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '</div>');

				$('.alert-danger').fadeIn('slow');
			}

			<?php if (!$logged) { ?>
				if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
					reloadPaymentMethod();
					
					<?php if ($shipping_required) { ?>
					reloadShippingMethod('payment');
					<?php } ?>
				} else {
					reloadPaymentMethod();
					
					<?php if ($shipping_required) { ?>
					reloadShippingMethod('shipping');
					<?php } ?>
				}
			<?php } else { ?>
				if ($('#payment-address input[name=\'payment_address\']:checked').val() == 'new') {
					reloadPaymentMethod();
				} else {
					reloadPaymentMethodById($('#payment-address select[name=\'address_id\']').val());
				}
				
				<?php if ($shipping_required) { ?>
				if ($('#shipping-address input[name=\'shipping_address\']:checked').val() == 'new') {
					reloadShippingMethod('shipping');
				} else {
					reloadShippingMethodById($('#shipping-address select[name=\'address_id\']').val());
				}
				<?php } ?>
			<?php } ?>
			
			<?php if (!$shipping_required) { ?>
			loadCart();
			<?php } ?>
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
});
<?php } ?>

<?php if ($shipping_required) { ?>
$(document).on('focusout', 'input[name=\'postcode\']', function() {
	<?php if (!$logged) { ?>
	if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
		reloadShippingMethod('payment');
	} else {
		reloadShippingMethod('shipping');
	}
	<?php } else { ?>
	if ($('#shipping-address input[name=\'shipping_address\']:checked').val() == 'new') {
		reloadShippingMethod('shipping');
	} else {
		reloadShippingMethodById($('#shipping-address select[name=\'address_id\']').val());
	}
	<?php } ?>
});
<?php } ?>

<?php if ($highlight_error) { ?>
	$(document).on('keydown', 'input', function() {
		$(this).css('background', '').css('border', '');
		
		$(this).siblings('.text-danger').remove();
	});
	$(document).on('change', 'select', function() {
		$(this).css('background', '').css('border', '');
		
		$(this).siblings('.text-danger').remove();
	});
<?php } ?>

<?php if ($edit_cart) { ?>
$(document).on('click', '.button-update', function() {
	$.ajax({
		url: 'index.php?route=quickenquiry/cart/update',
		type: 'post',
		data: $('#cart1 :input'),
		dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#cart1 .button-update').prop('disabled', true);
		},
		success: function(json) {
			if (json['redirect']) {
				location = json['redirect'];
			} else {
				<?php if (!$logged) { ?>
					if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
						reloadPaymentMethod();
						
						<?php if ($shipping_required) { ?>
						reloadShippingMethod('payment');
						<?php } ?>
					} else {
						reloadPaymentMethod();
						
						<?php if ($shipping_required) { ?>
						reloadShippingMethod('shipping');
						<?php } ?>
					}
				<?php } else { ?>
					if ($('#payment-address input[name=\'payment_address\']:checked').val() == 'new') {
						reloadPaymentMethod();
					} else {
						reloadPaymentMethodById($('#payment-address select[name=\'address_id\']').val());
					}
					
					<?php if ($shipping_required) { ?>
					if ($('#shipping-address input[name=\'shipping_address\']:checked').val() == 'new') {
						reloadShippingMethod('shipping');
					} else {
						reloadShippingMethodById($('#shipping-address select[name=\'address_id\']').val());
					}
					<?php } ?>
				<?php } ?>
				
				<?php if (!$shipping_required) { ?>
				loadCart();
				<?php } ?>
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});
});

$(document).on('click', '.button-remove', function() {
	var remove_id = $(this).attr('data-remove');

	$.ajax({
		url: 'index.php?route=quickenquiry/cart/update&remove=' + remove_id,
		type: 'get',
		dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#cart1 .button-remove').prop('disabled', true);
		},
		success: function(json) {
			if (json['redirect']) {
				location = json['redirect'];
			} else {
				<?php if (!$logged) { ?>
					if ($('#payment-address input[name=\'shipping_address\']:checked').val()) {
						reloadPaymentMethod();
						
						<?php if ($shipping_required) { ?>
						reloadShippingMethod('payment');
						<?php } ?>
					} else {
						reloadPaymentMethod();
						
						<?php if ($shipping_required) { ?>
						reloadShippingMethod('shipping');
						<?php } ?>
					}
				<?php } else { ?>
					if ($('#payment-address input[name=\'payment_address\']:checked').val() == 'new') {
						reloadPaymentMethod();
					} else {
						reloadPaymentMethodById($('#payment-address select[name=\'address_id\']').val());
					}
					
					<?php if ($shipping_required) { ?>
					if ($('#shipping-address input[name=\'shipping_address\']:checked').val() == 'new') {
						reloadShippingMethod('shipping');
					} else {
						reloadShippingMethodById($('#shipping-address select[name=\'address_id\']').val());
					}
					<?php } ?>
				<?php } ?>
				
				<?php if (!$shipping_required) { ?>
				loadCart();
				<?php } ?>
			}
		},
		<?php if ($debug) { ?>
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
		<?php } ?>
	});

	return false;
});
<?php } ?>

$('.date').datetimepicker({
	format: 'YYYY-MM-DD'
});

$('.time').datetimepicker({
	format: 'HH:mm'
});

$('.datetime').datetimepicker();
//--></script>
<?= $footer; ?>