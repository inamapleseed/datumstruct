<table class="table">
    <tr>
        <td colspan="2">
            <h4><?php echo $text_scheduling_settings; ?></h4>   
        </td>
    </tr>
	<tr>
        <td class="col-xs-3">
            <h5 class="option_title"><?php echo $text_sched_tasks; ?></h5>
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_sched_tasks_help; ?></span>
        </td>
        <td class="col-xs-9">
            <div class="col-xs-4">
                <select id="ScheduleToggle" name="<?php echo $moduleName; ?>[ScheduleEnabled]" class="form-control">
                    <option value="yes" <?php echo (!empty($moduleData['ScheduleEnabled']) && $moduleData['ScheduleEnabled'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
                    <option value="no"  <?php echo (empty($moduleData['ScheduleEnabled']) || $moduleData['ScheduleEnabled']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
                </select>
            </div>
        </td>
    </tr>
</table>

<table class="table cronForm" id="mainSettings" style="border-top: 1px solid #ddd;
<? echo (!empty($moduleData['ScheduleEnabled']) && $moduleData['ScheduleEnabled'] == 'yes') ? '' : 'display:none;'; ?>;">
   <tr>
    <td class="col-xs-3">
    	<h5 class="option_title"><?php echo $text_test_cron; ?></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_test_cron_help; ?></span>
    </td>
    <td class="col-xs-9">
        <div class="col-md-10">
    	    <a id="TestCronAvailablity" class="btn btn-warning cronBtn"><i class="fa fa-certificate"></i> <?php echo $btn_text_cron; ?></a>
            <br /><br />
            <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $cron_no_support_helper; ?></span>
	    </div>
    </td>
  </tr>
  <tr>
    <td class="col-xs-3">
    	<h5 class="option_title"><?php echo $text_sched_type; ?></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_sched_type_help; ?></span>
    </td>
    <td class="col-xs-9">
    	<div class="col-md-4">
          <select name="<?php echo $moduleName; ?>[ScheduleType]" class="form-control">
            <option value="F" <?php if(!empty($moduleData['ScheduleType']) && $moduleData['ScheduleType'] == 'F') echo "selected" ?>><?php echo $select_fixed_dates; ?></option>
            <option value="P" <?php if(!empty($moduleData['ScheduleType']) && $moduleData['ScheduleType'] == 'P') echo "selected" ?>><?php echo $select_periodic_date; ?></option>
          </select>
		</div>
	</td>
  </tr>
  <tr>
    <td class="col-xs-3">
    	<h5 class="option_title"><?php echo $text_schedule; ?></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_schedule_help; ?></span>
    </td>
    <td class="col-xs-9">
	  <div class="col-md-5">
            <div id="FixedDateOptions" class="row">
                <div class="col-md-5">
                    <div class="input-group">
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                       <input type="text" id="FixedDate" class="form-control" data-date-format="DD.MM.YYYY" value="" placeholder="<?php echo $text_date; ?>" readonly />
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="input-group">
                       <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                    <input type="text" id="FixedDateTime" class="timepicker form-control" data-date-format="HH:mm" placeholder="<?php echo $text_time; ?>" readonly />
                    </div>
                </div>
                <div class="col-md-2">
                    <button class="btn addDate"><i class="fa fa-plus"></i> <?php echo $btn_add; ?></button>
                </div>
            <div style="height:10px;clear:both;"></div>
            <div class="scrollbox dateList">
                <?php if(isset($moduleData['FixedDates'])) { 
                        foreach($moduleData['FixedDates'] as $date) {?>
                            <div id="date<?php  $id = explode( '/', $date); $id=explode('.' , $id[0]); echo $id[0].$id[1].$id[2]; ?>"><?php echo $date ?> 
                                <i class="fa fa-minus-circle removeIcon"></i>
                                <input type="hidden" name="<?php echo $moduleName; ?>[FixedDates][]" value="<?php echo $date ?>" />
                            </div>
                        <?php } ?>
                 <?php } ?> 
             </div>
          </div>
          <div id="PeriodicOptions">
            <div id="CronSelector"></div>
            <input type="hidden" id="abCron" name="<?php echo $moduleName; ?>[PeriodicCronValue]" value="">
          </div>
      </div>
    </td>
  </tr>
  <tr>
    <td class="col-xs-3">
        <h5 class="option_title"><?php echo $text_sched_notify; ?></h5>
        <span class="help"><i class="fa fa-info-circle"></i>&nbsp;<?php echo $text_sched_notify_help; ?></span>
    </td>
    <td class="col-xs-9">
        <div class="col-xs-4">
            <select name="<?php echo $moduleName; ?>[NotifyEmail]" class="form-control">
                <option value="yes" <?php echo (!empty($moduleData['NotifyEmail']) && $moduleData['NotifyEmail'] == 'yes') ? 'selected=selected' : '' ?>><?php echo $text_enabled; ?></option>
                <option value="no"  <?php echo (empty($moduleData['NotifyEmail']) || $moduleData['NotifyEmail']== 'no') ? 'selected=selected' : '' ?>><?php echo $text_disabled; ?></option>
            </select>
        </div>
    </td>
   </tr>
 </table>
 
 <h4><?php echo $text_cron_faq; ?></h4>
 <hr />
 <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
          <?php echo $heading_what_is_cron; ?>
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
      <div class="panel-body">
         <?php echo $cron_what_is_cron; ?>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
          <?php echo $heading_how_to_setup_cron; ?>
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
      <div class="panel-body">
        <?php echo $cron_setup_text; ?>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
          <?php echo $heading_cron_not_appearing; ?>
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
        <?php echo $cron_does_not_support; ?><br />
      	<br />
        <strong><?php echo $cronPhpPath; ?></strong>
        <br /><br />
		<?php echo $cron_does_not_support_2; ?>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingFour">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
          <?php echo $heading_cron_no_support; ?>
        </a>
      </h4>
    </div>
    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
      <div class="panel-body">
        <p>
            <?php echo $cron_support_external; ?>
            <ul>
                <li>- <a href="<?php echo HTTP_CATALOG; ?>index.php?route=<?php echo $modulePath; ?>/sendReminder"><?php echo HTTP_CATALOG; ?>index.php?route=<?php echo $modulePath; ?>/sendReminder</a></li>
            </ul>
            <?php echo $cron_support_external_2; ?>
        </p>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingFive">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
          <?php echo $heading_cron_more_than_once; ?>
        </a>
      </h4>
    </div>
    <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
      <div class="panel-body">
        <?php echo $cron_more_than_once; ?>
      </div>
    </div>
  </div>
</div>
  
<!-- CronModal -->
<div class="modal fade" id="cronModal" tabindex="-1" role="dialog" aria-labelledby="cronModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo $text_close_text; ?></span></button>
        <h4 class="modal-title" id="cronModalLabel"><?php echo $heading_cron_modal; ?></h4>
      </div>
      <div class="modal-body" id="cronModalBody">
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $text_close_text; ?></button>
      </div>
    </div>
  </div>
</div>