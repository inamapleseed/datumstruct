// Enquiry add remove functions
var enquiry = {
    'add': function (product_id, quantity) {
        $.ajax({
            url: 'index.php?route=enquiry/cart/add',
            type: 'post',
            data: 'product_id=' + product_id + '&quantity=' + (typeof (quantity) != 'undefined' ? quantity : 1),
            dataType: 'json',
            beforeSend: function () {
                $('#enquiry > button').button('loading');
            },
            complete: function () {
                $('#enquiry > button').button('reset');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();

                if (json['redirect']) {
                    $('body').removeAttr('style');
                    setTimeout(function () {
                        location = json['redirect'];
                    }, 1000);
                }

                if (json['success']) {
                    // Need to set timeout otherwise it wont update the total
                    setTimeout(function () {
                        $('#enquiry-quantity-total').text(json['total_quantity']);
                        $('#enquiry-total').text(json['total']);
                    }, 100);

                    swal({
                        title: json['success_title'],
                        html: json['success'],
                        type: "success"
                    });

                    $('#enquiry > ul').load('index.php?route=common/enquiry/info ul > *');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    'remove': function (key) {
        $.ajax({
            url: 'index.php?route=enquiry/cart/remove',
            type: 'post',
            data: 'key=' + key,
            dataType: 'json',
            beforeSend: function () {
                $('#enquiry > button').button('loading');
            },
            complete: function () {
                $('#enquiry > button').button('reset');
            },
            success: function (json) {
                // Need to set timeout otherwise it wont update the total
                setTimeout(function () {
                    $('#enquiry-quantity-total').text(json['total_quantity']);
                    $('#enquiry-total').text(json['total']);
                }, 100);

                if ($('body.short_hand').length) {
                    if (location.toString().indexOf('cart') > 1 || location.toString().indexOf('checkout') > 1) {
                        location.reload();
                    }
                    else {
                        $('#enquiry > ul').load('index.php?route=common/enquiry/info ul > *');

                        swal({
                            title: json['success_remove_title'],
                            html: json['success'],
                            type: "success"
                        });
                    }
                }
                else {
                    if (getURLVar('route') == 'enquiry/cart' || getURLVar('route') == 'enquiry/checkout' || getURLVar('route') == 'quickenquiry/checkout') {
                        location = 'index.php?route=enquiry/cart';
                    } else {
                        $('#enquiry > ul').load('index.php?route=common/enquiry/info ul > *');

                        swal({
                            title: json['success_remove_title'],
                            html: json['success'],
                            type: "success"
                        });
                    }
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
}