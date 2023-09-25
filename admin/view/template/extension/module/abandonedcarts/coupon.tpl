<div id="couponsList">
    <table class="table table-bordered table-hover list">
        <thead>
            <tr class="table-header">
                <td class="left">
                    <?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_coupon_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_coupon_name; ?></a>
                    <?php } ?>
                </td>
                <td class="left">
                    <?php if ($sort == 'code') { ?>
                    <a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_code; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a>
                    <?php } ?>
                </td>
                <td class="right">
                    <?php if ($sort == 'discount') { ?>
                    <a href="<?php echo $sort_discount; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_discount; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_discount; ?>"><?php echo $column_discount; ?></a>
                    <?php } ?>
                </td>
                <td class="left">
                    <?php if ($sort == 'date_start') { ?>
                    <a href="<?php echo $sort_date_start; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_start; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_start; ?>"><?php echo $column_date_start; ?></a>
                    <?php } ?>
                </td>
                <td class="left">
                    <?php if ($sort == 'date_end') { ?>
                    <a href="<?php echo $sort_date_end; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_end; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_end; ?>"><?php echo $column_date_end; ?></a>
                    <?php } ?>
                </td>
                <td class="left">
                    <?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?>
                </td>
                <td class="left">
                    <?php if ($sort == 'date_added') { ?> 
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?>
                </td>
            </tr>
        </thead>
        
        <tbody>
        <?php 
        if (!empty($coupons)) { 
            foreach ($coupons as $coupon) { ?>
            <tr>
                <td class="left"><?php echo $coupon['name']; ?></td>
                <td class="left"><?php echo $coupon['code']; ?></td>
                <td class="right"><?php echo $coupon['discount']; ?></td>
                <td class="left"><?php echo $coupon['date_start']; ?></td>
                <td class="left"><?php echo $coupon['date_end']; ?></td>
                <td class="left"><?php echo $coupon['status']; ?></td>
                <td class="left"><?php echo $coupon['date_added']; ?></td>
            </tr>
            <?php } 
        } else { ?>
            <tr>
                <td class="center" colspan="7"><?php echo $text_no_results; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    
    <div class="row">
        <div class="col-sm-6 text-left">
            <?php echo $pagination; ?>
        </div>
        
        <div class="col-sm-6 text-right">
            <?php echo $results; ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#couponsList .pagination a').click(function(e){
		e.preventDefault();
		$.ajax({
			url: this.href,
			type: 'get',
			dataType: 'html',
			success: function(data) {				
				$("#couponsList").html(data);
			}
        });
    });
});
   
$(document).ready(function(){
	$('#couponsList .list thead tr td a').click(function(e){
		e.preventDefault();
		$.ajax({
			url: this.href,
			type: 'get',
			dataType: 'html',
			success: function(data) {				
				$("#couponsList").html(data);
			}
		});
	});
});
</script>