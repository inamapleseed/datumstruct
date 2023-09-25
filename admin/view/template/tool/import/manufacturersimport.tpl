<hr />
<form action="<?php echo $manufactureaction; ?>" method="post" enctype="multipart/form-data" id="formimportmanufacturer" class="form-horizontal">
	
	<div class="row">
		
		<!-- Manufacture File Import Input -->
		<div class="col-sm-6 tbpadding">
			<input type="file" name="import" value=""/>	
		</div>
		<div class="col-sm-6 tbpadding">
			<?php echo $entry_manufacturerimport; ?>
		</div>
		<div class="clearfix"></div>
		
		<!-- Import Button -->
		<div class="col-sm-6 tbpadding">
			<button onclick="$('#formimportmanufacturer').submit()"; type="button" class="ourbtn btn btn-primary form-control"><i class="fa fa-upload"></i> Import</button>
		</div>
		
		
	</div>

</form>