<div class="joiner joiner_<?= $uqid; ?> col-xs-12 section">
	<div class="row">
		<div class="table_heading text-center">
			<h2 class="text-center">
				<span>
					<?= $heading_title; ?>
				</span>
			</h2>
			<div class="pointer arrow_left pull-left ready" onclick="$('.joiner_<?= $uqid; ?> .tab-pane.active .arrow_left').click();" ></div>
			<div class="pointer arrow_right pull-right ready" onclick="$('.joiner_<?= $uqid; ?> .tab-pane.active .arrow_right').click();" ></div>
		</div>

		<div class="join_content">
			<ul class="nav nav_custom inline-list text-center">
				<?php foreach ($modules as $index => $module) { ?>
				<li class="<?= !$index?'active':''; ?> t18">
					<a data-toggle="tab" href="#mj_<?= $uqid; ?>_<?= $index; ?>">
						<?= $module['tab']; ?>
					</a>
				</li>
				<?php } ?>
			</ul>
			<div class="tab-content">
				<?php foreach ($modules as $index=> $module) { ?>
				<div id="mj_<?= $uqid; ?>_<?= $index; ?>" class="tab-pane fade in <?= !$index?'active':''; ?>">
					<?= $module['module']; ?>
					<?php if( $module['href'] ){ ?>
					<div class="col-xs-12 text-center join_uni_url">
						<a class="btn btn-primary inline-block-i active" href="<?= $module['href']; ?>"><?= $btn_view; ?></a>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<!--module joiner row-->