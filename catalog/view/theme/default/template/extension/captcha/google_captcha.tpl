<div id="google_recaptcha" class="form-group required">
  <script src="//www.google.com/recaptcha/api.js" type="text/javascript"></script>
  <div id="input-payment-captcha" class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
  <?php if ($error_captcha) { ?>
  <div class="text-danger"><?php echo $error_captcha; ?></div>
  <?php } ?>
</div>
