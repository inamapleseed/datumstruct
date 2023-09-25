<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<h1><?php echo $heading_title; ?></h1>
			<ul class="breadcrumb">
				<?php foreach ($breadcrumbs as $breadcrumb) { ?>
				<li>
					<a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
				</li>
				<?php } ?>
			</ul>
		</div>
	</div>
	<div class="container-fluid">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="fa fa-puzzle-piece"></i>
					<?php echo $text_list; ?></h3>
			</div>
			<div class="panel-body">
				<fieldset>
					<legend><?php echo $text_type; ?></legend>
					<ul id="extension-tab" class="nav nav-tabs">
						<?php foreach ($categories as $category) { ?>
						<li class <?php if ($type == $category['code']) { ?> ="active" <?php } ?>>
							<a href="#" data-value="<?php echo $category['href']; ?>" data-toggle="tab"><?php echo $category['text']; ?></a >
						</li>
						<?php } ?>
					</ul>
				</fieldset>
				<div id="extension"></div>
			</div>
		</div>
	</div>
	<?php if ($categories) { ?>
	<script type="text/javascript">
		<!--
		$("#extension-tab a").click(function (e) {
			e.preventDefault();
			$page = $(this).attr("data-value");
			$.ajax({
				url: $page,
				dataType: 'html',
				beforeSend: function () {
					$('.fa-filter').addClass('fa-circle-o-notch fa-spin');
					$('.fa-filter').removeClass('fa-filter');
				},
				complete: function () {
					$('.fa-circle-o-notch').addClass('fa-filter');
					$('.fa-circle-o-notch').removeClass('fa-circle-o-notch fa-spin');

				},
				success: function (html) {
					$('#extension').html(html);
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});

		$('#extension-tab li.active a').trigger('click');

		$('#extension').on('click', '.btn-success', function (e) {
			e.preventDefault();

			var node = this;

			$.ajax({
				url: $(node).attr('href'),
				dataType: 'html',
				beforeSend: function () {
					$(node).button('loading');
				},
				complete: function () {
					$(node).button('reset');
				},
				success: function (html) {
					$('#extension').html(html);
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		});

		$('#extension').on('click', '.btn-danger, .btn-warning', function (e) {
			e.preventDefault();

			if (confirm('<?php echo $text_confirm; ?>')) {
				var node = this;

				$.ajax({
					url: $(node).attr('href'),
					dataType: 'html',
					beforeSend: function () {
						$(node).button('loading');
					},
					complete: function () {
						$(node).button('reset');
					},
					success: function (html) {
						$('#extension').html(html);
					},
					error: function (xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
			}
		});

		//-->
	</script>
	<?php } ?>
</div>
<?php echo $footer; ?>
