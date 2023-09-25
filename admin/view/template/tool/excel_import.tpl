<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1>Excel Import Point</h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if($error_warning){ ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<?php if($success){ ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i>Excel Import Point</h3>
      </div>
      <div class="panel-body">
		
		<style>
		.nav-tabs{
			margin-bottom:0px;
		}
		.tab-content{
			margin-top: 0px;
			border-left: 1px solid #ddd;
			border-right: 1px solid #ddd;
			border-bottom: 1px solid #ddd;
			padding:20px;
		}
		.btn-success a{
			color:#fff;
			font-size:14px;
			text-transform:uppercase;
		}
		.tbpadding{
			padding:10px;
		}
		.ourbtn{
			background-color:#921e6f;
			border:none;
			text-transform:uppercase;
		}
		.ourbtn:hover{
			background-color:#921e6f;
			border:none;
		}
		.ourbtn i{
			margin-right:5px;
		}
		</style>
		
		<div class="tab-content">
            <div class="tab-pane active" id="tab-import">
				<div class="row">
					<div class="col-sm-12">
					  <ul class="nav nav-tabs">
						<li class="active"><a href="#productimport" data-toggle="tab">Products</a></li>
						<li><a href="#categoriesimport" data-toggle="tab">Categories</a></li>
						<li><a href="#manufacturersimport" data-toggle="tab">Manufacturers</a></li>
					  </ul>
					</div>
					<div class="col-sm-12">
					  <div class="tab-content">
						 <div class="tab-pane active" id="productimport">
							<h3>Product Import</h3>
							<?php require_once('import/productimport.tpl'); ?>
						 </div>
						 <div class="tab-pane" id="categoriesimport">
							<h3>Category Import</h3>
							<?php require_once('import/categoriesimport.tpl'); ?>
						 </div>
						 <div class="tab-pane" id="manufacturersimport">
							<h3>Manufacturer Import</h3>
							<?php require_once('import/manufacturersimport.tpl'); ?>
						 </div>
					   </div>
					</div>
				</div>
			</div>
		</div>
	  </div>
    </div>
 </div>
</div>
<?php echo $footer; ?>