<?php echo $header; ?>
<div class="container">
  <div class="row">
    <div class="col-12">
      <div class="maintainance-box text-center">
        <h2><?= $heading_title; ?></h2>
        <i class="fa fa-smile-o" aria-hidden="true"></i>

        <?php echo $message; ?>

        <br/>
        <a class="btn btn-primary esc" href="mailto:<?=$email;?>" ><?= $email_us; ?></a>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>