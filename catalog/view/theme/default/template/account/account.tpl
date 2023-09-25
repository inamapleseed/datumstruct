<?php echo $header; ?>
<div class="container">
  <?php echo $content_top; ?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
      <h2><?php echo $text_my_account; ?></h2>
      
      <div class="account-container">
        <a class="profile"  href="<?php echo $edit; ?>">
          <h3 class="account-sub-heading" ><?= $update_my_profile; ?></h3>
          <p><?= $access_your_account_details; ?></p>
          <span class="icon-font icon-profile"></span>
        </a>
        <a class="password" href="<?php echo $password; ?>">
          <h3 class="account-sub-heading" ><?= $update_my_password; ?></h3>
          <p><?= $keeps_your_security_accesses_in_check; ?></p>
          <span class="icon-font icon-updatePassword"></span>
        </a>
        <a class="order"    href="<?php echo $order; ?>">
          <h3 class="account-sub-heading" ><?= $my_order_history; ?></h3>
          <p><?= $keeps_track_of_your_orders; ?></p>
          <span class="icon-font icon-history"></span>
        </a>
        <a class="enquiry"  href="<?php echo $enquiry; ?>">
          <h3 class="account-sub-heading" ><?= $my_enquiry_history; ?></h3>
          <p><?= $keeps_track_of_your_enqueries; ?></p>
          <span class="icon-font icon-history"></span>
        </a>
        <a class="enquiry"  href="index.php?route=account/wishlist">
          <h3 class="account-sub-heading" >My Wishlist</h3>
          <p>Keeps track of your wishlist</p>
          <span class="icon-font icon-history"></span>
        </a>
        <a class="address"  href="<?php echo $address; ?>">
          <h3 class="account-sub-heading" ><?= $my_address_book; ?></h3>
          <p><?= $keeps_track_of_your_addresses; ?></p>
          <span class="icon-font icon-addressbook"></span>
        </a>
        <?php if($reward) { ?>
        <a class="reward"  href="<?php echo $reward; ?>">
          <h3 class="account-sub-heading" ><?= $my_reward_points; ?></h3>
          <p><?= $keeps_track_of_your_reward_points; ?></p>
          <span class="icon-font icon-history"></span>
        </a>
        <?php } ?>
      </div>
      </div>
    <?php echo $column_right; ?></div>
    <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?> 