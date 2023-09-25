<?php if($active){ ?>
	
	<div id="side-price">
		<div class="list-group-item item-header"><?= $heading_title; ?></div>
		<div class="list-group-item">
			<div class="price-container">
			
				<div class="input-group">
					<?php if($left_symbol){ ?>
						<label class="input-group-addon padding-m14-left c343434"><?= $left_symbol; ?></label>
					<?php } ?>
					<input type="text" 
					name="price_min" 
					min="<?= $lowest_price; ?>" 
					max="<?= $highest_price; ?>" 
					class="form-control input-number" 
					value="<?= $price_min; ?>" 
					onkeyup="updateSlider();"
					id="price_min"
					/>
				</div>
				<div class="input-group">
					<?php if($left_symbol){ ?>
						<label class="input-group-addon padding-m14-left c343434"><?= $left_symbol; ?></label>
					<?php } ?>
					<input type="text" 
					name="price_max" 
					min="<?= $lowest_price; ?>" 
					max="<?= $highest_price; ?>" 
					class="form-control input-number" 
					value="<?= $price_max; ?>"
					onkeyup="updateSlider();"
					id="price_max"
					/>
				</div>
			</div>
		
			<div id="slider-price"></div>
		</div>
		
		<script type='text/javascript' >
			$("#slider-price").slider({
				min: <?= $lowest_price; ?>,
				max: <?= $highest_price; ?>,
				step: 1.00,
				range: true,
				values: [<?= $price_min; ?>, <?= $price_max; ?>],
				create: function (event, ui) {
					$(".ui-slider-handle").attr("onclick", "");
				},
				slide: function () {
					val = $(this).slider("values");

					price_min = val[0].toFixed(2);
					price_max = val[1].toFixed(2);

					$("input[name='price_min']").val(price_min);
					$("input[name='price_max']").val(price_max);
				},
				stop: function (event, ui) {
					val = $(this).slider("values");

					price_min = val[0].toFixed(2);
					price_max = val[1].toFixed(2);

					$("input[name='price_min']").val(price_min);
					$("input[name='price_max']").val(price_max);

					applyFilter();
				}
			});

			function updateSlider(){

				let price_min = $("input[name='price_min']").val();
				let price_max = $("input[name='price_max']").val();

				$("#slider-price").slider( "values", [price_min, price_max]);
				
				if(price_min > -1 && price_max > -1){
					applyFilter();
				}
			}
		</script>
		
	</div>
	
<?php } ?>