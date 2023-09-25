<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-customer-group" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-customer-group" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-reward" data-toggle="tab"><?php echo $tab_reward; ?></a></li>
            <li><a href="#tab-discount" data-toggle="tab" class="hidden"><?php echo $tab_discount; ?></a></li>
          </ul>
          <div class="tab-content">
            
            <div class="tab-pane active" id="tab-general">
              <div class="form-group required">
                <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
                <div class="col-sm-10">
                  <?php foreach ($languages as $language) { ?>
                  <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                    <input type="text" name="customer_group_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($customer_group_description[$language['language_id']]) ? $customer_group_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
                  </div>
                  <?php if (isset($error_name[$language['language_id']])) { ?>
                  <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                  <?php } ?>
                  <?php } ?>
                </div>
              </div>
              <?php foreach ($languages as $language) { ?>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                <div class="col-sm-10">
                  <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                    <textarea name="customer_group_description[<?php echo $language['language_id']; ?>][description]" rows="5" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($customer_group_description[$language['language_id']]) ? $customer_group_description[$language['language_id']]['description'] : ''; ?></textarea>
                  </div>
                </div>
              </div>
              <?php } ?>

              <div class="form-group">
                <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_approval; ?>"><?php echo $entry_approval; ?></span></label>
                <div class="col-sm-10">
                  <label class="radio-inline">
                    <?php if ($approval) { ?>
                    <input type="radio" name="approval" value="1" checked="checked" />
                    <?php echo $text_yes; ?>
                    <?php } else { ?>
                    <input type="radio" name="approval" value="1" />
                    <?php echo $text_yes; ?>
                    <?php } ?>
                  </label>
                  <label class="radio-inline">
                    <?php if (!$approval) { ?>
                    <input type="radio" name="approval" value="0" checked="checked" />
                    <?php echo $text_no; ?>
                    <?php } else { ?>
                    <input type="radio" name="approval" value="0" />
                    <?php echo $text_no; ?>
                    <?php } ?>
                  </label>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
                </div>
              </div>

            </div>
            <div class="tab-pane" id="tab-reward">

              <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-earn" ><?php echo $entry_earn; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="reward_point_earn_rate" value="<?php echo $reward_point_earn_rate; ?>" placeholder="<?php echo $entry_earn; ?>" id="input-earn" class="form-control" />
                  </div>
              </div>

              <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-spend" ><?php echo $entry_spend; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="reward_point_step_spend" value="<?php echo $reward_point_step_spend; ?>" placeholder="<?php echo $entry_spend; ?>" id="input-spend" class="form-control" />
                  </div>
              </div>

              <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-amount" ><?php echo $entry_amount; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="reward_point_spend_rate" value="<?php echo $reward_point_spend_rate; ?>" placeholder="<?php echo $entry_amount; ?>" id="input-amount" class="form-control" />
                  </div>
              </div>

              <hr>

              <div class="table-responsive">
                <p style="color:red"><?= $text_not_same_dates ?></p>
								<table id="reward-dates" class="table table-striped table-bordered table-hover">
									<thead>
										<tr>
											<td class="text-left"><?= $entry_start_date; ?></td>
											<td class="text-left"><?= $entry_end_date; ?></td>
											<td class="text-left"><?= $entry_clear_date; ?></td>
											<td></td>
										</tr>
									</thead>
									<tbody>
										<?php $dates_row = 0; ?>
										<?php foreach ($reward_dates as $reward_date) { ?>
											<tr id="reward-dates-row<?= $dates_row; ?>">
												<td class="text-right">
                          <div class="input-group date">
                            <input type="text" name="reward_dates[<?= $dates_row; ?>][start_date]" value="<?= $reward_date['start_date']; ?>" placeholder="<?= $entry_start_date; ?>" data-date-format="YYYY-MM-DD" id="input-date-start<?= $dates_row; ?>" class="form-control" />
                            <span class="input-group-btn">
                              <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                          </div>
                        </td>
												<td class="text-right">
                          <div class="input-group date">
                            <input type="text" name="reward_dates[<?= $dates_row; ?>][end_date]" value="<?= $reward_date['end_date']; ?>" placeholder="<?= $entry_end_date; ?>" data-date-format="YYYY-MM-DD" id="input-date-end<?= $dates_row; ?>" class="form-control" />
                            <span class="input-group-btn">
                              <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                          </div>
                        </td>
												<td class="text-right">
                          <div class="input-group date">
                            <input type="text" name="reward_dates[<?= $dates_row; ?>][clear_date]" value="<?= $reward_date['clear_date']; ?>" placeholder="<?= $entry_clear_date; ?>" data-date-format="YYYY-MM-DD" id="input-date-clear<?= $dates_row; ?>" class="form-control" />
                            <span class="input-group-btn">
                              <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                            </span>
                          </div>
                        </td>
												<td class="text-left"><button type="button" onclick="$('#reward-dates-row<?= $dates_row; ?>').remove();" data-toggle="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
											</tr>
											<?php $dates_row++; ?>
										<?php } ?>
									</tbody>
									<tfoot>
										<tr>
											<td colspan="3"></td>
											<td class="text-left"><button type="button" onclick="addDates();" data-toggle="tooltip" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
										</tr>
									</tfoot>
								</table>
							</div>

              <?php /* ?>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-start"><?= $entry_start_date; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="start_date" value="<?= $start_date; ?>" placeholder="<?= $entry_start_date; ?>" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-end"><?= $entry_end_date; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="end_date" value="<?= $end_date; ?>" placeholder="<?= $entry_end_date; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control" />
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>

              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-date-clear"><?= $entry_clear_date; ?></label>
                <div class="col-sm-3">
                  <div class="input-group date">
                    <input type="text" name="clear_date" value="<?= $clear_date; ?>" placeholder="<?= $entry_clear_date; ?>" data-date-format="YYYY-MM-DD" id="input-date-clear" class="form-control" />
                    <span class="input-group-btn">
                      <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                    </span></div>
                </div>
              </div>
              <?php */ ?>

              <div class="form-group <?=$is_dev?>">
                <label class="col-sm-2 control-label"><div style="color:red;"><?php echo $entry_important; ?> *</div></label>
                <div class="col-sm-10">
                  <div style="color:red;">
                    <p><?php echo $text_cron_note; ?></p>
                  </div>
                </div>
              </div>

            </div>

          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    $('.date').datetimepicker({
      pickTime: false
    });
</script>
<script type="text/javascript"><!--
		var dates_row = <?= $dates_row; ?>;
		
		function addDates() {
			html  = '<tr id="reward-dates-row' + dates_row + '">';
      html += '                 <td class="text-right">';
      html += '                   <div class="input-group date">';
      html += '                      <input type="text" name="reward_dates[' + dates_row + '][start_date]" value="" placeholder="<?= $entry_start_date; ?>" data-date-format="YYYY-MM-DD" id="input-date-start' + dates_row + '" class="form-control" />';
      html += '                      <span class="input-group-btn">';
      html += '                        <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>';
      html += '                      </span>';
      html += '                    </div>';
      html += '                  </td>';
			
      html += '									<td class="text-right">';
      html += '                    <div class="input-group date">';
      html += '                      <input type="text" name="reward_dates[' + dates_row + '][end_date]" value="" placeholder="<?= $entry_end_date; ?>" data-date-format="YYYY-MM-DD" id="input-date-end' + dates_row + '" class="form-control" />';
      html += '                      <span class="input-group-btn">';
      html += '                        <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>';
      html += '                      </span>';
      html += '                    </div>';
      html += '                  </td>';
			
      html += '									<td class="text-right">';
      html += '                    <div class="input-group date">';
      html += '                      <input type="text" name="reward_dates[' + dates_row + '][clear_date]" value="" placeholder="<?= $entry_clear_date; ?>" data-date-format="YYYY-MM-DD" id="input-date-clear' + dates_row + '" class="form-control" />';
      html += '                      <span class="input-group-btn">';
      html += '                        <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>';
      html += '                      </span>';
      html += '                    </div>';
      html += '                  </td>';
      
      html += '  <td class="text-left"><button type="button" onclick="$(\'#reward-dates-row' + dates_row + '\').remove();" data-toggle="tooltip" title="<?= $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
			html += '</tr>';
			
			$('#reward-dates tbody').append(html);

      $('.date').datetimepicker({
        pickTime: false
      });

			dates_row++;
		}
	//--></script>
<?php echo $footer; ?>

