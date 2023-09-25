<div class="timeslot_container">
    <h4><?= $heading_title; ?></h4>
    <div class="timeslot_type_container">
        <label id="express_btn" >
            <input id="timeslot_type" type="checkbox" name="timeslot_type" <?= $selected_type=='express'?'checked':''; ?> />
            <i class="fa fa-info-circle pull-right pointer" aria-hidden="true" data-toggle="tooltip" title="<?= $text_tool_tip; ?>" ></i>
            <span><?= $text_express_delivery; ?></span>
        </label>
    </div>
    <div class="timeslot_date_container">
        <input readonly name="selected_date" id="selected_date" type="text" class="pointer" value="<?= $selected_date; ?>" />
    </div>
    <div class="timeslot_time_container">
        <select name="selected_timeslot" id="selected_timeslot" >
            <?php foreach($timeslots as $slot){ ?>
                <option value="<?= $slot['timeslot_id']; ?>" ><?= $slot['display_text']; ?></option>
            <?php } ?>
        </select>
    </div>
<script type="text/javascript">
    var xhr = null;
    $(document).ready(function() {

         $('#selected_date').datetimepicker({
            format: 'YYYY-MM-DD',
            minDate: '<?php echo $date_min; ?>',
            maxDate: '<?php echo $date_max; ?>',
            //disabledDates: [],
            ignoreReadonly: true,
            //daysOfWeekDisabled: []
        });

        $('#express_btn').click(function(e){
            
            $(".timeslot_container").addClass('overlay');
            
            if(xhr) xhr.abort();

            $('#selected_timeslot').html('');
            
            setTimeout(function(){
                var selected_type = 'standard';
                
                if($('#timeslot_type:checked').length) selected_type = "express";

                xhr = $.ajax({
                    url: 'index.php?route=extension/timeslot/selectedType',
                    data: 'type=' + selected_type,
                    dataType: 'JSON',
                    type: 'POST',
                    success: function(json){
                        $(".timeslot_container").removeClass('overlay');
                        if(json){
                            $('#selected_date').val(json['selected_date']);

                            $('#selected_date').data('DateTimePicker').minDate(json['selected_date']);
                            $('#selected_date').data('DateTimePicker').maxDate(json['date_max']);

                            if(json['timeslots']){ 
                                var options = "";
                                for(i=0; i < json['timeslots'].length; i++){
                                    slot = json['timeslots'][i];
                                    options += '<option value="' + slot['timeslot_id'] + '" >';
                                    options += slot['display_text'];
                                    options += '</option>';
                                }
                                $('#selected_timeslot').html(options);
                            }

                        }
                    }
                });

            }, 300);
        });

    });

</script>
<script type="text/javascript">
    $('#selected_date').change(function(){
        var selected_date = this.value;
        $.ajax({
            url: 'index.php?route=extension/timeslot/selectedDate',
            data: 'selected_date=' + selected_date,
            dataType: 'JSON',
            type: 'POST',
            success: function(json){
                if(json['new_date']){
                    $('#selected_date').val(json['new_date']);
                    alert(json['message']);
                }
            }
        });
    });
</script>
<script type="text/javascript">
    $('#selected_timeslot').change(function(){
        var selected_timeslot = this.value;
        $.ajax({
            url: 'index.php?route=extension/timeslot/selectedSlot',
            data: 'selected_timeslot=' + selected_timeslot,
            dataType: 'JSON',
            type: 'POST',
            success: function(json){
                if(json['new_date']){
                    $('#selected_date').change();
                }
            }
        });
    }).change();
</script>
<script type="text/javascript">
   var general_error = '<?= $general_error; ?>';
   function validateTimeslot(){
       var selected_date = $('#selected_date').val();
       var selected_timeslot = $('#selected_timeslot').val();
       if(
           selected_date && selected_timeslot
       ){
           $.ajax({
               url: 'index.php?route=extension/timeslot/validateTimeslot',
               data: 'selected_date=' + selected_date + '&selected_timeslot=' + selected_timeslot,
               dataType: 'JSON',
               type: 'POST',
                success: function(json){
                    if(!json['status']){
                        $('#warning-messages').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"> ' + json['message'] + '</div>');
                        $('html, body').animate({ scrollTop: 0 }, 'slow');

                        $('#button-payment-method').prop('disabled', false);
                        $('#button-payment-method').button('reset');
                        $('.fa-spinner').remove();
                    }
                    
                    if(json['status'] && typeof validateShippingMethod == 'function'){
                        validatePaymentMethod();
                    }
                }
           });
       }
       else{
            $('#warning-messages').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"> ' + general_error + '</div>');
            $('html, body').animate({ scrollTop: 0 }, 'slow');

            $('#button-payment-method').prop('disabled', false);
            $('#button-payment-method').button('reset');
            $('.fa-spinner').remove();
       }
   }
</script>
</div>