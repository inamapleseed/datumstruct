CKEDITOR.editorConfig = function( config ) {
	//config.extraPlugins = 'widget,widgetselection,lineutils,fontAwesome,youtube,menu,panel,floatpanel,contextmenu,codemirror,bt_table,tableresize';
	config.extraPlugins = 'widget,widgetselection,lineutils,fontAwesome,youtube,menu,panel,floatpanel,contextmenu,codemirror,btgrid,dialog,tooltip,wordcount,btbutton'; 
	originpath = location.href.split( '/index' );

	op = originpath[0]; 
	frontend = op.replace('admin', '');

	config.contentsCss = [
		//op	+ '/view/template/enhancement/js/font-awesome-4.7.0/css/font-awesome.min.css',
		op	+ '/view/template/enhancement/custom-ck.css',
		frontend +  'catalog/view/javascript/bootstrap/css/bootstrap.css',
		frontend +  'catalog/view/theme/default/stylesheet/stylesheet.css',
		frontend +  'catalog/view/theme/default/stylesheet/sass/custom.css',
	];
	config.allowedContent = true;
	config.fontAwesome_version = '4.7';
	config.fontAwesome_html_tag = 'i';
	config.fontAwesome_size = 'class';
	CKEDITOR.dtd.$removeEmpty['span'] = false;
	CKEDITOR.dtd.$removeEmpty['i'] = false;
	config.fontAwesome_unicode = false;
	config.toolbar = [
		//{ name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
		//{ name: 'editing', items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
		//{ name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
		{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
		//'/',
		{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl' ] },
		{ name: 'links', items: ['Tooltip', 'RemoveTooltip', 'Link', 'Unlink', 'btbutton', '-' ] },
		//{ name: 'insert', items: [ 'Image', 'Youtube', 'Flash', '-', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'Iframe', 'ShowBlocks' ] },
		{ name: 'insert', items: [ 'Image', 'Youtube', '-', 'Table', 'HorizontalRule', 'Iframe', 'ShowBlocks' ] },
		//'/',
		//{ name: 'styles', items: [ 'FontAwesome', 'Styles', 'Format', 'Font', 'FontSize' ] },
		{ name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
		//{ name: 'colors', items: [ 'TextColor', 'BGColor', '-', 'Templates', '-', 'Source', '-', 'Maximize' ] }
		{ name: 'colors', items: [ 'TextColor', 'BGColor', '-', 'Source', '-', 'Maximize' ] }
	];

	config.removeButtons = 'Save,NewPage,Preview,Print,Replace,Find,SelectAll,Language,About,PageBreak';
	
	config.codemirror = {
		lineNumbers: true,
		lineWrapping: true,
		matchBrackets: true,
		autoCloseTags: true,
		autoCloseBrackets: true,
		enableSearchTools: true,
		enableCodeFolding: true,
		enableCodeFormatting: true,
		autoFormatOnStart: true,
		autoFormatOnModeChange: true,
		autoFormatOnUncomment: true,
		mode: 'htmlmixed',
		showSearchButton: true,
		showTrailingSpace: true,
		highlightMatches: true,
		showFormatButton: true,
		showCommentButton: true,
		showUncommentButton: true,
		showAutoCompleteButton: true,
		styleActiveLine: true
	};
	
	config.font_names = 'GoogleWebFonts;' + config.font_names;
	config.protectedSource.push(/<i[^>]*><\/i>/g);
	config.forceSimpleAmpersand = true;
	config.basicEntities = false;
	config.entities_additional = '#1049';
	config.entities_greek = false;
	config.entities_latin = false;
	
	config.tooltip_html = false;
};