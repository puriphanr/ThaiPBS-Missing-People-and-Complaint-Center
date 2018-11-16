<?php 
/*
Template name: missing
*/
get_header() ?>
<div class="container">
	<div class="col-lg-12">
		<ol class="breadcrumb">
			  <li><a href="<?php bloginfo('url')?>">หน้าหลัก</a></li>
			  <li class="active">ศูนย์ข้อมูลคนหาย</li>
		</ol>
	</div>
	<div class="col-lg-12">


		<div class="page-header row">
			<div class="col-lg-3">
				ศูนย์ข้อมูลคนหาย
			</div>
			<div class="col-lg-9 back">
			
				<form class="form-horizontal" method="POST" action="<?php echo home_url('missing')?>">
					<div class="form-group">
						<div class="col-lg-11 col-md-9 col-sm-9 col-xs-9">
							<input type="text" name="search" class="form-control" required />
						</div>
						<div class="col-lg-1 col-md-2 col-sm-2 col-xs-2">
							<button type="submit" class="btn btn-success"><i class="fa fa-search"></i> ค้นหา</button>
						</div>
						
					</div>
				</form>
				
				
			</div>
		</div>
		<div class="page-content row">
			<div class="col-lg-12">
				<div class="row back" style="margin-bottom:20px">
					<a href="<?php bloginfo('url')?>/missing/create-missing" class="btn btn-primary"><i class="fa fa-user"></i> แจ้งข้อมูลคนหาย</a>
					<a href="<?php bloginfo('url')?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> กลับ</a>
				</div>
			</div>
			<div class="col-lg-12 mTop">
			<?php
				$query = array( 
											'post_type' => 'missing_people',
											'post_status' => array('publish'),
											'meta_key'		=> 'status',
											'meta_value'	=> 3,
											'orderby' => 'DESC',
											);
				if(!empty($_POST['search'])){
					$query['meta_query'] = array(
												'relation' => 'OR',
												array(
													'key' => 'fname',
													'compare' => 'LIKE',
													 'value' => $_POST['search'],  
												),
												array(
													'key' => 'lname',
													'compare' => 'LIKE',
														 'value' => $_POST['search'],    
												)
											);
				}
				
				
				$loop = new WP_Query($query);
				if ( $loop->have_posts() ) :
					while ( $loop->have_posts() ) : $loop->the_post();
					?>
					<a title="ดูรายละเอียด" href="<?php bloginfo('url')?>/missing/content?id=<?php echo get_the_ID()?>" >
					<article class="col-lg-3 col-md-3 col-sm-6 col-xs-6">
						<div class="image">
							<img src="<?php echo get_field('image')['sizes']['medium'] ?>"  class="img-responsive"/>
						</div>
						<div class="title text-center">
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
	</div>
	
</div>
<?php get_footer() ?>