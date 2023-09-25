<hr />
<form action="<?php echo $categoriesaction; ?>" method="post" enctype="multipart/form-data" id="form-importcategories" class="form-horizontal">
	<div class="row">
		
		<!-- Category File Import Input -->
		<div class="col-sm-6 tbpadding">
			<input type="file" name="import" value="" />	
		</div>
		<div class="col-sm-6 tbpadding">
			<?php echo $entry_categoriesimport; ?>
		</div>
		<div class="clearfix"></div>
		
		<!-- Store and Language -->
		<div class="col-sm-6 tbpadding">
			<label><?php echo $entry_store; ?></label>
			<select class="form-control" name="store_id">
				<option value="0"><?php echo $text_default; ?></option>
				<?php foreach($stores as $store){ ?>	
				<option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
				<?php } ?>
			</select>
			<i>Import your products according to Store.</i>
		</div>
		
		<div class="col-sm-6 tbpadding">
			<label><?php echo $entry_language; ?></label>
			<select class="form-control" name="language_id">
				<?php foreach($languages as $language){ ?>
				<option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
				<?php } ?>
			</select>
			<i>Import your products according to Language.</i>
		</div>
		<div class="clearfix"></div>
		
		<!-- Import Button -->
		<div class="col-sm-6 tbpadding">
			<button onclick="$('#form-importcategories').submit()"; type="button" class="ourbtn btn btn-primary form-control"><i class="fa fa-upload"></i> Import</button>
		</div>
		
	</div>

</form>