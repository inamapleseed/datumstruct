
    
    <div id="form-ocext_feed_generator_google" class="container-fluid">
        <div align="right">
                <a onclick="setSettingGoogle();" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i>  <?php echo $button_save; ?></a>
        </div>
        <hr>
    <style>
        
        .small_text{
            font-size: 9px;
            color: darkgray;
        }
        .small_text:hover{
            font-size: 9px;
            color: black;
        }
        .scrollbox div:nth-child(2n+1){
           background: lemonchiffon;
        }
        .scrollbox table tbody tr td div:nth-child(2n+1){
           background: none;
        }
        .help-box{ border-left: 2px solid #cccccc; padding-left: 7px; margin-bottom: 10px; background: cornsilk}
        
    </style>

                        <?php $setting_id = 0; ?>
                        
                        <?php $setting = array(); ?>
                        
                        <div id="tab-template_setting_google<?php echo $setting_id ?>" class="tab-pane tab-template_setting-content-google active" >
                            <img src="<?php echo HTTP_SERVER; ?>/view/image/ocext/loading.gif" />
                        </div>
                        
                        <?php if($template_setting){ ?>
                        
                            <?php foreach($template_setting as $template_setting_row){ ?>
                            
                                <?php $setting_id = $template_setting_row['setting_id']; ?>
                                
                                <div id="tab-template_setting_google<?php echo $setting_id ?>" class="tab-pane tab-template_setting-content-google active" >
                                    <img src="<?php echo HTTP_SERVER; ?>/view/image/ocext/loading.gif" />
                                </div>
                                
                            <?php } ?>
                            
                    <?php } ?>
                
    
</div>
<script type="text/javascript"><!--

$(document).ready(function() {
    
    getTemplateSettingGoogle(<?php echo $setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_product_id ?>,0);
    
});

function setSettingGoogle(){

    
    $.ajax({
            type: 'post',
            url: 'index.php?route=feed/ocext_feed_generator_google/setSettings&&token=<?php echo $token; ?>',
            dataType: 'json',
            data: $('#form-ocext_feed_generator_google select, #form-ocext_feed_generator_google input[type=\'radio\']:checked,  #form-ocext_feed_generator_google input[type=\'checkbox\']:checked, #form-ocext_feed_generator_google input[type=\'hidden\'], #form-ocext_feed_generator_google input[type=\'text\'], #form-ocext_feed_generator_google textarea'),
            success: function(response) {
                if(response['message']=='error'){
                    alert(response['text']);
                }else{
                    window.location.reload();
                }
            },
            <?php if ($debug) { ?>
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
            <?php } ?>
    });

}

function  settingFieldsGoogle(value_selected,setting_id,name_field){
    $('#form-ocext_feed_generator_google .setting_fields'+setting_id+name_field).hide();
    if(value_selected=='attribute_id' || value_selected=='category_id' || value_selected=='option_id' || value_selected=='composite'){
        $('#form-ocext_feed_generator_google #setting_fields_'+value_selected+setting_id+name_field).show();
    }
}

function getSettingFieldsGoogle(sample_setting_id,setting_type,setting_id,name_field){
    
    $('#form-ocext_feed_generator_google .setting_'+name_field+setting_id).before('<div id="ocext_loading'+name_field+setting_id+'"><img src="<?php echo HTTP_SERVER; ?>/view/image/ocext/loading.gif" /></div>');
    $.ajax({
            type: 'GET',
            url: 'index.php?route=feed/ocext_feed_generator_google/getSettingFields&sample_setting_id='+sample_setting_id+'&setting_type='+setting_type+'&name_field='+name_field+'&setting_id='+setting_id+'&token=<?php echo $token; ?>',
            dataType: 'html',
            success: function(response) {
                $('#ocext_loading'+name_field+setting_id).remove();
                $('#form-ocext_feed_generator_google .setting_'+name_field+setting_id).before(response);
            },
            <?php if ($debug) { ?>
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
            <?php } ?>
    });
    
}

function getTemplateSettingGoogle(setting_id,setting_type,setting_product_id,sample_setting_id){

    $.ajax({
            type: 'GET',
            url: 'index.php?route=feed/ocext_feed_generator_google/getTemplateSetting&sample_setting_id='+sample_setting_id+'&setting_product_id='+setting_product_id+'&setting_type='+setting_type+'&setting_id='+setting_id+'&token=<?php echo $token; ?>',
            dataType: 'html',
            success: function(response) {
                $('.tab-template_setting-content-google').empty();
                $('#tab-template_setting_google'+setting_id).html('<img src="<?php echo HTTP_SERVER; ?>/view/image/ocext/loading.gif" />');
                $('#tab-template_setting_google'+setting_id).html(response);
                
            },
            <?php if ($debug) { ?>
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
            <?php } ?>
    });

}



//--></script>