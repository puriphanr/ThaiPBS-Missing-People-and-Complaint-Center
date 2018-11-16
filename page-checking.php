<?php 
/*
Template name: checking
*/
?>
<?php 
get_header();
$task_id = trim($_POST['task_id']);
$typeChar = substr($task_id, 0, 3);
if($typeChar = "INF"){
	$cpt = "inform";
}
elseif($typeChar = "LEG"){
	$cpt = "legal";
}
else{
	$cpt = "missing_people";
}
$page = get_page_by_title($task_id , $output = OBJECT , $cpt);
?>

<div class="container">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			  <li><a href="<?php bloginfo('url')?>">หน้าหลัก</a></li>
			  <li class="active">ติดตามการแจ้งเรื่อง</li>
		</ol>
	</div>
	<div class="col-lg-12">


		<div class="page-header row">
			<div class="col-lg-4">
				ติดตามการแจ้งเรื่อง
			</div>
			<div class="col-lg-8 back">
				<a href="<?php bloginfo('url')?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> กลับ</a>
			</div>
		</div>
		<div class="page-content row">
		
			<?php 
			if( $page ){ 
				$id = $page->ID;
				$typeArray = array('inform'=>'แจ้งเรื่องร้องเรียน','legal'=>'ปรึกษากฎหมาย','missing_people'=>'ข้อมูลคนหาย');
				$pageArray = array('inform'=>'inform','legal'=>'legal','missing_people'=>'create-missing');
			?>
	
		
				<div class="panel panel-default checking">
				  <div class="panel-heading">ผลการค้นหา : <?php echo $task_id ?></div>
				  <div class="panel-body">
				  <dl class="row">
					<div class="row">
					  <dt class="col-sm-3">เลขที่เอกสาร</dt>
					  <dd class="col-sm-9"><?php echo get_the_title($id)?></dd>
					</div>
					<div class="row">
					   <dt class="col-sm-3">วันที่บันทึก</dt>
					  <dd class="col-sm-9"><?php echo dateThai(get_the_date( 'Ymd', $id )).' <br/> '.get_the_date( 'H:i', $id )?> น.</dd>
					</div>
					<div class="row">
					  <dt class="col-sm-3">ประเภทเอกสาร</dt>
					  <dd class="col-sm-9"><?php echo $typeArray[$cpt]?></dd>
					 </div>
					 <div class="row">
					   <dt class="col-sm-3">เรื่อง</dt>
					  <dd class="col-sm-9">
					  <?php 
						if($cpt == 'missing_people'){
							echo get_field('front_name',$id)['label'].' '.get_field('fname',$id).' '.get_field('lname',$id);
						}
						else{
							echo get_field('topic',$id);
						}
					  ?>
					  </dd>
					  </div>
					  <div class="row">
					  <dt class="col-sm-3">สถานะปัจจุบัน</dt>
					  <dd class="col-sm-9"><span style="font-weight:bold;color:<?php echo colorStatus(get_field('status',$id)['value'])?>"><?php echo get_field('status',$id)['label']?></span></dd>
					  </div>
					  <div class="row">
					   <dt class="col-sm-3">ดูข้อมูล</dt>
					  <dd class="col-sm-9"><a href="<?php echo home_url($pageArray[$cpt])?>?complete=<?php echo $id?>&hide=1" class="btn btn-xs btn-primary" target="_blank">ดูข้อมูล</a></dd>
					</div>

					  
					</dl>
				  </div>
				</div>
			
		
			<?php } else { ?>
			<div class="alert alert-info">
				<strong>ไม่พบข้อมูลที่เกี่ยวข้องในขณะนี้</strong>
			</div>
			
			<?php } ?>
		</div>
	
	</div>
</div>

<?php get_footer();?>