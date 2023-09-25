<div id="product">
  <?php if ($options) { ?>
    <hr>
    <?php foreach ($options as $option) { ?>
        <?php if ($option['type'] == 'select') { ?>
              <div class="this form-group<?= ($option['required'] ? ' required' : ''); ?> option">
                <label class="control-label" for="input-option<?= $option['product_option_id']; ?>"><?= $option['name']; ?></label>
                <select name="option[<?= $option['product_option_id']; ?>]" id="input-option<?= $option['product_option_id']; ?>" class="form-control">
                  <option value=""><?= $text_select; ?></option>
                  <?php foreach ($option['product_option_value'] as $option_value) { ?>

                <?php if ($option_value['qty'] <= 0): ?>

                  <option disabled value="<?= $option_value['product_option_value_id']; ?>">
                    <?= $option_value['name']; ?> (Out of Stock)
                    <?php if ($option_value['price']) { ?>
                      (<?= $option_value['price_prefix']; ?><?= $option_value['price']; ?>)
                    <?php } ?>
                  </option>
                    <?php else: ?>
                  <option value="<?= $option_value['product_option_value_id']; ?>">
                    <?= $option_value['name']; ?>
                  
                    <?php if ($option_value['price']) { ?>
                      (<?= $option_value['price_prefix']; ?><?= $option_value['price']; ?>)
                    <?php } ?>
                  </option>
                <?php endif ?>
                
                  <?php } ?>
                </select>
              </div>
        <?php } ?>
        <?php if ($option['type'] == 'radio') { ?>
            <div class="form-group<?= ($option['required'] ? ' required' : ''); ?> option">
                <label class="control-label"><?= $option['name']; ?></label>
                <div id="input-option<?= $option['product_option_id']; ?>">
                  <?php foreach ($option['product_option_value'] as $i => $option_value) { ?>
                    <div class="radio">

                        <label>
                          <?php if($option_value['qty'] > 0) : ?>
                            <input type="radio" name="option[<?= $option['product_option_id']; ?>]" value="<?= $option_value['product_option_value_id']; ?>" />
                            <?php if ($option_value['image']) { ?>
                            <img id="i<?=$i;?>" class="opt" src="<?= $option_value['image']; ?>" alt="<?= $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> 
                            <?php } ?>                    
                            <?php if ($option_value['price']) { ?>
                            (<?= $option_value['price_prefix']; ?><?= $option_value['price']; ?>)
                            <?php } ?>
                          <?php else : ?>
                            <?php if ($option_value['image']) { ?>
                            <div class="img-con">
                                <img id="i<?=$i;?>" class="opt no-qty" src="<?= $option_value['image']; ?>" alt="<?= $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" /> 
                                <img src="image/catalog/ds/slash.png" alt="X" class="slash" title="Out of Stock">
                            </div>
                            <?php } ?>
                          <?php endif; ?>

                        </label>

                    </div>
                  <?php } ?>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
  <?php } ?>

    <div class="form-group">

      <?php if (!$enquiry): ?>  
        <div class="input-group">
          <span class="input-group-addon"><?= $entry_qty; ?></span>
          <span class="input-group-btn"> 
            <button type="button" class="btn btn-default btn-number" data-type="minus" data-field="qty-<?= $product_id; ?>" onclick="descrement($(this).parent().parent())")>
              <span class="glyphicon glyphicon-minus"></span> 
            </button>
          </span>
          <input type="text" name="quantity" class="form-control input-number integer text-center" id="input-quantity" value="<?= $minimum; ?>" >
          <span class="input-group-btn">
            <button type="button" class="btn btn-default btn-number" data-type="plus" data-field="qty-<?= $product_id; ?>" onclick="increment($(this).parent().parent())">
              <span class="glyphicon glyphicon-plus"></span>
            </button>
          </span>
        </div>
      <?php endif ?>
  
      <input type="hidden" name="product_id" value="<?= $product_id; ?>" />
      <br />

      <?php if($download){ ?>
          <a href="<?= $download; ?>" target="_blank" class="" ><?= $button_download; ?></a>
        <?php } ?>

<?php if($share_html){ ?>
  <div class="input-group-flex ">
    <span><?= $text_share; ?></span>
    <div><?= $share_html; ?></div>
  </div>
<?php } ?>      

  <div class="cute-buttons">
      <?php if(!$enquiry){ ?>

        <?php if($upc == 1){ ?>

            <button type="button" id="button-cart1" data-loading-text="<?= $text_loading; ?>" class="btn custom_button"><?= $button_cart; ?></button>
        <?php } else { ?>
            <button type="button" id="button-cart" data-loading-text="<?= $text_loading; ?>" class="btn custom_button"><?= $button_cart; ?></button>
        <?php } ?>
        <button 
          type="button" 
          onclick="wishlist.add('<?= $product_id; ?>');" class="btn btn-default wlbutton">
          Add to Wishlist
        </button>

      <?php }else{ ?>
        <button type="button" id="button-enquiry" data-loading-text="<?= $text_loading; ?>" class="btn custom_button" onclick="enquiry.add('<?= $product_id; ?>');"><?= $button_enquiry; ?></button>
      <?php } ?>

        <button class="btn-default wlbutton btn" onclick="goBack()">Back</button>
  </div>
    </div>
  </div>


  
<script type="text/javascript">
    $('.date').datetimepicker({
        pickTime: false
    });
    
    $('.datetime').datetimepicker({
        pickDate: true,
        pickTime: true
    });
    
    $('.time').datetimepicker({
        pickDate: false
    });
    
    $('button[id^=\'button-upload\']').on('click', function() {
        var node = this;
    
        $('#form-upload').remove();
    
        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');
    
        $('#form-upload input[name=\'file\']').trigger('click');
    
        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }
    
        timer = setInterval(function() {
            if ($('#form-upload input[name=\'file\']').val() != '') {
                clearInterval(timer);
    
                $.ajax({
                    url: 'index.php?route=tool/upload',
                    type: 'post',
                    dataType: 'json',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $(node).button('loading');
                    },
                    complete: function() {
                        $(node).button('reset');
                    },
                    success: function(json) {
                        $('.text-danger').remove();
    
                        if (json['error']) {
                            $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
                        }
    
                        if (json['success']) {
                            alert(json['success']);
    
                            $(node).parent().find('input').val(json['code']);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    });
    //--></script>

  <?php if(isset($update_price_status) && $update_price_status) { ?>
					
	<script type="text/javascript">
	
		$("#product input[type='checkbox']").click(function() {
			changePrice();
		});
		
		$("#product input[type='radio']").click(function() {
			changePrice();
		});
		
		$("#product select").change(function() {
			changePrice();
		});
		
		$("#input-quantity").blur(function() {
			changePrice();
		});

    $("#input-quantity").parent(".input-group").find(".btn-number").click(function() {
			changePrice();
		});
		
		function changePrice() {
			$.ajax({
				url: 'index.php?route=product/product/updatePrice&product_id=<?php echo $product_id; ?>',
				type: 'post',
				dataType: 'json',
				data: $('#product input[name=\'quantity\'], #product select, #product input[type=\'checkbox\']:checked, #product input[type=\'radio\']:checked'),
				beforeSend: function() {
					
				},
				complete: function() {
					
				},
				success: function(json) {
					$('.alert-success, .alert-danger').remove();
					
					if(json['new_price_found']) {
						$('.new-prices').html(json['total_price']);
						$('.product-tax').html(json['tax_price']);
					} else {
						$('.old-prices').html(json['total_price']);
						$('.product-tax').html(json['tax_price']);
					}
				}
			});
		}
	</script>
		
<?php } ?>

<script>
function goBack() {
  window.history.back();
}
</script>