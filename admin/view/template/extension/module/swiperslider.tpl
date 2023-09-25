<?php echo $header; ?>
<?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-slideshow" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary">
					<i class="fa fa-save">
					</i>
				</button>
				<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default">
					<i class="fa fa-reply">
					</i>
				</a>
			</div>
			<h1>
				<?php echo $heading_title; ?>
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
		<?php if ($error_warning) { ?>
			<div class="alert alert-danger">
				<i class="fa fa-exclamation-circle">
				</i> <?php echo $error_warning; ?>
				<button type="button" class="close" data-dismiss="alert">&times;</button>
			</div>
		<?php } ?>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-pencil">
					</i> <?php echo $text_edit; ?>
				</h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-slideshow" class="form-horizontal">
					<ul class="nav nav-tabs">
						<li <?php if(!$error_name){ ?> class="active" <?php } ?>><a data-toggle="tab" href="#slide">Slide</a></li>
						<li <?php if($error_name){ ?> class="active" <?php } ?> ><a data-toggle="tab" href="#settings">Settings</a></li>
						<li><a data-toggle="tab" href="#format">Format</a></li>
					</ul>
					<div class="tab-content">
						
						<div id="format" class="tab-pane fade">
							
							<div class="alert alert-danger stick" ><i class="fa fa-exclamation-circle" aria-hidden="true"></i> IF YOU MODIFY THE FORMAT PLEASE SAVE TO REFLECT IN THE SLIDE TAB</div>
							<div class="alert alert-info stick"><i class="fa fa-info-circle" aria-hidden="true"></i> Before adding new text defination, do get the code(css) ready in the frontend store before adding otherwise the text will be render as normal paragraph.</div> 
							
							<table class="table table-bordered table-hover">
								<thead>
									<tr> 
										<td>
											<span data-toggle="tooltip" title="A class will be generated from this title and set as class. Customer only need to define each text defination so frontend will render accordingly. PS: Removing this will no remove the defination in frontend" >
												Format title
											</span>
										</td>
										<th style="width:1px" ></th>
									</tr>
								</thead>
								<tbody  id= "definationFirst" > 
									<?php foreach($defination as $index => $each){ ?>
										<tr>
											<td>
												<input type="text" name="defination[]" value="<?= $each; ?>" class="form-control"  />
											</td>
											<td><a onclick="remove(this);" data-toggle="tooltip" title="Remove Defination" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a></td>
										</tr> 
									<?php } ?> 
								</tbody>
								<tfoot>
									<tr>  
										<td></td>
										<td><a onclick="addDefination();" data-toggle="tooltip" title="Add Defination" class="btn btn-primary"><i class="fa fa-plus-circle"></i></a></td>
									</tr>
								</tfoot>
							</table> 
							<script type="text/javascript">
								var defination = '<tr><td><input type="text" name="defination[]" value="" class="form-control"  /></td><td><a onclick="remove(this);" data-toggle="tooltip" title="Remove Defination" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a></td></tr>';
								function addDefination(){ 
									$("#definationFirst").append(defination);
								}
							</script>
						</div> 
						
						<!-- Settings-->
						
						<div id="settings" class="tab-pane fade <?php if($error_name){ ?> active in <?php } ?>"> 
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-name">
									<span data-toggle="tooltip" title="Module Name. This will not reflect in frontend but for admin to refer" >Module Name</span>
								</label>
								<div class="col-sm-10">
									<input type="text" name="name" class="form-control" value="<?= $name; ?>" />
									<?php if($error_name){ ?>
										<div class="text-danger"><?= $error_name; ?></div>
									<?php } ?>
								</div>
							</div>	 
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-direction">
									<span data-toggle="tooltip" title="Could be 'horizontal' or 'vertical' (for vertical slider)." >Direction</span>
								</label>
								<div class="col-sm-10">
									<?= choice("direction", "input-direction",  $direction, $direction_select); ?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-effect">
									<span data-toggle="tooltip" title="Transition/animation from slide to slide" >Effect</span>
								</label>
								<div class="col-sm-10">
									<?= choice("effect", "input-effect",  $effect, $effect_select); ?>
								</div>
							</div>
							<div class="form-group">
								<div class="col-xs-12">
									
									<div class="alert alert-info stick"><i class="fa fa-info-circle" aria-hidden="true"></i>  
										Set responsive slide size cater for mobile, table and desktop or any device.
									</div>
									
									<table class="table table-bordered table-hover">
										<thead>
											<tr>
												<td><span data-toggle="tooltip" title="From 0(px) to N(px) where N is the 'Breaking point' measure by width" >Breaking point (px)</span></td>
												<td>
													<span data-toggle="tooltip" title="In percentage but don't have to include the % in value"  >Slider Width (%)</span>
												</td>
												<td>
													<span data-toggle="tooltip" title="In percentage but don't have to include the % in value"  >Slider Height (%)</span> 
												</td>
												<td style="width:1px;" ></td>
											</tr> 
										</thead>
										
										<tbody id="breakingFirst" >
											<?php $i = 0; ?>
											<?php foreach($breaking as $break){ ?>
												<tr>
													<td>
														<input type="text" name="breaking[<?= $i; ?>][point]" class="form-control" value="<?= isset($break['point'])?$break['point']:''; ?>" />
													</td>
													<td>
														<input type="text" name="breaking[<?= $i; ?>][width]" class="form-control" value="<?= isset($break['width'])?$break['width']:''; ?>" />
													</td>
													<td>
														<input type="text" name="breaking[<?= $i; ?>][height]" class="form-control" value="<?= isset($break['point'])?$break['height']:''; ?>" />
													</td>
													<td><a onclick="remove(this);" data-toggle="tooltip" title="Remove Breaking Point" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a></td>
												</tr>
												<?php $i++; ?>
											<?php } ?>
										</tbody>
										
										<tfoot>
											<tr>
												<td colspan="3" ></td>
												<td><a onclick="addBreakingPoint();" data-toggle="tooltip" title="Add Breaking Point" class="btn btn-primary"><i class="fa fa-plus-circle"></i></a></td>
											</tr>
										</tfoot>
										
									</table>
								</div>
							</div>
							<script type= "text/javascript">
								var breaking_row = <?= $i ; ?>;  
								var breaking="";
								function addBreakingPoint(){
									breaking = '<tr><td><input type="text" name="breaking['+breaking_row+'][point]" class="form-control" value="" /></td><td><input type="text" name="breaking['+breaking_row+'][width]" class="form-control" value="" /></td><td><input type="text" name="breaking['+breaking_row+'][height]" class="form-control" value="" /></td><td><a onclick="remove(this);" data-toggle="tooltip" title="Remove Defination" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a></td></tr>';
									$("#breakingFirst").append(breaking);
									breaking_row++;
								}
							</script> 
						</div>
						
						<!-- END Settings-->
						
						<!-- Slider -->
						
						<div id="slide" class="tab-pane fade  <?php if(!$error_name){ ?> active in <?php } ?>">
							
							<div class="alert alert-info stick"><i class="fa fa-info-circle" aria-hidden="true"></i> Swiper slider is best used with image only slider as all images are used as background instead. Meaning the slider will not get restricted by the resolution of the image.</div>
							<div class="alert alert-info stick"><i class="fa fa-info-circle" aria-hidden="true"></i> Do set your responsive point under the settings. <small><em>GG Good Luck Have Fun!!</em></small></div>
							
							<table class="table table-bordered">
								<thead>
									<tr>
										<td style="width:127px;" class="text-center" >
											<span data-toggle="tooltip" title="This will  be used as background" >Image</span>
										</td>  
										<td>
											<span data-toggle="tooltip" title="The slider attribute and behaviour" >Slider setting</span> 
										</td>
										<td style="width:1px;" ></td>
									</tr>
								</thead>
								<tbody id="bannerFirst" >
									<?php $j = 0; ?>
									<?php foreach($sliders as $slide){ ?> 
										<tr>
											<td>
												<a href="" id="thumb-slide-<?= $j; ?>" data-toggle="image" class="img-thumbnail"  style="width:100%;" >
													<img src="<?= isset($slide['thumb'])?$slide['thumb']:''; ?>" data-placeholder="<?= $placeholder; ?>" style="width:100%;" />
												</a>
												<input type="hidden" name="sliders[<?= $j; ?>][image]" value ="<?= isset($slide['image'])?$slide['image']:''; ?>" id="input-slide-<?= $j; ?>" />
											</td> 
											<td id="sliderDescriptionSection<?= $j; ?>"> 
												<div class="text-col-xs-12 alt text-right"> 
													<a onclick="addDescription('<?= $j; ?>');" class="btn btn-warning" >Add Text</a>
												</div>
												
												<?php if(isset($slide['description']) && $slide['description']){ ?>
												
												<?php } ?>
												
											</td> 
											<td><a onclick="remove(this);" data-toggle="tooltip" title="Remove Breaking Point" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a></td>
										</tr>
										<?php $j++; ?>
									<?php } ?> 
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2"></td> 
										<td><a onclick="addBanner();" data-toggle="tooltip" title="Add Slide" class="btn btn-primary"><i class="fa fa-plus-circle"></i></a></td>
									</tr>
								</tfoot>
							</table>
							<style>
								.alt + .alt{margin-top:8px; padding-top:8px; border-top: 1px solid #e1e1e1;}
								.alt *{border-radius: 0px !important;}
								.alt .input-group:first-child{width: 100%;}
								.alt .input-group:first-child .input-group-addon:not(.esc){padding: 0px;border: 0px;} 
								.alt .input-group:first-child .input-group-addon:first-child+*{width: calc(100% - 38px);} 
								.alt .input-group:first-child .input-group-addon:last-child{width: 38px;}
								.input-group + .input-group{margin-top:4px;}
								.alt .btn{height: 35px; vertical-align: middle;}
							</style> 
						</style>
						<script type="text/javascript">  
							var slide_count = <?= $j; ?>;
							function addBanner(){ 
								var slider = '<tr>'+ 
								'<td>'+ 
								'<a href="" id="thumb-slide-'+slide_count+'" data-toggle="image" class="img-thumbnail"  style="width:100%;" >'+
								'<img src="<?= $placeholder; ?>" data-placeholder="<?= $placeholder; ?>" style="width:100%;" />'+
								'</a>'+  
								'<input type="hidden" name="sliders['+slide_count+'][image]" value ="" id="input-slide-'+slide_count+'" />'+
								'</td>'+ 
								'<td id = "sliderDescriptionSection'+slide_count+'"  >'+
								'<div class="text-col-xs-12 alt text-right">'+
								'<a onclick="addDescription('+slide_count+');" class="btn btn-warning" >Add Text</a>'+
								'</div>'+
								''+
								'</td>'+
								'<td><a onclick="remove(this);" data-toggle="tooltip" title="Remove Breaking Point" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i></a></td>'+
								'</tr>';
								
								$("#bannerFirst").append(slider);  
								
								slide_count++;
							}
							
							function addDescription(slide_count){
								
								var sliderDescriptionSection = 								
								'<div class="text-col-xs-12 alt">'+
								'<div class="input-group">'+
								'<span class="input-group-addon esc">'+ 
								'<b>Format</b>'+
								'</span>'+
								'<span class="input-group-addon">'+
								'<select name=sliders['+slide_count+'][description][format][] class="form-control btn-primary" >'+
								'<option>Paragraph</option>'+ 
								<?php foreach($defination as $index => $each){ ?>
									'<option value="<?= $index; ?>"><?= $each; ?></option>'+
								<?php } ?>
								'</select>'+  
								'</span>'+ 
								'<span class="input-group-addon"><a onclick="remove2(this);" class="btn btn-danger"><i class="fa fa-times" aria-hidden="true"></i></a></span>'+
								'</div>'+ 
								
								<?php foreach($langs as $lang){ ?>
									'<span class="input-group col-xs-12" >'+ 
									'<span class="input-group-addon esc vmid">'+
									'<img src="language/<?php echo $lang['code']; ?>/<?php echo $lang['code']; ?>.png" title="<?php echo $lang['name']; ?>" />'+
									'</span>'+
									'<textarea value="" class="form-control"  name=sliders['+slide_count+'][description][<?= $lang['language_id']; ?>][name][]"></textarea>'+
									'</span>'+
								<?php } ?>
								'</div>';
								
								$('#sliderDescriptionSection'+slide_count).append(sliderDescriptionSection);
								
							}
						</script>
					</div>
					
					<!-- End Slider -->
					
				</div>
			</form>
		</div>
	</div>
</div>
</div>
<script type="text/javascript">
	function remove(element){
		$(element).parent().parent().remove();
	}	
	function remove2(element){
		$(element).parent().parent().parent().remove();
	}
</script>

<?php echo $footer; ?>																					