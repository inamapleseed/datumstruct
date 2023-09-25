<table class="quickcheckout-cart">
	<thead>
		<tr>
		  <td class="image"><?php echo $column_image; ?></td>
		  <td class="name"><?php echo $column_name; ?></td>
		  <td class="text-right"><?php echo $column_quantity; ?></td>
		  <!-- <td class="price1"><?php echo $column_price; ?></td>
		  <td class="total"><?php echo $column_total; ?></td> -->
		</tr>
	</thead>
    <?php if ($products || $vouchers) { ?>
	<tbody>
        <?php foreach ($products as $product) { ?>
        <tr>
          <td class="image"><?php if ($product['thumb']) { ?>
            <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
            <?php } ?></td>
          <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?><br/> [<?= $button_view; ?>]</a>
            <div>
              <?php foreach ($product['option'] as $option) { ?>
              <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br />
              <?php } ?>
			  <?php if ($product['reward']) { ?>
			  <br />
			  <small><?php echo $product['reward']; ?></small>
			  <?php } ?>
			  <?php if ($product['recurring']) { ?>
			  <br />
			  <span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
			  <?php } ?>
            </div></td>
          <td class="text-right">x&nbsp;<?php echo $product['quantity']; ?></td>
		 		  <!-- <td class="price1"><?php echo $product['price']; ?></td>
          <td class="total"><?php echo $product['total']; ?></td> -->
        </tr>
        <?php } ?>

			<?php foreach ($totals as $total) { ?>
			<tr>
				<td class="text-right" colspan="2"><b><?php echo $total['title']; ?>:</b></td>
				<td class="text-right"><?php echo $total['text']; ?></td>
			</tr>
        <?php } ?>
	</tbody>
    <?php } ?>
</table>