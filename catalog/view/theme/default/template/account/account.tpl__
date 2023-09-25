<?php echo $header; ?>
<div class="container">
	<br />
	<?php if ($success) { ?>
		<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
	<?php } ?>
	<div class="row"><?php echo $column_left; ?>
		<?php if ($column_left && $column_right) { ?>
			<?php $class = 'col-sm-6'; ?>
			<?php } elseif ($column_left || $column_right) { ?>
			<?php $class = 'col-sm-9'; ?>
			<?php } else { ?>
			<?php $class = 'col-sm-12'; ?>
		<?php } ?>
		<div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
			<div class="row">
				<div class="col-lg-12">
					<h2><?php echo $text_my_account; ?></h2>
					<ul class="customer-account"> 
						<li class=" col-md-2 col-sm-4  col-xs-6"><a href="<?php echo $edit; ?>"><i class="fa fa-pencil-square" aria-hidden="true"></i><hr /><?php echo $text_edit; ?></a></li>
						<li class=" col-md-2 col-sm-4  col-xs-6"><a href="<?php echo $password; ?>"><i class="fa fa-key" aria-hidden="true"></i><hr /><?php echo $text_password; ?></a></li>
						<li class=" col-md-2 col-sm-4  col-xs-6"><a href="<?php echo $address; ?>"><i class="fa fa-book" aria-hidden="true"></i><hr /><?php echo $text_address; ?></a></li>
						<li class=" col-md-2 col-sm-4  col-xs-6"><a href="<?php echo $wishlist; ?>"><i class="fa fa-heart" aria-hidden="true"></i><hr /><?php echo $text_wishlist; ?></a></li>
					</ul>
				</div>
			</div>
			<p>&nbsp;</p>
			
			<?php if ($credit_cards) { ?>
				<div class="row">
					<div class="col-lg-12">
						<ul class="customer-account"><h2><?php echo $text_credit_card; ?></h2>
							<?php foreach ($credit_cards as $credit_card) { ?>
								<li class=" col-md-2 col-sm-4  col-xs-6"><a href="<?php echo $credit_card['href']; ?>"><?php echo $credit_card['name']; ?></a></li>
							<?php } ?>
						</ul>
					</div>
				</div>
			<?php } ?>
			
			
			<div class="row">
				<div class="col-lg-12">
					<h2><?php echo $text_my_orders; ?></h2>
					<ul class="customer-account"> 
						<li class=" col-md-2 col-sm-4  col-xs-6"><a href="<?php echo $order; ?>"><i class="fa fa-shopping-cart" aria-hidden="true"></i><hr /><?php echo $text_order; ?></a></li>
						<li class=" col-md-2 col-sm-4  col-xs-6"><a href="<?php echo $download; ?>"><i class="fa fa-download" aria-hidden="true"></i><hr /><?php echo $text_download; ?></a></li>
						<?php if ($reward) { ?>
							<li class=" col-md-2 col-sm-4  col-xs-6"><a href="<?php echo $reward; ?>"><i class="fa fa-gift" aria-hidden="true"></i><hr /><?php echo $text_reward; ?></a></li>
						<?php } ?>
						<li class=" col-md-2 col-sm-4  col-xs-6"><a href="<?php echo $return; ?>"><i class="fa fa-retweet" aria-hidden="true"></i><hr /><?php echo $text_return; ?></a></li>
						<li class=" col-md-2 col-sm-4  col-xs-6"><a href="<?php echo $transaction; ?>"><i class="fa fa-money" aria-hidden="true"></i><hr /><?php echo $text_transaction; ?></a></li>
						<li class=" col-md-2 col-sm-4  col-xs-6"><a href="<?php echo $recurring; ?>"><i class="fa fa-repeat" aria-hidden="true"></i><hr /><?php echo $text_recurring; ?></a></li>
					</ul>
				</div>
			</div>
			
			<p>&nbsp;</p>
			<div class="row">
				<div class="col-lg-12">
					<h2><?php echo $text_my_newsletter; ?></h2>
					<ul class="customer-account"> 
						<li class=" col-md-2 col-sm-4  col-xs-6"><a href="<?php echo $newsletter; ?>"><i class="fa fa-newspaper-o" aria-hidden="true"></i><hr /><?php echo $text_newsletter; ?></a></li>
					</ul>
				</div>
			</div>
			
		<?php echo $content_bottom; ?></div>
	<?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?> 