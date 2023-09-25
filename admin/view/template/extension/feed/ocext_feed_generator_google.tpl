<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
              <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
              <?php foreach ($breadcrumbs as $breadcrumb) { ?>
              <li><a  href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
              <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
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
    
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        
    <div class="panel panel-default">
    
    <div class="panel-body">
        
        <ul  class="nav nav-tabs" >
            <li class="active"><a  data-toggle="tab"  href="#tab-template-setting" ><?php echo $tab_template_setting; ?></a></li>
            <li><a onclick="openYmFilterData('no_selected');" data-toggle="tab"  href="#tab-ym-filter-data" ><?php echo $tab_ym_filter_data; ?></a></li>
            <li><a  data-toggle="tab"  href="#tab-ym-categories" ><?php echo $tab_ym_categories; ?></a></li>
            <li><a  data-toggle="tab"  href="#tab-general-setting" ><?php echo $tab_general_setting; ?></a></li>
            <li onclick="getWelcomeWindow();"><a  data-toggle="tab" href="#tab-welcome-extecom"  ><?php echo $tab_welcome_extecom; ?></a></li>
            
        </ul>
        
        <div class="tab-content">
            
            
        <div id="tab-ym-categories" class="tab-pane" >
            <div align="right">
                <a onclick="$('#form-ym-categories').submit();" type="submit" form="form-ups" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i>  <?php echo $button_save; ?></a>
                <br><br>
            </div>
            
            <!--=============filter=================-->
            
            
            
            <form action="<?php echo $action_ym_categories_filter; ?>" method="post" enctype="multipart/form-data" id="form-ym-categories-filter">
            <div class="well">
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label" for="ym_category_last_child"><?php echo $text_ym_categories_filter_ym_category_last_child; ?></label>
                  <input type="text" name="ym_category_last_child" value="<?php echo $ym_category_last_child; ?>" placeholder="<?php echo $text_ym_categories_filter_ym_category_last_child; ?>" id="ym_category_last_child" class="form-control" />
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label" for="filter_category_id"><?php echo $text_ym_categories_filter_category_id; ?></label>
                  <select name="filter_category_id" id="category_id" class="form-control">
                    <option value=""><?php echo $text_ym_categories_filter_category_id_; ?></option>
                    <?php if ($filter_category_id) { ?>
                        <option value="1" selected="selected"><?php echo $text_ym_categories_filter_category_id_1; ?></option>
                    <?php }else{ ?>
                        <option value="1" ><?php echo $text_ym_categories_filter_category_id_1; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col-sm-4">
                <div class="form-group">
                  <label class="control-label" for="filter_ym_status"><?php echo $text_ym_categories_filter_status; ?></label>
                  <select name="filter_ym_status" id="ym_status" class="form-control">
                    <option value=""><?php echo $text_ym_categories_filter_status_; ?></option>
                    <?php if ($filter_ym_status==='0') { ?>
                        <option value="0" selected="selected"><?php echo $text_ym_categories_filter_status_1; ?></option>
                        <option value="1" ><?php echo $text_ym_categories_filter_status_2; ?></option>
                    <?php }elseif ($filter_ym_status==1){ ?>
                        <option value="0" ><?php echo $text_ym_categories_filter_status_1; ?></option>
                        <option value="1" selected="selected"><?php echo $text_ym_categories_filter_status_2; ?></option>
                    <?php }else{ ?>
                        <option value="0" ><?php echo $text_ym_categories_filter_status_1; ?></option>
                        <option value="1" ><?php echo $text_ym_categories_filter_status_2; ?></option>
                    <?php } ?>
                  </select>
                </div>
                <a onclick="$('#form-ym-categories-filter').submit();" title="<?php echo $button_filter; ?>" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></a>
              </div>
            </div>
          </div>
        </form>
            <!--=============endFilter=================-->
            
            <form action="<?php echo $action_ym_categories; ?>" method="post" enctype="multipart/form-data" id="form-ym-categories">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead>
                        <tr>
                            <td class="text-left">
                                <?php echo $column_ym_category_path; ?>
                            </td>
                            <td class="text-left">
                                <?php echo $column_ym_category_last_child; ?>
                            </td>
                            <td class="text-left">
                                <?php echo $column_category_id; ?>
                            </td>
                            <td class="text-center">
                                <?php echo $column_ym_status; ?> <input type="checkbox" onclick="if(this.checked==true){ $('input[value=\'1\']').prop('checked', this.checked); }else{ $('input[value=\'0\']').prop('checked', true); }" />
                            </td>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if ($ym_categories) { ?>
                        <?php foreach ($ym_categories as $ym_category) { ?>
                        <tr>
                          <td class="text-left">
                              <input class="form-control"  name="ym_path[<?php echo $ym_category['ym_category_id']; ?>]" type="text" value="<?php echo $ym_category['ym_category_path']; ?>" placeholder="Категория Я.Маркет" />
                          </td>
                          <td class="text-left"><?php echo $ym_category['ym_category_last_child']; ?></td>
                          <td class="text-left">
                              <input placeholder="Find a category" class="form-control" onkeyup="getCategories(<?php echo $ym_category['ym_category_id'] ?>,this.value)" />
                                <div id="ym_categories_categories_place_<?php echo $ym_category['ym_category_id'] ?>"></div>
                                <div class="form-control" id="ym_categories_categories_place_checked_<?php echo $ym_category['ym_category_id'] ?>" style="border-top:1px solid #ccc; min-height: 20px; margin-top: 7px; height: auto; max-height: 150px; overflow-y: auto"></div>
                                <a style="margin-top: 5px; display: none;" id="ym_categories_save_<?php echo $ym_category['ym_category_id'] ?>" onclick="$('#form-ym-categories').submit();" type="submit" form="form-ups" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i>  <?php echo $button_save; ?></a>
                                <script type="text/javascript"><!--

                                    $(document).ready(function() {
                                        getCategories(<?php echo $ym_category['ym_category_id'] ?>,'');
                                    });

                                //--></script>
                          </td>
                          <td class="text-left"><?php if ($ym_category['status']) { ?>
                            <input type="radio" name="ym_status[<?php echo $ym_category['ym_category_id']; ?>]" value="1" checked="checked" /> <?php echo $text_ym_status_1; ?>
                            <br><input type="radio" name="ym_status[<?php echo $ym_category['ym_category_id']; ?>]" value="0" /> <?php echo $text_ym_status_0; ?>
                            <?php } else { ?>
                            <input type="radio" name="ym_status[<?php echo $ym_category['ym_category_id']; ?>]" value="1"  /> <?php echo $text_ym_status_1; ?>
                            <br><input type="radio" name="ym_status[<?php echo $ym_category['ym_category_id']; ?>]" value="0" checked="checked" /> <?php echo $text_ym_status_0; ?>
                            <?php } ?>
                          </td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                        <tr>
                          <td class="text-center" colspan="8"><?php echo $text_no_gcats; ?></td>
                        </tr>
                        <?php } ?>
                      </tbody>
                    </table>
                  </div>
            </form>
            <div class="row">
                <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                <div class="col-sm-6 text-right"><?php echo $results; ?></div>
            </div>
            <hr>
            
            <a href="https://support.google.com/merchants/answer/1705911" target="_blank">Текущий список категорий Google</a>
            <hr>
            <div class="well">
                <div class="row">
                <div class="col-sm-4">
                    
                    <a onclick="this.href = this.href + '&google_categories_language_code=' + $('select[name=\'google_categories_language_code\']').val();" data-toggle="tooltip" title="<?php echo $text_google_categories_language; ?>" href="<?php echo $href_google_categories_update ?>" class="btn btn-primary">  <?php echo $text_google_categories_language; ?></a>
                    
                </div>
                <div class="col-sm-8">
                    <select class="form-control" name="google_categories_language_code">
                        <option value="0"><?php echo $text_select; ?> language</option>
                        <?php foreach($google_categories_language as $google_categories_language_code => $google_categories_language_title){ ?>

                            <option value="<?php echo $google_categories_language_code; ?>"><?php echo $google_categories_language_title; ?></option>

                        <?php } ?>
                    </select>
                    
                </div>
                </div>
            </div>
        </div>
            
            
        <div id="tab-ym-filter-data" class="tab-pane" >
            <form id="form-ym-filter-data" action="<?php echo $action_ym_filter_data; ?>" method="post" enctype="multipart/form-data">
                <div align="right" class="filter_data_box">
                    <a onclick="$('#form-ym-filter-data').submit();" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i>  <?php echo $button_save; ?></a>
                    <br><br>
                </div>
                <div>

                    
                    
                    <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <?php $setting_field = 'filter_datas'; ?>
                        <tr>
                            <td><?php echo ${'text_ym_filter_data_'.$setting_field}; ?></td>
                            <td>
                                <select onchange="openYmFilterData(this.value); $('input[name=ocext_feed_generator_google_ym_filter_data_filter_data_name]').val($('select[name=ocext_feed_generator_google_ym_filter_data_filter_data_group_id] option:selected').text()) " class="form-control" name="ocext_feed_generator_google_ym_filter_data_filter_data_group_id">
                                    
                                    <option value="no_selected"><?php echo $text_select; ?></option>
                                    
                                    <?php foreach(${$setting_field} as ${$setting_field.'_value'} => ${$setting_field.'_title'}){ ?>
                                        <?php if($filter_data_group_id === ${$setting_field.'_value'}){ ?>
                                            <option selected="" value="<?php echo ${$setting_field.'_value'}; ?>"><?php echo ${$setting_field.'_title'}; ?></option>
                                        <?php }else{ ?>
                                            <option value="<?php echo ${$setting_field.'_value'}; ?>"><?php echo ${$setting_field.'_title'}; ?></option>
                                        <?php } ?>

                                    <?php } ?>

                                </select>
                                        <br> 
                                    <label class="filter_data_box"><?php echo $text_ym_filter_data_new_filter_name_title ?></label>
                                <?php if(!$filter_data_group_id){ ?>
                                    <input placeholder="<?php echo $text_ym_filter_data_new_filter_name ?>" value="<?php echo $text_ym_filter_data_new_filter ?>" class="form-control filter_data_box" name="ocext_feed_generator_google_ym_filter_data_filter_data_name" />
                                <?php }else{ ?>
                                    <input placeholder="<?php echo $text_ym_filter_data_new_filter_name ?>" class="form-control filter_data_box" name="ocext_feed_generator_google_ym_filter_data_filter_data_name" />
                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                    </div>
                    
                    
                    <div class="filter_data_box" style="display: none">
                    
                       
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <td class="text-left">
                                    <?php echo $text_ym_filter_data_categories; ?>
                                </td>
                                <td class="text-left" width='65%'>
                                    <div id="ym_filter_data_place_categories"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left">
                                    <?php echo $text_ym_filter_data_prioritet; ?>
                                </td>
                                <td class="text-left" width='65%'>
                                    
                                    <input name="ocext_feed_generator_google_ym_filter_prioritet[categories]" value="<?php echo $ocext_feed_generator_google_ym_filter_prioritet['categories'] ?>" class="form-control" />
                                    
                                </td>
                            </tr>
                          </tbody>
                        </table>
                    </div> 
                <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <td class="text-left">
                                    <?php echo $text_ym_filter_data_manufacturers; ?>
                                </td>
                                <td class="text-left" width='65%'>
                                    <div id="ym_filter_data_place_manufacturers"></div>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-left">
                                    <?php echo $text_ym_filter_data_prioritet; ?>
                                </td>
                                <td class="text-left" width='65%'>
                                    
                                    <input name="ocext_feed_generator_google_ym_filter_prioritet[manufacturers]" value="<?php echo $ocext_feed_generator_google_ym_filter_prioritet['manufacturers'] ?>" class="form-control" />
                                    
                                </td>
                            </tr>
                          </tbody>
                        </table>
                    </div>  
                <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <td class="text-left">
                                    <?php echo $text_ym_filter_data_attributes; ?>
                                </td>
                                <td class="text-left" width='65%'>
                                    <div id="ym_filter_data_place_attributes"></div>
                                </td>
                            </tr>
                          </tbody>
                        </table>
                    </div>  
                <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <tbody>
                            <tr>
                                <td class="text-left">
                                    <?php echo $text_ym_filter_data_options; ?>
                                </td>
                                <td class="text-left" width='65%'>
                                    <div id="ym_filter_data_place_options"></div>
                                </td>
                            </tr>
                          </tbody>
                        </table>
                    </div>  
                
            
                    </div>

                </div>
            </form>
        </div>
            
            
            
            
        <div id="tab-template-setting" class="tab-pane active" >
            <form id="form-template-setting" action="<?php echo $action_template_setting; ?>" method="post" enctype="multipart/form-data">
            <div align="right">
                <a onclick="$('#form-template-setting').submit();" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i>  <?php echo $button_save; ?></a>
            <br><br>
            </div>
            
            
            <div class="row">
                <div class="col-sm-2">
                    <ul class="nav nav-pills nav-stacked">
                        <li onclick="getTemplateSettingGoogle(0,'<?php echo $setting_type ?>',<?php echo $setting_product_id ?>,0); $('#form-template-setting').attr('action','<?php echo $action_template_setting; ?>'+'&setting_id=0')" class="active"><a data-toggle="tab" id='tab-template_setting_nav0' href="#tab-template_setting0"> <?php echo $tab_template_setting_default; ?></a></li>
                        <?php if($template_setting){ ?>
                        
                            <?php foreach($template_setting as $setting_row){ ?>
                            
                                <?php $setting_id = $setting_row['setting_id']; ?>

                                <?php $setting = $setting_row['setting']; ?>
                            
                                <?php
                                
                                    $css_template_setting = '';
                                    
                                    if($setting['status']){
                                    
                                        $css_template_setting = 'border-left:3px solid #1eff00;';
                                        
                                    }else{
                                    
                                        $css_template_setting = 'border-left:3px solid #cccccc;';
                                        
                                    }
                                    
                                ?>
                                <li onclick="getTemplateSettingGoogle(<?php echo $setting_id;?>,'<?php echo $setting_type ?>',<?php echo $setting_product_id ?>,0); $('#form-template-setting').attr('action','<?php echo $action_template_setting; ?>'+'&setting_id=<?php echo $setting_id;?>')"><a data-toggle="tab" id='tab-template_setting_nav<?php echo $setting_id;?>' style="<?php echo $css_template_setting; ?>" href="#tab-template_setting<?php echo $setting_id;?>"> <?php echo $setting['title']; ?></a></li>
                            
                            <?php } ?>
                            
                        <?php } ?>
                    </ul>
                </div>
                <div class="col-sm-10">				
                    <div class="tab-content">
                        <?php $setting_id = 0; ?>
                        
                        <?php $setting = array(); ?>
                        
                        <div id="tab-template_setting<?php echo $setting_id ?>" class="tab-pane tab-template_setting-content active" >
                            <img src="<?php echo HTTP_SERVER; ?>/view/image/ocext/loading.gif" />
                        </div>
                        
                        <?php if($template_setting){ ?>
                        
                            <?php foreach($template_setting as $template_setting_row){ ?>
                            
                                <?php $setting_id = $template_setting_row['setting_id']; ?>
                                
                                <div id="tab-template_setting<?php echo $setting_id ?>" class="tab-pane tab-template_setting-content active" >
                                    
                                </div>
                                
                            <?php } ?>
                            
                        <?php } ?>
                    </div>
                </div>
            </div>
            </form>
        </div>
         
            
            <div id="tab-general-setting" class="tab-pane" >
                <div align="right">
                    <a onclick="$('#form-general-setting').submit();" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i>  <?php echo $button_save; ?></a>
                    <br><br>
                </div>

                <form action="<?php echo $action_general_setting; ?>" method="post" enctype="multipart/form-data" id="form-general-setting">
                    <table class="table table-bordered table-hover">
                        <tr>
                            <td><?php echo $text_general_setting_status; ?></td>
                            <td>
                                <select name="ocext_feed_generator_google_status" class="form-control">
                                    <?php if ($ocext_feed_generator_google_status) { ?>
                                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                        <option value="0"><?php echo $text_disabled; ?></option>
                                    <?php } else { ?>
                                        <option value="1"><?php echo $text_enabled; ?></option>
                                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <?php $general_setting_row = 'count_custom_elements'; ?>
                            <td><?php echo ${'text_general_setting_'.$general_setting_row}; ?></td>
                            <td>
                                <input name="ocext_feed_generator_google_general_setting[<?php echo $general_setting_row; ?>]" value="<?php if(isset($ocext_feed_generator_google_general_setting[$general_setting_row])) { echo $ocext_feed_generator_google_general_setting[$general_setting_row]; } else { echo ''; } ?>" class="form-control" />
                            </td>
                        </tr>
                        <tr>
                            <?php $general_setting_row = 'name'; ?>
                            <td><?php echo ${'text_general_setting_'.$general_setting_row}; ?></td>
                            <td>
                                <input name="ocext_feed_generator_google_general_setting[<?php echo $general_setting_row; ?>]" value="<?php if(isset($ocext_feed_generator_google_general_setting[$general_setting_row])) { echo $ocext_feed_generator_google_general_setting[$general_setting_row]; } else { echo ''; } ?>" class="form-control" />
                            </td>
                        </tr>
                        <tr>
                            <?php $general_setting_row = 'store_url'; ?>
                            <td><?php echo ${'text_general_setting_'.$general_setting_row}; ?></td>
                            <td>
                                <input name="ocext_feed_generator_google_general_setting[<?php echo $general_setting_row; ?>]" value="<?php if(isset($ocext_feed_generator_google_general_setting[$general_setting_row])) { echo $ocext_feed_generator_google_general_setting[$general_setting_row]; } else { echo ''; } ?>" class="form-control" />
                            </td>
                        </tr>
                        
                        <tr>
                            <td colspan="2"><?php echo $text_ocext_plugin_microdata; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo $text_ocext_plugin_microdata_product_status; ?></td>
                        <td>
                            <select name="ocext_plugin_microdata_product_status" class="form-control">
                                <?php if ($ocext_plugin_microdata_product_status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        </tr>
                        <tr>
                            <td><?php echo $text_ocext_plugin_microdata_breadcrumps_status; ?></td>
                        <td>
                            <select name="ocext_plugin_microdata_breadcrumps_status" class="form-control">
                                <?php if ($ocext_plugin_microdata_breadcrumps_status) { ?>
                                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                    <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                    <option value="1"><?php echo $text_enabled; ?></option>
                                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                        </tr>
                        
                    </table>
                    <h3><?php echo $tab_general_setting_2 ?></h3>     
                    <table class="table table-bordered table-hover">
                        
                        <thead>
                            <tr>
                                            <td>
                                                <?php echo $text_general_setting_filter_data_template; ?>

                                                

                                            </td>
                                            <td><?php echo $text_general_setting_filter_data_file_and_link; ?></td>
                            </tr>
                        </thead>
                        
                        <?php if($filter_datas_general_setting){ ?>
                        
                            <?php foreach($filter_datas_general_setting as $filter_data_group_id_general_setting => $filter_data_name_general_setting){ ?>
                        
                                        <tr>
                                            <td>
                                                <?php $general_setting_row = 'filter_datas_general_setting'; ?>

                                                <?php echo $filter_data_name_general_setting; ?>
                                                <input hidden="" name="ocext_feed_generator_google_general_setting[filter_data][<?php echo $filter_data_group_id_general_setting ?>][filter_data_group_id]" value="<?php echo $filter_data_group_id_general_setting ?>" />

                                            </td>
                                            <td>




                                                <table class="table table-bordered table-hover">
                                                
                                                
                                                
                                                <!--
                                                В контроллере массиво с $setting_field - <значение> = <название>
                                                -->
                                                <?php $general_setting_row = 'content_language_id'; ?>
                                                <tr>
                                                    <td width='30%'><?php echo ${'text_general_setting_'.$general_setting_row}; ?></td>
                                                    <td>
                                                        <select class="form-control" name="ocext_feed_generator_google_general_setting[filter_data][<?php echo $filter_data_group_id_general_setting ?>][<?php echo $general_setting_row ?>]">
                                                            <?php foreach(${$general_setting_row} as ${$general_setting_row.'_value'} => ${$general_setting_row.'_title'}){ ?>

                                                                <?php if(isset($ocext_feed_generator_google_general_setting['filter_data'][$filter_data_group_id_general_setting][$general_setting_row]) && $ocext_feed_generator_google_general_setting['filter_data'][$filter_data_group_id_general_setting][$general_setting_row] == ${$general_setting_row.'_value'}){ ?>
                                                                    <option selected="" value="<?php echo ${$general_setting_row.'_value'}; ?>"><?php echo ${$general_setting_row.'_title'}; ?></option>
                                                                <?php }else{ ?>
                                                                    <option value="<?php echo ${$general_setting_row.'_value'}; ?>"><?php echo ${$general_setting_row.'_title'}; ?></option>
                                                                <?php } ?>

                                                            <?php } ?>

                                                        </select>
                                                    </td>
                                                </tr>

                                               

                                                <?php $general_setting_row = 'path_token_export'; ?>
                                                <tr>
                                                    <td><?php echo ${'text_general_setting_'.$general_setting_row}; ?>
                                                        <br><?php echo $text_general_setting_copy  ?>: <input style="width:60%"  class="form-control"  readonly="" onclick="$(this).select()" value="<?php echo HTTP_CATALOG.'index.php?route='.$path_oc.'/ocext_feed_generator_google&token='.$ocext_feed_generator_google_general_setting['filter_data'][$filter_data_group_id_general_setting][$general_setting_row] ?>"/>
                                                    </td>
                                                    <td>
                                                        <?php echo HTTP_CATALOG.'index.php?route='.$path_oc.'/ocext_feed_generator_google&token='; ?><input name="ocext_feed_generator_google_general_setting[filter_data][<?php echo $filter_data_group_id_general_setting ?>][<?php echo $general_setting_row; ?>]" value='<?php echo $ocext_feed_generator_google_general_setting['filter_data'][$filter_data_group_id_general_setting][$general_setting_row] ?>'/>
                                                        <?php if(isset($text_general_setting_empty_token[$filter_data_group_id_general_setting])){ ?>
                                                            <br><b style="color:red"><?php echo $text_general_setting_empty_token[$filter_data_group_id_general_setting] ?></b>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                                
                                                 <?php $general_setting_row = 'filename_export'; ?>
                                                <tr>
                                                    <td><?php echo ${'text_general_setting_'.$general_setting_row}; ?>
                                                        <br><?php echo $text_general_setting_copy  ?>: <input style="width:60%"  readonly="" class="form-control" onclick="$(this).select()" value="<?php echo HTTP_CATALOG.''.$ocext_feed_generator_google_general_setting['filter_data'][$filter_data_group_id_general_setting][$general_setting_row].'.xml' ?>"/>
                                                    </td>
                                                    <td>
                                                        <?php echo HTTP_CATALOG.''; ?><input name="ocext_feed_generator_google_general_setting[filter_data][<?php echo $filter_data_group_id_general_setting ?>][<?php echo $general_setting_row; ?>]" value='<?php echo $ocext_feed_generator_google_general_setting['filter_data'][$filter_data_group_id_general_setting][$general_setting_row]; ?>'/>.xml
                                                    </td>
                                                </tr>
                                                
                                                </table>




                                            </td>
                                        </tr>   
                            <?php } ?>
                        <?php }else{ ?>
                        
                                <tr><td colspan="2">
                            <?php echo $text_general_setting_filter_data_empty ?>
                            </td></tr>
                        
                        
                        
                        
                        
                        <?php } ?>
                        
                    </table>
                                
                                
                </form>
                
                
                
            </div>        
        
            <div id="tab-welcome-extecom" class="tab-pane" >
                
                <div id="tab-welcome-extecom-window"></div>
                <hr>
                <?php if ((!$error_warning) && (!$success)) { ?>
                <div id="ocext_notification" class="alert alert-info"><i class="fa fa-info-circle"></i>
                        <div id="ocext_loading"><img src="<?php echo HTTP_SERVER; ?>/view/image/ocext/loading.gif" /></div>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                <?php } ?>
                
            </div>
                                                        
        </div>
        
    </div>        
    </div>
</div>
</div>
<script type="text/javascript"><!--

$(document).ready(function() {
    
    $("a[href=\'#<?php echo $open_tab ?>\']").click();
    
    getTemplateSettingGoogle(0,'<?php echo $setting_type ?>',<?php echo $setting_product_id ?>,0);
    
});

function  settingFieldsGoogle(value_selected,setting_id,name_field,sample_setting_id,setting_type){
    $('.setting_fields'+setting_id+name_field).hide();
    if(value_selected=='category_id' || value_selected=='composite' || value_selected=='text_field'){
        $('#setting_fields_'+value_selected+setting_id+name_field).show();
    }
    if(value_selected=='option_id' || value_selected=='attribute_id'){
        settingFieldsGoogleGetOptOrAtr(value_selected,setting_id,name_field,sample_setting_id,setting_type);
        $('#setting_fields_'+value_selected+setting_id+name_field).show();
    }
}

function  settingFieldsGoogleGetOptOrAtr(value_selected,setting_id,name_field,sample_setting_id,setting_type){
    $('#setting_fields_'+value_selected+setting_id+name_field+" td div.scrollbox").before('<div id="ocext_loading'+value_selected+name_field+setting_id+'"><img src="<?php echo HTTP_SERVER; ?>/view/image/ocext/loading.gif" /></div>');
    $.ajax({
            type: 'GET',
            url: 'index.php?route=<?php echo $path_oc ?>/ocext_feed_generator_google/settingFieldsGoogleGetOptOrAtr&sample_setting_id='+sample_setting_id+'&setting_type='+setting_type+'&setting_id='+setting_id+'&name_field='+name_field+'&value_selected='+value_selected+'&token=<?php echo $token; ?>',
            dataType: 'html',
            success: function(response) {
                $('#setting_fields_'+value_selected+setting_id+name_field+" td div.scrollbox").html(response);
                $('#ocext_loading'+value_selected+name_field+setting_id).remove();
            },
            <?php if ($debug) { ?>
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
            <?php } ?>
    });
    
}

function getSettingFieldsGoogle(sample_setting_id,setting_type,setting_id,name_field){
    
    $('.setting_'+name_field+setting_id).before('<div id="ocext_loading'+name_field+setting_id+'"><img src="<?php echo HTTP_SERVER; ?>/view/image/ocext/loading.gif" /></div>');
    $.ajax({
            type: 'GET',
            url: 'index.php?route=<?php echo $path_oc ?>/ocext_feed_generator_google/getSettingFields&sample_setting_id='+sample_setting_id+'&setting_type='+setting_type+'&name_field='+name_field+'&setting_id='+setting_id+'&token=<?php echo $token; ?>',
            dataType: 'html',
            success: function(response) {
                
                $('#ocext_loading'+name_field+setting_id).remove();
                $('.setting_'+name_field+setting_id).before(response);
            },
            <?php if ($debug) { ?>
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
            <?php } ?>
    });
    
}

function openYmFilterData(filter_data_group_id){
    
    if(filter_data_group_id=='no_selected'){
        $('.filter_data_box').hide();
        $('select[name="ocext_feed_generator_google_ym_filter_data_filter_data_group_id"]').val('no_selected');
    }else{
        $('.filter_data_box').show();
        getYmFilterData('categories',filter_data_group_id);
        getYmFilterData('manufacturers',filter_data_group_id);
        getYmFilterData('options',filter_data_group_id);
        getYmFilterData('attributes',filter_data_group_id);
    }
    
    
    
    
}

function getYmFilterData(type_data,filter_data_group_id){
    $('#ym_filter_data_place_'+type_data).empty();
    $('#ym_filter_data_place_'+type_data).before('<div id="ocext_loading_ym_filter_data_'+type_data+'"><img src="<?php echo HTTP_SERVER; ?>/view/image/ocext/loading.gif" /></div>');
    $.ajax({
            type: 'GET',
            url: 'index.php?route=<?php echo $path_oc ?>/ocext_feed_generator_google/getYmFilterData&filter_data_group_id='+filter_data_group_id+'&'+type_data+'=1&token=<?php echo $token; ?>',
            dataType: 'html',
            success: function(response) {
                $('#ocext_loading_ym_filter_data_'+type_data).remove();
                $('#ym_filter_data_place_'+type_data).html(response);
                if(filter_data_group_id==0){
                    $('select[name=ocext_feed_generator_google_ym_filter_data_filter_data_group_id]').val(0);
                    $('input[name=ocext_feed_generator_google_ym_filter_data_filter_data_name]').val($('select[name=ocext_feed_generator_google_ym_filter_data_filter_data_group_id] option:selected').text());
                }
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
            url: 'index.php?route=<?php echo $path_oc ?>/ocext_feed_generator_google/getTemplateSetting&sample_setting_id='+sample_setting_id+'&setting_product_id='+setting_product_id+'&setting_type='+setting_type+'&setting_id='+setting_id+'&token=<?php echo $token; ?>',
            dataType: 'html',
            success: function(response) {
                $('.tab-template_setting-content').empty();
                $('#tab-template_setting'+setting_id).html('<img src="<?php echo HTTP_SERVER; ?>/view/image/ocext/loading.gif" />');
                $('#tab-template_setting'+setting_id).html(response);
            },
            <?php if ($debug) { ?>
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
            <?php } ?>
    });

}

function getNotifications() {
	$.ajax({
            type: 'GET',
            url: 'index.php?route=<?php echo $path_oc ?>/ocext_feed_generator_google/getNotifications&token=<?php echo $token; ?>',
            dataType: 'json',
            success: function(json) {
                    if (json['error']) {
                            $('#ocext_notification').html('<i class="fa fa-info-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> '+json['error']);
                    } else if (json['message'] && json['message']!='' ) {
                            $('#ocext_notification').html('<i class="fa fa-info-circle"></i><button type="button" class="close" data-dismiss="alert">&times;</button> '+json['message']);
                    }else{
                        $('#ocext_notification').remove();
                    }
            },
            <?php if ($debug) { ?>
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
            <?php } ?>
    });
}
getNotifications();

function getWelcomeWindow() {
	$.ajax({
            type: 'GET',
            url: 'index.php?route=<?php echo $path_oc ?>/ocext_feed_generator_google/getWelcomeWindow&token=<?php echo $token; ?>',
            dataType: 'html',
            success: function(html) {
                $('#tab-welcome-extecom-window').html(html);
            },
            failure: function(){
                $('#tab-welcome-extecom-window').html();
            },
            error: function() {
                $('#tab-welcome-extecom-window').html();
            }
    });
}


function getCategories(ym_category_id,filter_name){
    $('#ym_categories_categories_place_'+ym_category_id).empty();
    $('#ym_categories_categories_place_'+ym_category_id).before('<div id="ocext_loading_ym_categories_categories'+ym_category_id+'"><img src="<?php echo HTTP_SERVER; ?>/view/image/ocext/loading.gif" /></div>');
    $.ajax({
            type: 'GET',
            url: 'index.php?route=<?php echo $path_oc ?>/ocext_feed_generator_google/getCategories&filter_name='+filter_name+'&ym_category_id='+ym_category_id+'&token=<?php echo $token; ?>',
            dataType: 'html',
            success: function(response) {
                $('#ocext_loading_ym_categories_categories'+ym_category_id).remove();
                $('#ym_categories_categories_place_'+ym_category_id).html(response);
                
            },
            <?php if ($debug) { ?>
            error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
            <?php } ?>
    });
}

function moveElementTo(elementFrom,elementTo,elementFromDIV,ym_category_id){
    if($(elementFrom).prop("checked")==true){
        $('.'+elementFromDIV).remove();
        $('#'+elementFromDIV).addClass(elementFromDIV);
        $('#'+elementFromDIV).appendTo(elementTo);
        if(ym_category_id!=0){
            $('#ym_categories_save_'+ym_category_id).show();
        }
        
    }else{
        $(elementFromDIV).remove();
        if(ym_category_id!=0){
            $('#ym_categories_save_'+ym_category_id).show();
        }
    }
}

//--></script> 
<?php echo $footer; ?>