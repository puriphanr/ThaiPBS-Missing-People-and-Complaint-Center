<?php 
add_theme_support( 'post-thumbnails' );
add_post_type_support( 'tips','excerpt' );
function remove_admin_login_header() {
    remove_action('wp_head', '_admin_bar_bump_cb');
}
add_action('get_header', 'remove_admin_login_header');
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' ); 
wp_enqueue_style('admin_css_bootstrap', get_template_directory_uri().'/css/bootstrap.css'), false, '1.0.0', 'all');

add_filter('manage_missing_people_posts_columns', 'columns_head_only_missing_people', 10);
add_action('manage_missing_people_posts_custom_column', 'columns_content_only_missing_people', 10, 2);
 

function columns_head_only_missing_people($defaults) {
	$defaults['names'] = 'ชื่อ - นามสกุล';
	$defaults['province'] = 'จังหวัด';
	$defaults['status'] = 'สถานะ';
    return $defaults;
}
function columns_content_only_missing_people($column_name, $post_ID) {
	 if ($column_name == 'names') {
		 echo get_field( 'fname',$post_id)." ".get_field( 'lname',$post_id);
	 }
	  if ($column_name == 'province') {
		  echo get_field( 'province',$post_id);
	  }
    if ($column_name == 'status') {
		$status = get_field( 'status',$post_id);
		echo '<span class="label label-'.colorArray($status['value']).'">'.$status['label'].'</span>';
		
    }
}

add_action( 'admin_menu', 'my_admin_menu' );

function my_admin_menu() {
	add_menu_page( 'สถิติและรายงาน', 'สถิติและรายงาน', 'edit_posts', 'myplugin/stats_page.php', 'stats_page', 'dashicons-chart-area', 6  );
}

function stats_page(){
	?>
	<div class="wrap">
		<h2>สถิติและรายงาน</h2>
	</div>
	
	<?php
}

function colorArray($code){
	$colorArray = array(
						1 => 'danger',
						2 => 'warning',
						3 => 'primary',
						4 => 'success'
						);
	return $colorArray[$code];
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

function truncate($text, $chars = 150) {
    $text = $text." ";
    $text = mb_substr($text,0,$chars,'utf-8');
    $text = mb_substr($text,0,strrpos($text,' '),'utf-8');
    $text = $text."...";
    return $text;
}


function DateThai($strDate){
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
	 <meta property="og:description" content='<?php echo get_field('fname',$id)?> <?php echo get_field('lname',$id)?>'>
	 
	<meta name="twitter:card" content="summary">
	<meta name="twitter:site" content="@ThaiPBS">
	<meta name="twitter:creator" content="@ThaiPBS">
	<meta name="twitter:url" content="<?php bloginfo('url')?>/missing/content?id=<?php echo $id?>">
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
?>