<?php if($categories){ ?>
<div class="scrollbox" style="max-height: 120px; overflow-y: auto; margin-top: 7px;">
    
    <?php foreach($categories as $category){ ?>
        
            <?php if(isset($ym_categories[ $category['category_id'] ][ $ym_category_id ] )){ ?>
                <div id="ym_categories_categories_place_checked_<?php echo $category['category_id'].'_'.$ym_category_id ?>" style="min-height: 15px;">
                    <input onclick=moveElementTo("input[name='category_id[<?php echo $ym_category_id ?>][<?php echo $category['category_id'] ?>]']","#ym_categories_categories_place_checked_<?php echo $ym_category_id; ?>","ym_categories_categories_place_checked_<?php echo $category['category_id'].'_'.$ym_category_id ?>",<?php echo $ym_category_id ?>) checked="" type="checkbox"  name="category_id[<?php echo $ym_category_id ?>][<?php echo $category['category_id'] ?>]" value="<?php echo $category['category_id'] ?>" />
                    <?php echo $category['name']; ?>
                </div>
            <?php }elseif($filter_name){ ?>
                <div id="ym_categories_categories_place_checked_<?php echo $category['category_id'].'_'.$ym_category_id ?>"  style="min-height: 15px;">
                    <input onclick=moveElementTo("input[name='category_id[<?php echo $ym_category_id ?>][<?php echo $category['category_id'] ?>]']","#ym_categories_categories_place_checked_<?php echo $ym_category_id; ?>","ym_categories_categories_place_checked_<?php echo $category['category_id'].'_'.$ym_category_id ?>",<?php echo $ym_category_id ?>) type="checkbox" name="category_id[<?php echo $ym_category_id ?>][<?php echo $category['category_id'] ?>]" value="<?php echo $category['category_id'] ?>" />
                    <?php echo $category['name']; ?>
                </div>
            <?php } ?>
            <script type="text/javascript"><!--

            $(document).ready(function() {
                if($("input[name='category_id[<?php echo $ym_category_id ?>][<?php echo $category['category_id'] ?>]']").prop("checked")){
                    moveElementTo("input[name='category_id[<?php echo $ym_category_id ?>][<?php echo $category['category_id'] ?>]']","#ym_categories_categories_place_checked_<?php echo $ym_category_id; ?>","ym_categories_categories_place_checked_<?php echo $category['category_id'].'_'.$ym_category_id ?>",0);
                }
            });

        //--></script>
        
    <?php } ?>
</div>
<?php }else{ ?>
    <div class="alert-info" style="margin-top: 7px;" align="center"><?php echo $text_ym_categories_categories_empty ?></div>
<?php } ?>