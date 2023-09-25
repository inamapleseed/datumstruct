// If value 1 only, then value 1 is address
// If value 1&2, then value 1 is street, and value 2 is block/unit
// If value 1,2,3 then value 1 is street, value 2 is building, value 3 is block/unit

// Sample return data
/*
	response{
	results: [
	0: [
	ADDRESS"158 KALLANG WAY PERFORMANCE BUILDING SINGAPORE 349245"
	BLK_NO:"158"
	BUILDING:"PERFORMANCE BUILDING"
	LATITUDE:"1.32320025483415"
	LONGITUDE:"103.876549935471"
	LONGTITUDE:"103.876549935471"
	POSTAL:"349245"
	ROAD_NAME:"KALLANG WAY"
	SEARCHVAL:"PERFORMANCE BUILDING"
	X:"32811.2183294455"
	Y:"33938.3202846274"
	]
	]
	}
*/

// Multilevel Accordion for Mobile menu
jQuery(document).ready(function(){var e=$(".cd-accordion-menu");e.length>0&&e.each(function(){$(this).on("change",'input[type="checkbox"]',function(){var e=$(this);console.log(e.prop("checked")),e.prop("checked")?e.siblings("ul").attr("style","display:none;").slideDown(300):e.siblings("ul").attr("style","display:block;").slideUp(300)})})});

// Quantity Increment
function increment(t) {UpdateQuantity(t.find(".input-number"), !0) } 
function descrement(t) { UpdateQuantity(t.find(".input-number"), !1) } 
function UpdateQuantity(t,n){var i=getQuantity(t);if(isNaN(i)) {i = 0}i+=1*(n?1:-1),1>i&&(i=1),t.attr("value",i.toString()).val(i.toString())}
function getQuantity(t){var n=parseInt(t.val());return("NaN"==typeof n||1>n)&&(n=1),n}
function quantity_increment(t){UpdateQuantity(t.find(".product-quantity"),!0)}function quantity_decrement(t){UpdateQuantity(t.find(".product-quantity"),!1)}

function postalcode(ele, value1, value2, value3){
	var loading_tpl = '<i class="loading-prefix-js fa fa-spinner fa-pulse absolute-center-right"></i>';
	if($(ele).length){
		var requesting = null;
		$(ele).on("keyup", function(e){

			e.preventDefault();
			e.stopPropagation();

			if(Number($(ele).val()) && $(ele).val().length == 6){

				var target_src = $(ele);

				if(target_src.length == 1) {
					var html = $(target_src[0]).prop("tagName");
					if(html == 'INPUT'){
						target_src.parent().addClass('relative');
						target_src.parent().append(loading_tpl);
					}
				}
				
				if(requesting){
					requesting.abort();
				}

				requesting = $.get("https://developers.onemap.sg/commonapi/search?returnGeom=Y&getAddrDetails=Y&pageNum=1&searchVal=" + $(ele).val(), function(response){
					if('results' in response && response.found > 0){
						block = response.results[0].BLK_NO;
						street = response.results[0].ROAD_NAME;
						building = response.results[0].BUILDING;
						
						//cl(response); // console.log
						if(value1 && value2 && value3){ 
							$(value1).val(street);
							$(value2).val(building);
							$(value3).val(block);
						}
						else if(value1 && value2){
							$(value1).val(block+" "+street);
							$(value2).val(building);
						}
						else if (value1){ 
							address = response.results[0].ADDRESS; // As a whole
							address_only = address.replace(response.results[0].POSTAL, "");
							
							$(value1).val(address_only);
						}
						else{
							// No value
						}
					}

					$('.loading-prefix-js').remove();
				});
			}

		});
	}
	$('.loading-prefix-js').remove();
}

function view_password(el){
	if (!el) return;
	var _input = $(el).prev();
	if (_input.attr('type') == 'password'){
		_input.attr('type', 'text');
	}
	else{
		_input.attr('type', 'password');
	}

	// Change icon
	if($(el).hasClass('fa-eye')){
		$(el).removeClass('fa-eye').addClass('fa-eye-slash');
	}
	else if($(el).hasClass('fa-eye-slash')){
		$(el).removeClass('fa-eye-slash').addClass('fa-eye');
	}
}

$(document).ready(function () {

	var safari_animation_fix = setTimeout(function(){
		$('.fixed-header').css({'left':'0'});
		$('.page_transition').css({'opacity':'0'});
	}, 100);

	$('#main-menu').smartmenus({
		subMenusSubOffsetY: -1
	});

	$('input[type="password"]').each(function () {

		$view_password = '<i style="[STYLE]" class="fa fa-eye pointer absolute view-password" aria-hidden="true" onclick="view_password(this);" ></i>';
		//$view_password = '<i style="[STYLE]" class="fa fa-eye pointer absolute view-password" aria-hidden="true" onmousedown="view_password(this);" onmouseup="view_password(this);" ></i>';

		// Element Control
		el_password = $(this);

		// Label-control
		el_password_label = el_password.prev(); //cl(el_password_label);

		// Btn-group
		el_password_btn = el_password.next('.input-group-btn');

		el_password_parent = el_password.parent();
		// End Element Control

		// Position Control
		el_parent_padding_right = el_password_parent.css('padding-right');

		el_parent_padding_right = parseInt(el_parent_padding_right);

		el_password_parent.addClass('relative');

		let half_input_height = el_password.outerHeight() * 0.5;

		el_password_padding_right = el_password.css('padding-right');

		el_password_padding_right = parseInt(el_password_padding_right);

		right_input_padding =  el_parent_padding_right;

		if (el_password_label.length && el_password_label.is(':visible')){
			let label_height = $(el_password_label).outerHeight(true); 
			label_height = parseInt(label_height);
			half_input_height += label_height;
		}

		if (el_password_btn.length){
			let label_width = $(el_password_btn).outerWidth(true); 
			label_width = parseInt(label_width);
			right_input_padding += label_width;
		}
		// End Position Control
		
		$view_password_style = "transform: translateY(-50%);";
		$view_password_style += "height:" + el_password.innerHeight() + "px;";
		$view_password_style += "top:" + half_input_height + "px;";
		$view_password_style += "right:" + right_input_padding + "px;";
		$view_password_style += "padding: 0px " + el_password_padding_right + "px;";

		$view_password = $view_password.replace('[STYLE]', $view_password_style);
		
		el_password.after($view_password);

		var eye_render_width = el_password.next('i').width();
			eye_render_width = parseInt(eye_render_width);
			eye_render_width += (el_password_padding_right * 2);
		el_password.css('padding-right', eye_render_width);
	});

	var $link_condition = 'a[href]';
		$link_condition += ':not([href="#"])';
		$link_condition += ':not([href^="tel"])';
		$link_condition += ':not([href^="mailto"])';
		$link_condition += ':not([href^="fax"])';	//	 Mobile not supported
		$link_condition += ':not([download])';
		$link_condition += ':not(.agree)';
		$link_condition += ':not(.esc)';
		$link_condition += ':not(.colorbox)';
		$link_condition += ':not([data-toggle="tab"])';
		$link_condition += ':not([data-toggle="collapse"])';
		$link_condition += ':not([data-toggle="dropdown"])';
		$link_condition += ':not([data-toggle="modal-content"])';
		$link_condition += ':not([target])';

	var transition_duration = $('body').css('transition-duration');
	var transition_delay = $('body').css('transition-delay');
	var transition_in_seconds = 0; // Step up delay for javascript transition
	
	if(transition_duration.indexOf('ms') > 0){ 
		transition_in_seconds = parseInt(transition_duration.replace('ms', ''));
	}
	else if(transition_duration.indexOf('s') > 0){
		transition_in_seconds = parseFloat(transition_duration.replace('s', '')) * 1000; 
	}

	if(transition_delay.indexOf('ms') > 0){ 
		transition_in_seconds += parseInt(transition_delay.replace('ms', ''));
	}
	else if(transition_delay.indexOf('s') > 0){
		transition_in_seconds += parseFloat(transition_delay.replace('s', '')) * 1000; 
	}
	
	$($link_condition).each(function () {
		$(this).on('click', function (e) {
			e.preventDefault();
			var href = this.href;
			
			//$('body').removeAttr('style');
			$('body').addClass('transition-state');

			setTimeout(function(){
				location = href;
			}, transition_in_seconds);
		});
	});
	
	$('body').addClass('done');
	$(window).resize(function () {
		padding_top = $('.fixed-header').outerHeight(true);
		padding_bottom = $('#footer-area').outerHeight(true);
		$('body').css({ 'padding-top': padding_top, 'padding-bottom': padding_bottom});
	}).resize();
	
	$('#cart .dropdown-menu, #cart .dropdown-menu *').on('click', function (e) {
		e.stopPropagation();
	});
});

var last_click = null;
$(window).load(function () {
	
	$('.cke_iframe').each(function(){
		ele = $(this);
		iframe = ele.attr('data-cke-realelement');
		iframe = decodeURIComponent(iframe);
		// cl(iframe);
		ele.after(iframe);
		ele.remove();
	});

	$('#side-categories .toggle').on('click', function(e){
	
		e.preventDefault();
		ele = $(this).parent();
		
		if(ele.hasClass('active')){
			ele.removeClass('active');
		}
		else{ 
			if(ele.hasClass('.level-1')){
				$('.level-1.active').removeClass('active');
			}
			else if(ele.hasClass('.level-2')){
				$('.level-2.active').removeClass('active');
			}
			
			ele.addClass('active');
		}
	});

	$(".list-group .input-group .input-group-addon").click(function (e) {
		e.preventDefault();
		e.stopPropagation();
		
		if (this != last_click) {
			
			last_click = this;
			
			// Level Parent
			parent = $(this).data("parent");
			//cl(parent);
			$("." + parent).removeClass("active");
			
			// Level child
			level = $(this).data("level");
			$("." + level).stop().slideUp(300);
			
			$(this).prev().addClass("active");
			
			child = $("." + this.id);
			if (child.length) {
				child.stop().slideDown(300);
			}
		} else {
			
		}
	});
	
	numbers_only();

});


function numbers_only(){
	$('.input-number').each(function () {
		$(this).keydown(function (e) {
			//cl(e.keyCode);
			// Allow: backspace, delete, tab, escape, enter and .
			// 110 - dot (.)
			// 190 - angle right (>)

			if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
				// Allow: Ctrl/cmd+A
				(e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
				// Allow: Ctrl/cmd+C
				(e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
				// Allow: Ctrl/cmd+X
				(e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
				// Allow: home, end, left, right
				(e.keyCode >= 35 && e.keyCode <= 39)) {
				// let it happen, don't do anything
				return;
			}
			// Ensure that it is a number and stop the keypress
			if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
				e.preventDefault();
			}
		});
	});
}


/* UItoTop jQuery */
jQuery(document).ready(function () {
	$().UItoTop({
		easingType: 'easeOutQuint'
	});
});

(function ($) {
	$.fn.UItoTop = function (options) {
		var defaults = {
			text: 'To Top',
			min: 200,
			inDelay: 600,
			outDelay: 400,
			containerID: 'ToTop',
			containerHoverID: 'ToTopHover',
			scrollSpeed: 800,
			easingType: 'linear'
		};
		
		var settings = $.extend(defaults, options);
		var containerIDhash = '#' + settings.containerID;
		var containerHoverIDHash = '#' + settings.containerHoverID;
		$('body').append('<span id="' + settings.containerID + '">' + settings.text + '</span>');
		$(containerIDhash).hide().click(function (event) {
			$('html, body').animate({
				scrollTop: 0
			}, settings.scrollSpeed);
			event.preventDefault();
		})
		.prepend('<span id="' + settings.containerHoverID + '"></span>')
		.hover(function () {
			$(containerHoverIDHash, this).stop().animate({
				'opacity': 1
			}, 600, 'linear');
			}, function () {
			$(containerHoverIDHash, this).stop().animate({
				'opacity': 0
			}, 700, 'linear');
		});
		
		$(window).scroll(function () {
			var sd = $(window).scrollTop();
			if (typeof document.body.style.maxHeight === "undefined") {
				$(containerIDhash).css({
					'position': 'absolute',
					'top': $(window).scrollTop() + $(window).height() - 50
				});
			}
			if (sd > settings.min)
			$(containerIDhash).fadeIn(settings.inDelay);
			else
			$(containerIDhash).fadeOut(settings.Outdelay);
		});

		initializeMobileNav();
	};
})(jQuery);

function initializeMobileNav(){

	$('#mobileNav').sidr({
		onOpen: function(){
			left = $('#sidr').width();
			$('.fixed-header').css('left', left);
			$('body').addClass('sidr-custom-open');
		},
		onClose: function(){
			$('.fixed-header').css('left', 0);
			$('body').addClass('closing');
		},
		onCloseEnd: function(){
			$('body').removeClass('closing');
			$('body').removeClass('sidr-custom-open');
		}
	});

	$('[data-toggle="jquery-accordion"]').accordion();
}
function cl(x) {
	console.log(x);
}

/* Google Map */

var mapObj = [];

function gmap() {
	
	var infowindow = [];
	var service = [];
	var marker = [];
	
	$("[data-toggle=\'gmap\']").each(function (index, value) {
		cmap = $(this);
		loadMapMarker(mapObj, index, cmap);
	});
	
	$("#accordion").on('shown.bs.collapse', function () {
		reintGmap();
	});
}

function loadMapMarker(mapObj, index, cmap) {
	
	var lat = cmap.data('lat');
	var lng = cmap.data('lng');
	var id = cmap.data('id');
	var store = cmap.data('store');
	var address = cmap.data('address');
	
	var place = { lat: lat, lng: lng };
	mapObj[index] = new google.maps.Map(
	document.getElementById(id), {
		zoom: 16,
		center: place
	});
	
	var contentString =
	'<b>' + store + '</b>' +
	'<p>' + address + '</p>';
	
	var infowindow = new google.maps.InfoWindow({
		content: contentString
	});
	
	var marker = new google.maps.Marker({
		position: place,
		map: mapObj[index],
		title: store
	});
	
	marker.addListener('click', function () {
		infowindow.open(mapObj[index], marker);
	});
	
}

function reintGmap() {
	var center = null;
	$.each(mapObj, function (index, value) {
		center = mapObj[index].getCenter();
		google.maps.event.trigger(mapObj[index], "resize");
		mapObj[index].setCenter(center);
	});
}



// Data-toggle: modal-content
$(document).delegate('[data-toggle="modal-content"]', 'click', function (e) {
	e.preventDefault();

	$('#modal-content-custom').remove();

	var element = this;

	let title = $(element).text();
	if ($(element).attr('data-title').length){
		title = $(element).attr('data-title');
	}
	
	$.ajax({
		url: $(element).attr('href'),
		type: 'get',
		dataType: 'html',
		success: function (data) {
			html = '<div id="modal-content-custom" class="modal">';
			html += '  <div class="modal-dialog">';
			html += '    <div class="modal-content">';
			html += '      <div class="modal-header">';
			html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
			html += '        <h4 class="modal-title">' + title + '</h4>';
			html += '      </div>';
			html += '      <div class="modal-body">' + data + '</div>';
			html += '    </div';
			html += '  </div>';
			html += '</div>';

			$('body').append(html);

			$('#modal-content-custom').modal('show');
		}
	});
});