nitro.register('css_extract', (function($){
  var extract_stack;
  var extracting;
  var extracted;
  var extract_xhr;
  var extract_total;
  var config;

  var init = function(callback) {
    extracting = false;
    extracted = 0;
    extracted_total = 0;
    message('');
    if (extract_xhr) {
      extract_xhr.abort();
    }
    callback();
  };

  var set_progress = function (percent) {
    $(config.progressbar_selector).css('width', percent + '%');
  }

  var message = function(message) {
    $(config.output_selector).html(message);
  }

  var extract_css = function() {
    if (extract_stack.length == 0) {
      init(function() {
        set_progress(100);
        message('Task completed.');
      });
    } else if (extracting) {
      var page = extract_stack.shift();

      extract_xhr = $.ajax({
        url : config.extract_url,
        type: 'POST',
        data: {
          page: page
        },
        beforeSend : function(jqXHR) {
          set_progress(Math.ceil(extracted * 100 / extract_total));
          message('Processing page type: ' + page.name + "<br />" + 'Completed: ' + extracted + '/' + extract_total);
        },
        success: function() {
          config.clear_cache_button.show();
        },
        complete : function() {
          extracted++;
          extract_css();
        },
        error: function(xhr, status, msg) {
          alert(status + ": " + msg);
        }
      });
    }
  }

  var load_stack = function() {
    $.ajax({
      url : config.stack_url,
      dataType : 'json',
      success : function(data) {
        extracting = true;
        extract_total = data.length;
        extract_stack = data;
        extract_css();
      }
    });
  }

  var clear_cache = function(callback) {
    callback = callback||false;
    $.ajax({
      url: config.delete_url,
      success: function() {
        config.clear_cache_button.hide();
        if (callback) {
          callback();
        }
      },
      error: function() {
        alert("Could not clear the base CSS cache. Aborting...");
      }
    });
  }
  
  var start = function() {
    clear_cache(function() {
      init(function() {
        set_progress(0);
        load_stack();
      });
    });
  }
  
  var abort = function() {
    if (!extracting) return;

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
    },
    clear_cache: function() {
      clear_cache();
    }
  }
})(jQuery));
