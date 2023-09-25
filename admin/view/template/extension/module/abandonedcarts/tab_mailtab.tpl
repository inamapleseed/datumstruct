<?php 
    $mailtemplate_name = $moduleName.'[MailTemplate]['.$mailtemplate['id'].']';
    $mailtemplate_data = (isset($moduleData['MailTemplate'][$mailtemplate['id']])) ? $moduleData['MailTemplate'][$mailtemplate['id']] : array();
?>
<div id="mailtemplate_<?php echo $mailtemplate['id']; ?>" class="tab-pane templates" style="width:99%;overflow:hidden;">
	<div class="row removable">
	  <div class="col-md-3">
        <h5 class="option_title"><?php echo $text_template; ?> <?php echo $mailtemplate['id']; ?> <?php echo $text_status_small; ?></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_template_help; ?></span>
      </div>
      <div class="col-md-3">
        <select id="Checker" name="<?php echo $mailtemplate_name; ?>[Enabled]" class="form-control">
              <option value="yes" <?php echo (!empty($mailtemplate_data['Enabled']) && $mailtemplate_data['Enabled'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
              <option value="no"  <?php echo (empty($mailtemplate_data['Enabled']) || $mailtemplate_data['Enabled']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
        </select>
      </div>
    </div>
    <div class="row removable">
      <br />
      <div class="col-md-3">
        <h5 class="option_title"><?php echo $text_template; ?> <?php echo $mailtemplate['id']; ?> <?php echo $text_name_small; ?></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_name_help; ?></span>
      </div>
      <div class="col-md-3">
		<input type="text" class="form-control" name="<?php echo $mailtemplate_name; ?>[Name]" value="<?php if (isset($mailtemplate_data['Name'])) echo $mailtemplate_data['Name']; else echo $text_template.' '.$mailtemplate['id'] ; ?>" />
      </div>
    </div>
    <div class="row removable">
      <br />
      <div class="col-md-3">
        <h5 class="option_title"><?php echo $text_message_delay; ?></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_message_delay_help; ?></span>
      </div>
      <div class="col-md-3">
      	<div class="input-group">
		 <input type="text" class="form-control" name="<?php echo $mailtemplate_name; ?>[Delay]" value="<?php if (isset($mailtemplate_data['Delay'])) echo $mailtemplate_data['Delay']; else echo '3'; ?>" />
         <span class="input-group-addon"><?php echo $text_days_small; ?></span>
        </div>
      </div>
    </div>
    <div class="row">
      <br />
      <div class="col-md-3">
        <h5 class="option_title"><?php echo $text_type_of_discount; ?></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_type_of_discount_help; ?></span>
      </div>
      <div class="col-md-3">
        <select name="<?php echo $mailtemplate_name; ?>[DiscountType]" class="discountTypeSelect form-control"> 
            <option value="P" <?php if(!empty($mailtemplate_data['DiscountType']) && $mailtemplate_data['DiscountType'] == "P") echo "selected"; ?>><?php echo $text_percentage; ?></option>
            <option value="F" <?php if(!empty($mailtemplate_data['DiscountType']) && $mailtemplate_data['DiscountType'] == "F") echo "selected"; ?>><?php echo $text_fixed_amount; ?></option>
            <option value="N" <?php if(empty($mailtemplate_data['DiscountType']) || $mailtemplate_data['DiscountType'] == "N") echo "selected"; ?>><?php echo $text_no_discount; ?></option>
        </select>
      </div>
    </div>
    <br />
    <div class="discountSettings">
        <div class="row">
          <div class="col-md-3">
            <h5 class="option_title"><?php echo $text_discount; ?></strong></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_discount_help; ?></span>
          </div>
          <div class="col-md-3">
            <div class="input-group">
                <input type="text" class="form-control" name="<?php echo $mailtemplate_name; ?>[Discount]" value="<?php if(!empty($mailtemplate_data['Discount'])) echo $mailtemplate_data['Discount']; else echo '10'; ?>">
                <span class="input-group-addon">
                   <span style="display:none;" id="currencyAddon"><?php echo $currency; ?></span><span style="display:none;" id="percentageAddon">%</span>
               </span>
            </div>
          </div>
        </div>
        <br />
        <div class="row">
          <div class="col-md-3">
            <h5 class="option_title"><?php echo $text_total_amount; ?></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_total_amount_help; ?></span>
          </div>
          <div class="col-md-3">
            <div class="input-group">
                <input type="text" class="form-control" name="<?php echo $mailtemplate_name; ?>[TotalAmount]" value="<?php if(!empty($mailtemplate_data['TotalAmount'])) echo $mailtemplate_data['TotalAmount']; else echo '20'; ?>">
                <span class="input-group-addon"><?php echo $currency ?></span>
            </div>
          </div>
        </div>
        <br />
        <div class="row">
          <div class="col-md-3">
            <h5 class="option_title"><?php echo $text_discount_validity; ?></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_discount_validity_help; ?></span>
          </div>
          <div class="col-md-3">
            <div class="input-group">
                <input type="text" class="form-control" value="<?php if(!empty($mailtemplate_data['DiscountValidity'])) echo (int)$mailtemplate_data['DiscountValidity']; else echo 7; ?>" name="<?php echo $mailtemplate_name; ?>[DiscountValidity]">
                <span class="input-group-addon"><?php echo $text_days_small; ?></span>
            </div>
          </div>
        </div>
        <br />
        <div class="row">
          <div class="col-md-3">
            <h5 class="option_title"><?php echo $text_apply_discount; ?></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_apply_discount_help; ?></span>
          </div>
          <div class="col-md-3">
            <select class="form-control" name="<?php echo $mailtemplate_name; ?>[DiscountApply]" > 
                <option value="all_products" <?php if(!empty($mailtemplate_data['DiscountApply']) && $mailtemplate_data['DiscountApply'] == "all_products") echo "selected"; ?>><?php echo $text_all_products; ?></option>
                <option value="cart_products" <?php if(!empty($mailtemplate_data['DiscountApply']) && $mailtemplate_data['DiscountApply'] == "cart_products") echo "selected"; ?>><?php echo $text_specific_products; ?></option>
            </select>
          </div>
        </div>
        <br />
        <div class="row">
          <div class="col-md-3">
            <h5 class="option_title"><?php echo $text_discount_free_shipping; ?></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_discount_free_shipping_help; ?></span>
          </div>
          <div class="col-md-3">
            <select class="form-control" name="<?php echo $mailtemplate_name; ?>[DiscountShipping]" > 
                <option value="1" <?php echo (!empty($mailtemplate_data['DiscountShipping']) && $mailtemplate_data['DiscountShipping'] == '1') ? 'selected=selected' : '' ?>><?php echo $text_yes; ?></option>
                <option value="0"  <?php echo (empty($mailtemplate_data['DiscountShipping']) || $mailtemplate_data['DiscountShipping'] == '0') ? 'selected=selected' : '' ?>><?php echo $text_no; ?></option>
            </select>
          </div>
        </div>
        <br />
        <div class="row">
          <div class="col-md-3">
            <h5 class="option_title"><?php echo $text_discount_customer_login; ?></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_discount_customer_login_help; ?></span>
          </div>
          <div class="col-md-3">
            <select class="form-control" name="<?php echo $mailtemplate_name; ?>[DiscountCustomerLogin]" > 
                <option value="1" <?php echo (!empty($mailtemplate_data['DiscountCustomerLogin']) && $mailtemplate_data['DiscountCustomerLogin'] == '1') ? 'selected=selected' : '' ?>><?php echo $text_yes; ?></option>
                <option value="0"  <?php echo (empty($mailtemplate_data['DiscountCustomerLogin']) || $mailtemplate_data['DiscountCustomerLogin'] == '0') ? 'selected=selected' : '' ?>><?php echo $text_no; ?></option>
            </select>
          </div>
        </div>
    </div>
    <br />
	<div class="row">
      <div class="col-md-3">
        <h5 class="option_title"><?php echo $text_product_dimensions; ?></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_product_dimens_help; ?></span>
      </div>
      <div class="col-md-3">
        <div class="input-group">
           <span class="input-group-addon"><?php echo $text_width; ?>&nbsp;</span> <input class="form-control" id="appendedInput" type="text" name="<?php echo $mailtemplate_name; ?>[ProductWidth]" value="<?php echo (isset($mailtemplate_data['ProductWidth'])) ? $mailtemplate_data['ProductWidth'] : '60' ?>">
          <span class="input-group-addon">px</span>
        </div>
        <br />
        <div class="input-group">
            <span class="input-group-addon"><?php echo $text_height; ?></span> <input class="form-control" id="appendedInput" type="text" name="<?php echo $mailtemplate_name; ?>[ProductHeight]" value="<?php echo (isset($mailtemplate_data['ProductHeight'])) ? $mailtemplate_data['ProductHeight'] : '60' ?>">
          <span class="input-group-addon">px</span>
        </div>
      </div>
    </div>
    <hr />
	<div class="row">
      <div class="col-md-3">
        <h5 class="option_title"><?php echo $text_message_customer; ?></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_message_customer_help; ?></span>
      </div>
      <div class="col-md-9">
        <ul class="nav nav-tabs mailtemplate_tabs">
            <?php $i=0; foreach ($languages as $language) { ?>
              <?php $flag_url = version_compare(VERSION, '2.2.0.0', "<") ? 'view/image/flags/' . $language['image'] : 'language/' . $language['code'] . '/' . $language['code'] . '.png'; ?>
                <li <?php if ($i==0) echo 'class="active"'; ?>><a href="#tab-<?php echo $mailtemplate['id']; ?>-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="<?php echo $flag_url; ?>"/> <?php echo $language['name']; ?></a></li>
            <?php $i++; }?>
        </ul>	
        <div class="tab-content">
			<?php $i=0; foreach ($languages as $language) { ?>
            	<div id="tab-<?php echo $mailtemplate['id']; ?>-<?php echo $language['language_id']; ?>" language-id="<?php echo $language['language_id']; ?>" class="row-fluid tab-pane language <?php if ($i==0) echo 'active'; ?>">
                <div class="row">
                  <div class="col-md-7">
					<input placeholder="Mail subject" type="text" class="form-control" name="<?php echo $mailtemplate_name; ?>[Subject][<?php echo $language['language_id']; ?>]" value="<?php if(!empty($mailtemplate_data['Subject'][$language['language_id']])) echo $mailtemplate_data['Subject'][$language['language_id']]; else echo $text_template_sample; ?>" />
                  </div>
                </div>
                <br />
				<div class="row">
                  <div class="col-md-12">
					<textarea class="mailMessageText" id="message_<?php echo $mailtemplate['id']; ?>_<?php echo $language['language_id']; ?>" name="<?php echo $mailtemplate_name; ?>[Message][<?php echo $language['language_id']; ?>]">
						<?php if(!empty($mailtemplate_data['Message'][$language['language_id']])) echo $mailtemplate_data['Message'][$language['language_id']]; else echo '<table style="width:100%"><tbody><tr><td align="center"><table style="width:650px;margin:0 auto;border:1px solid #f0f0f0;padding:10px;line-height:1.8"><tbody><tr><td>'.$column_text_template.'</td></tr></tbody></table><table style="width:650px;margin:0 auto;line-height:1.8"><tbody><tr><td><div style="float:right;font-size:11px;">{unsubscribe_link}</div></td></tr></tbody></table></td></tr></tbody></table>'; ?>
				    </textarea>
                  </div>
                </div>
        	</div>
        <?php $i++; } ?>
		</div>
      </div>
    </div>
	<div class="row removable">
	  <br />
      <div class="col-md-3">
        <h5 class="option_title"><?php echo $text_remove_empty_records; ?></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_remove_empty_help; ?></span>
      </div>
	  <div class="col-md-3">
        <select id="Checker" name="<?php echo $mailtemplate_name; ?>[RemoveEmptyRecords]" class="form-control">
          <option value="yes" <?php echo (!empty($mailtemplate_data['RemoveEmptyRecords']) && $mailtemplate_data['RemoveEmptyRecords'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
          <option value="no" <?php echo (empty($mailtemplate_data['RemoveEmptyRecords']) || $mailtemplate_data['RemoveEmptyRecords']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
        </select>
      </div>
    </div>
    <br />
    <div class="row">
      <div class="col-md-3">
        <h5 class="option_title"><?php echo $text_additional_options; ?></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_additional_options_help; ?></span>
      </div>
      <div class="col-md-6">
        <div class="checkbox">
            <label>
                <input type="checkbox" name="<?php echo $mailtemplate_name; ?>[RemoveAfterSend]" value="yes" <?php echo !empty($mailtemplate_data['RemoveAfterSend']) ? 'checked="checked"' : ''; ?>/> <?php echo $text_remove_email_after_sending; ?>
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" name="<?php echo $mailtemplate_name; ?>[SkipOutOfStock]" value="yes" <?php echo !empty($mailtemplate_data['SkipOutOfStock']) ? 'checked="checked"' : ''; ?>/> <?php echo $text_skip_out_of_stock; ?>
            </label>
        </div>
      </div>
    </div>
    <?php if (isset($newAddition) && $newAddition==true) { ?>
    <script type="text/javascript">
        <?php foreach ($languages as $language) { ?>
            $('#message_<?php echo $mailtemplate['id']; ?>_<?php echo $language['language_id']; ?>').summernote({
                    height: 320
            });
        <?php } ?>
        selectorsForDiscount();
    </script>
    <?php } ?>
</div>
