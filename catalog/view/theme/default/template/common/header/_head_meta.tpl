<head>

	<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="robots" content="index,follow" />
	<link rel="canonical" href="<?= $actual_link; ?>" />

	<title data-min-length="60" data-max-length="70"><?=$title; ?></title>

	<base href="<?=$base; ?>" />

	<link rel="preload" href="<?= $logo; ?>" as="image">

	<?php if(isset($extra_tags)) { foreach($extra_tags as $extra_tag) { ?>
		<meta <?php echo ($extra_tag[ 'name']) ? 'name="' . $extra_tag[ 'name'] . '" ' : ''; ?>
		<?php echo (!in_array($extra_tag['property'], array("noprop", "noprop1", "noprop2", "noprop3", "noprop4"))) ? 'property="' . $extra_tag['property'] . '" ' : ''; ?> content="
		<?php echo addslashes($extra_tag['content']); ?>" />
	<?php } } ?>

	<?php if ($description) { ?>
	<meta name="description" content="<?=$description; ?>" data-max-length="155" />
	<?php } ?>

	<?php if ($keywords) { ?>
	<meta name="keywords" content="<?=$keywords; ?>" />
	<?php } ?>

	<!-- Schema.org markup for Google+ -->
	<meta itemprop="name" content="<?=$title; ?>" />
	<meta itemprop="description" content="<?=$description; ?>" />
	<meta itemprop="image" content="<?= $gp_img; ?>" />

	<!-- Twitter Card data -->
	<meta name="twitter:card" content="<?= $store_name; ?>" />
	<!-- <meta name="twitter:site" content="@publisher_handle"/> -->
	<meta name="twitter:title" content="<?=$title; ?>" />
	<meta name="twitter:description" content="<?=$description; ?>" data-length="200" />
	<!-- <meta name="twitter:creator" content="@author_handle"/> -->
	<!-- Twitter summary card with large image must be at least 280x150px -->
	<meta name="twitter:image:src" content="<?= $tw_img; ?>" />

	<!-- Open Graph data -->
	<meta property="og:title" content="<?=$title; ?>" />
	<meta property="og:type" content="<?= $content_type; ?>" />
	<meta property="og:url" content="<?= $current_page; ?>" />
	<meta property="og:image" content="<?= $fb_img; ?>" />
	<meta property="og:description" content="<?=$description; ?>" />
	<meta property="og:site_name" content="<?= $store_name; ?>" />
	<!-- <meta property="fb:admins" content="Facebook numberic ID" /> -->
	

	<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
	<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
	<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
	<link href="catalog/view/javascript/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
	<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
	<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
	<link href="catalog/view/theme/default/stylesheet/sass/mystylesheet.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Poppins:300&display=swap" rel="stylesheet">


	<!-- <link href="catalog/view/javascript/smartmenus/sm-core-css.min.css" rel="stylesheet"> --> <!--Added in sidr_bare_sm_core_css_sass_icon.css -->
	<link href="catalog/view/javascript/smartmenus/sm-blue.min.css" rel="stylesheet">
	<!-- <link href="catalog/view/javascript/side-menu-sidr/stylesheets/sidr.bare.min.css" rel="stylesheet"> --> <!--Added in sidr_bare_sm_core_css_sass_icon.css -->
	<link href="catalog/view/javascript/jquery-multi-level-accordion-menu/css/style.min.css" rel="stylesheet">

	<link href="catalog/view/theme/default/stylesheet/normalize.min.css" rel="stylesheet">
	<!-- <link href="catalog/view/theme/default/stylesheet/sass/icon.min.css" rel="stylesheet"> --> <!--Added in sidr_bare_sm_core_css_sass_icon.css -->
	<link href="catalog/view/theme/default/stylesheet/sidr_bare_sm_core_css_sass_icon.css" rel="stylesheet">
	<link href="catalog/view/theme/default/stylesheet/sass/helper.min.css" rel="stylesheet">
	<link href="catalog/view/theme/default/stylesheet/sass/custom.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.min.css">
	<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
		
	<?php foreach ($styles as $style) { ?>
		<link href="<?=$style['href']; ?>" type="text/css" rel="<?=$style['rel']; ?>" media="<?=$style['media']; ?>" />
	<?php } ?>

	<link href="catalog/view/theme/default/stylesheet/animate.min.css" rel="stylesheet">

	<script src="catalog/view/javascript/jquery-ui-1.12.1.custom/jquery-ui.min.js" type="text/javascript"></script>
	<script src="catalog/view/javascript/jquery.ui.touch-punch.min.js" type="text/javascript"></script>
	<script src="catalog/view/javascript/side-menu-sidr/jquery.sidr.min.js" type="text/javascript"></script> <!--Minified-->
	<script src="catalog/view/javascript/side-menu-sidr/sidr.min.js" type="text/javascript"></script>
	<script src="catalog/view/javascript/jquery-multi-level-accordion-menu/js/modernizr.js" type="text/javascript"></script>
	<!-- <script src="catalog/view/javascript/jquery-multi-level-accordion-menu/js/main.js" type="text/javascript"></script> --> <!--Added in Alpha.js -->
	<script src="catalog/view/javascript/smartmenus/jquery.smartmenus.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
<script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
				
<script type="text/javascript" src="https://cdn.rawgit.com/botmonster/jquery-bootpag/master/lib/jquery.bootpag.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.6/jquery.simplePagination.js"></script>

	<script src="catalog/view/javascript/bluebird.min.js"></script> <!--FOR IE-->
	<link href="catalog/view/javascript/sweetalert2.min.css" rel="stylesheet">
	<script src="catalog/view/javascript/sweetalert2.all.min.js"></script>

	<link href="catalog/view/javascript/aos/aos.css" rel="stylesheet">
	<script src="catalog/view/javascript/aos/aos.js" type="text/javascript"></script>

	<!-- <script src="catalog/view/javascript/quantityincrementdecrement.js" type="text/javascript"></script> --> <!--Added in Alpha.js -->
	<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
	<script src="catalog/view/javascript/enquiry.js" type="text/javascript"></script>
	<script src="catalog/view/javascript/alpha.js" type="text/javascript"></script>

	<?php foreach ($links as $link) { ?>
		<link href="<?=$link['href']; ?>" rel="<?=$link['rel']; ?>" />
	<?php } ?>

	<?php foreach ($scripts as $script => $defer) { ?>
	<script <?php if($defer){ ?>
		defer = "defer"
	<?php } ?>
		src = "<?=$script; ?>"
		type = "text/javascript" >
	</script>
	<?php } ?>

	<?php foreach ($analytics as $analytic) { ?>
		<?=$analytic; ?>
	<?php } ?> 

	<script type="application/ld+json"><?= html($schema_json_code); ?></script>
	<?= $fb_pixel; ?>
	<style>
		body.loading #loading_wrapper { opacity:1;visibility: visible; }
		#loading_wrapper { -webkit-transition: all 0.6s ease-out;-moz-transition: all 0.6s ease-out ;-ms-transition: all 0.6s ease-out ;-o-transition: all 0.6s ease-out ;transition: all 0.6s ease-out ;display: block;opacity: 0;visibility: hidden;position:fixed;  z-index:10000000001;top:        0;left:0;height:100%;width:100%;background: rgba(51, 51, 51, 0.7); }

		.spinner {margin: auto;width: 40px;height: 40px;position: absolute;text-align: center;-webkit-animation: sk-rotate 2.0s infinite linear;animation: sk-rotate 2.0s infinite linear;left: 0;right: 0;top: 0;bottom: 0;}

		.dot1, .dot2 {width: 60%;height: 60%;display: inline-block;position: absolute;top: 0;background-color: #750a00;border-radius: 100%;-webkit-animation: sk-bounce 2.0s infinite ease-in-out;animation: sk-bounce 2.0s infinite ease-in-out;}

		.dot2 {top: auto;bottom: 0;-webkit-animation-delay: -1.0s;animation-delay: -1.0s;}

		@-webkit-keyframes sk-rotate { 100% { -webkit-transform: rotate(360deg) }}
		@keyframes sk-rotate { 100% { transform: rotate(360deg); -webkit-transform: rotate(360deg) }}

		@-webkit-keyframes sk-bounce {
		0%, 100% { -webkit-transform: scale(0.0) }
		50% { -webkit-transform: scale(1.0) }
		}

		@keyframes sk-bounce {
		0%, 100% {
		transform: scale(0.0);
		-webkit-transform: scale(0.0);
		} 50% {
		transform: scale(1.0);
		-webkit-transform: scale(1.0);
		}
		}
	</style>
	<div id="fb-root"></div>
	<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v5.0"></script>
	
	<!-- GetButton.io widget -->
    <!--<script type="text/javascript">
        (function () {
            var options = {
                whatsapp: "+65 6546 9874", // WhatsApp number
                call_to_action: "", // Call to action
                position: "right", // Position may be 'right' or 'left'
            };
            var proto = document.location.protocol, host = "getbutton.io", url = proto + "//static." + host;
            var s = document.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = url + '/widget-send-button/js/init.js';
            s.onload = function () { WhWidgetSendButton.init(host, proto, options); };
            var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(s, x);
        })();
    </script>-->
    <!-- /GetButton.io widget -->
</head>