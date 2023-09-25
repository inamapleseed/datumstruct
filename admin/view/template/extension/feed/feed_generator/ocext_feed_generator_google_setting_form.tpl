    
    <?php $setting = $template_setting['setting']; ?>

    <input type="hidden" name="setting[setting_id]" value="<?php echo $setting_id ?>" />
    <input type="hidden" name="setting[setting_type]" value="<?php echo $setting_type ?>" />
    <input type="hidden" name="setting[setting_product_id]" value="<?php echo $setting_product_id ?>" />
    <?php if($all_template_setting){ ?>  
    <table class="table table-bordered table-hover">
            <tbody>
                
              
                <tr>
                    <td width="40%"><?php echo $text_template_setting_sample_setting; ?></td>
                    <td>
                        
                        <select class="form-control" onchange="getTemplateSettingGoogle(<?php echo $setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_product_id; ?>,this.value);" >
                                <option value="0"><?php echo $text_select; ?></option>
                            <?php foreach($all_template_setting as $all_template_setting_row){ ?>
                                <option value="<?php echo $all_template_setting_row['setting_id']; ?>"><?php echo $all_template_setting_row['setting']['title']; ?></option>
                            <?php } ?>
                            
                        </select>
                        
                    </td>
                </tr>
            </tbody>
    </table>
    <?php } ?> 
    <table class="table table-bordered table-hover">
            <tbody>
                
                
                
            <tr>
                <td width="40%"><?php echo $text_setting_title; ?></td>
                <td>
                    <?php
                    
                        $setting_title = '';
                        if($setting_id){
                            $setting_title = $setting['title'];
                        }
                    
                    ?>
                    <input  type="text" class="form-control" value="<?php echo $setting_title ?>" type="text" onchange="if(this.value==''){ $('#tab-template_setting_nav<?php echo $setting_id ?>').html('<?php echo $tab_template_setting_default; ?>'); }else{ $('#tab-template_setting_nav<?php echo $setting_id ?>').html(this.value); }" name="setting[title]" />
                </td>
            </tr>
            
            <tr>
                <td><?php echo $text_setting_offer_name; ?></td>
                <td>
                    <div class="setting_offer_name<?php echo $setting_id ?>"></div>
                    <script type="text/javascript"><!--

                    $(document).ready(function() {
                        getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'offer_name');
                    });

                    //--></script>
                    
                    
                    <div class="setting_fields<?php echo $setting_id ?>offer_name" id="setting_fields_composite<?php echo $setting_id ?>offer_name" style="<?php if(isset($setting['offer_name']['field']['status']) && $setting['offer_name']['field']['status'] != 'composite' || !isset($setting['offer_name']['field']['status'])){ ?> display: none; <?php } ?>">
                        
                    <div class="help-box"><?php echo $text_setting_name_composite_help; ?></div>

                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td><?php echo $text_setting_name_composite_element_composite_sort_order ?></td>
                                    <td><?php echo $text_setting_name_composite_element_composite_status ?></td>
                                </tr>
                            </thead>
                            <?php for($i=1;$i<6;$i++){ ?>
                                <tr>
                                    <td>
                                        <?php echo $i ?>
                                    </td>
                                    <td>
                                        <div class="setting_offer_name_composite_<?php echo $i ?><?php echo $setting_id ?>"></div>
                                        <script type="text/javascript"><!--

                                            $(document).ready(function() {
                                                getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'offer_name_composite_<?php echo $i ?>');
                                            });

                                        //--></script>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    
                    </div>
                    
                </td>
            </tr>
            
            
            
            
            
            <tr>
                <td><?php echo $text_setting_description; ?></td>
                <td>
                    <select class="form-control" name="setting[offer_description][field]">
                        <?php foreach($offer_description_parts as $offer_description_part){ ?>
                            <option  <?php if(isset($setting['offer_description']['field']) && $setting['offer_description']['field'] == $offer_description_part){ ?> selected="" <?php } ?> value="<?php echo $offer_description_part; ?>"><?php echo ${'text_setting_description_'.$offer_description_part}; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            
            <?php $setting_field = 'add_option_descr'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <select  class="form-control" name="setting[<?php echo $setting_field; ?>]">
                        <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == $setting_field){ ?>
                            <option selected="" value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option value="0"><?php echo $text_disable; ?></option>
                        <?php }else{ ?>
                            <option value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option selected="" value="0"><?php echo $text_disable; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <?php $setting_field = 'add_attribute_descr'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <select  class="form-control" name="setting[<?php echo $setting_field; ?>]">
                        <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == $setting_field){ ?>
                            <option selected="" value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option value="0"><?php echo $text_disable; ?></option>
                        <?php }else{ ?>
                            <option value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option selected="" value="0"><?php echo $text_disable; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <?php $setting_field = 'attribute_sintaxis'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <select  class="form-control" name="setting[<?php echo $setting_field; ?>]">
                        <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == $setting_field){ ?>
                            <option selected="" value="<?php echo $setting_field; ?>"><?php echo $entry_template_setting_attribute_sintaxis_1; ?></option>
                            <option value="0"><?php echo $entry_template_setting_attribute_sintaxis_0; ?></option>
                        <?php }else{ ?>
                            <option value="<?php echo $setting_field; ?>"><?php echo $entry_template_setting_attribute_sintaxis_1; ?></option>
                            <option selected="" value="0"><?php echo $entry_template_setting_attribute_sintaxis_0; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td><?php echo $text_setting_offer_available_in_stock; ?></td>
                <td>
                    <?php if($stock_statuses){ ?>
                        <div class="scrollbox" style="height: 70px; overflow-y: auto">
                            <?php foreach($stock_statuses as $stock_status_id=>$stock_status){ ?>
                                <div>
                                    <?php if(isset($setting['available_in_stock']) && $setting['available_in_stock'] == $stock_status_id){ ?>
                                        <input type="radio" checked="" name="setting[available_in_stock]" value="<?php echo $stock_status_id ?>" />
                                        <?php echo $stock_status['name']; ?>
                                    <?php }else{ ?>
                                        <input type="radio" name="setting[available_in_stock]" value="<?php echo $stock_status_id ?>" />
                                        <?php echo $stock_status['name']; ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php }else{ ?>
                        <div class="alert-info" align="center"><?php echo $text_setting_offer_stock_statuses_empty ?></div>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $text_setting_offer_available_out_of_stock; ?></td>
                <td>
                    <?php if($stock_statuses){ ?>
                        <div class="scrollbox" style="height: 70px; overflow-y: auto">
                            <?php foreach($stock_statuses as $stock_status_id=>$stock_status){ ?>
                                <div>
                                    <?php if(isset($setting['available_out_of_stock'][$stock_status_id])){ ?>
                                        <input checked="" type="checkbox" name="setting[available_out_of_stock][<?php echo $stock_status_id ?>]" value="<?php echo $stock_status_id ?>" />
                                        <?php echo $stock_status['name']; ?>
                                    <?php }else{ ?>
                                        <input type="checkbox" name="setting[available_out_of_stock][<?php echo $stock_status_id ?>]" value="<?php echo $stock_status_id ?>" />
                                        <?php echo $stock_status['name']; ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php }else{ ?>
                        <div class="alert-info" align="center"><?php echo $text_setting_offer_stock_statuses_empty ?></div>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <td><?php echo $text_setting_offer_available_preorder; ?></td>
                <td>
                    <?php if($stock_statuses){ ?>
                        <div class="scrollbox" style="height: 70px; overflow-y: auto">
                            <?php foreach($stock_statuses as $stock_status_id=>$stock_status){ ?>
                                <div>
                                    <?php if(isset($setting['available_preorder'][$stock_status_id])){ ?>
                                        <input checked="" type="checkbox" name="setting[available_preorder][<?php echo $stock_status_id ?>]" value="<?php echo $stock_status_id ?>" />
                                        <?php echo $stock_status['name']; ?>
                                    <?php }else{ ?>
                                        <input type="checkbox" name="setting[available_preorder][<?php echo $stock_status_id ?>]" value="<?php echo $stock_status_id ?>" />
                                        <?php echo $stock_status['name']; ?>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    <?php }else{ ?>
                        <div class="alert-info" align="center"><?php echo $text_setting_offer_stock_statuses_empty ?></div>
                    <?php } ?>
                </td>
            </tr>
            
            <?php $setting_field = 'dispublic_quantity'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <select  class="form-control" name="setting[<?php echo $setting_field; ?>]">
                        <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == $setting_field){ ?>
                            <option selected="" value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option value="0"><?php echo $text_disable; ?></option>
                        <?php }else{ ?>
                            <option value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option selected="" value="0"><?php echo $text_disable; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            
            <?php $setting_field = 'available_by_quantity'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <select  class="form-control" name="setting[<?php echo $setting_field; ?>]">
                        <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == $setting_field){ ?>
                            <option selected="" value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option value="0"><?php echo $text_disable; ?></option>
                        <?php }else{ ?>
                            <option value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option selected="" value="0"><?php echo $text_disable; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td><?php echo $text_setting_shipping; ?></td>
                <td>
                    <select name="setting[shipping][status]" onchange="if(this.value!=0){ $('#template_setting_delivery-options<?php echo $setting_id ?>').show() }else{ $('#template_setting_delivery-options<?php echo $setting_id ?>').hide() }" class="form-control" >
                        <?php $setting_delivery_options_css = "display:none"; ?>
                        <?php if(isset($setting['shipping']['status']) && $setting['shipping']['status'] == 'shipping'){ ?>
                            <?php $setting_delivery_options_css = "display:block"; ?>
                            <option selected=""  value="shipping"><?php echo $text_enable; ?></option>
                            <option value="0"><?php echo $text_disable; ?></option>
                        <?php }else{ ?>
                            <option value="shipping"><?php echo $text_enable; ?></option>
                            <option selected="" value="0"><?php echo $text_disable; ?></option>
                        <?php } ?>
                    </select>
                    <div id="template_setting_delivery-options<?php echo $setting_id ?>" style="margin-top: 5px; <?php echo $setting_delivery_options_css ?>">
                    <div class="help-box"><?php echo $text_setting_shipping_help; ?></div>
                    <table class="table table-bordered table-hover">
                        <thead>
                            <td>Country</td>
                            <td>Service</td>
                            <td>Price*</td>
                        </thead>
                        <?php for($i=0;$i<5;$i++){ ?>
                        <?php
                        $country = '';
                        $service = '';
                        $price = '';
                        if( isset($setting['shipping'][$i]['country']) ){
                            $country = $setting['shipping'][$i]['country'];
                        }
                        if( isset($setting['shipping'][$i]['service']) ){
                            $service = $setting['shipping'][$i]['service'];
                        }
                        if( isset($setting['shipping'][$i]['price']) ){
                            $price = $setting['shipping'][$i]['price'];
                        }
                        ?>
                        <tr>
                            <td><input type="text" value="<?php echo $country ?>" name="setting[shipping][<?php echo $i ?>][country]" class="form-control" /></td>
                            <td><input type="text" value="<?php echo $service ?>" name="setting[shipping][<?php echo $i ?>][service]" class="form-control" /></td>
                            <td><input type="text" value="<?php echo $price ?>" name="setting[shipping][<?php echo $i ?>][price]" class="form-control" /></td>
                        </tr>
                        <?php } ?> 
                    </table>
                    </div>
                </td>
            </tr>
            
            
            <!--
            В контроллере массив с $setting_field - <значение> = <название>
            -->
            <?php $setting_field = 'condition'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <select class="form-control" name="setting[<?php echo $setting_field ?>]">
                        <?php foreach(${$setting_field} as ${$setting_field.'_value'} => ${$setting_field.'_title'}){ ?>
                        
                            <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == ${$setting_field.'_value'}){ ?>
                                <option selected="" value="<?php echo ${$setting_field.'_value'}; ?>"><?php echo ${$setting_field.'_title'}; ?></option>
                            <?php }else{ ?>
                                <option value="<?php echo ${$setting_field.'_value'}; ?>"><?php echo ${$setting_field.'_title'}; ?></option>
                            <?php } ?>
                        
                        <?php } ?>
                        
                    </select>
                </td>
            </tr>
            
            
            <?php $setting_field = 'google_product_category'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <div class="setting_<?php echo $setting_field; ?><?php echo $setting_id ?>"></div>
                    <script type="text/javascript"><!--

                    $(document).ready(function() {
                        getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'<?php echo $setting_field; ?>');
                    });

                    //--></script>
                </td>
            </tr>
            
            <?php $setting_field = 'product_type'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <div class="setting_<?php echo $setting_field; ?><?php echo $setting_id ?>"></div>
                    <script type="text/javascript"><!--

                    $(document).ready(function() {
                        getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'<?php echo $setting_field; ?>');
                    });

                    //--></script>
                </td>
            </tr>
            
            
            <?php $setting_field = 'gtin'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <div class="setting_<?php echo $setting_field; ?><?php echo $setting_id ?>"></div>
                    <script type="text/javascript"><!--

                    $(document).ready(function() {
                        getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'<?php echo $setting_field; ?>');
                    });

                    //--></script>
                </td>
            </tr>
            <?php $setting_field = 'mpn'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <div class="setting_<?php echo $setting_field; ?><?php echo $setting_id ?>"></div>
                    <script type="text/javascript"><!--

                    $(document).ready(function() {
                        getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'<?php echo $setting_field; ?>');
                    });

                    //--></script>
                </td>
            </tr>
            <?php $setting_field = 'identifier_exists'; ?>
            <tr>
                <td>
                    
                    <?php 
                            
                     ?>
                    
                    
                    <?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <select  class="form-control" name="setting[<?php echo $setting_field; ?>]">
                        <?php if(!isset($setting[$setting_field]) || (isset($setting[$setting_field]) && $setting[$setting_field] == 'no_tag')){ ?>
                            <option selected="" value="no_tag"><?php echo $text_setting_identifier_exists_no_tag; ?></option>
                            <option value="yes_to_tag"><?php echo $text_setting_identifier_exists_yes_to_tag; ?></option>
                            <option value="no_to_tag"><?php echo $text_setting_identifier_exists_no_to_tag; ?></option>
                        <?php }elseif(isset($setting[$setting_field]) && $setting[$setting_field] == 'yes_to_tag'){ ?>
                            <option value="no_tag"><?php echo $text_setting_identifier_exists_no_tag; ?></option>
                            <option selected="" value="yes_to_tag"><?php echo $text_setting_identifier_exists_yes_to_tag; ?></option>
                            <option   value="no_to_tag"><?php echo $text_setting_identifier_exists_no_to_tag; ?></option>
                        <?php }elseif(isset($setting[$setting_field]) && $setting[$setting_field] == 'no_to_tag'){ ?>
                            <option value="no_tag"><?php echo $text_setting_identifier_exists_no_tag; ?></option>
                            <option value="yes_to_tag"><?php echo $text_setting_identifier_exists_yes_to_tag; ?></option>
                            <option  selected="" value="no_to_tag"><?php echo $text_setting_identifier_exists_no_to_tag; ?></option>
                        <?php }else{ ?>
                            <option selected="" value="no_tag"><?php echo $text_setting_identifier_exists_no_tag; ?></option>
                            <option value="yes_to_tag"><?php echo $text_setting_identifier_exists_yes_to_tag; ?></option>
                            <option value="no_to_tag"><?php echo $text_setting_identifier_exists_no_to_tag; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            
            <?php $setting_field = 'brand'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <div class="setting_<?php echo $setting_field; ?><?php echo $setting_id ?>"></div>
                    <script type="text/javascript"><!--

                    $(document).ready(function() {
                        getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'<?php echo $setting_field; ?>');
                    });

                    //--></script>
                </td>
            </tr>
            
            <?php $setting_field = 'attribute_age_group'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <div class="setting_<?php echo $setting_field; ?><?php echo $setting_id ?>"></div>
                    <script type="text/javascript"><!--

                    $(document).ready(function() {
                        getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'<?php echo $setting_field; ?>');
                    });

                    //--></script>
                </td>
            </tr>
            <?php $setting_field = 'attribute_gender'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <div class="setting_<?php echo $setting_field; ?><?php echo $setting_id ?>"></div>
                    <script type="text/javascript"><!--

                    $(document).ready(function() {
                        getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'<?php echo $setting_field; ?>');
                    });

                    //--></script>
                </td>
            </tr>
             <?php $setting_field = 'color'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <div class="setting_<?php echo $setting_field; ?><?php echo $setting_id ?>"></div>
                    <script type="text/javascript"><!--

                    $(document).ready(function() {
                        getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'<?php echo $setting_field; ?>');
                    });

                    //--></script>
                </td>
            </tr>
             <?php $setting_field = 'material'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <div class="setting_<?php echo $setting_field; ?><?php echo $setting_id ?>"></div>
                    <script type="text/javascript"><!--

                    $(document).ready(function() {
                        getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'<?php echo $setting_field; ?>');
                    });

                    //--></script>
                </td>
            </tr>
             <?php $setting_field = 'pattern'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <div class="setting_<?php echo $setting_field; ?><?php echo $setting_id ?>"></div>
                    <script type="text/javascript"><!--

                    $(document).ready(function() {
                        getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'<?php echo $setting_field; ?>');
                    });

                    //--></script>
                </td>
            </tr>
             <?php $setting_field = 'size_system'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <div class="setting_<?php echo $setting_field; ?><?php echo $setting_id ?>"></div>
                    <script type="text/javascript"><!--

                    $(document).ready(function() {
                        getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'<?php echo $setting_field; ?>');
                    });

                    //--></script>
                </td>
            </tr>
             <?php $setting_field = 'size_type'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <div class="setting_<?php echo $setting_field; ?><?php echo $setting_id ?>"></div>
                    <script type="text/javascript"><!--

                    $(document).ready(function() {
                        getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'<?php echo $setting_field; ?>');
                    });

                    //--></script>
                </td>
            </tr>
             <?php $setting_field = 'size'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <div class="setting_<?php echo $setting_field; ?><?php echo $setting_id ?>"></div>
                    <script type="text/javascript"><!--

                    $(document).ready(function() {
                        getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'<?php echo $setting_field; ?>');
                    });

                    //--></script>
                </td>
            </tr>
            
             <?php $setting_field = 'adwords_redirect'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <div class="setting_<?php echo $setting_field; ?><?php echo $setting_id ?>"></div>
                    <script type="text/javascript"><!--

                    $(document).ready(function() {
                        getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'<?php echo $setting_field; ?>');
                    });

                    //--></script>
                </td>
            </tr>
            
             <?php $setting_field = 'promotion_id'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <div class="setting_<?php echo $setting_field; ?><?php echo $setting_id ?>"></div>
                    <script type="text/javascript"><!--

                    $(document).ready(function() {
                        getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'<?php echo $setting_field; ?>');
                    });

                    //--></script>
                </td>
            </tr>
            
            <?php $setting_field = 'adult'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <select  class="form-control" name="setting[<?php echo $setting_field; ?>]">
                        <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == $setting_field){ ?>
                            <option selected="" value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option value="0"><?php echo $text_disable; ?></option>
                        <?php }else{ ?>
                            <option value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option selected="" value="0"><?php echo $text_disable; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            
            <?php $setting_field = 'multipack'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <div class="setting_<?php echo $setting_field; ?><?php echo $setting_id ?>"></div>
                    <script type="text/javascript"><!--

                    $(document).ready(function() {
                        getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'<?php echo $setting_field; ?>');
                    });

                    //--></script>
                </td>
            </tr>
            
            <?php $setting_field = 'no_pictures'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <select  class="form-control" name="setting[<?php echo $setting_field; ?>]">
                        <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == $setting_field){ ?>
                            <option selected="" value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option value="0"><?php echo $text_disable; ?></option>
                        <?php }else{ ?>
                            <option value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option selected="" value="0"><?php echo $text_disable; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            
            
            <?php $setting_field = 'pictures_sizes'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <input  type="text" class="form-control" placeholder="" value="<?php if(isset($setting[$setting_field])){ echo $setting[$setting_field]; } ?>" name="setting[<?php echo $setting_field ?>]" /> 
                </td>
            </tr>
            
            
            <?php $setting_field = 'count_pictures'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <select class="form-control"  name="setting[<?php echo $setting_field ?>]">
                        <?php for($i=1;$i<10;$i++){ ?>
                            <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == $i){ ?>
                                <option selected="" value="<?php echo $i ?>"><?php echo $i ?></option>
                            <?php }else{ ?>
                                <option value="<?php echo $i ?>"><?php echo $i ?></option>
                            <?php } ?>
                        <?php } ?>
                        <option value="0">0</option>
                    </select>
                </td>
            </tr>
            
            
            
            <?php $setting_field = 'currencies'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <?php if($currencies){ ?>
                        <select class="form-control" name="setting[<?php echo $setting_field; ?>]">
                            
                            <?php foreach($currencies as $currency_code => $currency_data){ ?>
                                
                                <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == $currency_code){ ?>
                                    <option selected="" value="<?php echo $currency_code; ?>"><?php echo $currency_data['title']; ?></option>
                                <?php }else{ ?>
                                    <option value="<?php echo $currency_code; ?>"><?php echo $currency_data['title']; ?></option>
                                <?php } ?>
                            
                            <?php } ?>
                        </select>
                    <?php }else{ ?>
                        <div class="alert-info" align="center"><?php echo $text_setting_currencies_empty; ?></div>
                    <?php } ?>
                </td>
            </tr>
            
            
            <?php $setting_field = 'price_currencies_from'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <?php if($currencies){ ?>
                        <select class="form-control" name="setting[<?php echo $setting_field; ?>]">
                            <option selected="" value="0"><?php echo $text_disable; ?></option>
                            <?php foreach($currencies as $currency_code => $currency_data){ ?>
                                
                                <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == $currency_code){ ?>
                                    <option selected="" value="<?php echo $currency_code; ?>"><?php echo $currency_data['title']; ?></option>
                                <?php }else{ ?>
                                    <option value="<?php echo $currency_code; ?>"><?php echo $currency_data['title']; ?></option>
                                <?php } ?>
                            
                            <?php } ?>
                        </select>
                    <?php }else{ ?>
                        <div class="alert-info" align="center"><?php echo $text_setting_currencies_empty; ?></div>
                    <?php } ?>
                </td>
            </tr>
            <?php $setting_field = 'price_currencies_to'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <?php if($currencies){ ?>
                        <select class="form-control" name="setting[<?php echo $setting_field; ?>]">
                            <option selected="" value="0"><?php echo $text_disable; ?></option>
                            <?php foreach($currencies as $currency_code => $currency_data){ ?>
                                
                                <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == $currency_code){ ?>
                                    <option selected="" value="<?php echo $currency_code; ?>"><?php echo $currency_data['title']; ?></option>
                                <?php }else{ ?>
                                    <option value="<?php echo $currency_code; ?>"><?php echo $currency_data['title']; ?></option>
                                <?php } ?>
                            
                            <?php } ?>
                        </select>
                    <?php }else{ ?>
                        <div class="alert-info" align="center"><?php echo $text_setting_currencies_empty; ?></div>
                    <?php } ?>
                </td>
            </tr>
            <?php $setting_field = 'sale_price'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <select  class="form-control" name="setting[<?php echo $setting_field; ?>]">
                        <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == $setting_field){ ?>
                            <option selected="" value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option value="0"><?php echo $text_disable; ?></option>
                        <?php }else{ ?>
                            <option value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option selected="" value="0"><?php echo $text_disable; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            
            
            <?php $setting_field = 'ymlprice'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <input  type="text" class="form-control" placeholder="" value="<?php if(isset($setting[$setting_field])){ echo $setting[$setting_field]; } ?>" name="setting[<?php echo $setting_field ?>]" /> 
                </td>
            </tr>
            
            <?php $setting_field = 'price_from'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <input  type="text" class="form-control" placeholder="" value="<?php if(isset($setting[$setting_field])){ echo $setting[$setting_field]; } ?>" name="setting[<?php echo $setting_field ?>]" /> 
                </td>
            </tr>
            
            <?php $setting_field = 'price_to'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <input  type="text" class="form-control" placeholder="" value="<?php if(isset($setting[$setting_field])){ echo $setting[$setting_field]; } ?>" name="setting[<?php echo $setting_field ?>]" /> 
                </td>
            </tr>
            
            <?php $setting_field = 'product_id_from'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <input  type="text" class="form-control" placeholder="" value="<?php if(isset($setting[$setting_field])){ echo $setting[$setting_field]; } ?>" name="setting[<?php echo $setting_field ?>]" /> 
                </td>
            </tr>
            
            <?php $setting_field = 'product_id_to'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <input  type="text" class="form-control" placeholder="" value="<?php if(isset($setting[$setting_field])){ echo $setting[$setting_field]; } ?>" name="setting[<?php echo $setting_field ?>]" /> 
                </td>
            </tr>
            <?php $setting_field = 'divide_on_option'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <select class="form-control" name="setting[<?php echo $setting_field; ?>]" onchange="if(this.value!=0){ $('#setting_fields<?php echo $setting_id ?>divide_on_option_option_id').show() }else{ $('#setting_fields<?php echo $setting_id ?>divide_on_option_option_id').hide() }">
                        <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == $setting_field){ ?>
                            <option selected="" value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option value="0"><?php echo $text_disable; ?></option>
                        <?php }else{ ?>
                            <option value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option selected="" value="0"><?php echo $text_disable; ?></option>
                        <?php } ?>
                    </select>
                    
                    <div id="setting_fields<?php echo $setting_id; ?>divide_on_option_option_id" style="margin-top:5px;<?php if(isset($setting[$setting_field]) && !$setting[$setting_field] || !isset($setting[$setting_field])){ ?> display: none; <?php } ?>">
                        
                        <div class="setting_divide_on_option_option_id<?php echo $setting_id ?>"></div>
                        <script type="text/javascript"><!--

                            $(document).ready(function() {
                                getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'divide_on_option_option_id');
                            });

                    //--></script>
                        
                        
                        <table class="table table-bordered table-hover">
                            
                        <?php $setting_field = 'type_variation'; ?>
                        <tr>
                            <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                            <td>
                                <?php if(${$setting_field}){ ?>
                                    <select class="form-control" name="setting[<?php echo $setting_field; ?>]">
                                        <option value="0"><?php echo $text_select; ?></option>
                                        <?php foreach(${$setting_field} as $id_setting_field => $setting_field_title){ ?>

                                            <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == $id_setting_field){ ?>
                                                <option selected="" value="<?php echo $id_setting_field; ?>"><?php echo $setting_field_title; ?></option>
                                            <?php }else{ ?>
                                                <option value="<?php echo $id_setting_field; ?>"><?php echo $setting_field_title; ?></option>
                                            <?php } ?>

                                        <?php } ?>
                                    </select>
                                <?php } ?>
                            </td>
                        </tr>
                        
                        <?php $setting_field = 'add_to_title_option_value_name'; ?>
                        <tr>
                            <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                            <td>
                                <select  class="form-control" name="setting[<?php echo $setting_field; ?>]">
                                    <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == $setting_field){ ?>
                                        <option selected="" value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                                        <option value="0"><?php echo $text_disable; ?></option>
                                    <?php }else{ ?>
                                        <option value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                                        <option selected="" value="0"><?php echo $text_disable; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        
                        </table>
                    </div>
                    
                    
                    
                </td>
            </tr>
            
            
            <tr>
                <td><?php echo $text_setting_custom_elements; ?></td>
                <td>
                    
                    
                    
                    <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td><?php echo $text_setting_custom_elements_name_element ?></td>
                                    <td><?php echo $text_setting_custom_elements_field_element ?></td>
                                </tr>
                            </thead>
                            <?php for($i=0;$i<$ocext_feed_generator_google_general_setting['count_custom_elements'];$i++){ ?>
                                <tr>
                                    <td>
                                        <?php if(isset($setting['custom_elements_name_'.$i]) && $setting['custom_elements_name_'.$i]){ ?>
                                        <<input type="text"  name="setting[custom_elements_name_<?php echo $i ?>]" value="<?php echo $setting['custom_elements_name_'.$i] ?>" />>
                                        <?php }else{ ?>
                                        <<input type="text"  name="setting[custom_elements_name_<?php echo $i ?>]" value="custom_label_<?php echo $i ?>" />>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <div class="setting_custom_elements_field_<?php echo $i ?><?php echo $setting_id ?>"></div>
                                        <script type="text/javascript"><!--

                                            $(document).ready(function() {
                                                getSettingFieldsGoogle(<?php echo $sample_setting_id; ?>,'<?php echo $setting_type ?>',<?php echo $setting_id ?>,'custom_elements_field_<?php echo $i ?>');
                                            });

                                        //--></script>
                                    </td>
                                </tr>
                            <?php } ?>
                        </table>
                    
                </td>
            </tr>
            
            <?php $setting_field = 'disable_this_product'; ?>
            <tr>
                <td><?php echo ${'text_setting_'.$setting_field}; ?></td>
                <td>
                    <select class="form-control" name="setting[<?php echo $setting_field; ?>]" >
                        <?php if(isset($setting[$setting_field]) && $setting[$setting_field] == $setting_field){ ?>
                            <option selected="" value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option value="0"><?php echo $text_disable; ?></option>
                        <?php }else{ ?>
                            <option value="<?php echo $setting_field; ?>"><?php echo $text_enable; ?></option>
                            <option selected="" value="0"><?php echo $text_disable; ?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            
            <tr>
                <td><?php echo $text_setting_status; ?></td>
                <td>
                    <select class="form-control" name="setting[status]">
                        <?php if(isset($setting['status']) && $setting['status'] == '1'){ ?>
                            <option selected="" value="1"><?php echo $text_enable; ?></option>
                            <option value="0"><?php echo $text_disable; ?></option>
                            <option value="2"><?php echo $text_delete; ?></option>
                        <?php }else{ ?>
                            <option value="1"><?php echo $text_enable; ?></option>
                            <option selected="" value="0"><?php echo $text_disable; ?></option>
                            <option value="2"><?php echo $text_delete; ?></option>
                        <?php } ?> ?>
                    </select>
                </td>
            </tr>
        </tbody>
    </table> 