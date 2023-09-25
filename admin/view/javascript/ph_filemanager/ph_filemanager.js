/* PH File Manager
	*
	* @copyright (C) 2017 PrimeHover (Gustavo Fernandes)
	* @desc This extension enhances the current OpenCart file manager and adds a crop section to edit images.
	* @version 1.1.0
	*
	* To view the full documentation for this extension, visit:
	* http://primehover.gufernandes.com.br/ph-file-manager
	*
	* This program is free software: you can redistribute it and/or modify
	* it under the terms of the GNU General Public License as published by
	* the Free Software Foundation, either version 3 of the License, or
	* any later version.
	*
	* You should have received a copy of the GNU General Public License
	* along with this program.  If not, see [http://www.gnu.org/licenses/].
*/

function PHFileManager(target, thumb, ckeditor_enabled, ckedialog) {
	
    this.thumb = (typeof thumb != 'undefined' && thumb != '' ? thumb : '');
    this.target = (typeof target != 'undefined' && target != '' ? target : '');
    this.searchTerm = '';
    this.directory = [];
    this.page = 1;
	this.ckeditor_enabled = ckeditor_enabled;
	this.ckedialog = ckedialog;
	
    this.token = getURLVar('token');
    this.url = {
        load:         'index.php?route=common/filemanager/load&token=' + this.token,
        move:         'index.php?route=common/filemanager/move&token=' + this.token,
        crop:         'index.php?route=common/filemanager/crop&token=' + this.token,
        remove:       'index.php?route=common/filemanager/delete&token=' + this.token,
        rename:       'index.php?route=common/filemanager/rename&token=' + this.token,
        folder:       'index.php?route=common/filemanager/folder&token=' + this.token,
        localUpload:  'index.php?route=common/filemanager/upload&token=' + this.token,
        remoteUpload: 'index.php?route=common/filemanager/remote&token=' + this.token
	};
	
    this.$modal = $('#modal-image');
    this.$breadcrumb = $("#phfm-breadcrumb");
    this.$status = $("#phfm-status");
    this.$library = $("#phfm-library");
    this.$details = $("#phfm-details");
    this.$pagination = $("#phfm-pagination");
	
    this.helpers = JSON.parse($("#phfm-helpers").val());
	
    this.selectedItems = [];
    this.croppingDirectory = '';
    this.hasDropped = false;
	
    this.staticEventHandlers();
    this.sendLoad();
	
}
PHFileManager.prototype.constructor = PHFileManager;

PHFileManager.prototype.staticEventHandlers = function() {
	
    var obj = this;
	
    var $image = $("#editorImage");
	
    this.$modal.on('hidden.bs.modal', function () {
        obj.selectedItems = [];
	});
	
    this.$modal.on('shown.bs.modal', function() {
		
        /* Initializes the cropper */
        /* It has to be initialized after the modal is shown */
        $image.cropper({
            viewMode: 0,
            zoomOnWheel: false,
            crop: function (e) {
                $("#editorStatus").html('<b>' + obj.helpers.editor_width + '</b>: ' + e.width.toFixed(0) + 'px<br /><b>' + obj.helpers.editor_height + '</b>: ' + e.height.toFixed(0) + 'px');
			}
		});
		
	});
	
    /* Refreshes the directory */
    $('#button-refresh').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        obj.selectedItems = [];
        obj.formatSelectItems();
        obj.sendLoad();
	});
	
    /* Uploads the files to the corresponding folder */
    $("#inputImage").change(function() {
        obj.handlerUpload();
	});
	
    /* Accomplishes a remote upload of an image */
    $("#button-remote").click(function() {
        obj.handlerRemote();
	});
	
    /* Deletes the selected image(s) */
    $("#button-delete").click(function() {
        obj.handlerDelete();
	});
	
    /* Deletes the selected item(s) on delete press */
    $('body').keydown(function(event) {
        var code = event.keyCode || event.which;
        if (code == 46 && obj.selectedItems.length > 0) {
            obj.handlerDelete();
		}
	});
	
    /* Creates a new folder */
    $("#button-folder").click(function() {
        obj.handlerCreateFolder();
	});
	
    /* Searches inside the current folder */
    $('#button-search').click(function() {
        obj.handlerSearch();
	});
	
    /* Searches inside the current folder */
    $('input[name="search"]').keypress(function(e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            obj.handlerSearch();
		}
	});
	
    $("#phfm-help").click(function(e) {
        e.preventDefault();
        swal({
            title: 'PH File Manager',
            text: obj.helpers.text_help_desc + '<br /><br /><small>PrimeHover Plugins<br /><a href="http://primehover.gufernandes.com.br/ph-file-manager?utm_source=filemanager&utm_medium=downloaded" target="_blank">' + obj.helpers.text_full_doc + '</a></small>',
            html: true
		});
	});
	
    /* IMAGE CROPPER HANDLERS */
	
    /* Uploads an image via Blob URL to be cropped */
    $("#inputImageEditor").change(function() {
		
        var URL = window.URL || window.webkitURL;
        var blobURL;
		
        if (URL) {
            var files = this.files;
            var file;
			
            if (!$image.data('cropper')) {
                return;
			}
			
            if (files && files.length) {
                file = files[0];
                if (/^image\/\w+$/.test(file.type)) {
                    blobURL = URL.createObjectURL(file);
                    $image.one('built.cropper', function () {
                        URL.revokeObjectURL(blobURL);
					}).cropper('reset').cropper('replace', blobURL);
                    $("#editorInitial").hide();
                    $("#editorDivCanvas").show();
                    $(this).val('');
                    obj.croppingDirectory = '';
					} else {
                    swal(obj.helpers.error_filetype);
				}
			}
			} else {
            swal(obj.helpers.error_upload_blob);
		}
		
	});
	
    /* Sets the drag mode */
    $("#editorDragMode").click(function() {
        $image.cropper('setDragMode', 'move');
	});
	
    /* Sets the crop mode */
    $("#editorCropMode").click(function() {
        $image.cropper('setDragMode', 'crop');
	});
	
    /* Sets the zoom in */
    $("#editorZoomIn").click(function() {
        $image.cropper('zoom', 0.1);
	});
	
    /* Sets the zoom out */
    $("#editorZoomOut").click(function() {
        $image.cropper('zoom', -0.1);
	});
	
    /* Sets the rotate left */
    $("#editorRotateLeft").click(function() {
        $image.cropper('rotate', -90);
	});
	
    /* Sets the rotate left */
    $("#editorRotateRight").click(function() {
        $image.cropper('rotate', 90);
	});
	
    /* Sets the flip horizontal */
    $("#editorFlipHorizontal").click(function() {
        var data = $(this).attr('data-scale');
        $image.cropper('scaleX', -data);
        $(this).attr('data-scale', -data);
	});
	
    /* Sets the flip vertical */
    $("#editorFlipVertical").click(function() {
        var data = $(this).attr('data-scale');
        $image.cropper('scaleY', -data);
        $(this).attr('data-scale', -data);
	});
	
    /* Cancels the crop */
    $("#editorCancelCrop").click(function() {
        $image.cropper('clear');
	});
	
    /* Resets the crop */
    $("#editorResetCrop").click(function() {
        $image.cropper('reset');
	});
	
    /* Saves the image that has been cropped */
    $("#editorSaveCrop").click(function() {
        obj.$modal.css('overflow-y', 'hidden');
        swal({
            title: obj.helpers.savecrop_title,
            text: obj.helpers.savecrop_desc,
            type: "input",
            showCancelButton: true,
            animation: "slide-from-top",
            inputPlaceholder: obj.helpers.savecrop_placeholder,
            inputValue: name[0],
            closeOnConfirm: false,
            showLoaderOnConfirm: true,
            confirmButtonText: obj.helpers.button_ok,
            cancelButtonText: obj.helpers.button_cancel
			}, function(inputValue) {
            if (inputValue === false || inputValue.trim() === "" || inputValue.trim().length < 3 || inputValue.trim().length > 128) {
                swal.showInputError(obj.helpers.error_rename);
                return false;
				} else {
                obj.$modal.css('overflow-y', 'auto');
			}
			
            var newImage = $("#editorImage").cropper('getCroppedCanvas');
            newImage = newImage.toDataURL();
			
            $.ajax({
                url: obj.url.crop,
                type: 'POST',
                data: {
                    directory: obj.croppingDirectory,
                    name: inputValue.trim(),
                    image: newImage
				},
                success: function (data) {
                    if (typeof data.success != 'undefined') {
                        swal('', data.success, 'success');
                        obj.sendLoad();
						} else if (typeof data.error != 'undefined') {
                        swal('', data.error, 'error');
					}
				},
                error: function (x) {
                    console.log(x.responseText);
				}
			});
			
		});
		
	});
	
};

PHFileManager.prototype.loadEventHandlers = function() {
	
    var obj = this;
	
    this.selectedItems = [];
	
    $(".draggable").draggable({
        revert: function(dropped) {
            if (!dropped) {
                $('.draggable').animate({opacity: 1, left: 0, top: 0}, 500);
			}
		},
        opacity: 0.25,
        start: function(event, ui) {
            if (typeof ui.helper != 'undefined') {
                $('.draggable').each(function() {
                    if (obj.selectedItems.indexOf($(this).data('info')) > -1) {
                        $(this).addClass('top-z');
					}
				});
                $(this).addClass('top-z');
                obj.pushSelectedItem(ui.helper.data('info'));
			}
		},
        stop: function(event, ui) {
            if (typeof ui.helper != 'undefined') {
                ui.helper.removeClass('top-z');
			}
		},
        drag: function(event, ui) {
			
            $(this).addClass('top-z');
            var currentLoc = $(this).position();
            var orig = ui.originalPosition;
            var offsetLeft = currentLoc.left-orig.left;
            var offsetTop = currentLoc.top-orig.top;
            var element = 0;
            var l = 0;
            var t = 0;
			
            $('.draggable').each(function() {
                if (obj.selectedItems.indexOf($(this).data('info')) > -1) {
                    element = $(this);
                    l = element.context.clientLeft;
                    t = element.context.clientTop;
                    element.css('left', l+offsetLeft);
                    element.css('top', t+offsetTop);
				}
			});
		}
	});
    $(".droppable").droppable({
        accept: '.draggable',
        activeClass: "draggable-active",
        hoverClass: "draggable-hover",
        drop: function(event, ui) {
            ui.draggable.draggable('option', 'revert', function(){return false});
            var info = $(this).data('info');
            if (typeof info != 'undefined' && info == 'parent') {
                obj.handlerMove('');
                obj.hasDropped = true;
				} else if (typeof info != 'undefined' && info.hasOwnProperty('path')) {
                obj.handlerMove(info.path);
                obj.hasDropped = true;
			}
		}
	});
    $(".selectable").selectable({
        cancel: '.directory',
        selecting: function( event, ui ) {
            ui = $(ui.selecting);
            if (ui.attr('class').indexOf('item-library') > -1 && obj.selectedItems.indexOf(ui.data('info')) == -1) {
                obj.pushSelectedItem(ui.data('info'));
                obj.formatSelectItems();
			}
		},
        unselecting: function( event, ui ) {
            ui = $(ui.unselecting);
            if (ui.attr('class').indexOf('item-library') > -1) {
                var index = obj.selectedItems.indexOf(ui.data('info'));
                if (index > -1) {
                    obj.selectedItems.splice(index, 1);
                    obj.formatSelectItems();
				}
			}
		},
        selected: function( event, ui ) {
            ui = $(ui.selected).children('.item-library');
            if (ui.length == 1 && obj.selectedItems.indexOf(ui.data('info')) == -1) {
                obj.pushSelectedItem(ui.data('info'));
                obj.formatSelectItems();
			}
		},
        unselected: function( event, ui ) {
            ui = $(ui.unselected).children('.item-library');
            if (ui.length == 1) {
                var index = obj.selectedItems.indexOf(ui.data('info'));
                if (index > -1) {
                    obj.selectedItems.splice(index, 1);
                    obj.formatSelectItems();
				}
			}
		}
	});
	
    /* Cancels and cleans the selection */
    $("#button-cancel-selection").click(function() {
        var selectable = $(".selectable");
        selectable.children('div').removeClass('ui-selected');
        selectable.children('div').removeClass('ui-selecting');
        selectable.selectable("refresh");
        obj.handlerCancelSelection();
        obj.formatSelectItems();
	});
	
    /* Opens a directory */
    $('a.directory').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        console.log(obj.hasDropped, obj.directory);
        if (!obj.hasDropped) {
            var info = $(this).parent().data('info');
            if (typeof info != 'undefined' && info == 'parent') {
                obj.directory.pop();
                obj.searchTerm = '';
                obj.page = 1;
                obj.sendLoad();
                obj.selectedItems = [];
                obj.formatSelectItems();
				} else if (typeof info != 'undefined' && info.hasOwnProperty('name')) {
                obj.directory.push(info.name);
                obj.searchTerm = '';
                obj.page = 1;
                obj.sendLoad();
                obj.selectedItems = [];
                obj.formatSelectItems();
			}
		}
	});
	
    /* Selects an image and see its details */
    $('a.image').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var selectable = $(".selectable");
        var info = $(this).parent().data('info');
        if (e.ctrlKey == false) {
            selectable.children('div').removeClass('ui-selected');
            $(this).parent().parent().addClass('ui-selected');
            obj.selectedItems = [];
            obj.loadDetails(info);
            $("#button-cancel-selection").show();
			} else {
            var index = obj.selectedItems.indexOf(info);
            if (index == -1) {
                $(this).parent().parent().addClass('ui-selected');
                obj.pushSelectedItem(info);
				} else {
                $(this).parent().parent().removeClass('ui-selected');
                obj.selectedItems.splice(index, 1);
			}
            obj.formatSelectItems();
		}
        selectable.selectable("refresh");
	});
	
    /* Goes to another page */
    $('.pagination a').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var page = parseInt($(this).attr('href').replace('#', ''));
        if (page > 0) {
            obj.page = page;
            obj.sendLoad();
		}
	});
	
};

PHFileManager.prototype.loadDynamicEventHandlers = function() {
	
    var obj = this;
	//console.log(obj);
    /* Initializes all the tooltips */
    $('[data-toggle="tooltip"]').tooltip();
	
    /* Selects the image and places it into the form */
    $("#btnSelect").click(function() {
		
        if (obj.selectedItems.length > 0) {
            if (obj.thumb != '') {
                $("#" + obj.thumb).find('img').attr('src', obj.selectedItems[0].thumb);
			}
            if (obj.target != '') {
                $("#" + obj.target).val(obj.selectedItems[0].path);
			}
            var range, node, textarea, sel = window.getSelection();
            if (sel.rangeCount) {
				
                var img = document.createElement('img'); //cl(obj.selectedItems[0]);
                img.src = obj.selectedItems[0].href;
                range = sel.getRangeAt(0);
                node = range.commonAncestorContainer ? range.commonAncestorContainer : range.parentElement ? range.parentElement() : range.item(0);
				
				var name = obj.selectedItems[0].name;
				name = name.split(".");
				name = name[0];
				
				// Ckeditor
				if(typeof CKEDITOR != "undefined"){ 
					//console.log("ck");
					dialog = CKEDITOR.dialog.getCurrent();
					var targetElement = obj.ckedialog || null;
					
					if(targetElement){
						var target = targetElement.split( ':' );
						
						
						//dialog.setValueOf( target[ 0 ], target[ 1 ], obj.selectedItems[0].href );
						dialog.setValueOf( target[ 0 ], target[ 1 ], "image/" + obj.selectedItems[0].path );
						
						// custom
						// info:txtUrl
						// try: info:txtAlt
						
						dialog.setValueOf( 'info', 'txtAlt', name );
						
						// try: info:txtClass
						init_value = dialog.getValueOf( 'advanced', 'txtGenClass');
						//cl(init_value.indexOf("img-responsive"));
						if( init_value.indexOf("img-responsive") == -1 ){ 
							init_value += " img-responsive ";
						}
						
						dialog.setValueOf( 'advanced', 'txtGenClass', init_value );
						// End custom
					}
				}
				else{
					//console.log("summer");
					textarea = $(node).parents('.note-editor').siblings('textarea');
					//console.log(textarea);
					textarea.summernote('insertImage', obj.selectedItems[0].href);
				}
				// End Ckeditor
			}
            obj.$modal.modal('hide');
            obj.selectedItems = [];
		}
		
	});
	
    /* Deletes the selected image(s) */
    $("#btnDelete").click(function() {
        obj.handlerDelete();
	});
	
    /* Renames a file or directory */
    $("#btnRename").click(function() {
        obj.handlerRename();
	});
	
    /* Sends an image to the image editor to crop */
    $("#btnCrop").click(function() {
        if (obj.selectedItems.length == 1 && obj.selectedItems[0] != 'parent') {
            $("#editorInitial").hide();
            $("#editorDivCanvas").show();
            $("#phfm-navs a:last").on('shown.bs.tab', function() {
                $("#editorImage").cropper('reset').cropper('replace', obj.selectedItems[0].href).cropper('resize');
			}).tab('show');
            obj.croppingDirectory = obj.directory.join('/');
		}
	});
	
};

PHFileManager.prototype.sendLoad = function() {
	
    var obj = this;
	
    $.ajax({
        url: obj.url.load,
        type: 'POST',
        data: obj.getData(),
        beforeSend: function() {
            obj.printStatusBeforeSend();
            obj.$library.css({ opacity: 0.4 });
		},
        success: function(data) {
            data = JSON.parse(data);
			//console.log(data);
            obj.formatData(data);
            obj.loadEventHandlers();
            obj.formatBreadcrumb();
            obj.printStatusSuccess();
            obj.$library.css({ opacity: 1 });
		},
        error: function(x) {
            console.log(x.responseText);
            obj.printStatusError();
            obj.$library.css({ opacity: 1 });
		}
	});
	
};

PHFileManager.prototype.getData = function() {
    return {
        thumb: this.thumb,
        target: this.target,
        searchTerm: this.searchTerm,
        directory: this.directory.join('/'),
        page: this.page
	}
};

PHFileManager.prototype.loadDetails = function(details) {
	
    var str = '<p class="text-center">';
    str += '<img src="' + details.thumb + '" class="img-responsive img-thumbnail" style="max-height: 100px;" /><br />';
    str += '<b>' + this.helpers.details_name + '</b>: ' + details.name + '<br />';
    str += '<b>' + this.helpers.details_type + '</b>: ' + details.type + '<br />';
    str += '<b>' + this.helpers.details_size + '</b>: ' + this.formatBytes(details.size) + '<br />';
    str += '<b>' + this.helpers.details_location + '</b>: ' + details.path + '</p>';
	
    str += '<p class="text-center">';
    str += '<button class="btn btn-success" id="btnSelect" data-toggle="tooltip" data-placement="top" title="' + this.helpers.button_select + '"><i class="fa fa-check"></i></button> ';
	
	if(details.type == "image"){
		str += '<button class="btn btn-primary" id="btnCrop" data-toggle="tooltip" data-placement="top" title="' + this.helpers.button_crop + '"><i class="fa fa-crop"></i></button> ';
	}
	
    str += '<button class="btn btn-info" id="btnRename" data-toggle="tooltip" data-placement="top" title="' + this.helpers.button_rename + '"><i class="fa fa-pencil"></i></button> ';
    str += '<button class="btn btn-danger" id="btnDelete" data-toggle="tooltip" data-placement="top" title="' + this.helpers.button_delete + '"><i class="fa fa-trash"></i></button> ';
    str += '</p>';
	
    this.selectedItems = [];
    this.pushSelectedItem(details);
	
    this.$details.html(str);
    this.loadDynamicEventHandlers();
	
};

PHFileManager.prototype.pushSelectedItem = function(item) {
    if (this.selectedItems.indexOf(item) == -1) {
        this.selectedItems.push(item);
	}
};

PHFileManager.prototype.formatData = function(data) { 
	
    var str = '';
	
    if (data.directory != '' && typeof data.parent != 'undefined') {
        str += '<div class="col-md-3 col-sm-4 col-xs-6 text-center padding-item"><div class="item-library thumbnail-out droppable ignore" data-info="parent"><a href="javascript:void(0)" class="thumbnail directory"><i class="fa fa-reply fa-5x" style="color: #000" ></i></a><label>Back to '+ decodeURIComponent(data.directory) +'</label></div></div>';
	}
	//cl(data);
    if (typeof data.images != 'undefined') {
        for (var i = 0; i < data.images.length; i++) {
            str += '<div class="col-md-3 col-sm-4 col-xs-6 text-center padding-item" title="' +data.images[i].name+ '">';
            if (data.images[i].type == 'directory') {
                str += '<div class="item-library thumbnail-out draggable droppable" data-info=\'' + JSON.stringify(data.images[i]) + '\'><a href="javascript:void(0)" class="thumbnail directory"><i class="fa fa-folder fa-5x"></i></a><label>' + data.images[i].name.substr(0, 20) + '</label></div>';
			//} else if (data.images[i].type == 'image') {
            } else {
			str += '<div class="item-library thumbnail-out draggable" data-info=\'' + JSON.stringify(data.images[i]) + '\'><a href="javascript:void(0)" class="thumbnail image"><img src="' + data.images[i].thumb + '" alt="' + data.images[i].name + '" title="' + data.images[i].name + '" style="max-width:100px;" /></a><label class="truncate text-center" >' + data.images[i].name + '</label></div>'
		}
		str += '</div>';
	}
}

if (typeof data.pagination != 'undefined') {
	this.$pagination.html(data.pagination);
}

this.$library.html(str);

};

PHFileManager.prototype.formatBytes = function(bytes,decimals) {
    if(bytes == 0) return '0 B';
    var k = 1000;
    var dm = decimals + 1 || 2;
    var sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    var i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
};

PHFileManager.prototype.formatSelectItems = function() {
	
    var json = {};
    var totalSize = 0;
    var str = (this.selectedItems.length == 1 ? this.helpers.selecting_singular.replace('%s', this.selectedItems.length) : this.helpers.selecting_plural.replace('%s', this.selectedItems.length));
    for (var i = 0; i < this.selectedItems.length; i++) {
        json = this.selectedItems[i];
        if (typeof json.size != 'undefined') {
            totalSize += parseInt(json.size);
		}
	}
    str += ' (' + this.formatBytes(totalSize) + ')';
	
    this.formatCancelSelection();
    this.$details.html(str);
	
};

PHFileManager.prototype.formatBreadcrumb = function() {
	
    var str = '';
    str += '<ol class="breadcrumb breadcrumb-manager">';
    str += '<li><a' + (this.directory.length == 0 ? ' class="active"' : '') + '>' + this.helpers.text_library_folder + '</a></li>';
    for (var i = 0; i < this.directory.length; i++) {
        str += '<li><a' + (i+1 == this.directory.length ? ' class="active"' : '') + '>' + this.directory[i] + '</a></li>';
	}
    str += '</ol>';
    this.$breadcrumb.html(str);
	
};

PHFileManager.prototype.formatCancelSelection = function() {
    if (this.selectedItems.length > 0) {
        $("#button-cancel-selection").show();
		} else {
        $("#button-cancel-selection").hide();
	}
};

PHFileManager.prototype.printStatusBeforeSend = function() {
    this.handlerCancelSelection();
    this.$status.html('<span><i class="fa fa-spin fa-spinner"></i> ' + this.$status.data("text-loading") + '</span>');
};

PHFileManager.prototype.printStatusSuccess = function() {
    this.$status.html('<span class="text-success"><i class="fa fa-check"></i> ' + this.$status.data("text-success") + '</span>');
};

PHFileManager.prototype.printStatusError = function() {
    this.$status.html('<span class="text-danger"><i class="fa fa-times"></i> ' + this.$status.data("text-error") + '</span>');
};

PHFileManager.prototype.handlerCancelSelection = function() {
    this.selectedItems = [];
    this.formatCancelSelection();
};

PHFileManager.prototype.handlerUpload = function() {
    var obj = this;
    var count = $("#inputImage")[0].files.length;
    var directory = obj.directory.join('/');
    var formData = new FormData($("#form-upload")[0]);
	
    $.ajax({
        url: obj.url.localUpload + '&directory=' + directory,
        type: 'POST',
        cache: false,
        processData: false,
        contentType: false,
        dataType: 'json',
        data: formData,
        beforeSend: function() {
            obj.printStatusBeforeSend();
            obj.$details.html('<span>' + (count == 1 ? obj.helpers.uploading_singular : obj.helpers.uploading_plural) + '</span>');
		},
        success: function(data) {
            if (typeof data.success != 'undefined') {
                obj.printStatusSuccess();
                obj.$details.html('<span class="text-success"><i class="fa fa-check"></i> ' + data.success + '</span>');
                obj.sendLoad();
				} else if (typeof data.error != 'undefined') {
                obj.printStatusError();
                obj.$details.html('<span class="text-danger"><i class="fa fa-times"></i> ' + data.error + '</span>');
			}
		},
        error: function(x) {
            console.log(x.responseText);
            obj.printStatusError();
		},
        xhr: function() {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function(evt) {
                if (evt.lengthComputable) {
                    var percentComplete = evt.loaded / evt.total;
                    percentComplete = parseInt(percentComplete * 100);
                    obj.$details.html('<span>' + (count == 1 ? obj.helpers.uploading_singular : obj.helpers.uploading_plural) + ' (' + percentComplete + '%)</span>');
				}
			}, false);
            return xhr;
		}
	});
};

PHFileManager.prototype.handlerRemote = function() {
    var obj = this;
    swal({
        title: obj.helpers.remote_upload_title,
        text: obj.helpers.remote_upload_desc,
        type: "input",
        showCancelButton: true,
        animation: "slide-from-top",
        inputPlaceholder: obj.helpers.remote_upload_placeholder,
        closeOnConfirm: false,
        confirmButtonText: obj.helpers.button_ok,
        cancelButtonText: obj.helpers.button_cancel
		}, function(inputValue){
        if (inputValue === false || inputValue.trim() === "") {
            swal.showInputError(obj.helpers.error_url);
            return false
			} else {
            swal.close();
		}
		
        $.ajax({
            url: obj.url.remoteUpload,
            type: 'POST',
            data: { directory: obj.directory.join('/'), url: inputValue.trim() },
            beforeSend: function() {
                obj.printStatusBeforeSend();
                obj.$details.html('<span>' + obj.helpers.uploading_singular + '</span>');
			},
            success: function(data) {
                console.log(data);
                if (typeof data.success != 'undefined') {
                    obj.printStatusSuccess();
                    obj.$details.html('<span class="text-success"><i class="fa fa-check"></i> ' + data.success + '</span>');
                    obj.sendLoad();
					} else if (typeof data.error != 'undefined') {
                    obj.printStatusError();
                    obj.$details.html('<span class="text-danger"><i class="fa fa-times"></i> ' + data.error + '</span>');
				}
			},
            error: function(x) {
                console.log(x.responseText);
                obj.printStatusError();
			}
		});
		
	});
};

PHFileManager.prototype.handlerCreateFolder = function() {
    var obj = this;
    obj.$modal.css('overflow-y', 'hidden');
    swal({
        title: obj.helpers.folder_title,
        text: obj.helpers.folder_desc,
        type: "input",
        showCancelButton: true,
        animation: "slide-from-top",
        inputPlaceholder: obj.helpers.folder_placeholder,
        closeOnConfirm: false,
        confirmButtonText: obj.helpers.button_ok,
        cancelButtonText: obj.helpers.button_cancel
		}, function(inputValue){
        if (inputValue === false || inputValue.trim() === "" || inputValue.length < 3 || inputValue.length > 128) {
            swal.showInputError(obj.helpers.error_folder);
            return false
			} else {
            obj.$modal.css('overflow-y', 'auto');
            swal.close();
		}
		
        $.ajax({
            url: obj.url.folder,
            type: 'POST',
            data: { directory: obj.directory.join('/'), name: inputValue.trim() },
            beforeSend: function() {
                obj.printStatusBeforeSend();
                obj.$details.html('<span>' + obj.helpers.creating_folder + '</span>');
			},
            success: function(data) {
                if (typeof data.success != 'undefined') {
                    obj.printStatusSuccess();
                    obj.$details.html('<span class="text-success"><i class="fa fa-check"></i> ' + data.success + '</span>');
                    obj.sendLoad();
					} else if (typeof data.error != 'undefined') {
                    obj.printStatusError();
                    obj.$details.html('<span class="text-danger"><i class="fa fa-times"></i> ' + data.error + '</span>');
				}
			},
            error: function(x) {
                console.log(x.responseText);
                obj.printStatusError();
			}
		});
		
	});
};

PHFileManager.prototype.handlerDelete = function() {
	
    var obj = this;
	
    if (obj.selectedItems.length > 0) {
		
        swal({
            title: obj.helpers.delete_title,
            text: (obj.selectedItems.length == 1 ? obj.helpers.delete_desc_singular : obj.helpers.delete_desc_plural),
            type: "warning",
            showCancelButton: true,
            animation: "slide-from-top",
            closeOnConfirm: false,
            confirmButtonText: obj.helpers.button_ok,
            cancelButtonText: obj.helpers.button_cancel
			}, function(){
			
            var paths = [];
            for (var i = 0; i < obj.selectedItems.length; i++) {
                if (typeof obj.selectedItems[i].path != 'undefined') {
                    paths.push(obj.selectedItems[i].path);
				}
			}
            swal.close();
			
            if (paths.length > 0) {
                $.ajax({
                    url: obj.url.remove,
                    type: 'POST',
                    data: {path: paths},
                    beforeSend: function () {
                        obj.printStatusBeforeSend();
                        obj.$details.html('<span>' + obj.helpers.deleting + '</span>');
					},
                    success: function (data) {
                        if (typeof data.success != 'undefined') {
                            obj.printStatusSuccess();
                            obj.$details.html('<span class="text-success"><i class="fa fa-check"></i> ' + data.success + '</span>');
                            obj.sendLoad();
							} else if (typeof data.error != 'undefined') {
                            obj.printStatusError();
                            obj.$details.html('<span class="text-danger"><i class="fa fa-times"></i> ' + data.error + '</span>');
						}
					},
                    error: function (x) {
                        console.log(x.responseText);
                        obj.printStatusError();
					}
				});
				} else {
                obj.printStatusError();
			}
			
		});
	}
};

PHFileManager.prototype.handlerRename = function() {
	
    var obj = this;
    obj.$modal.css('overflow-y', 'hidden');
	
    if (obj.selectedItems.length == 1) {
        var name = obj.selectedItems[0].name.replace(' ', '').split('.');
        swal({
            title: obj.helpers.rename_title,
            text: obj.helpers.rename_desc,
            type: "input",
            showCancelButton: true,
            animation: "slide-from-top",
            inputPlaceholder: obj.helpers.rename_placeholder,
            inputValue: name[0],
            closeOnConfirm: false,
            confirmButtonText: obj.helpers.button_ok,
            cancelButtonText: obj.helpers.button_cancel
			}, function (inputValue) {
            obj.$modal.css('overflow-y', 'auto');
            if (inputValue === false || inputValue.trim() === "" || inputValue.trim().length < 3 || inputValue.trim().length > 128 || obj.selectedItems[0].name == inputValue.trim()) {
                swal.showInputError(obj.helpers.error_rename);
                return false
				} else {
                swal.close();
			}
			
            $.ajax({
                url: obj.url.rename,
                type: 'POST',
                data: {
                    directory: obj.directory.join('/'),
                    oldName: name[0] + '.' + name[1],
                    newName: inputValue.trim()
				},
                beforeSend: function () {
                    obj.printStatusBeforeSend();
                    obj.$details.html('<span>' + obj.helpers.renaming + '</span>');
				},
                success: function (data) {
                    if (typeof data.success != 'undefined') {
                        obj.printStatusSuccess();
                        obj.$details.html('<span class="text-success"><i class="fa fa-check"></i> ' + data.success + '</span>');
                        obj.sendLoad();
						} else if (typeof data.error != 'undefined') {
                        obj.printStatusError();
                        obj.$details.html('<span class="text-danger"><i class="fa fa-times"></i> ' + data.error + '</span>');
					}
				},
                error: function (x) {
                    console.log(x.responseText);
                    obj.printStatusError();
				}
			});
		});
	}
	
};

PHFileManager.prototype.handlerMove = function(newDirectory) {
	
    var obj = this;
	
    var parent = this.selectedItems.indexOf('parent');
    if (parent > -1) {
        this.selectedItems.splice(parent, 1);
	}
	
    if (this.selectedItems.length > 0) {
		
        var oldDirectory = obj.directory.join('/');
        if (newDirectory.trim() == '') {
            var element = obj.directory.pop();
            newDirectory = obj.directory.join('/');
            obj.directory.push(element);
		}
		
        $.ajax({
            url: obj.url.move,
            type: 'POST',
            data: {
                selected: obj.selectedItems,
                oldDirectory: oldDirectory,
                newDirectory: newDirectory.trim()
			},
            beforeSend: function () {
                obj.printStatusBeforeSend();
                obj.$details.html('<span>' + obj.helpers.moving + '</span>');
			},
            success: function (data) {
                obj.hasDropped = false;
                if (typeof data.success != 'undefined') {
                    obj.printStatusSuccess();
                    obj.$details.html('<span class="text-success"><i class="fa fa-check"></i> ' + data.success + '</span>');
                    obj.sendLoad();
					} else if (typeof data.error != 'undefined') {
                    obj.printStatusError();
                    obj.$details.html('<span class="text-danger"><i class="fa fa-times"></i> ' + data.error + '</span>');
				}
			},
            error: function (x) {
                obj.hasDropped = true;
                console.log(x.responseText);
                obj.printStatusError();
			}
		});
	}
	
};

PHFileManager.prototype.handlerSearch = function() {
    this.searchTerm = $('input[name="search"]').val();
    this.selectedItems = [];
    this.formatSelectItems();
    this.sendLoad();
	};	