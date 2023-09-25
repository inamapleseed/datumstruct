<table class="table">
    <tr>
        <td colspan="2">
            <h4><?php echo $text_main_settings; ?></h4>   
        </td>
    </tr>
    <tr>
        <td class="col-xs-3">
            <h5 class="option_title"><?php echo $text_status; ?></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_status_help; ?></span>
        </td>
        <td class="col-xs-9">
            <div class="col-md-4">
                <select name="<?php echo $moduleName; ?>[Enabled]" class="form-control">
                  <option value="yes" <?php echo (!empty($moduleData['Enabled']) && $moduleData['Enabled'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
                  <option value="no"  <?php echo (empty($moduleData['Enabled']) || $moduleData['Enabled']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td class="col-xs-3">
            <h5 class="option_title"><?php echo $text_dateformat; ?></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_dateformat_help; ?></span>
        </td>
        <td class="col-xs-9">
            <div class="col-md-4">
                <select name="<?php echo $moduleName; ?>[DateFormat]" class="form-control">
                  <option value="d-m-Y" <?php echo (isset($moduleData['DateFormat']) && $moduleData['DateFormat'] == 'd-m-Y') ? 'selected=selected' : '' ?>>dd-mm-yyyy</option>
                  <option value="m-d-Y" <?php echo (isset($moduleData['DateFormat']) && $moduleData['DateFormat']== 'm-d-Y') ? 'selected=selected' : '' ?>>mm-dd-yyyy</option>
                  <option value="Y-m-d" <?php echo (isset($moduleData['DateFormat']) && $moduleData['DateFormat']== 'Y-m-d') ? 'selected=selected' : '' ?>>yyyy-mm-dd</option>
                  <option value="Y-d-m" <?php echo (isset($moduleData['DateFormat']) && $moduleData['DateFormat']== 'Y-d-m') ? 'selected=selected' : '' ?>>yyyy-dd-mm</option>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td class="col-xs-3">
            <h5 class="option_title"><?php echo $text_send_bcc; ?></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_send_bcc_help; ?></span>
        </td>
        <td class="col-xs-9">
            <div class="col-md-4">
                <select name="<?php echo $moduleName; ?>[BCC]" class="form-control">
                  <option value="yes" <?php echo (isset($moduleData['BCC']) && $moduleData['BCC'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
                  <option value="no" <?php echo (empty($moduleData['BCC']) || $moduleData['BCC'] == 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
                </select>
            </div>
        </td>
    </tr> 
    <tr>
        <td class="col-xs-3">
            <h5 class="option_title"><?php echo $text_expired_coupons; ?></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_expired_coupons_help; ?></span>
        </td>
        <td class="col-xs-9">
            <div class="col-md-4">
                 <a class="btn btn-default btn-mini btn-remove-expired" onClick="removeExpiredCoupons();"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $btn_clear_expired_coupons; ?></a>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <br />
            <h4><?php echo $text_module_view_settings; ?></h4>   
        </td>
    </tr>
    <tr>
        <td class="col-xs-3">
            <h5 class="option_title"><?php echo $text_menu_widget; ?></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_menu_widget_help; ?></span>
        </td>
        <td class="col-xs-9">
            <div class="col-md-4">
                <select name="<?php echo $moduleName; ?>[MenuWidget]" class="form-control">
                  <option value="yes" <?php echo (isset($moduleData['MenuWidget']) && $moduleData['MenuWidget'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
                  <option value="no" <?php echo (empty($moduleData['MenuWidget']) || $moduleData['MenuWidget']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td class="col-xs-3">
            <h5 class="option_title"><?php echo $text_dashboard_widget; ?></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_dashboard_widget_help; ?></span>
        </td>
        <td class="col-xs-9">
            <div class="col-md-4">
                <select name="<?php echo $moduleName; ?>[DashboardWidget]" disabled="disabled" class="form-control">
                  <option value="yes" <?php echo (isset($moduleData['DashboardWidget']) && $moduleData['DashboardWidget'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
                  <option value="no" <?php echo (empty($moduleData['DashboardWidget']) || $moduleData['DashboardWidget']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
                </select>
                <br />
                <?php echo $widget_2_3_x_text; ?>
            </div>
        </td>
    </tr>
    <tr>
        <td colspan="2">
            <br />
            <h4><?php echo $text_experimental_settings; ?></h4>   
        </td>
    </tr>
    <tr>
        <td class="col-xs-3">
            <h5 class="option_title"><?php echo $text_checkout_fixes; ?></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_checkout_fixes_help; ?></span>
        </td>
        <td class="col-xs-9">
            <div class="col-md-4">
                <select name="<?php echo $moduleName; ?>[CheckoutFix]" class="form-control">
                  <option value="yes" <?php echo (isset($moduleData['CheckoutFix']) && $moduleData['CheckoutFix'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
                  <option value="no" <?php echo (empty($moduleData['CheckoutFix']) || $moduleData['CheckoutFix']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
                </select>
            </div>
        </td>
    </tr>
    <tr>
        <td class="col-xs-3">
            <h5 class="option_title"><?php echo $text_applytaxes; ?></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_applytaxes_help; ?></span>
        </td>
        <td class="col-xs-9">
            <div class="col-md-4">
                <select name="<?php echo $moduleName; ?>[Taxes]" class="form-control">
                  <option value="yes" <?php echo (isset($moduleData['Taxes']) && $moduleData['Taxes'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
                  <option value="no" <?php echo (empty($moduleData['Taxes']) || $moduleData['Taxes']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
                </select>
            </div>
        </td>
    </tr>
</table>