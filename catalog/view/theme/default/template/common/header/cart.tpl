<div id="cart" class="relative slide-out-cart" > <!-- add/remove class slide-out-cart for normal opencart cart dropdown-->
  <div id="cartwo">
    <a data-toggle="dropdown" class="cart-dropdown pointer" id="cart_dropdown_icon" onclick="$('body, #cart').toggleClass('open-custom');" >
      <i class="fa fa-shopping-cart"></i>
    </a>
    <a class="cart-quantity-total">
      <span id="cart-quantity-total" ><?= $total_item; ?></span>
    </a>  
  </div>
    <ul class="dropdown-menu pull-right"  >
      
      <div class="cart-header">
        <div class="cart-header-text"><?= $text_my_cart; ?></div>
        <button type="button" class="pointer cart_close" onclick="$('#cart_dropdown_icon').click(); return false;" ></button>
      </div>

      <?php if ($products) { ?>

      <li class="cart-dorpdown-indicator" >
        <?php if($free_shipping_indicator){ echo $free_shipping_indicator; }else{ ?>
          <span id="cart-total"><?php echo $text_items; ?></span>
        <?php } ?>
      </li>

      <li class="cart-dorpdown-items">
        <?php foreach ($products as $product) { ?>
          <div class="item">

              <a href="<?php echo $product['href']; ?>">
                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" />
              </a>

              <div class="item-details">
                <button type="button" onclick="cart.remove('<?= $product['cart_id']; ?>');" title="<?= $button_remove; ?>" class="btn btn-danger no-custom pull-right">
                  <i class="fa fa-times"></i>
                </button> 

                <a class="item-name" href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                <div class="item-option">

                  <?php if ($product['option']) { ?>
                    <?php foreach ($product['option'] as $option) { ?>
                      <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br />
                    <?php } ?>

                  <?php } ?>
                  
                  <?php if ($product['recurring']) { ?>
                    <br/><small><?php echo $text_recurring; ?> <?php echo $product['recurring']; ?></small>
                  <?php } ?>

                </div>
                <div class="cart-dorpdown-item-charges">
                  <span>
                    <span><?= $text_quantity; ?></span><span>:</span> <span><?php echo $product['quantity']; ?></span>
                  </span>
                  <span>
                    <span><?= $text_price; ?></span><span>:</span> <span><?php echo $product['total']; ?></span>
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
              <td class="text-right"><strong><?php echo $total['title']; ?></strong></td>
              <td class="text-right"><?php echo $total['text']; ?></td>
            </tr>
            <?php } ?>
          </table>
      </li>
      <li class="cart-dorpdown-footer" >
          <a href="<?php echo $cart; ?>" class="btn btn-default"><?php echo $text_cart; ?></a>
          <a href="<?php echo $checkout; ?>" class="btn btn-primary"><?php echo $text_checkout; ?></a>
      </li>
      <?php } else { ?>
      <li class="cart-dropdown-empty text-center" >
        <i class="fa fa-meh-o" aria-hidden="true"></i>
        <p><?php echo $text_empty; ?></p>
      </li>
      <?php } ?>
    </ul>
</div>
