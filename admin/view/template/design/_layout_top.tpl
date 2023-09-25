<table id="module-content-top" class="table">
        
    <thead>
        <tr>
            <td colspan="3" class="text-center well"><?= $text_content_top; ?></td>
        </tr>
        <tr class="text-center cuatom-tr" >
            <td>Background</td>
            <td>Section Mode</td>
            <td>Module</td>
        </tr>
    </thead>

<tbody>
<?php foreach ($layout_modules as $layout_module) { ?>
<?php if ($layout_module['position'] == 'content_top') { ?>
<tr id="module-row<?= $module_row; ?>">
    <td class="text-center">
        <a href="#" id="thumb-image_content_top<?= $module_row; ?>" data-toggle="image" class="img-thumbnail" style="width: 50px;" >
            <img src="<?= $layout_module['background_thumb']; ?>" alt="" title="" data-placeholder="<?= $placeholder; ?>" class="img-responsive"  />
        </a>
        <input class="form-control" type = "hidden" name = "layout_module[<?= $module_row; ?>][background]" value = "<?= $layout_module['background']; ?>" id="input-image_content_top<?= $module_row; ?>" />
    </td>
    <td>
        <select name="layout_module[<?= $module_row; ?>][mode]" class="form-control input-sm">
            <option value=""                          <?= !$layout_module['mode']?'selected':''; ?>                               >Default</option>
            <option value="full-width"                <?= ($layout_module['mode']=='full-width')?'selected':''; ?>                >Full width</option>
            <option value="full-width-with-container" <?= ($layout_module['mode']=='full-width-with-container')?'selected':''; ?> >Full width with container</option>
        </select>
    </td>
    <td class="text-left"><div class="input-group">
        <select name="layout_module[<?= $module_row; ?>][code]" class="form-control input-sm">
        <?php foreach ($extensions as $extension) { ?>
        <optgroup label="<?= $extension['name']; ?>">
        <?php if (!$extension['module']) { ?>
        <?php if ($extension['code'] == $layout_module['code']) { ?>
        <option value="<?= $extension['code']; ?>" selected="selected"><?= $extension['name']; ?></option>
        <?php } else { ?>
        <option value="<?= $extension['code']; ?>"><?= $extension['name']; ?></option>
        <?php } ?>
        <?php } else { ?>
        <?php foreach ($extension['module'] as $module) { ?>
        <?php if ($module['code'] == $layout_module['code']) { ?>
        <option value="<?= $module['code']; ?>" selected="selected"><?= $module['name']; ?></option>
        <?php } else { ?>
        <option value="<?= $module['code']; ?>"><?= $module['name']; ?></option>
        <?php } ?>
        <?php } ?>
        <?php } ?>
        </optgroup>
        <?php } ?>
        </select>
        <input type="hidden" name="layout_module[<?= $module_row; ?>][position]" value="<?= $layout_module['position']; ?>" />
        <input type="hidden" name="layout_module[<?= $module_row; ?>][sort_order]" value="<?= $layout_module['sort_order']; ?>" />
        <div class="input-group-btn"> <a href="<?= $layout_module['edit']; ?>" type="button" data-toggle="tooltip" title="<?= $button_edit; ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
        <button type="button" onclick="$('#module-row<?= $module_row; ?>').remove();" data-toggle="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger btn-sm"><i class="fa fa fa-minus-circle"></i></button>
        </div>
    </div></td>
</tr>
<?php $module_row++; ?>
<?php } ?>
<?php } ?>
</tbody>
<tfoot>
<tr>
    <td class="text-center">
        <a href="#" id="thumb-image_content_top_adding" data-toggle="image" class="img-thumbnail" style="width: 50px;" >
            <img src="<?= $placeholder; ?>" alt="" title="" data-placeholder="<?= $placeholder; ?>" class="img-responsive"  />
        </a>
        <input class="form-control" type = "hidden" value = "" id="input-image_content_top_adding" />
    </td>
    <td>
        <select class="form-control input-sm">
            <option value="" >Default</option>
            <option value="full-width" >Full width</option>
            <option value="full-width-with-container" >Full width with container</option>
        </select>
    </td>
    <td class="text-left"><div class="input-group">
        <select class="form-control input-sm">
        <?php foreach ($extensions as $extension) { ?>
        <optgroup label="<?= $extension['name']; ?>">
        <?php if (!$extension['module']) { ?>
        <option value="<?= $extension['code']; ?>"><?= $extension['name']; ?></option>
        <?php } else { ?>
        <?php foreach ($extension['module'] as $module) { ?>
        <option value="<?= $module['code']; ?>"><?= $module['name']; ?></option>
        <?php } ?>
        <?php } ?>
        </optgroup>
        <?php } ?>
        </select>
        <div class="input-group-btn">
        <button type="button" onclick="addModule('content-top');" data-toggle="tooltip" title="<?= $button_module_add; ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i></button>
        </div>
    </div></td>
</tr>
</tfoot>
</table>