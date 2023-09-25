<?php if (!empty($sections)) { ?>
<?php usort($sections, function ($a, $b) { return strcmp($a['sort'], $b['sort']); }); ?>
<?php foreach($sections as $section) { ?>
<div class="panel-group" id="section<?php echo $section['index']; ?>" role="tablist" aria-multiselectable="true">
  <?php if ($section['title']){ ?>
    <h3><?php echo $section['title']; ?></h3>
  <?php } ?>
  <?php usort($section['groups'], function ($a, $b) { return strcmp($a['sort'], $b['sort']); }); ?>
  <?php foreach($section['groups'] as $key => $group){ ?>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#section<?php echo $section['index']; ?>" href="#item<?php echo $module; ?>-<?php echo $section['index']; ?>-<?php echo $group['id']; ?>">
          <?php echo $group['title']; ?>
        </a>
      </h4>
    </div>
    <div id="item<?php echo $module; ?>-<?php echo $section['index']; ?>-<?php echo $group['id']; ?>" class="panel-collapse collapse" role="tabpanel">
      <div class="panel-body">
        <?php echo $group['description']; ?>
      </div>
    </div>
  </div>
  <?php } ?>
 </div>
<?php } ?>
<?php } ?>