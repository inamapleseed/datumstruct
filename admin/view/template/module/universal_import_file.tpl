<?php if ($import_source == 'upload') { ?>
  <div class="source_upload col-md-offset-2">
    <div id="dropzone" class="text-center">
      <span><i class="fa fa-mouse-pointer"></i> <?php echo $_language->get('text_dropzone'); ?></span>
    </div>
      <span class="btn btn-success fileinput-button" style="display:none;">
        <span><?php echo $_language->get('text_btn_upload'); ?></span>
        <input id="fileupload" type="file" name="files[]" />
        <input id="import_file" type="hidden" name="import_file" />
      </span>
    <div id="uploadProgress" class="progress" style="display:none">
      <div class="progress-bar progress-bar-striped progress-bar-success active"></div>
    </div>
    <div id="files" class="alert alert-success" style="display:none"></div>
    <div id="files_error" class="alert alert-danger" style="display:none"></div>
  </div>
  
<script type="text/javascript"><!--
$(function () {
  'use strict';
  
  $('#fileupload').fileupload({
    url: uploadUrl,
    maxChunkSize: 5000000, // 5 MB
    dropZone: $('#dropzone'),
    dataType: 'json',
    submit: function (e, data) {
      $('#uploadProgress').show();
      $('#files,#files_error').hide();
    },
    done: function (e, data) {
      $.each(data.result.files, function (index, file) {
        if (file.error) {
          $('#files_error').html('<i class="fa fa-exclamation-triangle"></i> <b><?php echo $_language->get('text_file_error'); ?></b> ' + file.error).fadeIn();
          $('#uploadProgress').hide();
          $('#importStep1 button.submit').attr('disabled', true);
        } else {
        $('#files').html('<?php echo $_language->get('text_file_loaded'); ?> <i class="fa fa-file"></i> <b>' + file.name + '</b>').fadeIn();
        $('#import_file').val(file.name);
          
          $('#uploadProgress').hide();
          $('#importStep1 button.submit').attr('disabled', false);
        }
        //$('<p/>').text(file.name).appendTo('#files');
      });
      //$('#importStep1').collapse('hide');
      //$('#importStep2').collapse('show');
    },
    progressall: function (e, data) {
      var progress = parseInt(data.loaded / data.total * 100, 10);
      $('#uploadProgress .progress-bar').css(
        'width',
        progress + '%'
      );
    }
  });
});

$('#dropzone').click(function(){
	$('#fileupload').click();
});
</script>
<?php } else if ($import_source == 'ftp') { ?>
  <div class="source_ftp">
    <div class="form-group">
      <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('source_ftp_i'); ?>"><?php echo $_language->get('source_ftp'); ?></span></label>
      <div class="col-sm-4">
        <input type="text" name="import_ftp" class="form-control" value="<?php if (isset($profile['import_ftp'])) echo $profile['import_ftp']; ?>" placeholder="ftp://username:password@ftp.example.com"/>
      </div>
      <label class="col-sm-2 control-label"><?php echo $_language->get('source_ftp_path'); ?></label>
      <div class="col-sm-4">
        <input type="text" name="import_file" class="form-control" value="<?php if (isset($profile['import_source']) && $profile['import_source'] == 'ftp' && isset($profile['import_file'])) echo $profile['import_file']; ?>" placeholder="/feed/feed.csv"/>
      </div>
    </div>
  </div>
<?php } else if ($import_source == 'url') { ?>
  <div class="source_url">
    <div class="form-group">
      <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('source_url_i'); ?>"><?php echo $_language->get('source_url'); ?></span></label>
      <div class="col-sm-10">
        <input type="text" name="import_file" class="form-control" value="<?php if (isset($profile['import_file']) && $profile['import_source'] == 'url' && isset($profile['import_file'])) echo $profile['import_file']; ?>" placeholder="http://example.com/import_feed.xml"/>
      </div>
    </div>
  </div>
<?php } else if ($import_source == 'path') { ?>
  <div class="source_path">
    <div class="form-group">
      <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $_language->get('source_path_i'); ?>"><?php echo $_language->get('source_path'); ?></span></label>
      <div class="col-sm-10">
        <input type="text" name="import_file" class="form-control" value="<?php if (isset($profile['import_source']) && $profile['import_source'] == 'path' && isset($profile['import_file'])) echo $profile['import_file']; ?>" placeholder="/var/feed/import_feed.xml"/><br>
        <?php echo $_language->get('current_server_path') . ' ' . str_replace('system/', '', DIR_SYSTEM); ?>
      </div>
    </div>
  </div>
<?php } ?>