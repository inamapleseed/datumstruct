
<select class="form-control"  name="setting[<?php echo $name_field ?>][field][status]" onchange="settingFieldsGoogle(this.value,'<?php echo $setting_id ?>' ,'<?php echo $name_field; ?>','<?php echo $sample_setting_id; ?>','<?php echo $setting_type; ?>');" >
    <option value="0"><?php echo $text_disable ?></option>
    <?php
        
        $template_setting_fields_css['option_id'] = "display:none";
        $template_setting_fields_css['attribute_id'] = "display:none";
        $template_setting_fields_css['category_id'] = "display:none";
        $template_setting_fields_css['composite'] = "display:none";
        $template_setting_fields_css['text_field'] = "display:none";
        
        
        $setting = $template_setting['setting'];

    ?>
    <?php foreach($content_parts as $template_setting_field){ ?>
        <?php if(isset($setting[$name_field]['field']['status']) && $setting[$name_field]['field']['status'] == $template_setting_field){ ?>
            <?php $template_setting_fields_css[$template_setting_field] = ""; ?>
            <option selected="" value="<?php echo $template_setting_field; ?>"><?php if(isset(${'text_setting_name_composite_element_'.$template_setting_field})){ echo ${'text_setting_name_composite_element_'.$template_setting_field}; }else{ echo $template_setting_field.' '.$text_setting_name_composite_element_self; } ?></option>
        <?php }else{ ?>
            <option value="<?php echo $template_setting_field; ?>"><?php if(isset(${'text_setting_name_composite_element_'.$template_setting_field})){ echo ${'text_setting_name_composite_element_'.$template_setting_field}; }else{ echo $template_setting_field.' '.$text_setting_name_composite_element_self; } ?></option>
        <?php } ?>
        
    <?php } ?>
</select>
        
        <script type="text/javascript"><!--

            $(document).ready(function() {
                $("select[name='setting[<?php echo $name_field ?>][field][status]']").change();
            });

        //--></script>
    
<div class="setting_fields<?php echo $setting_id.$name_field ?>" id="setting_fields_category_id<?php echo $setting_id.$name_field ?>" style="border-left: 2px solid #cccccc; padding-left: 7px; background: cornsilk; margin-top: 10px; <?php echo $template_setting_fields_css['category_id'] ?>"><?php echo $text_setting_offer_composite_category_id; ?></div>

<div class="setting_fields<?php echo $setting_id.$name_field ?>" id="setting_fields_text_field<?php echo $setting_id.$name_field ?>" style="margin-top: 5px; <?php echo $template_setting_fields_css['text_field'] ?>" >
    
    <input type="text" name="setting[<?php echo $name_field ?>][field][text_field]" value="<?php if(isset($setting[$name_field]['field']['text_field']) && $setting[$name_field]['field']['text_field']) { echo $setting[$name_field]['field']['text_field']; } else { echo ''; } ?>"  class="form-control" />
    
</div>

<table class="table table-bordered table-hover" style="margin-top: 5px; margin-bottom: 0px;">
        <tbody>
        <tr class="setting_fields<?php echo $setting_id.$name_field ?>" id="setting_fields_option_id<?php echo $setting_id.$name_field ?>" style="<?php echo $template_setting_fields_css['option_id']; ?>">
            <td colspan="2">
                <?php if($options){ ?>
                    <div class="scrollbox" style="height: 70px; overflow-y: auto">
                        
                    </div>
                <?php }else{ ?>
                <div class="alert-info" align="center"><?php echo $text_setting_offer_composite_option_id_empty ?></div>
                <?php } ?>
            </td>
        </tr>
        <tr class="setting_fields<?php echo $setting_id.$name_field ?>" id="setting_fields_attribute_id<?php echo $setting_id.$name_field ?>" style="<?php echo $template_setting_fields_css['attribute_id']; ?>">
            <td colspan="2">
                <?php if($attributes){ ?>
                    <div class="scrollbox" style="height: 150px; overflow-y: auto; width: 100%">
                        
                    </div>
                <?php }else{ ?>
                <div class="alert-info" align="center"><?php echo $text_setting_offer_composite_attribute_id_empty ?></div>
                <?php } ?>
            </td>
        </tr>
        </tbody>
    </table>