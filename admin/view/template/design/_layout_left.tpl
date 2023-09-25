<table id="module-column-left" class="table">
    <thead>
    <tr>
        <td class="text-center well"><?php echo $text_column_left; ?></td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($layout_modules as $layout_module) { ?>
    <?php if ($layout_module['position'] == 'column_left') { ?>
    <tr id="module-row<?php echo $module_row; ?>">
        <td class="text-left"><div class="input-group">
            <select name="layout_module[<?php echo $module_row; ?>][code]" class="form-control input-sm">
            <?php foreach ($extensions as $extension) { ?>
            <optgroup label="<?php echo $extension['name']; ?>">
            <?php if (!$extension['module']) { ?>
            <?php if ($extension['code'] == $layout_module['code']) { ?>
            <option value="<?php echo $extension['code']; ?>" selected="selected"><?php echo $extension['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $extension['code']; ?>"><?php echo $extension['name']; ?></option>
            <?php } ?>
            <?php } else { ?>
            <?php foreach ($extension['module'] as $module) { ?>
            <?php if ($module['code'] == $layout_module['code']) { ?>
            <option value="<?php echo $module['code']; ?>" selected="selected"><?php echo $module['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $module['code']; ?>"><?php echo $module['name']; ?></option>
            <?php } ?>
            <?php } ?>
            <?php } ?>
            </optgroup>
            <?php } ?>
            </select>
            <input type="hidden" name="layout_module[<?php echo $module_row; ?>][position]" value="<?php echo $layout_module['position']; ?>" />
            <input type="hidden" name="layout_module[<?php echo $module_row; ?>][sort_order]" value="<?php echo $layout_module['sort_order']; ?>" />
            <div class="input-group-btn"><a href="<?php echo $layout_module['edit']; ?>" type="button" data-toggle="tooltip" title="<?php echo $button_edit; ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-pencil"></i></a>
            <button type="button" onclick="$('#module-row<?php echo $module_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-sm"><i class="fa fa fa-minus-circle"></i></button>
            </div>
        </div></td>
    </tr>
    <?php $module_row++; ?>
    <?php } ?>
    <?php } ?>
    </tbody>
    <tfoot>
    <tr>
        <td class="text-left"><div class="input-group">
            <select class="form-control input-sm">
            <?php foreach ($extensions as $extension) { ?>
            <optgroup label="<?php echo $extension['name']; ?>">
            <?php if (!$extension['module']) { ?>
            <option value="<?php echo $extension['code']; ?>"><?php echo $extension['name']; ?></option>
            <?php } else { ?>
            <?php foreach ($extension['module'] as $module) { ?>
            <option value="<?php echo $module['code']; ?>"><?php echo $module['name']; ?></option>
            <?php } ?>
            <?php } ?>
            </optgroup>
            <?php } ?>
            </select>
            <div class="input-group-btn">
            <button type="button" onclick="addModule('column-left');" data-toggle="tooltip" title="<?php echo $button_module_add; ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i></button>
            </div>
        </div></td>
    </tr>
    </tfoot>
</table>