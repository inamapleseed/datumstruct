<div class="_search">

    <div id="fullpage-search">
        <a id="fullpage-search-close" class="fx-close" ></a>
        <div class="container">
            <?=$search; ?>
        </div>
    </div>

    <a id="fullpage-search-open" >
        <i class="fa fa-search" ></i>
    </a>

    <script type="text/javascript">
        if($('.search-custom').length){
            $('#fullpage-search').insertBefore('header');
        }
        $('#fullpage-search-close').on('click', function(e){
            e.preventDefault();
            $('#fullpage-search').fadeOut(300);
        });
        
        $('#fullpage-search-open').on('click', function(e){
            e.preventDefault();
            $('#fullpage-search').fadeIn(300);
        });
    </script>

</div>