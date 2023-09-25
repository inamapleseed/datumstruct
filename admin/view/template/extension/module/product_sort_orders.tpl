<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="button" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary" onclick="$('#form').submit();"><i class="fa fa-save"></i></button>
			</div>
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
		<?php if ($success) { ?>
			<div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } ?>
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
			</div>
			<div class="panel-body">
				<div class="well">
					<div class="row">
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="input-status"><?php echo $column_category; ?></label>
								<select name="filter_category" class="form-control" >
									<option value="*"></option>
									<?php foreach ($categories as $category) { ?>
										<?php if ($category['category_id']==$filter_category) { ?>
											<option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
										<?php } else { ?>
											<option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option> 
										<?php } ?>
									<?php } ?>
								</select>
							</div>
							<a class="btn btn-primary pull-right"  onclick="filter();"><i class="fa fa-search"></i> <?php echo $button_filter; ?></a>
						</div>
					</div>
				</div>
				<form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" id="form">
					<input type="hidden" name="category_id" value="<?php echo $filter_category; ?>">
					<div class="table-responsive">
						<table id="product-list" class="table table-bordered table-hover">
						  <thead>
							<tr>
							  <td class="text-center"><?php echo $column_image; ?></td>
							  <td class="text-center"><?php if ($sort == 'p.sort_order') { ?>
								<a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_sort_order; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_order; ?>"><?php echo $column_sort_order; ?></a>
								<?php } ?>
							  </td>
							  <td class="text-left"><?php if ($sort == 'pd.name') { ?>
								<a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
								<?php } ?></td>
							  <td class="text-left"><?php if ($sort == 'p.model') { ?>
								<a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
								<?php } ?></td>
							  <td class="text-left"><?php if ($sort == 'p.price') { ?>
								<a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
								<?php } ?></td>
							  <td class="text-left"><?php if ($sort == 'p2c.category_id') { ?>
								<a href="<?php echo $sort_category; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_category; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_category; ?>"><?php echo $column_category; ?></a>
							  <?php } ?></td>
							  <td class="right"><?php if ($sort == 'p.quantity') { ?>
								<a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
								<?php } ?></td>
							  <td class="text-left"><?php if ($sort == 'p.status') { ?>
								<a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
								<?php } else { ?>
								<a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
								<?php } ?></td>
							  <td class="right"><?php echo $column_action; ?></td>
							</tr>
						  </thead>
						  <tbody>
							<?php if ($products) { ?>
							<?php foreach ($products as $product) { ?>
							<tr>
							  <td class="text-center"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" /></td>
							  <td class="text-left">
								<input type="text" name="sort_order_<?php echo $product['product_id']; ?>" value="<?php echo $product['sort_order']; ?>" style="width: 40px;">
							  </td>
							  <td class="text-left"><?php echo $product['name']; ?></td>
							  <td class="text-left"><?php echo $product['model']; ?></td>
							  <td class="text-left"><?php if ($product['special']) { ?>
								<span style="text-decoration: line-through;"><?php echo $product['price']; ?></span><br/>
								<span style="color: #b00;"><?php echo $product['special']; ?></span>
								<?php } else { ?>
								<?php echo $product['price']; ?>
								<?php } ?></td>
							   <td class="text-left">
								<?php foreach ($categories as $category) { ?>
								<?php if (in_array($category['category_id'], $product['category'])) { ?>
								<?php echo $category['name'];?><br>
								<?php } ?> <?php } ?>
							
							  </td>
							  <td class="right"><?php if ($product['quantity'] <= 0) { ?>
								<span style="color: #FF0000;"><?php echo $product['quantity']; ?></span>
								<?php } elseif ($product['quantity'] <= 5) { ?>
								<span style="color: #FFA500;"><?php echo $product['quantity']; ?></span>
								<?php } else { ?>
								<span style="color: #008000;"><?php echo $product['quantity']; ?></span>
								<?php } ?></td>
							  <td class="text-left"><?php echo $product['status']; ?></td>
								<td class="text-right">
									<a href="<?php echo $product['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
								</td>
							</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
							  <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
							</tr>
							<?php } ?>
						  </tbody>
						</table>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=extension/module/product_sort_orders&token=<?php echo $token; ?>';
	
	var filter_category = $('select[name=filter_category]').val();
    
    if (filter_category != '*') {
		url += '&filter_category=' + encodeURIComponent(filter_category);
	}
	
	location = url;
}

$(document).ready(function () {
	if(jQuery.ui != undefined) {
		$("table#product-list tbody").sortable({
			items: "tr",
			helper: function (e, ui) {
				ui.children().each(function () {
					$(this).width($(this).width());
				});
				return ui;
			},
			scroll: true,
			stop: function (event, ui) {
				var cnt = 1;
				var list = '';
				$('table#product-list tbody tr').each(function(){
					($(this).find('td input[name*=sort_order]').val(cnt));
					cnt++;
				});      
			}
		});
	}
});
//--></script> 
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script> 
<?php echo $footer; ?>