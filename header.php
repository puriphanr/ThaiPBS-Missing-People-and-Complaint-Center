<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, user-scalable=no">
<title><?php bloginfo('name')?></title>
<link rel="shortcut icon" href="http://news.thaipbs.or.th/images/icons/favicon.ico"/>
<?php wp_head();?>
  <script>
        function loadCSS(e,n,o,t){"use strict";var d=window.document.createElement("link"),i=n||window.document.getElementsByTagName("script")[0],r=window.document.styleSheets;return d.rel="stylesheet",d.href=e,d.media="only x",t&&(d.onload=t),i.parentNode.insertBefore(d,i),d.onloadcssdefined=function(e){for(var n,o=0;o<r.length;o++)r[o].href&&r[o].href===d.href&&(n=!0);n?e():setTimeout(function(){d.onloadcssdefined(e)})},d.onloadcssdefined(function(){d.media=o||"all"}),d}

        loadCSS('http://news.thaipbs.or.th/css/style.min.css?0a5fa5f');
   </script>
<!--CSS-->
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url')?>/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url')?>/css/font-awesome.min.css" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url')?>/style.css?v=<?php echo time(); ?>" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_url')?>/js/fancybox-master/dist/jquery.fancybox.min.css" />

<!--JAVASCRIPT-->
<script type="text/javascript" src="<?php bloginfo('template_url')?>/js/jquery-2.1.4.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url')?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url')?>/js/fancybox-master/dist/jquery.fancybox.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url')?>/js/Chart.js-master/samples/utils.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url')?>/js/Chart.js-master/samples/Chart.PieceLabel.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_url')?>/js/printPreview.js?v=<?php echo time()?>"></script>
</head>
<body>
<div id="header">
	<div id="top-nav" class="container navbar-orange">
                    <div class="navbar-header">

                        <button type="button" class="navbar-toggle collapsed dropdown-toggle" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
								เมนู
                            <span class="caret"></span>
                        </button>
                        <button type="button" class="navbar-toggle btn-secondary">
                            <i class="fa fa-user"></i>
                        </button>
                        <button type="button" class="navbar-toggle btn-secondary" id="open-search-btn">
                            <i class="fa fa-search"></i>
                        </button>
                        <a class="navbar-brand col-xxs-4 col-xs-4" href="//www.thaipbs.or.th" title="Thai PBS logo">
                            <img src="http://news.thaipbs.or.th/images/logo/tpbs.png" class="logo" alt="Thai PBS logo">
                            <img src="http://news.thaipbs.or.th/images/logo/tpbs-sm.png" class="logo-sm" alt="Thai PBS logo">
                        </a>
                    </div>
                    <div id="navbar" class="navbar-collapse collapse">
                          <ul class="nav navbar-nav">
                            <li class="home">
                                <a href="//www.thaipbs.or.th" target="_blank" title="หน้าแรก">หน้าแรก</a>
                            </li>
                            <li>
                                <a href="//www.thaipbs.or.th/news" target="_blank" title="ข่าว">ข่าว</a>
                            </li>
                            <li>
                                <a href="//program.thaipbs.or.th" target="_blank" title="รายการทีวี">รายการทีวี</a>
                            </li>
                            <li>
								<a href="https://www.thaipbs.or.th/live" target="_blank" title="ชมสด">ชมสด</a>
                                <a class="hidden" href="http://thaipbs.or.th/live" target="_blank" title="ชมสด">ชมสด</a>
                            </li>
                            <li>
                                <a href="//program.thaipbs.or.th/watch" target="_blank" title="ชมย้อนหลัง">ชมย้อนหลัง</a>
                            </li>
                            <li>
                                <a href="http://www.thaipbsonline.net/" target="_blank" title="วิทยุ">วิทยุ</a>
                            </li>
                            <li>
                                <a href="//org.thaipbs.or.th/home" target="_blank" title="องค์กร">องค์กร</a>
                            </li>
                            <li class="dropdown">
                                <a href="#" id="language-switcher" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="full">ENGLISH</span><span class="short">ENG</span> <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li><a href="http://englishnews.thaipbs.or.th/" target="_blank" title="Thai PBS News">Thai PBS News</a></li>
            						<li><a href="http://www2.thaipbs.or.th/home.php" target="_blank" title="About Thai PBS">About Thai PBS</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="social-sharing">
                          
                            <li>
                                <a href="https://www.facebook.com/ThaiPBSFan?_rdr=p" target="_blank" title="Thai PBS Facebook"><i class="fa fa-facebook-square"></i></a>
                            </li>
                            <li>
                                <a href="https://twitter.com/ThaiPBS" target="_blank" title="Thai PBS Twitter"><i class="fa fa-twitter"></i></a>
                            </li>
                            <li>
                                <a href="https://www.youtube.com/user/Thaipbs" target="_blank" title="Thai PBS YouTube"><i class="fa fa-youtube-play"></i></a>
                            </li>
                            <li>
                                <a href="https://www.instagram.com/thaipbs/" target="_blank" title="Thai PBS Instagram"><i class="fa fa-instagram"></i></a>
                            </li>
                            <li>
                                <a href="http://gplus.to/ThaiPBS" target="_blank" title="Thai PBS Google Plus"><i class="fa fa-google-plus"></i></a>
                            </li>
                            <li class="rss">
                                <a href="http://news.thaipbs.or.th/rss/news/home" target="_blank" title="Thai PBS RSS"><i class="fa fa-rss"></i></a>
                            </li>
                         

                        </ul>
                      
                    </div>
    </div>
</div>  

<div id="<?php echo is_front_page() ? 'banner' : 'banner-page' ?>">
	<div id="secondary-nav">
	
	<div class="container-fluid">
     <nav class="navbar navbar-default navbar-fixed">
     
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed btn btn-xs btn-default " data-toggle="collapse" data-target="#navbar-collapse-1">
				เมนูหลัก <i class="fa fa-caret-down"></i>
          </button>
       
        </div>
    
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-collapse-1">
          <ul class="nav navbar-nav navbar-right">
          
				            <li>
                                <a href="<?php bloginfo('url')?>">หน้าหลัก</a>
                            </li>
				            <li>
                                <a href="<?php bloginfo('url')?>/missing">ศูนย์คนหาย</a>
                            </li>
              
                            <li>
								<a href="<?php bloginfo('url')?>/inform">แจ้งเรื่องร้องเรียน</a>
                            </li>
                            <li>
                                <a href="<?php bloginfo('url')?>/legal">ปรึกษากฎหมาย</a>
                            </li>
							<li>
                                <a href="<?php bloginfo('url')?>/contact">ติดต่อเรา</a>
                            </li>
           
          </ul>
         
        </div><!-- /.navbar-collapse -->
     
    </nav><!-- /.navbar -->
	</div>

				
			
		
	 </div> 
	<div class="container">
	<?php if(!is_front_page()){ ?>
	<div class="col-lg-6" id="logo">
		<span><img src="<?php echo get_template_directory_uri()?>/images/people-logo.png" class="img-responsive" /></span>
		<span><img src="<?php echo get_template_directory_uri()?>/images/rongtook-logo.png" class="img-responsive" /></span>
	</div>	
	<?php } ?>

</div>	 
</div>