nitro.register('preminify', (function($){
  var preminify_stack;
  var preminifying;
  var preminified;
  var preminify_xhr;
  var preminify_total;
  var config;

  var init = function(callback) {
    preminifying = false;
    preminify_stack = [];
    preminified = 0;
    preminify_total = 0;
    message('');
    if (preminify_xhr) {
      preminify_xhr.abort();
    }
    callback();
  };

  var set_progress = function (percent) {
    $(config.progressbar_selector).css('width', percent + '%');
  }

  var message = function(message) {
    $(config.output_selector).html(message);
  }

  var preminify_file = function() {
    if (preminify_stack.length == 0) {
      init(function() {
        set_progress(100);
        message('Task completed.');
      });
    } else if (preminifying) {
      var resource = preminify_stack.shift();

      preminify_xhr = $.ajax({
        url : config.minify_url,
		type: 'POST',
		data: {
			file: resource
		},
        beforeSend : function(jqXHR) {
          jqXHR.setRequestHeader(config.http_header, '1');
          set_progress(Math.ceil(preminified * 100 / preminify_total));
          message('Processing item: ' + resource + "<br />" + 'Completed: ' + preminified + '/' + preminify_total);
        },
        complete : function() {
          preminified++;
          preminify_file();
        }
      });
    }
  }

  var load_stack = function() {
    $.ajax({
      url : config.stack_url,
      dataType : 'json',
      success : function(data) {
        preminifying = true;
        preminify_total = data.length;
        preminify_stack = data;
      },
      complete : preminify_file
    });
  }
  
  var start = function() {
	init(function() {
	  set_progress(0);
	  load_stack();
	});
  }
  
  var abort = function() {
	if (!preminifying) return;

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