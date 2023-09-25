<div class="row">
    <div class="col-md-4">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-user"></i> <?php echo $license_your_license; ?></h3>
            </div>
            <div class="panel-body">
            <?php if (empty($data['ExcelPort']['LicensedOn'])) : ?>
                <div class="form-group">
                    <label for="moduleLicense"><?php echo $license_enter_code; ?></label>
                    <div class="licenseAlerts"></div>
                    <div class="licenseDiv"></div>
                    <input type="text" class="licenseCodeBox form-control" placeholder="<?php echo $license_placeholder; ?>" value="<?php echo !empty($license_code) ? $license_code : ''; ?>" />
                </div>

                <div class="form-group">
                    <button type="button" class="btn btn-success btnActivateLicense"><i class="icon-ok"></i> <?php echo $license_activate; ?></button>
                    <div class="pull-right">
                        <button type="button" class="btn btn-link small-link" onclick="window.open('http://isenselabs.com/users/purchases/')"><?php echo $license_get_code; ?> <i class="fa fa-external-link"></i></button>
                    </div>
                </div>
                        
                <?php 
                    $hostname = !empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '' ;
                    $hostname = strstr($hostname, 'http://') === false ? 'http://' . $hostname : $hostname;
                ?>
                <script type="text/javascript">
                    var domain = '<?php echo base64_encode($hostname); ?>';
                    var domainraw = '<?php echo $hostname; ?>';
                    var timenow = <?php echo time(); ?>;
                    var MID = 'TBY2PJCCI7';
                </script>
                <script type="text/javascript" src="//isenselabs.com/external/validate/"></script>

            <?php endif; ?>

            <?php if (!empty($data['ExcelPort']['LicensedOn'])): ?>
                <input name="cHRpbWl6YXRpb24ef4fe" type="hidden" value="<?php echo base64_encode(json_encode($data['ExcelPort']['License'])); ?>" />
                <input name="OaXRyb1BhY2sgLSBDb21" type="hidden" value="<?php echo $data['ExcelPort']['LicensedOn']; ?>" />

                <div class="row">
                    <label class="license_label"><?php echo $license_holder; ?></label>
                    <span class="license_info"><?php echo $data['ExcelPort']['License']['customerName']; ?></span>
                </div>
                <div class="row">
                    <label class="license_label"><?php echo $license_registered_domains; ?></label>
                    <ul class="license_info">
                        <?php foreach ($data['ExcelPort']['License']['licenseDomainsUsed'] as $domain): ?>
                            <li><i class="fa fa-check"></i>&nbsp;<?php echo $domain; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="row">
                    <label class="license_label"><?php echo $license_expires; ?></label>
                    <span class="license_info"><?php echo date("F j, Y", strtotime($data['ExcelPort']['License']['licenseExpireDate'])); ?></span>

                    <div class="alert alert-success center"><?php echo $license_valid; ?> [<a href="http://isenselabs.com/users/purchases" target="_blank"><?php echo $license_manage; ?></a>]</div>
                </div>
            <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-users"></i> <?php echo $license_get_support; ?></h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="thumbnail">
                            <img alt="<?php echo $license_community; ?>" src="view/image/excelport/community.png">
                            <div class="caption center">
                                <h3><?php echo $license_community; ?></h3>
                                <p><?php echo $license_community_info; ?></p>
                                <p style="padding-top: 5px;"><a href="http://isenselabs.com/forum" target="_blank" class="btn btn-lg btn-default"><?php echo $license_forums; ?></a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail">
                            <img data-src="holder.js/300x200" alt="Ticket support" src="view/image/excelport/tickets.png">
                            <div class="caption center">
                                <h3><?php echo $license_tickets; ?></h3>
                                <p><?php echo $license_tickets_info; ?></p>
                                <p style="padding-top: 5px;"><a href="http://isenselabs.com/tickets/open/<?php echo base64_encode('Support Request').'/'.base64_encode('179').'/'. base64_encode($_SERVER['SERVER_NAME']); ?>" target="_blank" class="btn btn-lg btn-default"><?php echo $license_tickets_open; ?></a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail">
                            <img alt="<?php echo $license_presale; ?>" src="view/image/excelport/pre-sale.png">
                            <div class="caption center">
                                <h3><?php echo $license_presale; ?></h3>
                                <p><?php echo $license_presale_info; ?></p>
                                <p style="padding-top: 5px;"><a href="https://isenselabs.com/pages/premium-services" target="_blank" class="btn btn-lg btn-default"><?php echo $license_presale_bump; ?></a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>