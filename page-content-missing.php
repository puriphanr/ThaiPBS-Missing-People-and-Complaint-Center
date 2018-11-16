<?php 
/*
Template name: content-missing
*/
get_header();
$id = $_GET['id'];
 ?>
<div class="container">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			  <li><a href="<?php bloginfo('url')?>">หน้าหลัก</a></li>
			  <li><a href="<?php bloginfo('url')?>/missing">ศูนย์ข้อมูลคนหาย</a></li>
			  <li class="active">ข้อมูลคนหาย</li>
		</ol>
	</div>
	<div class="col-lg-12">
		
		<div class="page-header row">
			<div class="col-lg-4">
				ข้อมูลคนหาย
			</div>
			<div class="col-lg-8 back">
				<a href="<?php bloginfo('url')?>/missing" class="btn btn-default"><i class="fa fa-arrow-left"></i> กลับ</a>
			</div>
		</div>

		<div class="page-content row">
			<div class="missing-blog">
			<div class="col-lg-4">
				<div class="image">
					<img src="<?php echo get_field('image',$id)['url'] ?>"  class="img-responsive thumbnail"/>
				</div>
				<div class="social-sharing">
						<div class="share-label"><span class="btn-circle btn btn-lg btn-default"><i class="fa fa-share-alt"></i></span> แบ่งปัน</div>
						<div style="vertical-align:top;">
							<ul class="social-btn">
								<li><button class="btn social fb"><i class="fa fa-facebook"></i></button></li>
								<li><button class="btn social tw"><i class="fa fa-twitter"></i></button></li>
								<li><div class="line-it-button" data-lang="en" data-type="share-d" data-url="<?php bloginfo('url')?>/missing/content?id=<?php echo $_GET['id']?>" style="display: none;"></div></li>
							</ul>
						</div>
				</div>
			</div>
			<div class="col-lg-8">
				<h2>ข้อมูลส่วนตัว</h2>
				 <table class="table">
					<tbody>
						<tr>
							<th width="30%">ชื่อ - นามสกุล</th>
							<td width="70%"><?php echo get_field('front_name',$id)['label']?><?php echo get_field('fname',$id)?> <?php echo get_field('lname',$id)?></td>
						</tr>
						<tr>
							<th>ชื่อเล่น</th>
							<td><?php echo get_field('nickname',$id)?></td>
						</tr>
						<tr>
							<th>อายุ</th>
							<td><?php echo calAge(get_field('birthday',$id))?> ปี</td>
						</tr>
						<tr>
							<th>จังหวัด</th>
							<td><?php echo get_field('province',$id)['label']?></td>
						</tr>
						<tr>
							<th>วันที่หาย</th>
							<td><?php echo DateThai(get_field('missing_date',$id))?></td>
						</tr>
					
					</tbody>
				 </table>
				 <h2>รูปพรรณสัณฐาน</h2>
				<table class="table">
					<tbody>
						<tr>
							<th width="30%">สีผิว</th>
							<td width="70%"><?php echo get_field('morphology',$id)['label']?></td>
						</tr>
						<tr>
							<th>ความสูง (เซนติเมตร)</th>
							<td><?php echo get_field('height',$id)?></td>
						</tr>
						<tr> 
							<th>น้ำหนัก (กิโลกรัม)</th>
							<td><?php echo get_field('weight',$id)?></td>
						</tr>
						
					</tbody>
				 </table>
			</div>
			
			<div class="col-lg-12">
				 <h2>รายละเอียดเพิ่มเติม</h2>
				<table class="table">
					<tbody>
						<tr>
							<th width="20%">ข้อมูลพบเห็นล่าสุด</th>
							<td width="70%"><?php echo nl2br(get_field('last_seeing',$id))?></td>
						</tr>
						<tr>
							<th>ข้อมูลเพิ่มเติม</th>
							<td><?php echo nl2br(get_field('other_description',$id))?></td>
						</tr>
					</tbody>
				</table>
			</div>
			
			
		</div>
		</div>
	</div>
	
</div>
<script src="https://d.line-scdn.net/r/web/social-plugin/js/thirdparty/loader.min.js" async="async" defer="defer"></script>
<script type="text/javascript">
window.fbAsyncInit = function() {
    FB.init({
        appId            : '688208238037170',
        status           : true,
        cookie           : true,
        version          : 'v2.10'                
    });

  
};

(function(d, s, id){
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

$(function(){
	$( '.fb' ).click(function(e){
		e.preventDefault();
		FB.ui(
			{
				method: 'share',
				href: '<?php bloginfo('url')?>/missing/content?id=<?php echo $_GET['id']?>',
				redirect_uri : '<?php bloginfo('url')?>/missing',
				display : 'popup'
			},
			function (response) {

			}
		);
	})
	$('.tw').click(function (e) {
				e.preventDefault();
				var loc = '<?php bloginfo('url')?>/missing/content?id=<?php echo $_GET['id']?>';
				var title  = "<?php echo get_field('front_name',$_GET['id'])['label']?> <?php echo get_field('fname',$_GET['id'])?> <?php echo get_field('lname',$_GET['id'])?> อายุ <?php echo calAge(get_field('birthday',$_GET['id']))?> ปี จังหวัด<?php echo get_field('province',$_GET['id'])['label']?> หากพบเห็นแจ้งศูนย์คนหาย ไทยพีบีเอส โทร 02-790-2111 %23ศูนย์คนหายไทยพีบีเอส %23Thai PBS";

				window.open('http://twitter.com/share?url=' + loc + '&text=' + title + '&', 'twitterwindow', 'height=450, width=550, left='+$(window).width()/2 +', toolbar=0, location=0, menubar=0, directories=0, scrollbars=0');
			});
})
</script>
<?php get_footer() ?>