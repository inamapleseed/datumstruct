<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  
    <div class="page-header">
      <div class="container-fluid">
        <div class="pull-right">
          <button type="submit" form="form-eatools-setting" class="btn btn-primary" onclick="showOverlay('<?php echo $text_ea_saving; ?>');"><i class="fa fa-save"></i>&nbsp;&nbsp;<?php echo $button_save_settings; ?></button>
        </div>
        <h1><i class="fa fa-wrench fa-lg"></i>&nbsp;&nbsp;<?php echo $heading_title; ?> - <?php echo $product_version; ?></h1>
        <ul class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
          <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
  
	<div class="container-fluid"> 

        <div class="panel panel-default">
            <div class="panel-heading">                
              <h3 class="panel-title " style="margin-top:2px;"><i class="fa fa-cogs"></i> <?php echo $text_settings_config; ?></h3>
            </div>
          
          <div class="panel-body">
              <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-eatools-setting" class="form-horizontal"> 
                  <div class="col-sm-12">
                      <div class="panel panel-eatools">         
                        <div class="panel-heading">
                          <h3 class="panel-title"><i class="fa fa-list-alt"></i> <?php echo $text_settings_ckeditor; ?></h3>
                        </div>
                        <div class="panel-body">
                            
                            <div class="row">
                                <div class="col-sm-6" style="border-right:1px dotted #ccc;">
                                    <div class="form-group" >
                                      <label class="col-sm-4 control-label"><?php echo $entry_enable_ckeditor; ?><span data-toggle="tooltip" title="<?php echo $help_enable_ckeditor; ?>">&nbsp;</span></label>
                                      <div class="col-sm-8">
                                          <select name="ea_cke_enable_ckeditor" id="input-ckeditor" class="form-control">
                                              <?php if ($ea_cke_enable_ckeditor) { ?>
                                                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                                  <option value="0"><?php echo $text_disabled; ?></option>
                                              <?php } else { ?>
                                                  <option value="1"><?php echo $text_enabled; ?></option>
                                                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                              <?php } ?>
                                          </select>
                                      </div>               
                                    </div>
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                      <label class="col-sm-4 control-label"><?php echo $entry_ckeditor_mode; ?><span data-toggle="tooltip" title="<?php echo $help_ckeditor_mode; ?>">&nbsp;</span></label>
                                      <div class="col-sm-8">
                                          <select name="ea_cke_ckeditor_mode" id="input-ckeditor-mode" class="form-control">
                                            <?php foreach ($ckeditor_modes as $ckeditor_mode) { ?>
                                            <?php if ($ckeditor_mode == $ea_cke_ckeditor_mode) { ?>
                                            <option value="<?php echo $ckeditor_mode; ?>" selected="selected"><?php echo $ckeditor_mode; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $ckeditor_mode; ?>"><?php echo $ckeditor_mode; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                          </select>
                                      </div>
                                    </div> 
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-sm-6" style="border-right:1px dotted #ccc;">
                                    <div class="form-group">
                                      <label class="col-sm-4 control-label"><?php echo $entry_ckeditor_theme; ?><span data-toggle="tooltip" title="<?php echo $help_ckeditor_theme; ?>">&nbsp;</span></label>
                                      <div class="col-sm-8">
                                          <select name="ea_cke_ckeditor_skin" id="input-ckeditor-skin" class="form-control">
                                            <option value="moono-lisa">moono-lisa</option>
                                            <?php foreach ($skins as $skin) {?>
                                              <?php if ($skin == $ea_cke_ckeditor_skin) { ?>
                                                <option value="<?php echo $skin; ?>" selected="selected"><?php echo $skin; ?></option>
                                              <?php } else { ?>
                                                <option value="<?php echo $skin; ?>" ><?php echo $skin; ?></option>
                                              <?php } ?>
                                            <?php } ?>
                                          </select>
                                      </div>
                                    </div>                              
                                </div>
                                
                                <div class="col-sm-6">
                                    <div class="form-group">
                                      <label class="col-sm-4 control-label"><?php echo $entry_cmirror_theme; ?><span data-toggle="tooltip" title="<?php echo $help_cmirror_theme; ?>">&nbsp;</span></label>
                                      <div class="col-sm-8">
                                          <select name="ea_cke_codemirror_skin" id="input-cmirror-skin" class="form-control">
                                          <option value="eclipse">eclipse</option>
                                            <?php foreach ($cmskins as $cmskin) {?>
                                              <?php if ($cmskin == $ea_cke_codemirror_skin) { ?>
                                                <option value="<?php echo $cmskin; ?>" selected="selected"><?php echo $cmskin; ?></option>
                                              <?php } else { ?>
                                                <option value="<?php echo $cmskin; ?>" ><?php echo $cmskin; ?></option>
                                              <?php } ?>
                                            <?php } ?>
                                          </select>
                                      </div>
                                    </div>                              
                                </div>
                            </div>                                                                                    
                        </div>                                             
                      </div>                        
                  </div>            
              </form>
          </div>
          
        <div class="loading-overlay hidden" id="loading-overlay">
            <div id="ea-loader"></div> 
            <div id="ea-msg"></div>                          
        </div>          
          
      </div>
	</div>  

<style>
.panel-eatools {
  border-color: #ddd; }
.panel-eatools > .panel-heading {
  color: #fff;
  background-color: #515151;
  border-color: #515151;
  padding: 10px 15px; }
.panel-eatools > .panel-heading + .panel-collapse > .panel-body {
  border-top-color: #515151; }
.panel-eatools > .panel-heading .badge {
  color: #333;
  background-color: #f5f5f5; }
.panel-eatools > .panel-footer + .panel-collapse > .panel-body {
  border-bottom-color: #ddd; }
.panel-eatools .form-group {
    margin-bottom: 0;
    padding-bottom: 13px;
    padding-top: 12px;
}  
.iflash {
  -webkit-animation: flash 2s ease infinite;
  -moz-animation: flash 2s ease infinite;
  -o-animation: flash 2s ease infinite;
  animation: flash 2s ease infinite;
}

.loading-overlay-container {position: relative;}
.loading-overlay {position: absolute;left: 0;top: 0;width: 100%;height: 100%;background: rgba(0, 0, 0, 0.4)!important;font-size: 4em;text-align: center;z-index: 20;}

#ea-loader {
	-moz-border-bottom-colors: none;
	-moz-border-left-colors: none;
	-moz-border-right-colors: none;
	-moz-border-top-colors: none;
	animation: 2s linear 0s normal none infinite running spin;
	border-color: #990000 #C9C9C9;
	border-image: none;
	border-radius: 50%;
	border-style: solid;
	border-width: 16px;
	height: 120px;
	left: 50%;
	margin: 0 0 0 -60px;
	position: fixed;
	top: 35%;
	width: 120px;
	z-index: 1;
	opacity:0.7;
}
#ea-msg {
	height: 60px;
	left: 50%;
	margin: 30px 0 0 -160px;
	position: fixed;
	top: 35%;
	width: 320px;
	z-index: 1;	
	border-top:2px solid #eee;
	border-bottom:2px solid #eee;
	padding:0;
}
#ea-msg div {
	margin-top:-10px;
	color: #eee;
	text-shadow: 1px 1px 1px rgba(150, 150, 150, 1);
	opacity:0.8;
}
#toast-container > div {
	width: 550px!important;
	opacity: 0.8;
	-ms-filter: progid:DXImageTransform.Microsoft.Alpha(Opacity=80);
	filter: alpha(opacity=80);
}
.toast-message {
	font-size:16px;
	font-weight:bold;
}
@keyframes spin {
to{transform:rotateZ(720deg)}
}
@-o-keyframes spin {
to{transform:rotateZ(720deg)}
}
@-moz-keyframes spin {
to{transform:rotateZ(720deg)}
}
@-webkit-keyframes spin {
to{transform:rotateZ(720deg)}
}
</style>

<script type="text/javascript"><!--
function showOverlay(msg) {
	$('#ea-msg').html('');
	html = '<div>'+ msg +'</div>';
	$('#ea-msg').prepend(html);
	$('#loading-overlay').removeClass('hidden');
}

function hideOverlay() {
	$('#loading-overlay').addClass('hidden');
}

function showToast(type, css, msg) {
	toastr.options.extendedTimeOut = 0; //1000;
	toastr.options.timeOut = 8000;
	toastr.options.hideDuration = 250;
	toastr.options.showDuration = 500;
	toastr.options.showMethod = 'slideDown';
	toastr.options.hideMethod = 'slideUp';
	toastr.options.closeMethod = 'slideUp';	
	toastr.options.closeButton = true,
	toastr.options.preventDuplicates = true,
	toastr.options.positionClass = css;
	toastr[type](msg);
}
//--></script>

<?php if ($error_warning) { ?>
<script type="text/javascript"><!--
showToast('warning', 'toast-top-center', '<?php echo $error_warning; ?>');
//--></script>
<?php } ?>
<?php if ($success) { ?>
<script type="text/javascript"><!--
showToast('success', 'toast-top-center', '<?php echo $success; ?>');
//--></script>
<?php } ?>

</div>

<?php echo $footer; ?>