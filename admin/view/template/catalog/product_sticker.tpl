<button 
    type="button" 
    title="<?= $text_sticker; ?>" 
    class="btn btn-success" 
    data-toggle="modal" 
    data-target="#sticker_modal"
    ><?= $text_sticker; ?></button>
<div id="sticker_modal" class="modal fade">
    <style>
    .modal-xlg{
        width: calc(100vw - 30px);
        max-width: 1480px;
    }

    #sticker_modal h3{
        margin: 0px;
    }

    .valign{
        vertical-align: middle;
    }

    .valign[type="radio"]{
        position: relative;
        top: -1px;
    }

    .fake:hover,
    .fake{
        border-top-color: transparent;
        border-right-color: transparent;
        border-bottom-color: transparent;
        box-shadow: none;
    }
    .custom-input-group-addon:first-child {
        border-right: 0;
    }
    .custom-input-group-addon:first-child{
        border-bottom-right-radius: 0;
        border-top-right-radius: 0;
    }
    .custom-input-group-addon{
        padding: 8px 13px;
        font-size: 12px;
        font-weight: normal;
        line-height: 1;
        color: #555;
        text-align: center;
        background-color: #eee;
        border: 1px solid #ccc;
        border-radius: 3px;
        width: 1%;
        white-space: nowrap;
        vertical-align: middle;
        display: table-cell;
    }
    .select2 {
        width: 100% !important;
    }
    
    #sticker_modal .well{
        display: block;
    }

    #sticker_modal .label{
        font-size:14px;
        font-weight: 400;
        display: inline-block;
        margin: 0px 6px 10px 0px;
        box-shadow: 0px 0px 6px rgba(0,0,0,0.2);

        transition: all 0.3s;

        -webkit-animation: BLINKING_BACKGROUND 0.8s forwards;  /* Safari 4+ */
        -moz-animation: BLINKING_BACKGROUND 0.8s forwards;  /* Fx 5+ */
        -o-animation: BLINKING_BACKGROUND 0.8s forwards;  /* Opera 12+ */
        animation: BLINKING_BACKGROUND 0.8s forwards;  /* IE 10+, Fx 29+ */
        cursor: pointer;
        line-height: 1.8;
    }

    #sticker_modal .label.added_before{
        -webkit-animation: BLINKING_BACKGROUND_ADDED_BEFORE 0.8s forwards;  /* Safari 4+ */
        -moz-animation: BLINKING_BACKGROUND_ADDED_BEFORE 0.8s forwards;  /* Fx 5+ */
        -o-animation: BLINKING_BACKGROUND_ADDED_BEFORE 0.8s forwards;  /* Opera 12+ */
        animation: BLINKING_BACKGROUND_ADDED_BEFORE 0.8s forwards;  /* IE 10+, Fx 29+ */
    }

    #sticker_modal .label:hover{
        box-shadow: 0px 0px 10px rgba(0,0,0,0.7);
    }

    #sticker_modal .label i{    cursor: pointer;    }
    .well{ padding-right: 9px; }

    /*0 - 50 - 100*/
    @keyframes BLINKING_BACKGROUND{
        0%, 100% {
            background-color: #777;
        }
        50% {
            background-color: #5cb85c;
        }
    }
    @keyframes BLINKING_BACKGROUND_ADDED_BEFORE{
        0%, 100% {
            background-color: #777;
        }
        50% {
            background-color: #e2322d;
        }
    }
    .select2-results__options > li{
        height: 29px;
    }

    .select2-container .select2-selection--single{
        height: 34px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered{
        line-height: 33px;
    }
    </style>
    <div class="modal-dialog modal-xlg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true" style="font-size: 24px;">&times;</button>
                <h3><?= $text_sticker; ?> v1.0</h3>
            </div>
            <div class="modal-body">
                <div class="alert alert-info sticky">
                    <i class="fa fa-exclamation-circle" ></i> If product were tagged into multiple stickers, only the last will apply
                </div>
                <div class="alert alert-info sticky">
                    <i class="fa fa-exclamation-circle"></i> Select product form dropdown to add to sticker
                </div>

                <table class="table table-bordered">
                    <thead>
                        <td width="300px">Sticker Settings</td>
                        <td width="115px" class="hidden-">
                            <span data-toggle="tooltip" data-html="true" title="<?= $text_sticker_image_help; ?>"><?= $text_sticker_image; ?></span>
                        </td>
                        <td width="140px" class="hidden">
                            <span data-toggle="tooltip" data-html="true" title="<?= $text_sticker_duration_help; ?>"><?= $text_sticker_duration; ?></span>
                        </td>
                        <td>Product(s)</td>
                        <td width="1px">
                            <button class="btn btn-primary" type="button" onclick="addSticker();" >
                                <i class="fa fa-plus-circle"></i>
                            </button>
                        </td>
                    </thead>
                    <tbody>
                        <?php $i = 0; ?>
                        <?php foreach($stickers as $sticker){ ?>
                        <tr>
                            <td>
                                <?php foreach($languages as $language) { ?>
                                <div class="input-group">
                                    <span class="custom-input-group-addon">
                                        <img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />
                                    </span>
                                    <input type="text" class="form-control" name="sticker[<?= $i; ?>][name][<?php echo $language['language_id']; ?>]"
                                        placeholder="<?= $text_sticker_text; ?>"
                                        value="<?= isset($sticker['name'][$language['language_id']])?$sticker['name'][$language['language_id']]:''; ?>"
                                        />
                                </div>
                                <hr />
                                <?php } ?>
                                <hr class="hidden-" />
                                <div class="input-group hidden">
                                    <span class="custom-input-group-addon control-label" data-toggle="tooltip" title="<?= $text_sticker_percentage_help; ?>">
                                        <?= $text_sticker_percentage; ?></span>
                                    <div class="form-control fake">
                                        <label><input checked type="radio" class="valign" name="sticker[<?= $i; ?>][percentage]" value="0" />
                                            <span class="valign">
                                                <?= $text_sticker_percentage_off; ?></span></label>&nbsp;+
                                        <label><input type="radio" class="valign" name="sticker[<?= $i; ?>][percentage]" value="1" />
                                            <span class="valign">
                                                <?= $text_sticker_percentage_on; ?></span></label>
                                    </div>
                                </div>
                                
                                <hr />
                                <div class="input-group color-picker">
                                    <div class="custom-input-group-addon">
                                        <?= $text_sticker_label; ?>
                                    </div>
                                    <input type="text" name="sticker[<?= $i; ?>][label_color]" class=" form-control" value="<?= $sticker['label_color']; ?>">
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                        
                                <hr />
                                <div class="input-group color-picker">
                                    <div class="custom-input-group-addon">
                                        <?= $text_sticker_sticker; ?>
                                    </div>
                                    <input type="text" name="sticker[<?= $i; ?>][sticker_color]" class=" form-control" value="<?= $sticker['sticker_color']; ?>">
                                    <span class="input-group-addon"><i></i></span>
                                </div>
                            </td>
                        
                            <td class="hidden-">
                                <a href="" id="thumb-image-<?= $i; ?>" data-toggle="image" class="img-thumbnail">
                                    <img src="<?= $placeholder; ?>" alt="" title="" data-placeholder="<?= $placeholder; ?>" class="img-responsive" />
                                </a>
                                <input type="hidden" name="sticker[<?= $i; ?>][image]" value="" id="input-image-<?= $i; ?>" />
                            </td>
                            <td class="hidden">
                                <input type="number" name="sticker[<?= $i; ?>][duration]" class="form-control" value="0">
                            </td>
                            <td>
                                <select class="sticker-product-tag-<?= $i; ?>" data-index="<?= $i; ?>">
                                    <option></option>
                                    <?php foreach($product_list as $each){ ?>
                                    <option value="<?= $each['product_id']; ?>">
                                        <?= $each['name']; ?>
                                    </option>
                                    <?php } ?>
                                </select>
                                <div class="well identify-well-<?= $i; ?>" style="height: 130px; overflow: auto; margin: 10px 0px 0px;">
                                    <?php foreach($sticker['products'] as $product){ ?>
                                        <label class="label label-default indentity-<?= $product['product_id']; ?>">
                                            <i class="fa fa-minus-circle" onclick="$(this).parent().remove();"></i> <?= $product['name']; ?>
                                            <input type="hidden" name="sticker[<?= $i; ?>][products][]" value="<?= $product['product_id']; ?>" />
                                        </label>
                                    <?php } ?>
                                </div>
                            </td>
                            <td>
                                <button type="button" onclick="$(this.parentNode.parentNode).remove();" class="btn btn-danger">
                                    <i class="fa fa-minus-circle"></i>
                                </button>
                            </td>
                        </tr>
                        <?php $i++; } ?>
                    </tbody>
                </table>
                <hr/>
                <div class="text-right">
                    <button id="saveSticker" type="button" class="btn btn-primary" ><?= $button_submit; ?></button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('.color-picker').colorpicker();

        var index   =   <?= count($stickers); ?>;
        var tr_html =   '<tr>'+
                        '   <td>'+
                        <?php foreach($languages as $language) { ?>
                        '   <div class="input-group">'+
                        '       <span class="custom-input-group-addon">'+
                        '           <img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" />'+
                        '       </span>'+
                        '       <input type="text" class="form-control" name="sticker[INX][name][<?php echo $language['language_id']; ?>]" placeholder="<?= $text_sticker_text; ?>" />'+
                        '   </div>'+
                        '   <hr/>'+
                        <?php } ?>
                        '       <hr class="hidden-" />'+
                        '       <div class="input-group hidden">' +
                        '           <span class="custom-input-group-addon control-label" data-toggle="tooltip" title="<?= $text_sticker_percentage_help; ?>"><?= $text_sticker_percentage; ?></span>' +
                        '           <div class="form-control fake">'+
                        '               <label><input checked type="radio" class="valign" name="sticker[INX][percentage]" value="0" /> '+
                        '                   <span class="valign"><?= $text_sticker_percentage_off; ?></span></label>&nbsp;' +
                        '               <label><input type="radio" class="valign" name="sticker[INX][percentage]" value="1" /> '+
                        '                   <span class="valign"><?= $text_sticker_percentage_on; ?></span></label>' +
                        '           </div>'+
                        '       </div>'+
                        '       <hr/>' +
                        '       <div class="input-group color-picker">'+
                        '           <div class="custom-input-group-addon" ><?= $text_sticker_label; ?></div>'+    
                        '           <input type="text" name="sticker[INX][label_color]" class=" form-control" value="#ffffff">'+
                        '<span class="input-group-addon"><i></i></span>'+
                        '       </div>'+
                        
                        '       <hr/>'+
                        '       <div class="input-group color-picker">' +
                        '           <div class="custom-input-group-addon"><?= $text_sticker_sticker; ?></div>'+    
                        '           <input type="text" name="sticker[INX][sticker_color]" class=" form-control" value="#000000">' +
                        '<span class="input-group-addon"><i></i></span>' +
                        '       </div>' +
                        '   </td>'+
                        
                        '   <td class="hidden-" >' +
                        '       <a href="" id="thumb-image-INX" data-toggle="image" class="img-thumbnail">'+
                        '           <img src="<?= $placeholder; ?>" alt="" title="" data-placeholder="<?= $placeholder; ?>" class="img-responsive" />'+
                        '       </a>'+
                        '       <input type = "hidden" name = "sticker[INX][image]" value = "" id = "input-image-INX" />' +
                        '   </td>' +
                        '   <td class="hidden">'+
                        '       <input type="number" name="sticker[INX][duration]" class="form-control" value="0" >'+
                        '   </td>' +
                        '   <td>'+
                        '       <select class="sticker-product-tag-INX" data-index="INX">'+
                        '           <option></option>'+
                            <?php foreach($product_list as $each){ ?>
                        '            <option value="<?= $each['product_id']; ?>" ><?= $each['name']; ?></option>'+
                            <?php } ?>
                        '       </select>'+
                        '       <div class="well identify-well-INX" style="height: 130px; overflow: auto; margin: 10px 0px 0px;">'+
                        '       </div>'+
                        '   </td>' +
                        '   <td>'+
                        '       <button type="button" onclick="$(this.parentNode.parentNode).remove();" class="btn btn-danger" >'+
                        '           <i class="fa fa-minus-circle"></i>'+
                        '       </button>'+
                        '   </td>' +
                        '</tr>';
                            
        var label_html  =   '<label class="label label-default IND" >'+
                            '   <i class="fa fa-minus-circle" onclick="$(this).parent().remove();" ></i> TEXT'+
                            '   <input type="hidden" name="sticker[INX][products][]" value="PRO_ID" />'
                            '</label>';

        function addSticker(){

            let tr_append = tr_html.split('INX').join(index);

            $('#sticker_modal tbody').append(tr_append);

            $('.sticker-product-tag-'+index).select2().on('change', function(){
                let ele = this;
                
                if(!ele.value) return;
                
                product_id = ele.value;
                name=$(ele).find('option[value="'+product_id+'"]').text();
                ind='indentity-'+product_id;
                selected_index = ele.dataset.index;

                let label_append = label_html.replace('IND', ind)
                                             .replace('TEXT', name)
                                             .replace('INX', selected_index)
                                             .replace('PRO_ID', product_id);

                $(ele).next().next().prepend(label_append);
            });

            $('.color-picker').colorpicker();
            $('[data-toggle=\'tooltip\']').tooltip({container: 'body', html: true});

            index++;
        }
    </script>
    <script>
        $(window).load(function(){
            $('#saveSticker').click(function(){
                let btn = this;
                $.ajax({
                    url: 'index.php?route=catalog/product/sticker&token=<?= $token; ?>',
                    data: $('#sticker_modal input[type="text"], #sticker_modal input[type="radio"]:checked, #sticker_modal input[type="hidden"], #sticker_modal input[type="number"]'),
                    dataType: 'json',
                    type: 'post',
                    beforeSend: function(){
                        $(btn).prop('disabled', true);
                    },
                    success: function(json){
                        $(btn).prop('disabled', false);
                        if(json){
                            alert('<?= $text_sticker_success; ?>');
                        }
                    }
                });
            });
        });
    </script>
    <script>
    <?php $i = 0; ?>
    <?php foreach($stickers as $sticker){ ?>
    $('.sticker-product-tag-<?= $i; ?>').select2().on('change', function () {
        let ele = this;

        if (!ele.value) return;

        product_id = ele.value;
        name = $(ele).find('option[value="' + product_id + '"]').text();
        ind = 'indentity-' + product_id;
        selected_index = ele.dataset.index;

        let check = '.identify-well-'+ selected_index + ' > .' + ind;
        
        if($(check).length == 0){
            let label_append = label_html.replace('IND', ind)
                .replace('TEXT', name)
                .replace('INX', selected_index)
                .replace('PRO_ID', product_id);

            $(ele).next().next().prepend(label_append);
        }
        else{
            $(check).addClass('added_before');
        }

        
    });
    <?php $i++;} ?>
    </script>
</div>