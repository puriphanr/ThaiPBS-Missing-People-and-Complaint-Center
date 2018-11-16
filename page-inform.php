<?php 
/*
Template name: inform
*/
?>
<?php acf_form_head(); ?>
<?php get_header();?>

<div class="container">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			  <li><a href="<?php bloginfo('url')?>">หน้าหลัก</a></li>
			  <li class="active">แจ้งเรื่องร้องเรียน</li>
		</ol>
	</div>
	<div class="col-lg-12">


		<div class="page-header row">
			<div class="col-lg-4">
				แจ้งเรื่องร้องเรียน
			</div>
			<div class="col-lg-8 back">
				<a href="<?php bloginfo('url')?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> กลับ</a>
			</div>
		</div>
		<div class="page-content row">
		<?php if(empty($_GET['complete'])){ ?>
		<form id="target-form" class="form-horizontal" role="form" action="<?php echo home_url('process?fn=save_data')?>" method="POST" enctype="multipart/form-data">
			<div class="form-content">
				<input type="hidden" name="p_type" value="inform" />
				
				<?php
					$new_post = array(
						'field_groups'       => array(155), 
						'uploader' 			=> 'basic',
						'form'	=> false
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
						window.location = '<?php echo home_url('inform')?>?complete='+result.id;
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
})
</script>
		<?php }else{
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
				<div class="form-title">เรื่องร้องเรียน</div>
				<div class="row">
					<div class="col-lg-6">
						<strong>เลขที่เอกสาร :</strong>  <?php echo get_the_title( $id ); ?>
					</div>
					<div class="col-lg-6" style="text-align:right">
						<strong>วันที่บันทึก :</strong>  <?php echo dateThai(get_the_date( 'Ymd', $id )).' <strong>เวลา : </strong>'.get_the_date( 'H:i', $id ) ; ?>
					</div>
				</div>
			</div>
		
		<div class="acf-fields acf-form-fields -top">
			<div class="acf-field acf-field-text acf-field-599fb07173cbf" data-name="inform_name" data-type="text" data-key="field_599fb07173cbf" data-required="1">
	<div class="acf-label">
		<label for="acf-field_599fb07173cbf">ชื่อผู้แจ้ง</label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('inform_name',$id))?></div>	
	</div>
</div>
<div class="acf-field acf-field-text acf-field-599fb09473cc0" data-name="phone" data-type="text" data-key="field_599fb09473cc0" data-required="1">
	<div class="acf-label">
		<label for="acf-field_599fb09473cc0">เบอร์โทรศัพท์ </label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('phone',$id))?></div>	
	</div>
</div>
<div class="acf-field acf-field-textarea acf-field-599fb0e773cc2" data-name="address" data-type="textarea" data-key="field_599fb0e773cc2">
	<div class="acf-label">
		<label for="acf-field_599fb0e773cc2">ที่อยู่ที่ติดต่อได้</label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('address',$id))?></div>	
	</div>
</div>
<div class="acf-field acf-field-select acf-field-599fb10973cc3" data-name="province" data-type="select" data-key="field_599fb10973cc3">
	<div class="acf-label">
		<label for="acf-field_599fb10973cc3">จังหวัดที่เกิดเหตุ</label>
	</div>
<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('province',$id)['label'])?></div>	
	</div>
</div>
<div class="acf-field acf-field-text acf-field-599fb12273cc4" data-name="topic" data-type="text" data-key="field_599fb12273cc4">
	<div class="acf-label">
		<label for="acf-field_599fb12273cc4">ประเด็นปัญหา</label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('topic',$id))?></div>	
	</div>
</div>
<div class="acf-field acf-field-textarea acf-field-599fb13373cc5" data-name="description" data-type="textarea" data-key="field_599fb13373cc5">
	<div class="acf-label">
		<label for="acf-field_599fb13373cc5">รายละเอียด</label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap"><?php echo dash(get_field('description',$id))?></div>	
	</div>
</div>
<div class="acf-field acf-field-file acf-field-59c23ef0861f9" data-name="document" data-type="file" data-key="field_59c23ef0861f9">
	<div class="acf-label">
		<label for="acf-field_59c23ef0861f9">ไฟล์เอกสาร</label>
	</div>
	<div class="acf-input">
		<div class="acf-input-wrap">
		<?php
		$file = get_field('document',$id);
		if(!empty($file)){
			echo '<a href="'.$file['url'].'" target="_blank">'.$file['filename'].'</a>';
		}
		else{
			echo '-';
		}
		?>
		
		</div>	
	</div>
</div>
</div>
</div>
<div class="print-wrap">
	<a href="javascript:void(0)" id="btnPrint" class="printable btn btn-primary"><i class="fa fa-print"></i> พิมพ์</a>
	<a href="<?php bloginfo('url')?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> กลับ</a>
</div>

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
		
		
		} ?>
		</div>
	</div>
	
</div>

<?php get_footer();?>