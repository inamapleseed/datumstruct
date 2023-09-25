<h3><?= $product_name; ?></h3>
<ul class="list-unstyled hidden">
  <?php if ($manufacturer) { ?>
  <li><?= $text_manufacturer; ?> <a href="<?= $manufacturers; ?>"><?= $manufacturer; ?></a></li>
  <?php } ?>
  <li><?= $text_model; ?> <?= $model; ?></li>
  <?php if ($reward) { ?>
  <li><?= $text_reward; ?> <?= $reward; ?></li>
  <?php } ?>
  <li><?= $text_stock; ?> <?= $stock; ?></li>
</ul>
<?php if ($price && !$enquiry) { ?>
<ul class="list-unstyled inside-price">
  <?php if (!$special) { ?>
  <li>
    <div class="product-price" ><?= $price; ?></div>
  </li>
  <?php } else { ?>
  <li><span style="text-decoration: line-through;" class="old-prices"><?= $price; ?></span>&nbsp;&nbsp;</li>
  <li>
    <div class="product-special-price new-prices"><?= $special; ?></div>
  </li>
  <?php } ?>
  <?php if ($tax) { ?>
  <li class="product-tax-price product-tax" ><?= $text_tax; ?> <?= $tax; ?></li>
  <?php } ?>
  <?php if ($points) { ?>
  <li><?= $text_points; ?> <?= $points; ?></li>
  <?php } ?>
  <?php if ($discounts) { ?>
  <li>
    <hr>
  </li>
  <?php foreach ($discounts as $discount) { ?>
  <li><?= $discount['quantity']; ?><?= $text_discount; ?><?= $discount['price']; ?></li>
  <?php } ?>
  <?php } ?>
</ul>
<?php } ?>
<ul class="list-unstyled">
  <?php if ($manufacturer) { ?>
  <li><?= $text_manufacturer; ?> <a href="<?= $manufacturers; ?>"><?= $manufacturer; ?></a></li>
  <?php } ?>
</ul>

<?php if($enquiry){ ?>
    <div class="product-price" ><?= $price; ?></div>
<?php } ?>
<?php if (!$description): ?>
    <p>
      <i>
        Description unavailable
      </i>
    </p>
  <?php else: ?>
    <div class="product-description">
      <?= $description; ?>
    </div>      
<?php endif ?>

<?php include_once('product_attributes_reviews.tpl'); ?>

<?php if ($waiting_module): ?>
  <?= $waiting_module; ?>
  <?php if($share_html){ ?>
    <div class="input-group-flex ">
      <span><?= $text_share; ?></span>
      <div><?= $share_html; ?></div>
    </div>
  <?php } ?>    
  <?php else: ?>
  <?php include_once('product_options.tpl'); ?>
<?php endif ?>
