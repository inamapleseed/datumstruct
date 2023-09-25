<div class="fcategory_tab" >

  <div class="container">
    <h2 class="target-heading" ><?= $heading_title; ?></h2>
  </div>

  <ul class="nav nav-pills margin-auto table width-auto">      
    <?php foreach ($tabs as $index => $tab) { ?>
      <li <?php if(!$index){ ?>class="active"<?php } ?> >
        <a data-toggle="tab" href="#fc<?= $index; ?>-tab-content"><?= $tab['tab_name']?$tab['tab_name']:$tab['name']; ?></a>
      </li>
    <?php } ?>
  </ul>

  <div class="tab-content">
    <?php foreach ($tabs as $index => $tab) { ?>
      <div class="tab-pane fade <?= !$index?'in active':''; ?>" id="fc<?= $index; ?>-tab-content">
        <div class="fc_product_slider">
          <div class="owl-carousel hidden-xs" id="fc_tab_slider_<?= $uqid; ?>-<?= $index; ?>" >
            <?php foreach($tab['products'] as $product){ ?>
              <?= $product; ?>
            <?php }  ?>
          </div>
          <div class="owl-carousel mobile fc_product_slider_obj visible-xs" id="fc_tab_slider_<?= $uqid; ?>-<?= $index; ?>_m" >
            <?php $products = array_chunk($tab['products'], 4); ?>

            <?php foreach($products as $product_set){ ?>
              <?php $product = implode('', $product_set); ?>
              <div class="owl-grouped">
                  <?= $product; ?>
              </div>
            <?php }  ?>
          </div>
        </div> 
      </div>
    <?php } ?>
  </div>

  <?= $slider_script; ?>

</div>