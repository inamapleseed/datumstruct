<div class="featured-module featured_<?= $uqid; ?>">
  <div class="featured section">
    <div class="table_heading text-center">
      <h2 class="text-center target-heading">
          <?= $heading_title; ?>
      </h2>
    </div>
    <div class="owl-carousel" id="featured_slider_<?= $uqid; ?>">
      <?php foreach ($products as $product) { ?>
        <?= html($product); ?>
      <?php } ?>
    </div>
    <script type="text/javascript">

      $(window).load(function(){
        setTimeout(function () {
          featured_product_carousel<?= $uqid; ?>();
        }, 250)
      });

      function featured_product_carousel<?= $uqid; ?>(){
        var owl<?= $uqid; ?> =  $("#featured_slider_<?= $uqid; ?>").owlCarousel({
          items: 5,
          margin: 30,
          loop: false,
          dots: false,
          nav: true,
          responsive: {
            0: {
              items: 1,
              margin: 0,
            },
            480: {
              items: 2,
              margin: 10,
            },
            768: {
              items: 3,
              margin: 15,
            },
            992:{
              items: 4,
              margin: 15,
            },
            1200: {
              items: 5,
              margin: 20,
            }
          },
          onInitialized: function () {
            $('#featured_slider_<?= $uqid; ?>').addClass('');

            // Fix Looping issue when add to cart
            $('#featured_slider_<?= $uqid; ?> .input-number').each(function(index, value){
                $old=$(this).attr('id');
                $new=$old + 'a' + index;

                $(this).attr('id', $new); // Change add to cart
                $btn_cart = $(this).parents('div.owl-item').find('.btn-cart');

                if($btn_cart.length){
                  $onclick = $btn_cart.attr('onclick');
                  $onclick = $onclick.replace($old, $new);
                  $btn_cart.attr('onclick', $onclick);
                }
            });
            
            // Update add-to-cart
            $(window).resize();

            // Fix if content occupied more than window view
            $slider_window_view_width = $('#featured_slider_<?= $uqid; ?>').width();
            $content_width = 0;
            $content_margin = 0;
            $single_margin = 0;

            $('#featured_slider_<?= $uqid; ?> .owl-item.active').each(function(){
              $content_width += $(this).outerWidth();

              $single_margin = $(this).css('margin-right');
              $single_margin = $single_margin.replace('px','');
              $content_margin += parseFloat($single_margin);
            });

            $content_width += $content_margin;
            $content_width -= $single_margin;
            
            //cl($content_width);
            //cl($slider_window_view_width);

            if($content_width > $slider_window_view_width){
              setTimeout(function(){
                $('#featured_slider_<?= $uqid; ?>').trigger('refresh.owl.carousel');
              }, 100);
            }

          },
          onRefreshed: function(){ 
            $(".featured_<?= $uqid; ?> .owl-item .product-layout .product-thumb").removeAttr("style");

            var height = 0;

            $(".featured_<?= $uqid; ?> .owl-item").each(function(){
              if(height < $(this).height()) height = $(this).height();            
            });

            $(".featured_<?= $uqid; ?> .owl-item .product-layout .product-thumb").css('min-height', height);
          }
        });
      }
    </script>
  </div>
</div>