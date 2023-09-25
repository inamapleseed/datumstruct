<style>
.product_adv h3{font-size:16px; width:100%; border-bottom:1px solid #bbb; padding-bottom:4px; margin-bottom:15px; margin-top:10px;}
.product_adv table{width:100%}
.product_adv img{float:none;display:inline;}
.product_adv td{width:33%;text-align:center;}
.price-old{color:red; text-decoration: line-through; font-size:12px;}
.product_adv h4 {font-size:15px;padding-bottom:7px;}
</style>
<div class="product_adv">
  <h3 class="center"><?php echo $language->get('product_ad_'.$mode); ?></h3>
  <table>
    <?php foreach ($products as $rows) { ?>
    <tr>
      <?php foreach ($rows as $product) { ?>
        <td>
          <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
          <p><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>"/></a></p>
          <p class="price">
            <?php if (!$product['special']) { ?>
            <?php echo $product['price']; ?>
            <?php } else { ?>
            <span class="price-old"><?php echo $product['price']; ?></span>
            <span class="price-new"><?php echo $product['special']; ?></span>
            <?php } ?>
          </p>
        </td>
      <?php } ?>
    </tr>
    <?php } ?>
  </table>
</div>