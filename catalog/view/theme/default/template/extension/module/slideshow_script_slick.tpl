<script defer="defer" type="text/javascript">
    $('#slideshow<?= $module; ?>').slick({
        infinite: true,

        speed: 600, // Transition duration or in css: transition: all 0.6s ease
        fade: true,

        arrows: <?= $arrows; ?>,
        prevArrow: '<div class="pointer absolute position-top-left h100 slider-nav slider-nav-left hover-show"></div>',
        nextArrow: '<div class="pointer absolute position-top-right h100 slider-nav slider-nav-right hover-show"></div>',

        dots: <?= $dots; ?>,
        dotsClass: 'slider-dots slider-custom-dots absolute position-bottom-left w100 list-inline text-center',
    
    <?php if ($autoplayspeed > 0) { ?>
        autoplay: true,
            autoplaySpeed: <?= $autoplayspeed; ?>,
    <?php } ?>
        draggable: true,
            swipe: true,
                touchThreshold: 99, // Smaller for shorter swipe length (1/touchThreshold)
  });
</script>