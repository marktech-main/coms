<?php 
$daters = date('Y-m');
$now = new DateTime($daters);
$date_1 = $now->sub(new DateInterval('P1M'))->format('F');
$date_2 = $now->sub(new DateInterval('P1M'))->format('F');
$date_3 = $now->sub(new DateInterval('P1M'))->format('F');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>PPR Monthly Report</title>
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/autoreport.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
    <!-- Page Content -->
    <div class="container custom_container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-12">
            	<h1 class="page-header">
                    PPR
                    <small>Monthly Report</small>
                </h1>
                <div class="legend">
                	<table>
            			<tr>
            				<th class="head" colspan="2">Ranking Format</th>
            			</tr>
                		<tr>
                			<td colspan="2"><center><?=$date_1?> | <?=$date_2?> | <?=$date_3?></center></td>
                		</tr>
            			<tr>
            				<th colspan="2">Ranking color</th>
            			</tr>
                		<tr>
                			<td><span class="label label-success"></span></td>
                			<td><span>Maintained top</span></td>
                		</tr>
                		<tr>
                			<td><span class="label label-primary"></span></td>
                			<td><span>Maintained mid</span></td>
                		</tr>
                		<tr>
                			<td><span class="label label-danger"></span></td>
                			<td><span>Maintained poor</span></td>
                		</tr>
                		<tr>
                			<th colspan="2">Speed comparison</th>
                		</tr>
                		<tr>
                			<td><center><span class='dropup'><span class='caret' id='caret_up'></span></span></center></td>
                			<td><span>Faster</span></td>
                		</tr>
                		<tr>
                			<td><center><span class='caret' id='caret_down'></span></center></td>
                			<td><span>Slower</span></td>
                		</tr>
                		<tr>
                			<td><center><img class="same" src="/images/circle_icon_x.png" /></center></td>
                			<td><span>Same</span></td>
                		</tr>
                	</table>
	            </div>
		        <table class="table table-striped ppr_autoreport">
		         <thead class="thead-inverse">
		         	<tr class="sum_label">
		            	<th colspan="2"><a id="d_report">Download Report</a><span class="d_countdown"></span></th>
			            <th colspan="4" class="box">
			            	<?=($result->sum1month->tot_min != '') ? $result->sum1month->tot_min : '0'?> min / <?=($result->sum1month->tot_trans != '') ? $result->sum1month->tot_trans : '0'?> transaction 
			            	<br> 
			            	average <?=($result->sum1month->ave_speed != '') ? $result->sum1month->ave_speed : '0'?> min / transaction
			            </th>
			            <th colspan="4" class="box mid">
			            	<?=($result->sum2month->tot_min != '') ? $result->sum2month->tot_min : '0'?> min / <?=($result->sum2month->tot_trans != '') ? $result->sum2month->tot_trans : '0'?> transaction 
			            	<br> 
			            	average <?=($result->sum2month->ave_speed != '') ? $result->sum2month->ave_speed : '0'?> min / transaction
			            </th>
			            <th colspan="4" class="box">
			            	<?=($result->sum3month->tot_min != '') ? $result->sum3month->tot_min : '0'?> min / <?=($result->sum3month->tot_trans != '') ? $result->sum3month->tot_trans : '0'?> transaction 
			            	<br> 
			            	average <?=($result->sum3month->ave_speed != '') ? $result->sum3month->ave_speed : '0'?> min / transaction
			            </th>
			        </tr>
		            <tr class="date_label">
		            	<th></th>
		            	<th></th>
		            	<?php 
		            		$daters = date('Y-m');
							$now = new DateTime($daters);
							$date_1 = $now->sub(new DateInterval('P1M'))->format('F');
							$date_2 = $now->sub(new DateInterval('P1M'))->format('F');
							$date_3 = $now->sub(new DateInterval('P1M'))->format('F');
		            	?>
			            <th colspan="4"><?=$date_1?></th>
			            <th colspan="4"><?=$date_2?></th>
			            <th colspan="4"><?=$date_3?></th>
			        </tr>

		            <tr>
		            	<th class="ranking">Ranking</th>
		            	<th class="team_name">Names</th>
						<th>Min</th>
						<th>Avg</th>
						<th>Max</th>
						<th>Trans</th>
						<th>Min</th>
						<th>Avg</th>
						<th>Max</th>
						<th>Trans</th>
						<th>Min</th>
						<th>Avg</th>
						<th>Max</th>
						<th>Trans</th>
		            </tr>
		          </thead>
		         <tbody>
		          <?php 
		          foreach ($result->m1 as $item) { ?>
		            <tr>
		            	<td class="ranking_monthly">
		            	<?php 
		            	$rank1 = ($item->score != 0) ? $item->rank : '-';
		            	$rank2 = ($result->m2[$item->team_id]->score != 0) ? $result->m2[$item->team_id]->rank : '-';
		            	$rank3 = ($result->m3[$item->team_id]->score != 0) ? $result->m3[$item->team_id]->rank : '-';


	            		if($rank1 >= 1 && $rank1 <= 4 && $rank2 >= 1 && $rank2 <= 4 && $rank3 >= 1 && $rank3 <= 4) {
	            			echo '<span class="label label-success">';
	            		} else if($rank1 >= 5 && $rank1 <= 15 && $rank2 >= 5 && $rank2 <= 15 && $rank3 >= 5 && $rank3 <= 15){
	            			echo '<span class="label label-primary">';
	            		} else if($rank1 >= 10 && $rank2 >= 10 && $rank3 >= 10){
	            			echo '<span class="label label-danger">';
	            		} else if($rank1 == '-' && $rank2 >= 1 && $rank2 <= 4 && $rank3 >= 1 && $rank3 <= 4){
	            			echo '<span class="label label-success">';
	            		} else if($rank2 == '-' && $rank1 >= 1 && $rank1 <= 4 && $rank3 >= 1 && $rank3 <= 4){
	            			echo '<span class="label label-success">';
	            		} else if($rank3 == '-' && $rank1 >= 1 && $rank1 <= 4 && $rank2 >= 1 && $rank2 <= 4){
	            			echo '<span class="label label-success">';
	            		} else if($rank1 == '-' && $rank2 >= 5 && $rank2 <= 15 && $rank3 >= 5 && $rank3 <= 15){
	            			echo '<span class="label label-primary">';
	            		} else if($rank2 == '-' && $rank1 >= 5 && $rank1 <= 15 && $rank3 >= 5 && $rank3 <= 15){
	            			echo '<span class="label label-primary">';
	            		} else if($rank3 == '-' && $rank1 >= 5 && $rank1 <= 15 && $rank2 >= 5 && $rank2 <= 15){
	            			echo '<span class="label label-primary">';
	            		} else if($rank1 == '-' && $rank2 >= 10 && $rank3 >= 10){
	            			echo '<span class="label label-danger">';
	            		} else if($rank2 == '-' && $rank1 >= 10 && $rank3 >= 10){
	            			echo '<span class="label label-danger">';
	            		} else if($rank3 == '-' && $rank1 >= 10 && $rank2 >= 10){
	            			echo '<span class="label label-danger">';
	            		} else if($rank1 == '-' && $rank2 == '-' && $rank3 >= 1 && $rank3 <= 4){
	            			echo '<span class="label label-success">';
	            		} else if($rank2 == '-' && $rank3 == '-' && $rank1 >= 1 && $rank1 <= 4){
	            			echo '<span class="label label-success">';
	            		} else if($rank3 == '-' && $rank1 == '-' && $rank2 >= 1 && $rank2 <= 4){
	            			echo '<span class="label label-success">';
	            		} else if($rank1 == '-' && $rank2 == '-' && $rank3 >= 5 && $rank3 <= 15){
	            			echo '<span class="label label-primary">';
	            		} else if($rank2 == '-' && $rank3 == '-' && $rank1 >= 5 && $rank1 <= 15){
	            			echo '<span class="label label-primary">';
	            		} else if($rank3 == '-' && $rank1 == '-' && $rank2 >= 5 && $rank2 <= 15){
	            			echo '<span class="label label-primary">';
	            		} else if($rank1 == '-' && $rank2 == '-' && $rank3 >= 10){
	            			echo '<span class="label label-danger">';
	            		} else if($rank2 == '-' && $rank3 == '-' && $rank1 >= 10){
	            			echo '<span class="label label-danger">';
	            		} else if($rank3 == '-' && $rank1 == '-' && $rank2 >= 10){
	            			echo '<span class="label label-danger">';
	            		} else if($rank1 == '-' && $rank2 == '-' && $rank3 == '-'){
	            			echo '<span class="label label-danger">';
	            		} else {
	            			echo '<span>';
	            		}
	            		echo $rank1.' | '.$rank2.' | '.$rank3;
		            	?>
		            	</span>
		            	</td>
						<td class="team_name">

						<?=ucwords(strtolower($item->name));?>
							
						</td>
						<td class="m1"><?=($item->score != 0) ? $item->min_speed : '-'?></td>
						<td class="m1">
						<?php 
						if($item->score != 0) {
							if ($item->sec_ave_speed < $result->m2[$item->team_id]->sec_ave_speed) {
								echo "<span class='dropup'><span class='caret' id='caret_up'></span></span>";
							} else if ($item->sec_ave_speed > $result->m2[$item->team_id]->sec_ave_speed) {
								echo "<span class='caret' id='caret_down'></span>";
							} else if ($item->sec_ave_speed == $result->m2[$item->team_id]->sec_ave_speed) {
								echo '<img class="same" src="/images/circle_icon_x.png" />';
							}
						}
						echo ($item->score != 0) ? $item->ave_speed : '-';
						?>
						</td>
						<td class="m1"><?=($item->score != 0) ? $item->max_speed : '-'?></td>
						<td class="m1"><?=($item->score != 0) ? $item->total_trans : '-'?></td>

						<td class="m2"><?=($result->m2[$item->team_id]->score != 0) ? $result->m2[$item->team_id]->min_speed : '-'?></td>
						<td class="m2">
						<?php 
						if($result->m2[$item->team_id]->score != 0) {
							if ($result->m2[$item->team_id]->sec_ave_speed < $result->m3[$item->team_id]->sec_ave_speed) {
								echo "<span class='dropup'><span class='caret' id='caret_up'></span></span>";
							} else if ($result->m2[$item->team_id]->sec_ave_speed > $result->m3[$item->team_id]->sec_ave_speed) {
								echo "<span class='caret' id='caret_down'></span>";
							} else if ($result->m2[$item->team_id]->sec_ave_speed == $result->m3[$item->team_id]->sec_ave_speed) {
								echo '<img class="same" src="/images/circle_icon_x.png" />';
							}
						}	
						echo ($result->m2[$item->team_id]->score != 0) ? $result->m2[$item->team_id]->ave_speed : '-';
						?>
						</td>
						<td class="m2"><?=($result->m2[$item->team_id]->score != 0) ? $result->m2[$item->team_id]->max_speed : '-'?></td>
						<td class="m2"><?=($result->m2[$item->team_id]->score != 0) ? $result->m2[$item->team_id]->total_trans : '-'?></td>

						<td class="m3"><?=($result->m3[$item->team_id]->score != 0) ? $result->m3[$item->team_id]->min_speed : '-'?></td>
						<td class="m3">
						<?php 
						if($result->m3[$item->team_id]->score != 0) {
							if ($result->m3[$item->team_id]->sec_ave_speed < $result->m4[$item->team_id]->sec_ave_speed) {
								echo "<span class='dropup'><span class='caret' id='caret_up'></span></span>";
							} else if ($result->m3[$item->team_id]->sec_ave_speed > $result->m4[$item->team_id]->sec_ave_speed) {
								echo "<span class='caret' id='caret_down'></span>";
							} else if ($result->m3[$item->team_id]->sec_ave_speed == $result->m4[$item->team_id]->sec_ave_speed) {
								echo '<img class="same" src="/images/circle_icon_x.png" />';
							}
						}
						echo ($result->m3[$item->team_id]->score != 0) ? $result->m3[$item->team_id]->ave_speed : '-';	
						?>
						</td>
						<td class="m3"><?=($result->m3[$item->team_id]->score != 0) ? $result->m3[$item->team_id]->max_speed : '-'?></td>
						<td class="m3"><?=($result->m3[$item->team_id]->score != 0) ? $result->m3[$item->team_id]->total_trans : '-'?></td>
		            </tr>
		            <?php } ?>
		          </tbody>
		            
		        </table>

            </div>

        </div>
        <!-- /.row -->

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="/js/jquery-3.1.1.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="/js/bootstrap.min.js"></script>
    <!-- <script src="/js/html2canvas.js"></script>
  	<script src="/js/jspdf.min.js"></script> -->
    <script type="text/javascript">
    	/*$(document).on('click','#d_report', function(){
		    download_couter();
		   $('#d_report').attr("disabled", true).addClass('disabled');
		    html2canvas($('#export_this'), {
		          onrendered: function(canvas) {
		            var imgData = canvas.toDataURL(
		                'image/png');              
		            var pdf = new jsPDF('p', 'mm', 'a4');
		            var filename = "css_report_.pdf"  //date( 'm-Y')
		            pdf.addImage(canvas, 'PNG', 5, 5, 201, 285);
		            pdf.save(filename);
		          }
		      });
		});*/
		     
	  	$('#d_report').on('click',function(){
	  		window.location.replace("<?php echo base_url()?>pprautoreport/generate_excel");
		});
    </script>

</body>

</html>
