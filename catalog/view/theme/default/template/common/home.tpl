<?php echo $header; ?>
    <div class="container main-bg">
        <div class="row"><?php echo $column_left; ?>
            <?php if ($column_left && $column_right) { ?>
            <?php $class = 'col-sm-6'; ?>
            <?php } elseif ($column_left || $column_right) { ?>
            <?php $class = 'col-sm-9'; ?>
            <?php } else { ?>
            <?php $class = 'col-sm-12'; ?>
            <?php } ?>
            <div id="content" class="<?php echo $class; ?>">
                <?php echo $content_top; ?>
                <div class="h2">
                    <h2 data-aos="fade-right">Reliable • Flexible • Consistent</h2>
                </div>
                <div class="landing-icon" data-aos="flip-right">
                    <div>
                        <img alt="image" src="image/catalog/ds/homepage/ideal-icon-min.png">
                        <p class="landing-idesc">Ideal Workspace</p>
                    </div>
               </div> 
            </div>
        </div>
    </div>
<?php echo $content_bottom; ?>
<?php echo $footer; ?>