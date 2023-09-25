<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?=$direction; ?>" lang="<?=$lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?=$direction; ?>" lang="<?=$lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?=$direction; ?>" lang="<?=$lang; ?>">
<!--<![endif]-->

<?= $head_tags ?>

<body class="<?=$class; ?> <?= $seo_enabled?'short_hand':''; ?> <?= $isMobile; ?>">

	<div id="loading_wrapper">
		<div class="spinner">
			<div class="dot1"></div>
			<div class="dot2"></div>
		</div>
	</div>

	<?= $fb_messanger; ?>
	<div class="x213"><h1 id="page_heading_title" ><?= $title; ?></h1></div>
	<header class="fixed-header">
		<div class="contain">
			
			<div class="header-container">
				<div class="header-mobile-links visible-xs visible-sm">
					<div class="header-links">
						<a id="mobileNav" href="#sidr" class="pointer esc">
							<i class="fa fa-bars"></i>
						</a>
					</div>
				</div>

				<div class="header-logo">
					<?php if ($logo) { ?>
						<a class="header-logo-image" href="<?=$home; ?>">
							<img src="<?=$logo; ?>" title="<?=$name; ?>" alt="<?=$name; ?>" class="default img-responsive" />
							<img src="image/catalog/ds/homepage/DS_black.png" title="<?=$name; ?>" alt="<?=$name; ?>" class="onscroll img-responsive" />
						</a>
					<?php } else { ?>
						<a class="header-logo-text" href="<?=$home; ?>"><?=$name; ?></a>
					<?php } ?>
				</div>

				<div class="header-top"> <!-- block -->

					<div class="authmark hidden-xs hidden-sm">
						<div class="authmark-inner">
							<h3>Authorized Dealer</h3>
							<h4>Humanscale<sup>&reg;</sup></h4>
						</div>	
					</div>

					<div class="header-menu-outer">
						<div class="header-menu hidden-xs hidden-sm">
							<?= $menu; ?>
						</div>

						<div class="header-top-inner">
							<div class="search_container">
								<span class="hidden-xs hidden-sm">
									<?php $search; ?>
									<?= $pop_up_search; ?>
								</span>
							</div>

							<span class="hidden-xs hidden-sm">
								<?= $login_part; ?>
							</span>
							<?= $enquiry; ?>
							<?= $cart; ?>
						</div>
					</div>
				</div>
				<span class="hidden" >
					<?=$currency; ?>
					<?=$language; ?>
					<?=$wishlist; ?>
				</span>
			</div>
		</div>
		<?= $menu_cat_sub; ?>
	</header>

	<div id="sidr">
		<div class="header-mobile">
			<div class="mobile-account relative">
				<?php if($logged){ ?> 
					<a href="<?= $account; ?>">
						<i class="fa fa-user-circle-o" aria-hidden="true"></i>
						<?= $text_account; ?>
					</a>
					<a href="<?= $logout; ?>">
						<i class="fa fa-sign-out" aria-hidden="true"></i>
						<?= $text_logout; ?>
					</a>
				<?php } else { ?> 
					<a href="<?= $login; ?>">
						<i class="fa fa-user-circle-o" aria-hidden="true"></i>
						<?= $text_login; ?>
					</a>
					<a href="<?= $register; ?>">
						<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
						<?= $text_register; ?>
					</a>
				<?php } ?>
			</div>
			<div class="mobile-search">
				<?= $search; ?>
			</div>
		</div>
		<?= $mobile_menu; ?>
	</div>


<?= $page_banner; ?>

<script type="text/javascript">
  $(window).scroll(function () {
    if ($(this).scrollTop() > 20) {
      $('.fixed-header').addClass('nav_scroll');
    } else {
      $('.fixed-header').removeClass('nav_scroll');
    }
  });
</script>