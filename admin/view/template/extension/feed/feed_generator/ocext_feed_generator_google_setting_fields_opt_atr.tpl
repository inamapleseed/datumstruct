    <?php if($options){ ?>
        <?php foreach($options as $option_id=>$option){ ?>

        <div>


            <?php if(isset($setting[$name_field]['field']['option_id']) && $setting[$name_field]['field']['option_id']==$option_id){ ?>
                <input type="radio" checked="" name="setting[<?php echo $name_field ?>][field][option_id]" value="<?php echo $option_id ?>" />
                <?php echo $option['name']; ?>
            <?php }else{ ?>
                <input type="radio" name="setting[<?php echo $name_field ?>][field][option_id]" value="<?php echo $option_id ?>" />
                <?php echo $option['name']; ?>
            <?php } ?>

        </div>

        <?php } ?>
    <?php } ?>

    <?php if($attributes){ ?>
        <div class="scrollbox" style="height: 150px; overflow-y: auto; width: 100%">

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

                        <?php if(isset($setting[$name_field]['field']['attribute_id']) && $setting[$name_field]['field']['attribute_id']==$attribute_group_id.'___'.$attribute_id){ ?>
                            <input checked="" type="radio" name="setting[<?php echo $name_field ?>][field][attribute_id]" value="<?php echo $attribute_group_id.'___'.$attribute_id ?>" />
                            <?php echo $attribute['name']; ?>
                        <?php }else{ ?>
                            <input type="radio" name="setting[<?php echo $name_field ?>][field][attribute_id]" value="<?php echo $attribute_group_id.'___'.$attribute_id ?>" />
                            <?php echo $attribute['name']; ?>
                        <?php } ?>
                    </div>

                <?php } ?>

            <?php } ?>

        </div>
    <?php } ?>