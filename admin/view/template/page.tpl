<?= $header, $column_left; ?>

<div id="content">
    
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <?= $button; ?>
            </div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <!-- End .page-header -->

    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?= $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-<?= $form_placeholder_type; ?>" class="form-horizontal">
                    <?= $content; ?>
                </form>
            </div>
        </div>
        <!-- End .panel.panel-default -->
    </div>
    <!-- End .container-fluid -->

</div>
<!-- End #content -->
<?= $footer; ?>