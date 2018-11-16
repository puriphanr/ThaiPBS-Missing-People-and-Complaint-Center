<?php 
//--------Add Feature Image & Excerpt & Hide Post Title------------//
add_theme_support( 'post-thumbnails' );
add_post_type_support( 'tips','excerpt' );
add_action('admin_init', 'hide_post_title');
function hide_post_title() {
     remove_post_type_support('missing_people', 'title');
	 remove_post_type_support('legal', 'title');
	 remove_post_type_support('inform', 'title');
}

//--------Style Front-end Admin Bars------------//
function remove_admin_login_header() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}

add_action('get_header', 'remove_admin_login_header');
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' ); 

//--------Add css & script to back-end------------//
wp_enqueue_style('admin_css_bootstrap', get_template_directory_uri().'/css/bootstrap.css', false, '1.0.0', 'all');
wp_enqueue_style('admin_fontawesome', get_template_directory_uri().'/css/font-awesome.min.css', false, '1.0.0', 'all');
wp_enqueue_style('admin_theme', get_template_directory_uri().'/css/admin.css', false, '1.0.0', 'all');
wp_enqueue_script('jquery-ui-datepicker');
wp_register_script('printPreview', get_template_directory_uri().'/js/printPreview.js');
wp_enqueue_script('printPreview');
wp_register_style('jquery-ui', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css');
wp_enqueue_style('jquery-ui');
wp_register_script('chartjs', 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js');
wp_enqueue_script('chartjs');
wp_register_script('chartutils', get_template_directory_uri().'/js/Chart.js-master/samples/utils.js');
wp_enqueue_script('chartutils');
wp_register_script('chartPieceLabel', get_template_directory_uri().'/js/Chart.js-master/samples/Chart.PieceLabel.min.js');
wp_enqueue_script('chartPieceLabel');
//--------Custom Admin Page------------//
add_action( 'admin_menu', 'my_admin_menu' );

function my_admin_menu() {
	add_menu_page( 'สถิติและรายงาน', 'สถิติและรายงาน', 'edit_posts', 'myplugin/stats_page.php', 'stats_page', 'dashicons-analytics', 6  );
	add_submenu_page( 'myplugin/stats_page.php','เรื่องร้องเรียน', 'เรื่องร้องเรียน', 'edit_posts', 'myplugin/stats_inform_page.php', 'stats_inform_page' );
	add_submenu_page('myplugin/stats_page.php', 'ปรึกษากฎหมาย', 'ปรึกษากฎหมาย', 'edit_posts', 'myplugin/stats_legal_page.php', 'stats_legal_page');
	add_submenu_page( 'myplugin/stats_page.php','ศูนย์ข้อมูลคนหาย', 'ศูนย์ข้อมูลคนหาย', 'edit_posts', 'myplugin/stats_missing_page.php', 'stats_missing_page');
	add_menu_page( 'ดูข้อมูล', 'ดูข้อมูล', 'edit_posts', 'myplugin/viewPost.php', 'viewPost', 'dashicons-analytics', 6  );
}

	

function stats_inform_page(){
	$default_after_date = date('Y-m-01');
	$default_before_date = date('Y-m-d');
	// not search option
	if(empty($_POST)){
		$query = array(
				'post_type'	=> 'inform',
				'date_query' => array(
						array(
							'after'     => $default_after_date,
							'before'    => $default_before_date,
							'inclusive' => true,
						)
					)
				);
	}else{ // have search option
		
		$query = array(
					'post_type'	=> 'inform',
					'meta_query'	=> array(
						'relation'		=> 'AND',
					)
				
				);
		// search by date
		if(!empty($_POST['start_date']) && !empty($_POST['end_date'])){
			$exstart = explode('/',$_POST['start_date']);
			$exend = explode('/',$_POST['end_date']);
			$afterDate = $exstart[2]."-".$exstart[1]."-".$exstart[0];
			$beforeDate = $exend[2]."-".$exend[1]."-".$exend[0];
		}
		else{
			$afterDate = $default_after_date;
			$beforeDate = $default_before_date;
		}
		
		// search by status
		if($_POST['status'] == 2){
			$query['meta_query'][0] = array(
				'key'	 	=> 'status',
				'value'	  	=> 3,
				'compare' 	=> '=',
			);
		}
		$query['date_query'] = array(
						array(
							'after'     => $afterDate,
							'before'    => $beforeDate,
							'inclusive' => true,
						)
					);
	}
	// get inform type
	$inform_type = get_posts(array(
						'post_type'	=> 'inform_type',
						)
					);
					
	// get channel type
	$channel_type = get_field_object('field_599fcb7200df2');
	// query all inform
	$data = get_posts($query);
	// get only type and status 
	foreach($data as $key=>$row){
		$type[] = get_field('inform_type',$row->ID);
		$channel[] = get_field('receive_channel',$row->ID)['value'];
	}
	// group data
	$group_type = array_count_values($type);
	$group_channel = array_count_values($channel);
	?>
	<div class="wrap">
		<h2>สถิติและรายงาน > เรื่องร้องเรียน</h2>
		<div class="row">
			<div class="col-lg-8">
				<form class="form-horizontal" method="post" id="search-form" style="margin-top:10px">
					
						<label class="col-lg-2">ช่วงเวลา</label>
						<div class="col-lg-3">
							<input type="text" name="start_date" class="form-control datepicker" placeholder="เริ่มต้น" />
						</div>
						<div class="col-lg-3">
							<input type="text" name="end_date" class="form-control datepicker" placeholder="สิ้นสุด"/>
						</div>
						<label class="col-lg-1">สถานะ</label>
						<div class="col-lg-2">
							<select name="status" class="form-control">
								<option value="1" <?php echo empty($_POST['status']) || $_POST['status']==1 ? 'selected' : NULL ?>>ทั้งหมด</option>
								<option value="2" <?php echo !empty($_POST['status']) && $_POST['status']==2 ? 'selected' : NULL ?>>ออกอากาศ</option>
							</select>
						</div>
						<div class="col-lg-1">
							<input type="submit" class="btn btn-primary" value="ค้นหา"/>
						</div>
				</form>
			</div>
			<div class="col-lg-4">
				<div style="text-align:right">
					ข้อมูลวันที่ : <?php echo DateThai($query['date_query'][0]['after'])?> - <?php echo DateThai($query['date_query'][0]['before'])?></span>
				</div>
			</div>
		</div>
		<div class="row">
		<div id="dashboard-widgets" class="metabox-holder col-lg-6">
			<div class="panel panel-default">
			  <div class="panel-heading">ประเภท</div>
			  <div class="panel-body">
				<div class="chart-container">
					<canvas id="chart-area-type"></canvas>
				</div>
			  </div>
			</div>
		</div>
		<div id="dashboard-widgets" class="metabox-holder col-lg-6">
			<div class="panel panel-default">
			  <div class="panel-heading">ช่องทางการติดต่อ</div>
			  <div class="panel-body">
				<div class="chart-container">
					<canvas id="chart-area-channel"></canvas>
				</div>
			  </div>
			</div>
		</div>
		</div>
	</div>
	<script  type="text/javascript">
	jQuery(function($) { 
		$('.datepicker').datepicker();	
		    var config = {
				type: 'doughnut',
				data: {
					datasets: [{
						data: [
							<?php foreach($inform_type as $key=>$row) { ?>
								<?php echo ($group_type[$row->ID] > 0) ? $group_type[$row->ID] : 0 ?>,
							<?php } ?>
						],
						backgroundColor: [
							<?php foreach($inform_type as $key=>$row) { ?>
								'<?php echo get_field('color',$row->ID)?>',
							<?php } ?>
						],
						label: 'ประเภท'
					}],
					labels: [
					   <?php foreach($inform_type as $key=>$row) { ?>
								"<?php echo $row->post_title?>",
					   <?php } ?>
					]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					legend: {
								position: 'right',
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
					<?php foreach($channel_type['choices'] as $key=>$row) { ?>
						<?php echo ($group_channel[$key] > 0) ? $group_channel[$key] : 0 ?>,
					<?php } ?>
                ],
                backgroundColor: [
					window.chartColors.red,
					window.chartColors.purple,
					window.chartColors.yellow,
					window.chartColors.green,
					window.chartColors.orange,
					window.chartColors.grey,
                ],
                label: 'จำนวน'
            }],
            labels: [
             <?php foreach($channel_type['choices'] as $key=>$row) { ?>
			   '<?php echo $row?>',
			 <?php } ?>
            ]
        },
        options: {
            responsive: true,
			maintainAspectRatio: false,
			legend: {
                        position: 'right',
						labels: {
							fontColor: 'black',
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
	
		window.onload = function() {
        var ctx1 = document.getElementById("chart-area-type").getContext("2d");
			window.myPie = new Chart(ctx1, config);
			
		 var ctx2 = document.getElementById("chart-area-channel").getContext("2d");
			window.myPie = new Chart(ctx2, config2);
	
		};
		
	})
	</script>
	<?php
}

function stats_legal_page(){
	
	$default_after_date = date('Y-m-01');
	$default_before_date = date('Y-m-d');
	// not search option
	if(empty($_POST)){
		$query = array(
				'post_type'	=> 'legal',
				'date_query' => array(
						array(
							'after'     => $default_after_date,
							'before'    => $default_before_date,
							'inclusive' => true,
						)
					)
				);
	}else{ // have search option
		
		$query = array(
					'post_type'	=> 'legal',
					'meta_query'	=> array(
						'relation'		=> 'AND',
					)
				
				);
		// search by date
		if(!empty($_POST['start_date']) && !empty($_POST['end_date'])){
			$exstart = explode('/',$_POST['start_date']);
			$exend = explode('/',$_POST['end_date']);
			$afterDate = $exstart[2]."-".$exstart[1]."-".$exstart[0];
			$beforeDate = $exend[2]."-".$exend[1]."-".$exend[0];
		}
		else{
			$afterDate = $default_after_date;
			$beforeDate = $default_before_date;
		}
		
		// search by status
		if($_POST['status'] == 2){
			$query['meta_query'][0] = array(
				'key'	 	=> 'status',
				'value'	  	=> 3,
				'compare' 	=> '=',
			);
		}
		$query['date_query'] = array(
						array(
							'after'     => $afterDate,
							'before'    => $beforeDate,
							'inclusive' => true,
						)
					);
	}
	// get inform type
	$civil_type = get_posts(array(
						'post_type'	=> 'legal_civil',
						)
					);
	$crime_type = get_posts(array(
						'post_type'	=> 'legal_crime',
						)
					);
					
	// get channel type
	$channel_type = get_field_object('field_59a000bcc3826');
	// query all inform
	$data = get_posts($query);
	// get only type and status 
	foreach($data as $key=>$row){
		if(get_field('inform_type',$row->ID) == 1){
			$type_civil[] = get_field('sub_topic_civil',$row->ID)->ID;
			
		}
		else{
			$type_crime[] = get_field('sub_topic_crime',$row->ID)->ID;
		}
		
	
		$channel[] = get_field('receive_channel',$row->ID)['value'];
	}
	
	// group data
	$group_type_civil = array_count_values($type_civil);
	$group_type_crime = array_count_values($type_crime);
	$group_channel = array_count_values($channel);
	?>
	<div class="wrap">
		<h2>สถิติและรายงาน > ปรึกษากฎหมาย</h2>
		<div class="row">
			<div class="col-lg-8">
				<form class="form-horizontal" method="post" id="search-form" style="margin-top:10px">
					
						<label class="col-lg-2">ช่วงเวลา</label>
						<div class="col-lg-3">
							<input type="text" name="start_date" class="form-control datepicker" placeholder="เริ่มต้น" />
						</div>
						<div class="col-lg-3">
							<input type="text" name="end_date" class="form-control datepicker" placeholder="สิ้นสุด"/>
						</div>
						<label class="col-lg-1">สถานะ</label>
						<div class="col-lg-2">
							<select name="status" class="form-control">
								<option value="1" <?php echo empty($_POST['status']) || $_POST['status']==1 ? 'selected' : NULL ?>>ทั้งหมด</option>
								<option value="2" <?php echo !empty($_POST['status']) && $_POST['status']==2 ? 'selected' : NULL ?>>ออกอากาศ</option>
							</select>
						</div>
						<div class="col-lg-1">
							<input type="submit" class="btn btn-primary" value="ค้นหา"/>
						</div>
				</form>
			</div>
			<div class="col-lg-4">
				<div style="text-align:right">
					ข้อมูลวันที่ : <?php echo DateThai($query['date_query'][0]['after'])?> - <?php echo DateThai($query['date_query'][0]['before'])?></span>
				</div>
			</div>
		</div>
		<div class="row">
			<div id="dashboard-widgets" class="metabox-holder col-lg-6">
				<div class="panel panel-default">
				  <div class="panel-heading">ประเภทคดี</div>
				  <div class="panel-body">
					<div class="chart-container">
						<canvas id="chart-area-type"></canvas>
					</div>
				  </div>
				</div>
			</div>
			<div id="dashboard-widgets" class="metabox-holder col-lg-6">
				<div class="panel panel-default">
				  <div class="panel-heading">ช่องทางการติดต่อ</div>
				  <div class="panel-body">
					<div class="chart-container">
						<canvas id="chart-area-channel"></canvas>
					</div>
				  </div>
				</div>
			</div>
		</div>
		<div class="row">
			<div id="dashboard-widgets" class="metabox-holder col-lg-6">
				<div class="panel panel-default">
				  <div class="panel-heading">ประเภท > คดีแพ่ง </div>
				  <div class="panel-body">
					<div class="chart-container">
						<canvas id="chart-area-civil"></canvas>
					</div>
				  </div>
				</div>
			</div>
			<div id="dashboard-widgets" class="metabox-holder col-lg-6">
				<div class="panel panel-default">
				  <div class="panel-heading">ประเภท > คดีอาญา</div>
				  <div class="panel-body">
					<div class="chart-container">
						<canvas id="chart-area-crime"></canvas>
					</div>
				  </div>
				</div>
			</div>
		</div>
		
		
		</div>
	</div>
	<script  type="text/javascript">
	jQuery(function($) { 
		$('.datepicker').datepicker();	
		
		 var config_type = {
				type: 'doughnut',
				data: {
					datasets: [{
						data: [
							<?php echo count($type_civil)?>,
							<?php echo count($type_crime)?>
						],
						backgroundColor: [
							window.chartColors.red,
							window.chartColors.green
						],
						label: 'ประเภท'
					}],
					labels: [
					  'คดีแพ่ง',
					  'คดีอาญา'
					]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					legend: {
								position: 'right',
							},
					pieceLabel: {
						render: 'percentage',
						fontSize: 16,
						fontColor: '#fff'
					}
				}
			};
			
		    var config_civil = {
				type: 'doughnut',
				data: {
					datasets: [{
						data: [
							<?php foreach($civil_type as $key=>$row) { ?>
								<?php echo ($group_type_civil[$row->ID] > 0) ? $group_type_civil[$row->ID] : 0 ?>,
							<?php } ?>
						],
						backgroundColor: [
							<?php foreach($civil_type as $key=>$row) { ?>
								'<?php echo get_field('color',$row->ID)?>',
							<?php } ?>
						],
						label: 'ประเภท'
					}],
					labels: [
					  <?php foreach($civil_type as $key=>$row) { ?>
								"<?php echo $row->post_title?>",
					   <?php } ?>
					]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					legend: {
								position: 'right',
							},
					pieceLabel: {
						render: 'percentage',
						fontSize: 16,
						fontColor: '#fff'
					}
				}
			};
			
	 var config_crime = {
				type: 'doughnut',
				data: {
					datasets: [{
						data: [
							<?php foreach($crime_type as $key=>$row) { ?>
								<?php echo ($group_type_crime[$row->ID] > 0) ? $group_type_crime[$row->ID] : 0 ?>,
							<?php } ?>
						],
						backgroundColor: [
							<?php foreach($crime_type as $key=>$row) { ?>
								'<?php echo get_field('color',$row->ID)?>',
							<?php } ?>
						],
						label: 'ประเภท'
					}],
					labels: [
					  <?php foreach($crime_type as $key=>$row) { ?>
								"<?php echo $row->post_title?>",
					   <?php } ?>
					]
				},
				options: {
					responsive: true,
					maintainAspectRatio: false,
					legend: {
								position: 'right',
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
					<?php foreach($channel_type['choices'] as $key=>$row) { ?>
						<?php echo ($group_channel[$key] > 0) ? $group_channel[$key] : 0 ?>,
					<?php } ?>
                ],
                backgroundColor: [
					window.chartColors.red,
					window.chartColors.purple,
					window.chartColors.yellow,
					window.chartColors.green,
					window.chartColors.orange,
					window.chartColors.grey,
                ],
                label: 'จำนวน'
            }],
            labels: [
             <?php foreach($channel_type['choices'] as $key=>$row) { ?>
			   '<?php echo $row?>',
			 <?php } ?>
            ]
        },
        options: {
            responsive: true,
			maintainAspectRatio: false,
			legend: {
                        position: 'right',
						labels: {
							fontColor: 'black',
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
	
		window.onload = function() {
			var ctx0 = document.getElementById("chart-area-type").getContext("2d");
			window.myPie = new Chart(ctx0, config_type);
			
        var ctx1 = document.getElementById("chart-area-civil").getContext("2d");
			window.myPie = new Chart(ctx1, config_civil);
			
		 var ctx2 = document.getElementById("chart-area-crime").getContext("2d");
			window.myPie = new Chart(ctx2, config_crime);
			
		var ctx3 = document.getElementById("chart-area-channel").getContext("2d");
			window.myPie = new Chart(ctx3, config2);
	
		};
		
	})
	</script>
	<?php
}

function stats_missing_page(){
	if(empty($_POST)){
		$afterDate = date('Y-m-01');
		$beforeDate = date('Y-m-d');
	}
	else{
		$exstart = explode('/',$_POST['start_date']);
		$exend = explode('/',$_POST['end_date']);
		$afterDate = $exstart[2]."-".$exstart[1]."-".$exstart[0];
		$beforeDate = $exend[2]."-".$exend[1]."-".$exend[0];
	}
	
	$data = get_posts(array(
		'post_type'	=> 'missing_people',
		'date_query' => array(
				array(
					'after'     => $afterDate,
					'before'    => $beforeDate,
					'inclusive' => true,
				),
		),
	));

	foreach($data as $key=>$row){
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
	<div class="wrap">
		<h2>สถิติและรายงาน > ศูนย์ข้อมูลคนหาย</h2>
		<div class="row">
			<div class="col-lg-6">
				<form class="form-horizontal" method="post" id="search-form" style="margin-top:10px">
					
						<label class="col-lg-3">ช่วงเวลา</label>
						<div class="col-lg-4">
							<input type="text" name="start_date" class="form-control datepicker" placeholder="เริ่มต้น" />
						</div>
						<div class="col-lg-4">
							<input type="text" name="end_date" class="form-control datepicker" placeholder="สิ้นสุด"/>
						</div>
						<div class="col-lg-1">
							<input type="submit" class="btn btn-primary" value="ค้นหา"/>
						</div>
				</form>
			</div>
			<div class="col-lg-6">
				<div style="text-align:right">
					ข้อมูลวันที่ : <?php echo DateThai($afterDate)?> - <?php echo DateThai($beforeDate)?></span>
				</div>
			</div>
		</div>
		<div class="row">
		<div id="dashboard-widgets" class="metabox-holder col-lg-6">
			<div class="panel panel-default">
			  <div class="panel-heading">เพศ</div>
			  <div class="panel-body">
				<div class="chart-container">
					<canvas id="chart-area-gender"></canvas>
				</div>
			  </div>
			</div>
		</div>
		<div id="dashboard-widgets" class="metabox-holder col-lg-6">
			<div class="panel panel-default">
			  <div class="panel-heading">ผลการติดตาม</div>
			  <div class="panel-body">
				<div class="chart-container">
					<canvas id="chart-area-status"></canvas>
				</div>
			  </div>
			</div>
		</div>
		<div id="dashboard-widgets" class="metabox-holder col-lg-12">
			<div class="panel panel-default">
			  <div class="panel-heading">อายุ</div>
			  <div class="panel-body">
				<div class="chart-container" style="min-height: 500px;">
					<canvas id="chart-area-age"></canvas>
				</div>
			  </div>
			</div>
		</div>
	</div>
	</div>
<script  type="text/javascript">
	jQuery(function($) { 
		$('.datepicker').datepicker();	
	
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
				'ชาย',
				'หญิง'
            ]
        },
        options: {
            responsive: true,
			maintainAspectRatio: false,
			title : {
				display : true,
				text : 'เพศ',
				position: 'top',
				fontColor: 'black',
				fontFamily: 'Sukhumvit_settext',
				fontSize :24,
				padding:20
			},
			legend: {
                        position: 'right',
						labels: {
							fontColor: 'black',
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
				fontColor: 'black',
				fontFamily: 'Sukhumvit_settext',
				fontSize :24,
				padding:20
			},
			legend: {
                        position: 'right',
						labels: {
							fontColor: 'black',
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
						fontColor: 'black',
						fontFamily: 'Sukhumvit_settext',
						fontSize :24,
						padding:20
					},
					legend: {
								position: 'bottom',
								labels: {
									fontColor: 'black',
									fontFamily: 'Sukhumvit_settext',
									fontSize : 16
								}
					},
					scales: {
						yAxes: [{
							ticks: {
								fontColor: "black",
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
								fontColor: "black",
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
});
</script>
<?php
}

//------------View Page------------//
function viewPost(){
	$post_type_obj = get_post_type_object( $_GET['post_type'] ); 
	$post_id = $_GET['post'];
	?>
	<div class="wrap">
		<h2><?php echo $post_type_obj->label?> > <?php echo $_GET['post_type'] <> 'missing_people' ?  get_field('topic',$post_id) : get_field( 'front_name',$post_id)['label'].get_field( 'fname',$post_id)." ".get_field( 'lname',$post_id) ?> 
		<div class="pull-right" style="margin-bottom:10px">
			<a href="edit.php?post_type=<?php echo $_GET['post_type']?>"  class="btn btn-default"><i class="fa fa-arrow-left"></i> กลับ</a>
			<a href="javascript:void(0)" id="btnPrint"  class="btn btn-primary" id="doPrint"><i class="fa fa-print"></i> พิมพ์</a>
		</div></h2>
		
		<?php if($_GET['post_type'] == 'inform'){ ?>
		<div class="row">
			<div class="col-lg-12" id="print-area">
				
				 <div class="panel panel-default">
					<div class="panel-heading">ประเด็น</div>
					<div class="panel-body">
						<div class="pull-left postID"><strong>ประเภทเอกสาร  : </strong> <?php echo $post_type_obj->label?></div>
						<div class="pull-right postID"><strong>เลขที่  : </strong> <?php echo postCode($_GET['post_type'],$post_id)?></div>
						<table class="table">
							<tr>
								<th width="20%">ชื่อผู้แจ้ง</th>
								<td><?php echo get_field('inform_name',$post_id)?></td>
							</tr>
							<tr>
								<th width="20%">เบอร์โทรศัพท์</th>
								<td><?php echo get_field('phone',$post_id)?></td>
							</tr>
							<tr>
								<th width="20%">ที่อยู่ที่ติดต่อได้</th>
								<td><?php echo get_field('address',$post_id)?></td>
							</tr>
							<tr>
								<th width="20%">จังหวัดที่เกิดเหตุ</th>
								<td><?php echo get_field('province',$post_id)['label']?></td>
							</tr>
							<tr>
								<th width="20%">ประเด็นปัญหา</th>
								<td><?php echo get_field('topic',$post_id)?></td>
							</tr>
							<tr>
								<th width="20%">รายละเอียด</th>
								<td><?php echo get_field('description',$post_id)?></td>
							</tr>
						</table>
					</div>
				  </div>
				  <div class="panel panel-default">
					<div class="panel-heading">ข้อมูลสำหรับเจ้าหน้าที่</div>
					<div class="panel-body">
						<table class="table">
							<tr>
								<th width="20%">ประเภท</th>
								<td>
								<?php echo get_the_title(get_field('inform_type',$post_id))?></td>
							</tr>
							<tr>
								<th width="20%">หัวข้อย่อย</th>
								<td><?php echo get_field('subtopic',$post_id)?></td>
							</tr>
							<tr>
								<th width="20%">ลำดับความสำคัญ</th>
								<td><?php echo get_field('priority',$post_id)['label']?></td>
							</tr>
							<tr>
								<th width="20%">ผู้รับผิดชอบ</th>
								<td>
								
								<?php echo get_field('officer_name',$post_id)['display_name']?></td>
							</tr>
							<tr>
								<th width="20%">วัน/เวลารับเรื่อง</th>
								<td>
								<?php 
								$date = get_field('receive_date',$post_id);
								$time = get_field('receive_time',$post_id);
								$date = new DateTime($date);
								$time = new DateTime($time);
								
								echo $date->format('d/m/Y')." ".$time->format('H:i')
								?>
								</td>
							</tr>
							<tr>
								<th width="20%">ช่องทางการรับเรื่อง</th>
								<td>
								<?php echo get_field('receive_channel',$post_id)['label']?></td>
							</tr>
							<tr>
								<th width="20%">สถานะ</th>
								<td><?php echo get_field('status',$post_id)['label']?></td>
							</tr>
						</table>
					</div>
				  </div>
				
			</div>
			
		</div>
		<?php } elseif($_GET['post_type'] == 'legal') {  ?>
		<div class="row">
			<div class="col-lg-12" id="print-area">
			
				 <div class="panel panel-default">
					<div class="panel-heading">ประเด็น</div>
					<div class="panel-body">
						<div class="pull-left postID"><strong>ประเภทเอกสาร  : </strong> <?php echo $post_type_obj->label?></div>
						<div class="pull-right postID"><strong>เลขที่  : </strong> <?php echo postCode($_GET['post_type'],$post_id)?></div>
						<table class="table">
							<tr>
								<th width="20%">ชื่อผู้แจ้ง</th>
								<td><?php echo get_field('inform_name',$post_id)?></td>
							</tr>
							<tr>
								<th width="20%">เบอร์โทรศัพท์</th>
								<td><?php echo get_field('phone',$post_id)?></td>
							</tr>
							<tr>
								<th width="20%">ที่อยู่ที่ติดต่อได้</th>
								<td><?php echo get_field('address',$post_id)?></td>
							</tr>
							<tr>
								<th width="20%">จังหวัดที่เกิดเหตุ</th>
								<td><?php echo get_field('province',$post_id)['label']?></td>
							</tr>
							<tr>
								<th width="20%">ประเด็นปัญหา</th>
								<td><?php echo get_field('topic',$post_id)?></td>
							</tr>
							<tr>
								<th width="20%">รายละเอียด</th>
								<td><?php echo get_field('description',$post_id)?></td>
							</tr>
						</table>
					</div>
				  </div>
				  <div class="panel panel-default">
					<div class="panel-heading">ข้อมูลสำหรับเจ้าหน้าที่</div>
					<div class="panel-body">
						<table class="table">
							<tr>
								<th width="20%">ประเภท</th>
								<td>
								<?php echo get_field('inform_type',$post_id)['label']?></td>
							</tr>
							<tr>
								<th width="20%">หัวข้อย่อย</th>
								<td>
								<?php 
								if(get_field('inform_type',$post_id)['value'] == 1){
									$subfield = 'sub_topic_civil';
								}
								else{
									$subfield = 'sub_topic_crime';
								}
								echo get_the_title(get_field($subfield,$post_id))?>
								
								</td>
							</tr>
							<tr>
								<th width="20%">ลำดับความสำคัญ</th>
								<td><?php echo get_field('priority',$post_id)['label']?></td>
							</tr>
							<tr>
								<th width="20%">ผู้รับผิดชอบ</th>
								<td>
								
								<?php echo get_field('officer_name',$post_id)['display_name']?></td>
							</tr>
							<tr>
								<th width="20%">วัน/เวลารับเรื่อง</th>
								<td>
								<?php 
								$date = get_field('receive_date',$post_id);
								$time = get_field('receive_time',$post_id);
								$date = new DateTime($date);
								$time = new DateTime($time);
								
								echo $date->format('d/m/Y')." ".$time->format('H:i')
								?>
								</td>
							</tr>
							<tr>
								<th width="20%">ช่องทางการรับเรื่อง</th>
								<td>
								<?php echo get_field('receive_channel',$post_id)['label']?></td>
							</tr>
							<tr>
								<th width="20%">สถานะ</th>
								<td><?php echo get_field('status',$post_id)['label']?></td>
							</tr>
						</table>
					</div>
				  </div>
				
			</div>
		</div>
		<?php } else { ?>
		<div class="row">
			<div class="col-lg-12" id="print-area">
				
				 <div class="panel panel-default">
					<div class="panel-heading">ข้อมูลส่วนตัวคนหาย</div>
					<div class="panel-body">
					<div class="pull-left postID"><strong>ประเภทเอกสาร  : </strong> <?php echo $post_type_obj->label?></div>
						<div class="pull-right postID"><strong>เลขที่  : </strong> <?php echo postCode($_GET['post_type'],$post_id)?></div>
						<table class="table">
							<tr>
								<th width="20%">ชื่อ - นามสกุล</th>
								<td><?php echo get_field( 'front_name',$post_id)['label'].get_field( 'fname',$post_id)." ".get_field( 'lname',$post_id)?></td>
							</tr>
							<tr>
								<th width="20%">ชื่อเดิม</th>
								<td><?php echo get_field( 'old_fname',$post_id)?></td>
							</tr>
							<tr>
								<th width="20%">ชื่อเล่น</th>
								<td><?php echo get_field( 'nickname',$post_id)?></td>
							</tr>
							<tr>
								<th width="20%">เพศ</th>
								<td><?php echo get_field( 'gender',$post_id)['label']?></td>
							</tr>
							<tr>
								<th width="20%">วัน/เดือน / ปี เกิด</th>
								<td><?php
								$date = get_field('birthday',$post_id);
								$date = new DateTime($date);
								echo $date->format('d/m/Y');
								?></td>
							</tr>
							<tr>
								<th width="20%">อายุ</th>
								<td><?php echo calAge(get_field('birthday',$post_id)) ?></td>
							</tr>
							<tr>
								<th width="20%">โรคประจำตัว</th>
								<td><?php echo get_field('health_disorder',$post_id) ?></td>
							</tr>
							<tr>
								<th width="20%">อาการ</th>
								<td>
								<?php echo get_field('other_disorder',$post_id)['label'] ?>
								<?php if( get_field('other_disorder',$post_id)['value'] == 3){ 
									echo get_field('disabled',$post_id);
								} ?>
								</td>
							</tr>
							<tr>
								<th width="20%">รูปพรรณสัณฐาน</th>
								<td><?php echo get_field('morphology',$post_id) ?></td>
							</tr>
							<tr>
								<th width="20%">ความสูง</th>
								<td><?php echo get_field('height',$post_id) ?> เซนติเมตร</td>
							</tr>
							<tr>
								<th width="20%">น้ำหนัก</th>
								<td><?php echo get_field('weight',$post_id) ?> กิโลกรัม</td>
							</tr>
							<tr>
								<th width="20%">รายละเอียดอื่นๆ</th>
								<td><?php echo get_field('other_description',$post_id) ?></td>
							</tr>
							<tr>
								<th width="20%">ภาพ</th>
								<td>
								<img src="<?php echo get_field('image',$post_id)['sizes']['medium'] ?>" />
								<div style="margin-top:10px"><a href="<?php bloginfo('url')?>/process?fn=downloadImage&post=<?php echo $post_id?>" class="btn btn-primary btn-xs"><i class="fa fa-download"></i> ดาวน์โหลด</a></div>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">ข้อมูลการหาย</div>
					<div class="panel-body">
						<table class="table">
							<tr>
								<th width="20%">จุดที่หายตัวไป</th>
								<td><?php echo get_field( 'missing_point',$post_id)['label'] ?></td>
							</tr>
							<tr>
								<th width="20%">รายละเอียดจุดที่หาย</th>
								<td>
								<strong>บ้านเลขที่</strong> <?php echo get_field( 'house_no',$post_id) ?><br/>
								<strong>หมู่บ้าน</strong> <?php echo get_field( 'villege',$post_id)?><br/>
								<strong>ถนน</strong> <?php echo get_field( 'road',$post_id)?><br/>
								<strong>ตำบล/แขวง</strong> <?php echo get_field( 'subdistrict',$post_id)?><br/>
								<strong>อำเภอ/เขต</strong> <?php echo get_field( 'district',$post_id)?><br/>
								<strong>จังหวัด</strong> <?php echo get_field( 'province',$post_id)['label']?><br/>
								</td>
							</tr>
							<tr>
								<th width="20%">วัน/เวลา ที่หายตัวไป</th>
								<td><?php
								$missdate = get_field('missing_date',$post_id);
								$misstime = get_field('missing_time',$post_id);
								$missdate = new DateTime($missdate);
								$misstime = new DateTime($misstime);
								echo $missdate->format('d/m/Y')." ".$misstime->format('H:i');
								?></td>
							</tr>
							<tr>
								<th width="20%">สาเหตุที่หายตัวไป</th>
								<td><?php echo get_field('missing_cause',$post_id)['label'] ?></td>
							</tr>
							<tr>
								<th width="20%">ข้อมูลพบเห็นล่าสุด</th>
								<td><?php echo get_field('last_seeing',$post_id) ?></td>
							</tr>
							<tr>
								<th width="20%">รายละเอียดผู้แจ้ง</th>
								<td>
								<strong>ชื่อผู้แจ้ง</strong> <?php echo get_field( 'notice_name',$post_id) ?><br/>
								<strong>ความสัมพันธ์ </strong><?php echo get_field( 'notice_relationship',$post_id)?><br/>
								<strong>หมายเลขโทรศัพท์ </strong><?php echo get_field( 'notice_phone',$post_id)?><br/>
								<strong>อีเมล</strong> <?php echo get_field( 'notice_email',$post_id)?><br/>
								</td>
							</tr>
							<tr>
								<th width="20%">สถานีตำรวจที่แจ้งความ</th>
								<td>
								<strong>สำเนาใบแจ้งความ</strong> <?php echo get_field( 'police_document',$post_id)['label'] ?><br/>
								<strong>ชื่อสถานีตำรวจ</strong> <?php echo get_field( 'police_station',$post_id)?><br/>
								<strong>วันที่แจ้งความ</strong> <?php echo get_field( 'police_officer',$post_id)?><br/>
								<strong>เจ้าหน้าที่รับเรื่อง</strong> <?php echo get_field( 'police_phone',$post_id)?><br/>
								</td>
							</tr>
						</table>
					</div>
				</div>
				  <div class="panel panel-default">
					<div class="panel-heading">ข้อมูลสำหรับเจ้าหน้าที่</div>
					<div class="panel-body">
						<table class="table">
							<tr>
								<th width="20%">ชื่อ - นามสกุล</th>
								<td>
								
								<?php echo get_field('officer_name',$post_id)['display_name']?></td>
							</tr>
							
							
							<tr>
								<th width="20%">วัน/เวลารับเรื่อง</th>
								<td>
								<?php 
								$date = get_field('receive_date',$post_id);
								$time = get_field('receive_time',$post_id);
								$date = new DateTime($date);
								$time = new DateTime($time);
								
								echo $date->format('d/m/Y')." ".$time->format('H:i')
								?>
								</td>
							</tr>
							<tr>
								<th width="20%">ช่องทางการรับเรื่อง</th>
								<td>
								<?php echo get_field('receive_channel',$post_id)['label']?></td>
							</tr>
							<tr>
								<th width="20%">ลำดับความสำคัญ</th>
								<td><?php echo get_field('priority',$post_id)['label']?></td>
							</tr>
							<tr>
								<th width="20%">สถานะ</th>
								<td><?php echo get_field('status',$post_id)['label']?></td>
							</tr>
						</table>
					</div>
				  </div>
				
			</div>
		</div>
		<?php } ?>
		
	</div>
	<script type="text/javascript">
		  jQuery(function($) { 
					$("#btnPrint").printPreview({
						obj2print:'#print-area',
						width:'810'
					});
				});
		</script>
	<?php
}




/*function posts_where_missing_fname( $where ) {  
    $where = str_replace("meta_key = 'fname'", "meta_key   LIKE 'fname_%", $where);
    return $where;
}
function posts_where_missing_lname( $where ) {  
     $where = str_replace("meta_key = 'lname'", "meta_key   LIKE 'lname_%", $where);
    return $where;
}

add_filter( 'posts_where' , 'posts_where_missing_fname' );
add_filter( 'posts_where' , 'posts_where_missing_lname' );*/

//--------Custom Back-end Post Table------------//
//1. Inform & Legal
add_filter('manage_inform_posts_columns', 'columns_head_only_inform_legal');
add_filter('manage_legal_posts_columns', 'columns_head_only_inform_legal');
function columns_head_only_inform_legal($defaults) {
	
	$defaults['createDate'] = 'วันที่';
	$defaults['code'] = 'เลขที่';
	$defaults['topic'] = 'ประเด็น';
	$defaults['names'] = 'ชื่อผู้แจ้ง';
	$defaults['province'] = 'จังหวัด';
	$defaults['officer'] = 'ผู้รับผิดชอบ';
	$defaults['status'] = 'สถานะ';
    return $defaults;
}

add_action('manage_inform_posts_custom_column', 'columns_content_only_inform_legal', 10, 2);
add_action('manage_legal_posts_custom_column', 'columns_content_only_inform_legal', 10, 2);
function columns_content_only_inform_legal($column_name, $post_id) {
	
	if ($column_name == 'createDate') {
		 echo get_the_date( 'd/m/Y H:i', $post_id );
	}
	
	if ($column_name == 'code') {
		 echo postCode($_GET['post_type'],$post_id);
	}
	
	if ($column_name == 'topic') {
		$status  = get_field('priority',$post_id);
		$channel = get_field('receive_channel' , $post_id);
		$rowHtml = '<div>'.get_field('topic',$post_id).'</div>';
		$rowHtml .= '<div><span title="ความสำคัญ : '.$status['label'].'" class="label-priority" style="color:'.colorPriority($status['value']).'"><i class="fa fa-exclamation-circle" aria-hidden="true" ></i></span>'.iconChannel($channel).'</div>';
		echo $rowHtml;
	}
	if ($column_name == 'names') {
		 echo get_field( 'inform_name',$post_id);
	}
	
	if ($column_name == 'province') {
		echo get_field('province',$post_id)['label'];
	}
	if ($column_name == 'officer') {
		echo get_field('officer_name',$post_id)['display_name'];
	}
	
    if ($column_name == 'status') {
		$status = get_field( 'status',$post_id);
		echo '<span style="color:'.colorStatus($status['value']).'">'.$status['label'].'</span>';	
    }
}

//3. Missing People
add_filter('manage_missing_people_posts_columns', 'columns_head_only_missing_people');
function columns_head_only_missing_people($defaults) {
	$defaults['createDate'] = 'วันที่';
	$defaults['code'] = 'เลขที่';
	$defaults['names'] = 'ชื่อ - นามสกุล';
	$defaults['province'] = 'จังหวัด';
	$defaults['officer'] = 'รับเรื่องโดย';
	$defaults['status'] = 'สถานะ';
    return $defaults;
}

add_action('manage_missing_people_posts_custom_column', 'columns_content_only_missing_people', 10, 2);
function columns_content_only_missing_people($column_name, $post_id) {
	if ($column_name == 'createDate') {
		 echo get_the_date( 'd/m/Y H:i', $post_id );
	}
	if ($column_name == 'code') {
		 echo postCode($_GET['post_type'],$post_id);
	}
	if ($column_name == 'names') {
		$status  = get_field('priority',$post_id);
		$channel = get_field('receive_channel' , $post_id);

		$rowHtml = '<div>'.get_field( 'front_name',$post_id)['label'].get_field( 'fname',$post_id)." ".get_field( 'lname',$post_id).'</div>';
		$rowHtml .= '<div><span title="ความสำคัญ : '.$status['label'].'" class="label-priority" style="color:'.colorPriority($status['value']).'"><i class="fa fa-exclamation-circle" aria-hidden="true" ></i></span>'.iconChannel($channel).'</div>';
		echo $rowHtml;
	}
	if ($column_name == 'province'){
		echo get_field( 'province',$post_id)['label'];
	}
	if ($column_name == 'officer') {
		echo get_field('officer_name',$post_id)['display_name'];
	}
	
    if ($column_name == 'status') {
		$status = get_field( 'status',$post_id);
		echo '<span style="color:'.colorStatus($status['value']).'">'.$status['label'].'</span>';	
    }
}


//--------Custom Post Action Button------------//
function remove_quick_edit($actions, $post){
    $post_id = $post->ID;
    $post_type = $post->post_type;
    if($post_type == 'missing_people' || $post_type == 'legal' || $post_type == 'inform'){
	  unset($actions['view']);
      unset($actions['inline hide-if-no-js']); 
	  $actions['print'] = '<a href="admin.php?page=myplugin/viewPost.php&post='.$post_id.'&post_type='.$post_type.'" class="btn btn-xs btn-default" title="ดู"><i class="fa fa-print"></i></a>';
	  $actions['edit'] = '<a href="'.get_edit_post_link($post_id).'" class="btn btn-xs btn-default" title="แก้ไข"><i class="fa fa-pencil"></i></a>';
	  $actions['trash'] = '<a href="'.get_delete_post_link($post_id).'" class="btn btn-xs btn-default" title="ลบ"><i class="fa fa-times"></i></a>';
    }
    return $actions;
}
add_filter('post_row_actions', 'remove_quick_edit', 10, 2);

//--------WP-Head Opengraph Meta Data------------//

function opengraph() {
    global $post;
 
    if(is_page('missing-content') && !empty($_GET['id'])) {
		$id = $_GET['id'];
        ?>
 
	 <meta property="og:title" content="ศูนย์คนหายไทยพีบีเอส">
	 <meta property="og:url" content="<?php bloginfo('url')?>/missing/content?id=<?php echo $id?>">
	 <meta property="og:type" content="website">
	 <meta property="og:site_name" content="Thai PBS">
	 <meta property="og:image" content="<?php echo get_field('image',$id)['sizes']['medium'] ?>">
	 <meta property="fb:app_id" content="688208238037170">
	 <meta property="og:description" content='<?php echo get_field('front_name',$id)['label']?> <?php echo get_field('fname',$id)?> <?php echo get_field('lname',$id)?> อายุ <?php echo calAge(get_field('birthday',$id))?> ปี จังหวัด<?php echo get_field('province',$id)['label']?> หากพบเห็นแจ้งศูนย์คนหาย ไทยพีบีเอส โทร 02-790-2111'>
	 
	<meta name="twitter:card" content="summary">
	<meta name="twitter:site" content="@ThaiPBS">
	<meta name="twitter:creator" content="@ThaiPBS">
	<meta name="twitter:url" content="<?php bloginfo('url')?>/missing/content?id=<?php echo $id?> ">
	<meta name="twitter:title" content="ศูนย์คนหายไทยพีบีเอส">
	<meta name="twitter:image" content="<?php echo get_field('image',$id)[0]['sizes']['medium'] ?>">

 
<?php
    } else {
       ?>
	   	<meta property="og:title" content="Thai PBS : รายการสถานีประชาชน ร้องทุก(ข์)ลงป้ายนี้ ศูนย์คนหายไทยพีบีเอส">
		<meta property="og:url" content="<?php bloginfo('url')?>">
		<meta property="og:type" content="website">
		<meta property="og:site_name" content="Thai PBS">
		<meta property="og:image" content="<?php echo get_template_directory_uri();?>/images/fbcover.jpg">
		<meta property="fb:app_id" content="688208238037170">
		<meta property="og:description" content='ร้องเรียนร้องทุกข์ ศูนย์ข้อมูลคนหาย ปรึกษากฎหมาย เพราะทุก(ข์)ปัญหามีทางออก'>
		<?php
    }
}
add_action('wp_head', 'opengraph', 5);

//--------Functions------------//
function colorStatus($code){
	$colorArray = array(
						1 => '#d9534f',
						2 => '#f0ad4e',
						3 => '#337ab7',
						4 => '#5cb85c'
						);
	return $colorArray[$code];
}

function colorPriority($code){
	$colorArray = array(
						1 => '#337ab7',
						2 => '#f0ad4e',
						3 => '#d9534f'
						);
	return $colorArray[$code];
}

function colorChannel($code){
	$colorArray = array(
						1 => '#d9534f',
						2 => '#f0ad4e',
						3 => '#337ab7',
						4 => '#5cb85c',
						5 => '#00b900',
						6 => '#9ed0ed'
						);
	return $colorArray[$code];
}

function iconChannel($channel){
	$iconArray = array(
						1 => '<span title="ช่องทาง : '.$channel['label'].'" class="label label-default"><i class="fa fa-phone"></i></span>',
						2 => '<span title="ช่องทาง : '.$channel['label'].'" class="label label-default"><i class="fa fa-envelope"></i></span>',
						3 => '<img width="16" title="ช่องทาง : '.$channel['label'].'" src="'.get_template_directory_uri().'/images/fb-icon.png" />',
						4 => '<img width="16" title="ช่องทาง : '.$channel['label'].'" src="'.get_template_directory_uri().'/images/line-icon.png" />',
						5 => '<span title="ช่องทาง : '.$channel['label'].'" class="label label-default"><i class="fa fa-globe"></i></span>',
						6 => '<span title="ช่องทาง : '.$channel['label'].'" class="label label-default"><i class="fa fa-user"></i></span>'
						);
	return $iconArray[$channel['value']];
}
function calAge($date){
    $birthday = new DateTime($date);
    $interval = $birthday->diff(new DateTime);
    return $interval->y;
}


function do_curl($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$result=curl_exec($ch);
	curl_close($ch);
	return json_decode($result, true);
}

function getProgram($pgKey,$page = 1){
	$url = 'http://program.thaipbs.or.th/api/episodes?program='.$pgKey.'&page='.$page.'&limit=8';
	$pgObj	= do_curl($url);
	return $pgObj['data'];
}

function truncate($text, $chars = 110) {
    $text = $text." ";
    $text = mb_substr($text,0,$chars,'utf-8');
    $text = mb_substr($text,0,strrpos($text,' '),'utf-8');
    $text = $text."...";
    return $text;
}

function DateThai($strDate){
	if($strDate != ""){
		$strYear = date("Y",strtotime($strDate))+543;
		$strMonth= date("n",strtotime($strDate));
		$strDay= date("j",strtotime($strDate));
		$strHour= date("H",strtotime($strDate));
		$strMinute= date("i",strtotime($strDate));
		$strSeconds= date("s",strtotime($strDate));
		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
		$strMonthThai=$strMonthCut[$strMonth];
		return "$strDay $strMonthThai $strYear";
	}
	else{
		return "";
	}
}	

function dash($text){
	if($text == ""){
		return "-";
	}
	else{
		return $text;
	}
}

function postCode($post_type){
	if($post_type == 'inform'){
		$prefix = 'INF';
	}
	elseif($post_type == 'legal'){
		$prefix = 'LEG';
	}
	else{
		$prefix = 'MIS';
	}
	return $prefix.randomString();
}

function randomString($l = 8, $c = 0, $n = 8, $s = 0) {
   // get count of all required minimum special chars
   $count = $c + $n + $s;
 
   // sanitize inputs; should be self-explanatory
   if(!is_int($l) || !is_int($c) || !is_int($n) || !is_int($s)) {
      trigger_error('Argument(s) not an integer', E_USER_WARNING);
      return false;
   }
   elseif($l < 0 || $l > 20 || $c < 0 || $n < 0 || $s < 0) {
      trigger_error('Argument(s) out of range', E_USER_WARNING);
      return false;
   }
   elseif($c > $l) {
      trigger_error('Number of password capitals required exceeds password length', E_USER_WARNING);
      return false;
   }
   elseif($n > $l) {
      trigger_error('Number of password numerals exceeds password length', E_USER_WARNING);
      return false;
   }
   elseif($s > $l) {
      trigger_error('Number of password capitals exceeds password length', E_USER_WARNING);
      return false;
   }
   elseif($count > $l) {
      trigger_error('Number of password special characters exceeds specified password length', E_USER_WARNING);
      return false;
   }
 
   // all inputs clean, proceed to build password
 
   // change these strings if you want to include or exclude possible password characters
   $chars = "abcdefghijklmnopqrstuvwxyz";
   $caps = strtoupper($chars);
   $nums = "0123456789";
   $syms = "!@#$%^&*()-+?";
 
   // build the base password of all lower-case letters
   for($i = 0; $i < $l; $i++) {
      $out .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
   }
 
   // create arrays if special character(s) required
   if($count) {
      // split base password to array; create special chars array
      $tmp1 = str_split($out);
      $tmp2 = array();
 
      // add required special character(s) to second array
      for($i = 0; $i < $c; $i++) {
         array_push($tmp2, substr($caps, mt_rand(0, strlen($caps) - 1), 1));
      }
      for($i = 0; $i < $n; $i++) {
         array_push($tmp2, substr($nums, mt_rand(0, strlen($nums) - 1), 1));
      }
      for($i = 0; $i < $s; $i++) {
         array_push($tmp2, substr($syms, mt_rand(0, strlen($syms) - 1), 1));
      }
 
      // hack off a chunk of the base password array that's as big as the special chars array
      $tmp1 = array_slice($tmp1, 0, $l - $count);
      // merge special character(s) array with base password array
      $tmp1 = array_merge($tmp1, $tmp2);
      // mix the characters up
      shuffle($tmp1);
      // convert to string for output
      $out = implode('', $tmp1);
   }
 
   return $out;
}
add_filter('wp_mail_content_type','set_content_type');
function set_content_type($content_type){
return 'text/html';
}

function sendMail($type,$id){

	$to = 'puriphanr@thaipbs.or.th';
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$headers = 'From: ระบบเว็บไซต์สถานีประชาชน <wimonratk@thaipbs.or.th>' . "\r\n";
	
	$body = '<p>เรียนทีมงานรายการสถานีประชาชน</p>';	
	
	if($type == 'inform'){
		$subject = 'แจ้งเตือนได้รับข้อมูลใหม่ผ่านเว็บไซต์ (เรื่องร้องเรียน)';
		$body .= '<p>ทางเว็บไซต์ได้รับร้องเรียนเรื่อง <strong>'.get_field('topic',$id).' จากคุณ '.get_field('inform_name',$id).'</strong>';
	}
	elseif($type== 'legal'){
		$subject = 'แจ้งเตือนได้รับข้อมูลใหม่ผ่านเว็บไซต์ (ปรึกษากฎหมาย)';
		$body .= '<p>ทางเว็บไซต์ได้รับร้องเรื่องการปรึกษากฎหมาย ประเด็น  <strong>'.get_field('topic',$id).' จากคุณ '.get_field('inform_name',$id).'</strong>';
	}
	else{
		$subject = 'แจ้งเตือนได้รับข้อมูลใหม่ผ่านเว็บไซต์ (คนหาย)';
		$body .= '<p>ทางเว็บไซต์ได้รับข้อมูลแจ้งคนหาย คือ <strong>'.get_field('front_name',$id)['label'].' '.get_field('fname',$id).' '.get_field('lname',$id).'</strong></p>';
	}
	
	$body .= '<p>คลิกเข้าชมที่ <a href="'.get_edit_post_link($id).'" target="_blank">'.get_edit_post_link($id).'</a></p>';
	
	wp_mail($to, $subject, $body, $headers );
}


function my_update_attachment($f,$pid,$t='',$c='') {
  wp_update_attachment_metadata( $pid, $f );
  if( !empty( $_FILES[$f]['name'] )) { 
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
 include( ABSPATH . 'wp-admin/includes/image.php' );
    // $override['action'] = 'editpost';
    $override['test_form'] = false;
    $file = wp_handle_upload( $_FILES[$f], $override );

    if ( isset( $file['error'] )) {
      return new WP_Error( 'upload_error', $file['error'] );
    }

    $file_type = wp_check_filetype($_FILES[$f]['name'], array(
      'jpg|jpeg' => 'image/jpeg',
      'gif' => 'image/gif',
      'png' => 'image/png',
    ));
    if ($file_type['type']) {
      $name_parts = pathinfo( $file['file'] );
      $name = $file['filename'];
      $type = $file['type'];
      $title = $t ? $t : $name;
      $content = $c;

      $attachment = array(
        'post_title' => $title,
        'post_type' => 'attachment',
        'post_content' => $content,
        'post_parent' => $pid,
        'post_mime_type' => $type,
        'guid' => $file['url'],
      );

      foreach( get_intermediate_image_sizes() as $s ) {
        $sizes[$s] = array( 'width' => '', 'height' => '', 'crop' => true );
        $sizes[$s]['width'] = get_option( "{$s}_size_w" ); // For default sizes set in options
        $sizes[$s]['height'] = get_option( "{$s}_size_h" ); // For default sizes set in options
        $sizes[$s]['crop'] = get_option( "{$s}_crop" ); // For default sizes set in options
      }

      $sizes = apply_filters( 'intermediate_image_sizes_advanced', $sizes );

      foreach( $sizes as $size => $size_data ) {
        $resized = image_make_intermediate_size( $file['file'], $size_data['width'], $size_data['height'], $size_data['crop'] );
        if ( $resized )
          $metadata['sizes'][$size] = $resized;
      }

      $attach_id = wp_insert_attachment( $attachment, $file['file'] /*, $pid - for post_thumbnails*/);

      if ( !is_wp_error( $attach_id )) {
        $attach_meta = wp_generate_attachment_metadata( $attach_id, $file['file'] );
        wp_update_attachment_metadata( $attach_id, $attach_meta );
      }
   
   return array(
	  'pid' =>$pid,
	  'url' =>$file['url'],
	  'file'=>$file,
	  'attach_id'=>$attach_id
   );
    }
  }
}

function upload_file($f,$pid,$t='',$c=''){
	 wp_update_attachment_metadata( $pid, $f );
	 if( !empty( $_FILES[$f]['name'] )) { 
    require_once( ABSPATH . 'wp-admin/includes/file.php' );
	include( ABSPATH . 'wp-admin/includes/image.php' );
   
    $override['test_form'] = false;
    $file = wp_handle_upload( $_FILES[$f], $override );

    if ( isset( $file['error'] )) {
      return new WP_Error( 'upload_error', $file['error'] );
    }
	
	 $attachment = array(
        'post_title' => $title,
        'post_type' => 'attachment',
        'post_content' => $content,
        'post_parent' => $pid,
        'post_mime_type' => $type,
        'guid' => $file['url'],
      );

    $file_type = wp_check_filetype($_FILES[$f]['name'], array(
      'jpg|jpeg' => 'image/jpeg',
      'gif' => 'image/gif',
      'png' => 'image/png'
    ));
	
	if ($file_type['type']) {
      $name_parts = pathinfo( $file['file'] );
      $name = $file['filename'];
      $type = $file['type'];
      $title = $t ? $t : $name;
      $content = $c;

      $attachment = array(
        'post_title' => $title,
        'post_type' => 'attachment',
        'post_content' => $content,
        'post_parent' => $pid,
        'post_mime_type' => $type,
        'guid' => $file['url'],
      );

      foreach( get_intermediate_image_sizes() as $s ) {
        $sizes[$s] = array( 'width' => '', 'height' => '', 'crop' => true );
        $sizes[$s]['width'] = get_option( "{$s}_size_w" ); // For default sizes set in options
        $sizes[$s]['height'] = get_option( "{$s}_size_h" ); // For default sizes set in options
        $sizes[$s]['crop'] = get_option( "{$s}_crop" ); // For default sizes set in options
      }

      $sizes = apply_filters( 'intermediate_image_sizes_advanced', $sizes );

      foreach( $sizes as $size => $size_data ) {
        $resized = image_make_intermediate_size( $file['file'], $size_data['width'], $size_data['height'], $size_data['crop'] );
        if ( $resized )
          $metadata['sizes'][$size] = $resized;
      }
   
    }
	 $attach_id = wp_insert_attachment( $attachment, $file['file'] );

      if ( !is_wp_error( $attach_id )) {
        $attach_meta = wp_generate_attachment_metadata( $attach_id, $file['file'] );
        wp_update_attachment_metadata( $attach_id, $attach_meta );
      }
		return array(
		  'pid' =>$pid,
		  'url' =>$file['url'],
		  'file'=>$file,
		  'attach_id'=>$attach_id
		);
	
	}


}
?>