<?php if($ajax_save){ ?>
	<script type = "text/javascript"><!--
		var btn = '<label type = "submit" onclick = "ajax_submit();" style = "margin-right:3px" id = "save_stay" data-toggle="tooltip" title="QUICK SAVE" class="btn btn-success">QUICK SAVE</label>';
		
		$(window).load(function(){
			if(location.toString().indexOf("id") > 0){
				if($('.page-header *[type=\'submit\']').length == 1){$(btn).insertBefore($('.page-header *[type=\'submit\']:first'));}
			}
			
			$(".alert:not(.stick)").click(function(e){
				
				if(!$(this).hasClass('sticky')){
					var t = $(this);
					t.slideUp(300, function(){
						t.remove();
					});
				}
			});
		});
		
		var default_text = "QUICK SAVE";
		var sending_text = "Sending";
		var text = '<div class="alert alert-success" onclick = "this.remove();" style = "cursor:pointer;" ><i class="fa fa-check-circle"></i> [Success: Form Submit] - Take not if you are using any ads blocker this might not work! Do make sure the required field is filled. <button type="button" class="close" data-dismiss="alert">×</button></div>';
		
		function ajax_submit(){
			$(".alert").remove();
			$("label#save_stay").text(sending_text);
			if($(".alert-success").length){
				$(".alert-success").remove();
			}
			if($(".cke").length){
				$(".cke").each(function(){
					var idc = $(this).attr("id").replace("cke_","");
					
					var diframe = $(this).find("iframe").contents().find("body").html();
					$(this).prev("textarea").val(diframe);
				});
			}
			
			$("form").each(function(){
				var url_f = $(this).attr('action');
				var id_c = $(this).attr('id');
				if(url_f){
					$.ajax({url: url_f.toString() ,type: 'POST', datatype: 'JSON',data: $(this).serialize(),success: function(html){
						html = $(html);
						danger = $(".text-danger", html);
						alert = $(".alert", html);
						
						if(alert.length){
							$(".page-header .container-fluid").append(alert[0].outerHTML);
						}
						else if(danger.length){
							for(i in danger){
								if(typeof danger[i] !== "undefined" && $(danger[i]).hasClass("text-danger")){
									text = $(danger[i]).text();
									text = '<div class="alert alert-success" onclick = "this.remove();" style = "cursor:pointer;" ><i class="fa fa-check-circle"></i> '+text+' <button type="button" class="close" data-dismiss="alert">×</button></div>';
									$(".page-header .container-fluid").append(text);
								}
							}
							
						}
						else{
							$(".page-header .container-fluid").append(text);
						}
						$("label#save_stay").text(default_text);
						$(".alert").click(function(){var t = $(this);t.slideUp(300, function(){t.remove();});});
					}
					
					});
				}
			});
			
		}
		
	--></script>
	
<?php } ?>

<?php if($ckeditor_enabled){ ?>
	<script type = "text/javascript"><!--
		setTimeout(function(){ckdialog();},300);
		
		function ckdialog(){
			if(typeof CKEDITOR != "undefined"){
				CKEDITOR.on('dialogDefinition', function (event) {
					var editor = event.editor
					var dialogDefinition = event.data.definition;
					var dialogName = event.data.name;
					var tabCount = dialogDefinition.contents.length;
					for (var i = 0; i < tabCount; i++) { 
						var browseButton = dialogDefinition.contents[i].get('browse');
						if (browseButton !== null) { 
							browseButton.hidden = false;
							
							browseButton.onClick = function () {
								var popup = this;
								var elements = [];
								if (popup.domId) {
									$.each($("#"+popup.domId).parents('.cke_dialog_ui_vbox').find('input'), function (index, el) {elements.push(el);});
								} 
								
								var ele = this;
								var target = $("#" + ele.domId).parents('.cke_dialog_ui_vbox').find('input').attr("id");
								var fm = $('<div/>').dialogelfinder({
									url: 'index.php?route=common/filemanager/connector&token=' + getURLVar('token'),
									lang: 'en',
									destroyOnClose: true,
									uiOptions: {toolbar: [['home', 'back', 'forward'],['reload'],['mkdir', 'upload'],['open', 'download', 'getfile'],['info'],['quicklook'],['copy', 'cut', 'paste'],['rm'],['duplicate', 'edit', 'resize'],['extract', 'archive', 'multiupload', 'sort'],['search'],['view'],['help']]
									},
									width: '<?= $width; ?>',
									height: '<?= $height; ?>',
									//contextmenu: {navbar: ["open","|","copy","cut","paste","duplicate","|","rm","|","info"],cwd: ["reload","back","|","upload","mkdir","mkfile","paste","|","sort","|","info"],files: ["getfile","|","open","quicklook","|","download","|","copy","cut","paste","duplicate","|","rm","|","edit","resize","|","archive","multiupload","extract","|","info"]
									contextmenu: {navbar: ["open","info"],cwd: ["reload","back","|","upload","mkdir","mkfile","info"],files: ["getfile","|","open","quicklook","|","download","|","rm","|","|","archive","multiupload","extract","|","info"]
									},
									getFileCallback: function (files, fm) {
										
										dialog = CKEDITOR.dialog.getCurrent();
										
										var name = files.name;
										name = name.split(".");
										name = name[0];
										
										var path = files.path;
										path = path.split("\\");
										path = path.join("/"); 
										
										
										
										if(dialog.getName() == "image"){
											dialog.setValueOf('info', 'txtUrl', "image/" + path); 
											ck_name = dialog.getValueOf('info', 'txtAlt');
											
											if($.trim(ck_name) == ""){
												dialog.setValueOf( 'info', 'txtAlt', name );
											}
											
											init_value = dialog.getValueOf( 'advanced', 'txtGenClass');
											if( init_value.indexOf("img-responsive") == -1 ){ 
												init_value += " img-responsive ";
											}
											dialog.setValueOf( 'advanced', 'txtGenClass', init_value );
										}
										else if(dialog.getName() == "link"){
											dialog.setValueOf('info', 'url', "image/" + path); 
										}
										
										
										
									},
									commandsOptions: {getfile: {	oncomplete: 'close',	folders: false}
									}
								}).dialogelfinder('instance');
								$('.dialogelfinder').css({'z-index': '99999999'});
								return;
							} // end
						}
					}
				});
				
			}
		}
	--></script>
<?php } ?>

<?php if ($pim_status) { ?>
	<script type="text/javascript">		
		// Power Image Manager
		$(document).ready(function () {
			
			$(document).undelegate('a[data-toggle=\'image\']', 'click');
			// Power Image Manager
			$(document).delegate('a[data-toggle=\'image\']', 'click', function (e) {
				e.preventDefault();
				var element = this;
				$(element).popover({
					html: true,
					placement: 'right',
					trigger: 'manual',
					content: function () {
						return '<button type ="button" id="button-image" class="btn btn-primary"><i class="fa fa-pencil"></i></button> <button type="button" id="button-clear" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>';
					}
				});
				location.hash = 'pim';
				
				$(element).popover('toggle');
				
				$('#button-image').on('click', function () {
					$(element).popover('hide');
					var target = $(element).parent().find('input').attr('id');
					var thumb = $(element).attr('id');
					var fm = $('<div/>').dialogelfinder({
						url: 'index.php?route=common/filemanager/connector&token=' + getURLVar('token'),
						lang: '<?php echo $lang;?>',
						width: <?php echo $width;?>,
						height: <?php echo $height;?>,
						destroyOnClose: true,
						
						uiOptions: {
							toolbar: [
							[
							'home', 'back', 'forward'
							],
							['reload'],
							[
							'mkdir', 'upload'
							],
							[
							'open', 'download', 'getfile'
							],
							['info'],
							['quicklook'],
							[
							'copy', 'cut', 'paste'
							],
							['rm'],
							[
							'duplicate', 'rename', 'edit', 'resize'
							],
							[
							'extract', 'archive', 'multiupload'
							],
							['search'],
							['view'],
							['help']
							]
						},
						
						contextmenu: {
							navbar: [
							"open",
							"|",
							"copy",
							"cut",
							"paste",
							"duplicate",
							"|",
							"rm",
							"|",
							"info"
							],
							cwd: [
							"reload",
							"back",
							"|",
							"upload",
							"mkdir",
							"mkfile",
							"paste",
							"|",
							"sort",
							"|",
							"info"
							],
							files: [
							"getfile",
							"|",
							"open",
							"quicklook",
							"|",
							"download",
							"|",
							"copy",
							"cut",
							"paste",
							"duplicate",
							"|",
							"rm",
							"|",
							"edit",
							"rename",
							"resize",
							"|",
							"archive",
							"multiupload",
							"extract",
							"|",
							"info"
							]
						},
						
						getFileCallback: function (files, fm) {
							a = files.url;
							
							b = a.replace('<?php echo HTTPS_CATALOG."image/";?>', '');
							b = b.replace('<?php echo HTTP_CATALOG."image/";?>', '');
							
							$('#' + thumb).find('img').attr('src', files.tmb);
							$('#' + target).val(decodeURIComponent(b));
							$('#radio-' + target).removeAttr('disabled');
							$('#radio-' + target).val(b);

							if(typeof updateTextarea == 'function'){
								updateTextarea();
							}
						},
						commandsOptions: {
							getfile: {
								oncomplete: 'close'
							}
						}
					}).dialogelfinder('instance');
					return;
				});
				
				$('#button-clear').on('click', function () {
					$(element).find('img').attr('src', $(element).find('img').attr('data-placeholder'));
					$(element).parent().find('input').attr('value', '');
					$(element).popover('hide');

					if(typeof updateTextarea == 'function') updateTextarea();
				});
			});
			
			$(document).delegate('a[data-toggle        =\'manager\']', 'click', function (e) {
				e.preventDefault();
				var fm = $('<div/>').dialogelfinder({
					url: 'index.php?route                  =common/filemanager/connector&token=' + getURLVar('token'),
					lang: '<?php echo $lang;?>',
					width: <?php echo $width;?>,
					height: <?php echo $height;?>,
					destroyOnClose: true,
					
					uiOptions: {
						toolbar: [
						[
						'home', 'back', 'forward'
						],
						['reload'],
						[
						'mkdir', 'upload'
						],
						[
						'open', 'download', 'getfile'
						],
						['info'],
						['quicklook'],
						[
						'copy', 'cut', 'paste'
						],
						['rm'],
						[
						'duplicate', 'rename', 'edit', 'resize'
						],
						[
						'extract', 'archive', 'multiupload', 'sort'
						],
						['search'],
						['view'],
						['help']
						]
					},
					
					contextmenu: {
						navbar: [
						"open",
						"|",
						"copy",
						"cut",
						"paste",
						"duplicate",
						"|",
						"rm",
						"|",
						"info"
						],
						cwd: [
						"reload",
						"back",
						"|",
						"upload",
						"mkdir",
						"mkfile",
						"paste",
						"|",
						"sort",
						"|",
						"info"
						],
						files: [
						"getfile",
						"|",
						"open",
						"quicklook",
						"|",
						"download",
						"|",
						"copy",
						"cut",
						"paste",
						"duplicate",
						"|",
						"rm",
						"|",
						"edit",
						"rename",
						"resize",
						"|",
						"archive",
						"multiupload",
						"extract",
						"|",
						"info"
						]
					},
					
					getFileCallback: function (files, fm) {
						a = files.url;
						b = a.replace('<?php echo HTTPS_CATALOG."image/";?>', '');
						b = b.replace('<?php echo HTTP_CATALOG."image/";?>', '');
						addMultiImage(decodeURIComponent(b));
					},
					commandsOptions: {
						getfile: {
							oncomplete: 'close',
							folders: false
						}
					}
				}).dialogelfinder('instance');
			});
			
			$(document).undelegate('button[data-toggle =\'image\']', 'click');
			
			$(document).delegate('button[data-toggle   =\'image\']', 'click', function (e) {
				e.preventDefault();
				location.hash = '';
				var fm = $('<div/>').dialogelfinder({
					url: 'index.php?route                  =common/filemanager/connector&token=' + getURLVar('token'),
					lang: '<?php echo $lang;?>',
					width: <?php echo $width;?>,
					height: <?php echo $height;?>,
					destroyOnClose: true,
					getFileCallback: function (files, fm) {
						var range,
						sel = window.getSelection();
						if (sel.rangeCount) {
							var img = document.createElement('img');
							a = files.url;
							b = a.replace(files.baseUrl, '');
							img.src = files.baseUrl + '' + b;
							range = sel.getRangeAt(0);
							range.insertNode(img);
						}
					},
					commandsOptions: {
						getfile: {
							oncomplete: 'close',
							folders: false
						}
					}
				}).dialogelfinder('instance');
			});
		});
		// Power Image Manager
	<?php } ?>
</script>
<footer id="footer"><?php echo $text_footer; ?><br/><?php echo $text_version; ?></footer>
</div>
</body>
</html>
