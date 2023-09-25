<?php if(!$products_available){ ?>
    <?= $order_unavailable_for_reorder; ?>
<?php }else{ ?>
    <div class="order-product-list">

        <ul class="nav nav-pills nav-justified" >
            <li class="active" ><a data-toggle="tab" href="#products_available" ><?= $product_available; ?>
                <span class="badge"><?= count($products_available); ?></span>
            </a></li>
            <?php if($products_unavailable){ ?>
                <li><a data-toggle="tab" href="#products_unavailable" ><?= $product_unavailable; ?>
                    <span class="badge"><?= count($products_unavailable); ?></span>
                </a></li>
            <?php } ?>
        </ul>

        <div class="tab-content">
        <?php if($products_available){ ?>
            <div id="products_available" class="tab-pane fade in active">
                <div class="order-product-item-list">
                    <?php foreach($products_available as $product){ ?>
                        <?= $product; ?>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

        <?php if($products_unavailable){ ?>
            <div id="products_unavailable" class="tab-pane fade">
                <div class="order-product-item-list">
                <?php foreach($products_unavailable as $product){ ?>
                    <?= $product; ?>
                <?php } ?>
                </div>
            </div>
        <?php } ?>
        </div>
        
        <button type="button" id="reorder_order" class="btn btn-primary btn-block" ><?= $button_reorder; ?></button>
        <script>
            $('#reorder_order').on('click', function(e){
                e.preventDefault();
                if($('#products_unavailable').length){
                    swal({
                        title: '<?= $text_notice; ?>',
                        text: "<?= $product_notice; ?>",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes'
                    }).then((result) => {
                        if (result.value) {
                            reorder_order();
                        }
                    })
                }
                else{
                    reorder_order();
                }
            });

            function reorder_order(){
                $.post(
                    'index.php?route=account/order/reorder_order',
                    $('#products_available input, #products_available textarea').serialize(),
                    function (json) {
                        if (json) {
                            $('#cart-quantity-total').text(json['total_quantity']);
                            $('#cart > ul').load('index.php?route=common/cart/info ul li');
                        }

                        swal(
                            '<?= $product_success; ?>',
                            '<?= $product_reordered; ?>',
                            'success'
                        ).then((inner_result) => {
                            if (inner_result.value) {
                                $('#modal-content-custom').modal('hide');
                            }
                        });
                    }
                );
            }
        </script>
    </div>
<?php } ?>