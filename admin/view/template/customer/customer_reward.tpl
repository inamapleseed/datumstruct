<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_date_added; ?></td>
        <td class="text-left"><?php echo $column_description; ?></td>
        <td class="text-right"><?php echo $column_points; ?></td>
        <td class="text-right" width="20px"><?php echo $column_action; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($rewards) { ?>
      <?php foreach ($rewards as $reward) { ?>
      <tr>
        <td class="text-left"><?php echo $reward['date_added']; ?></td>
        <td class="text-left"><?php echo $reward['description']; ?></td>
        <td class="text-right"><?php echo $reward['points']; ?></td>
        <td class="text-center">
          <button data-loading-text="<?php echo $text_loading; ?>" data-order="<?php echo $reward['order_id']; ?>" data-toggle="tooltip" title="<?php echo $button_reward_remove; ?>" class="btn btn-danger btn-xs button-reward-remove"><i class="fa fa-minus-circle"></i></button>
        </td>
      </tr>
      <?php } ?>
      <tr>
        <td></td>
        <td class="text-right"><b><?php echo $text_balance; ?></b></td>
        <td class="text-right"><?php echo $balance; ?></td>
        <td class="text-center"></td>
      </tr>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>


<script type="text/javascript">
    
  $('.button-reward-remove').on('click', function(e) {

    if (confirm('<?php echo $text_alert; ?>')) {
      e.preventDefault();
      var order_id = $(this).data('order');
      $.ajax({
        url: 'index.php?route=sale/order/removereward&token=<?php echo $token; ?>&order_id=' + order_id,
        beforeSend: function() {
          $('#button-reward').button('loading');
        },
        complete: function() {
          $('#button-reward').button('reset');
        },
        success: function(json) {
          $('#reward').load('index.php?route=customer/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');
        }
      });

    } 
  });

</script>