<div id="SendReminderSuccess"></div>

<form id="SendReminderCustomForm">
    <input type="hidden" name="ABcart_id" value="<?php echo $id; ?>" />
    <input type="hidden" name="AB_store" value="<?php echo $store_id; ?>" />
    <input type="hidden" name="AB_template_id" value="<?php echo $mailtemplate['id']; ?>" />
    <input type="hidden" name="AB_language_id" value="<?php echo $language_id; ?>" />
    <input type="hidden" name="AB_email" id="customer_email" value="<?php if(!empty($result['customer_info']['email'])) echo $result['customer_info']['email']; ?>" />
    
    <div class="row">
        <div class="col-xs-3">
            <label>To: <strong><?php if(!empty($result['customer_info']['email'])) echo $result['customer_info']['email']; ?></strong></label>
        </div>
        <div class="col-xs-3">
            <label>Name: <strong><?php echo $result['customer_info']['firstname']." ". $result['customer_info']['lastname']; ?></strong></label>
        </div>
        <div class="col-xs-3">
            <label>Phone: <strong><?php echo $result['customer_info']['telephone']; ?></strong></label>
        </div>
    </div>
    
    <?php if (isset($mailtemplate['id'])) { ?>
    <div class="row">
        <div class="col-xs-12">
            <?php require(DIR_APPLICATION.'view/template/'.$modulePath.'/tab_mailtab.tpl'); ?>
        </div>
    </div>
    <?php } ?>
</form>

<script type="text/javascript">
var language_id = '<?php echo $language_id; ?>';

$('#SendReminderCustomForm .removable').remove();
$('#SendReminderCustomForm .mailtemplate_tabs a[href="#tab-<?php echo $mailtemplate["id"]; ?>-' + language_id + '"]').tab('show');
$('#SendReminderCustomForm .mailtemplate_tabs a[href="#tab-<?php echo $mailtemplate["id"]; ?>-' + language_id + '"]').parent().siblings().hide();
</script>