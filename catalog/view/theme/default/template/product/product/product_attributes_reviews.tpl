<?php if ($attribute_groups || $review_status) { ?>
  <ul id="product-tabs" class="nav nav-tabs">
  <?php if ($attribute_groups) { ?>
    <?php foreach ($attribute_groups as $index_1 => $attribute_group) { ?>
      <?php foreach ($attribute_group['attribute'] as $index_2 => $attribute) { ?>
        <li class="<?= !$index_2?'active':''; ?>" >
          <a href="#tab-attribute-<?= $index_1; ?>-<?= $index_2; ?>" data-toggle="tab"><?= $attribute['name']; ?></a>
        </li>
      <?php } ?>
    <?php } ?>
  <?php } ?>
  <?php if ($review_status) { ?>
    <li><a href="#tab-review" data-toggle="tab"><?= $tab_review; ?></a></li>
  <?php } ?>
    <li onclick="toggler()">
      <i class="fa fa-minus"></i>
    </li>
  </ul>

<script type="text/javascript">
  function  toggler(){
    let that = document.getElementById("tab-content");
    if (that.style.display == "none") {
      that.style.display = "block";
    } else {
      that.style.display = "none";
    }
  }
</script>
  <div class="tab-content" id="tab-content">
  <?php if ($attribute_groups) { ?>
    <?php foreach ($attribute_groups as $index_1 => $attribute_group) { ?>
      <?php foreach ($attribute_group['attribute'] as $index_2 => $attribute) { ?>
        <div class="tab-pane <?= !$index_2?'active':''; ?>" id="tab-attribute-<?= $index_1; ?>-<?= $index_2; ?>">
          <?= html($attribute['text']); ?>
        </div>
      <?php } ?> 
    <?php } ?>
  <?php } ?>

  <?php if ($review_status) { ?>
    <div class="tab-pane" id="tab-review">
      <form class="form-horizontal" id="form-review">
        <div id="review"></div>
        <h3><?= $text_write; ?></h3>
        <?php if ($review_guest) { ?>
        <div class="form-group required">
          <div class="col-sm-12">
            <label class="control-label" for="input-name"><?= $entry_name; ?></label>
            <input type="text" name="name" value="<?= $customer_name; ?>" id="input-name" class="form-control" />
          </div>
        </div>
        <div class="form-group required">
          <div class="col-sm-12">
            <label class="control-label" for="input-review"><?= $entry_review; ?></label>
            <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
            <div class="help-block"><?= $text_note; ?></div>
          </div>
        </div>
        <div class="form-group required">
          <div class="col-sm-12">
            <label class="control-label"><?= $entry_rating; ?></label>
            &nbsp;&nbsp;&nbsp; <?= $entry_bad; ?>&nbsp;
            <input type="radio" name="rating" value="1" />
            &nbsp;
            <input type="radio" name="rating" value="2" />
            &nbsp;
            <input type="radio" name="rating" value="3" />
            &nbsp;
            <input type="radio" name="rating" value="4" />
            &nbsp;
            <input type="radio" name="rating" value="5" />
            &nbsp;<?= $entry_good; ?></div>
        </div>
        <?php // below "col-sm-12" is fix for offset negative margin left right in the recaptcha markup ?>
        <div class="col-sm-12"><?= $captcha; ?></div>
        <div class="buttons clearfix">
          <div class="pull-right">
            <button type="button" id="button-review" data-loading-text="<?= $text_loading; ?>" class="btn btn-primary"><?= $button_continue; ?></button>
          </div>
        </div>
        <?php } else { ?>
        <?= $text_login; ?>
        <?php } ?>
      </form>
    </div>
  <?php } ?>
  </div>
<?php } ?>