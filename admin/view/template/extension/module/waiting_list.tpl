<div class="form-group"> 
    <label class="control-label col-sm-2"><?= $entry_status; ?></label>
    <div class="col-sm-10">
        <select class="form-control" name="waiting_list_status">
            <option value="0"><?= $text_disabled; ?></option>
            <option value="1" <?= $waiting_list_status?'selected':''; ?> ><?= $text_enabled; ?></option>
        </select>
    </div>
</div>

<div class="form-group required"> 
    <label class="control-label col-sm-2">
        <span data-toggle="tooltip" title="<?= $entry_response_success_help; ?>" ><?= $entry_response_success; ?></span>
    </label>
    <div class="col-sm-10">
        <input class="form-control" name="waiting_list_success" value="<?= $waiting_list_success; ?>" />
        <?php if($error_waiting_list_success){ ?>
            <div class="text-danger" ><?= $error_waiting_list_success; ?></div>    
        <?php } ?>
    </div>
</div>

<div class="form-group required"> 
    <label class="control-label col-sm-2">
        <span data-toggle="tooltip" title="<?= $entry_response_error_help; ?>" ><?= $entry_response_error; ?></span>
    </label>
    <div class="col-sm-10">
        <input class="form-control" name="waiting_list_error" value="<?= $waiting_list_error; ?>" />
        <?php if($error_waiting_list_error){ ?>
            <div class="text-danger" ><?= $error_waiting_list_error; ?></div>    
        <?php } ?>
    </div>
</div>

<div class="form-group"> 
    <label class="control-label col-sm-2"><?= $entry_description; ?></label>
    <div class="col-sm-10">
        <textarea name="waiting_list_description" id="waiting_list_description"><?= $waiting_list_description; ?></textarea>
    </div>
</div>

<h3><?= $text_waiters; ?></h3>
<hr/>
<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <td><?= $column_product_name; ?></td>
            <td width="1px" ><?= $column_no_request; ?></td>
        </tr>
    </thead>
    <tbody>
        <?php foreach($waiting_products as $product){ ?>
            <tr>
                <td><?= $product['name']; ?></td>
                <td class="text-right"><?= $product['request']; ?></td>
            </tr>    
        <?php } ?>
    </tbody>
</table>

<div class="row">
    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>

<script>
CKEDITOR.replace("waiting_list_description", { baseHref: "<?= $base_url; ?>", language: "en", filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>', filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>', filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>', height: 350 });</script>