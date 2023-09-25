<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
	<head>
		<meta charset="UTF-8" />
		<title><?php echo $title; ?></title>
		<base href="<?php echo $base; ?>" />
		<?php if ($admin_icon) { ?>
			<link rel="icon" href="../image/<?php echo $admin_icon; ?>" />
		<?php } ?>
		<?php if ($description) { ?>
			<meta name="description" content="<?php echo $description; ?>" />
		<?php } ?>
		<?php if ($keywords) { ?>
			<meta name="keywords" content="<?php echo $keywords; ?>" />
		<?php } ?>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
		<!-- <script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script> -->
		<script type="text/javascript" src="view/template/enhancement/js/jquery/jquery-2.2.4.min.js"></script>
		
		<?php if (isset($pim_status) && $pim_status) {?>
			<!-- Power Image Manager -->
			<link rel="stylesheet" href="view/javascript/jquery/jquery-ui-1.11.4.custom/jquery-ui.css"/>
			<script src="view/javascript/jquery/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
			<script type="text/javascript" src="view/javascript/pim/pim.min.js"></script>
			<link rel="stylesheet" type="text/css" media="screen" href="view/stylesheet/pim/pim.min.css">
			<link rel="stylesheet" type="text/css" media="screen" href="view/stylesheet/pim/theme.css">
			<?php if ($lang) { ?>
				<script type="text/javascript" src="view/javascript/pim/i18n/<?php echo $lang;?>.js"></script>
			<?php } ?>
			<!-- Power Image Manager -->
		<?php } ?>
		
		<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
		<link href="view/stylesheet/bootstrap.css" type="text/css" rel="stylesheet" />
		<!-- <link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" /> -->
		<link href="view/template/enhancement/js/font-awesome-4.7.0/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
		<script src="view/javascript/jquery/datetimepicker/moment.js" type="text/javascript"></script>
		<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
		<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
		<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
		<link type="text/css" href="view/stylesheet/custom-theme.css" rel="stylesheet" media="screen" />
		<?php foreach ($styles as $style) { ?>
			<link type="text/css" href="<?php echo $style['href']; ?>" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
		<?php } ?>
		<?php foreach ($links as $link) { ?>
			<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
		<?php } ?>
		<script src="view/javascript/common.js" type="text/javascript"></script>
		<?php foreach ($scripts as $script => $delay) { ?>
			<script type="text/javascript" src="<?php echo $script; ?>" <?= $delay?'defer="defer"':''; ?> ></script>
		<?php } ?>
		
		<?php if($cke_page == 1 && $enable_ckeditor == 1) { ?>
			<?php if ($ckeditor_mode == 'advanced') { ?>
				<script type="text/javascript" src="view/template/enhancement/js/ckeditor/ckeditor_enhanced.js"></script> 
				<?php } else { ?>
				<script type="text/javascript" src="view/template/enhancement/js/ckeditor/ckeditor.js"></script> 
			<?php } ?>	
			<script type="text/javascript"><!--
				CKEDITOR.dtd.$removeEmpty.span = false;
				CKEDITOR.dtd.$removeEmpty.i = false;
			//--></script>
			<style>
				.cke_eval{z-index: 99999 !important}
				a.cke_button {cursor: pointer!important;}
				.cke_button__image {
				background-color: #B0D591!important;
				}
				.cke_top {background: #EDEDED linear-gradient(to bottom, #ffffff, #EDEDED) repeat scroll 0 0!important;border-bottom: 1px solid #b6b6b6;box-shadow: 0 1px 0 #fff inset; padding: 6px 8px 2px;white-space: normal;}
				.cke_bottom {background: #EDEDED linear-gradient(to bottom, #ffffff, #EDEDED) repeat scroll 0 0!important;border-top: 1px solid #bfbfbf;box-shadow: 0 1px 0 #fff inset;padding: 6px 8px 2px;position: relative;}
			</style>
		<?php } ?>
		
	</head>
	<body>
		<div id="container">
			<header id="header" class="navbar navbar-static-top">
				<div class="navbar-header">
					<?php if ($logged) { ?>
						<a type="button" id="button-menu" class="pull-left"><i class="fa fa-indent fa-lg"></i></a>
					<?php } ?>
				<a href="<?php echo $home; ?>" class="navbar-brand">
					<?php if($admin_logo){ ?>
						<img src="../image/<?php echo $admin_logo; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" style="max-height: 22px;" />
					<?php }else{ ?>
						<img src="view/image/logo.png" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" />
					<?php } ?>
				</a>
			</div>
				<?php if ($logged) { ?>
					<ul class="nav pull-right">
						<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><?php if($alerts > 0) { ?><span class="label label-danger pull-left"><?php echo $alerts; ?></span><?php } ?> <i class="fa fa-bell fa-lg"></i></a>
							<ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
								<li class="dropdown-header"><?php echo $text_order; ?></li>
								<li><a href="<?php echo $processing_status; ?>" style="display: block; overflow: auto;"><span class="label label-warning pull-right"><?php echo $processing_status_total; ?></span><?php echo $text_processing_status; ?></a></li>
								<li><a href="<?php echo $complete_status; ?>"><span class="label label-success pull-right"><?php echo $complete_status_total; ?></span><?php echo $text_complete_status; ?></a></li>
								<li><a href="<?php echo $return; ?>"><span class="label label-danger pull-right"><?php echo $return_total; ?></span><?php echo $text_return; ?></a></li>
								<li class="divider"></li>
								<li class="dropdown-header"><?php echo $text_customer; ?></li>
								<li><a href="<?php echo $online; ?>"><span class="label label-success pull-right"><?php echo $online_total; ?></span><?php echo $text_online; ?></a></li>
								<li><a href="<?php echo $customer_approval; ?>"><span class="label label-danger pull-right"><?php echo $customer_total; ?></span><?php echo $text_approval; ?></a></li>
								<li class="divider"></li>
								<li class="dropdown-header"><?php echo $text_product; ?></li>
								<li><a href="<?php echo $product; ?>"><span class="label label-danger pull-right"><?php echo $product_total; ?></span><?php echo $text_stock; ?></a></li>
								<li><a href="<?php echo $review; ?>"><span class="label label-danger pull-right"><?php echo $review_total; ?></span><?php echo $text_review; ?></a></li>
								<li class="divider"></li>
								<li class="dropdown-header"><?php echo $text_affiliate; ?></li>
								<li><a href="<?php echo $affiliate_approval; ?>"><span class="label label-danger pull-right"><?php echo $affiliate_total; ?></span><?php echo $text_approval; ?></a></li>
							</ul>
						</li>
						
						<?php if(count($stores) > 1){ ?> 
						<li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-home fa-lg"></i></a>
							<ul class="dropdown-menu dropdown-menu-right">
								<li class="dropdown-header"><?php echo $text_store; ?></li>
								<?php foreach ($stores as $store) { ?>
									<li><a href="<?php echo $store['href']; ?>" target="_blank"><?php echo $store['name']; ?></a></li>
								<?php } ?>
							</ul>
						</li>
						<?php }else{ ?>
						<li><a href="<?php echo $stores[0]['href']; ?>" target="_blank" title="<?php echo $stores[0]['name']; ?>" ><i class="fa fa-home fa-lg"></i></a></li>
						<?php } ?>
						<li><a href="<?php echo $logout; ?>"><span class="hidden-xs hidden-sm hidden-md"><?php echo $text_logout; ?></span> <i class="fa fa-sign-out fa-lg"></i></a></li>
					</ul>
				<?php } ?>
			</header>
				