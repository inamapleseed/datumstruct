<?php if($uqid == '31_') : ?>
  <div class="hs-bg" style="background-image: url('image/<?=$humanscale['bg'];?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <div class="humanscale-container">
      <div class="humanscale-container-inner">
        <div class="humanscale-texts">
          <h3><?=$humanscale['btitle'];?><sup>&reg;</sup></h3>
          <h4><?=$humanscale['stitle'];?></h4>
        </div>
        <div class="humanscale-products">
          <?php foreach ($products as $product) { ?>
            <?= html($product); ?>
          <?php } ?>
        </div>
      </div>
    </div>    
  </div>

<?php else : ?>
  <div class="featured-module featured_<?= $uqid; ?>" data-aos="fade-right">
    <h3 class="text-center target-heading">

    <hr>
      <?= $heading_title; ?>
    </h3>
    <div class="featured section relative" style="opacity: 0;">
      <div id="featured_slider_<?= $uqid; ?>" >
        <?php foreach ($products as $product) { ?>
          <?= html($product); ?>
        <?php } ?>
      </div>
    </div>
  </div>  
<?php endif; ?>

<script type="text/javascript">

      $(window).load(function(){
        setTimeout(function () {
          featured_product_slick<?= $uqid; ?>();
          AOS.init();
        }, 250);
      });

      function featured_product_slick<?= $uqid; ?>(){
        $("#featured_slider_<?= $uqid; ?>").on('init', function (event, slick) {
          $('#featured_slider_<?= $uqid; ?>').parent().removeAttr('style');
        });

        $(".humanscale-products").on('init', function (event, slick) {
          $('.humanscale-products').parent().removeAttr('style');
        });

      $('.humanscale-products').slick({
        dots: false,
        arrows: true,
        autoplay: true,
        speed: 300,
        slidesToShow: 3,
        responsive: [
        {
          breakpoint: 1023,
          settings: {
            slidesToShow: 2,
          }
        },
        {
          breakpoint: 767,
          settings: {
            slidesToShow: 1,
          }
        }
        ],
          prevArrow: "<div style='left: -1em; height: 100%;' class='pointer slick-nav left prev absolute'><div class='absolute position-center-center'><i class='fa fa-chevron-left fa-2em'></i></div></div>",
          nextArrow: "<div style='right: -1em; height: 100%;' class='pointer slick-nav right next absolute'><div class='absolute position-center-center'><i class='fa fa-chevron-right fa-2em'></i></div></div>",
        });

        $("#featured_slider_<?= $uqid; ?>").slick({
          dots: false,
          infinite: true,
          autoplay: false,
          centerMode: false,
          centerPadding: '60px',
          speed: 300,
          slidesToShow: 4,
          slidesToScroll: 1,
          responsive: [
            {
              breakpoint: 1199,
              settings: {
                slidesToShow: 3,
              }
            },
            {
              breakpoint: 991,
              settings: {
                slidesToShow: 3,
              }
            },
            {
              breakpoint: 767,
              settings: {
                slidesToShow: 2,
              }
            }
          ],
          prevArrow: "<div class='pointer slick-nav left prev absolute'><div class='absolute position-center-center'><i class='fa fa-chevron-left fa-2em'></i></div></div>",
          nextArrow: "<div class='pointer slick-nav right next absolute'><div class='absolute position-center-center'><i class='fa fa-chevron-right fa-2em'></i></div></div>",
        });

        
      }
</script>
