<?php echo $header; ?><?php echo $column_left; ?>

<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-html" class="btn btn-primary"><?php echo $button_save; ?></button>
				
				<a href="<?php echo $back; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
			</div>
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<?php if ($success) { ?>
			<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } ?>

		<?php if ($error_warning) { ?>
			<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } ?>
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-html">
					  <ul class="nav nav-tabs">
						  <li class="active"><a href="<?php echo $tab_action_main; ?>"><i class="fa fa-file"></i> <?php echo $tab_main; ?></a></li>
						  <li><a href="<?php echo $tab_action_settings; ?>"><i class="fa fa-cog"></i> <?php echo $tab_settings; ?></a></li>
						  <li><a href="<?php echo $tab_action_about; ?>"><i class="fa fa-question"></i> <?php echo $tab_about; ?></a></li>
					  </ul>
				  <div class="tab-content">
					<div class="tab-pane active">
	
					<div class="col-md-12">
					<?php if( empty($module)) { ?>
							
					<ul></ul>
							
							<a id="module-add" onclick="addModule();" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo $button_add_module; ?></a>
							<?php } ?>
					<?php $module_row = 1; ?>
					  <div class="tab-content">
				  <?php if( ! empty($module)) { ?>
				  <div id="tab-module-<?php echo $module_row; ?>" >
					  <table class="table">
						<tr>
						  <td width="150"><?php echo $text_name; ?></td>
						  <td>
							  <input style="width: 400px" type="text" value="<?php if( isset( $module['name'] ) ) { echo $module['name']; } ?>" class="html_tab_name form-control" name="html_code_module[name]" id="name-<?php echo $module_row; ?>" />
						  </td>
						</tr>
						<tr>
						  <td><?php echo $text_mode; ?></td>
						  <td data-name="mode"></td>
						</tr>
					  </table>
					<ul id="language-<?php echo $module_row; ?>" class="nav nav-tabs">
					  <?php foreach ($languages as $language) { ?>
						<?php
						
							$flag = version_compare(VERSION, '2.2.0.0', '>=') ? 'language/' . $language['code'] . '/' . $language['code'] . '.png' : 'view/image/flags/' . $language['image'];
						
						?>
						<li><a href="#tab-language-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="<?php echo $flag; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
					  <?php } ?>
					</ul>
					  <div class="tab-content">
					<?php foreach ($languages as $language) { ?>
					<div class="tab-pane" id="tab-language-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>">
					  <table class="table">
						<tr id="header-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>"<?php if( ! isset( $module['mode'] ) || $module['mode'] != 'box' ) { ?> style="display:none"<?php } ?>>
						  <td><?php echo $text_header; ?></td>
						  <td><input class="form-control" type="text" style="width: 400px" name="html_code_module[header][<?php echo $language['language_id']; ?>]" value="<?php echo isset($module['header'][$language['language_id']]) ? $module['header'][$language['language_id']] : ''; ?>" /></td>
						</tr>
						<tr>
						  <td width="200"><?php echo $text_html; ?></td>
						  <td>
							  <input type="hidden" id="texteditor-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>" name="html_code_module[texteditor][<?php echo $language['language_id']; ?>]" value="<?php echo ! isset( $module['texteditor'][$language['language_id']] ) || ! empty( $module['texteditor'][$language['language_id']] ) ? '1' : '0'; ?>" />
							  <a href="#" rel="description-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>">
								  <?php echo $text_enable_disable_texteditor; ?>
							  </a>
							  <div style="clear: both;"></div>
							  <textarea style="width:100%; height:300px" name="html_code_module[description][<?php echo $language['language_id']; ?>]" id="description-<?php echo $module_row; ?>-<?php echo $language['language_id']; ?>"><?php echo isset($module['description'][$language['language_id']]) ? $module['description'][$language['language_id']] : ''; ?></textarea>
						 
						  </td>
						</tr>
					  </table>
					</div>
					<?php } ?>
					  </div>
					<table class="table">
					  <tr>
						<td width="200"><?php echo $entry_layout; ?></td>
						<td>
							<div class="scrollbox">
								<?php foreach ($layouts as $layout_id => $layout) { ?>
								  <div class="<?php echo $layout_id%2 ? 'even' : 'odd'; ?>">
									  <input type="checkbox" value="<?php echo $layout['layout_id']; ?>" id="layout_id-<?php echo $module_row; ?>" name="html_code_module[layout_id][]" <?php if( isset( $module['layout_id'] ) && in_array( $layout['layout_id'], $module['layout_id'] ) ) { ?> checked="checked"<?php } ?> />
											 <?php echo $layout['name']; ?>
								  </div>
								<?php } ?>
							</div>

							<a onclick="$(this).parent().find(':checkbox').attr('checked', true).trigger('change');">Select All</a>
		  /
		  <a onclick="$(this).parent().find(':checkbox').attr('checked', false).trigger('change');">Unselect All</a>
					  </tr>
					  <tr id="category_id-<?php echo $module_row; ?>" <?php if( ! isset( $module['layout_id'] ) || ! in_array( $settings['html_code_layout_c'], $module['layout_id'] ) ) { ?> style="display:none"<?php } ?>>
						<td><?php echo $entry_show_in_categories; ?><span class="help"><?php echo $text_checkbox_guide; ?></span></td>
						<td>
							<div class="scrollbox">
								<?php foreach( $categories as $category_id => $category ) { ?>
								  <div class="<?php echo $category_id%2 ? 'even' : 'odd'; ?>">
									  <input type="checkbox" value="<?php echo $category['category_id']; ?>" name="html_code_module[category_id][]" <?php if( isset( $module['category_id'] ) && in_array( $category['category_id'], $module['category_id'] ) ) { ?> checked="checked"<?php } ?> />
											 <?php echo $category['name']; ?>
								  </div>
								<?php } ?>
							</div>

							<a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Select All</a>
		  /
		  <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Unselect All</a>
						</td>
					  </tr>
					  <tr id="information_id-<?php echo $module_row; ?>" <?php if( ! isset( $module['layout_id'] ) || ! in_array( $settings['html_code_layout_i'], $module['layout_id'] ) ) { ?> style="display:none"<?php } ?>>
						<td><?php echo $entry_show_in_informations; ?><span class="help"><?php echo $text_checkbox_guide; ?></span></td>
						<td>
							<div class="scrollbox">
								<?php foreach( $informations as $information_id => $information ) { ?>
								  <div class="<?php echo $information_id%2 ? 'even' : 'odd'; ?>">
									  <input type="checkbox" value="<?php echo $information['information_id']; ?>" name="html_code_module[information_id][]" <?php if( isset( $module['information_id'] ) && in_array( $information['information_id'], $module['information_id'] ) ) { ?> checked="checked"<?php } ?> />
											 <?php echo $information['title']; ?>
								  </div>
								<?php } ?>
							</div>

							<a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Select All</a>
		  /
		  <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Unselect All</a>
						</td>
					  </tr>
					  <tr id="products_id-input-<?php echo $module_row; ?>" <?php if( ! isset( $module['layout_id'] ) || ! in_array( $settings['html_code_layout_p'], $module['layout_id'] ) ) { ?> style="display:none;"<?php } ?>>
						<td><?php echo $entry_products; ?><span class="help"><?php echo $text_autocomplete; ?></span></td>
						<td><input style="width: 400px" class="form-control" type="text" name="product-name" value="" /></td>
					  </tr>
					  <tr id="products_id-list-<?php echo $module_row; ?>" <?php if( ! isset( $module['layout_id'] ) || ! in_array( $settings['html_code_layout_p'], $module['layout_id'] ) ) { ?> style="display:none;"<?php } ?>>
						<td><span class="help"><?php echo $text_checkbox_guide; ?></span></td>
						<td>
						<div class="scrollbox">
						  <?php if( ! empty( $products ) ) { ?>
							<?php foreach ($products as $product_id => $name) { ?>
							<div>
								<a class="btn btn-xs" style="color: red"><i class="fa fa-minus-circle"></i></a>
								  <?php echo $name; ?>
								  <input type="hidden" name="html_code_module[product_id][]" value="<?php echo $product_id; ?>" />
							</div>
							<?php } ?>
						  <?php } ?>
						  </div></td>
					  </tr>
					  <tr>
						<td><?php echo $entry_store; ?></td>
						<td>
							<div class="scrollbox">
								<?php foreach ($stores as $store_id => $store) { ?>
								  <div class="<?php echo $store_id%2 ? 'even' : 'odd'; ?>">
									  <input type="checkbox" value="<?php echo $store['store_id']; ?>" name="html_code_module[store_id][]" <?php if( isset( $module['store_id'] ) && in_array( $store['store_id'], $module['store_id'] ) ) { ?> checked="checked"<?php } ?> />
											 <?php echo $store['name']; ?>
								  </div>
								<?php } ?>
							</div>

							<a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Select All</a>
		  /
		  <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Unselect All</a>
					  </tr>
					  <tr>
						<td>
							<?php echo $entry_customer_group; ?><span class="help"><?php echo $text_checkbox_guide; ?></span>
						</td>
						<td>
							<div class="scrollbox">
								<?php foreach ($customer_groups as $customer_group_id => $customer_group) { ?>
								  <div class="<?php echo $customer_group_id%2 ? 'even' : 'odd'; ?>">
									  <input type="checkbox" value="<?php echo $customer_group['customer_group_id']; ?>" name="html_code_module[customer_group_id][]" <?php if( isset( $module['customer_group_id'] ) && in_array( $customer_group['customer_group_id'], $module['customer_group_id'] ) ) { ?> checked="checked"<?php } ?> />
											 <?php echo $customer_group['name']; ?>
								  </div>
								<?php } ?>
							</div>

							<a onclick="$(this).parent().find(':checkbox').attr('checked', true);">Select All</a>
		  /
		  <a onclick="$(this).parent().find(':checkbox').attr('checked', false);">Unselect All</a>
					  </tr>
					  <tr>
						<td><?php echo $entry_position; ?></td>
						<td data-name="position"></td>
					  </tr>
					  <tr>
						<td><?php echo $entry_php; ?></td>
						<td data-name="php"></td>
					  </tr>
					  <tr>
						<td><?php echo $entry_status; ?></td>
						<td data-name="status"></td>
					  </tr>
					  <tr>
						<td><?php echo $entry_sort_order; ?></td>
						<td><input class="form-control" style="width: 124px" type="text" name="html_code_module[sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
					  </tr>
					</table>
				  </div>
				  
				  <?php } ?></div></div></div>
					</div>
				</form>
			</div>
    </div>
  </div>
</div>

<style type="text/css">
	.col-md-2 .nav-tabs > li {
		float: none;
		clear: both;
		border: 1px solid transparent;
		border-right: none;
		overflow: hidden;
	}
	.col-md-2 .nav-tabs > li > a, .col-md-2 .nav-tabs > li > a:hover {
		border: 1px solid transparent;
		outline: none;
		background: none;
	}
	.col-md-2 .nav-tabs > li.active {
		border-color: #ccc;
		background: #eee;
	}
	.col-md-2 .nav-tabs > li > span {
		padding-top: 7px;
		padding-right: 7px;
	}
	
	a.cke_button {
		height: 26px !important;
	}
	
	.scrollbox {
		height: 150px;
		width: 300px;
		overflow-y: scroll;
		overflow-x: auto;
		border: 1px solid #ccc;
	}
	
	.scrollbox > div {
		padding: 2px 5px;
		white-space: nowrap;
	}
	
	.scrollbox > div input {
		vertical-align: middle;
		margin: 0 5px 0 0;
	}
	
	.help {
		display: block;
		font-size: 11px;
		color: #666666;
	}
	
	.nav-tabs-v > li.active > a,
	.nav-tabs-v > li.active > a:hover,
	.nav-tabs-v > li.active > a:focus {
		background: none !important;
		outline-style: none !important;
		border-color: transparent !important;
	}
	.btn-group .btn.active {
		opacity: 1;
	}
	.btn-group .btn {
	  opacity: 0.5;
	}

</style>

<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
<link href="view/javascript/summernote/summernote.css" rel="stylesheet" />

<script type="text/javascript"><!--
	var module_row = <?php echo $module_row; ?>,
		_name = 'html_code';

function enableEditor( target ){
	var element = $('#' + target);
		element.summernote({
			disableDragAndDrop: true,
			height: 300,
			emptyPara: '',
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'underline', 'clear']],
				['fontname', ['fontname']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['insert', ['link', 'image', 'video']],
				['view', ['fullscreen', 'codeview', 'help']]
			],
			buttons: {
				image: function() {
					var ui = $.summernote.ui;

					// create button
					var button = ui.button({
						contents: '<i class="note-icon-picture" />',
						tooltip: $.summernote.lang[$.summernote.options.lang].image.image,
						click: function () {
							$('#modal-image').remove();

							$.ajax({
								url: 'index.php?route=common/filemanager&token=' + getURLVar('token'),
								dataType: 'html',
								beforeSend: function() {
									$('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
									$('#button-image').prop('disabled', true);
								},
								complete: function() {
									$('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
									$('#button-image').prop('disabled', false);
								},
								success: function(html) {
									$('body').append('<div id="modal-image" class="modal">' + html + '</div>');

									$('#modal-image').modal('show');

									$('#modal-image').delegate('a.thumbnail', 'click', function(e) {
										e.preventDefault();

										$(element).summernote('insertImage', $(this).attr('href'));

										$('#modal-image').modal('hide');
									});
								}
							});						
						}
					});
					return button.render();
				}
			}
		});
	}
	function addMode(){
		$('table').find('[data-name="mode"]')
			.append( createField( 'radio_group', '[mode]', '<?php echo isset( $module['mode']) ? $module['mode'] : 'none'; ?>', {
				'multiOptions' : {
					'items' : [
						[ 'none', '<?php echo $text_mode_none; ?>' ],
						[ 'box', '<?php echo $text_mode_box; ?>' ]
					]
				}
			}
		));
	};
	function addStatus(){
		$('table').find('[data-name="status"]')
			.append( createField( 'radio_group', '[status]', '<?php echo isset( $module['status']) ? $module['status'] : 1; ?>', {
				'multiOptions' : {
					'items' : [
						[ '1', '<?php echo $text_enabled; ?>' ],
						[ 'pc', '<?php echo $text_pc; ?>' ],
						[ 'mobile', '<?php echo $text_mobile; ?>' ],
						[ '0', '<?php echo $text_disabled; ?>' ]
					]
				}
			}
		));
	};
	function addPhp(){
		$('table').find('[data-name="php"]')
			.append( createField( 'radio_group', '[php]', '<?php echo isset( $module['php']) ? $module['php'] : 0; ?>', {
				'multiOptions' : {
					'items' : [
						[ '1', '<?php echo $text_enabled; ?>' ],
						[ '0', '<?php echo $text_disabled; ?>' ]
					]
				}
			}
		));
	};
	function addPosition(){
		$('table').find('[data-name="position"]')
			.append( createField( 'radio_group', '[position]', '<?php echo isset( $module['position']) ? $module['position'] : 'content_top'; ?>', {
				'multiOptions' : {
					'items' : [
						[ 'content_top', '<?php echo $text_content_top; ?>' ],
						[ 'content_bottom', '<?php echo $text_content_bottom; ?>' ],
						[ 'column_left', '<?php echo $text_column_left; ?>' ],
						[ 'column_right', '<?php echo $text_column_right; ?>'],
						[ 'header_top', '<?php echo $text_header_top; ?>'],
						[ 'header_bottom', '<?php echo $text_header_bottom; ?>'],
						[ 'footer_top', '<?php echo $text_footer_top; ?>'],
						[ 'footer_bottom', '<?php echo $text_footer_bottom; ?>']
					]
				}
			}
		));
	};
	addMode();
	addStatus();
	addPhp();
	addPosition();
	buttons();
	
		function buttons(){
			$('.btn-group').on('click', 'label.btn', function(){
				$(this).siblings().removeClass('active');
				$(this).addClass('active');
			});
		}
		
		function createField( type, name, value, attribs ) {
		var self	= this,
			cnt;
			
		if( typeof value == 'undefined' ) {
			value = '';
		}
		function multiOptions( items, fn ) {
			for( var i = 0; i < items.length; i++ ) {
				if( typeof attribs['multiOptions'].items[i] == 'function' ) continue;
					
				var k = typeof attribs['multiOptions'].key != 'undefined' ? attribs['multiOptions'].items[i][attribs['multiOptions'].key] : attribs['multiOptions'].items[i][0],
					l = typeof attribs['multiOptions'].label != 'undefined' ? attribs['multiOptions'].items[i][attribs['multiOptions'].label] : attribs['multiOptions'].items[i][1];
				
				fn( k, l, k == value );
			}
		}
		switch( type ) {
			case 'select' : {
				cnt = jQuery('<select>');
				
				multiOptions( attribs['multiOptions'].items, function(k, l, selected){
					cnt.append(jQuery('<option>')
						.attr('value', k)
						.attr('selected', selected)
						.text(l)
					);
				});
				delete attribs['multiOptions'];
				break;
			}
			case 'radio_group' : {
				cnt = jQuery('<div class="btn-group" data-toggle="fm-buttons">');
				
				multiOptions( attribs['multiOptions'].items, function(k, l, selected){
					cnt.append(jQuery('<label class="btn btn-primary btn-sm' + ( selected ? ' active' : '' ) + '">')
						.append(jQuery('<input type="radio" style="display:none">')
							.attr('value', k)
							.attr('name', self._name + '_module' + name)
							.attr('checked', selected)
						)
						.append(l)
					);
				});
				delete attribs['multiOptions'];
				break;
			}
			default : {
				cnt = jQuery('<input>')
					.attr('type', type)
					.attr('value', value);
				
				break;
			}
		}
		if( type != 'radio_group' ) {
			if( name !== null ) {
				cnt.attr('name', _name + '_module' + name);
			}
			for( var i in attribs ) {
				if( typeof attribs[i] == 'function' ) continue;

				cnt.attr( i, attribs[i] );
			}
		}
		return cnt;
	};
	
	$('.nav-tabs').each(function(){
		$(this).find('> li:first a[data-toggle]').tab('show');
	});
	
	$('#content a[rel^=description]').each(function(){
		var rel		= $(this).attr('rel'),
			$status	= $('#' + rel.replace('description', 'texteditor'));
		
		if( $status.val() != '1' ) {
			
		} else {
			//enable editor
			enableEditor($(this).attr('rel'));
		}
	});

////////////////////////////////////////////////////////////////////////////////

$(document).on('click', '.scrollbox div a', function() {
	var $self	= $(this),
		$parent	= $self.parent(),
		$box	= $parent.parent();
		
	$parent.remove();
});

////////////////////////////////////////////////////////////////////////////////
	
	function init_inputs() {
		$('#content input[name="html_code_module[mode]"]').unbind('change').bind('change', function(){
			$('tr[id^=header-1]')[$(this).val() == 'box'?'show':'hide']();
		});
		
		$('#content input[type=checkbox][id^=layout_id-]').unbind('change').bind('change', function(){
			var id	= $(this).attr('id').split('-');
			
			$('#content input[type=checkbox][id^=layout_id-' + id[1] + '][value=<?php echo $settings['html_code_layout_c']; ?>]').each(function(){
				$('tr[id=category_id-' + id[1] + ']')[$(this).is(':checked')?'show':'hide']();
			});
			
			$('#content input[type=checkbox][id^=layout_id-' + id[1] + '][value=<?php echo $settings['html_code_layout_i']; ?>]').each(function(){
				$('tr[id=information_id-' + id[1] + ']')[$(this).is(':checked')?'show':'hide']();
			});
			
			$('#content input[type=checkbox][id^=layout_id-' + id[1] + '][value=<?php echo $settings['html_code_layout_p']; ?>]').each(function(){
				$('tr[id=products_id-input-' + id[1] + '],tr[id=products_id-list-' + id[1] + ']')[$(this).is(':checked')?'show':'hide']();
			});
		});		

		// Filter
		$('input[name="product-name"]:not([autocomplete])').each(function(){
			var $this = $(this),
				id = $(this).parent().parent().attr('id'),
				nr = id.split('-')[2],
				$c = $('#products_id-list-' + nr).find('.scrollbox');
			
			$(this).attr('autocomplete','1').autocomplete({
				delay: 500,
				source: function(request, response) {
					$.ajax({
						url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent($this.val()),
						dataType: 'json',
						success: function(json) {		
							response($.map(json, function(item) {
								return {
									label: item.name,
									value: item.product_id
								}
							}));
						}
					});
				}, 
				select: function(item) {
					$c.find('input[value=' + item.value + ']').parent().remove();

					$c.append('<div><a class="btn btn-xs" style="color: red"><i class="fa fa-minus-circle"></i></a>' + item.label + '<input type="hidden" name="html_code_module[product_id][]" value="' + item.value + '" /></div>');

					//$c.find('div:odd').attr('class', 'odd');
					//$c.find('div:even').attr('class', 'even');

					return false;
				},
				focus: function(item) {
				return false;
			}
			});
		});
		
		$('#content input.html_tab_name').unbind('change keyup').bind('change keyup', function(){
			var val = $(this).val(),
				id	= $(this).attr('id').split('-')[1];
			
			if( ! val )
				val = '<?php echo $tab_module; ?> ' + id;
			
			$('#module-' + id).find('b').text( val );
		}).trigger('change');
		
		$('#content a[rel^=description]').css({
			'float'	: 'right',
			'margin-bottom'	: '5px'
		}).unbind('click').bind('click', function(){
			var rel		= $(this).attr('rel'),
				$status	= $('#' + rel.replace('description', 'texteditor')),
				status	= $status.val();
			
			if( status == '1' ) {
				$('#' + rel).summernote('destroy');
			} else {
				enableEditor( rel );
			}
			
			$status.val(status=='1'?'0':'1');
			
			return false;
		});
	}
	
	init_inputs();

function addModule() {	
	html  = '<div id="tab-module-' + module_row + '">';
	html += '<table class="table">';
    html += '   <tr>';
    html += '       <td width="150"><?php echo $text_name; ?></td>';
    html += '       <td><input style="width: 400px" type="text" class="html_tab_name form-control" name="html_code_module[name]" value="" id="name-' + module_row + '" />';
	html += '		</td>';
    html += '      </tr>';
    html += '   <tr>';
    html += '       <td><?php echo $text_mode; ?></td>';
    html += '       <td data-name="mode">';
	html += '		</td>';
    html += '      </tr>';
	html += '	</table>';
	
	html += '  <ul id="language-' + module_row + '" class="nav nav-tabs">';
    <?php $k = 0; foreach ($languages as $language) { ?>
		<?php $flag = version_compare(VERSION, '2.2.0.0', '>=') ? 'language/' . $language['code'] . '/' . $language['code'] . '.png' : 'view/image/flags/' . $language['image']; ?>
    html += '    <li<?php echo $k ? "" : " class=\"active\""; ?>><a data-toggle="tab" href="#tab-language-'+ module_row + '-<?php echo $language['language_id']; ?>"><img src="<?php echo $flag; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>';
    <?php $k++; } ?>
	html += '  </ul>';

	html += '<div class="tab-content">';
	<?php $k = 0; foreach ($languages as $language) { ?>
	html += '    <div class="tab-pane<?php echo $k ? "" : " active"; ?>" id="tab-language-'+ module_row + '-<?php echo $language['language_id']; ?>">';
	html += '      <table class="table">';
    html += '          <tr id="header-' + module_row + '-<?php echo $language['language_id']; ?>" style="display:none">';
    html += '            <td><?php echo $text_header; ?></td>';
    html += '            <td><input class="form-control" type="text" style="width: 400px" name="html_code_module[header][<?php echo $language['language_id']; ?>]" value="" /></td>';
    html += '          </tr>';
	html += '        <tr>';
	html += '          <td width="200"><?php echo $heading_title; ?></td>';
	html += '          <td><input type="hidden" id="texteditor-' + module_row + '-<?php echo $language['language_id']; ?>" name="html_code_module[texteditor][<?php echo $language['language_id']; ?>]" value="1" />';
	html += '<a href="#" rel="description-' + module_row + '-<?php echo $language['language_id']; ?>">';
	html += '					<?php echo $text_enable_disable_texteditor; ?>';
	html += '				</a><div style="clear: both;"></div>';
	html += '<textarea style="width:100%; height:300px" name="html_code_module[description][<?php echo $language['language_id']; ?>]" id="description-' + module_row + '-<?php echo $language['language_id']; ?>"></textarea></td>';
	html += '        </tr>';
	html += '      </table>';
	html += '    </div>';
	<?php $k++; } ?>
	html += '</div>';

	html += '  <table class="table">';
	html += '    <tr>';
	html += '      <td width="200"><?php echo $entry_layout; ?><span class="help"><?php echo $text_checkbox_guide; ?></span></td>';
	html += '      <td>';
	html += '			  <div class="scrollbox">';
	<?php foreach ($layouts as $layout_id => $layout) { ?>
	html += '					<div class="<?php echo $layout_id%2 ? 'even' : 'odd'; ?>">';
	html += '						<input type="checkbox" value="<?php echo $layout['layout_id']; ?>" id="layout_id-' + module_row + '" name="html_code_module[layout_id][]" />';
	html += '							   <?php echo $layout['name']; ?>';
	html += '					</div>';
	<?php } ?>
	html += '			  </div>';
	html += '			  ';
	html += '			  <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true).trigger(\'change\');">Select All</a>';
	html += ' / ';
	html += '<a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false).trigger(\'change\');">Unselect All</a></td>';
	html += '    </tr>';
    html += '       <tr id="category_id-' + module_row + '" style="display:none">';
    html += '         <td><?php echo $entry_show_in_categories; ?><span class="help"><?php echo $text_checkbox_guide; ?></span></td>';
    html += '         <td>';
	html += '			  <div class="scrollbox">';
	<?php foreach( $categories as $category_id => $category ) { ?>
		html += '					<div class="<?php echo $category_id%2 ? 'even' : 'odd'; ?>">';
		html += '						<input type="checkbox" value="<?php echo $category['category_id']; ?>" name="html_code_module[category_id][]" />';
		html += '<?php echo addslashes( $category['name'] ); ?>';
		html += '					</div>';
	<?php } ?>
	html += '			  </div>';
				  
	html += '			  <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);">Select All</a>';
	html += ' / ';
	html += '<a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);">Unselect All</a>';
	html += '		  </td>';
    html += '        </tr>';
    html += '       <tr id="information_id-' + module_row + '" style="display:none">';
    html += '         <td><?php echo $entry_show_in_informations; ?><span class="help"><?php echo $text_checkbox_guide; ?></span></td>';
    html += '         <td>';
	html += '			  <div class="scrollbox">';
	<?php foreach( $informations as $information_id => $information ) { ?>
		html += '					<div class="<?php echo $information_id%2 ? 'even' : 'odd'; ?>">';
		html += '						<input type="checkbox" value="<?php echo $information['information_id']; ?>" name="html_code_module[information_id][]" />';
		html += '<?php echo addslashes( $information['title'] ); ?>';
		html += '					</div>';
	<?php } ?>
	html += '			  </div>';
				  
	html += '			  <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);">Select All</a>';
	html += '/';
	html += '<a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);">Unselect All</a>';
	html += '		  </td>';
    html += '        </tr>';
	
	html += '<tr id="products_id-input-' + module_row + '" style="display:none;">';
	html += '	<td><?php echo $entry_products; ?><span class="help"><?php echo $text_autocomplete; ?></span></td>';
    html += '   <td><input style="width: 400px" class="form-control" type="text" name="product-name" value="" /></td>';
    html += '</tr>';
    html += '<tr id="products_id-list-' + module_row + '" style="display:none;">';
    html += '	<td><span class="help"><?php echo $text_checkbox_guide; ?></span></td>';
    html += '   <td>';
	html += '		<div class="scrollbox"></div>';
	html += '	</td>';
    html += '</tr>';
	
	html += '    <tr>';
	html += '      <td><?php echo $entry_store; ?></td>';
	html += '      <td>';
	html += '			  <div class="scrollbox">';
	<?php foreach ($stores as $store_id => $store) { ?>
	html += '					<div class="<?php echo $store_id%2 ? 'even' : 'odd'; ?>">';
	html += '						<input type="checkbox" value="<?php echo $store['store_id']; ?>" name="html_code_module[store_id][]" />';
	html += '							   <?php echo $store['name']; ?>';
	html += '					</div>';
	<?php } ?>
	html += '			  </div>';
	html += '			  ';
	html += '			  <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);">Select All</a>';
	html += ' / ';
	html += '<a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);">Unselect All</a></td>';
	html += '    </tr>';
	
	html += '    <tr>';
	html += '      <td><?php echo $entry_customer_group; ?><span class="help"><?php echo $text_checkbox_guide; ?></span></td>';
	html += '      <td>';
	html += '			  <div class="scrollbox">';
	<?php foreach ($customer_groups as $customer_group_id => $customer_group) { ?>
	html += '					<div class="<?php echo $customer_group_id%2 ? 'even' : 'odd'; ?>">';
	html += '						<input type="checkbox" value="<?php echo $customer_group['customer_group_id']; ?>" name="html_code_module[customer_group_id][]" />';
	html += '							   <?php echo $customer_group['name']; ?>';
	html += '					</div>';
	<?php } ?>
	html += '			  </div>';
	html += '			  ';
	html += '			  <a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', true);">Select All</a>';
	html += ' / ';
	html += '<a onclick="$(this).parent().find(\':checkbox\').attr(\'checked\', false);">Unselect All</a></td>';
	html += '    </tr>';
	
	html += '    <tr>';
	html += '      <td><?php echo $entry_position; ?></td>';
	html += '      <td data-name="position"></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_php; ?></td>';
	html += '      <td data-name="php"></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_status; ?></td>';
	html += '      <td data-name="status"></td>';
	html += '    </tr>';
	html += '    <tr>';
	html += '      <td><?php echo $entry_sort_order; ?></td>';
	html += '      <td><input class="form-control" style="width: 124px" type="text" name="html_code_module[sort_order]" value="" size="3" /></td>';
	html += '    </tr>';
	html += '  </table>'; 
	html += '</div>';
	
	$('#form-html .col-md-12:first .tab-content:first').append(html);
	
	$('#module-add').hide();
	addMode();
	addPhp();
	addPosition();
	addStatus();
	init_inputs();
	buttons();
	
	<?php foreach ($languages as $language) { ?>
		enableEditor('description-' + module_row + '-<?php echo $language['language_id']; ?>'); 
	<?php } ?>
	
	$('#module-' + module_row).trigger('click');
	
	module_row++;
}
//--></script> 


<?php echo $footer; ?>