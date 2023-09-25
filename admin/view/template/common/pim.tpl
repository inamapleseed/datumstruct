<?php echo $header; ?>
<?php if (!isset($cke) || !$cke && isset($column_left)) { echo $column_left; }?>
<?php if (isset($cke) && $cke && !$header) {?>
  <!DOCTYPE html>
  <html dir="<?php echo isset($direction)?$direction:'ltr'; ?>" lang="<?php echo isset($lang)?$lang:''; ?>">
  <head>
  <meta charset="UTF-8" />
  <title><?php echo $heading_title; ?></title>
  <base href="<?php echo $base; ?>" />

  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
  <script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>

  
  <!-- Power Image Manager -->
  <link rel="stylesheet" href="view/javascript/jquery/jquery-ui-1.11.4.custom/jquery-ui.css" />
  <script src="view/javascript/jquery/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
  <script type="text/javascript" src="view/javascript/pim/pim.min.js"></script>          
  <link rel="stylesheet" type="text/css" media="screen" href="view/stylesheet/pim/pim.min.css">
  <link rel="stylesheet" type="text/css" media="screen" href="view/stylesheet/pim/theme.css">
    <?php if ($lang) { ?>
     <script type="text/javascript" src="view/javascript/pim/i18n/<?php echo $lang;?>.js"></script>  
    <?php } ?>        	
  <!-- Power Image Manager -->        

  <script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
  <link href="view/javascript/bootstrap/opencart/opencart.css" type="text/css" rel="stylesheet" />
  <link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
  <link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
  <script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
  <script src="view/javascript/jquery/datetimepicker/moment.js" type="text/javascript"></script>
  <script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
  <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
  <link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />  
  </head>
  <body>
<?php }?>
<div id="content">
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title;?></h3>
        <div class="btn-group pull-right">
            <button aria-hidden="true" data-dismiss="modal" class="close" id="pimClose" type="button">×</button>
        </div>
      </div>
      <div class="panel-body">
        <div id="pim"></div>      
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function getUrlParam(paramName) {
    var reParam = new RegExp('(?:[\?&]|&amp;)' + paramName + '=([^&]+)', 'i') ;
    var match = window.location.search.match(reParam) ;

    return (match && match.length > 1) ? match[1] : '' ;
}  
$().ready(function() {
  var funcNum = '';
  <?php if (isset($cke) && $cke<>'') {?>
    funcNum = getUrlParam('CKEditorFuncNum');
  <?php }  ?>
  <?php if (isset($CKEditorFuncNum) && $CKEditorFuncNum<>'') {?>
    funcNum = <?php echo $CKEditorFuncNum;?>
  <?php }  ?>  
    
		var elf = $('#pim').elfinder({
			url : 'index.php?route=common/filemanager/connector&token=<?php echo $token; ?>',  // connector URL (REQUIRED)
			lang : '<?php echo $lang;?>', /* Setup your language here! */
			dirimage: '<?php echo HTTP_CATALOG."image/";?>', 
			height: '<?php echo ($height);?>',
      useBrowserHistory: false,
      uiOptions : {toolbar : [['home', 'back', 'forward'],['reload'],['mkdir', 'upload'],['open', 'download', 'getfile'],['info'],['quicklook'],['copy', 'cut', 'paste'],['rm'],['duplicate', 'rename', 'edit', 'resize'],['extract', 'archive', 'sort'],['search'],['view'],['help']]},		
      contextmenu: {navbar: ["open", "|", "copy", "cut", "paste", "duplicate", "|", "rm", "|", "info"],cwd: ["reload", "back", "|", "upload", "mkdir", "mkfile", "paste", "|", "sort", "|", "info"],files: ["getfile", "|", "open", "quicklook", "|", "download", "|", "copy", "cut", "paste", "duplicate", "|", "rm", "|", "edit", "rename", "resize", "|", "archive","extract", "|", "info"]},
      <?php if (isset($target) && isset($thumb) && $target && $thumb ) {?>
        getFileCallback : function(files, fm) {
          a = files.url;
					b = a.replace('<?php echo HTTPS_CATALOG."image/";?>','');	
					b = b.replace('<?php echo HTTP_CATALOG."image/";?>','');	
          var img = $('#<?php echo $thumb;?>').find('img');
          
          $('#<?php echo $target;?>').val(decodeURIComponent(b));
          
          var jorunal_is_unbelivable_piece_of_SHIT = img.attr('data-ng-src');
          if (Journal2Config !== typeof undefined && Journal2Config !== false && angular !== typeof undefined && typeof jorunal_is_unbelivable_piece_of_SHIT !== typeof undefined && jorunal_is_unbelivable_piece_of_SHIT !== false) {
            img.attr('data-ng-src',a);
            img.attr('src',a);
            var currentElement = $('#<?php echo $target;?>');
            var scope = angular.element(currentElement).scope();
            scope.image = b;
            scope.$apply();
            $('#pimClose').click();
          } else {
            img.attr('src', files.tmb);
          }
          $('#modal-image').remove();
          
        }, 
      <?php } ?>
      <?php if ( (isset($cke) && $cke<>'')) { ?>
        getFileCallback : function(file) {
          window.opener.CKEDITOR.tools.callFunction(funcNum, file.url)
          self.close();	
        },
        <?php } ?>
      <?php if (isset($CKEditorFuncNum) && $CKEditorFuncNum<>'') { ?>
        getFileCallback : function(file) {
          window.CKEDITOR.tools.callFunction(funcNum,file.url);
          self.close();	
      		delete CKEditorFuncNum;
      		$('#modal-image').modal('hide');
      		$('#modal-image').remove();          
        },
        <?php } ?>        
				
				<?php if (isset($ckedialog) && $ckedialog) { ?>			
				getFileCallback : function(file) {
					//e.preventDefault();
					dialog = CKEDITOR.dialog.getCurrent();
					var targetElement = '<?php echo $ckedialog; ?>' || null;
					var target = targetElement.split( ':' );
					dialog.setValueOf( target[ 0 ], target[ 1 ], file.url );
					$('#modal-image').modal('hide');
				},
				<?php } ?>        
        
				<?php if (isset($productmanager) && $productmanager<>'') { ?>
						getFileCallback : function(file) {
							var pr_id = $('body').attr('data-current-product-id');
							a = file.url;
							b = a.replace('<?php echo HTTPS_CATALOG."image/";?>','');	
							b = b.replace('<?php echo HTTP_CATALOG."image/";?>','');								
							doSave(pr_id, 'image',b );
							$('#modal-image').modal('hide');								
						},
				<?php } ?>
        commandsOptions : {
          getfile : {
            oncomplete : 'close',
          }
        }              
      
		}).elfinder('instance');


  });
  
//--></script>
<?php echo $footer; ?>
<?php if (isset($cke) && $cke<>'' && !$footer) {?>
</body>
</html>
<?php } ?>