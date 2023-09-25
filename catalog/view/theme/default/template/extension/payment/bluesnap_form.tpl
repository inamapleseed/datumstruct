<?php if (isset($bluesnap_config_error) && $bluesnap_config_error == 1) {?> 
	<script>alert('<?php echo $bluesnap_config_error_message;?>');</script>
<?php } else {?>

<style>
/* Hosted Payment Fields styles*/
.hosted-field-focus { 
  border: 1px solid #66afe9;
  box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(102,175,233,.6);
}

.hosted-field-invalid {
  border: 1px solid #e93143;
  box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgba(233,49,67, 0.8);
}

.hosted-field-valid {
  border: 1px solid #14ba57 ;
}
</style>
  <form id="checkout-form"> 
    
      <div class="row">
       <div class="form-group col-md-12">
          <label for="cardholder-firstname"><?php echo $entry_firstname;?></label>
          <input name="cardholder-firstname" required type="text" class="form-control" id="cardholder-firstname" placeholder="<?php echo $placeholder_firstname;?>">
	  <span id="cardholder-firsname-help" class="helper-text"></span>
       </div>
       <div class="form-group col-md-12">
          <label for="cardholder-lastname"><?php echo $entry_lastname;?></label>
          <input name="cardholder-lastname" required type="text" class="form-control" id="cardholder-lastname" placeholder="<?php echo $placeholder_lastname;?>">
	  <span id="cardholder-lastname-help" class="helper-text"></span>
       </div>
       <div class="form-group col-md-12">
          <label for="card-number"><?php echo $entry_card_number;?></label>
          <div class="input-group">
            <div class="form-control" id="card-number" data-bluesnap="ccn"></div>
            <div id="card-logo" class="input-group-addon"><img src="https://files.readme.io/d1a25b4-generic-card.png" height="20px"></div>
         </div>
         <span class="helper-text" id="card-help"></span>
       </div>
       <div class="form-group col-xs-7">
          <label for="exp-date"><?php echo $entry_expiry_date;?></label>
          <div class="form-control" id="exp-date" data-bluesnap="exp"></div>
          <span class="helper-text"></span>
       </div>
       <div class="form-group col-xs-5">
         <label for="cvv">Security Code</label>
         <div class="form-control" id="cvv" data-bluesnap="cvv"></div>
         <span class="helper-text"></span>
      </div>
    </div>  
    <button class="btn btn-success btn-lg col-xs-6 col-xs-offset-3" id="submit-button-bluesnap" disabled="disabled"><?php echo $button_pay_now;?></button>
  </form>
<script type="text/javascript">
	var ccnOk = false;
	var cvvOk = false;
	var expOk = false;
  var bsObj = {
                hostedPaymentFields: {
                        ccn: "ccn", // name cannot contain spaces or special characters
                        cvv: "cvv", // name cannot contain spaces or special characters
                        exp: "exp"  // name cannot contain spaces or special characters
                },
		onFieldEventHandler: {
                        onFocus: function(tagId) {
				console.log("onFocus");
                                // Handle focus
                                if (tagId == "ccn") {
                                        $( "#card-number" ).addClass( "hosted-field-focus" );
                                } else if (tagId == "exp") {
                                        $( "#exp-date" ).addClass( "hosted-field-focus" ); 
                                } else if (tagId == "cvv") {
                                        $( "#cvv" ).addClass( "hosted-field-focus" );
                                }
				checkForm();
                        },
			onBlur: function(tagId) {	
				console.log("onBlur");
                                // Handle blur
                                if (tagId == "ccn") {
                                        $( "#card-number" ).removeClass( "hosted-field-focus" );
                                } else if (tagId == "exp") {
                                        $( "#exp-date" ).removeClass( "hosted-field-focus" ); 
                                } else if (tagId == "cvv") {
                                        $( "#cvv" ).removeClass( "hosted-field-focus" );
                                }
				checkForm();
			},
        	        onError: function(tagId, errorCode) {
				console.log("onError callback");
                                if (tagId == "ccn" && errorCode == "001") {
                                        $( "#card-number" ).removeClass( "hosted-field-focus hosted-field-valid" ).addClass( "hosted-field-invalid" );
                                        $( "#card-help" ).text('<?php echo $error_card_number;?>');
					ccnOk = false;
                                } else if (tagId == "exp" && errorCode == "003") {
                                        $( "#exp-date" ).removeClass( "hosted-field-focus hosted-field-valid" ).addClass( "hosted-field-invalid" ).next('span').text('<?php echo $error_expiry_date;?>'); 
					expOk = false;
                                } else if (tagId == "cvv" && errorCode == "002" ) {
                                        $( "#cvv" ).removeClass( "hosted-field-focus hosted-field-valid" ).addClass( "hosted-field-invalid" ).next('span').text('<?php echo $error_security_code;?>');
					cvvOk = false;
                                }
				$('#submit-button-bluesnap').button('reset');
				checkForm();
                	},
	                onEmpty: function(tagId, errorCode) {
				console.log("onEmpty"); 
                                // Handle a change in validation  
                                if (tagId == "ccn" && errorCode == "001") {
                                        $( "#card-number" ).removeClass( "hosted-field-focus hosted-field-valid hosted-field-invalid" );
                                        $( "#card-help" ).text('');
                                        $('#card-logo img').attr("src", "https://files.readme.io/d1a25b4-generic-card.png");
					ccnOk = false;
                                } else if (tagId == "exp" && errorCode == "003") {
                                        $( "#exp-date" ).removeClass( "hosted-field-focus hosted-field-valid hosted-field-invalid" ).next('span').text('');
					expOk = false;	
                                } else if (tagId == "cvv" && errorCode == "002" ) {
                                        $( "#cvv" ).removeClass( "hosted-field-focus hosted-field-valid hosted-field-invalid" ).next('span').text('');
					cvvOk = false;
                                }
				$('#submit-button-bluesnap').button('reset');
				checkForm();
                	},
	                onType: function(tagId, cardType) {
				console.log("onType callback");
                                // get card type from cardType and display the img accordingly
                                if (cardType == "AmericanExpress") { $('#card-logo img').attr("src", "https://files.readme.io/97e7acc-Amex.png");
                                } else if (cardType == "CarteBleue") { $('#card-logo img').attr("src", "https://files.readme.io/5da1081-cb.png");
                                } else if (cardType == "DinersClub") { $('#card-logo img').attr("src", "https://files.readme.io/8c73810-Diners_Club.png");
                                } else if (cardType == "Discover") { $('#card-logo img').attr("src", "https://files.readme.io/caea86d-Discover.png");
                                } else if (cardType == "JCB") { $('#card-logo img').attr("src", "https://files.readme.io/e076aed-JCB.png");
                                } else if (cardType == "MaestroUK") { $('#card-logo img').attr("src", "https://files.readme.io/daeabbd-Maestro.png");
                                } else if (cardType == "MasterCard") { $('#card-logo img').attr("src", "https://files.readme.io/5b7b3de-Mastercard.png");
                                } else if (cardType == "Solo") { $('#card-logo img').attr("src", "https://sandbox.bluesnap.com/services/hosted-payment-fields/cc-types/solo.png");
                                } else if (cardType == "Visa") { $('#card-logo img').attr("src", "https://files.readme.io/9018c4f-Visa.png");
                                }
        	        },
                	onValid: function(tagId) {
				console.log("onValid callback");
                                // Handle a change in validation
                                if (tagId == "ccn") {
                                        $( "#card-number" ).removeClass( "hosted-field-focus hosted-field-invalid" ).addClass( "hosted-field-valid" )
                                        $( "#card-help" ).text('');
					ccnOk = true;
                                } else if (tagId == "exp") {
                                        $( "#exp-date" ).removeClass( "hosted-field-focus hosted-field-invalid" ).addClass( "hosted-field-valid" ).next('span').text(''); 
					expOk = true;
                                } else if (tagId == "cvv") {
                                        $( "#cvv" ).removeClass( "hosted-field-focus hosted-field-invalid" ).addClass( "hosted-field-valid" ).next('span').text('');
					cvvOk = true;
                                }
				checkForm();
                	}	
		}
  	};
	bluesnap.hostedPaymentFieldsCreation ("<?php echo $bluesnap_hosted_payments_field['TOKEN'];?>", bsObj);//insert your Hosted Payment Fields token
	
	$("#cardholder-firstname").on("change", function() {
		if ($(this).val().length == 0) {
			$(this).removeClass( "hosted-field-focus hosted-field-valid" ).addClass( "hosted-field-invalid" ).next('span').text('<?php echo $error_firstname;?>');
				
		} else {
			$(this).removeClass( "hosted-field-focus hosted-field-invalid" ).addClass( "hosted-field-valid" ).next('span').text('');
		}
		checkForm();
	}).on("blur", function() { 
		if ($(this).val().length == 0) {
                        $(this).removeClass( "hosted-field-focus hosted-field-valid" ).addClass( "hosted-field-invalid" ).next('span').text('<?php echo $error_firstname;?>');
                                
                } else {
                        $(this).removeClass( "hosted-field-focus hosted-field-invalid" ).addClass( "hosted-field-valid" ).next('span').text('');
                }
		checkForm(); 
	});

	$("#cardholder-lastname").on("change", function() {
                if ($(this).val().length == 0) {
                        $(this).removeClass( "hosted-field-focus hosted-field-valid" ).addClass( "hosted-field-invalid" ).next('span').text('<?php echo $error_lastname;?>');
                                
                } else {
                        $(this).removeClass( "hosted-field-focus hosted-field-invalid" ).addClass( "hosted-field-valid" ).next('span').text('');
                }
                checkForm();
        }).on("blur", function() { 
                if ($(this).val().length == 0) {
                        $(this).removeClass( "hosted-field-focus hosted-field-valid" ).addClass( "hosted-field-invalid" ).next('span').text('<?php echo $error_lastname;?>');
                                
                } else {
                        $(this).removeClass( "hosted-field-focus hosted-field-invalid" ).addClass( "hosted-field-valid" ).next('span').text('');
                }
                checkForm(); 
        });

	function checkForm() {
		console.log("Checking form");
		var firstNameOk = $("#cardholder-firstname").val().length > 0;
		console.log("firstNameOk=" + firstNameOk);
		var lastNameOk = $("#cardholder-lastname").val().length > 0;
		if (cvvOk && ccnOk && expOk && firstNameOk && lastNameOk) {
			console.log("Form is valid");		
			$('#submit-button-bluesnap').removeAttr("disabled");
		} else {
			console.log("form is not valid [firstNameOk=" + firstNameOk + "] [lastNameOk=" + lastNameOk +"] [cvvOk=" + cvvOk +"] [ccnOk=" + ccnOk + "] [expOk=" + expOk +"]");
			$('#submit-button-bluesnap').attr("disabled","disabled");
		}
	}

	$("#submit-button-bluesnap").on('click', function() {
		$('#submit-button-bluesnap').button('loading');
                bluesnap.submitCredentials( function(cardData) {
			console.log('the card type is ' + cardData.ccType + ' and last 4 digits are ' + cardData.last4Digits + ' and exp is ' + cardData.exp + ' after that I can call final submit');
			var cardholder_firstname = $("#cardholder-firstname").val();
			var cardholder_lastname	= $("#cardholder-lastname").val();
			console.log(cardholder_firstname + " " + cardholder_lastname);
			$.ajax({ 
                        	type: 'post',
                                url: 'index.php?route=extension/payment/bluesnap/confirm',
                                cache: false,
                                async: false,
                                dataType: 'json',
                                data : { cardholderFirstName: cardholder_firstname, cardholderLastName: cardholder_lastname, ccType : cardData.ccType, last4Digits : cardData.last4Digits, expiryDate : cardData.exp },                                
				beforeSend: function() {
                                        $('#submit-button-bluesnap').button('loading');
                                },
				complete: function() {
					 $('#submit-button-bluesnap').button('reset');

				}, 
                                success: function(data) {
					if (data.result_code != 0) {
						alert(data.result_msg);
					}	
					if (data.redirect_url) {
						location.href = data.redirect_url;					
					} else {
						$('#submit-button-bluesnap').button('reset');
 					}
                                }              
                        });
                });
		return false;
       	});       
</script>

<iframe width='1' height='1' frameborder='0' scrolling='no' src='<?php echo $bluesnap_url;?>/servlet/logo.htm?s=<?php echo $bluesnap_fraud_session_id;?>'>
     <img width='1' height='1' src='<?php echo $bluesnap_url;?>/servlet/logo.gif?s=<?php echo $bluesnap_fraud_session_id;?>'>
</iframe>

<?php }?>
