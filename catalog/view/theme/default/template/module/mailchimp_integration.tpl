<?php $module_id = rand(); ?>

<style type="text/css">
	.mailchimp-integration {
		overflow: hidden;
		/* margin-bottom: -30px; */
	}
	.mailchimp-integration h3,
	.mailchimp-integration h4 {
		margin-top: 0;
	}
	.mailchimp-integration h4 {
		margin-bottom: 5px;
	}
	.mailchimp-integration label,
	.mailchimp-integration input[type="checkbox"],
	.mailchimp-integration input[type="radio"] {
		cursor: pointer;
	}
	.mi-message {
		display: none;
		font-size: 11px;
		margin-bottom: 10px;
	}
	.mi-message a {
		font-size: 11px;
	}

	.box-content{
		max-width: 315px;
		width: 100%;
		margin: auto;
	}

	.newsletter-grid{
		display: grid;
		grid-template-columns: 1fr auto;
		grid-template-rows: auto;
	}

	.newsletter-grid > div{
		grid-column: 1/3;
	}
	
	.newsletter-grid > div:nth-last-child(2){
		grid-column-start: 1;
		grid-column-end: 2;
	}
	
	.newsletter-grid > div:last-child{
		grid-column-start: 2;
		grid-column-end: 3;
	}

	/* IE */
	.newsletter-grid{
		display: -ms-grid;
		-ms-grid-columns: 1fr auto;
		-ms-grid-rows: auto auto auto auto auto auto auto auto auto auto auto auto auto auto auto;
	}

	.newsletter-grid > div{
		-ms-grid-column: 1;
		-ms-grid-column-span: 2;
		-ms-grid-row-span: 1;
	}

	<?php 
	$i = $i_printable = 0;
	foreach ($default_fields as $field){
		$i++;
		if (empty($settings['modules_' . $field]) || $settings['modules_' . $field] == 'hide') continue;
		
		$i_printable++;
		echo '.newsletter-grid > div:nth-child('.$i.'){
			-ms-grid-row: '.$i_printable.';
		}
		';
	} ?>

	<?php $second_last = $i_printable+1; ?>
	
	.newsletter-grid > div:last-child,
	.newsletter-grid > div:nth-child(<?= $second_last; ?>){
		-ms-grid-row: <?= $second_last; ?>;
		-ms-grid-column-span: 1;
	}
	
	.newsletter-grid > div:last-child{
		-ms-grid-column: 2;
	}
	/*END IE*/

	.mi-block {
		margin: 5px 0px;		
		vertical-align: top;
	}

	.mi-toptext {
		display: block;
		margin: 5px;
	}
	.mi-required {
		color: #F00;
	}
	.mi-button {
		text-align: center;
	}
	#content .mi-button {
		margin-top: 26px;
	}
	#column-left .mi-button,
	#column-right .mi-button {
		margin-top: 15px;
	}
	.mi-button .button[disabled="disabled"] {
		cursor: not-allowed;
		opacity: 0.5;
	}
	.mi-padleft {
		margin-left: 10px;
	}

	.mi-block.subscribed{
		grid-column: 1/3 !important;
		grid-row: 2/3;
	}

</style>

<?php if ($popup) { ?>
	<style type="text/css">
		#mi<?php echo $module_id; ?> h3 {
			margin-top: 20px;
		}
		#mi<?php echo $module_id; ?> {
			display: none;
			background: #FFF;
			border: 10px solid #444;
			padding: 0 15px 15px 15px;
			position: fixed;
			top: 20%;
			left: 38%;
			width: 25%;
			min-width: 210px;
			z-index: 100000;
			box-shadow: 0 0 10px #000;
			border-radius: 5px;
		}
		#mi-modal-overlay {
			display: none;
			background: #000;
			opacity: 0.5;
			position: fixed;
			_position: absolute;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			z-index: 99999;
		}
		@media (max-width: 767px) {
			#mi<?php echo $module_id; ?> {
				left: 0;
				width: 100%;
			}
		}
	</style>
	<script type="text/javascript"><!--
		function showMailchimpPopup() {
			$('#mi-modal-overlay, .mailchimp-integration').fadeIn();
			$('#mi<?php echo $module_id; ?>').find('.box-heading').removeClass('box-heading').wrap('<h3>');
			$('#mi<?php echo $module_id; ?>').find('.box-content').removeClass('box-content');
		}
		
		<?php if (!empty($trigger_popup)) { ?>
			$(document).ready(function(){
				showMailchimpPopup();
			});
		<?php } ?>
	//--></script>
	<div id="mi-modal-overlay" onclick="$(this).fadeOut().next().fadeOut()"></div>
<?php } ?>

<div id="mi<?php echo $module_id; ?>" class="mailchimp-integration box">
	<div class="box-heading">
		<?php if ($settings['moduletext_heading_'.$language]) { ?>
			<?php if (version_compare(VERSION, '2.0', '>=')) echo '<h3>'; ?>
			<?php echo html_entity_decode($settings['moduletext_heading_'.$language], ENT_QUOTES, 'UTF-8'); ?>
			<?php if (version_compare(VERSION, '2.0', '>=')) echo '</h3>'; ?>
		<?php } ?>
	</div>
	<div class="box-content">
		<!--<div class="mi-message"></div>-->
		<?php if ($subscribed) { ?>
			<div class="mi-block">
				<?php echo html(str_replace('[email]', $email, $settings['moduletext_subscribed_'.$language]), ENT_QUOTES, 'UTF-8'); ?>
			</div>
			<input type="hidden" name="email" value="<?php echo $email; ?>" />
			
			<div class="mi-block <?= $subscribed?'subscribed':''; ?>">
				<!-- <h4><span class="mi-required">*</span> <?php echo $entry_email . (strpos($entry_email, ':') ? '' : ':'); ?></h4> -->
				<input 
				placeholder="<?php echo $entry_email . (strpos($entry_email, ':') ? '' : ''); ?>"
				type="text" class="form-control" name="email" onkeydown="if (event.keyCode == 13) miSubscribe<?php echo $module_id; ?>($(this))" <?php if ($email) echo 'value="' . $email . '" disabled="disabled"'; ?> />
			</div>
			
		<?php } else { ?>
			
			<?php if (!empty($settings['moduletext_top_'.$language])) { ?>
				<div class="mi-toptext"><?php echo html_entity_decode($settings['moduletext_top_'.$language], ENT_QUOTES, 'UTF-8'); ?></div>
			<?php } ?>
			
			<div class="newsletter-grid">
				<?php if (!$email) { ?>
					<?php foreach ($default_fields as $field) { ?>
						<?php if (empty($settings['modules_' . $field]) || $settings['modules_' . $field] == 'hide') continue; ?>
						<div class="mi-block">
							<?php $placeholder = ""; ?>
							<!--
							<h4><?php if ($settings['modules_' . $field] == 'required') { ?>
									<span class="mi-required">*</span>
								<?php } ?>
								<?php if ($field == 'address') $entry_address = substr($entry_address_1, 0, strpos($entry_address_1, ' ')); ?>
								<?php echo $placeholder = ${'entry_'.$field}; ?><?php echo (strpos(${'entry_'.$field}, ':')) ? '' : ':'; ?>
							</h4>
							-->
							<?php if ($field == 'country') { ?>
								<select class="form-control" name="country">
									<?php foreach ($countries as $country) { ?>
										<option value="<?php echo $country['country_id']; ?>" <?php if ($country['country_id'] == $country_id) echo 'selected="selected"'; ?>><?php echo $country['name']; ?></option>
									<?php } ?>
								</select>
							<?php } elseif ($field == 'zone') { ?>
								<select class="form-control" name="zone"></select>
							<?php } else { ?>
								<input 
								placeholder="<?= $placeholder; ?>"
								type="text" class="form-control" name="<?php echo $field; ?>" onkeydown="if (event.keyCode == 13) miSubscribe<?php echo $module_id; ?>($(this))" />
							<?php } ?>
						</div>
					<?php } ?>
				<?php } ?>
			

				<div class="mi-block <?= $subscribed?'subscribed':''; ?>">
					<!-- <h4><span class="mi-required">*</span> <?php echo $entry_email . (strpos($entry_email, ':') ? '' : ':'); ?></h4> -->
					<input 
					placeholder="<?php echo $entry_email . (strpos($entry_email, ':') ? '' : ''); ?>"
					type="text" class="form-control" name="email" onkeydown="if (event.keyCode == 13) miSubscribe<?php echo $module_id; ?>($(this))" <?php if ($email) echo 'value="' . $email . '" disabled="disabled"'; ?> />
				</div>
				
				<?php if (!empty($mailchimp_lists)) { ?>
					<?php $lists = explode(';', $settings['modules_lists']); ?>
					
					<div class="mi-block">
						<h4><span class="mi-required">*</span> <?php echo $settings['moduletext_list_'.$language]; ?></h4>
						<?php if (!in_array('allow_multiple', $lists)) { ?>
							<select name="list" class="form-control">
						<?php } ?>
						
						<?php foreach ($mailchimp_lists as $list_id => $list_name) { ?>
							<?php if ($list_id == 'allow_multiple' || !in_array($list_id, $lists)) continue; ?>
							
							<?php if (in_array('allow_multiple', $lists)) { ?>
								<?php $checked = (in_array($list_id, $subscribed_lists)) ? 'checked="checked"' : ''; ?>
								<div><label><input type="checkbox" name="list[]" value="<?php echo $list_id; ?>" <?php echo $checked; ?> /> <?php echo $list_name; ?></label></div>
							<?php } else { ?>
								<?php $selected = (in_array($list_id, $subscribed_lists)) ? 'selected="selected"' : ''; ?>
								<option value="<?php echo $list_id; ?>" <?php echo $selected; ?>><?php echo $list_name; ?></option>
							<?php } ?>
						<?php } ?>
						
						<?php if (!in_array('allow_multiple', $lists)) { ?>
							</select>
						<?php } ?>
					</div>
				<?php } ?>
				
				<?php if ($interest_groups && !empty($settings['moduletext_interestgroups_'.$language])) { ?>
					<div class="mi-toptext"><?php echo html_entity_decode($settings['moduletext_interestgroups_'.$language], ENT_QUOTES, 'UTF-8'); ?></div>
				<?php } ?>
				
				<?php foreach ($interest_groups as $interest_group) { ?>
					<?php if (empty($settings[$settings['listid'] . '_' . $interest_group['id'] . '_' . $language])) continue; ?>
					
					<div class="mi-block">
						<h4><?php echo html_entity_decode($settings[$settings['listid'] . '_' . $interest_group['id'] . '_' . $language], ENT_QUOTES, 'UTF-8'); ?>:</h4>
						<?php if ($interest_group['type'] == 'dropdown') { ?>
							
							<select class="mi-padleft form-control" name="interests[]">
								<?php foreach ($interest_group['interests'] as $interest) { ?>
									<?php if (empty($settings[$settings['listid'] . '_' . $interest_group['id'] . '_' . $interest['id'] . '_' . $language])) continue; ?>
									<?php $selected = (isset($interests[$interest_group['id']]) && in_array($interest['id'], $interests[$interest_group['id']])) ? 'selected="selected"' : ''; ?>
									<option value="<?php echo $interest['id']; ?>" <?php echo $selected; ?>><?php echo $settings[$settings['listid'] . '_' . $interest_group['id'] . '_' . $interest['id'] . '_' . $language]; ?></option>
								<?php } ?>
							</select>
							
						<?php } else { ?>
							
							<?php foreach ($interest_group['interests'] as $interest) { ?>
								<?php if (empty($settings[$settings['listid'] . '_' . $interest_group['id'] . '_' . $interest['id'] . '_' . $language])) continue; ?>
								<?php $checked = (isset($interests[$interest_group['id']]) && in_array($interest['name'], $interests[$interest_group['id']])) ? 'checked="checked"' : ''; ?>
								<div class="mi-padleft">
									<label><input type="<?php echo str_replace('es', '', $interest_group['type']); ?>" value="<?php echo $interest['id']; ?>" <?php echo $checked; ?> name="interests[]" /> &nbsp;<?php echo html_entity_decode($settings[$settings['listid'] . '_' . $interest_group['id'] . '_' . $interest['id'] . '_' . $language], ENT_QUOTES, 'UTF-8'); ?></label>
								</div>
							<?php } ?>
							
						<?php } ?>
					</div>
				<?php } ?>
				
				<?php if (!$subscribed) { ?>
					<div class="mi-block mi-button">
						<a class="button btn btn-primary btn-mi" onclick="miSubscribe<?php echo $module_id; ?>($(this))"><?php echo html($settings['moduletext_button_'.$language]); ?></a>
					</div>
				<?php } elseif ($interest_groups || !empty($lists)) { ?>
					<div class="mi-block mi-button">
						<a class="button btn btn-primary btn-mi" onclick="miSubscribe<?php echo $module_id; ?>($(this))"><?php echo html($settings['moduletext_updatebutton_'.$language]); ?></a>
					</div>
				<?php } ?>
			</div>
		
		<?php } ?>
	</div>
</div>

<script>
	<?php if (!empty($settings['modules_zone']) && $settings['modules_zone'] != 'hide') { ?>
		$('.mailchimp-integration select[name="country"]').change(function(){
			element = $(this);
			$.getJSON('index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/getZones&country_id=' + element.val(), function(data) {
				zones = element.parent().parent().find('select[name="zone"]');
				zones.find('option').remove();
				for (i = 0; i < data.length; i++) {
					zones.append('<option value="' + data[i]['zone_id'] + '"' + (data[i]['zone_id'] == <?php echo $zone_id; ?> ? 'selected="selected"' : '') + '>' + data[i]['name'] + '</option>');
				}
			});
		});
		$('.mailchimp-integration select[name="country"]').each(function(){
			$(this).change();
		});
	<?php } ?>
	
	function miSubscribe<?php echo $module_id; ?>(element) {
		// var message = element.parent().parent().find('.mi-message');
		element.parent().parent().find('a.button').attr('disabled', 'disabled');
		
		//message.slideUp(function(){
			//message.removeClass('attention success warning alert alert-warning alert-danger');
			
			if (!$.trim(element.parent().parent().find('input[name="email"]').val()).match(/^[^\@]+@.*\.[a-z]{2,6}$/i)) {
				alert_message = '<?php echo str_replace("'", "\'", $settings['moduletext_invalidemail_'.$language]); ?>';
				swal({
					title: alert_message,
					type: "info",
					customClass: 'swal-mailchimp',
				});
				//message.html(alert_message).addClass('attention alert alert-warning').slideDown();
				element.parent().parent().find('a.button').removeAttr('disabled');
			<?php foreach ($default_fields as $field) { ?>
				<?php if (!$email && isset($settings['modules_' . $field]) && $settings['modules_' . $field] == 'required') { ?>
					} else if (!$.trim(element.parent().parent().find('input[name="<?php echo $field; ?>"]').val())) {
						$<?= $field; ?>_alert_message = '<?php echo str_replace("'", "\'", $settings['moduletext_emptyfield_'.$language]); ?>';
						swal({
							title: $<?= $field; ?>_alert_message,
							type: "info",
							customClass: 'swal-mailchimp',
						});
						//message.html($<?= $field; ?>_alert_message).addClass('attention alert alert-warning').slideDown();
						element.parent().parent().find('a.button').removeAttr('disabled');
				<?php } ?>
			<?php } ?>
				
			} else {
				$.ajax({
					type: 'POST',
					url: 'index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/subscribe',
					data: element.parent().parent().find(':input:not(:checkbox,:radio), :checkbox:checked, :radio:checked'),
					beforeSend: function () {
						$('.newsletter-grid input, .newsletter-grid select').prop('disabled', true);
					},
					success: function(error) {
						$('.newsletter-grid input, .newsletter-grid select').prop('disabled', false);

						if (error.indexOf('Use PUT to insert or update list members') != -1) {
							$use_put_to_alert = '<?php echo html_entity_decode($settings['moduletext_subscribed_'.$language], ENT_QUOTES, 'UTF-8'); ?>'.replace('[email]', $('input[name="email"]').val());
							//message.html().addClass('warning alert alert-danger').slideDown();
							swal({
								title: $use_put_to_alert,
								type: "info",
								customClass: 'swal-mailchimp',
							});
						} else if (error) {
							$error_message = <?php echo ($settings['moduletext_error_'.$language]) ? "'" . str_replace("'", "\'", $settings['moduletext_error_'.$language]) . "'" : 'error'; ?>;
							$error_message = $error_message.replace('&bull; ','');
							//message.html($error_message).addClass('warning alert alert-danger').slideDown();
							swal({
								title: $error_message,
								type: "error",
								customClass: 'swal-mailchimp',
							});
						} else {
							var messageText = '<?php echo str_replace("'", "\'", $settings['moduletext_' . ($subscribed && $interest_groups ? 'updated' : 'success') . '_' . $language]); ?>';
							messageText = messageText.replace('/[^a-z0-9]/gi','');
							<?php if ($settings['modules_redirect']) { ?>
								alert(messageText);
								location = '<?php echo $settings['modules_redirect']; ?>';
							<?php } elseif ($popup) { ?>
								alert(messageText);
								$('#mi-modal-overlay, .mailchimp-integration').fadeOut();
							<?php } else { ?>
								//message.html(messageText).addClass('success alert alert-success').slideDown();
								swal({
								title: messageText,
								type: "success",
								customClass: 'swal-mailchimp',
							});
							<?php } ?>
						} 
						element.parent().parent().find('a.button').removeAttr('disabled');
					}
				});
			}
			
		// });
	}
</script>
