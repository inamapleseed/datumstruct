<?= $header; ?>
<div class="container">
  <?= $content_top; ?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li>
      <a href="<?= $breadcrumb['href']; ?>">
        <?= $breadcrumb['text']; ?>
      </a>
    </li>
    <?php } ?>
  </ul>
  <?php if ($attention) { ?>
  <div class="alert alert-info">
    <i class="fa fa-info-circle"></i>
    <?= $attention; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success hidden">
    <i class="fa fa-check-circle"></i>
    <?= $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger hidden">
    <i class="fa fa-exclamation-circle"></i>
    <?= $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row">
    <?= $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?= $class; ?>">
      
      <h2>
        <?= $heading_title; ?>
        <?php if ($weight) { ?> &nbsp;(
        <?= $weight; ?>)
        <?php } ?>
      </h2>

      <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="cartSummary" >

          <table class="table">
            <thead>
              <tr>
                <td class="text-left" colspan="2" >
                  <?= $column_name; ?>
                </td>
                <td class="text-center" width="1px" >
                  <?= $column_quantity; ?>
                </td>
                <td class="text-right hidden">
                  <?= $column_price; ?>
                </td>
                <td class="text-right hidden">
                  <?= $column_total; ?>
                </td>
                <td></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product) { ?>
              
              <tr>

                <td class="text-left" width="<?= $width; ?>px" >
                  <?php if ($product['thumb']) { ?>
                  <a href="<?= $product['href']; ?>">
                    <img src="<?= $product['thumb']; ?>" alt="<?= $product['name']; ?>" title="<?= $product['name']; ?>" />
                  </a>
                  <?php } ?>
                </td>

                <td class="text-left cart-description" style="width: calc(100% - <?= $width; ?>px)">

                    <a href="<?= $product['href']; ?>">
                      <?= $product['name']; ?>
                      <br/>
                      [<?= $button_view; ?>]
                    </a>

                    <?php if (!$product['stock']) { ?>
                      <span class="text-danger">***</span>
                    <?php } ?>

                    <?php if ($product['option']) { ?>
                      <?php foreach ($product['option'] as $option) { ?>
                      <br />
                        <small>
                          <?= $option['name']; ?>:
                          <?= $option['value']; ?>
                        </small>
                      <?php } ?>
                    <?php } ?>
                    
                    <?php if ($product['recurring']) { ?>
                      <br />
                      <span class="label label-info">
                        <?= $text_recurring_item; ?>
                      </span>

                      <small>
                        <?= $product['recurring']; ?>
                      </small>
                    <?php } ?>
                </td>

                <td class="text-center cart-quantity" width="<?= $width; ?>px">

                 
                    <div class="input-group" id="cart-item-<?= $product['enquiry_id']; ?>" >
                      <span class="input-group-btn">
                        <button type="button" data-loading="-" class="btn btn-default btn-number no-custom <?= $product['quantity']==1?'disabled':''; ?>" data-type="minus" 
                        <?php if($product['quantity'] > 1){ ?>
                          onclick="descrement($(this).parent().parent()); refreshEnquiry(this);" 
                        <?php } ?>
                        >
                          <span class="glyphicon glyphicon-minus"></span>
                        </button>
                      </span>

                      <input type="text" name="quantity[<?= $product['enquiry_id']; ?>]" class="form-control input-number integer text-center update-cart no-custom" value="<?= $product['quantity']; ?>" onfocus="rememberQuantity('<?= $product['quantity']; ?>');" onblur="refreshEnquiry(this);" >
                      
                      <span class="input-group-btn">
                        <button type="button" data-loading="+" class="btn btn-default btn-number no-custom" data-type="plus" onclick="increment($(this).parent().parent());  refreshEnquiry(this);">
                          <span class="glyphicon glyphicon-plus"  ></span>
                        </button>
                      </span>
                    </div>

                </td>

                <td class="text-right cart-price hidden" style="width: calc(50% - <?= $width/2; ?>px)" >
                  <span class="column-label hidden-sm hidden-md hidden-lg" ><?= $column_price; ?></span>
                  <span class="hidden-sm hidden-md hidden-lg" >:</span>
                  <div><?= $product['price']; ?></div>
                </td>

                <td class="text-right cart-price hidden" style="width: calc(50% - <?= $width/2; ?>px)" >
                  <span class="column-label hidden-sm hidden-md hidden-lg" ><?= $column_total; ?></span>
                  <span class="hidden-sm hidden-md hidden-lg" >:</span>
                  <div><?= $product['total']; ?></div>
                </td>

                <td class="text-center" >
                  <button type="button" data-toggle="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger remove" onclick="enquiry.remove('<?= $product['enquiry_id']; ?>');">
                    <i class="fa fa-times-circle"></i>
                  </button>
                </td>

              </tr>
              <?php } ?>
              
            </tbody>
          </table>

      </form>

      <div class="row">
        <div class="col-sm-4 col-sm-offset-8">
          <table class="table table-bordered" id="cartTotals">
            <?php foreach ($totals as $total) { ?>
            <tr>
              <td class="text-right">
                <strong>
                  <?= $total['title']; ?>:</strong>
              </td>
              <td class="text-right">
                <?= $total['text']; ?>
              </td>
            </tr>
            <?php } ?>
          </table>
        </div>
      </div>
      <div class="buttons clearfix">
        <div class="pull-left">
          <a href="<?= $continue; ?>" class="btn btn-default">
            <?= $button_shopping; ?>
          </a>
        </div>
        <div class="pull-right"> 
          <a href="<?= $checkout; ?>" class="btn btn-primary">
            <?= $button_checkout; ?>
          </a>
        </div>
      </div>
      
    </div>
    <?= $column_right; ?>
  </div>
  <?= $content_bottom; ?>
</div>
<script type="text/javascript">

  var current_focus_quantity = 0;
  function rememberQuantity(qty){
    current_focus_quantity = parseInt(qty);
  }

  var request = null;
  function refreshEnquiry(ele) {
    element = $(ele);

    id = element.parent().parent().attr("id");
    if (element.is("input")) {
      id = element.parent().attr("id");
    }

    if($("#" + id + " input").val() == current_focus_quantity){
      return;
    }
    
    if(request) request.abort();

    request = $.ajax({
      url: '<?= $action; ?>',
      data: $("#" + id + " input").serialize(),
      dataType: 'HTML',
      type: 'post',
      beforeSend: function () {

        $('.alert').remove();
        if(element.hasClass('btn-number')){
          cache_text = element.html();
          // element.button("loading");
          element.html(cache_text);
        }
        else{
          element.button("loading");
        }
        current_focus_quantity = 0;
      },
      complete: function () {
        element.button("reset");
      },
      success: function (html) {
        $(".alert-success").remove();

        content_total_dropdown_body = $(html).find("#loadFrom").html();
        content_total_dropdown = $(html).find("#enquiry-quantity-total").text();
        content_total = $(html).find("#cartTotals");
        content = $(html).find("#cartSummary tbody");
        alert = $(html).find(".alert-success");
        alert_error = $(html).find(".alert-danger");

        if(alert_error.length){
          error = alert_error.html().split(":");
          if(error.length > 1){
            swal({
              title: error[0],
              html: error[1],
              type: "error"
            });
          }
          else{
            swal({
              title: '<?= $text_title_warning; ?>',
              html: error[0],
              type: "error"
            });
          }
        }
        else if(alert.length){
          success = alert.html().split(":");
          if(success.length > 1){
            swal({
              title: success[0],
              html: success[1],
              type: "success"
            });
          }
        }
        
        if (content.length) { 
          content = content.html();
          $("#cartSummary tbody").html(content);
          var parent_element = $("#cartTotals").parent(); $("#cartTotals").remove();
          parent_element.html(content_total);
          
          $('#enquiry-quantity-total').text(content_total_dropdown);
          $('#loadTo').html(content_total_dropdown_body);

          $('#enquiry > ul').load('index.php?route=common/enquiry/info ul > *');
        } else {
          location.reload();
        }

      }
    })
  }
</script>
<?= $footer; ?>