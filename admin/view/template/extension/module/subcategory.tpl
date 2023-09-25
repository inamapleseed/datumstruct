<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-category" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-category">
							Module Name
						</label>
						<div class="col-sm-10">
							<input type="text" name="name" id="input-category" class="form-control" value="<?php echo $name; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
						<div class="col-sm-10">
							<select name="status" id="input-status" class="form-control">
								<?php if ($status) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
									<option value="0"><?php echo $text_disabled; ?></option>
									<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-category">
							<span data-toggle="tooltip"  title="Catergory to redirect and apply filter from 'List Filter'">Filter to Category</span>
						</label>
						<div class="col-sm-10">
							<input type="text" name="category" id="input-category" class="form-control" value="<?php echo $category; ?>" />
							<input type="hidden" name="category_id" id="input-category" value="<?php echo $category_id; ?>" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-filter">
							List Filter
						</label>
						<div class="col-sm-10">
							<input type="text" name="filter" value="" placeholder="Filter value" id="input-filter" class="form-control"/>
							<div id="category-filter" class="well well-sm" style="height: 200px; overflow: auto;">
								<?php foreach ($category_filters as $category_filter) { ?>
									<div id="category-filter<?php echo $category_filter['filter_id']; ?>">
										<i class="fa fa-minus-circle"></i>
										<?php echo $category_filter['name']; ?>
										<input type="hidden" name="category_filter[]" value="<?php echo $category_filter['filter_id']; ?>"/>
									</div>
								<?php } ?>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	<!--
	$('input[name=\'filter\']').autocomplete({
		'source': function (request, response) {
			$.ajax({
				url: 'index.php?route=catalog/filter/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
				dataType: 'json',
				success: function (json) {
					response($.map(json, function (item) {
						return {label: item['name'], value: item['filter_id']}
					}));
				}
			});
		},
		'select': function (item) {
			$('input[name=\'filter\']').val('');
			
			$('#category-filter' + item['value']).remove();
			
			$('#category-filter').append('<div id="category-filter' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="category_filter[]" value="' + item['value'] + '" /></div>');
		}
	});
	
	$('#category-filter').delegate('.fa-minus-circle', 'click', function () {
		$(this).parent().remove();
	});
	
	//-->
</script>
<script type="text/javascript">
	<!--
	$('input[name=\'category\']').autocomplete({
		'source': function (request, response) {
			$.ajax({
				url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
				dataType: 'json',
				success: function (json) {
					json.unshift({category_id: 0, name: '<?php echo $text_none; ?>'});
					
					response($.map(json, function (item) {
						return {label: item['name'], value: item['category_id']}
					}));
				}
			});
		},
		'select': function (item) {
			$('input[name=\'category\']').val(item['label']);
			$('input[name=\'category_id\']').val(item['value']);
		}
	});
	
	//-->
</script>
<?php echo $footer; ?>