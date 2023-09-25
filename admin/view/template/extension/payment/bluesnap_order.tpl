<h2><?php echo $text_payment_info; ?></h2>
<div class="bs-example">
	<div class="panel-group" id="accordion">
		<?php foreach ($bluesnap_audit_entries as $i=>$bluesnap_audit_entry) {
			$id = $bluesnap_audit_entry['bluesnap_audit_id'];
		?>
	 		<div class="panel panel-default">
        			<div class="panel-heading">
                			<h4 class="panel-title">
                    				<a data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $id;?>"><?php echo $text_audit_entry_title;?><?php echo $id;?></a>
                			</h4>
            			</div>
          			<div id="collapse<?php echo $id;?>" class="panel-collapse collapse in">
                			<div class="panel-body">
						<div class="row">
							<div class="col-xs-12">
						        <?php foreach ($bluesnap_audit_entry as $key=>$value) {?>
						        	<div class="row">
						                        <div class="col-xs-2"><?php echo $key;?></div>
							                <div class="col-xs-10"><pre><?php echo $value;?></pre></div>
								</div>
							<?php }?>
						</div>
                			</div>
				</div>
			</div>
		<?php }?>
        </div>
</div>
