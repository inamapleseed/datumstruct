nitro.register('pagespeed', (function($) {
    var token = '';
    var apiKey = '';
    var storeUrl = '';
    var saveUrl = '';
    var strategies = ["mobile", "desktop"];
    var refreshXHRs = [];
    var saveXHRs = [];
    var isInProgress = false;
    var isRefreshDone = false;

    var saveResult = function(result, strategy) {
      if (saveXHRs.length) {
        setTimeout(function() {
          saveResult(result, strategy);
        }, 200);
      } else {
        saveXHRs.push($.ajax({
          url: saveUrl,
          type: "POST",
          data: {
            data: result,
            strategy: strategy
          },
          complete: function(xhr) {
            var index = saveXHRs.indexOf(xhr);

            if (index > -1) {
              saveXHRs.splice(index, 1);
            }

            if (!saveXHRs.length && isRefreshDone) {
              $("#icon-refresh-pagespeed").removeClass("icon-spin");
              isInProgress = false;
            }
          },
          error: function(xhr, text) {
            alert("Error: Could not save the PageSpeed result to the server");
          }
        }));
      }
    }

    return {
        refresh: function() {
          if (isInProgress || !apiKey || !token) return;

          isInProgress = true;
          isRefreshDone = false;

          for (var x = 0; x < strategies.length; x++) {
            var strategy = strategies[x];
            var xhr = $.ajax({
              url: "https://www.googleapis.com/pagespeedonline/v1/runPagespeed?url=" + storeUrl + "&key=" + apiKey + "&strategy=" + strategy,
              type: "GET",
              dataType: "json",
              success: function(resp, status, xhr) {
                if (!resp.error) {
                  switch(xhr.strategy) {
                    case "desktop":
                      report_desktop = resp;
                      break;
                    case "mobile":
                      report_mobile = resp;
                      break;
                  }
                  saveResult(resp, xhr.strategy);
                } else {
                  alert(resp.error.message);
                }
              },
              complete: function(xhr) {
                var index = refreshXHRs.indexOf(xhr);

                if (index > -1) {
                  refreshXHRs.splice(index, 1);
                }

                if (!refreshXHRs.length) {
                  isRefreshDone = true;
                  drawReportStats();
                }

                if (isRefreshDone && !saveXHRs.length) {
                  $("#icon-refresh-pagespeed").removeClass("icon-spin");
                  isInProgress = false;
                }
              },
              error: function(xhr, text) {
                try{
                  var resp = JSON.parse(xhr.responseText);
                  if (resp.error) {
                    alert(resp.error.message);
                  } else {
                    alert(text);
                  }
                } catch (e) {
                  console.log(e);
                }
              }
            });

            xhr.strategy = strategy;
            refreshXHRs.push(xhr);
          }
        },
        setToken: function(t) {
            token = t;
        },
        setApiKey: function(key) {
            apiKey = key;
        },
        setStoreUrl: function(url) {
            storeUrl = url;
        },
        setSaveUrl: function(url) {
            saveUrl = url;
        }
    };
})(jQuery));
