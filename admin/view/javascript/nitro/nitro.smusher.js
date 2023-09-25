nitro.register('smusher', (function($) {
    var token = '';
    var method = 'local';
    var quality = 100;
    var flagPause = false;
    var logArea = null;
    var refreshXHR = null;
    var refreshTimeout = null;
    var smushXHR = null;
    var isLoadingImagesList = false;
    var isSmushWaiting = false;
    var isSmushStartEventFired = false;
    var smushFinishedCallbacks = [];
    var smushPausedCallbacks = [];
    var smushResumedCallbacks = [];
    var smushStartedCallbacks = [];
    var smushUpdateCallbacks = [];
    var smushErrorCallbacks = [];

    var getImageList = function(callback) {
        var smushTargetPath = "";
        if ($('#smushTargetPath').val().length > 0) {
            smushTargetPath = $('#smushTargetPath').val().replace(/\//g, '%2F');
        }

        $.ajax({
            url: 'index.php?route=tool/nitro/smush_init&token=' + token,
            dataType: 'json',
            type: 'POST',
            data: {
                targetDir: smushTargetPath
            },
            cache: false,
            success: function(data) {
                isLoadingImagesList = false;
                switch (data.status) {
                    case 'success':
                        if (callback) callback();
                        break;
                    case 'fail':
                        fireCallbacks(smushErrorCallbacks, data);
                        break;
                }
            }
        });
    };

    var fireCallbacks = function(callbacksList, data) {
        data = data || null;
        if (callbacksList.length) {
            var callback = null;
            for (x = 0; x < callbacksList.length; x++) {
                callback = callbacksList[x];
                callback(data);
            }
        }
    };

    var refresh_progress = function(auto_refresh) {
        if (typeof auto_refresh == 'undefined') {//this is because auto_refresh may be boolean false
            auto_refresh = true;
        }

        if (refreshXHR) {
            refreshXHR.abort();
            clearTimeout(refreshTimeout);
        }

        refreshXHR = $.ajax({
            url: 'index.php?route=tool/nitro/smush_get_progress&token=' + token,
            type: 'GET',
            dataType: 'json',
            cache: false,
            success: function(data) {
                fireCallbacks(smushUpdateCallbacks, data);
            },
            complete: function() {
                clearTimeout(refreshTimeout);

                if (auto_refresh) {
                  refreshTimeout = setTimeout(refresh_progress, 2000);
                }
            }
        });
    };

    var smush = function(action) {
        action = action || 'start';
        
        if (!isSmushStartEventFired) {
            isSmushStartEventFired = true;
            fireCallbacks(smushStartedCallbacks);
        }

        smushXHR = $.ajax({
            url: 'index.php?route=tool/nitro/smush_' + action + '&token=' + token,
            type: 'GET',
            dataType: 'text',
            data: {
                method: method,
                quality: quality
            },
            cache: false,
            beforeStart : function() {
                this.smushIsReady = 0;
            },
            success : function(data) {
                this.smushIsReady = parseInt(data);
            },
            complete: function(jqXHR) {
                if (this.smushIsReady == 0) {
                    smush('resume');
                } else {
                    if (refreshXHR) {
                        refreshXHR.abort();
                        clearTimeout(refreshTimeout);
                    }

                    refresh_progress(false);

                    fireCallbacks(smushFinishedCallbacks);
                }
            }
        });

        refresh_progress();
    };

    var stopSmushing = function() {
        refreshXHR.abort();
        clearTimeout(refreshTimeout);
        smushXHR.abort();

        $.ajax({
            url: 'index.php?route=tool/nitro/smush_pause&token=' + token,
            type: 'GET',
            dataType: 'json',
            cache: false,
            complete: function(data) {
                refresh_progress(false);

                fireCallbacks(smushPausedCallbacks);
            }
        });
    };

    var restoreState = function() {
        $.ajax({
            url: 'index.php?route=tool/nitro/smush_get_progress&token=' + token,
            type: 'GET',
            dataType: 'json',
            cache: false,
            success: function(data) {
                fireCallbacks(smushUpdateCallbacks, data);
            }
        });
    };

    return {
        init: function(logElement) {
            logArea = logElement;
            restoreState();
        },
        reset: function() {
            isSmushStartEventFired = false;
        },
        begin: function() {
            this.restart();
        },
        restart: function() {
            this.reset();
            getImageList(function() { smush('start'); });
            //smush('start');
        },
        resume: function() {
            this.reset();
            smush('resume');
        },
        pause: function() {
            stopSmushing();
        },
        setToken: function(t) {
            token = t;
        },
        setMethod: function(m) {
            method = m;
        },
        setQuality: function(q) {
            quality = q;
        },
        addSmushFinishEventListener: function(callback) {
            if (typeof callback === 'function') {
                smushFinishedCallbacks.push(callback);
            }
        },
        addSmushPauseEventListener: function(callback) {
            if (typeof callback === 'function') {
                smushPausedCallbacks.push(callback);
            }
        },
        addSmushResumeEventListener: function(callback) {
            if (typeof callback === 'function') {
                smushResumedCallbacks.push(callback);
            }
        },
        addSmushStartEventListener: function(callback) {
            if (typeof callback === 'function') {
                smushStartedCallbacks.push(callback);
            }
        },
        addSmushStartedEventListener: function(callback) {
            if (typeof callback === 'function') {
                smushStartedCallbacks.push(callback);
            }
        },
        addSmushUpdateEventListener: function(callback) {
            if (typeof callback === 'function') {
                smushUpdateCallbacks.push(callback);
            }
        },
        addErrorEventListener: function(callback) {
            if (typeof callback === 'function') {
                smushErrorCallbacks.push(callback);
            }
        }
    };
})(jQuery));
