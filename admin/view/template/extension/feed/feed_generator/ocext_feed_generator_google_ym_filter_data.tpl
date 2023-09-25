


<?php if(isset($categories) && $categories){ ?>
<div class="scrollbox" style="max-height: 350px; overflow-y: auto; margin-top: 7px;">
    
    <?php if($templates_setting){ ?>
    <select class="form-control" onchange="if(this.value!=''){ $('.all_yml_export_ocext_ym_filter_data_categories_class').val(this.value); }" name="[setting_id]" >
            <option value=""><?php echo $text_ym_filter_data_all_data ?></option>
            <option value="0"><?php echo $text_ym_filter_data_templates_setting_0; ?></option>
            <?php
            
                foreach($templates_setting as $template_setting){
                
                    $setting_id = $template_setting['setting_id'];
                    $setting_tilte = $template_setting['setting']['title'];
                
                    ?>
                    <option  value="<?php echo $setting_id ?>"><?php echo $setting_tilte; ?></option>
                    
            <?php } ?>
    </select>
    <?php } ?>
    <hr>
    <table class="table table-bordered table-hover">
        <thead>
            <td><?php echo $text_ym_filter_data_categories_name; ?></td>
            <td><?php echo $tab_template_setting; ?></td>
        </thead>
        <tbody>
            <?php foreach($categories as $category){ ?>
            <tr>
                
                    <td>
                    <?php if($ym_categories && isset($ym_categories[ $category['category_id'] ]['category_id']) && $ym_categories[ $category['category_id'] ]['category_id']){ ?>
                        <div style="min-height: 25px;">
                        <input checked="" type="checkbox"  name="ocext_feed_generator_google_ym_filter_category[<?php echo $category['category_id'] ?>][category_id]" value="<?php echo $category['category_id'] ?>" />
                        <?php echo $category['name']; ?>
                        </div>
                    <?php }else{ ?>
                        <div style="min-height: 25px;">
                        <input type="checkbox"  name="ocext_feed_generator_google_ym_filter_category[<?php echo $category['category_id'] ?>][category_id]" value="<?php echo $category['category_id'] ?>" />
                        <?php echo $category['name']; ?>
                        </div>
                    <?php } ?>
                    </td>
                    <td>
                    <?php if($templates_setting){ ?>
                        <select class="all_yml_export_ocext_ym_filter_data_categories_class form-control" name="ocext_feed_generator_google_ym_filter_category[<?php echo $category['category_id'] ?>][setting_id]" >
                            <option value="0"><?php echo $text_ym_filter_data_templates_setting_0; ?></option>
                        <?php
            
                            foreach($templates_setting as $template_setting){

                                $setting_id = $template_setting['setting_id'];
                                $setting_tilte = $template_setting['setting']['title'];

                                ?>
                                <?php if($ym_categories && isset($ym_categories[ $category['category_id'] ]['setting_id']) && $ym_categories[ $category['category_id'] ]['setting_id']==$setting_id){ ?>
                                <option selected=""  value="<?php echo $setting_id ?>"><?php echo $setting_tilte; ?></option>
                                <?php }else{ ?>
                                <option  value="<?php echo $setting_id ?>"><?php echo $setting_tilte; ?></option>
                                <?php } ?>
                        <?php } ?>
                        </select>
                    <?php }else{ ?>
                        <div class="alert-info" style="margin-top: 7px;" align="center"><?php echo $text_ym_filter_data_templates_setting_empty ?></div>
                        <select hidden="" name="ocext_feed_generator_google_ym_filter_category[<?php echo $category['category_id'] ?>][setting_id]" >
                            <option value="0"></option>
                        </select>
                    <?php } ?>
                    </td>
                
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php }elseif(isset($categories) && !$categories){ ?>
    <div class="alert-info" style="margin-top: 7px;" align="center"><?php echo $text_categories_empty ?></div>
<?php }elseif(isset($manufacturers) && $manufacturers){ ?>
<div class="scrollbox" style="max-height: 350px; overflow-y: auto; margin-top: 7px;">
    
    <?php if($templates_setting){ ?>
    <select  class="form-control"  onchange="if(this.value!=''){ $('.all_yml_export_ocext_ym_filter_data_manufacturers_class').val(this.value); }" name="[setting_id]" >
            <option value=""><?php echo $text_ym_filter_data_all_data ?></option>
            <option value="0"><?php echo $text_ym_filter_data_templates_setting_0; ?></option>
            <?php
                        foreach($templates_setting as $template_setting){

                            $setting_id = $template_setting['setting_id'];
                            $setting_tilte = $template_setting['setting']['title'];

                            ?>
                    <option  value="<?php echo $setting_id ?>"><?php echo $setting_tilte; ?></option>
            <?php } ?>
        </select>
    <?php } ?>
    <hr>
    
    <table class="table table-bordered table-hover">
        <thead>
            <td><?php echo $text_ym_filter_data_manufacturers_name; ?></td>
            <td><?php echo $tab_template_setting; ?></td>
        </thead>
        <tbody>
            <?php foreach($manufacturers as $manufacturer){ ?>
            <tr>
                
                    <td>
                    <?php if($ym_manufacturers && isset($ym_manufacturers[ $manufacturer['manufacturer_id'] ]['manufacturer_id']) && $ym_manufacturers[ $manufacturer['manufacturer_id'] ]['manufacturer_id']){ ?>
                        <div style="min-height: 25px;">
                        <input checked="" type="checkbox"  name="ocext_feed_generator_google_ym_filter_manufacturers[<?php echo $manufacturer['manufacturer_id'] ?>][manufacturer_id]" value="<?php echo $manufacturer['manufacturer_id'] ?>" />
                        <?php echo $manufacturer['name']; ?>
                        </div>
                    <?php }else{ ?>
                        <div style="min-height: 25px;">
                        <input type="checkbox"  name="ocext_feed_generator_google_ym_filter_manufacturers[<?php echo $manufacturer['manufacturer_id'] ?>][manufacturer_id]" value="<?php echo $manufacturer['manufacturer_id'] ?>" />
                        <?php echo $manufacturer['name']; ?>
                        </div>
                    <?php } ?>
                    </td>
                    <td>
                    <?php if($templates_setting){ ?>
                        <select class="all_yml_export_ocext_ym_filter_data_manufacturers_class form-control" name="ocext_feed_generator_google_ym_filter_manufacturers[<?php echo $manufacturer['manufacturer_id'] ?>][setting_id]" >
                            <option value="0"><?php echo $text_ym_filter_data_templates_setting_0; ?></option>
                                <?php foreach($templates_setting as $template_setting){

                                    $setting_id = $template_setting['setting_id'];
                                    $setting_tilte = $template_setting['setting']['title'];

                                    ?>
                                <?php if($ym_manufacturers && isset($ym_manufacturers[ $manufacturer['manufacturer_id'] ]['setting_id']) && $ym_manufacturers[ $manufacturer['manufacturer_id'] ]['setting_id']==$setting_id){ ?>
                                    <option selected=""  value="<?php echo $setting_id ?>"><?php echo $setting_tilte; ?></option>
                                <?php }else{ ?>
                                    <option  value="<?php echo $setting_id ?>"><?php echo $setting_tilte; ?></option>
                                <?php } ?>
                        <?php } ?>
                        </select>
                    <?php }else{ ?>
                        <div class="alert-info" style="margin-top: 7px;" align="center"><?php echo $text_ym_filter_data_templates_setting_empty ?></div>
                        <select hidden="" name="ocext_feed_generator_google_ym_filter_manufacturers[<?php echo $manufacturer['manufacturer_id'] ?>][setting_id]" >
                            <option value="0"></option>
                        </select>
                    <?php } ?>
                    </td>
                
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php }elseif(isset($manufacturers) && !$manufacturers){ ?>
    <div class="alert-info" style="margin-top: 7px;" align="center"><?php echo $text_manufacturers_empty ?></div>
<?php }elseif(isset($attributes) && $attributes){ ?>
<div class="scrollbox" style="max-height: 350px; overflow-y: auto; width: 100%">
    <?php foreach($attributes as $attribute_group_id=>$attribute_group){ ?>

        <?php if(isset($attribute_group_name)){ ?>
            <?php unset($attribute_group_name); ?>
        <?php } ?>

        <?php foreach($attribute_group as $attribute_id=>$attribute){ ?>
            <?php if(!isset($attribute_group_name)){ ?>
                <?php $attribute_group_name = $attribute['attribute_group']; ?>
                <h4 style="margin-top: 15px; margin-bottom: 10px;"><?php echo $attribute_group_name ?></h4>
            <?php } ?>

            <div>

                <?php if(isset($ym_attributes[$attribute_group_id.'___'.$attribute_id]) && $ym_attributes[$attribute_group_id.'___'.$attribute_id]==$attribute_group_id.'___'.$attribute_id){ ?>
                <input checked="" type="checkbox" name="ocext_feed_generator_google_ym_filter_attributes[<?php echo $attribute_group_id.'___'.$attribute_id ?>]" value="<?php echo $attribute_group_id.'___'.$attribute_id ?>" />
                    <?php echo $attribute['name']; ?>
                <?php }else{ ?>
                    <input type="checkbox" name="ocext_feed_generator_google_ym_filter_attributes[<?php echo $attribute_group_id.'___'.$attribute_id ?>]" value="<?php echo $attribute_group_id.'___'.$attribute_id ?>" />
                    <?php echo $attribute['name']; ?>
                <?php } ?>
            </div>

        <?php } ?>

    <?php } ?>
</div>
<?php }elseif(isset($attributes) && !$attributes){ ?>
    <div class="alert-info" style="margin-top: 7px;" align="center"><?php echo $text_attributes_empty ?></div>
<?php }elseif(isset($options) && $options){ ?>
<div class="scrollbox" style="max-height: 350px; overflow-y: auto; width: 100%">
    <?php foreach($options as $option_id=>$option){ ?>

        <div>


            <?php if(isset($ym_options[$option_id]) && $ym_options[$option_id]==$option_id){ ?>
                <input type="checkbox" checked="" name="ocext_feed_generator_google_ym_filter_options[<?php echo $option_id ?>]" value="<?php echo $option_id ?>" />
                <?php echo $option['name']; ?>
            <?php }else{ ?>
                <input type="checkbox" name="ocext_feed_generator_google_ym_filter_options[<?php echo $option_id ?>]" value="<?php echo $option_id ?>" />
                <?php echo $option['name']; ?>
            <?php } ?>

        </div>

    <?php } ?>
</div>
<?php }elseif(isset($options) && !$options){ ?>
    <div class="alert-info" style="margin-top: 7px;" align="center"><?php echo $text_options_empty ?></div>
<?php } ?>