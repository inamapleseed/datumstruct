<div id="enquiry" class="relative slide-out-cart" > <!-- add/remove class slide-out-cart for normal opencart cart dropdown-->
    <a data-toggle="dropdown" class="cart-dropdown pointer" id="enquiry_dropdown_icon" onclick="$('body, #enquiry').toggleClass('open-custom');" >
      <i class="fa fa-file-text" aria-hidden="true"></i>
<!--       <span class="badge" >
        <span id="enquiry-quantity-total" ><?= $total_item; ?></span>
      </span> -->
    </a>

    <ul class="dropdown-menu pull-right"  >
      
      <div class="cart-header">
        <div class="cart-header-text"><?= $text_my_cart; ?></div>
        <button type="button" class="pointer cart_close" onclick="$('#enquiry_dropdown_icon').click(); return false;" ></button>
      </div>

      <?php if ($products) { ?>

      <li class="cart-dorpdown-indicator" >
         <span id="cart-total"><?= $text_items; ?></span>
      </li>

      <li class="cart-dorpdown-items">
        <?php foreach ($products as $product) { ?>
          <div class="item">

              <a href="<?= $product['href']; ?>">
                <img src="<?= $product['thumb']; ?>" alt="<?= $product['name']; ?>" title="<?= $product['name']; ?>" class="img-thumbnail" />
              </a>

              <div class="item-details">
                <button type="button" onclick="enquiry.remove('<?= $product['enquiry_id']; ?>');" title="<?= $button_remove; ?>" class="btn btn-danger no-custom pull-right">
                  <i class="fa fa-times"></i>
                </button> 

                <a class="item-name" href="<?= $product['href']; ?>"><?= $product['name']; ?></a>
                <div class="item-option">

                  <?php if ($product['option']) { ?>
                    <?php foreach ($product['option'] as $option) { ?>
                      <small><?= $option['name']; ?> <?= $option['value']; ?></small><br />
                    <?php } ?>

                  <?php } ?>
                  
                  <?php if ($product['recurring']) { ?>
                    <br/><small><?= $text_recurring; ?> <?= $product['recurring']; ?></small>
                  <?php } ?>

                </div>
                <div class="cart-dorpdown-item-charges">
                  <span>
                    <span><?= $text_quantity; ?></span><span>:</span> <span><?= $product['quantity']; ?></span>
                  </span>
                  <span class="hidden" >
                    <span><?= $text_price; ?></span><span>:</span> <span><?= $product['total']; ?></span>
                  </span>
                </div>
              </div>
            

          </div>
        <?php } ?>
      </li>
      <li class="cart-dropdown-order-totals" >
          <table class="table table-bordered">
            <?php foreach ($totals as $total) { ?>
            <tr>
              <td class="text-right"><strong><?= $total['title']; ?></strong></td>
              <td class="text-right"><?= $total['text']; ?></td>
            </tr>
            <?php } ?>
          </table>
      </li>
      <li class="cart-dorpdown-footer" >
          <a href="<?= $cart; ?>" class="btn btn-default"><?= $text_cart; ?></a>
          <a href="<?= $checkout; ?>" class="btn btn-primary"><?= $text_checkout; ?></a>
      </li>
      <?php } else { ?>
      <li class="cart-dropdown-empty text-center" >
        <i class="fa fa-meh-o" aria-hidden="true"></i>
        <p><?= $text_empty; ?></p>
      </li>
      <?php } ?>
    </ul>
</div>
