<div id="about" data-aos="zoom-in">	
	<div class="tab">
		<?php foreach ($items as $i => $item): ?>
			<a  class="tablinks" onclick="openTab(event, 'tab_id_<?= $i?>')" id="defaultOpen"><?= $item['tab']?></a>
		<?php endforeach ?>
	</div>

	<?php foreach ($items as $i => $item): ?>
		<div  id="tab_id_<?= $i ?>" class="tabcontent">
			<div class="tabdesc">
				<?= html_entity_decode($item['desc'], ENT_QUOTES, 'UTF-8') ?>
			</div>
			<?php if (strpos($item['image'], 'no_image-100x100.png') == false ): ?>
				<img alt="<?=$item['tab'];?>" id="tab_id_<?= $i ?>" src="image/<?= $item['image']?>">
			<?php endif ?>
		</div>
	<?php endforeach ?>
</div>

<script>
  function openTab(evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
   
  }

    // aiezscript
    var hash = window.location.hash;
      if(hash) {
        $('.tabcontent').each(function(){
        	$(this).hide();
        });
        $(hash).show();
      } else {
      	document.getElementById("defaultOpen").click();	
    }
</script>


