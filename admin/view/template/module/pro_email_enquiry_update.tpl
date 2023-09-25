<div class="tab-pane" id="tab-content-1-a">
        <ul class="nav nav-pills nav-stacked col-md-2 menu-statuses">
        <?php $f=1; $row=0; foreach ($enquiry_order_statuses as $status) { ?>
        <li <?php if($f) echo 'class="active"'; $f=0; ?>><a href="#tab-enquiry_status-<?php echo $row; ?>" data-type="enquiry.update|<?php echo $status['order_status_id']; ?>" data-toggle="pill" <?php if(isset($status['color']) && $status['color'] != '000000') { ?>style="color:#<?php echo $status['color']; ?>"<?php } ?>><?php echo $status['name']; ?></a></li>
        <?php $row++; } ?>
        </ul>
        <div class="tab-content col-md-10">
        <?php mailEditorForm('enquiry_status', $enquiry_order_statuses, $languages, $_language, $from_name_placeholder, $from_email_placeholder); ?>
        </div>
</div>