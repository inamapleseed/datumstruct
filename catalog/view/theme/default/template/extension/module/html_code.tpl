
	<?php if( $mode == 'box' ) { ?>
		<div class="box html-code-box">
			<?php if( $header ) { ?>
				<h3><span><?php echo $header; ?></span></h3>
			<?php } ?>
			<div class="box-content html-code-content">
				<?php echo $description; ?>
			</div>
		</div>
	<?php } else { ?>
		<?php echo $description; ?>
	<?php } ?>
