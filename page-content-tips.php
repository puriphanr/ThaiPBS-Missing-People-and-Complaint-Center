<?php 
/*
Template name: content-tips
*/
?>
<?php 
get_header();
$tips = get_post($_GET['id']);
?>

<div class="container">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			  <li><a href="<?php bloginfo('url')?>">หน้าหลัก</a></li>
			  <li><a href="<?php bloginfo('url')?>/tips">สถานีประชาชนอัพเดต</a></li>
			  <li class="active"><?php echo $tips->post_title;?></li>
		</ol>
	</div>
	<div class="col-lg-12">


		<div class="page-header row">
			<div class="col-lg-11">
				<?php echo $tips->post_title;?>
			</div>
			<div class="col-lg-1 back">
				<a href="<?php bloginfo('url')?>/tips" class="btn btn-default"><i class="fa fa-arrow-left"></i> กลับ</a>
			</div>
		</div>
		
	</div>
	<div class="col-lg-8">
		<div class="page-content row">
			<div class="post-thumbnail">
				<?php echo get_the_post_thumbnail( $tips->ID, 'medium-large', array('class'=>'img-responsive') ); ?>
			
			</div>
			<div class="post-excerpt">
				<?php echo $tips->post_excerpt ?>
			</div>
			<div class="post-content">
				<?php echo $tips->post_content ?>
			</div>
		</div>
	</div>
	<?php echo get_sidebar()?>
	
</div>
<?php get_footer();?>