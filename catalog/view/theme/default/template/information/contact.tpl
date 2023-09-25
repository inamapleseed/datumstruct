<?= $header; ?>
<div class="container" data-aos="fade-down-left">
	<?= $content_top; ?>

	<div class="row"><?= $column_left; ?>
		<?php if ($column_left && $column_right) { ?>
			<?php $class = 'col-sm-6'; ?>
			<?php } elseif ($column_left || $column_right) { ?>
			<?php $class = 'col-sm-9'; ?>
			<?php } else { ?>
			<?php $class = 'col-sm-12'; ?>
		<?php } ?>
		<div id="content" class="<?= $class; ?>">
			<h2><?= $heading_title; ?></h2>
			<div class="inner-content">
				<div class="panel panel-default">
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3988.73968948207!2d103.967596114754!3d1.3323160990282792!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31da3cd1a8f9a10d%3A0x7106afebe249406!2sDatumstruct%20(S)%20Pte%20Ltd!5e0!3m2!1sen!2sph!4v1572709218431!5m2!1sen!2sph" width="450" height="250" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
					<div class="panel-body">
						<div class="row contact_details">
							<div class="ad">
								<div class="ad_icon">
									<img src="image/catalog/ds/contact/address-min.png">
								</div>	
								<address>
									<?= $address; ?>
								</address>
							</div>	
							<div class="tel">
								<img src="image/catalog/ds/contact/phone-min.png">
								<a href="tel:<?= $store_telephone; ?>" alt="<?= $store_telephone;?>" title="<?= $store_telephone;?>">
									<?= $store_telephone; ?>
								</a>
								<?php if ($fax) { ?>
									<a href="fax:<?= $fax; ?>" alt="<?= $fax; ?>" title="<?= $fax; ?>" ><?= $fax; ?></a>
								<?php } ?>
							</div>
							<div class="mail">	
								<img src="image/catalog/ds/contact/mail-min.png">
								<a href="mailto:<?=$store_email;?>">
									<?=$store_email;?>
								</a>
							</div>
						</div>
					</div>
				</div>

				<form action="<?= $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
					<div class="contact-body">
						<div class="form-group required">
							<input type="text" name="name" value="<?= $name; ?>" id="input-name" class="form-control" placeholder="<?= $entry_name; ?>" />
							<?php if ($error_name) { ?>
								<div class="text-danger"><?= $error_name; ?></div>
							<?php } ?>								
						</div>
					
						<div class="form-group required">
							<input type="text" name="email" value="<?= $email; ?>" id="input-email" class="form-control" placeholder="<?= $entry_email; ?>" />
							<?php if ($error_email) { ?>
								<div class="text-danger"><?= $error_email; ?></div>
							<?php } ?>
						</div>
					
						<div class="form-group required">
							<input type="tel" name="telephone" value="<?= $telephone; ?>" id="input-telephone" class="form-control input-number" placeholder="<?= $entry_telephone; ?>" />
							<?php if ($error_telephone) { ?>
								<div class="text-danger"><?= $error_telephone; ?></div>
							<?php } ?>
						</div>

						<div class="form-group required">
							<input type="text" name="subject" value="<?= $subject; ?>" id="input-subject" class="form-control" placeholder="<?= $entry_subject; ?>" />
							<?php if ($error_subject) { ?>
								<div class="text-danger"><?= $error_subject; ?></div>
							<?php } ?>
						</div>

						<div class="form-group required">
							<textarea name="enquiry" rows="10" id="input-enquiry" class="form-control" placeholder="<?= $entry_enquiry; ?>"><?= $enquiry; ?></textarea>
							<?php if ($error_enquiry) { ?>
								<div class="text-danger"><?= $error_enquiry; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="contact-footer">
						<?= $captcha; ?>
						<input class="btn btn-primary pull-sm-right" type="submit" value="<?= $button_submit; ?>" />
					</div>
				</form>
			</div>	
		</div>
	<?= $column_right; ?></div>
	<?= $content_bottom; ?>
</div>
<?= $footer; ?>
