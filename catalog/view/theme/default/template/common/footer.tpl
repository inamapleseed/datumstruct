<div id="footer-area">
	<footer>
		<div class="contain">
			<div class="footer-upper-contet">

				<?php if ($menu) { ?>
					<?php foreach($menu as $links){ ?>
						<li>
							<a href="<?=$links['href'];?>">
								<?php if($links['href'] != '#'){ ?>
								<?= $links['name']; ?>
								<?php }else{ ?>
								<a href="<?= $links['href']; ?>" 
									<?php if($links['new_tab']){ ?>
										target="_blank"
									<?php } ?>
									>
									<?= $links['name']; ?></a>
								<?php } ?>
							</a href="<?=$links['href'];?>">
						</li>
						<?php if($links['child']){ ?>
						<ul class="list-unstyled">
							<?php foreach ($links['child'] as $each) { ?>
							<li><a href="<?= $each['href']; ?>"
								<?php if($each['new_tab']){ ?>
									target="_blank"
								<?php } ?>
								>
									<?= $each['name']; ?></a></li>
							<?php } ?>
						</ul>
						<?php } ?>
					<?php } ?>
				<?php } ?>

			</div>
			
			<hr/>
			<div class="lower-footer">
				<div class="p">
					<p>&copy; 2019 DATUMSTRUCT Pte Ltd. All Rights Reserved.</p>
				</div>
				<div class="fcs " style="display: none">
					<a target="_blank" href="https://www.firstcom.com.sg/">Web Design</a>&nbsp;<p>by FirstCom Solutions</p>
				</div>
			</div>
		</div>
	</footer>
</div>
<div id="ToTopHover" ></div>
<script>
	<?php if ($humanscale) { ?>
		$('#footer-area').addClass('d-none');
	<?php } ?>
</script>

<script>
    $(document).ready(function(){
        AOS.init({
        	once: true
        });
    });
</script>
<?php 
/* extension bganycombi - Buy Any Get Any Product Combination Pack */
echo $bganycombi_module; 
?>
</body></html>