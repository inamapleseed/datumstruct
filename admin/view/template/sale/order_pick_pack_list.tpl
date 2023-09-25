<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
<script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
<link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
<link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
</head>
<body>
<div class="container">
  <h1><?php echo $text_pickpacklist; ?></h1>
  
  <div style="page-break-after: always;">
    <table class="table table-bordered">
      <thead>
        <tr>
          <td><?php echo $text_ppl_invoice_no; ?></td>
          <td><?php echo $text_ppl_order_date; ?></td>
          <td><?php echo $text_ppl_product_name; ?></td>
          <td><?php echo $text_ppl_sku; ?></td>
          <td  class="text-right"><?php echo $text_ppl_quantity; ?></td>
          <td  class="text-right"><?php echo $text_ppl_customer_name; ?></td>
          <td  class="text-right"><?php echo $text_ppl_customer_contact_no; ?></td>
          <td  class="text-right"><?php echo $text_ppl_delivery_address; ?></td>
          <td  class="text-right"><?php echo $text_ppl_delivery_instruction; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($orders as $order) { ?>
        <?php foreach ($order['product'] as $product) { ?>
            <tr>
              <td><?php echo $order['order_id']; ?></td>
              <td><?php echo $order['date_added']; ?></td>
              <td><?php echo $product['name']; ?>
                <?php foreach ($product['option'] as $option) { ?>
                <br />
                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                <?php } ?></td>
              <td><?php echo $product['sku']; ?></td>
              <td class="text-right"><?php echo $product['quantity']; ?></td>
              <td class="text-right"><?php echo $order['name']; ?></td>
              <td class="text-right"><?php echo $order['telephone']; ?></td>
              <td class="text-right"><?php echo $order['shipping_address']; ?></td>
              <td class="text-right"><?php echo $order['comment']; ?></td>
            </tr>
        <?php } ?>
        <?php } ?>
      </tbody>
    </table>
  </div>
  
</div>
</body>
</html>