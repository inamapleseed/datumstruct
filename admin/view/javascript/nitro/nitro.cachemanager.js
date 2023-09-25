nitro.register('cachemanager', (function($) {
  var token = '';
  var job_queue = [];
  var time_start = null;
  var interval = null;

  var runNextJob = function() {
    if (!time_start) {
      time_start = Date.now();
      $('.first-level-spinner').attr('class', 'icon-spinner icon-spin');
      interval = setInterval(function(){
        if ((Date.now() - time_start) > 3000) {
          clearInterval(interval);
          showProgressModal();
        }
      }, 500);
    }

    var next_job = job_queue.shift();
    if (next_job) next_job();
    else location.reload();
  }

  var showProgressModal = function() {
    $('#progressModal').modal({
      backdrop: 'static',
      show: true
    });
  }

  var queue_job = function(job, id, descr) {
    job_queue.push(job);
    $('#progressModal ul.progress-list').append('<li class="'+id+'">'+descr+'</li>');
  }

  var clearImageCache = function() {
    if (!$('ul.progress-list li.imagecache i').length) {
      $('ul.progress-list li.imagecache').append('<i class="icon-spinner icon-spin"></i>');
    }

    $.ajax({
      url: 'index.php?route=tool/nitro/clearimagecacheajax&token=' + token,
      dataType: 'json',
      success: function(resp) {
        if (resp.done) {
          $('ul.progress-list li.imagecache i').attr('class', 'icon-ok-sign');
          runNextJob();
        } else {
          clearImageCache();
        }
      }
    });
  }

  var clearPageCache = function() {
    if (!$('ul.progress-list li.pagecache i').length) {
      $('ul.progress-list li.pagecache').append('<i class="icon-spinner icon-spin"></i>');
    }

    $.ajax({
      url: 'index.php?route=tool/nitro/clearpagecacheajax&token=' + token,
      dataType: 'json',
      success: function(resp) {
        if (resp.done) {
          $('ul.progress-list li.pagecache i').attr('class', 'icon-ok-sign');
          runNextJob();
        } else {
          clearPageCache();
        }
      }
    });
  }

  var clearDBCache = function() {
    if (!$('ul.progress-list li.dbcache i').length) {
      $('ul.progress-list li.dbcache').append('<i class="icon-spinner icon-spin"></i>');
    }

    $.ajax({
      url: 'index.php?route=tool/nitro/cleardbcacheajax&token=' + token,
      dataType: 'json',
      success: function(resp) {
        if (resp.done) {
          $('ul.progress-list li.dbcache i').attr('class', 'icon-ok-sign');
          runNextJob();
        } else {
          clearDBCache();
        }
      }
    });
  }

  var clearCSSCache = function() {
    if (!$('ul.progress-list li.csscache i').length) {
      $('ul.progress-list li.csscache').append('<i class="icon-spinner icon-spin"></i>');
    }

    $.ajax({
      url: 'index.php?route=tool/nitro/clearcsscacheajax&token=' + token,
      dataType: 'json',
      success: function(resp) {
        if (resp.done) {
          $('ul.progress-list li.csscache i').attr('class', 'icon-ok-sign');
          runNextJob();
        } else {
          clearCSSCache();
        }
      }
    });
  }

  var clearJSCache = function() {
    if (!$('ul.progress-list li.jscache i').length) {
      $('ul.progress-list li.jscache').append('<i class="icon-spinner icon-spin"></i>');
    }

    $.ajax({
      url: 'index.php?route=tool/nitro/clearjscacheajax&token=' + token,
      dataType: 'json',
      success: function(resp) {
        if (resp.done) {
          $('ul.progress-list li.jscache i').attr('class', 'icon-ok-sign');
          runNextJob();
        } else {
          clearJSCache();
        }
      }
    });
  }

  var clearSystemCache = function() {
    if (!$('ul.progress-list li.systemcache i').length) {
      $('ul.progress-list li.systemcache').append('<i class="icon-spinner icon-spin"></i>');
    }

    $.ajax({
      url: 'index.php?route=tool/nitro/clearsystemcacheajax&token=' + token,
      dataType: 'json',
      success: function(resp) {
        if (resp.done) {
          $('ul.progress-list li.systemcache i').attr('class', 'icon-ok-sign');
          runNextJob();
        } else {
          clearSystemCache();
        }
      }
    });
  }

  var clearVqmodCache = function() {
    if (!$('ul.progress-list li.vqmodcache i').length) {
      $('ul.progress-list li.vqmodcache').append('<i class="icon-spinner icon-spin"></i>');
    }

    $.ajax({
      url: 'index.php?route=tool/nitro/clearvqmodcacheajax&token=' + token,
      dataType: 'json',
      success: function(resp) {
        if (resp.done) {
          $('ul.progress-list li.vqmodcache i').attr('class', 'icon-ok-sign');
          runNextJob();
        } else {
          clearVqmodCache();
        }
      }
    });
  }

  return {
    setToken: function(t) {
      token = t;
    },
    clearPageCache: function() {
      queue_job(clearPageCache, 'pagecache', 'Clearing page cache...');
      runNextJob();
    },
    clearDBCache: function() {
      queue_job(clearDBCache, 'dbcache', 'Clearing database cache...');
      runNextJob();
    },
    clearImageCache: function() {
      queue_job(clearImageCache, 'imagecache', 'Clearing image cache...');
      runNextJob();
    },
    clearCSSCache: function() {
      queue_job(clearCSSCache, 'csscache', 'Clearing CSS cache...');
      runNextJob();
    },
    clearJSCache: function() {
      queue_job(clearJSCache, 'jscache', 'Clearing JS cache...');
      runNextJob();
    },
    clearSystemCache: function() {
      queue_job(clearSystemCache, 'systemcache', 'Clearing system cache...');
      runNextJob();
    },
    clearVqmodCache: function() {
      queue_job(clearVqmodCache, 'vqmodcache', 'Clearing vQmod cache...');
      runNextJob();
    },
    clearNitroCaches: function() {
      queue_job(clearPageCache, 'pagecache', 'Clearing page cache...');
      queue_job(clearDBCache, 'dbcache', 'Clearing database cache...');
      queue_job(clearCSSCache, 'csscache', 'Clearing CSS cache...');
      queue_job(clearJSCache, 'jscache', 'Clearing JS cache...');
      runNextJob();
    },
    clearAllCaches: function() {
      queue_job(clearPageCache, 'pagecache', 'Clearing page cache...');
      queue_job(clearDBCache, 'dbcache', 'Clearing database cache...');
      queue_job(clearImageCache, 'imagecache', 'Clearing image cache...');
      queue_job(clearCSSCache, 'csscache', 'Clearing CSS cache...');
      queue_job(clearJSCache, 'jscache', 'Clearing JS cache...');
      queue_job(clearSystemCache, 'systemcache', 'Clearing system cache...');
      queue_job(clearVqmodCache, 'vqmodcache', 'Clearing vQmod cache...');
      runNextJob();
    }
  }
})(jQuery));
