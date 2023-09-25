<script>
function loadScript(url, callback){
        console.log("Loading Script [" + url + "]");
        var script = document.createElement("script");
        script.type = "text/javascript";
        

        if (script.readyState){  //IE
                script.onreadystatechange = function(){
                    if (script.readyState == "loaded" || script.readyState == "complete"){
                        script.onreadystatechange = null;
                        callback();
                    }
                };
        } else {  //Others
                script.onload = function(){
                    callback();
                };
        }
        script.src = url;
        document.getElementsByTagName("head")[0].appendChild(script);
}
// bluesnap = undefined;
if (typeof bluesnap == 'undefined') {
	console.log("bluesnap is undefined. Initialising it now");
	loadScript("<?php echo $bluesnap_url;?>/services/hosted-payment-fields/v1.0/bluesnap.hpf.mini.js", function() {
		console.log("Bluesnap script loaded");
		console.log(bluesnap);
	});
} else {
	console.log(bluesnap);
	console.log("bluesnap is defined");
}
</script>

<!--script type="text/javascript" src="<?php echo $bluesnap_url;?>/services/hosted-payment-fields/v1.0/bluesnap.hpf.mini.js"></script -->
<div id="bluespan_contents">
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>
</div>
<script type="text/javascript"><!--

$('#button-confirm').on('click', function() {
	console.log(bluesnap);
	if (typeof bluesnap == 'undefined') {
        	console.log("bluesnap is undefined");
	    // Assign myFunc
	} else {	
        	console.log("bluesnap is defined");
	}
	console.log("CLICKED");
	$.ajax({ 
		type: 'get',
		url: 'index.php?route=extension/payment/bluesnap/form',
		cache: false,
		async: false,
		dataType: 'html',
		beforeSend: function() {
			$('#button-confirm').button('loading');
		},
		complete: function() {
			$('#button-confirm').button('reset');
		},		
		success: function(html) {
			// $("#collapse-checkout-confirm .panel-body").html(html);
			$("#bluespan_contents .buttons").html(html);
		}		
	});
});


//--></script> 
