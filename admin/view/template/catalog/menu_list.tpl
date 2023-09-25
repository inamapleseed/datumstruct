<?= $header, $column_left; ?>
    <div id="content">
        <div class="page-header">
            <div class="container-fluid">
                
                <div class="pull-right">
                    <a href="<?= $add; ?>" data-toggle="tooltip" title="<?= $button_add; ?>" class="btn btn-primary">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
                
                <h1>
                    <?= $heading_title; ?>
                </h1>
                <ul class="breadcrumb">
                    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                    <li>
                        <a href="<?php echo $breadcrumb['href']; ?>">
                            <?php echo $breadcrumb['text']; ?>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <div class="container-fluid">
            <?php if ($warning) { ?>
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-circle"></i>
                <?php echo $warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>
            <?php if ($success) { ?>
            <div class="alert alert-success">
                <i class="fa fa-check-circle"></i>
                <?php echo $success; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-list"></i>
                        <?php echo $text_list; ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td class="text-left" width="1px">
                                        <?= $col_id; ?>
                                    </td>
                                    <td class="text-left">
                                        <?= $col_title; ?>
                                    </td>
                                    <td class="text-right hidden" width="1px">
                                        <?= $col_status; ?>
                                    </td>
                                    <td class="text-center" width="100px">
                                        <?= $col_action; ?>
                                    </td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($menus){ ?>
                                    <?php foreach($menus as $menu){ ?>
                                        <tr>
                                            <td><?= $menu['menu_id']; ?></td>
                                            <td><?= $menu['title']; ?></td>
                                            <td class="text-center hidden" >
                                                    <?php if($menu['status']){ ?>
                                                        <div class="tag label label-success">Enabled</div>
                                                    <?php }else{ ?>
                                                        <div class="tag label label-danger">Disabled</div>
                                                    <?php } ?>
                                            </td>
                                            <td class="text-center" >
                                                <a href="<?php echo $menu['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                                                <button type="button" data-toggle="tooltip" title="" class="btn btn-danger" onclick="confirm('Are you sure?') ? window.location='<?php echo $menu['delete']; ?>' : false;" data-original-title="Delete"><i class="fa fa-trash-o"></i></button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php }else{ ?>
                                <tr>
                                    <td colspan="4" class="text-center" >
                                        There's no menu to list here.
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>


            </div>

        </div>
    </div>
    <?= $footer; ?>