<?php 
/*
Template name: tips
*/
?>
<?php 
get_header();
$loop = new WP_Query( array( 
											'post_type' => 'tips', 
											'orderby' => 'DESC'
											)
									);
?>

<div class="container">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			  <li><a href="<?php bloginfo('url')?>">หน้าหลัก</a></li>
			  <li class="active">สถานีประชาชนอัพเดต</li>
		</ol>
	</div>
	<div class="col-lg-12">


		<div class="page-header row">
			<div class="col-lg-11">
				สถานีประชาชนอัพเดต
			</div>
			<div class="col-lg-1 back">
				<a href="<?php bloginfo('url')?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> กลับ</a>
			</div>
		</div>
		
	</div>
	<div class="col-lg-8">
		<div class="page-content row">
				<?php
				if ( $loop->have_posts() ) :
					while ( $loop->have_posts() ) : $loop->the_post();
					?>
					<a href="<?php bloginfo('url') ?>/tips/content?id=<?php echo get_the_ID()?>">
						<article class="row news">
								<div class="media-content col-lg-5 col-md-5 col-sm-5 col-xs-5">
									<img class="img-responsive" src="<?php the_post_thumbnail_url( 'medium' ) ?>">
								</div>
								<div class="media-title col-lg-7 col-md-7 col-sm-7 col-xs-7">
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
	<?php echo get_sidebar()?>
	
</div>
<?php get_footer();?>