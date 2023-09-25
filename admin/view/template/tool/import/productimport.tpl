<hr />
<form action="<?php echo $productaction; ?>" method="post" enctype="multipart/form-data" id="form-importproduct" class="form-horizontal">
	<div class="row">

		<!-- Product File Import Input -->
		
		<div class="col-sm-6 tbpadding">
			<input type="file" name="import" value="" />
		</div>
		
		<div class="col-sm-6 tbpadding">
			<?php echo $entry_prodimportimport; ?>
		</div>
		<div class="clearfix"></div>
		
		<!-- Import type and Store -->
		
		<div class="col-sm-6 tbpadding">
			<label><?php echo $text_importtype; ?></label>
			<select class="form-control" name="importtype">
			  <option value="1"><?php echo $text_productid; ?></option>
			  <option value="2"><?php echo $text_model; ?></option>
			</select>
			<b>Note: </b><i>Import your products according to Product ID Or Model (Model must be unique).</i>
		</div>
		
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
		<div class="clearfix"></div>
		
		<!-- Language and Submit -->
		
		<div class="col-sm-6 tbpadding">
			<label><?php echo $entry_language; ?></label>		
			<select class="form-control" name="language_id">
			 <?php foreach($languages as $language){ ?>
				<option value="<?php echo $language['language_id']; ?>"><?php echo $language['name']; ?></option>
			  <?php } ?>
			</select>
			<i>Import your products according to Language.</i>
		</div>
		
		<div class="col-sm-6 tbpadding">
			<label> </label>
			<button onclick="$('#form-importproduct').submit()"; type="button" class="ourbtn btn btn-primary form-control"><i class="fa fa-upload"></i> Import</button>
		</div>
		<div class="clearfix"></div>
		
	</div>
</form>