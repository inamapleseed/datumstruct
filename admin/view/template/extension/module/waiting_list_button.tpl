<button type="button" data-toggle="modal" data-target="#waiting_modal" class="btn btn-warning" >
    <i class="fa fa-envelope-o"></i>
</button>

<button type="submit" class="btn btn-primary" form="form-<?= $form_placeholder_type; ?>" >
<i class="fa fa-save"></i>
</button>

<div id="waiting_modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
  
      <!-- Modal content-->
      <div class="modal-content">

        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title"><?= $text_mail_template; ?></h4>
        </div>

        <div class="modal-body">
            <button data-toggle="collapse" data-target="#cron_job_instruction" class="btn btn-block btn-primary" >Cron Job Configuration</button>

            <div id='cron_job_instruction' class="collapse">
                <div class="alert alert-info stick">
                    <?= $cron_job_settings; ?>
                </div>
            </div>

            <div class="input-group" style="margin-top: 10px;" >
                <span class="input-group-addon"><?= $entry_msg_title; ?></span>
                <input type="text" class="form-control" name="waiting_msg_title" value="<?= $waiting_msg_title; ?>" />
            </div>
            <div class="form-group">
                <label class="label-control text-left">
                    <span data-toggle="tooltip" title="<?= $entry_msg_description_help; ?>" ><?= $entry_msg_description; ?></span>
                </label>
                <textarea rows="6" class="col-xs-12 form-control float-none" name="waiting_msg_description"><?= $waiting_msg_description; ?></textarea>
            </div>
        </div>
        <div class="modal-footer">
          <button id="saveIt" type="button" class="btn btn-primary"><?= $button_save; ?></button>
        </div>
      </div>
  
    </div>
    <script>
        $('#saveIt').on('click', function(e){
            e.preventDefault();
            $.ajax({
                url:        'index.php?route=extension/module/waiting_list/saveMsg&token=<?= $token; ?>',
                data:       $('#waiting_modal input, #waiting_modal textarea'),
                dataType:   'json',
                type:       'post',
                beforeSend: function(){
                    $('#saveIt').prop('disabled',  true);
                },
                success: function(json){
                    $('#saveIt').prop('disabled',  false);
                    alert(json);
                }
            });
        });
    </script>
</div>