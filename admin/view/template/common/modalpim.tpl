<link rel="stylesheet" href="view/javascript/jquery/jquery-ui-1.11.4.custom/jquery-ui.css" />
<script src="view/javascript/jquery/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>

<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title"><?php echo 'Power Image Manager'; ?></h4>
    </div>
    <div class="modal-body">
      <div id="pim"></div>  
    </div>
  </div>
</div>

<script type="text/javascript"><!--


$('a.thumbnail').on('click', function(e) {
	e.preventDefault();

	<?php if ($thumb) { ?>
	$('#<?php echo $thumb; ?>').find('img').attr('src', $(this).find('img').attr('src'));
	<?php } ?>
	
	<?php if ($target) { ?>
	$('#<?php echo $target; ?>').attr('value', $(this).parent().find('input').attr('value'));
	<?php } else { ?>
	var range, sel = document.getSelection(); 
	
	if (sel.rangeCount) { 
		var img = document.createElement('img');
		img.src = $(this).attr('href');
	
		range = sel.getRangeAt(0); 
		range.insertNode(img); 
	}
	<?php } ?>

	$('#modal-image').modal('hide');
});

//--></script> 
<script type="text/javascript"><!--
var thumb = '<?php echo $thumb;?>';
var target = '<?php echo $target;?>'; 

$().ready(function() {
  
		var elf = $('#pim').elfinder({
			url : 'index.php?route=common/filemanager/connector&token=<?php echo $token; ?>',  // connector URL (REQUIRED)
			lang : '<?php echo $lang;?>',
			height: <?php echo $height;?>,
			dirimage: '<?php echo HTTP_CATALOG."image/";?>', 
  		destroyOnClose : true,
      <?php if (isset($thumb) || isset($target)) {?>
// 			container: '<?php echo $field;?>',  
      uiOptions : {toolbar : [['home', 'back', 'forward'],['reload'],['mkdir', 'upload'],['open', 'download', 'getfile'],['info'],['quicklook'],['copy', 'cut', 'paste'],['rm'],['duplicate', 'rename', 'edit', 'resize'],['extract', 'archive','multiupload', 'sort'],['search'],['view'],['help']]},		
     
      contextmenu: {navbar: ["open", "|", "copy", "cut", "paste", "duplicate", "|", "rm", "|", "info"],cwd: ["reload", "back", "|", "upload", "mkdir", "mkfile", "paste", "|", "sort", "|", "info"],files: ["getfile", "|", "open", "quicklook", "|", "download", "|", "copy", "cut", "paste", "duplicate", "|", "rm", "|", "edit", "rename", "resize", "|", "archive","multiupload", "extract", "|", "info"]},				
				getFileCallback : function(files, fm) {
          	if (thumb){
              $('#'+thumb).find('img').attr('src', files.tmb);
            }
          	if (target) {
            	$('#'+target).val(files.path);
            	$('#radio-'+target).removeAttr('disabled');  
            	$('#radio-'+target).val(files.path);
          	}	
				},
				commandsOptions : {
					getfile : {
						oncomplete : 'close',
						folders : false
					}
				}
			
      <?php } else { ?>
        uiOptions : {toolbar : [['home', 'back', 'forward'],['reload'],['mkdir', 'upload'],['open', 'download', 'getfile'],['info'],['quicklook'],['copy', 'cut', 'paste'],['rm'],['duplicate', 'rename', 'edit', 'resize'],['extract', 'archive', 'sort'],['search'],['view'],['help']]},		
        contextmenu: {navbar: ["open", "|", "copy", "cut", "paste", "duplicate", "|", "rm", "|", "info"],cwd: ["reload", "back", "|", "upload", "mkdir", "mkfile", "paste", "|", "sort", "|", "info"],files: ["getfile", "|", "open", "quicklook", "|", "download", "|", "copy", "cut", "paste", "duplicate", "|", "rm", "|", "edit", "rename", "resize", "|", "archive","extract", "|", "info"]},
        <?php if (!isset($full)) {?>
				getFileCallback : function(files, fm) {
          	var range, sel = window.getSelection(); 
          	
          	if (sel.rangeCount) { 
          		var img = document.createElement('img');
          		img.src = files.url;
          	
          		range = sel.getRangeAt(0); 
          		range.insertNode(img); 
          	}
          	

				},
				commandsOptions : {
					getfile : {
						oncomplete : 'close',
						folders : false
					}
				}
        <?php } ?>
      <?php }?>

		}).elfinder('instance');
	});
//--></script>
