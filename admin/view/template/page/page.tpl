<?= $header,$column_left; ?>

<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?= $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach($breadcrumbs as $crumb){ ?>
                    <li><a href="<?= $crumb['href']; ?>" alt="<?= $crumb['text']; ?>" ><?= $crumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <!-- .page-header -->

    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><?= $sub_heading_title; ?></h3>
            </div>
            <div class="panel-body">
                <form action="" method="post" enctype="multipart/form-data" id="form-featured" class="form-horizontal">
                    <div class="page-grid">
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--.container-fluid-->

</div>

<?php $footer; ?>