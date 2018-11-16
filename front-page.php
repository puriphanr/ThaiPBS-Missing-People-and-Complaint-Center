<?php get_header()?>
<section id="intro">
	<div class="container">
		<div class="mTopR row">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<div class="content">
					<a href="<?php bloginfo('url')?>/legal">
						<img src="<?php bloginfo('template_url')?>/images/banner_rule.png"  class="img-responsive full-width"/>
					</a>
				</div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
				<div class="content">
					<a href="<?php bloginfo('url')?>/inform">
						<img src="<?php bloginfo('template_url')?>/images/banner_inform.png"  class="img-responsive full-width"/>
					</a>
				</div>
			</div>
		</div>
		<div class="mTop row">
			<div class="col-lg-6" id="tips">
				<div class="header">สถานีประชาชนอัพเดต <a href="<?php bloginfo('url') ?>/tips" class="readmore">ดูทั้งหมด</a></div>
				<div class="content">
				<?php
				$loop = new WP_Query( array( 
											'post_type' => 'tips', 
											'orderby' => 'DESC',
											'posts_per_page' =>2,
											)
									);
				if ( $loop->have_posts() ) :
					while ( $loop->have_posts() ) : $loop->the_post();
					?>
					<a href="<?php bloginfo('url') ?>/tips/content?id=<?php echo get_the_ID()?>">
						<article class="row news">
								<div class="media-content col-lg-6 col-md-6 col-sm-6 col-xs-6">
									<img class="img-responsive" src="<?php the_post_thumbnail_url( 'medium' ) ?>">
								</div>
								<div class="media-title col-lg-6 col-md-6 col-sm-6 col-xs-6">
									<div class="title"><?php the_title() ?></div>
									<div class="desc"><?php echo truncate(get_the_excerpt()) ?></div>
								</div>
						</article>
					</a>
					<?php
					endwhile;
				endif;
				?>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="header">ติดตามการแจ้งเรื่อง</div>
				<div class="content">
					<form role="form" class="row" id="tracking" method="POST" action="<?php echo home_url('checking') ?>">
					  <div class="form-group">
						<div class="col-lg-9"><input type="text" class="form-control" id="task_id" name="task_id" placeholder="เลขที่แจ้งเรื่อง" /></div>
						<div class="col-lg-3"><button type="submit" class="btn btn-primary">ตรวจสอบ</button></div>
					  </div>
					</form>
					<a href="<?php bloginfo('url')?>/missing/create-missing">
						<img src="<?php bloginfo('template_url')?>/images/banner_missing.png"  class="img-responsive full-width"/>
					</a>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="missing" class="pTop">
	<div class="header white">
		<div class="container">
			ศูนย์ข้อมูลคนหาย <a href="<?php bloginfo('url')?>/missing" class="readmore">ดูทั้งหมด</a>
		</div>
	</div>	
	<div class="content">
		<div class="container">
		<?php
				$loop = new WP_Query( array( 
											'post_type' => 'missing_people',
											'post_status' => array('publish'),
											'meta_key'		=> 'status',
											'meta_value'	=> 3,
											'orderby' => 'DESC',
											'posts_per_page' =>12,
											)
									);
				if ( $loop->have_posts() ) :
					while ( $loop->have_posts() ) : $loop->the_post();
				
					?>
					<a href="<?php bloginfo('url')?>/missing/content?id=<?php echo get_the_ID()?>">
					<article class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
						<div class="image">
							<img src="<?php echo get_field('image')['sizes']['medium']?>"  class="img-responsive"/>
						</div>
						<div class="title">
							<div class="name"><?php echo get_field('fname')." ".get_field('lname') ?></div>
							<div class="age">อายุ <?php echo calAge(get_field('birthday'))?> ปี</div>
							<div class="date">หายเมื่อวันที่ <?php echo DateThai(get_field('missing_date'))?></div>
						</div>
					</article>
					</a>
					<?php
					endwhile;
				endif;
				?>
		</div>
	</div>
</section>
<?php
	$afterDate = date('Y-m-d',strtotime('-30 days'));
	$beforeDate = date('Y-m-d');
	$missing_stats = get_posts(array(
						'post_type'	=> 'missing_people',
						'post_status' => array('publish'),
						'meta_key'		=> 'status',
						'meta_value'	=> array(3,4),
						'date_query' => array(
								array(
									'after'     => $afterDate,
									'before'    => $beforeDate,
									'inclusive' => true,
								),
						),
					));
	if(!empty($missing_stats)){
				
	
	foreach($missing_stats as $key=>$row){
		$gender[] = get_field('gender',$row->ID)['value'];
		$age[] = calAge(get_field('birthday',$row->ID));
		$status[] = array(get_field('status',$row->ID)['value'],get_field('cancel_reason',$row->ID)['value']);
	}
	
	$group_gender = array_count_values($gender);

	$countAge[0] = 0;
	$countAge[1] = 0;
	$countAge[2] = 0;
	$countAge[3] = 0;
	$countAge[4] = 0;
	$countAge[5] = 0;
	$countAge[6] = 0;
	$countAge[7] = 0;
	foreach($age as $key=>$row){
		if($row <= 10){
			$countAge[0]++;
		}
		elseif($row <= 20){
			$countAge[1]++;
		}
		elseif($row <= 30){
			$countAge[2]++;
		}
		elseif($row <= 40){
			$countAge[3]++;
		}
		elseif($row <= 50){
			$countAge[4]++;
		}
		elseif($row <= 60){
			$countAge[5]++;
		}
		elseif($row <= 70){
			$countAge[6]++;
		}
		else{
			$countAge[7]++;
		}
	}
	
	$countStatus[0] = 0;
	$countStatus[1] = 0;
	$countStatus[2] = 0;
	foreach($status as $key=>$row){
		if($row[0] == 4){
			if($row[1] == 3){
				$countStatus[1]++;
			}
			else{
				$countStatus[0]++;
			}
		}
		else{
			$countStatus[2]++;
		}
	}
	
	
?>
<section id="missing-stats" class="pTop">
	<div class="header white no-border">
		<div class="container">
			<div class="col-lg-6">สถิติศูนย์คนหายไทยพีบีเอส </div>
			<div class="col-lg-6 stats-date">ข้อมูลวันที่ <?php echo DateThai($afterDate)." - ".DateThai($beforeDate)?></div>
		</div>
	</div>	
	<div class="content">
		<div class="container">
			<div class="col-lg-6">
				<div class="chart-container">
					<canvas id="chart-area-gender"></canvas>
				</div>
			</div>
			<div class="col-lg-6">
				<div class="chart-container">
					<canvas id="chart-area-status"></canvas>
				</div>
			</div>
			
			<div class="col-lg-12">
				<div class="chart-container bar-chart">
					<canvas id="chart-area-age"></canvas>
				</div>
			</div>
		</div>
	</div>
</section>

	<?php } ?>

<section id="video1" class="pTop">
	<div class="header">
		<div class="container">
			<div class="header-title">ทุกวันจันทร์ - ศุกร์ เวลา 14.05 - 15.00 น. <a href="https://program.thaipbs.or.th/People" target="_blank" class="readmore">ดูทั้งหมด</a></div>
			<img src="<?php echo get_template_directory_uri()?>/images/people-logo.png" class="img-responsive" />
		</div>
	</div>	
	<div class="content">
		<div class="container">
		<?php
		$video1 = getProgram('People');
		foreach($video1 as $key=>$row){
		?>
				<article class="col-lg-3 col-md-4 col-sm-6 col-xs-12 video">
					<a href="<?php echo $row['canonical_url']?>" target="_blank">
						<div class="media-content">
							<i class="fa fa-play"></i>
							<img src="<?php echo $row['display_image']['sizes']['medium']['url']?>" class="img-responsive thumbnail" />
							
						</div>
						<div class="media-title">
							<div class="title"><?php echo $row['title']?></div>
							<div class="date"><?php echo $row['schedule_begin_at_date_string'] ?></div>
						</div>
					</a>
				</article>
		<?php } ?>
		</div>
	</div>
</section>

<section id="video2" class="pTop">
	<div class="header">
		<div class="container">
			<div class="header-title">ทุกวันจันทร์ - ศุกร์ เวลา 08.30 - 09.00 น. <a href="https://program.thaipbs.or.th/Rongtook" target="_blank" class="readmore">ดูทั้งหมด</a></div>
			<img src="<?php echo get_template_directory_uri()?>/images/rongtook-logo.png" class="img-responsive" />
		</div>
	</div>	
		<div class="content">
		<div class="container">
		<?php
		$video2 = getProgram('Rongtook');
		foreach($video2 as $key=>$row){
		?>
				<article class="col-lg-3 col-md-4 col-sm-6 col-xs-12 video">
					<a href="<?php echo $row['canonical_url']?>" target="_blank">
						<div class="media-content">
							<i class="fa fa-play"></i>
							<img src="<?php echo $row['display_image']['sizes']['medium']['url']?>" class="img-responsive thumbnail" />
							
						</div>
						<div class="media-title">
							<div class="title"><?php echo $row['title']?></div>
							<div class="date"><?php echo $row['schedule_begin_at_date_string'] ?></div>
						</div>
					</a>
				</article>
		<?php } ?>
		</div>
	</div>
</section>
<script  type="text/javascript">
	jQuery(function($) { 
		$('.datepicker').datepicker();	
	<?php if(!empty($missing_stats)){ ?>
    var config = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
					<?php foreach($group_gender as $key=>$row) { ?>
						<?php echo $row?>,
					<?php } ?>
                ],
                backgroundColor: [
					<?php foreach($group_gender as $key=>$row) { ?>
						<?php echo $key==1 ? 'window.chartColors.blue' : 'window.chartColors.red' ?>,
					<?php } ?>
                ],
                label: 'เพศ'
            }],
            labels: [
               <?php foreach($group_gender as $key=>$row) { ?>
						'<?php echo $key==1 ? 'ชาย' : 'หญิง' ?>',
			   <?php } ?>
            ]
        },
        options: {
            responsive: true,
			maintainAspectRatio: false,
			title : {
				display : true,
				text : 'เพศ',
				position: 'top',
				fontColor: 'white',
				fontFamily: 'Sukhumvit_settext',
				fontSize :24,
				padding:20
			},
			legend: {
                        position: 'right',
						labels: {
							fontColor: 'white',
							fontFamily: 'Sukhumvit_settext',
							fontSize : 16
						}
            },
			pieceLabel: {
				render: 'percentage',
				fontSize: 16,
				fontColor: '#fff'
			}
					
        }
    };

	var config2 = {
        type: 'doughnut',
        data: {
            datasets: [{
                data: [
					<?php foreach($countStatus as $key=>$row) { ?>
						<?php echo $row?>,
					<?php } ?>
                ],
                backgroundColor: [
					window.chartColors.green,
					window.chartColors.red,
					window.chartColors.yellow
                ],
                label: 'จำนวน'
            }],
            labels: [
               "พบตัวแล้ว",
			   "เสียชีวิต",
			   "ยังไม่พบ"
            ]
        },
        options: {
            responsive: true,
			maintainAspectRatio: false,
			title : {
				display : true,
				text : 'ผลการติดตาม',
				position: 'top',
				fontColor: 'white',
				fontFamily: 'Sukhumvit_settext',
				fontSize :24,
				padding:20
			},
			legend: {
                        position: 'right',
						labels: {
							fontColor: 'white',
							fontFamily: 'Sukhumvit_settext',
							fontSize : 16
						}
            },
			pieceLabel: {
				render: 'percentage',
				fontSize: 16,
				fontColor: '#fff'
			}	
        }
    };
    
			Chart.pluginService.register({
			beforeDraw: function (chart, easing) {
				if (chart.config.options.chartArea && chart.config.options.chartArea.backgroundColor) {
					var helpers = Chart.helpers;
					var ctx = chart.chart.ctx;
					var chartArea = chart.chartArea;

					ctx.save();
					ctx.fillStyle = chart.config.options.chartArea.backgroundColor;
					ctx.fillRect(chartArea.left, chartArea.top, chartArea.right - chartArea.left, chartArea.bottom - chartArea.top);
					ctx.restore();
				}
			}
		});
	var barChartData = {
            labels: ["น้อยกว่า 10 ปี","11-20 ปี","21-30 ปี","31-40 ปี","41-50 ปี","51-60 ปี","61-70 ปี","มากกว่า 70 ปี"],
            datasets: [{
                label: 'อายุ',
                backgroundColor: [
					window.chartColors.green,
					window.chartColors.red,
					window.chartColors.yellow,
					window.chartColors.grey,
					window.chartColors.blue,
					window.chartColors.orange,
					window.chartColors.purple,
					window.chartColors.grey
                ],
                borderWidth: 1,
                data: [
					<?php foreach($countAge as $key=>$row){ ?>
						<?php echo $row?>,
					<?php } ?>
                ]
            }]

        };
		
	window.onload = function() {
        var ctx1 = document.getElementById("chart-area-gender").getContext("2d");
        window.myPie = new Chart(ctx1, config);
		
		var ctx2 = document.getElementById("chart-area-status").getContext("2d");
        window.myPie = new Chart(ctx2, config2);
		
		 var ctx3 = document.getElementById("chart-area-age").getContext("2d");
            window.myBar = new Chart(ctx3, {
                type: 'bar',
                data: barChartData,
				options: {
					responsive: true,
					maintainAspectRatio: false,
					title : {
						display : true,
						text : 'อายุ',
						position: 'top',
						fontColor: 'white',
						fontFamily: 'Sukhumvit_settext',
						fontSize :24,
						padding:20
					},
					legend: {
								position: 'bottom',
								labels: {
									fontColor: 'white',
									fontFamily: 'Sukhumvit_settext',
									fontSize : 16
								}
					},
					scales: {
						yAxes: [{
							ticks: {
								fontColor: "white",
								fontFamily: 'Sukhumvit_settext',
								fontSize : 16,
								stepSize: 10,
								beginAtZero: true
							},
							 gridLines:{
							  color: "#808080",
							  lineWidth:2,
							  zeroLineColor :"#808080",
							  zeroLineWidth : 2
							}
						}],
						xAxes: [{
							ticks: {
								fontColor: "white",
								fontFamily: 'Sukhumvit_settext',
								fontSize : 16,
								beginAtZero: true
							},
							gridLines:{
							  color: "#808080",
							  lineWidth:2
							}
						}]
					},
					chartArea : {
						backgroundColor : 'rgba(239, 239, 239, 0.4)'
					}
											
				}
            });
    };
	<?php } ?>
});
</script>
<?php get_footer()?>