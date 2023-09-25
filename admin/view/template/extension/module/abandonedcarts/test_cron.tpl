<table class="table testCron" style="margin-bottom:0px;">
	<tr>
		<td colspan="2"><h4><?php echo $text_cron_system; ?></h4></td>
	</tr>
	<tr>
		<td><h5><span><?php echo $text_shell_exec_function; ?></span></h5></td>
		<td><strong><p class="<?php echo $shell_exec_status ?>"><?php echo ($shell_exec_status=='Enabled') ? '<span style="color:#4CC417">'.$text_cron_enabled.'</span>' : '<span style="color:#F70D1A">'.$text_cron_disabled.'</span>'; ?></strong></td>
	</tr>
    <tr>
        <td><h5><span><?php echo $cron_folder; ?></span></h5></td>
        <td><strong><p class="<?php echo $folder_permission ?>"><?php echo ($folder_permission=='Writable') ? '<span style="color:#4CC417">'.$text_cron_writable.'</span>' : '<span style="color:#F70D1A">'.$text_cron_unwritable.'</span>'; ?></strong></td>
    </tr>
	<tr>
		<td><h5><span><?php echo $text_cron_status; ?></span></h5></td>
		<td><strong><p class="<?php echo $cronjob_status ?>"><?php echo ($cronjob_status=='Enabled') ? '<span style="color:#4CC417">'.$text_cron_enabled.'</span>' : '<span style="color:#F70D1A">'.$text_cron_disabled.'</span>'; ?></strong></td>
	</tr>
	<?php if ($shell_exec_status=='Enabled' && $folder_permission=='Writable' && $cronjob_status=='Enabled') { ?>
    <tr>
        <td colspan="2"><h4><?php echo $text_cron_current_jobs; ?></h4></td>
    </tr>
    <?php if(isset($current_cron_jobs)) { 
        foreach($current_cron_jobs as $cron_job) { 
            if(!empty($cron_job)) { ?>
                <tr><td colspan="2"><p class="cronJobEntry"><?php  echo $cron_job; ?></td></tr>
    <?php } } } ?>
    <?php } ?>
</table>