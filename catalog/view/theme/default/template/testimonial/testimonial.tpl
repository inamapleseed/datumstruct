<?= $header; ?>
<div class="container">
    <?= $content_top; ?>
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <div class="row"><?= $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?= $class; ?>">
            <h2><?= $heading_title; ?></h2>

            <div class="testimonial_view">
                <?php foreach($testimonials as $testimonial){ ?>
                    <div class="testimonial_block">
                        <span class="review-author"><?= $testimonial['author']; ?></span>
                        <span class="testimonial-date-added"><?= $testimonial['date_added']; ?></span>
                        <div class="rating">
                            <?php for ($i = 1; $i <= 5; $i++) { ?>
                                <?php if ($testimonial['rating'] < $i) { ?>
                                <span class="fa fa-stack">
                                    <i class="fa fa-star-o fa-stack-2x" style='color: #FC0;'></i>
                                </span>
                                <?php } else { ?>
                                    <span class="fa fa-stack">
                                        <i class="fa fa-star fa-stack-2x" style='color: #FC0;'></i>
                                        <i class="fa fa-star-o fa-stack-2x" style='color: #E69500;'></i>
                                    </span>
                                <?php } ?>
                            <?php } ?>
                        </div>
                        <p><?= $testimonial['description']; ?></p>
                    </div>
                <?php } ?>
            </div>
            <div class="testimonial_pagination text-center">
                <?= $pagination; ?>
            </div>

            <?php if ($review_status) { ?>
            <hr/>
            <form class="form-horizontal" id="form-review">
                
                <?php if ($review_guest) { ?>
                    
                <h2><?= $text_write; ?></h2>
                <div class="form-group required">
                    <div class="col-sm-12">
                        <label class="control-label" for="input-name"><?= $entry_name; ?></label>
                        <input type="text" name="name" value="<?= $customer_name; ?>" id="input-name" class="form-control"/>
                    </div>
                </div>

                <div class="form-group required">
                    <div class="col-sm-12">
                        <label class="control-label" for="input-review"><?= $entry_review; ?></label>
                        <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>

                        <div class="help-block"><?= $text_note; ?></div>
                    </div>
                </div>

                <div class="form-group required">
                    <div class="col-sm-12">
                        <label class="control-label"><?= $entry_rating; ?></label>
                        &nbsp;&nbsp;&nbsp; <?= $entry_bad; ?>&nbsp;
                        <input type="radio" name="rating" value="1" />
                        &nbsp;
                        <input type="radio" name="rating" value="2" />
                        &nbsp;
                        <input type="radio" name="rating" value="3" />
                        &nbsp;
                        <input type="radio" name="rating" value="4" />
                        &nbsp;
                        <input type="radio" name="rating" value="5" />
                        &nbsp;<?= $entry_good; ?></div>
                </div>
                <?php if (isset($site_key) && $site_key) { ?>
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="g-recaptcha" data-sitekey="<?= $site_key; ?>"></div>
                    </div>
                </div>
                <?php } elseif(isset($captcha) && $captcha){ ?>
                <?= $captcha; ?>
                <?php } ?>
                <div class="buttons clearfix">
                    <div class="pull-right">
                        <button type="button" id="button-review" data-loading-text="<?= $text_loading; ?>"
                                class="btn btn-primary"><?= $button_continue; ?></button>
                    </div>
                </div>
                <?php } else { ?>
                <?= $text_login; ?>
                <?php } ?>
                
            </form>
            <?php } ?>
            </div>
        <?= $column_right; ?></div>
        <?= $content_bottom; ?>

    <script type="text/javascript"><!--
        $('#button-review').on('click', function () {
            $.ajax({
                url: '<?= html($write); ?>',
                type: 'post',
                dataType: 'json',
                data:  $("#form-review").serialize(),
                beforeSend: function () {
                    if ($("textarea").is("#g-recaptcha-response")) {
                        grecaptcha.reset();
                    }
                    $('#button-review').button('loading');
                },
                complete: function () {
                    $('#button-review').button('reset');
                },
                success: function (json) {
                    $('.alert-success, .alert-danger').remove();
                    if (json['error']) {
                        $('#form-review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                    }
                    if (json['success']) {
                        $('#form-review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

                        $('input[name=\'name\']').val('');
                        $('textarea[name=\'text\']').val('');
                        $('input[name=\'rating\']:checked').prop('checked', false);
                    }
                }
            });
        });
        //--></script>
</div>
<?= $footer; ?>