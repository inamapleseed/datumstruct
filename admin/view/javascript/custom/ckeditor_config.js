/**
	* @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
	* For licensing, see LICENSE.md or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function (config) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	//config.language='en';
	//config.height = 450;
	config.extraPlugins = 'widget,widgetselection,lineutils,menu,panel,floatpanel,contextmenu,codemirror,bt_table,loremipsum,tooltip'; 
    config.tooltip_tag = 'span';
	config.tooltip_class = 'source-info';
	config.tooltip_html = true;
	//widget required by oembed, lineutils required by widget
	
	config.codemirror_theme = 'paraiso-dark';
	config.font_names = 'GoogleWebFonts;' +
    'Arial/Arial, Helvetica, sans-serif;' +
    'Comic Sans MS/Comic Sans MS, cursive;' +
    'Courier New/Courier New, Courier, monospace;' +
    'Georgia/Georgia, serif;' +
    'Lucida Sans Unicode/Lucida Sans Unicode, Lucida Grande, sans-serif;' +
    'Tahoma/Tahoma, Geneva, sans-serif;' +
    'Times New Roman/Times New Roman, Times, serif;' +
    'Trebuchet MS/Trebuchet MS, Helvetica, sans-serif;' +
    'Verdana/Verdana, Geneva, sans-serif';
	
	config.fontSize_sizes = '8/8px;9/9px;10/10px;11/11px;12/12px;14/14px;16/16px;18/18px;20/20px;22/22px;24/24px;26/26px;28/28px;36/36px;48/48px;72/72px;';
	config.line_height = '8px;9px;10px;11px;12px;14px;16px;18px;20px;22px;24px;26px;28px;36px;48px;72px;';
	config.allowedContent = true;
	config.removePlugins = 'magicline';
	config.wordcount_showCharCount = true;
	
	config.toolbar = [{
		name: 'document',
		groups: ['mode', 'document', 'doctools'],
		items: ['Source', '-', 'autoFormat', 'CommentSelectedRange', 'UncommentSelectedRange', 'AutoComplete', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates']
	},
    {
		name: 'Loremipsum',
		items: ['Loremipsum']
	},
    {
		name: 'editing',
		groups: ['find']
	},
    // , 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt','AutoCorrect' ]
    // { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
    '/',
    {
		name: 'basicstyles',
		groups: ['basicstyles', 'cleanup'],
		items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat']
	},
    {
		name: 'paragraph',
		groups: ['list', 'indent', 'blocks', 'align', 'bidi'],
		items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', 'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-']
	},
    {
		name: 'insert',
		items: ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'Symbol', 'PageBreak', 'Iframe', '-', 'Youtube', 'oembed', 'videosnapshot', '-', 'wenzgmap', 'osem_googlemaps', '-', 'qrc', 'simplebutton', 'WidgetTemplateMenu', 'Slideshow']
	},
    '/',
    {
		name: 'styles',
		items: ['Styles', 'Format', 'Font', 'FontSize', 'lineheight', 'letterspacing']
	},
    {
		name: 'colors',
		items: ['TextColor', 'BGColor']
	},
    {
		name: 'links',
		items: ['Tooltip', 'RemoveTooltip','Link', 'Unlink']
	},
	
    // { name: 'tools', items: [ 'Zoom','Maximize', 'ShowBlocks' ] },
    // { name: 'others', items: [ '-', 'BidiLtr', 'BidiRtl','Language' ] },
    // { name: 'about', items: [ 'About' ] }
	];
	
};
