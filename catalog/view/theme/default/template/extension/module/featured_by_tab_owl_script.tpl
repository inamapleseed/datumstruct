
  <script type="text/javascript">

    $(window).load(function(){
      if($('.fcategory-tab.max-offset').length){
        setTimeout(function(){
          <?php foreach ($tabs as $index => $tab) { ?>
            featured_product_carousel(<?= $index; ?>);
          <?php } ?>
        }, 250);
      }else{
        <?php foreach ($tabs as $index => $tab) { ?>
          featured_product_carousel(<?= $index; ?>);
        <?php } ?>
      }
    });

    function featured_product_carousel($index){
      var owl<?= $uqid; ?> =  $("#fc_tab_slider_<?= $uqid; ?>-"  + $index).owlCarousel({
        items: 4,
        margin: 0,
        loop: true,
        dots: false,
        nav: true,
        navText: [
                  '<div class="standard_nav_left owl_position left_'+$index+' pointer"></div>', 
                  '<div class="standard_nav_right owl_position right_'+$index+' pointer"></div>'
                ],
        responsive: {
          0: {
            items: 1,
            margin: 0,
          },
          480: {
            items: 2,
            margin: 0,
          },
          768: {
            items: 3,
            margin: 5,
          },
          992:{
            items: 4,
            margin: 5,
          },
          1200: {
            items: 4,
            margin: 0,
          }
        },
        onInitialized: function () {
          $('#featured_slider_<?= $uqid; ?>-'+$index).addClass('');

          // Fix Looping issue when add to cart
          $('#featured_slider_<?= $uqid; ?>'+$index+' .input-number').each(function(index, value){
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
          $slider_window_view_width = $('#featured_slider_<?= $uqid; ?>-'+$index).width();
          $content_width = 0;
          $content_margin = 0;
          $single_margin = 0;

          $('#featured_slider_<?= $uqid; ?>-'+$index+' .owl-item.active').each(function(){
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
              $('#featured_slider_<?= $uqid; ?>-'+$index).trigger('refresh.owl.carousel');
            }, 100);
          }

        },
        onRefreshed: function(){ 
          $("#fc_tab_slider_<?= $uqid; ?>"+$index+" .owl-item .product-layout .product-thumb").removeAttr("style");

          var height = 0;

          $("#fc_tab_slider_<?= $uqid; ?> .owl-item").each(function(){
            if(height < $(this).height()) height = $(this).height();            
          });

          $("#fc_tab_slider_<?= $uqid; ?>-"+$index+" .owl-item .product-layout .product-thumb").css('min-height', height);
        }
      });
      var owl<?= $uqid; ?> =  $("#fc_tab_slider_<?= $uqid; ?>-"  + $index + '_m').owlCarousel({
        items: 1,
        margin: 0,
        loop: false,
        dots: false,
        nav: false,
        autoHeight: true
      });
    }
  </script>