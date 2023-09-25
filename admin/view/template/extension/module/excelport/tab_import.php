<table class="form">
    <tr>
        <td>
            <div class="question"><?php echo $text_question_data_import; ?></div>
            <div class="option">
            	<input id="DataTypeProductsOptionImport" type="radio" name="ExcelPort[Import][DataType]" value="Products" <?php echo (empty($data['ExcelPort']['Import']['DataType']) || $data['ExcelPort']['Import']['DataType'] == 'Products') ? 'checked="checked"' : ''; ?> /><label for="DataTypeProductsOptionImport"><?php echo $text_datatype_option_products; ?></label>
            </div>
            <div class="option">
            	<input id="DataTypeCategoriesOptionImport" type="radio" name="ExcelPort[Import][DataType]" value="Categories" <?php echo (!empty($data['ExcelPort']['Import']['DataType']) && $data['ExcelPort']['Import']['DataType'] == 'Categories') ? 'checked="checked"' : ''; ?> /><label for="DataTypeCategoriesOptionImport"><?php echo $text_datatype_option_categories; ?></label>
            </div>
            <div class="option">
            	<input id="DataTypeOptionsOptionImport" type="radio" name="ExcelPort[Import][DataType]" value="Options" <?php echo (!empty($data['ExcelPort']['Import']['DataType']) && $data['ExcelPort']['Import']['DataType'] == 'Options') ? 'checked="checked"' : ''; ?> /><label for="DataTypeOptionsOptionImport"><?php echo $text_datatype_option_options; ?></label>
            </div>
            <div class="option">
            	<input id="DataTypeAttributesOptionImport" type="radio" name="ExcelPort[Import][DataType]" value="Attributes" <?php echo (!empty($data['ExcelPort']['Import']['DataType']) && $data['ExcelPort']['Import']['DataType'] == 'Attributes') ? 'checked="checked"' : ''; ?> /><label for="DataTypeAttributesOptionImport"><?php echo $text_datatype_option_attributes; ?></label>
            </div>
            <div class="option">
            	<input id="DataTypeCustomersOptionImport" type="radio" name="ExcelPort[Import][DataType]" value="Customers" <?php echo (empty($data['ExcelPort']['Import']['DataType']) || $data['ExcelPort']['Import']['DataType'] == 'Customers') ? 'checked="checked"' : ''; ?> /><label for="DataTypeCustomersOptionImport"><?php echo $text_datatype_option_customers; ?></label>
            </div>
            <div class="option">
                <input id="DataTypeCustomerGroupsOptionImport" type="radio" name="ExcelPort[Import][DataType]" value="CustomerGroups" <?php echo (empty($data['ExcelPort']['Import']['DataType']) || $data['ExcelPort']['Import']['DataType'] == 'CustomerGroups') ? 'checked="checked"' : ''; ?> /><label for="DataTypeCustomerGroupsOptionImport"><?php echo $text_datatype_option_customer_groups; ?></label>
            </div>
            <div class="option">
                <input id="DataTypeOrdersOptionImport" type="radio" name="ExcelPort[Import][DataType]" value="Orders" <?php echo (empty($data['ExcelPort']['Import']['DataType']) || $data['ExcelPort']['Import']['DataType'] == 'Orders') ? 'checked="checked"' : ''; ?> /><label for="DataTypeOrdersOptionImport"><?php echo $text_datatype_option_orders; ?></label>
            </div>
            <div class="option">
                <input id="DataTypeManufacturersOptionImport" type="radio" name="ExcelPort[Import][DataType]" value="Manufacturers" <?php echo (empty($data['ExcelPort']['Import']['DataType']) || $data['ExcelPort']['Import']['DataType'] == 'Manufacturers') ? 'checked="checked"' : ''; ?> /><label for="DataTypeManufacturersOptionImport"><?php echo $text_datatype_option_manufacturers; ?></label>
            </div>
            <div class="option">
                <input id="DataTypeCouponsOptionImport" type="radio" name="ExcelPort[Import][DataType]" value="Coupons" <?php echo (empty($data['ExcelPort']['Import']['DataType']) || $data['ExcelPort']['Import']['DataType'] == 'Coupons') ? 'checked="checked"' : ''; ?> /><label for="DataTypeCouponsOptionImport"><?php echo $text_datatype_option_coupons; ?></label>
            </div>
            <div class="option">
                <input id="DataTypeVouchersOptionImport" type="radio" name="ExcelPort[Import][DataType]" value="Vouchers" <?php echo (empty($data['ExcelPort']['Import']['DataType']) || $data['ExcelPort']['Import']['DataType'] == 'Vouchers') ? 'checked="checked"' : ''; ?> /><label for="DataTypeVouchersOptionImport"><?php echo $text_datatype_option_vouchers; ?></label>
            </div>
            
            <div class="question" data-depends-on="#DataTypeProductsOptionImport, #DataTypeCategoriesOptionImport, #DataTypeOptionsOptionImport, #DataTypeAttributesOptionImport, #DataTypeCustomersOptionImport, #DataTypeCustomerGroupsOptionImport"><?php echo $text_question_language_import; ?></div>
            <?php foreach ($languages as $index => $language) : ?>
            <div class="option" data-depends-on="#DataTypeProductsOptionImport, #DataTypeCategoriesOptionImport, #DataTypeOptionsOptionImport, #DataTypeAttributesOptionImport, #DataTypeCustomersOptionImport, #DataTypeCustomerGroupsOptionImport">
            	<input id="Language<?php echo $language['language_id']; ?>OptionImport" type="radio" name="ExcelPort[Import][Language]" value="<?php echo $language['language_id']; ?>"<?php echo $index == 0 && empty($data['ExcelPort']['Import']['Language']) ? ' checked="checked"' : (!empty($data['ExcelPort']['Import']['Language']) && $data['ExcelPort']['Import']['Language'] == $language['language_id'] ? ' checked="checked"' : ''); ?> /><label for="Language<?php echo $language['language_id']; ?>OptionImport"><?php echo $language['name']; ?></label>
            </div>
            <?php endforeach; ?>
            <div class="question"><?php echo $text_question_file_import; ?></div>
            <div class="option">
            	<input type="file" name="ExcelPort[Import][File]" />
            </div>
            <div class="question"><input id="checkboxDelete" type="checkbox" name="ExcelPort[Import][Delete]" value="1" /><label><?php echo $text_question_delete_other; ?></label></div>
            <div class="question" data-depends-on="#DataTypeProductsOptionImport, #DataTypeAttributesOptionImport, #DataTypeOptionsOptionImport, #DataTypeCustomersOptionImport, #DataTypeCustomerGroupsOptionImport, #DataTypeManufacturersOptionImport, #DataTypeCouponsOptionImport, #DataTypeVouchersOptionImport"><input id="checkboxAddAsNew" type="checkbox" name="ExcelPort[Import][AddAsNew]" value="1" <?php echo !empty($data['ExcelPort']['Import']['AddAsNew']) ? 'checked="checked"' : ''; ?> /><label><?php echo $text_question_add_as_new; ?></label></div>
        </td>  
    </tr>
    <tr>
        <td>
            <input type="hidden" id="last_import_input" name="ExcelPort[LastImport]" value="<?php echo !empty($data['ExcelPort']['LastImport']) ? $data['ExcelPort']['LastImport'] : ''; ?>" />
            <div style="margin-top: 15px;" id="last_import" data-template="<?php echo $text_last_import; ?>" data-text="<?php echo !empty($data['ExcelPort']['LastImport']) ? $data['ExcelPort']['LastImport'] : ''; ?>">
            </div>
        	<div>
        		<a data-action="import" class="btn btn-success continueAction ExcelPortSubmitButton"><?php echo $button_import; ?></a>
            </div>
			<div class="help"><strong><?php echo $text_note; ?></strong> <?php echo $text_supported_in_oc1541; ?> <a class='needMoreSize' href="javascript:void(0)"><i class="fa fa-external-link"></i> <?php echo $text_learn_to_increase; ?></a></div>
        </td>
    </tr>
</table>