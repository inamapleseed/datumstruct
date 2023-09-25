<?php if ($error_library) { ?>
	<div class="row">
		<div class="col-sm-12">
  			<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_library; ?></div>
        </div>
	</div>
<?php } else { ?>
<?php if ($testmode) { ?>
	<div class="row">
		<div class="col-sm-12">
  			<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $text_testmode; ?></div>
        </div>
	</div>
<?php } ?>
<div class="row">
	<div class="col-sm-12"> 
    	<div class="alert alert-info"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_help; ?></div>
    </div>
</div>
<div class="row">
	<div class="col-sm-12"> 
    	<div id="braintree_messages" class="alert alert-danger"></div>
    </div>
</div>
<div class="row">
	<div class="col-sm-12"> 
    	<div id="braintree_info_messages" class="alert alert-info"></div>
    </div>
</div>
<div class="row">
	<div class="col-sm-3"><img class="img-responsive" src="image/payment/Cards.jpg" alt="VISA / MasterCard / Discover accepted"/></div>
</div>
<div class="row">
	<div class="col-sm-3">
		<p><?php echo $text_credit_card; ?></p>
	</div>
</div>	
<div class="row">
	<div class="col-sm-12">
        <form id="payment-form" method="post" action="">
          <div id="braintree"></div>
          <div class="buttons">
            <div class="pull-right">
              <input type="submit" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
            </div>
          </div>
        </form>
	</div>
</div>
<script type="text/javascript" src="https://js.braintreegateway.com/v2/braintree.js"></script>
<script type="text/javascript">
var checkout;
var info_message = true;

$('#braintree_messages').hide();
$('#braintree_info_messages').hide();

wait_for_braintree();

function send_braintree_payment(nonce) {
	$.ajax({
		url: 'index.php?route=extension/payment/braintree_tlt/send',
		type: 'post',
		data: 'payment_method_nonce=' + nonce,
		dataType: 'json',
		cache: false,
		complete: function() {
			$('#braintree_info_messages').hide();
			$('#braintree_info_messages').html('');
		},				
		success: function(json) {
			if (json['error']) {
				$('#braintree_messages').show();
				$('#braintree_messages').html(json['error']);
				$('#payment-form').find('#button-confirm').prop('disabled', false);
				checkout.teardown(function () {
  					checkout = null;
  					braintree_setup();
				});
			}
			
			if (json['success']) {
				location = json['success'];
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
	  });
}

function wait_for_braintree() {
	if (window.braintree && window.braintree.setup) {
		braintree_setup();
	} else {
		if (info_message) {
			info_message = false;
			$('#braintree_info_messages').show();
			$('#braintree_info_messages').html('<i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_gateway_loading; ?>');
		}
		setTimeout(function() { wait_for_braintree() }, 50);
	}
}

function braintree_setup() {
	braintree.setup('<?php echo $clientToken; ?>', 'dropin', {
		container: 'braintree',
		onReady: function (integration) {
			checkout = integration;
			$('#braintree_info_messages').hide();
			$('#braintree_info_messages').html('');
		},
		onPaymentMethodReceived: function (payload) {
			$('#braintree_info_messages').show();
			$('#braintree_info_messages').html('<i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_wait; ?>');
			$('#payment-form').find('#button-confirm').prop('disabled', true);
			send_braintree_payment(payload.nonce);
		}
	});
}

jQuery(function($) {
  $('#payment-form').submit(function(e) {
	$('#braintree_messages').hide();
	$('#braintree_messages').html('');
  });
});
</script>
<?php } ?>