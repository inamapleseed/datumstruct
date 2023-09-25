<div class="sorts">

    <div class="sorts_inner">
        <div class="form-group input-group input-group-sm">
          <div class=" select-style">
            <select id="input-limit" class="form-control no-custom" onchange="select_handler();">
            <?php foreach ($limits as $limits) { ?>
                <?php if ($limits['value'] == $limit) { ?>
                    <option value="<?php echo $limits['value']; ?>" selected="selected">View By</option>
                <?php } else { ?>
                    <option value="<?php echo $limits['value']; ?>"><?php echo $limits['text']; ?></option>
                <?php } ?>
            <?php } ?>
            </select>
          </div>  
        </div>

        <div class="form-group input-group input-group-sm custom-select">
          <div class=" select-style">
            <select id="input-sort" class="form-control no-custom" onchange="select_handler();">
            <?php foreach ($sorts as $sorts) { ?>
                <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                    <option value="<?php echo $sorts['value']; ?>" selected="selected">Sort By</option>
                <?php } else { ?>
                    <option value="<?php echo $sorts['value']; ?>"><?php echo $sorts['text']; ?></option>
                <?php } ?>
            <?php } ?>
            </select>
          </div>
        </div>        
    </div>
</div>