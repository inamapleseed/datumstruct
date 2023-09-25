<?php if($warning){ ?>
    <div class="alert alert-warning">
        <?= $warning; ?>
    </div>
<?php } ?>
<?php if($success){ ?>
    <div class="alert alert-success">
        <?= $success; ?>
    </div>
<?php } ?>

<fieldset>
    <form action="<?= $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
    <input type="submit" value="Submit" class="pull-right btn btn-success-2" />
    <legend> 
        <p><?= $heading_title; ?></p>
    </legend>
    <ul class="nav nav-tabs nav-justified">
        <li class="active"><a data-toggle="tab" href="#standard">Standard</a></li>
        <li><a data-toggle="tab" href="#express">Express</a></li>
    </ul>
    <div class="tab-content">
            <div id="standard" class="tab-pane fade in active">
                <div class="row">
                    <div class="col-xs-12">
                        <p>Days In Advance</p>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="input-group">
                            <div class="input-group-addon">Min</div>
                            <input type="number" name="timeslot_standard_min" value="<?= $timeslot_standard_min; ?>" class="form-control" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="input-group">
                            <div class="input-group-addon">Max</div>
                            <input type="number" name="timeslot_standard_max" value="<?= $timeslot_standard_max; ?>" class="form-control" />
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="table-responsive">
                    <table class="table table-bordered" >
                        <thead>
                            <tr>
                                <td>Delivery Time</td>
                                <td>Hours before Delivery Time</td>
                                <td>Displayed Delivery Time</td>
                                <td>Additional Cost</td>
                                <td width="1px" >Action</td>
                            </tr>
                        </thead>
                        <tbody id="standard_body" >
                            <?php $s=0; ?>
                            <?php foreach($timeslot as $slot){ ?>
                                <?php if($slot['type'] == 'standard'){ ?>
                                    <tr>
                                        <td><input type="text" value="<?= $slot['delivery_time']; ?>"               name="timeslot[standard][<?= $s; ?>][delivery_time]" class="form-control" /></td>
                                        <td><input type="text" value="<?= $slot['hours_before_delivery_time']; ?>"  name="timeslot[standard][<?= $s; ?>][hours_before_delivery_time]" class="form-control" /></td>
                                        <td><input type="text" value="<?= $slot['displayed_delivery_time']; ?>"     name="timeslot[standard][<?= $s; ?>][displayed_delivery_time]" class="form-control" /></td>
                                        <td><input type="text" value="<?= $slot['additional_cost']; ?>"             name="timeslot[standard][<?= $s; ?>][additional_cost]" class="form-control" /></td>
                                        <td><button type="button" onclick="$(this).parents('tr').remove();" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Remove"><i class="fa fa-minus-circle"></i></button></td>
                                    </tr>
                                <?php $s++; ?>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" ></td>
                                <td>
                                    <button type="button" onclick="addTimeslot('standard');" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Add Standard Timeslot"><i class="fa fa-plus-circle"></i></button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div id="express" class="tab-pane fade">

                <div class="row">
                    <div class="col-xs-12">
                        <p>Days In Advance</p>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="input-group">
                            <div class="input-group-addon">Min</div>
                            <input type="number" name="timeslot_express_min" value="<?= $timeslot_express_min; ?>" class="form-control" />
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="input-group">
                            <div class="input-group-addon">Max</div>
                            <input type="number" name="timeslot_express_max" value="<?= $timeslot_express_max; ?>" class="form-control" />
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="table-responsive">
                    <table class="table table-bordered" >
                        <thead>
                            <tr>
                                <td>Delivery Time</td>
                                <td>Hours before Delivery Time</td>
                                <td>Displayed Delivery Time</td>
                                <td>Additional Cost</td>
                                <td width="1px" >Action</td>
                            </tr>
                        </thead>
                        <tbody id="express_body" >
                            <?php $e = 0; ?>
                             <?php foreach($timeslot as $slot){ ?>
                                <?php if($slot['type'] == 'express'){ ?>
                                    <tr>
                                        <td><input type="text" value="<?= $slot['delivery_time']; ?>"               name="timeslot[express][<?= $e; ?>][delivery_time]" class="form-control" /></td>
                                        <td><input type="text" value="<?= $slot['hours_before_delivery_time']; ?>"  name="timeslot[express][<?= $e; ?>][hours_before_delivery_time]" class="form-control" /></td>
                                        <td><input type="text" value="<?= $slot['displayed_delivery_time']; ?>"     name="timeslot[express][<?= $e; ?>][displayed_delivery_time]" class="form-control" /></td>
                                        <td><input type="text" value="<?= $slot['additional_cost']; ?>"             name="timeslot[express][<?= $e; ?>][additional_cost]" class="form-control" /></td>
                                        <td><button type="button" onclick="$(this).parents('tr').remove();" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Remove"><i class="fa fa-minus-circle"></i></button></td>
                                    </tr>
                                <?php $e++; ?>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="4" ></td>
                                <td>
                                    <button type="button" onclick="addTimeslot('express');" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="Add Express Timeslot"><i class="fa fa-plus-circle"></i></button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
    </div>
    </form>
</fieldset>
<script type="text/javascript">
    var s = <?= $s; ?>;

    var e = <?= $e; ?>;

    function addTimeslot($type){
        if(!$type) return false;
        
        i = s;

        if($type=='express'){
          i = e;  
        }
        
        var html = "";
        html += "<tr>";
        html += "   <td><input type='text' name='timeslot["+$type+"]["+i+"][delivery_time]' value='' class='form-control' /></td>";
        html += "   <td><input type='text' name='timeslot["+$type+"]["+i+"][hours_before_delivery_time]' value='' class='form-control' /></td>";
        html += "   <td><input type='text' name='timeslot["+$type+"]["+i+"][displayed_delivery_time]' value='' class='form-control' /></td>";
        html += "   <td><input type='text' name='timeslot["+$type+"]["+i+"][additional_cost]' value='' class='form-control' /></td>";
        html += "   <td>";
        html += '       <button type="button" onclick="$(this).parents(\'tr\').remove();" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Remove"><i class="fa fa-minus-circle"></i></button>';
        html += "   </td>";
        html += "</tr>";

        var id_body = '#' + $type + '_body';

        $(id_body).append(html);
        
        if($type=='express'){
            e++;
        }
        else{
            s++;
        }
        
    }
</script>