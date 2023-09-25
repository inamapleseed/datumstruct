<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary save-changes"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title_version; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if (!empty($error_excelport_licensed_on)) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_excelport_licensed_on; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success_message) { ?>
    <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $success_message; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-body">
        <ul class="nav nav-tabs">
          <?php foreach ($tabs as $tab) { ?>
          <li><a class="excelport_tab" href="#tab-<?php echo $tab['id']; ?>" data-toggle="tab"><?php echo $tab['name']; ?></a></li>
          <?php } ?>
        </ul>
        
          <div class="tab-content">
          <?php foreach ($tabs as $tab) { ?>
            <div class="tab-pane" id="tab-<?php echo $tab['id']; ?>">
              <?php require_once $tab['file']; ?>
            </div>
          <?php } ?>
          </div>
        
      </div>
    </div>
  </div>
  </form>
</div>

<div id="progress-dialog" class="modal" data-backdrop="static" style="display: none;">
  <div class="modal-dialog">
    <div class="modal-content padding20">
      <div id="progressbar">
        <div class="progress">
          <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
          </div>
        </div>
      </div>
      <div id="progressinfo"></div>
      <button class="btn btn-default finishActionButton" style="display: none;">Abort</button>
    </div>
  </div>
</div>

<script type="text/javascript">

var jq = jQuery; // Intended for MijoShop

jq('li[id^="toolbar-popup"]').remove();

var xhr;
var loopXHR;
var pageTitle = jq('title').html();
var abort = false;
var sending = false;
var updateTimeout = null;
var loopXHR = null;
var site_url = null;
var lastMemory = 0;
var unidentifiedError = false;

var closeDialog = function() {
  jq("#progress-dialog").modal("hide");
  jq('#progressinfo').empty();
  jq('#progressbar .progress-bar').attr('aria-valuenow', '0');
  jq('#progressbar .progress-bar').css('width', '0%');
  jq('.finishActionButton').hide();
}

jq('.finishActionButton').click(function() {
  abort = true;
  loopXHR.abort();
  clearTimeout(updateTimeout);
  jq(this).html('<img src="./view/image/excelport/ajax-loader.gif" class="loadingImage2"/>');
  jq(this).attr('disabled', 'disabled');
  jq('#progressinfo').html('Aborting... Please wait...');
  jq('title').html(pageTitle);
  if (!sending) closeDialog();
});

jq(document).ajaxSend(function() {
  sending = true; 
});

jq(document).ajaxStop(function() {
  sending = false;
  if (abort) {
    closeDialog();
  }
});

var dependable = 'input[name="ExcelPort[Export][DataType]"], input[name="ExcelPort[Import][DataType]"]';

jq(dependable).each(function() {
  jq(this).change(function() {
    jq('*[data-depends-on]').each(function() {
      if (jq(jq(this).attr('data-depends-on')).is(':checked') || jq(jq(this).attr('data-depends-on')).is(':selected')) {
        jq(this).slideDown(100);
      } else {
        jq(this).hide();
      }
    });
  });
  jq(this).trigger('change');
});

jq( "#progress-dialog" ).modal({
  backdrop : 'static',
  show : false
});

switch (location.protocol) {
  case 'https:': 
    site_url = '<?php echo $https_server; ?>';
    break;
  default:
    site_url = '<?php echo $http_server; ?>';
    break;
}

if (document.location.href.indexOf('com_mijoshop') != -1) site_url += '/components/com_mijoshop/opencart';

if (document.location.href.indexOf('com_jcart') != -1) site_url += '/components/com_jcart';

if (document.location.href.indexOf('com_opencart') != -1) site_url += '/components/com_opencart';

var selected_tab = function() {
  var tab_id = localStorage.getItem('excelport_tab');

  if (!tab_id) {
    tab_id = jq('.excelport_tab').first().attr('href');
    localStorage.setItem('excelport_tab', tab_id);
  }

  return tab_id;
}

jq('.excelport_tab').click(function() {
  localStorage.setItem('excelport_tab', jq(this).attr('href'));
});

jq('.excelport_tab[href="' + selected_tab() + '"]').trigger('click');

jq(window).load(function() {

  var downloaded = false;
  var importing = false;
  var ajaxgenerate = <?php echo $ajaxgenerate; ?>;
  var ajaximport = <?php echo $ajaximport; ?>;
  var token = '';
  var vars = window.location.search.split('&');
  var progressText = ['', ''];
  var progressRegime = ajaxgenerate ? 'Export' : 'Import';
  
  var conditions = <?php echo json_encode($conditions); ?>;
  var operations = <?php echo json_encode($operations); ?>;
  var enabled_conditions = <?php echo json_encode(!empty($data['ExcelPort']['Export']['Filters']) ? $data['ExcelPort']['Export']['Filters'] : array()); ?>;
  
  var conditionsIndexes = {};
  for (var i in conditions) {
    conditionsIndexes[i] = 0;
  }

  if (jq('input[name="ExcelPort[' + progressRegime + '][DataType]"]:checked').val() == 'Products') {
    progressText = [
      '<?php echo $text_datatype_option_products; ?>',
      '<?php echo strtolower($text_datatype_option_products); ?>'
    ];  
  } else if (jq('input[name="ExcelPort[' + progressRegime + '][DataType]"]:checked').val() == 'Categories') {
    progressText = [
      '<?php echo $text_datatype_option_categories; ?>',
      '<?php echo strtolower($text_datatype_option_categories); ?>'
    ];    
  } else if (jq('input[name="ExcelPort[' + progressRegime + '][DataType]"]:checked').val() == 'Options') {
    progressText = [
      '<?php echo $text_datatype_option_options; ?>',
      '<?php echo strtolower($text_datatype_option_options); ?>',
    ];    
  } else if (jq('input[name="ExcelPort[' + progressRegime + '][DataType]"]:checked').val() == 'Attributes') {
    progressText = [
      '<?php echo $text_datatype_option_attributes; ?>',
      '<?php echo strtolower($text_datatype_option_attributes); ?>'
    ];    
  } else if (jq('input[name="ExcelPort[' + progressRegime + '][DataType]"]:checked').val() == 'Customers') {
    progressText = [
      '<?php echo $text_datatype_option_customers; ?>',
      '<?php echo strtolower($text_datatype_option_customers); ?>'
    ];    
  } else if (jq('input[name="ExcelPort[' + progressRegime + '][DataType]"]:checked').val() == 'CustomerGroups') {
    progressText = [
      '<?php echo $text_datatype_option_customer_groups; ?>',
      '<?php echo strtolower($text_datatype_option_customer_groups); ?>'
    ];    
  } else if (jq('input[name="ExcelPort[' + progressRegime + '][DataType]"]:checked').val() == 'Options') {
    progressText = [
            '<?php echo $text_datatype_option_options; ?>',
      '<?php echo strtolower($text_datatype_option_options); ?>'
        ];      
    } else if (jq('input[name="ExcelPort[' + progressRegime + '][DataType]"]:checked').val() == 'Manufacturers') {
    progressText = [
            '<?php echo $text_datatype_option_manufacturers; ?>',
      '<?php echo strtolower($text_datatype_option_manufacturers); ?>'
        ];      
    } else if (jq('input[name="ExcelPort[' + progressRegime + '][DataType]"]:checked').val() == 'Coupons') {
    progressText = [
            '<?php echo $text_datatype_option_coupons; ?>',
      '<?php echo strtolower($text_datatype_option_coupons); ?>'
        ];      
    } else if (jq('input[name="ExcelPort[' + progressRegime + '][DataType]"]:checked').val() == 'Vouchers') {
    progressText = [
            '<?php echo $text_datatype_option_vouchers; ?>',
      '<?php echo strtolower($text_datatype_option_vouchers); ?>'
        ];      
    }

  for (var i = 0; i < vars.length; i++) {
    var parts = vars[i].split('=');
    if (parts[0] == 'token') token = parts[1];  
  }
  var timer = null;
  var seconds;
  
  var zeroPad = function (num, places) {
    var zero = places - num.toString().length + 1;
    return Array(+(zero > 0 && zero)).join("0") + num;
  }
  
    var basename = function(path) {
        return path.replace(/^.*\/(.*?)$/g, '$1');
    }

    var setLastImport = function(text) {
        jq('#last_import').hide();

        if (typeof text == 'undefined') {
            text = jq('#last_import').attr('data-text');
        }

        text = basename(text);

        if (text != '') {
            var value = jq('#last_import').attr('data-template').replace('{FILE}', text);
            jq('#last_import').html(value);
            jq('#last_import_input').val(text);
            jq('#last_import').show();
        }
    }

    setLastImport();

  var progress = function(message, isError) {
    if (isError !== false) {
      jq('#progressbar .progress-bar').attr('aria-valuenow', message.percent);
      jq('#progressbar .progress-bar').css('width', message.percent + '%');
      if ((message.current === message.all && !importing && typeof(message.populateAll) == 'undefined') || message.finishedImport) {
        jq('.finishActionButton').html('Finish');
        jq('.finishActionButton').removeAttr('disabled');
        clearInterval(timer);
        clearTimeout(updateTimeout);
        loopXHR.abort();
        if (!downloaded) {
          jq('#progressinfo').html('<?php echo $text_file_downloading; ?>');

          setTimeout(function() {
            document.location.href = "index.php?token=" + token + "&route=extension/module/excelport/download";
          }, 2000);

          downloaded = true;
        }
        if (importing) {
          jq('#progressinfo').html('<?php echo $text_import_done; ?>'.replace('{COUNT}', message.current).replace('{TYPE}', progressText[1]));
          setLastImport(message.importingFile);
        }
      } else if (importing) {
        if (message.current > 0) {
          var pps = Math.round((message.current)/seconds);
          jq('#progressinfo').html('Importing. Please wait...<br />Reading from: ' + message.importingFile + '<br />' + progressText[0] + ' per second: ' + pps + "<br />Imported: " + message.current);
          setLastImport(message.importingFile);
        } else {
          jq('#progressbar .progress-bar').attr('aria-valuenow', '100');
          jq('#progressbar .progress-bar').css('width', '100%');
          jq('#progressinfo').html('<?php echo $text_preparing_data; ?>');  
        }
      } else {
        if (message.current > 0) {
          if (message.percent != 100) {
            var pps = message.current/seconds;
            var allSecondsRemaining = Math.round((message.all - message.current)/pps);
            var hoursRemaining =  zeroPad(Math.floor(allSecondsRemaining/3600), 2);
            var minutesRemaining = zeroPad(Math.floor((allSecondsRemaining%3600)/60), 2);
            var secondsRemaining = zeroPad(Math.floor((allSecondsRemaining%60)), 2);
            jq('#progressinfo').html("Progress: " + message.percent + "%<br />" + message.current + " " + progressText[1] + " were " + (importing ? "imported" : "exported") + "...<br />" + Math.ceil(pps) + " " + progressText[1] + " per second<br />" + "Estimated time left: " + hoursRemaining + ':' + minutesRemaining + ':' + secondsRemaining);
          } else {
            jq('#progressinfo').html('<?php echo $text_file_generating; ?>'); 
          }
        } else {
          jq('#progressinfo').html('<?php echo $text_preparing_data; ?>');    
        }
      }
    } else {
      jq('.finishActionButton').html('Finish');
      jq('.finishActionButton').removeAttr('disabled');
      jq('#progressbar .progress-bar').css('width', '0%');
      jq('#progressbar .progress-bar').attr('aria-valuenow', '0');
      jq('#progressinfo').html(message);
      clearInterval(timer);
      clearTimeout(updateTimeout);
    }
  }
  
  var countSeconds = function() {
    seconds++;
  }
  
  var updateProgressBar = function(site_root, countinueChecking, callback) {
    countinueChecking = typeof countinueChecking == 'undefined' ? true : countinueChecking;
    if (abort) return;
    loopXHR = jq.ajax({
      url: site_root+'/<?php echo IMODULE_TEMP_FOLDER; ?>/<?php echo $progress_name; ?>',
      type: 'GET',
      timeout: null,
      dataType: 'json',
      cache: false,
      success: function(returnData, textStatus, jqXHR) {
        if (jq( "#progress-dialog" ).is(':visible')) {
          if (returnData != null && returnData.error == false) {
            if (lastMemory == returnData.memory_get_usage && unidentifiedError) {
              var megabytes = Math.round(parseInt(returnData.memory_get_usage)/1048576);
              var errorMessage = 'Error: The server may be out of memory. Currently, the script is using ' + megabytes + ' MB';
              progress(errorMessage, false);
              return;
            } else {
              lastMemory = returnData.memory_get_usage;
            }
            progress(returnData, true);
            
            if (!importing) document.title = returnData.percent + '% ' + pageTitle;
            
            if ((returnData != null && returnData.current !== returnData.all && !importing) || (!returnData.finishedImport && importing)) {
              if (!countinueChecking) {
                return;
              }
              
              updateTimeout = setTimeout(function (){
                
                updateProgressBar(site_root);
              }, 1000);
            }
          } else {
            if (returnData != null) {
              progress(returnData.message, false);
              if (!countinueChecking || (returnData.current == returnData.all && !importing)) {
                return;
              }
              
              updateTimeout = setTimeout(function (){
                
                updateProgressBar(site_root);
              }, 1000); 
            } else {
              if (!countinueChecking) {
                return;
              }
              
              updateTimeout = setTimeout(function (){
                
                updateProgressBar(site_root);
              }, 1000);
            }
          }
        } else {
          
          clearTimeout(updateTimeout);
        }
      },
      error: function() {
        if (!countinueChecking) {
          
          return;
        }
        
        updateTimeout = setTimeout(function (){
          
          updateProgressBar(site_root);
        }, 1000);
      }
    });

    if (typeof callback != 'undefined') {
      callback();
    }
  }
  
  var startAjaxGenerate = function(path, data) {
    downloaded = false;
    importing = false;
    unidentifiedError = false;
    if (abort) return;
    if (!jq( "#progress-dialog" ).is(":visible")) {
      jq( "#progress-dialog" ).modal( "show" );
      jq('.loadingImage').show(); 
      jq('.finishActionButton').show();
    }
    if (timer == null) {
      seconds = 1;
      timer = setInterval(countSeconds, 1000);
    }
    
    xhr = jq.ajax({
      url: path,
      data: data,
      async: true,
      type: 'POST',
      timeout: null,
      dataType: 'json',
      cache: false,
      statusCode: {
        500: function(){
          progress('Server error 500 has occured.', false);
        }
      },
      success: function(successData) {
        if (successData == null) {
          unidentifiedError = true;
        } else {
          if (successData.current < successData.all && successData.done) {
            startAjaxGenerate(path, data);  
          }
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        clearTimeout(updateTimeout);
        error = true;
        
        if (textStatus == 'timeout') {
          progress('A server timeout has occured.', false);
        } else if (textStatus == 'error') {
          console.log('A server error has occured.'); 
        } else if (textStatus == 'parsererror') {
          progress(jqXHR.responseText.replace("<br />", ''), false);
        }
      }
    });
  }
  
  var startAjaxImport = function(path, data) {
    importing = true;
    downloaded = true;
    unidentifiedError = false;
    if (abort) return;
    if (!jq( "#progress-dialog" ).is(':visible')) {
      jq( "#progress-dialog" ).modal( "show" );
      jq('.loadingImage').show(); 
      jq('.finishActionButton').show();
    }
    if (timer == null) {
      seconds = 1;
      timer = setInterval(countSeconds, 1000);
    }
    
    xhr = jq.ajax({
      url: path,
      data: data,
      async: true,
      type: 'POST',
      timeout: null,
      dataType: 'json',
      cache: false,
      statusCode: {
        500: function(){
          progress('Server error 500 has occured.', false);
        }
      },
      success: function(successData) {
        if (successData == null) {
          unidentifiedError = true;
        } else {
          if (successData.error) {
            progress(successData.message, false);
          } else {
            if (successData.done && !successData.finishedImport) {
              startAjaxImport(path, data);  
            }
          }
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        clearTimeout(updateTimeout);
        error = true;
        
        if (textStatus == 'timeout') {
          progress('A server timeout has occured.', false);
        } else if (textStatus == 'error') {
          console.log('A server error has occured.'); 
        } else if (textStatus == 'parsererror') {
          progress(jqXHR.responseText.replace("<br />", ''), false);
        }
      }
    });
  }
  
  var triggerFiltersDisplay = function() {
    var checked = jq('input[name="ExcelPort[Export][DataType]"]:checked').val();
    
    if (jq('#toggle_filter').val() == '1') {
      jq('.dataTypeFilter').each(function(index, element) {
        if (jq(element).attr('data-type') == checked) jq(element).slideDown();
        else jq(element).slideUp();
      });
    } else {
      jq('.dataTypeFilter').slideUp();
    }
  }
  
  var getOperations = function(category, operation, index) {
    var html = '<select name="ExcelPort[Export][Filters][' + category + '][' + index + '][Condition]">';
    
    for (var i in operations) {
      html  += '<option value="' + i + '"' + ((typeof(operation) != 'undefined' && operation == i) ? ' selected="selected"' : '') + '>' + operations[i].html + '</option>';
    }
    
    html    += '</select>';
    
    return html;
  }
  
  var refreshOperation = function(field, type, predefine) {
    jq(field).find('option').attr('disabled', 'disabled');
    if (typeof(type) != 'undefined') {
      jq(field).closest('.hideable').show();
      
      if (type == 'text') jq(field).find('option[value^="text_"]').removeAttr('disabled');
      else if (type=='number') jq(field).find('option[value^="number_"]').removeAttr('disabled');
      else jq(field).find('option').removeAttr('disabled');
      
      if (predefine) {
        jq(field).find('option').removeAttr('selected');
        jq(field).find('option[disabled!="disabled"]:first').attr('selected', 'selected');
      }
    }

    if (jq(field).find('option[selected][disabled!="disabled"]').length == 0) {
        jq(field).find('option[disabled!="disabled"]:first').attr('selected', 'selected');
    }
  }
  
  var getFields = function(category, field_name, index) {
    var html = '<select name="ExcelPort[Export][Filters][' + category + '][' + index + '][Field]">';
    for (var i in conditions[category]) {
      html += '<option value="' + i + '"' + ((typeof(field_name) != 'undefined' && i == field_name) ? ' selected="selected"' : '') + '>' + conditions[category][i].label + '</option>';
    }
    html    += '</select>';
    
    return html;
  }
  
  var addCondition = function(category, field_name, operation, value) {
    if (typeof(category) != 'undefined') {
      html  = '<tr>';
      html += ' <td>' + getFields(category, field_name, conditionsIndexes[category]) + '';
      
      html += ' <span class="hideable">' + getOperations(category, operation, conditionsIndexes[category]) +'';
      html += ' <?php echo $text_the_value; ?> <input type="text"' + ((typeof(value) != 'undefined') ? ' value="' + value + '"' : '') + ' name="ExcelPort[Export][Filters][' + category + '][' + conditionsIndexes[category] + '][Value]" /></span></td>';
      html += ' <td class="right"><a class="btn btn-danger discardCondition"><i class="icon-trash icon-white"></i> <?php echo $button_discard_condition; ?></a></td>';
      html += '</tr>';
      
      jq('.dataTypeFilter[data-type="' + category + '"] table tbody').append(html);
      if (typeof(field_name) != 'undefined') {
        if (typeof(conditions[category][field_name]) != 'undefined') {
        refreshOperation('select[name="ExcelPort[Export][Filters][' + category + '][' + conditionsIndexes[category] + '][Condition]"]', conditions[category][field_name].type, false);
        }
      } else {
        for (var j in conditions[category]) { refreshOperation('select[name="ExcelPort[Export][' + category + '][' + conditionsIndexes[category] + '][Condition]"]', conditions[category][j].type, true); break; }
      }
      jq('select[name="ExcelPort[Export][Filters][' + category + '][' + conditionsIndexes[category] + '][Field]"]').change({index: conditionsIndexes[category]}, function(e, data) {
        refreshOperation('select[name="ExcelPort[Export][Filters][' + category + '][' + e.data.index + '][Condition]"]', conditions[category][jq(this).val()].type, false);
      }).trigger('change');
      
      jq('.discardCondition').unbind('click').click(function() {
        jq(this).closest('tr').remove();
      });
      
      conditionsIndexes[category]++;
    }
  }
  
  for (var i in enabled_conditions) {
    var added = false;
    for (var j in enabled_conditions[i]) {
      if (typeof(enabled_conditions[i][j].Field) != 'undefined') {
        addCondition(i, enabled_conditions[i][j].Field, enabled_conditions[i][j].Condition, enabled_conditions[i][j].Value);
        added = true;
      }
    }
    if (!added) addCondition(i);
  }
  
  if (ajaxgenerate) {
    jq('#generateLoading').show();

    var exportData = {
      ExcelPort : {
        Export : {
          DataType : jq('input[name="ExcelPort[Export][DataType]"]:checked').val(),
          Store : jq('input[name="ExcelPort[Export][Store]"]:checked').val(),
          Language : jq('input[name="ExcelPort[Export][Language]"]:checked').val(),
          ProductExportMode : jq('input[name="ExcelPort[Export][ProductExportMode]"]:checked').val(),
          Filter : jq('input[name="ExcelPort[Export][Filter]"]').val(),
          Filters : {}
        },
        LastImport : jq('#last_import_input').val(),
        Settings : {
          ExportLimit : jq('input[name="ExcelPort[Settings][ExportLimit]"]').val(),
          DescriptionEncoding : jq('select[name="ExcelPort[Settings][DescriptionEncoding]"]').val()
        }
      }
    };
    
    jq('*[name^="ExcelPort[Export][Filters]"]').each(function (index, element) {
      var regex = /ExcelPort\[Export\]\[Filters\]\[(.*?)\]\[(.*?)\]\[(.*?)\]/gi;
      match = regex.exec(jq(element).attr('name'));
      if (match == null) {
        regex = /ExcelPort\[Export\]\[Filters\]\[(.*?)\]\[(.*?)\]/gi;
        match = regex.exec(jq(element).attr('name'));
        if (typeof(exportData.ExcelPort.Export.Filters[match[1]]) == 'undefined') exportData.ExcelPort.Export.Filters[match[1]] = {};
        exportData.ExcelPort.Export.Filters[match[1]][match[2]] = jq(element).val();
      } else {
        if (typeof(exportData.ExcelPort.Export.Filters[match[1]]) == 'undefined') exportData.ExcelPort.Export.Filters[match[1]] = {};
        if (typeof(exportData.ExcelPort.Export.Filters[match[1]][match[2]]) == 'undefined') exportData.ExcelPort.Export.Filters[match[1]][match[2]] = {};
        exportData.ExcelPort.Export.Filters[match[1]][match[2]][match[3]] = jq(element).val();
      }
    });

    updateProgressBar(site_url, true, function() {
      startAjaxGenerate('index.php?token='+token+'&route=extension/module/excelport/ajaxgenerate&_=' + (new Date()).getTime(), exportData);
    });
  }
  
  if (ajaximport) {
    jq('#generateLoading').show();
    updateProgressBar(site_url);
    startAjaxImport('index.php?token='+token+'&route=extension/module/excelport/ajaximport&_=' + Date.now(), {
      ExcelPort : {
        Import : {
          DataType : jq('input[name="ExcelPort[Import][DataType]"]:checked').val(),
          Language : jq('input[name="ExcelPort[Import][Language]"]:checked').val(),
          Delete : jq('input[name="ExcelPort[Import][Delete]"]:checked').val(),
          AddAsNew : jq('input[name="ExcelPort[Import][AddAsNew]"]:checked').val()
        },
        LastImport : jq('#last_import_input').val(),
        Settings : {
          ImportLimit : jq('input[name="ExcelPort[Settings][ImportLimit]"]').val()
        }
      }
    });
  }

  jq('.needMoreSize').click(function() {
    window.open(site_url + '/vendors/excelport/help_increase_size.php', '_blank', 'location=no,width=830,height=580,resizable=no');
  });

  jq('.ExcelPortSubmitButton').click(function(e) {
    abort = false;
    var action = jq(this).attr('data-action');
    if (action == 'import' && jq('#checkboxDelete').is(':checked')) {
      if (!confirm('<?php echo $text_confirm_delete_other; ?>')) return;  
    }
    jq('#form').attr('action',jq('#form').attr('action').replace(/&submitAction=.*($|&)/g, ''));
    if (action != undefined && action != '') {
      jq('#form').attr('action',jq('#form').attr('action')+'&submitAction='+action);
    }
    jq('#form').submit();
  });

  jq('#filter_popover').popover({ trigger: 'hover' }).click(function() {
    jq(this).toggleClass('active');
    if (jq(this).hasClass('active')) {
      jq('#toggle_filter').val('1');
    } else {
      jq('#toggle_filter').val('0');
    }
    triggerFiltersDisplay();
  });
  
  jq('input[name="ExcelPort[Export][DataType]"]').change(triggerFiltersDisplay);
  triggerFiltersDisplay();
  
  jq('.addCondition').click(function() {
    addCondition(jq(this).closest('.dataTypeFilter').attr('data-type'));
  });
  
  jq('a[data-toggle="tooltip"]').tooltip({placement:'right'});

});

</script>

<?php echo $footer; ?>