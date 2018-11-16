<?php acf_form_head(); ?>
<?php get_header();?>
<div class="container">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			  <li><a href="<?php bloginfo('url')?>">หน้าหลัก</a></li>
			  <li><a href="<?php bloginfo('url')?>/missing">ศูนย์ข้อมูลคนหาย</a></li>
			  <li class="active">แจ้งข้อมูลคนหาย</li>
		</ol>
	</div>
	<div class="col-lg-12">
		<div class="page-header row">
			<div class="col-lg-4">
				แจ้งข้อมูลคนหาย
			</div>
			<div class="col-lg-8 back">
				<a href="<?php bloginfo('url')?>/missing" class="btn btn-default"><i class="fa fa-arrow-left"></i> กลับ</a>
			</div>
		</div>
		<div class="page-content row">
		<?php if(empty($_GET['complete'])){ ?>
		<form id="target-form" class="form-horizontal" role="form" action="<?php echo home_url('process?fn=save_data')?>" method="POST" enctype="multipart/form-data">
			<div class="form-content">
				<input type="hidden" name="p_type" value="missing_people" />
			<?php
				$new_post = array(
					'field_groups'      => array(25,147), 
					'form' => false,
					'uploader' 			=> 'basic'
				);
				acf_form( $new_post );
			?>
			</div>
			<div class="submit">
				<button type="submit" class="btn btn-success"> บันทึก</button>
			</div>
		<?php acf_enqueue_uploader(); ?>
		</form>
		
		<div id="dialog-confirm" title="ยืนยันข้อมูล">
  <p>คุณแน่ใจที่จะบันทึกข้อมูลดังกล่าวใช่หรือไม่ ?</p>
</div>
<div id="dialog-wait">
  <p><img src="<?php echo get_template_directory_uri()?>/images/spinner.gif" /> <span style="margin-left:10px">กำลังดำเนินการ</span></p>
</div>
<div id="dialog-error" title="ผิดพลาด">
  <p id="errorMsg"></p>
</div>

<script type="text/javascript">
$(function() {
	$('#target-form input[type=text],input[type=number],input[type=email],form select:not(.acf-disabled),textarea').addClass('form-control');
	
	var dialog = $( "#dialog-confirm" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
	  width : 350,
      modal: true,
	  dialogClass: 'no-close',
      buttons: [
			{
               text: "ยืนยัน",
               "class": 'btn btn-primary',
               click: function() {  
					dialog.dialog('close');
					saveData();
               }
            },
            {
               text: "ยกเลิก",
			   "class": 'btn btn-default',
               click: function() { 
                   dialog.dialog( "close" );
               }
            }
	  ]
    });
	
	var dialogErr = $( "#dialog-error" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
	  width : 350,
      modal: true,
	  dialogClass: 'no-close has-error',
      buttons: [		
            {
               text: "ตกลง",
			   "class": 'btn btn-danger',
               click: function() { 
                   dialogErr.dialog( "close" );
               }
            }
	  ]
    });
	
	var dialogWait = $( "#dialog-wait" ).dialog({
      autoOpen: false,
      resizable: false,
      height: "auto",
	  width : 350,
      modal: true,
	  dialogClass: 'no-close no-title'
    });
	
	function saveData(){
			$.ajax({
				type : 'POST',
				url : $('#target-form').attr('action'),
				data : new FormData($('#target-form')[0]),
				dataType : 'JSON',
				cache: false,
				contentType: false,
				processData: false,
				beforeSend : function(){
					dialogWait.dialog('open');
				},
				success : function(result){
					dialogWait.dialog('close');
					if(result.status == true){
						window.location = '<?php echo home_url('create-missing')?>?complete='+result.id;
					}
					else{
						$('#dialog-error #errorMsg').text(result.error);
					}
				}
			})
	}
		
	$('#target-form').submit(function(e){
		e.preventDefault();
			acf.add_filter('validation_complete', function( json, $form ){
				if( !json.errors ) {
					dialog.dialog('open');
				}
				return json;		
			});
	});
});	
</script>

		<?php 
		}
		else{
			$id = $_GET['complete'];
			?>
			<?php if(empty($_GET['hide'])){ ?>
		<div class="notice">
			
				<div class="alert alert-success">
				<div>  ขณะนี้ทางทีมงานได้รับข้อมูลของท่านเรียบร้อยแล้ว กรุณารอการตรวจสอบและการติดต่อกลับจากทีมงาน </div>
				</div>
			
		</div>
			<?php } ?>
		<div class="print-content" id="masterContent">
			<div class="form-head">
				<div class="form-title">ข้อมูลคนหาย</div>
				<div class="row">
					<div class="col-lg-6">
						<strong>เลขที่เอกสาร :</strong>  <?php echo get_the_title( $id ); ?>
					</div>
					<div class="col-lg-6" style="text-align:right">
						<strong>วันที่บันทึก :</strong><?php echo dateThai(get_the_date( 'Ymd', $id )).' <strong>เวลา : </strong>'.get_the_date( 'H:i', $post_id ) ; ?>
					</div>
				</div>
			</div>
		<div class="acf-fields acf-form-fields -top">
			<div class="acf-field acf-field-select acf-field-59a90853b6be1 acf-r0" style="width: 10%; min-height: 91px;" data-name="front_name" data-type="select" data-key="field_59a90853b6be1" data-required="1" data-width="10">
	<div class="acf-label">
		<label for="acf-field_59a90853b6be1">คำนำหน้า </label>
	</div>
	<div class="acf-input">
		<?php echo dash(get_field('front_name',$id)['label'])?></div>
	</div>
<div class="acf-field acf-field-text acf-field-59957d5d4da3b acf-r0" style="width: 45%; min-height: 91px;" data-name="fname" data-type="text" data-key="field_59957d5d4da3b" data-required="1" data-width="45">
	<div class="acf-label">
		<label for="acf-field_59957d5d4da3b">ชื่อ</label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('fname',$id))?></div>	</div>
</div>
<div class="acf-field acf-field-text acf-field-59957d814da3c acf-r0" style="width: 45%; min-height: 91px;" data-name="lname" data-type="text" data-key="field_59957d814da3c" data-required="1" data-width="45">
	<div class="acf-label">
		<label for="acf-field_59957d814da3c">นามสกุล</label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('lname',$id))?></div>	</div>
</div>
<div class="acf-field acf-field-text acf-field-59957d904da3d acf-c0" style="width: 55%; min-height: 90px;" data-name="old_fname" data-type="text" data-key="field_59957d904da3d" data-width="55">
	<div class="acf-label">
		<label for="acf-field_59957d904da3d">ชื่อเดิม (ถ้ามี)</label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('old_fname',$id))?></div>	</div>
</div>
<div class="acf-field acf-field-text acf-field-59957db34da3e" style="width: 45%; min-height: 90px;" data-name="nickname" data-type="text" data-key="field_59957db34da3e" data-width="45">
	<div class="acf-label">
		<label for="acf-field_59957db34da3e">ชื่อเล่น</label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('nickname',$id))?></div>	</div>
</div>
<div class="acf-field acf-field-radio acf-field-59a908b05d73a acf-c0" style="width: 50%; min-height: 90px;" data-name="gender" data-type="radio" data-key="field_59a908b05d73a" data-required="1" data-width="50">
	<div class="acf-label">
		<label for="acf-field_59a908b05d73a">เพศ</label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('gender',$id)['label'])?></div>
	</div>
</div>
<div class="acf-field acf-field-date-picker acf-field-59957dbf4da3f" style="width: 50%; min-height: 90px;" data-name="birthday" data-type="date_picker" data-key="field_59957dbf4da3f" data-required="1" data-width="50">
	<div class="acf-label">
		<label for="acf-field_59957dbf4da3f">วัน/เดือน/ปี เกิด </label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(dateThai(get_field('birthday',$id)))?></div>
	</div>
</div>
<div class="acf-field acf-field-text acf-field-59957def4da40" data-name="health_disorder" data-type="text" data-key="field_59957def4da40">
	<div class="acf-label">
		<label for="acf-field_59957def4da40">โรคประจำตัว</label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('health_disorder',$id))?></div>
	</div>
</div>
<div class="acf-field acf-field-radio acf-field-59957e2a4da41 acf-c0" style="width: 50%; min-height: 86px;" data-name="other_disorder" data-type="radio" data-key="field_59957e2a4da41" data-width="50">
	<div class="acf-label">
		<label for="acf-field_59957e2a4da41">อาการ</label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('other_disorder',$id)['label'])?></div>
	</div>
</div>
<div class="acf-field " >
	<div class="acf-label">
		<label for="acf-field_59957e774da42">ระบุความพิการ</label>
	</div>

	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('disabled',$id))?></div>
	</div>
	
</div>
<div class="acf-field acf-field-radio acf-field-59957f1a62d7c acf-c0" style="width: 60%; min-height: 113px;" data-name="morphology" data-type="radio" data-key="field_59957f1a62d7c" data-width="60">
	<div class="acf-label">
		<label for="acf-field_59957f1a62d7c">รูปพรรณสัณฐาน</label>
		<p class="description">สีผิว</p>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('morphology',$id)['label'])?></div>
	</div>
</div>
<div class="acf-field acf-field-number acf-field-59957fb362d7d" style="width: 20%; min-height: 113px;" data-name="height" data-type="number" data-key="field_59957fb362d7d" data-width="20">
	<div class="acf-label">
		<label for="acf-field_59957fb362d7d">ความสูง</label>
		<p class="description">เซนติเมตร</p>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('height',$id))?></div>
	</div>
</div>
<div class="acf-field acf-field-number acf-field-59957fef62d7e" style="width: 20%; min-height: 113px;" data-name="weight" data-type="number" data-key="field_59957fef62d7e" data-width="20">
	<div class="acf-label">
		<label for="acf-field_59957fef62d7e">น้ำหนัก</label>
		<p class="description">กิโลกรัม</p>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('weight',$id))?></div>
	</div>
</div>
<div class="acf-field acf-field-textarea acf-field-599581ec9da9b" data-name="other_description" data-type="textarea" data-key="field_599581ec9da9b">
	<div class="acf-label">
		<label for="acf-field_599581ec9da9b">รายละเอียดอื่นๆ</label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('other_description',$id))?></div>
	</div>
</div>
<div class="acf-field acf-field-image acf-field-599d7cc76043b" data-name="image" data-type="image" data-key="field_599d7cc76043b">
	<div class="acf-label">
		<label for="acf-field_599d7cc76043b">ภาพ</label>
	</div>
	<div class="acf-input">
		<img src="<?php echo get_field('image',$id)['sizes']['medium'] ?>" />
	</div>
</div>
<div class="acf-field acf-field-radio acf-field-59959fd100517" data-name="missing_point" data-type="radio" data-key="field_59959fd100517">
	<div class="acf-label">
		<label for="acf-field_59959fd100517">จุดที่หายตัวไป</label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('missing_point',$id))['label']?></div>
	</div>
</div>
<div class="acf-field acf-field-text acf-field-5995a7a1f2bf4 acf-c0" style="width: 30%; min-height: 113px;" data-name="house_no" data-type="text" data-key="field_5995a7a1f2bf4" data-width="30">
	<div class="acf-label">
		<label for="acf-field_5995a7a1f2bf4">รายละเอียดจุดที่หาย</label>
		<p class="description">บ้านเลขที่</p>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('house_no',$id))?></div>
	</div>
</div>
<div class="acf-field acf-field-text acf-field-5995a12d8ada8" style="width: 35%; min-height: 113px;" data-name="villege" data-type="text" data-key="field_5995a12d8ada8" data-width="35">
	<div class="acf-label">
		<label for="acf-field_5995a12d8ada8">&nbsp;</label>
		<p class="description">หมู่บ้าน</p>
	</div>
		<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('villege',$id))?></div>
	</div>
</div>
<div class="acf-field acf-field-text acf-field-5995a38d8ada9" style="width: 35%; min-height: 113px;" data-name="road" data-type="text" data-key="field_5995a38d8ada9" data-width="35">
	<div class="acf-label">
		<label for="acf-field_5995a38d8ada9">&nbsp;</label>
		<p class="description">ถนน</p>
	</div>
		<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('road',$id))?></div>
	</div>
</div>
<div class="acf-field acf-field-text acf-field-5995a43f1cef8 acf-c0" style="width: 30%; min-height: 93px;" data-name="subdistrict" data-type="text" data-key="field_5995a43f1cef8" data-width="30">
	<div class="acf-label">
		<label for="acf-field_5995a43f1cef8"></label>
		<p class="description">ตำบล</p>
	</div>
		<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('subdistrict',$id))?></div>
	</div>
</div>
<div class="acf-field acf-field-text acf-field-5995a5091cef9" style="width: 35%; min-height: 93px;" data-name="district" data-type="text" data-key="field_5995a5091cef9" data-width="35">
	<div class="acf-label">
		<label for="acf-field_5995a5091cef9"></label>
		<p class="description">อำเภอ</p>
	</div>
		<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('district',$id))?></div>
	</div>
</div>
<div class="acf-field acf-field-select acf-field-5995a5171cefa" style="width: 35%; min-height: 93px;" data-name="province" data-type="select" data-key="field_5995a5171cefa" data-width="35">
	<div class="acf-label">
		<label for="acf-field_5995a5171cefa"></label>
		<p class="description">จังหวัด</p>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('province',$id)['label'])?></div>
	</div>
</div>
<div class="acf-field acf-field-date-picker acf-field-5995a55fee94e acf-c0" style="width: 50%; min-height: 90px;" data-name="missing_date" data-type="date_picker" data-key="field_5995a55fee94e" data-width="50">
	<div class="acf-label">
		<label for="acf-field_5995a55fee94e">วันที่หายตัวไป</label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(dateThai(get_field('missing_date',$id)))?></div>
	</div>
</div>
<div class="acf-field acf-field-time-picker acf-field-5995a581ee94f" style="width: 50%; min-height: 90px;" data-name="missing_time" data-type="time_picker" data-key="field_5995a581ee94f" data-width="50">
	<div class="acf-label">
		<label for="acf-field_5995a581ee94f">เวลาที่หาย</label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('missing_time',$id))?></div>
	</div>
</div>
<div class="acf-field acf-field-radio acf-field-5995a5b4257ce" data-name="missing_cause" data-type="radio" data-key="field_5995a5b4257ce">
	<div class="acf-label">
		<label for="acf-field_5995a5b4257ce">สาเหตุที่หายตัวไป</label>
	</div>
		<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('missing_cause',$id)['label'])?></div>
	</div>
</div>
<div class="acf-field acf-field-textarea acf-field-5995a635257cf" data-name="last_seeing" data-type="textarea" data-key="field_5995a635257cf">
	<div class="acf-label">
		<label for="acf-field_5995a635257cf">ข้อมูลพบเห็นล่าสุด</label>
	</div>
		<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('last_seeing',$id))?></div>
	</div>
</div>
<div class="acf-field acf-field-text acf-field-5995a70bbc694 acf-c0" style="width: 50%; min-height: 113px;" data-name="notice_name" data-type="text" data-key="field_5995a70bbc694" data-width="50">
	<div class="acf-label">
		<label for="acf-field_5995a70bbc694">รายละเอียดผู้แจ้ง</label>
		<p class="description">ชื่อ - นามสกุล</p>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('notice_name',$id))?></div>
	</div>
</div>
<div class="acf-field acf-field-text acf-field-59969b0c3c1ba" style="width: 50%; min-height: 113px;" data-name="notice_relationship" data-type="text" data-key="field_59969b0c3c1ba" data-width="50">
	<div class="acf-label">
		<label for="acf-field_59969b0c3c1ba">&nbsp;</label>
		<p class="description">ความสัมพันธ์</p>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('notice_relationship',$id))?></div>
	</div>
</div>
<div class="acf-field acf-field-text acf-field-59969b2e3c1bb acf-c0" style="width: 50%; min-height: 113px;" data-name="notice_phone" data-type="text" data-key="field_59969b2e3c1bb" data-width="50">
	<div class="acf-label">
		<label for="acf-field_59969b2e3c1bb">&nbsp;</label>
		<p class="description">หมายเลขโทรศัพท์</p>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('notice_phone',$id))?></div>
	</div>
</div>
<div class="acf-field acf-field-email acf-field-59969b553c1bc" style="width: 50%; min-height: 113px;" data-name="notice_email" data-type="email" data-key="field_59969b553c1bc" data-width="50">
	<div class="acf-label">
		<label for="acf-field_59969b553c1bc">&nbsp;</label>
		<p class="description">อีเมล</p>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('email',$id))?></div>
	</div>
</div>
<div class="acf-field acf-field-radio acf-field-59969bc57d42d" data-name="police_document" data-type="radio" data-key="field_59969bc57d42d">
	<div class="acf-label">
		<label for="acf-field_59969bc57d42d">สถานีตำรวจที่แจ้งความ</label>
		<p class="description">สำเนาใบแจ้งความ</p>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('police_document',$id)['label'])?></div>
	</div>
</div>
<div class="acf-field acf-field-text acf-field-59969c2c464b8 acf-c0" style="width: 50%; min-height: 93px;" data-name="police_station" data-type="text" data-key="field_59969c2c464b8" data-width="50">
	<div class="acf-label">
		<label for="acf-field_59969c2c464b8"></label>
		<p class="description">ชื่อสถานีตำรวจ</p>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('police_station',$id))?></div>
	</div>
</div>
<div class="acf-field acf-field-date-picker acf-field-59969c8f464b9" style="width: 50%; min-height: 93px;" data-name="police_date" data-type="date_picker" data-key="field_59969c8f464b9" data-width="50">
	<div class="acf-label">
		<label for="acf-field_59969c8f464b9"></label>
		<p class="description">วันที่แจ้งความ</p>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(dateThai(get_field('police_date',$id)))?></div>
	</div>
</div>
<div class="acf-field acf-field-text acf-field-59969ca4464ba acf-c0" style="width: 50%; min-height: 93px;" data-name="police_officer" data-type="text" data-key="field_59969ca4464ba" data-width="50">
	<div class="acf-label">
		<label for="acf-field_59969ca4464ba"></label>
		<p class="description">เจ้าหน้าที่รับเรื่อง</p>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('police_officer',$id))?></div>
	</div>
</div>
<div class="acf-field acf-field-text acf-field-59969cb6464bb" style="width: 50%; min-height: 93px;" data-name="police_phone" data-type="text" data-key="field_59969cb6464bb" data-width="50">
	<div class="acf-label">
		<label for="acf-field_59969cb6464bb"></label>
		<p class="description">หมายเลขโทรศัพท์</p>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('police_phone',$id))?></div>
	</div>
</div>

</div>

</div>	
<div class="print-wrap">
	<a href="javascript:void(0)" id="btnPrint" class="printable btn btn-primary"><i class="fa fa-print"></i> พิมพ์</a>
	<a href="<?php echo home_url('missing')?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> กลับ</a>
</div>		

<script type="text/javascript">
  $(function(){
            $("#btnPrint").printPreview({
                obj2print:'#masterContent',
                width:'810'
            });
        });
</script>
			<?php
			
		}
		?>
		</div>
	</div>
</div>

<?php get_footer();?>