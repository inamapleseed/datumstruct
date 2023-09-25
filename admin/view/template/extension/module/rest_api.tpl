<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-rest_api" data-toggle="tooltip" title="<?php echo $button_save; ?>"
                        class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>"
                   class="btn btn-default"><i class="fa fa-reply"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if ($install_success) { ?>
        <div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> <?php echo $install_success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-rest_api"
                      class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                        <div class="col-sm-10">
                            <select name="rest_api_status" id="input-status" class="form-control">
                                <?php if ($rest_api_status) { ?>
                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                <option value="0"><?php echo $text_disabled; ?></option>
                                <?php } else { ?>
                                <option value="1"><?php echo $text_enabled; ?></option>
                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-entry-client_id">
              <span data-toggle="tooltip" title="" data-original-title="<?php echo $text_client_id; ?>">
                <?php echo $entry_client_id; ?>
              </span>
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="rest_api_client_id" value="<?php echo $rest_api_client_id; ?>"
                                   required="required"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-entry-client_secret">
                          <span data-toggle="tooltip" title="" data-original-title="<?php echo $text_client_secret; ?>">
                            <?php echo $entry_client_secret; ?>
                          </span>
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="rest_api_client_secret"
                                   value="<?php echo $rest_api_client_secret; ?>" required="required"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-entry-order-id">
                          <span data-toggle="tooltip" title="" data-original-title="<?php echo $text_basic_token; ?>">
                            <?php echo $entry_basic_token; ?>
                          </span>
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control basic_token" id="input-key" type="text" name="basic_token"
                                   value="<?php echo $basic_token; ?>" readonly />
                            <br>
                            <button type="button" id="button-generate" class="btn btn-primary"><i class="fa fa-refresh"></i> <?php echo $button_generate_basic_token; ?></button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-entry-token_ttl">
                  <span data-toggle="tooltip" title="" data-original-title="<?php echo $text_token_ttl; ?>">
                    <?php echo $entry_token_ttl; ?>
                  </span>
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="rest_api_token_ttl" value="<?php echo $rest_api_token_ttl; ?>"
                                   required="required"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label" for="input-entry-order-id">
                  <span data-toggle="tooltip" title="" data-original-title="<?php echo $text_order_id; ?>"
                        required="required">
                    <?php echo $entry_order_id; ?>
                  </span>
                        </label>
                        <div class="col-sm-10">
                            <input class="form-control" type="text" name="rest_api_order_id" value="<?php echo $rest_api_order_id; ?>"
                                   required="required"/>
                        </div>
                    </div>
                </form>
                <div class="alert alert-info">
                    <h4><i class="fa fa-info"></i> Info</h4>
                    <p>Please follow the instructions in install.txt to install REST API extension.</p>
                    <p>If you need help please check out our <a
                                href="https://opencart-api.com/opencart-rest-api-documentations/?utm=oauth_shopping"
                                target="_blank" class="alert-link">Documentation</a>
                        - You will find walkthrough <a href="https://opencart-api.com/tutorial/?utm=oauth_shopping"
                                                       target="_blank" class="alert-link">videos</a>,
                        <a href="https://opencart-api.com/faqs/?utm=oauth_shopping" target="_blank" class="alert-link">FAQs</a>,
                        <a href="https://opencart-api.com/forum/?utm=oauth_shopping" target="_blank" class="alert-link">forum</a>
                        and more.
                    </p>
                    <p>
                        You can find working PHP demo scripts <a
                                href="https://opencart-api.com/opencart-rest-api-demo-clients/?utm=oauth_shopping"
                                target="_blank" class="alert-link">here</a>.
                    </p>
                    <p>
                        If you have any questions about the extension, please do not hesitate to <a
                                href="https://opencart-api.com/contact/?utm=oauth_shopping" target="_blank"
                                class="alert-link">contact us</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
    $('#button-generate').on('click', function() {
        var client_id = $('[name=rest_api_client_id]').val();
        var client_secret = $('[name=rest_api_client_secret]').val();
        if(client_id.length < 1 || client_secret.length < 1) {
            alert("Client ID and client secret are required!");
        } else {
            var b64 = btoa(client_id+":"+client_secret);
            $('.basic_token').val(b64);
        }
    });
    //--></script>
<?php echo $footer; ?>
