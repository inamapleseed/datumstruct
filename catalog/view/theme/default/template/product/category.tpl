  <?php echo $header; ?>
    <?php if ($text['text']): ?>
      <div class="announcement_container min">
        
        <marquee>
          <a class="btn" href="https://www.dsolutions.asia/pesk-curve-ii">
            <p><?=$text['text']; ?>
            </p>
              <i class="fa fa-angle-right" aria-hidden="true"></i>
          </a>
        </marquee>
      </div>        
    <?php endif ?>
  <div class="container" >
    <?php echo $content_top; ?>
    <?php if ($text['text']): ?>
      <div class="announcement_container max">
          <a class="btn" href="https://www.dsolutions.asia/pesk-curve-ii">
            <p><?=$text['text']; ?>
            </p>
              <i class="fa fa-angle-right" aria-hidden="true"></i>
          </a>  
      </div>
    <?php endif ?>
  <!-- Modal -->
  <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        <?php if($mailchimp){ ?>
          <div class="newsletter-section text-center">
            <?= $mailchimp; ?>
          </div>
        <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    <h2><?php echo $heading_title; ?></h2> 

    <div class="row category_products">
    
      <?php echo $column_left; ?>

      <?php if ($column_left && $column_right) { ?>
      <?php $class = 'col-sm-6'; ?>
      <?php } elseif ($column_left || $column_right) { ?>
      <?php $class = 'col-sm-9'; ?>
      <?php } else { ?>
      <?php $class = 'col-sm-12'; ?>
      <?php } ?>

      <div id="content" class="<?php echo $class; ?>">

        <div id="product-filter-replace">
          <div id="product-filter-loading-overlay"></div>
          
            <?php if ($products) { ?>
            
              <?php include_once('sort_order.tpl'); ?>
                
              <div id="product-filter-detect">
                
                <div class="row">
                  <div class="product-view">
                    <?php foreach ($products as $product) { ?>
                      <?php echo $product; ?>
                    <?php } ?>
                  </div>
                </div>

              <!-- <div id="pagination-container"></div> -->
                <div class="col-sm-12 text-center"><?php echo $pagination; ?></div>

              </div> <!-- #product-filter-detect -->

            <?php } ?>

            <?php if (!$products) { ?>
            
              <p><?php echo $text_empty; ?></p>
              <div class="buttons hidden">
                <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
              </div>

            <?php } ?>

        </div> <!-- #product-filter-replace -->
      </div> <!-- #content -->

      <?php echo $column_right; ?></div>
      <?php echo $content_bottom; ?>
  </div>
  <?php echo $footer; ?>

<!--   <script type="text/javascript">
    let items = $(".product-view .product-block");
      let numItems = items.length;
      let perPage = 3;

      items.slice(perPage).hide();

      $('#pagination-container').pagination({
      items: numItems,
      itemsOnPage: perPage,
      hrefTextPrefix: '',
      prevText: "&lt;",
      nextText: "&gt;",
      onPageClick: function (pageNumber) {
          var showFrom = perPage * (pageNumber - 1);
          var showTo = showFrom + perPage;

        $(".simple-pagination a").each(function(e){
          $(this).removeAttr('href');
        });

          items.hide().slice(showFrom, showTo).show();
      }
      });

      $(".simple-pagination a").removeAttr("href");

      $(document).ready(function(){
        $("#pagination-container span").each(function(){
          let prev = $(this).text();
          $(this).text(prev.replace("prev", ""));
        });

        $("#pagination-container a").each(function(){
          let next = $(this).text();
          $(this).text(next.replace("next", ""));
        });
      })
  </script> -->