<button id="filter_group_tigger_open" class="visible-xs btn btn-primary" onclick="$('#filter-groups').addClass('open');" ><?= $button_filter; ?></button>
<div id="filter-groups">
    <button id="filter_group_tigger_close" class="btn btn-danger fixed position-right-top" onclick="$('#filter-groups').removeClass('open');" >
        <i class="fa fa-times" ></i>
    </button>
    <?= $categories; ?>
    <?= $manufacturers; ?>
    <?php /* please do not remove the below container (for filters use when triggering price filter AJAX) */ ?>
    <div id="for-filters-container"></div>
    <?= $filters; ?>
    <?= $prices; ?>

    <script type="text/javascript">
        // Handle Category
        var base_url = '<?= html($page_url); ?>';
        var last_clicked = null;
        var prefix = 'p_';
        var xhr = null;
        var click_delay = 350; // ms

        $('#side-categories a').on('click', function(e){
            e.preventDefault();

            base_url = this.href;   // cl(base);
            
            let el = $(this);
            let el_parent = el.parent();

            if(!el_parent.hasClass('active')){
                el_parent.addClass('active');
            }
            
            if(!last_clicked){
                last_clicked = prefix + el_parent.attr('data-path');
            }
            // Check switch category branch
            else{
                
                // Different branch
                if( 
                    last_clicked.indexOf(el_parent.attr('data-path')) == -1 && 
                    last_clicked != prefix + el_parent.attr('data-path') 
                ){
                    let el_path = prefix + el_parent.attr('data-path');

                    last_clicked = el_path; // Save branch

                    // Remove active from previous branch
                    $('#side-categories .item.active').each(function(){
                        
                        el_current = $(this);
                        el_current_path = el_current.attr('data-path');

                        if( el_path.indexOf(el_current_path) == -1 ){
                            el_current.removeClass('active');
                        }
                    });
                }
            }
            // Note: indexOf don't return false and false is not equals to -1
            // So !-1 will never be false

            applyFilter();
        });
        // End Handle Category

        // Handle Manufacturer
        $('#side-manufacturer input').on('change', function(){
            applyFilter();
        });
        // End Handle Manufacturer

        // Handle Price
            //  Price handler is with price file as slider have longer code
        // End Handle Price

        // Handle Filter
        var filter_event = null;
        function catchFilter(){
            if(filter_event) clearTimeout(filter_event);
            filter_event = setTimeout(function(){
                applyFilter();
            }, click_delay);
        }
        // End Handle Filter

        // Handle Select Change
        var select_event = null;
        function select_handler(){
            if(select_event) clearTimeout(select_event);
            select_event = setTimeout(function(){
                applyFilter();
            }, click_delay);
        }
        // End Handle Select Change

        function applyFilter(){
            if(xhr) xhr.abort();
            
            url = base_url;
            
            if( $('#side-manufacturer input:checked').length ){
                var url_manufacturer = [];
                $('#side-manufacturer input:checked').each(function(){
                    url_manufacturer.push(this.value);
                });
                url += '&manufacturer_id=' + url_manufacturer.join(',');
            }

            if( $("#side-price").length ){
                url += '&price_min=' + $("#side-price #price_min").val();
                url += '&price_max=' + $("#side-price #price_max").val();
            }
            
            if( $("#side_filter").length ){
                $filters = [];
                 $('#side_filter input:checked').each(function(){
                    $filters.push(this.value);
                });

                if($filters.length > 0){
                    url += '&filter=' + $filters.join(',');
                }
            }

            if( $('#input-sort').length && $('#input-sort').val() ) url += '&sort=' + $('#input-sort').val();

            if( $('#input-limit').length && $('#input-limit').val() ) url += '&limit=' + $('#input-limit').val();
            
            // url = encodeURIComponent(url);

            xhr = $.get(url, function(html){

                history.pushState({
                    state: 'filter'
                }, "Filter State", url);

                let side_filter = $(html).find('#side_filter').html();
                let product_listing = $(html).find('#product-filter-replace').html();
                
                $('#product-filter-replace').html(product_listing); // loadLayout();

                if(side_filter){
                    if( $('#side_filter').length ) {
                        $('#side_filter').html(side_filter);
                    }
                    else{
                        //$('#side-manufacturer').after('<div id="side_filter">'+ side_filter +'</div>');
                        $('#for-filters-container').after('<div id="side_filter">'+ side_filter +'</div>');
                    }
                }
                else{
                    $('#side_filter').remove();
                }
            });
        }
    </script>
</div>
