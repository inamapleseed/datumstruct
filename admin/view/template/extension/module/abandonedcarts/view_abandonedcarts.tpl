<div>
    <div style="float:right;padding: 5px;">
        <a onclick="removeAllEmpty()" class="btn btn-sm btn-warning"><i class="fa fa-file-o"></i>&nbsp;&nbsp;<?php echo $text_remove_all_empty_records; ?></a>&nbsp;<a onclick="removeAll()" class="btn btn-sm btn-danger"><i class="fa fa-trash-o"></i>&nbsp;&nbsp;<?php echo $text_remove_all; ?></a> 
        </div>
    <ul class="nav nav-pills filter" role="tablist">
      <li role="presentation" <?php echo ($forFilter=='default') ? 'class="active"' : ''; ?>><a href="<?php echo $filterURL; ?>&notified=0"><i class="fa fa-circle-o"></i>&nbsp;<strong><?php echo $text_default_ac_tab_name; ?></strong></a></li>
      <li role="presentation" <?php echo ($forFilter=='notified') ? 'class="active"' : ''; ?>><a href="<?php echo $filterURL; ?>&notified=1"><i class="fa fa-circle"></i>&nbsp;<strong><?php echo $text_already_notified_tab_name; ?></strong></a></li>
      <li role="presentation" <?php echo ($forFilter=='ordered') ? 'class="active"' : ''; ?>><a href="<?php echo $filterURL; ?>&ordered=1"><i class="fa fa-check"></i>&nbsp;<strong><?php echo $text_ordered_tab_name; ?></strong></a></li>
    </ul>
   </div>
<br />
<table id="abandonedCartsWrapper<?php echo $store_id; ?>" class="table table-bordered table-hover AbCartsTable" width="100%">
    <thead>
        <tr class="table-header">
            <td class="left" width="5%"><strong class="btn-send" data-toggle="tooltip" data-placement="left" title="<?php echo $helper_id; ?>"><?php echo $text_ID_number; ?></strong></td>
            <td class="left" width="20%"><strong class="btn-send" data-toggle="tooltip" title="<?php echo $helper_customer; ?>"><?php echo $text_customer_info; ?></strong></td>
            <td class="left" width="25%"><strong class="btn-send" data-toggle="tooltip" title="<?php echo $helper_shopping_cart; ?>"><?php echo $text_shopping_cart_info; ?></strong></td>
            <td class="left" width="15%"><strong class="btn-send" data-toggle="tooltip" title="<?php echo $helper_date_time_info; ?>"><?php echo $text_date_time_info; ?></strong></td>
            <td class="left" width="15%" style="white-space: normal;" ><strong class="btn-send" data-toggle="tooltip" title="<?php echo $helper_last_page; ?>"><?php echo $text_last_visited_page; ?></strong></td>
            <td class="left" width="10%"><strong class="btn-send" data-toggle="tooltip" title="<?php echo $helper_ip_info; ?>"><?php echo $text_ip_info ?></strong></td>
            <td class="left" width="10%"><strong class="btn-send" data-toggle="tooltip" title="<?php echo $helper_actions; ?>"><?php echo $text_status_actions;?></strong></td>
        </tr>
    </thead>
    <?php if (!empty($sources)) { ?>
		<?php $i=0; foreach ($sources as $ab) { ?>
        <tbody>
            <tr>
                <td class="left">
                    <?php echo $ab['id']; ?>
                </td>
                <td>
                    <?php $ab['customer_info'] = json_decode($ab['customer_info'], true); ?>
                    <table class="table table-bordered">
                        <?php if (empty($ab['customer_info'])) { ?>
                        <tr><td class="name"><i class="fa fa-user"></i> <?php echo $text_not_provided; ?></td></tr>
						<tr><td class="email"><i class="fa fa-envelope-o"></i> <?php echo $text_not_provided; ?></td></tr>
						<tr><td class="telephone"><i class="fa fa-phone"></i> <?php echo $text_not_provided; ?></td></tr>
                        <tr><td class="language"><i class="fa fa-flag"></i> <?php echo $text_not_provided; ?></td></tr>
                        <tr><td class="language"><i class="fa fa-book"></i> <?php echo $text_guest_label; ?></td></tr>
                       	<?php } else { ?>
						<tr>
                            <td class="name">
                                <i class="fa fa-user"></i>
                                <?php if (!isset($ab['customer_info']['firstname']) && !isset($ab['customer_info']['lastname'])) {  
                                    echo "(not provided)";
                                } ?>
                                <?php if (isset($ab['customer_info']['firstname'])) {  
                                    echo $ab['customer_info']['firstname'];
                                } ?>
                                &nbsp;
                                <?php if (isset($ab['customer_info']['lastname'])) {
                                    echo $ab['customer_info']['lastname'];
                                } ?>
                            </td>
                        </tr>
						<tr>
                            <td class="email">
                                <i class="fa fa-envelope-o"></i> <?php echo (isset($ab['customer_info']['email']) ? $ab['customer_info']['email'] : '(not provided)'); ?>
                            </td>
                        </tr>
						<tr>
                            <td class="telephone">
                                <i class="fa fa-phone"></i> <?php echo (isset($ab['customer_info']['telephone']) ? $ab['customer_info']['telephone'] : '(not provided)'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="language">
                                <i class="fa fa-flag"></i> <?php echo $text_language_label; ?> <?php echo isset($ab['customer_info']['language']) ? $ab['customer_info']['language'] : '(not provided)'; ?><?php echo (isset($ab['customer_info']['lang_image'])) ? '<div class="btn btn-xs btn-default" style="float:right;"><img src="'.$ab['customer_info']['lang_image'].'" style="margin-top:-2px;" /></div>' : ''; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="language">
                                <i class="fa fa-book"></i> 
                                <?php if (isset($ab['customer_info']['email'])) { 
                                    $customerCheck = $model_sale_customer->getCustomerByEmail($ab['customer_info']['email']); } else { $customerCheck=''; }
                                    if (!empty($customerCheck)) echo $text_registed_customer_label. " <a href='index.php?route=".$customer_url."&".$token_addon."&customer_id=".$customerCheck['customer_id']."' target='_blank' class='btn btn-xs btn-default' data-toggle='tooltip' title='".$text_customer_more_helper."' style='float:right;'><i class='fa fa-eye'></i> ".$text_more_word."</a>"; else echo $text_guest_customer_label; ?>
                            </td>
                        </tr>
                        <?php } ?>
                     </table>
                </td>
                <td>
                <?php $ab['cart'] = json_decode($ab['cart'], true); ?>
                    <table class="table table-bordered">
                    <?php if (is_array($ab['cart'])) { $total_price='0'; $total_quantity = 0; ?>
                        <?php foreach ($ab['cart'] as $product) { ?>
                            <?php if ($product['image']) {
                                $image_thumb = $model_tool_image->resize($product['image'], 30, 30);
                                $image = $model_tool_image->resize($product['image'], 125, 125);
                            } else {
                                $image_thumb = false;
                                $image = false;
                            }
                            ?>
                        <tr>
                            <script>
                            $( "#picture<?php echo $i; ?>" ).mouseleave(function() { $("#picture-hover<?php echo $i; ?>").fadeOut( 200 ); });
                            $( "#picture<?php echo $i; ?>" ).mouseenter(function() { $("#picture-hover<?php echo $i; ?>").fadeIn( 200 ); });
                            </script>
                            <td width="70%" class="name">
                                <div id="picture<?php echo $i; ?>" style="float:left;padding-right:3px;"><a href="<?php echo '../index.php?route=product/product&product_id='. $product['product_id']; ?>" target="_blank">
                                    <div id="picture-hover<?php echo $i; ?>" style="position:absolute;z-index:99999;padding-top:18px;display:none;"><img src="<?php echo $image; ?>" class="img-polaroid img-hover" /></div>
                                    <img src="<?php echo $image_thumb; ?>" /></a>
                                </div>
                                <a href="<?php echo $store_data['url'].'index.php?route=product/product&product_id='. $product['product_id']; ?>" target="_blank"><?php echo $product['name']; ?></a>
                                <div>
                                    <?php foreach ($product['option'] as $option) { ?>
                                    - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br />
                                    <?php } ?>
                                </div>
                            </td>
                            <td width="15%" class="quantity">x&nbsp;<?php echo $product['quantity']; ?></td>
                            <td width="15%" class="price"><?php $price = $thiscurrency->format($product['price'], $config_currency); echo $price; ?></td>
                        </tr>
                        <?php 
                            $total_quantity += $product['quantity'];
                            $total_price += $product['quantity']*$product['price'];
                        ?>
                        <?php $i++; } ?>
                        <tr>
                            <td width="70%"><small><?php echo $text_total; ?></small></td>
                            <td width="15%"><small>x&nbsp;<?php echo $total_quantity; ?></small></td>
                            <td width="15%"><small><?php echo $thiscurrency->format($total_price, $config_currency); ?></small></td>
                        </tr>
                    <?php } ?>
                    </table>
                </td>
                <td>
                    <?php echo $text_first_visit; ?><br /><?php echo $ab['date_created'] ?><br /><br />
                    <?php echo $text_last_visit; ?><br /><?php echo $ab['date_modified'] ?><br /><br />
                	<?php echo $text_total_time_spent; ?><br /><?php $time = strtotime($ab['date_modified']) - strtotime($ab['date_created']); echo gmdate("H:i:s", $time) ?>
                </td>
                <td> 
                    <?php $link = "...".substr($ab['last_page'], -30); ?>
                    <a href="<?php echo $last_accessed_url.$ab['last_page']; ?>" target="_blank"><?php echo $link; ?></a> 
                </td>
                <td> 
                    <?php echo $ab['ip']; ?> 
                    <br /><br />
                    <a class="btn btn-xs btn-default btn-send" href="http://www.checkip.com/ip/<?php echo $ab['ip']; ?>" data-toggle="tooltip" title="<?php echo $text_learn_more_user_location_helper; ?>" target="_blank"><i class="fa fa-search"></i> <?php echo $text_check_ip_location; ?></a>
                </td>
                <td>
                    <?php if (!empty($ab['customer_info']['email'])) { ?>
                        <div class="btn-group">
                          <button type="button" <?php if ($ab['ordered']==1) { echo "disabled='disabled'"; } ?> class="btn btn-sm btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-envelope-square"></i>&nbsp;<?php echo $text_send_reminder_action; ?> <span class="caret"></span>
                          </button>
                          <ul class="dropdown-menu" role="menu">
                          	<?php foreach ($usable_templates as $id => $template) { ?>
                            	<?php if ($id == 0) { ?>
                                	<li class="disabled"><a><?php echo $template; ?></a></li>
                                <?php } else { ?>
                            		<li><a data-event-id="<?php echo $ab['id']; ?>" style="cursor:pointer;" onClick="sendReminder('<?php echo $ab['id']; ?>', '<?php echo $id; ?>');"><?php echo $template; ?></a></li>
                                <?php } ?>
                            <?php } ?> 
                          </ul>
                        </div>
                    <?php } else { ?>
					  <a class="btn btn-sm btn-default disabled btn-send" data-toggle="tooltip" title="<?php echo $text_you_cannot_send_helper; ?>" disabled="disabled"><i class="fa fa-envelope-square"></i> <?php echo $text_send_reminder_action; ?></a>
                    <?php } ?>
                    <br /> <br />
                    <a onclick="removeItem('<?php echo $ab['id']; ?>')" class="btn btn-sm btn-danger"><i class="fa fa-times"></i> <?php echo $text_remove_button; ?></a>
                    <br /> <br />
                    <strong> <?php echo $text_ac_status; ?></strong> <br />
                    <?php echo $text_notified; ?> -> <?php if ($ab['notified'] == 0) { echo $text_no; } else { echo $text_yes." (". $ab['notified'] .")"; } ?> <br />
                    <?php echo $text_ordered; ?> -><?php if ($ab['ordered'] == 0) { echo $text_no; } else { echo $text_yes; } ?>
                </td>
            </tr>
        </tbody>
        <?php } ?>
	<?php } else { ?>
    	<tr><td colspan="10"><div class="center"><?php echo $text_no_records; ?></div></td></tr>
    <?php } ?>
    <tfoot>
        <tr>
            <td colspan="10">
    	        <div class="row">
                    <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                    <div class="col-sm-6 text-right"><?php echo $results; ?></div>
                    </div>
            </td>
        </tr>
    </tfoot>
</table>