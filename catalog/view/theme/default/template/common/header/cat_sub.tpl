<?php if ($sub_items) {
	foreach ($sub_items as $sub_item) {
		if ($sub_item['categories']) { ?>
			<div id="<?= $sub_item['id'];?>" data-hover="<?= $sub_item['id'];?>" class="menu-sub">
				<div class="container">
					<div class="row" id="cat-sub-container">
						<div class="cat-sub-col">
						<?php foreach (array_chunk($sub_item['categories'], ceil(count($sub_item['categories']) / 5)) as $categories) { ?>
								<?php foreach ($categories as $category) { //1st level
									if (!$category['child']) {//if no sub?>
					          			<ul class="list-unstyled">
											<li class="ncat">
												<h4>
													<a href="<?= $category['href']; ?>"><?= $category['name']; ?>
													</a>
												</h4>
											</li>
					          			</ul>
				    				<?php } else {?>
				          			<ul class="list-unstyled">
										<li>
				            				<h4>
				            					<a href="<?= $category['href']; ?>">
				            						<?= $category['name']; ?>
			            						</a>
				            					</h4>
				            				</li>
				            			<!-- <li><span>Shop By</span></li>
				            			<li><a href="<?= $category['href']; ?>">View All <?= $category['name']; ?></a></li> -->
				    					<?php foreach ($category['child'] as $category2) {?>
					            			<li class="sub-2">
					            				<a href="<?= $category2['href']; ?>">
					            					<?= $category2['name']; ?>
					            				</a>
					            			</li>
						    			<?php }?>
				          			</ul>
									<?php } ?>
								<?php } ?>
							<?php }?>
				        </div>
					</div>
				</div>
			</div>
		<?php
		}
	}
}?>
