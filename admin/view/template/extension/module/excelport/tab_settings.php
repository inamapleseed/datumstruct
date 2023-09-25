<div class="form-horizontal">
    <div class="form-group">
        <label class="control-label col-sm-2">
            <?php echo $text_export_product_description_html; ?>
        </label>
        <div class="col-sm-10">
            <select name="ExcelPort[Settings][DescriptionEncoding]" class="form-control">
                <option value="encoded_html" <?php if (!empty($data['ExcelPort']['Settings']['DescriptionEncoding']) && $data['ExcelPort']['Settings']['DescriptionEncoding'] == "encoded_html") echo 'selected="selected"'; ?>><?php echo $option_encoded_html; ?></option>
                <option value="standard_html" <?php if (!empty($data['ExcelPort']['Settings']['DescriptionEncoding']) && $data['ExcelPort']['Settings']['DescriptionEncoding'] == "standard_html") echo 'selected="selected"'; ?>><?php echo $option_standard_html; ?></option>
                <option value="no_html" <?php if (!empty($data['ExcelPort']['Settings']['DescriptionEncoding']) && $data['ExcelPort']['Settings']['DescriptionEncoding'] == "no_html") echo 'selected="selected"'; ?>><?php echo $option_no_html; ?></option>
            </select>
        </div> 
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">
            <?php echo $text_export_entries_number; ?>
        </label>
        <div class="col-sm-10">
            <input type="number" min="50" max="800" name="ExcelPort[Settings][ExportLimit]" value="<?php echo !empty($data['ExcelPort']['Settings']['ExportLimit']) ? $data['ExcelPort']['Settings']['ExportLimit'] : '500'; ?>" class="form-control" />
        </div> 
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">
        <?php echo $text_import_limit; ?>
        </label>
        <div class="col-sm-10">
            <input type="number" min="10" max="800" name="ExcelPort[Settings][ImportLimit]" value="<?php echo !empty($data['ExcelPort']['Settings']['ImportLimit']) ? $data['ExcelPort']['Settings']['ImportLimit'] : '100'; ?>" class="form-control" />
        </div> 
    </div>
    <div class="form-group">
        <label class="control-label col-sm-2">
            <?php echo $text_export_non_store_products; ?>
        </label>
        <div class="col-sm-10">
            <select name="ExcelPort[Settings][ExportNonStoreProducts]" class="form-control">
                <option value="0" <?php if (empty($data['ExcelPort']['Settings']['ExportNonStoreProducts'])) echo 'selected="selected"'; ?>><?php echo $text_no; ?></option>
                <option value="1" <?php if (!empty($data['ExcelPort']['Settings']['ExportNonStoreProducts'])) echo 'selected="selected"'; ?>><?php echo $text_yes; ?></option>
            </select>
        </div> 
    </div>
</div>