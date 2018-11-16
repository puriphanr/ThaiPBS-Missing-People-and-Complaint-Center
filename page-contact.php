<?php 
/*
Template name: contact
*/
?>
<?php 
get_header();
?>

<div class="container">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			  <li><a href="<?php bloginfo('url')?>">หน้าหลัก</a></li>
			  <li class="active">ติดต่อเรา</li>
		</ol>
	</div>
	<div class="col-lg-12">


		<div class="page-header row">
			<div class="col-lg-4">
				ติดต่อเรา
			</div>
			<div class="col-lg-8 back">
				<a href="<?php bloginfo('url')?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> กลับ</a>
			</div>
		</div>
		<div class="page-content row">
		<div class="col-lg-8">
			<div id="map-canvas"></div>
			<div class="contact col-xs-12">
						<div class="row">
							<div class="header">ติดต่อรายการสถานีประชาชน</div>
							<div class="clearfix"></div>
							<div class="content">
							<p>สถานีประชาชน ฝ่ายข่าว สถานีโทรทัศน์ไทยพีบีเอส (Thai PBS)</p>
							<p>เลขที่145 ถนนวิภาวดีรังสิต แขวงตลาดบางเขน เขตหลักสี่ กรุงเทพฯ 10210</p>
							<p>โทร 02-790-2111 หรือ 02-790-2630-3 (วันจันทร์ - ศุกร์ เวลา 09.00 - 16.00 น.) </p>
<p>โทรสาร 02-790-2089</p>
<p>อีเมล people@thaipbs.or.th</p>

<p>FB รายการสถานีประชาชน </p>
<p>FB รายการร้องทุก(ข์)ลงป้ายนี้ </p>
<p>FB  ศูนย์คนหายไทยพีบีเอส  </p>
<p>Line  @rongtookthaipbs </p>

							</div>
						</div>
			</div>
					
			</div>
		
		<?php get_sidebar()?>
			
		</div>
	</div>
</div>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script type="text/javascript">
function initialize() {
  var myLatlng = new google.maps.LatLng(13.867636,100.572939);
  var mapOptions = {
    zoom: 14,
    center: myLatlng
  }
  var map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);

  var marker = new google.maps.Marker({
      position: myLatlng,
      map: map,
      title: 'รายการสถานีประชาชน'
  });
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
<?php get_footer();?>