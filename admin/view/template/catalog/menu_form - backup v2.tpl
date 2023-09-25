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
				<?= $note_1; ?>
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
													<?php foreach($menu_option as $index => $list){ ?>
													<label>
														<input type="checkbox" data-href='<?= $list['href']; ?>' data-name='<?= $list['name']; ?>' data-label='<?= $list['label']; ?>' data-type='<?= generateSlug($type); ?>' />
														<?= $list['label']; ?>
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
													Custom Link
												</a>
											</h4>
										</div>
										<div id="collapse<?= $i; ?>" class="panel-collapse collapse <?php if(!$i) echo 'in'; ?>" role="tabpanel" aria-labelledby="heading<?= $i; ?>">
											<div class="panel-body" data-form-type="custom">
												<div class="list">
													<div class="input-group">
														<div class="input-group-addon">Label</div>
														<input type="text" name="label" class="form-control text" />
													</div>
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
														<input type="text" name="href" class="form-control href" />
													</div>
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

											<?php foreach($menus_interface as $each){ ?>
											<li level='<?= $each['level']; ?>' name='<?= $each['name']; ?>' label='<?= $each['label']; ?>' href='<?= $each['href']; ?>' type='<?= $each['type']; ?>'  >
												<div class="handler" onmousedown="$(this).addClass("groupedMove");" onmouseup="groupMoveInactive(this);" >
													<i class="fa fa-arrows"></i>
												</div>
												<div class="text">
													<?= $each['label']; ?>
												</div>
												<div class="remove" onclick="removeLi(this);">
													<i class="fa fa-times-circle"></i>
												</div>
											</li>
											<?php } ?>

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
		var max = 2; // Level start from 0

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
		var li = '<li level="0" label=\'[LABEL]\' name=\'[NAME]\' href=\'[HREF]\' type=\'[TYPE]\' >' +
			'<div class="handler">' +
			'<i class="fa fa-arrows"></i>' +
			'</div>' +
			'<div class="text">[SHOW_LABEL]</div>' +
			'<div class="remove" onclick="removeLi(this);">' +
			'<i class="fa fa-times-circle"></i>' +
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

							href = ui.dataset.href;
							name = ui.dataset.name;
							label = ui.dataset.label;
							type = ui.dataset.type;

							to_add_li += li
							.replace('[LABEL]', label)
							.replace('[SHOW_LABEL]', label)
							.replace('[NAME]', name)
							.replace('[HREF]', href)
							.replace('[TYPE]', type);

						});
					}
				}
				else if(form_type == 'custom' ){
					label = btn_clicked.parent().find('input.text').val();
					href = btn_clicked.parent().find('input.href').val();
					type = 'custom';
					name = '';

					$.each(btn_clicked.parent().find('input.name'), function(index, ui){
						name += "&text["+ui.name+"]=" + ui.value;
					});

					if(name){
						query = name;
						name = "";
						$.ajax({
							url: 'index.php?route=catalog/menu/safe&token=<?= $token; ?>' + query,
							datatype: 'JSON',
							type: 'GET',
							async: false,
							success: function(json){
								if(json){
									name = json;
								}
							}
						});
					}
					

					if( $.trim(label) != "" && $.trim(href) != "" && $.trim(name) != "" ){
						to_add_li += li.replace('[LABEL]', label)
								.replace('[SHOW_LABEL]', label)
								.replace('[NAME]', name)
								.replace('[HREF]', href)
								.replace('[TYPE]', type);
					}
					else{
						alert("Please fill all the filed to add Custom Link");
					}
				}

				if (to_add_li) {
					$("#menuList").append(to_add_li);
					$("#menuList").sortable('refresh');
					updateTextarea();
				}

				// Clear checkbox
				$("#form-menu input[type='checkbox']").prop("checked", false);

			});
		});
	</script>
	<script type="text/javascript">
		function updateTextarea() {

			if ($("#menuList > li").length) {

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

					json[index] = {
						level: $(ui).attr('level'),
						name: $(ui).attr('name'),
						href: $(ui).attr('href'),
						label: $(ui).attr('label'),
						type: $(ui).attr('type')
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
			$(ele).parent().remove();
			updateTextarea();
		}
	</script>
	<script type="text/javascript">
		function groupMoveActive(ele){
			
		}

		function groupMoveInactive(ele){
			$(ele).removeClass("groupedMove");
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