<?php 
$notGivenCoupons    = $givenCoupons - $usedCoupons; 
$guestCustomers     = $totalCustomers - $registeredCustomers;
?>
<script type="text/javascript" src="//www.google.com/jsapi"></script>
<script type="text/javascript">
google.load("visualization", "1", {packages:["corechart"]});

function drawChartCoupons() {
    var data = google.visualization.arrayToDataTable([
	  ['<?php echo $text_type; ?>', '<?php echo $text_count; ?>'],
	  ['<?php echo $text_used_coupons; ?>',	<?php echo $usedCoupons; ?>],
	  ['<?php echo $text_notused_coupons; ?>',	<?php echo $notGivenCoupons; ?>]
	]);
	
    var options = {
	  title: '<?php echo $text_total_coupons; ?> <?php echo $givenCoupons; ?>',
	  backgroundColor: 'transparent',
	  is3D: true,
	  chartArea: {top:50,bottom:0,width:'100%'}
	};
    
	var chart = new google.visualization.PieChart(document.getElementById('chartCoupons'));
	chart.draw(data, options);
}

function drawChartCustomers() {
	var data = google.visualization.arrayToDataTable([
	  ['<?php echo $text_type; ?>', '<?php echo $text_count; ?>'],
	  ['<?php echo $text_registerd_customers; ?>', <?php echo $registeredCustomers; ?>],
	  ['<?php echo $text_guest_customers; ?>', <?php echo $guestCustomers; ?>]
	]);
    
	var options = {
	  title: '<?php echo $text_total_carts; ?> <?php echo $totalCustomers; ?>',
	  backgroundColor: 'transparent',
	  is3D: true,
	  chartArea: {top:50,bottom:0,width:'100%'}
	};
	
    var chart = new google.visualization.PieChart(document.getElementById('chartCustomers'));
	chart.draw(data, options);
}

if (typeof drawChartCoupons == 'function') { 
    google.setOnLoadCallback(drawChartCoupons);
}

if (typeof drawChartCustomers == 'function') { 
    google.setOnLoadCallback(drawChartCustomers);
}

$('a[href=#ab_analytics]').on('click', function() {
	if (typeof drawChartCoupons == 'function') { 
		setTimeout(drawChartCoupons, 100);
	}
	if (typeof drawChartCustomers == 'function') { 
		setTimeout(drawChartCustomers, 100);
	}
});

$(window).resize(function(){
	drawChartCoupons();
	drawChartCustomers();
});
</script>

<div class="row-fluid">
    <div class="col-xs-6">
	    <div id="chartCoupons" class="well" style="height:300px;overflow:hidden;"></div>
	    <div id="chartCustomers" class="well" style="height:300px;overflow:hidden;"></div>
     </div>
    
    <div class="col-xs-6">
        <table class="table-hover table table-striped">
            <thead>
                <tr>
                    <th width="70%"><?php echo $text_coupons; ?></th>
                    <th><?php echo $text_count; ?></th>
                </tr>
            </thead>
            
            <tbody>
                <tr>
                    <td><i class="fa fa-gift"></i> <?php echo $text_total_coupons; ?></td>
                    <td><?php echo $givenCoupons; ?></td>
                </tr>
                <tr>
                    <td><i class="fa fa-ticket"></i> <?php echo $text_notused_coupons; ?></td>
                    <td><?php echo $notGivenCoupons; ?></td>
                </tr>
                <tr>
                    <td><i class="fa fa-check-square-o"></i> <?php echo $text_used_coupons; ?></td>
                    <td><?php echo $usedCoupons; ?></td>
                </tr>
            </tbody>
        </table>
        
        <table class="table-hover table table-striped">
            <thead>
                <tr>
                    <th width="70%"><?php echo $text_abandoned_carts; ?></th>
                    <th><?php echo $text_count; ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><i class="fa fa-list-alt"></i> <?php echo $text_total_carts_2; ?></td>
                    <td><?php echo $totalCustomers; ?></td>
                </tr>
                <tr>
                    <td><i class="fa fa-eye"></i> <?php echo $text_registerd_customers; ?></td>
                    <td><?php echo $registeredCustomers; ?></td>
                </tr>
                <tr>
                    <td><i class="fa fa-eye-slash"></i> <?php echo $text_guest_customers; ?></td>
                    <td><?php echo $guestCustomers; ?></td>
                </tr>
            </tbody>
        </table>
        
        <table class="table-hover table table-striped">
            <thead>
                <tr>
                    <th width="70%"><?php echo $text_last_visited_carts; ?></th>
                    <th><?php echo $text_count; ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($mostVisitedPages)) { ?>
                <?php foreach ($mostVisitedPages as $result) { ?>
                <tr>
                    <td><i class="fa fa-file"></i> <?php echo "...".substr($result['last_page'], -45); ?></td>
                    <td><?php echo $result['count']; ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                    <td colspan="3" class="text-center"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>  
    </div>
</div>
