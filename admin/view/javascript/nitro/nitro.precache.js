nitro.register('precache', (function($){
  var precache_stack;
  var precaching;
  var precached;
  var precache_xhr;
  var precache_total;
  var config;

  var init = function(callback) {
    precaching = false;
    precache_stack = [];
    precached = 0;
    precache_total = 0;
    message('');
    if (precache_xhr) {
      precache_xhr.abort();
    }
    callback();
  };

  var set_progress = function (percent) {
    $(config.progressbar_selector).css('width', percent + '%');
  }

  var message = function(message) {
    $(config.output_selector).html(message);
  }

  var precache_page = function() {
    if (precache_stack.length == 0) {
      init(function() {
        set_progress(100);
        message('Task completed.');
      });
    } else if (precaching) {
      var page = precache_stack.shift();

      precache_xhr = $.ajax({
        url : page,
        beforeSend : function(jqXHR) {
          jqXHR.setRequestHeader(config.http_header, '1');
          set_progress(Math.ceil(precached * 100 / precache_total));
          message('Processing item: ' + page + "<br />" + 'Completed: ' + precached + '/' + precache_total);
        },
        complete : function() {
          precached++;
          precache_page();
        }
      });
    }
  }

  var load_stack = function() {
    $.ajax({
      url : config.stack_url,
      dataType : 'json',
      success : function(data) {
        precaching = true;
        precache_total = data.length;
        precache_stack = data;
      },
      complete : precache_page
    });
  }
  
  var start = function() {
	init(function() {
	  set_progress(0);
	  load_stack();
	});
  }
  
  var abort = function() {
	if (!precaching) return;

	init(function() {
	  set_progress(0);
	  message('Task aborted.');
	});
  }
  
  return {
    setConfig: function(c) {
	  config = c;
	},
	start: function() {
	  start();
	},
	abort: function() {
	  abort();
	}
  }
})(jQuery));