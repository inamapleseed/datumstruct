CKEDITOR.editorConfig = function( config ) {
	config.timestamp = 'development'; // Remove this to cache editor config to initialize faster
	config.extraPlugins = 'widget,widgetselection,lineutils,menu,panel,floatpanel,contextmenu,codemirror,bt_table,btbutton';
	config.toolbar = [
		{ name: 'clipboard', items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
		{ name: 'editing', items: [ 'Scayt' ] },
		{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Strike', '-', 'RemoveFormat' ] },
		{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
		'/',
		{ name: 'styles', items: [ 'Styles', 'Format' ] },
		{ name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar', '-', 'Link', 'Unlink', 'btbutton' ] },
		{ name: 'tools', items: [ 'Source', 'Maximize' ] }
	];

	removeButtons = 'Subscript,Superscript,About,searchCode,autoFormat,CommentSelectedRange,UncommentSelectedRange,AutoComplete,Maximize,Strike';
	config.format_tags = 'p;h1;h2;h3;pre';

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
};
