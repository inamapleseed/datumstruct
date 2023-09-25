<?= $header; ?>
<div class="container">
  <?= $content_top; ?>
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success">
  <button type="button" class="close pull-right" data-dismiss="alert">&times;</button>
  <i class="fa fa-check-circle"></i> <?= $success; ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger">
  <button type="button" class="close pull-right" data-dismiss="alert">&times;</button>
  <i class="fa fa-exclamation-circle"></i> <?= $error_warning; ?></div>
  <?php } ?>
  <div class="row">
    <div id="content" class="col-sm-12">
      <h2><?= $heading_title; ?></h2>
      <div class="login-container">
        <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
          <div class="form-group">
            <input type="text" name="email" value="<?= $email; ?>" placeholder="<?= $entry_email; ?>" id="input-email" class="form-control" />
          </div>

          <div class="form-group">
            <input type="password" name="password" value="<?= $password; ?>" placeholder="<?= $entry_password; ?>" id="input-password" class="form-control" />
            <div class="login-forgotten text-center" ><?= $forgotten; ?></div>
          </div>
          <div class='text-center'>
            <input type="submit" value="<?= $button_login; ?>" class="btn btn-primary" />
          </div>
            <?php if ($redirect) { ?>
              <input type="hidden" name="redirect" value="<?= $redirect; ?>" />
            <?php } ?>
        </form>
        <?= $column_left; ?>
      </div>
    </div>
    <?= $column_right; ?></div>
    <?= $content_bottom; ?>
</div>
<?= $footer; ?>