<?php if($images){ ?>
<div class="product-image-column">

  <div class="product-image-main-container related relative">
    <?php if($sticker && $sticker['name']){ ?>
        <span 
        class="sticker absolute" 
        style="color: <?= $sticker['color']; ?>; background-color: <?= $sticker['background-color']; ?>">
          <?= $sticker['name']; ?>
        </span>
      <?php } ?>
        
        <?php if($is_out_of_stock){ ?>
        <span 
        class="sticker absolute" 
        style="color:white; background-color:black;">
          Out Of Stock
        </span>
      <?php } ?>    
        <div class="product-image-main">
          <?php foreach($images as $image){ ?>
             <img id="zoom_01" data-zoom-image="<?php echo $image['thumb']; ?>" src="<?= $image['thumb']; ?>" alt="<?= $heading_title; ?>" title="<?= $heading_title; ?>"
            class="main_images pointer" href="<?= $image['popup']; ?>" title="<?= $heading_title; ?>" />
              />
          <?php } ?>
        </div>
  </div>

  <div class="product-image-additional-container related">
    <div class="product-image-additional">
      <?php foreach($additional_images as $image){ ?>
      <img src="<?= $image['thumb']; ?>" alt="<?= $heading_title; ?>" title="<?= $heading_title; ?>" class="pointer selectZoom" />
      <?php } ?>
    </div>
  </div>

  <style type="text/css" >
    .product-image-additional-container .slick-slide {
      margin: 0 5px;
    }
    /* the parent */
    .product-image-additional-container .slick-list {
      margin: 0 -5px;
    }
  </style>
  <script type="text/javascript">
  
   $('.product-image-main').slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: true,
      fade: true,
      infinite: false,
      asNavFor: '.product-image-additional',
      prevArrow: "<div class='pointer slick-nav left prev'><div class='absolute position-center-center'><i class='fa fa-chevron-left fa-2em'></i></div></div>",
      nextArrow: "<div class='pointer slick-nav right next'><div class='absolute position-center-center'><i class='fa fa-chevron-right fa-2em'></i></div></div>",
    });

    $('.product-image-additional').slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      asNavFor: '.product-image-main',
      dots: false,
      centerMode: false,
      focusOnSelect: true,
      infinite: false,
      prevArrow: "<div class='pointer slick-nav left prev'><div class='absolute position-center-center'><i class='fa fa-chevron-left'></i></div></div>",
      nextArrow: "<div class='pointer slick-nav right next'><div class='absolute position-center-center'><i class='fa fa-chevron-right'></i></div></div>",
    });

    $(document).ready(function () {
      $('.main_images').magnificPopup({
        type: 'image',
        gallery: {
          enabled: true
        }
      });
    });
  </script>
</div>
<?php } ?>