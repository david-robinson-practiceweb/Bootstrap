<!doctype HTML>
<html class="no-js">
<head>
  <title><?php print $head_title; ?></title>
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="viewport" content="width=device-width,user-scalable=no" />
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
  <?php print $bootstrap_touch_icons; ?>
   <!--[if lt IE 9]>
  <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
   <!--[if lt IE 9]>
  <script src="//cdnjs.cloudflare.com/ajax/libs/respond.js/1.2.0/respond.js"></script>
  <![endif]-->
  
  <!--[if lt IE 9]>
  <meta http-equiv="X-UA-Compatible" content="IE=9">
  <![endif]-->
</head>
<body class="<?php print $body_classes; ?>">

  <header id="header" class="hidden-xs">
		<div class="container">
			<div class="logo pull-left">
				<a href="<?php print $front_page; ?>" title="<?php print $site_name; ?> home"><img src="<?php print $logo; ?>" alt="<?php print $site_name; ?> logo"></a>
			</div>
			<div class="user-action pull-right hidden-xs">
				<a type="button" class="btn btn-default no-js" data-toggle="modal" href="#modalsearch">Search <span class="glyphicon glyphicon-search"></span></a>
        <?php if ($logged_in) { ?>
          <?php if ($is_admin) ?><a type="button" class="btn btn-primary" href="/admin">Admin <span class="glyphicon glyphicon-wrench"></span></a>
        <a type="button" class="btn btn-primary" href="/user">My profile <span class="glyphicon glyphicon-user"></span></a>
				<a type="button" class="btn btn-primary" href="/logout">Logout <span class="glyphicon glyphicon-log-out"></span></a>
        <?php } else { ?>
				<a type="button" class="btn btn-primary" href="/user/login">Login <span class="glyphicon glyphicon-log-in"></span></a>
				<a type="button" class="btn btn-primary" href="/user/register">Register <span class="glyphicon glyphicon-tasks"></span></a>
        <?php } ?>
			</div>
      <?php if(!empty($company_phone) || !empty($site_mail)) { ?>
			<div class="contact-details pull-right">
				<h4>
          <?php print $company_phone; ?>
          <?php if(!empty($company_phone) && !empty($site_mail)) print ' / '; ?>
           <?php if(!empty($site_mail)) {
            print '<a href="' . $site_mail . '" title="Email us">' . $site_mail . '</a></h4>';
           } ?>
			</div>
      <?php }; ?>
		</div>
	</header>
  
  <nav id="primary-links" class="navbar navbar-default" role="navigation">
    <div class="container">
      <div class="row">
        <div class="navbar-header"<?php print $mobile_nav_bg; ?>>
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="<?php print $front_page; ?>" title="<?php print $site_name; ?> home" class="navbar-brand visible-xs">
          <?php if ($mobile_logo == TRUE) { ?>
            <img src="/<?php print $mobile_logo_path; ?>" alt="<?php print $site_name; ?> logo">
          <?php } else { ?>
            <img src="<?php print $logo; ?>" alt="<?php print $site_name; ?> logo">
          <?php }; ?>          
          </a>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
          <div class="visible-xs navbar-utilities">
            <a type="button" class="btn btn-default no-js" data-toggle="modal" href="#modalsearch"><span class="glyphicon glyphicon-search"></span><span class="sr-only">Search</span></a>
            <?php if ($logged_in) { ?>
              <?php if ($is_admin) ?><a type="button" class="btn btn-default" href="/admin">Admin</a>
            <a type="button" class="btn btn-default" href="/user">Profile</span></a>
            <a type="button" class="btn btn-default" href="/logout">Logout</span></a>
            <?php } else { ?>
            <a type="button" class="btn btn-default" href="/user/login">Login</a>
            <a type="button" class="btn btn-default" href="/user/register">Register</a>
            <?php } ?>
          </div>
          <?php print theme('links', $primary_links, array('class' => 'primary-links'), 'primary-links'); ?>          
        </div>
      </div>
    </div>
  </nav>
  
  <?php print $mobile_contact_utilities; ?>
  
  
  
  <?php if ($banner) {
    print '<div class="jumbotron"><div class="container">';
      print $banner;
    print '</div></div>';
   } ?>
   
  <?php
    // Desktop and tablet breadcrumbs
  if ($breadcrumb) {
    print '<div class="container hidden-xs">';
      print $breadcrumb;
    print '</div>';
  } ?>
  
  <?php
  // Mobile breadcrumbs
  if ($breadcrumb || ($left && $layout_front_offcanvas_sidebar == FALSE)) {
    print '<div id="m-nav-helper" class="container visible-xs">';
      if ($breadcrumb) {
        print '<button type="button" class="btn btn-default btn-sm btn-block" data-toggle="collapse" data-target="#breadcrumbs">' . $mobile_breadcrumb_btn_text . '</button>';
        print '<div id="breadcrumbs" class="collapse">';
          print $breadcrumb;
        print '</div>';
      };
      if ($left) {
        print '<button type="button" class="btn btn-default btn-sm btn-block" data-toggle="offcanvas">' . $mobile_offcanvas_btn_text . '</button>';
      };
    print '</div>';
  } ?>
    
  
   
  <?php if ($messages){
    print '<div class="container">';
      print $messages;
    print '</div>';
  } ?>
  
  <section id="offcanvas-container" class="container">
    <div id="main"<?php print $css_main; ?>>
    
			<article id="content-area"<?php print $css_content; ?>>
				<?php print $content_top; ?>
        
        <?php if (!$is_front || $layout_front_display_page_title == TRUE) { ?>
          <h1><?php print $title; ?></h1>
          <?php print $tabs; ?>
        <?php }; ?>
        
        <?php if (!$is_front || $layout_front_display_page_content == TRUE) { ?>
          <?php print $content; ?>
        <?php }; ?>
        <div class="row">
				<?php print $content_bottom; ?>
        </div>
			</article>
      
      <?php if ($right) { ?>
			<aside<?php print $css_right; ?>>
				<?php print $right; ?>
			</aside>
      <?php } ?>
      
      <?php if ($left) { ?>
			<aside id="sidebar-left" role="navigation"<?php print $css_left; ?>>
				<?php print $left; ?>
			</aside>
      <?php } ?>
      
		</div>
    
    <?php print $bottom; ?>
    
  </section>
  
  
   
  
  <footer>
    <?php if ($footer) { ?>
		<div id="footer-top">
			<div class="container">
				<div class="row">
          <?php print $footer; ?>
				</div>
			</div>
		</div>
    <?php }; ?>
    
    <?php if ($secondary_links || $closure_blocks) { ?>
		<div id="footer-bottom">
			<div class="container">
        <?php print theme('links', $secondary_links, array('class' => 'secondary-links'), 'secondary-links'); ?>
        <?php print $closure_blocks; ?>
			</div>
		</div>
    <?php }; ?>
  </footer>
    
     
  <?php print $modal; ?>
  
  <!-- Modal Search -->
  <div class="modal fade" id="modalsearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Search</h4>
        </div>
        <div class="modal-body">
          <?php print $search_box; ?>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  
  
  
  
  <?php print $closure; ?>
  <script>
		$(document).ready(function() {
      $('html').removeClass('no-js');
			$('[data-toggle=offcanvas]').click(function() {
				$('.row-offcanvas').toggleClass('active');
			});
		});
	</script>   
  
</body>
</html>