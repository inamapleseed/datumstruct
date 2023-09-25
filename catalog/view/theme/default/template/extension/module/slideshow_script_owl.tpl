<script type="text/javascript">
    // Note.. it supports Animate.css
    // Manual Slider don't support animate css
    $('#slideshow<?= $module; ?>').owlCarousel({
        items: 1,
        <?php if (count($banners) > 1) { ?>
            loop: true,
        <?php } ?>

        autoplay: true,
        autoplayTimeout: 5000,
        
        smartSpeed: 450,
        
        nav: false,
        
        dots: false,
    });
</script>