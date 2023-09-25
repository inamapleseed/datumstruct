<div id="side_filter" class="panel panel-default">
  
    <?php foreach ($filter_groups as $filter_group) { ?>
    <div class="list-group">
      <div class="list-group-item item-header"><?php echo $filter_group['name']; ?></div>
      <div class="list-group-item">
        <div id="filter-group<?php echo $filter_group['filter_group_id']; ?>">
          <?php foreach ($filter_group['filter'] as $filter) { ?>
          <div class="checkbox">
            <label onmouseup="catchFilter();" >
              <?php if (in_array($filter['filter_id'], $filter_category)) { ?>
              <input type="checkbox" name="filter[]" value="<?php echo $filter['filter_id']; ?>" checked="checked" />
              <?php echo $filter['name']; ?>
              <?php } else { ?>
              <input type="checkbox" name="filter[]" value="<?php echo $filter['filter_id']; ?>" />
              <?php echo $filter['name']; ?>
              <?php } ?>
            </label>
          </div>
          <?php } ?>
        </div>
      </div>
    </div>
    <?php } ?>
  
</div>