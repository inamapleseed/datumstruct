<div class="row">
    <div class="col-sm-12">
        <ul class="nav nav-tabs" id="language">
            <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?= $language['language_id']; ?>" data-toggle="tab"><img src="language/<?= $language['code']; ?>/<?= $language['code']; ?>.png" title="<?= $language['name']; ?>" /> <?= $language['name']; ?></a></li>
            <?php } ?>
        </ul>
        <div class="tab-content">
            <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?= $language['language_id']; ?>">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-description<?= $language['language_id']; ?>">Shipping notice</label>
                        <div class="col-sm-10">
                            <textarea 
                            name="quickcheckout_shipping_general_notice[<?= $language['language_id']; ?>]" 
                            id="input-description<?= $language['language_id']; ?>" 
                            class="form-control summernote"
                            ><?= isset($quickcheckout_shipping_general_notice[$language['language_id']]) ? $quickcheckout_shipping_general_notice[$language['language_id']] : ''; ?></textarea>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
	<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
	<script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
    <script>
        $('#language a:first').tab('show');
    </script>
</div>