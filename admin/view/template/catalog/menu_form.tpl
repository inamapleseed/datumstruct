<?= $header, $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-menu" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary">
					<i class="fa fa-save"></i>
				</button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default">
					<i class="fa fa-reply"></i>
				</a>
			</div>
			<h1>
				<?= $heading_title; ?>
			</h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li>
					<a href="<?php echo $breadcrumb['href']; ?>">
						<?php echo $breadcrumb['text']; ?>
					</a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>

	<div class="container-fluid">
		<div class="alert alert-info stick">
			<i class="fa fa-info-circle" ></i> 
			<?= $note_1; ?>
		</div>

		<div class="alert alert-info stick">
			<i class="fa fa-info-circle"></i>
			Categories loaded in header will auto load it's child (Default, 3rd level max, 2 level dropdown). May differ depending on design.
		</div>

		<div class="alert alert-info stick">
			<i class="fa fa-info-circle"></i>
			"For Admin Use" category will not be list but should you added them before setting it, the category will be listed in frontend but the page will be restricted from access and fall back to list all product page.
		</div>

		<?php if ($warning) { ?>
		<div class="alert alert-danger">
			<i class="fa fa-exclamation-circle"></i>
			<?php echo $warning; ?>
			<button type="button" class="close" data-dismiss="alert">&times;</button>
		</div>
		<?php } ?>

		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-pencil"></i>
					<?php echo $text_form; ?>
				</h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-menu" class="form-horizontal">
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="input-title">
							<?php echo $field_title; ?>
						</label>
						<div class="col-sm-10">
							<input type="text" name="title" value="<?php echo $title; ?>" placeholder="<?php echo $field_title; ?>" id="input-title"
								class="form-control" />
							<?php if($error_title) { ?>
							<div class="text-danger">
								<?= $error_title; ?>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group hidden">
						<label class="col-sm-2 control-label" for="input-status">
							<?php echo $field_status; ?>
						</label>
						<div class="col-sm-10">
							<select name="status" id="input-status" class="form-control">
								<?php if ($status) { ?>
								<option value="1" selected="selected">
									<?php echo $text_enabled; ?>
								</option>
								<option value="0">
									<?php echo $text_disabled; ?>
								</option>
								<?php } else { ?>
								<option value="1">
									<?php echo $text_enabled; ?>
								</option>
								<option value="0" selected="selected">
									<?php echo $text_disabled; ?>
								</option>
								<?php } ?>
							</select>
						</div>
					</div>

					<div class="form-group">

						<div class="col-xs-6 col-sm-5 col-md-3">
							<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
								<?php $i=0; foreach($menu_options as $type => $menu_option) { ?>
								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="heading<?= $i; ?>">
										<h4 class="panel-title">
											<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $i; ?>" aria-expanded="true" aria-controls="collapse<?= $i; ?>">
												<?= $type; ?>
											</a>
										</h4>
									</div>
									<div id="collapse<?= $i; ?>" class="panel-collapse collapse <?php if(!$i) echo 'in'; ?>" role="tabpanel" aria-labelledby="heading<?= $i; ?>">
										<div class="panel-body" data-form-type="text">
											<div class="list">
												<?php foreach($menu_option as $route => $list){ ?>
												<label>
													<input type="checkbox" 
													data-id='<?= $list['id']; ?>'
													data-name='<?= $list['name']; ?>' 
													data-query='<?= $list['query']; ?>'
													data-route='<?= $route; ?>' 
													/>
													<?= $list['name']; ?>
												</label>
												<br/>
												<?php } ?>
											</div>
											<hr/>
											<a class="btn btn-primary btn-menu">Add to Menu</a>
										</div>
									</div>
								</div>
								<?php $i++; } ?>
								<div class="panel panel-default">
									<div class="panel-heading" role="tab" id="heading<?= $i; ?>">
										<h4 class="panel-title">
											<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $i; ?>" aria-expanded="true" aria-controls="collapse<?= $i; ?>">
												Custom Link / Label
											</a>
										</h4>
									</div>
									<div id="collapse<?= $i; ?>" class="panel-collapse collapse <?php if(!$i) echo 'in'; ?>" role="tabpanel" aria-labelledby="heading<?= $i; ?>">
										<div class="panel-body" data-form-type="custom">
											<div class="list">
												<?php foreach($languages as $language){ ?>
												<?php $language_id = $language['language_id']; ?>
												<div class="input-group">
													<div class="input-group-addon">
														<img src="language/<?= $language['code']; ?>/<?= $language['code']; ?>.png" title="<?= $language['name']; ?>" />
													</div>
													<input type="text" name="<?= $language_id; ?>" class="form-control name" />
												</div>
												<?php } ?>
												<div class="input-group">
													<div class="input-group-addon">Url</div>
													<input type="text" name="href" class="form-control href custom-links-url" />
												</div>
												<span class="fake-btn pull-right pointer" onclick="$('.custom-links-url').val('#')">
													<i class="fa fa-hand-o-right" aria-hidden="true"></i> 
													Use as label
												</span>
											</div>
											<hr/>
											<a class="btn btn-primary btn-menu">Add to Menu</a>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-xs-6 col-sm-7 col-md-9">
							<div class="panel panel-default">
								<div class="panel-heading">
									<h4 class="panel-title">Menu Structure</h4>
								</div>
								<div class="panel-body menuDrag-body">
									<p>Drag each item into the order you prefer.</p>

									<ul id="menuList">
										<?php $i = 0; ?>
										<?php foreach($menus_interface as $each){ ?>

											<li level="<?= $each['level']; ?>" name="<?= $each['label']; ?>" query="<?= $each['query']; ?>" id="<?= generateSlug($each['query']); ?>" new_tab='<?= (int)$each['new_tab']; ?>' >	
												<div class="menu-cell" >
													<div class="handler">
														<i class="fa fa-arrows"></i>
													</div>
													
													<div class="icon">
														<a 
															href="" id="thumb-image-<?= $i; ?>" 
															data-toggle="image" 
															class="img-thumbnail">
																<img src="<?= $each['thumb']; ?>" alt="" title="" data-placeholder="<?= $placeholder; ?>" />
														</a>
														<input type="hidden" name="input-image-<?= $i; ?>" value="<?= $each['img']; ?>" id="input-image-<?= $i; ?>" />
													</div>

													<div class="text name"><?= $each['label']; ?></div>

													<div class="new-tab pointer <?= $each['new_tab']?'on':''; ?>" data-toggle="tooltip" title="Open New Tab" onclick="updateTab(this);" >
														<i class="fa fa-external-link" aria-hidden="true"></i>
													</div>

													<div class="url pointer" onclick="updateQuery(this.parentNode.parentNode)" >
														<i class="fa fa-link" aria-hidden="true"></i>
													</div>

													<div class="modify" onclick="$(this).parent().next().slideToggle(300);">
														<i class="fa fa-angle-down" aria-hidden="true"></i>
													</div>

													<div class="remove" onclick="removeLi(this);">
														<i class="fa fa-times-circle"></i>
													</div>
												</div>

												<div class="menu-name-area" >
													<div class="menu-area" >
													<?php $count = 0; ?>
													<?php foreach($languages as $language){ ?>
														<?php 
														$language_id = $language['language_id']; 
														$code =  $language['code'];
														$clang = 'L'. $language_id . '-'; 
														$name = $each['label']; 
														foreach($each['name'] as $each_name){
															if( strpos('_'.$each_name, $clang) ){
																$name = $each_name;
																$name = str_replace($clang, '',$name);
															}
														} ?>
													<div class="input-group">
														<div class="input-group-addon">
															<img src="language/<?= $code; ?>/<?= $code; ?>.png" title="<?= $language['name']; ?>" />
														</div>
														<input type="text" name="<?= $language_id; ?>" class="form-control" value="<?= $name; ?>"
															onkeyup="updateTextarea();<?php if(!$count){ ?>
																$(this).parents('li').find('div.name').text(this.value);
															<?php } ?>"
														/>
													</div>
													<?php $count++; } ?>
													</div>
												</div>

											</li>

										
										<?php $i++; } ?>

									</ul>

									<input id="menus" type="hidden" name="menus" value='<?= $menus; ?>' />
									<textarea id="samplebox" class="hidden" ><?= $menus; ?></textarea>
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
	var indent = <?= $indent; ?>; // px
	var max = 99; // Level start from 0
	var $i = <?= $i; ?>;
	$("#menuList").sortable({
		handle: 'div.handler',
		sort: function (e, ui) {
			var move = ui.position.left - ui.originalPosition.left;

			var dragged = $(ui.helper);
			var dragged_prev = dragged.prev();

			level = 0;

			if (move >= indent) {

				level = parseInt((move / indent), 10);

				if (!(move % indent)) {
					level = move / indent;
				}

				if (dragged_prev.length == 1) {
					max_level = parseInt($(dragged_prev).attr("level")) + 1;
					min_level = parseInt($(dragged_prev).attr("level")) - 1;

					if (min_level < 0){
						min_level = 0;
					}

					if (level > max_level) {
						level = max_level;
					} else if (level < min_level) {
						level = min_level;
					}

					if(level > max){ // Static limit
						level = max;
					}

				} else {
					level = 0; // First item
				}

			}

			$(ui.helper).attr("level", level);
			// updateTextarea();
		},
		stop: function (e, ui){
			movedtoFirst(ui);
		},
		out: function (e, ui) {
			movedtoFirst(ui);
		},
	});
</script>
<script type="text/javascript">
	function movedtoFirst(ui){
		setTimeout(function () {
			var dragged = $(ui.helper);
			var dragged_prev = dragged.prev();

			if (dragged_prev.length == 0) {
				dragged.attr("level", 0);
				updateTextarea();
			}
		}, 300);
	}
</script>
<script type="text/javascript">
	var li = '<li level="0" name=\'[NAME]\' query=\'[QUERY]\' id="[ID]" new_tab="0" >' +
		
		'<div class="menu-cell" >' +
		'	<div class="handler">' +
		'		<i class="fa fa-arrows"></i>' +
		'	</div>' +
		
		'<div class="icon">'+
			'<a '+
				'href="" id="thumb-image-XNDEX" '+
				'data-toggle="image" '+
				'class="img-thumbnail">'+
					'<img src="<?= $placeholder; ?>" alt="" title="" data-placeholder="<?= $placeholder; ?>" />'+
			'</a>'+
			'<input type="hidden" name="input-image-XNDEX" value="" id="input-image-XNDEX" />'+
		'</div>'+

		'	<div class="text name">[NAME]</div>' +

		'	<div class="new-tab pointer" data-toggle="tooltip" title="Open New Tab" onclick="updateTab(this);" >'+
		'		<i class="fa fa-external-link" aria-hidden="true"></i>'+
		'	</div>'+

		'	<div class="url pointer" onclick="updateQuery(this.parentNode.parentNode)" >'+
		'		<i class="fa fa-link" aria-hidden="true"></i>'+
		'	</div>'+
		
		'	<div class="modify" onclick="$(this).parent().next().slideToggle(300);">'+
		'		<i class="fa fa-angle-down" aria-hidden="true"></i>'+
		'	</div>'+

		'	<div class="remove" onclick="removeLi(this);">' +
		'		<i class="fa fa-times-circle"></i>' +
		'	</div>' +
		'</div>' +

		'<div class="menu-name-area" >' +
			'<div class="menu-area" >' +
			<?php $count = 0; ?>
			<?php foreach($languages as $language){ ?>
			<?php $language_id = $language['language_id']; ?>
			'<div class="input-group">'+
				'<div class="input-group-addon">'+
					'<img src="language/<?= $language['code']; ?>/<?= $language['code']; ?>.png" title="<?= $language['name']; ?>" />'+
				'</div>'+
				'<input type="text" name="<?= $language_id; ?>" class="form-control" value="[VALUE]" ' +
				<?php if(!$count){ ?>
					'onkeyup="$(this).parents(\'li\').find(\'div.name\').text(this.value);updateTextarea()"'+
				<?php } ?>
				'/>'+
			'</div>'+
			<?php $count++; } ?>
			'</div>' +
		'</div>' +
		
		'</li>';

	$(window).load(function () {
		$(".btn-menu").on('click', function (e) {
			e.preventDefault();

			btn_clicked = $(this);
			form_type = btn_clicked.parent().data('form-type');

			$(".btn-menu").prop('disabled', 'disabled');

			var to_add_li = "";
			if (form_type == 'text') {

				if (btn_clicked.parent().find('input:checked').length) {
					$.each(btn_clicked.parent().find('input:checked'), function (index, ui) {

						id = ui.dataset.id;
						name = ui.dataset.name;		// cl(name);
						query = ui.dataset.query;

						to_add_li += li
						.replace('[ID]', id).replace('[IDX]', id)
						.replace('[NAME]', name)
						.replace('[NAME]', name)
						<?php foreach($languages as $language){ ?>
						.replace('[VALUE]', name)
						<?php } ?>
						.replace('[QUERY]', query).split("XNDEX").join($i);
						$i++;
					});
				}
			}
			else if(form_type == 'custom' ){
				query = btn_clicked.parent().find('input.href').val();
				type = 'custom';
				names = {};
				inputs = btn_clicked.parent().find('input.name');
				$.each(inputs, function(index, ui){
					value = ui.value;
					language_id = ui.name;
	
					if ($.trim(value) == ''){
						alert("Please fill all the filed to add Custom Link");
						return false;
					}
					else{
						names[language_id] = value;
					}
				});

				if( $.trim(query) != "" && names.length != 0 ){
					to_add_li += li.replace('[ID]', '').replace('[IDX]', '')
							.replace('[QUERY]', query);
					
					for (var language_id in names){
						if (names.hasOwnProperty(language_id)) {
							var name=names[language_id];
							var to_add_li = to_add_li.replace('[NAME]', name)
							.replace('[NAME]', name)
							.replace('[VALUE]', name);
						}
					}

				}
				else{
					alert("Please fill all the filed to add Custom Link");
				}
			}

			if (to_add_li) {
				$("#menuList").append(to_add_li);
				$("#menuList").sortable('refresh');
				updateTextarea();

				$('div[data-form-type="custom"] input').val('');
			}

			// Clear checkbox
			$("#form-menu input[type='checkbox']").prop("checked", false);

		});
	});
</script>
<script type="text/javascript">
	function updateTextarea() {

		if ($("#menuList > li").length) {

			// Update the levels
			$.each($("#menuList > li"), function (index, ui) {
				var current = $(ui);
				if(!index){
					current.attr("level", 0);
				}
				else{
					var current_level = current.attr('level');
					var parent_from_above = $("#menuList > li:nth-child("+index+")");
					if(parent_from_above.length == 1){
						var parent_level = parent_from_above.attr('level');
						max_level = parseInt(parent_level) + 1;
						if(current_level > max_level ){
							current.attr("level", max_level);
						}
					}
				}
			});

			var json = []; 
			
			$.each($("#menuList > li"), function (index, ui) {
				
				var name = [];
				var firstname = $(ui).attr('name');
				$(ui).find('.menu-name-area').find('input').each(function(position, html){
					name.push('L'+this.name +'-'+ this.value);
					if(firstname == $(ui).attr('name')){
						firstname=this.value;
					}
				});

				json[index] = {
					level: $(ui).attr('level'),
					img: $(ui).find('.icon').find('input').val(),
					label: firstname,
					name: name,
					route: $(ui).attr('route'),
					query: $(ui).attr('query'),
					new_tab: $(ui).attr('new_tab'),
				};

			});

			json = JSON.stringify(json);
			$("#menus").val(json);
			$("#samplebox").val(json);
		} else {
			$("#menus").val('');
			$("#samplebox").val('');
		}
	}
</script>
<script type="text/javascript">
	function removeLi(ele) {
		$(ele).parents('li').remove();
		updateTextarea();
	}
</script>
<script type="text/javascript">
	function updateQuery(li){
		if(li == null){
			alert('Fail to obtain link node, Please refresh');
			return;
		}
		
		$new_query = prompt('Please enter new query/URL', $(li).attr('query'));
		if($new_query == null) return;

		$(li).attr('query', $new_query);

		updateTextarea();
	}
</script>
<script type="text/javascript">
	function updateTab(ele){
		if(ele == null){
			return;
		}
		
		$(ele).toggleClass('on');

		li = ele.parentNode.parentNode; //cl($(li).attr('new_tab'));

		$(li).attr('new_tab', $(li).attr('new_tab') == 1 ? 0 : 1);

		updateTextarea();
	}
</script>
<style>
	<?php for($i=0;
	$i < 100;
	$i++) {
		?>li[level="<?= $i; ?>"]:not(.ui-sortable-helper) {
			margin-left: <?=$i*$indent;
			?>px !important;
		}
		li[level="<?= $i; ?>"]+.ui-sortable-placeholder {
			margin-left: <?=$i*$indent;
			?>px !important;
		}
		<?php
	}

	?>
</style>
<?= $footer; ?>