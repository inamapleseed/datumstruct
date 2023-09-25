<?php $address_row = 1; ?>
<?php foreach ($addresses as $address) { ?>
<div class="tab-pane" id="tab-address<?php echo $address_row; ?>">
    <input type="hidden" name="address[<?php echo $address_row; ?>][address_id]" value="<?php echo $address['address_id']; ?>" />
    <div class="form-group required">
    <label class="col-sm-2 control-label" for="input-firstname<?php echo $address_row; ?>"><?php echo $entry_firstname; ?></label>
    <div class="col-sm-10">
        <input type="text" name="address[<?php echo $address_row; ?>][firstname]" value="<?php echo $address['firstname']; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname<?php echo $address_row; ?>" class="form-control" />
        <?php if (isset($error_address[$address_row]['firstname'])) { ?>
        <div class="text-danger"><?php echo $error_address[$address_row]['firstname']; ?></div>
        <?php } ?>
    </div>
    </div>
    <div class="form-group required">
    <label class="col-sm-2 control-label" for="input-lastname<?php echo $address_row; ?>"><?php echo $entry_lastname; ?></label>
    <div class="col-sm-10">
        <input type="text" name="address[<?php echo $address_row; ?>][lastname]" value="<?php echo $address['lastname']; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname<?php echo $address_row; ?>" class="form-control" />
        <?php if (isset($error_address[$address_row]['lastname'])) { ?>
        <div class="text-danger"><?php echo $error_address[$address_row]['lastname']; ?></div>
        <?php } ?>
    </div>
    </div>
    <div class="form-group">
    <label class="col-sm-2 control-label" for="input-company<?php echo $address_row; ?>"><?php echo $entry_company; ?></label>
    <div class="col-sm-10">
        <input type="text" name="address[<?php echo $address_row; ?>][company]" value="<?php echo $address['company']; ?>" placeholder="<?php echo $entry_company; ?>" id="input-company<?php echo $address_row; ?>" class="form-control" />
    </div>
    </div>
    <div class="form-group required">
    <label class="col-sm-2 control-label" for="input-postcode<?php echo $address_row; ?>"><?php echo $entry_postcode; ?></label>
    <div class="col-sm-10">
        <input type="text" name="address[<?php echo $address_row; ?>][postcode]" value="<?php echo $address['postcode']; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode<?php echo $address_row; ?>" class="form-control" />
        <?php if (isset($error_address[$address_row]['postcode'])) { ?>
        <div class="text-danger"><?php echo $error_address[$address_row]['postcode']; ?></div>
        <?php } ?>
    </div>
    <script type="text/javascript">
        $(window).load(function () {
        postalcode('#input-postcode<?php echo $address_row; ?>', '#input-address-1<?php echo $address_row; ?>', '#input-address-2<?php echo $address_row; ?>');
        });
    </script>
    </div>
    <div class="form-group required">
    <label class="col-sm-2 control-label" for="input-address-1<?php echo $address_row; ?>"><?php echo $entry_address_1; ?></label>
    <div class="col-sm-10">
        <input type="text" name="address[<?php echo $address_row; ?>][address_1]" value="<?php echo $address['address_1']; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1<?php echo $address_row; ?>" class="form-control" />
        <?php if (isset($error_address[$address_row]['address_1'])) { ?>
        <div class="text-danger"><?php echo $error_address[$address_row]['address_1']; ?></div>
        <?php } ?>
    </div>
    </div>
    <div class="form-group">
    <label class="col-sm-2 control-label" for="input-address-2<?php echo $address_row; ?>"><?php echo $entry_address_2; ?></label>
    <div class="col-sm-10">
        <input type="text" name="address[<?php echo $address_row; ?>][address_2]" value="<?php echo $address['address_2']; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-address-2<?php echo $address_row; ?>" class="form-control" />
    </div>
    </div>
    <div class="form-group">
    <label class="col-sm-2 control-label" for="input-unit-no<?php echo $address_row; ?>"><?php echo $entry_unit_no; ?></label>
    <div class="col-sm-10">
        <input type="text" name="address[<?php echo $address_row; ?>][unit_no]" value="<?php echo $address['unit_no']; ?>" placeholder="<?php echo $entry_unit_no; ?>" id="input-unit-no<?php echo $address_row; ?>" class="form-control" />
    </div>
    </div>
    <div class="form-group">
        <label class="col-sm-2 control-label" for="input-city<?php echo $address_row; ?>"><?php echo $entry_city; ?></label>
        <div class="col-sm-10">
            <input type="text" name="address[<?php echo $address_row; ?>][city]" value="<?php echo $address['city']; ?>" placeholder="<?php echo $entry_city; ?>" id="input-city<?php echo $address_row; ?>" class="form-control" />
            <?php if (isset($error_address[$address_row]['city'])) { ?>
            <div class="text-danger"><?php echo $error_address[$address_row]['city']; ?></div>
            <?php } ?>
        </div>
    </div>
    
    <div class="form-group required">
    <label class="col-sm-2 control-label" for="input-country<?php echo $address_row; ?>"><?php echo $entry_country; ?></label>
    <div class="col-sm-10">
        <select name="address[<?php echo $address_row; ?>][country_id]" id="input-country<?php echo $address_row; ?>" onchange="country(this, '<?php echo $address_row; ?>', '<?php echo $address['zone_id']; ?>');" class="form-control">
        <option value=""><?php echo $text_select; ?></option>
        <?php foreach ($countries as $country) { ?>
        <?php if ($country['country_id'] == $address['country_id']) { ?>
        <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
        <?php } ?>
        <?php } ?>
        </select>
        <?php if (isset($error_address[$address_row]['country'])) { ?>
        <div class="text-danger"><?php echo $error_address[$address_row]['country']; ?></div>
        <?php } ?>
    </div>
    </div>
    <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-zone<?php echo $address_row; ?>"><?php echo $entry_zone; ?></label>
        <div class="col-sm-10">
            <select name="address[<?php echo $address_row; ?>][zone_id]" id="input-zone<?php echo $address_row; ?>" class="form-control">
            </select>
            <?php if (isset($error_address[$address_row]['zone'])) { ?>
            <div class="text-danger"><?php echo $error_address[$address_row]['zone']; ?></div>
            <?php } ?>
        </div>
    </div>
    <?php include('customer_form_address_custom_field.tpl'); ?>
    <div class="form-group">
    <label class="col-sm-2 control-label"><?php echo $entry_default; ?></label>
    <div class="col-sm-10">
        <label class="radio">
        <?php if (($address['address_id'] == $address_id) || !$addresses) { ?>
        <input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" checked="checked" />
        <?php } else { ?>
        <input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" />
        <?php } ?>
        </label>
    </div>
    </div>
</div>
<?php $address_row++; ?>
<?php } ?>