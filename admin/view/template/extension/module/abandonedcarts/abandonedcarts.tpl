<?php echo $header; ?>
<?php echo $column_left; ?>

<div id="content" class="AbandonedCarts">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
            <div style="float:right">
                <div class="storeSwitcherWidget">
                    <div class="form-group" style="padding-top:0px;padding-bottom:0px;">
                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">&nbsp;&nbsp;<?php echo $store['name']; if($store['store_id'] == 0) echo "&nbsp;<strong>".$text_default."</strong>"; ?>&nbsp;<span class="caret"></span><span class="sr-only">Toggle Dropdown</span></button>
                        <ul class="dropdown-menu" role="menu">
                            <?php foreach ($stores  as $st) { ?>
                                <li><a href="index.php?route=<?php echo $modulePath; ?>&store_id=<?php echo $st['store_id']; ?>&<?php echo $token_addon; ?>"><?php echo $st['name']; ?></a></li>
                            <?php } ?> 
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
  
    <div class="container-fluid">
        
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger autoSlideUp">
            <i class="fa fa-exclamation-circle"></i>&nbsp;<?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
       
        <?php if ($success) { ?>
        <div class="alert alert-success autoSlideUp"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
        	<button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <script>$('.autoSlideUp').delay(3000).fadeOut(600, function(){ $(this).show().css({'visibility':'hidden'}); }).slideUp(600);</script>
        <?php } ?>
    
        <div id="messageResult" style="display:none;">
            <div class="alert alert-success"><i class="fa fa-info"></i>&nbsp;The message was sent successfully! <button type="button" class="close" data-dismiss="alert">&times;</button></div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                    <input type="hidden" name="store_id" value="<?php echo $store['store_id']; ?>">
                    <div class="tabbable">
                        <div class="tab-navigation form-inline">
                            <ul class="nav nav-tabs mainMenuTabs">
                                <li>
                                    <a href="#controlpanel" data-toggle="tab"><?php echo $tab_controlpanel; ?></a>
                                </li>
                                <li>
                                    <a href="#abandonedCarts" data-toggle="tab" class="active"><?php echo $tab_abandonedcarts; ?></a>
                                </li>
                                <li>
                                    <a href="#mail" data-toggle="tab"><?php echo $tab_templates; ?></a>
                                </li>
                                <li>
                                    <a href="#scheduled_tasks" data-toggle="tab"><?php echo $tab_scheduled_tasks; ?></a>
                                </li>
                                <li>
                                    <a href="#ab_analytics" data-toggle="tab"><?php echo $tab_analytics; ?></a>
                                </li>
                                <li class="dropdown">
                                    <a href="#"  data-toggle="dropdown" class="dropdown-toggle"><?php echo $tab_coupons; ?>&nbsp;&nbsp;<b class="caret"></b></a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="#givenCoupons" data-toggle="tab"><?php echo $tab_givencoupons; ?></a>
                                        </li>
                                        <li>
                                            <a href="#usedCoupons" data-toggle="tab"><?php echo $tab_usedcoupons; ?></a>
                                        </li>
                                    </ul>
                                </li>
                                      
                            </ul>
                            
                            <div class="tab-buttons">
                                <button type="submit" class="btn btn-success save-changes"><?php echo $btn_savechanges; ?></button>
                                <a onclick="location = '<?php echo $cancel; ?>'" class="btn btn-warning"><?php echo $btn_cancel; ?></a>
                            </div> 
                        </div>
                      
                        <div class="tab-content">
                            <?php
                            if (!function_exists('modification_vqmod')) {
                                function modification_vqmod($file) {
                                    if (class_exists('VQMod')) {
                                        return VQMod::modCheck(modification($file), $file);
                                    } else {
                                        return modification($file);
                                    }
                                }
                            }
                            ?>
                            <div id="controlpanel" class="tab-pane active">
                                <?php require_once modification_vqmod(DIR_APPLICATION.'view/template/'.$modulePath.'/tab_panel.php'); ?>
                            </div>

                            <div id="abandonedCarts" class="tab-pane">
                                <?php require_once modification_vqmod(DIR_APPLICATION.'view/template/'.$modulePath.'/tab_abandonedcarts.php'); ?>
                            </div>
                            
                            <div id="mail" class="tab-pane">
                                <?php require_once modification_vqmod(DIR_APPLICATION.'view/template/'.$modulePath.'/tab_mail.php'); ?>
                            </div> 
                            
                            <div id="scheduled_tasks" class="tab-pane">
                                <?php require_once modification_vqmod(DIR_APPLICATION.'view/template/'.$modulePath.'/tab_scheduled_tasks.php'); ?>
                            </div>
                                
                            <div id="ab_analytics" class="tab-pane">
                                <?php require_once modification_vqmod(DIR_APPLICATION.'view/template/'.$modulePath.'/tab_analytics.php'); ?>
                            </div>
                            
                            <div id="givenCoupons" class="tab-pane"></div>
                            
                            <div id="usedCoupons" class="tab-pane"></div>           
                            
                        </div>
                    </div>
                </form>
            </div> 
        </div>
    </div>
</div>
<script type="text/javascript">
<?php 
$hostname = (!empty($_SERVER['HTTP_HOST'])) ? $_SERVER['HTTP_HOST'] : '' ;
$hostname = (strstr($hostname,'http://') === false) ? 'http://'.$hostname: $hostname;
?>
var domain                                  = '<?php echo base64_encode($hostname); ?>';
var domainraw                               = '<?php echo $hostname; ?>';
var timenow                                 = <?php echo time(); ?>;
var MID                                     = 'ZPRMDIOGOM';
var token                                   = '<?php echo $token; ?>';
var token_string                            = '<?php echo $token_string; ?>';
var token_addon                             = '<?php echo $token_addon; ?>';
var moduleName                              = '<?php echo $moduleName; ?>';
var modulePath                              = '<?php echo $modulePath; ?>';
var store_id                                = '<?php echo $store["store_id"]; ?>';
var alertRemoveExpiredCouponsText           = '<?php echo $confirm_remove_coupons; ?>';
var alertRemoveExpiredCouponsTextSuccess    = '<?php echo $alert_remove_coupons; ?>';
var alertConfirmRemoveEntry                 = '<?php echo $text_confirm_remove_entry; ?>';
var alert_invalid_email                     = '<?php echo $alert_invalid_email; ?>';
var confirm_template_remove                 = '<?php echo $confirm_template_remove; ?>';
var text_template                           = '<?php echo $text_template; ?>';
var alert_date_time                         = '<?php echo $alert_date_time; ?>';
var confirm_remove_all_records              = '<?php echo $text_confirm_remove_all_records; ?>';
var confirm_remove_all_empty_records        = '<?php echo $text_confirm_remove_all_empty_records; ?>';
var cron_initial_settings                   = '<?php if(!empty($moduleData['PeriodicCronValue'])) echo $moduleData['PeriodicCronValue']; else echo "* * * * *";  ?>';

console.log('init variables');
</script> 
<?php echo $footer; ?>