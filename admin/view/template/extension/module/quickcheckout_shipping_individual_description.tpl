<ul class="nav nav-tabs" id="language-<?= $shipping_module['code']; ?>">
    <?php foreach ($languages as $language) { ?>
        <li><a href="#language-<?= $shipping_module['code']; ?>-<?= $language['language_id']; ?>" data-toggle="tab"><img src="language/<?= $language['code']; ?>/<?= $language['code']; ?>.png" title="<?= $language['name']; ?>" /> <?= $language['name']; ?></a></li>
    <?php } ?>
</ul>
<div class="tab-content">
    <?php foreach ($languages as $language) { ?>
        <div class="tab-pane" id="language-<?= $shipping_module['code']; ?>-<?= $language['language_id']; ?>">
            <div class="row">
                <div class="col-xs-12">
                    <textarea 
                    name="quickcheckout_shipping_individual_shipping_notice[<?= $shipping_module['code']; ?>][<?= $language['language_id']; ?>]" 
                    id="input-description<?= $language['language_id']; ?>" 
                    class="form-control"
                    ><?= isset($quickcheckout_shipping_individual_shipping_notice[$shipping_module['code']][$language['language_id']]) ? $quickcheckout_shipping_individual_shipping_notice[$shipping_module['code']][$language['language_id']] : ''; ?></textarea>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<script>
    $('#language-<?= $shipping_module['code']; ?> a:first').tab('show');
</script>
