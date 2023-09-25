<?php echo $header,$column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-banner" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
			<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
			<h1><?php echo $tx_heading; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
					<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
				<?php } ?>
			</ul>
		</div>
	</div>
	
	<div class="container-fluid">
		<div class="alert alert-info stick"><i class="fa fa-exclamation-circle"></i>
			Do not modify this page unless you know what you are doing.
		</div>
		<?php if ($error_warning) { ?>
			<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $tx_heading; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-banner" class="form-horizontal">
					<table class = "table table-bordered" >
						<thead>
							<tr>
								<td class = "text-center" ><?php echo $tx_name; ?></td>
								<td class = "text-center" ><span data-toggle="tooltip" title = "<?php echo $tx_null_help; ?>" ><?php echo $tx_null_title; ?></span></td>
								<td class = "text-center" ><?php echo $tx_library; ?></td>
								<td class = "text-center" ><?php echo $tx_layout; ?></td>
								<td class = "text-center" ><?php echo $tx_id; ?></td>
								<td class = "text-center" >Frontend Variable</td>
								<td class = "text-center" width = "1px" >Action</td>
							</tr>
						</thead>
						<tbody>
							<?php $rows = 0; ?>
							<?php foreach($rules as $rule){ ?> 
								<tr>
									<td><input type = 'text' name = 'rules[<?php echo $rows; ?>][title]' class = 'form-control' placeholder = '<?php echo $tx_name; ?>' value = "<?php echo $rule['title']; ?>" /></td>
									<td><input type = 'text' name = 'rules[<?php echo $rows; ?>][default_title]' class = 'form-control' placeholder = '<?php echo $tx_null_title; ?>' value = "<?php echo $rule['default_title']; ?>" /></td>
									<td><input type = 'text' name = 'rules[<?php echo $rows; ?>][library]' class = 'form-control' placeholder = '<?php echo $tx_library; ?>' value = "<?php echo $rule['library']; ?>" /></td>
									<td><input type = 'text' name = 'rules[<?php echo $rows; ?>][layout]' class = 'form-control' placeholder = '<?php echo $tx_layout; ?>' value = "<?php echo $rule['layout']; ?>" /></td>
									<td><input type = 'text' name = 'rules[<?php echo $rows; ?>][var]' class = 'form-control' placeholder = '<?php echo $tx_id; ?>' value = "<?php echo $rule['var']; ?>" /></td>
									<td><input type = 'text' name = 'rules[<?php echo $rows; ?>][var_frontend]' class = 'form-control' placeholder = 'Frontend Variable' value = "<?php echo $rule['var_frontend']; ?>" /></td>
									<td><a onclick = '$(this).parents(\"tr\").remove();' style = 'cursor:pointer;' class = 'btn btn-danger' data-toggle='tooltip' title = 'Remove' >
										<i class="fa fa-minus-circle"></i></a></td>
								</tr>
							<?php $rows++; } ?>
						</tbody>
						<tfoot>
							<tr>
								<td class = "text-right" colspan = "6" >
									<button type="button" onclick="addRow();" data-toggle="tooltip" title="Add row" class="btn btn-primary">
										<i class="fa fa-plus-circle"></i>
									</button>
								</td>
							</tr>
						</tfoot>
					</table>
				</form>
			</div>
		</div>
	</div>
</div>
<script type = "text/javascript">
	var i = <?php echo $rows; ?>;
	function addRow(){
		html = "<tr>";
		html += "<td><input type = 'text' name = 'rules["+i+"][title]' class = 'form-control' placeholder = '<?php echo $tx_name; ?>' /></td>";
		html += "<td><input type = 'text' name = 'rules["+i+"][default_title]' class = 'form-control' placeholder = '<?php echo $tx_null_title; ?>' /></td>";
		html += "<td><input type = 'text' name = 'rules["+i+"][library]' class = 'form-control' placeholder = '<?php echo $tx_library; ?>' /></td>";
		html += "<td><input type = 'text' name = 'rules["+i+"][layout]' class = 'form-control' placeholder = '<?php echo $tx_layout; ?>' /></td>";
		html += "<td><input type = 'text' name = 'rules["+i+"][var]' class = 'form-control' placeholder = '<?php echo $tx_id; ?>' /></td>";
		html += "<td><input type = 'text' name = 'rules["+i+"][var_frontend]' class = 'form-control' placeholder = 'Frontend Variable' /></td>";
		html += "<td><a onclick = '$(this).parents(\"tr\").remove();' style = 'cursor:pointer;' class = 'btn btn-danger' data-toggle='tooltip' title = 'Remove' ><i class=\"fa fa-minus-circle\"></i></a></td>";
		html += "</tr>";
		
		$("tbody").append(html);
		i++;
	}
</script>

<?php echo $footer; ?> 