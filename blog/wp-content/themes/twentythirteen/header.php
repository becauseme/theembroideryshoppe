<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title><?php wp_title( '|', true, 'right' ); ?></title>
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div id="page" class="hfeed site">
		<header id="masthead" class="site-header" role="banner">
			<div class="header">
        


<div class="header-top-container">

    <div class="header-top" style="margin-bottom: -20px;">

	

	<table  border="0" cellpadding="0" cellspacing="0" class="table-responsive">

  <tbody><tr>

    <td align="left" valign="top" id="header_bg"><a href="http://www.theembroideryshoppe.com/index.php/index.php">

	</a><a href="http://www.theembroideryshoppe.com/index.php"><img src="http://www.theembroideryshoppe.com/skin/frontend/default/default/images/Pic.png" alt="theembroideryshoppe." border="0" style="float:left" class="img-resize"></a>	
	
	<a href="http://www.theembroideryshoppe.com/index.php/videogallery/" class="img-resize2"><img border="0" width="90" src="http://www.theembroideryshoppe.com/skin/frontend/default/default/images/vedios.png" align="left"></a>
</td>

	

    <td align="left" valign="top" id="menu_bg">

	<div class="head-menu">

	<ul>

	<li><a href="http://www.theembroideryshoppe.com/index.php/logo-gallery/">Logo Gallery</a></li>

    <li><a href="http://www.theembroideryshoppe.com/index.php/upload-logo/">Upload New Logo</a></li>

	<li><a href="http://www.theembroideryshoppe.com/index.php/about-us/">By Phone or Fax </a></li>

	<li> <a href="http://www.theembroideryshoppe.com/index.php/customer/account/">Order Status </a></li>

	<li> <a href="http://theembroideryshoppe.com/blog/">Blog</a></li>

	   

	

		 
    </ul>



	</div>

	
		<span style="float: right; margin-bottom:7px;">
         <a target="_blank" href="http://twitter.com/teshoppe" style="text-decoration:none; float:left;"><img align="absmiddle" alt="twitter" src="http://www.theembroideryshoppe.com/skin/frontend/default/default/images/social_twitter.png">
         </a>&nbsp;
         <a target="_blank" href="http://www.facebook.com/teshoppe" style="text-decoration:none;float:left;"><img align="absmiddle" alt="Facebook" src="http://www.theembroideryshoppe.com/skin/frontend/default/default/images/social_facebook.png">
         </a>&nbsp;
         <link href="https://plus.google.com/100268854444900840252" rel="publisher"><a href="https://plus.google.com/100268854444900840252?prsrc=3" style="text-decoration:none;float:left;">
         <img align="middle" src="http://www.theembroideryshoppe.com/skin/frontend/default/default/images/social_google.png" alt="">
         </a>
         &nbsp;<a style="margin-left:1px; text-decoration:none;float:left;" target="_blank" href="http://www.linkedin.com/company/the-embroidery-shoppe?trk=fc_badge">
         <img src="http://www.theembroideryshoppe.com/skin/frontend/default/default/images/social_linkedin.png" alt="The Embroidery Shoppe on LinkedIn" align="absmiddle" locale="en">
         </a><a href="http://www.theembroideryshoppe.com/index.php/#" class="textblack"><strong>800.705.0400</strong>&nbsp; M-F 9AM-5PM EST</a></span>
    <div class="head-menu2">

	

	<div class="rit-menu">


	 

     

	

	</div>

	

	

	</div>

	

	</td>

  </tr>

  

 </tbody></table>

	

	

      

    </div>

</div>

    </div>

			<div id="navbar" class="navbar">
				<nav id="site-navigation" class="navigation main-navigation" role="navigation">
					<button class="menu-toggle"><?php _e( 'Menu', 'twentythirteen' ); ?></button>
					<a class="screen-reader-text skip-link" href="#content" title="<?php esc_attr_e( 'Skip to content', 'twentythirteen' ); ?>"><?php _e( 'Skip to content', 'twentythirteen' ); ?></a>
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_class' => 'nav-menu', 'menu_id' => 'primary-menu' ) ); ?>
					<?php get_search_form(); ?>
				</nav><!-- #site-navigation -->
			</div><!-- #navbar -->
		</header><!-- #masthead -->

		<div id="main" class="site-main">
<style>
.head-menu {
  float: left;
  width: 100%;
}
.head-menu ul {
  float: right;
  width: 100%;
  list-style: none;
  margin: 0;
  padding: 0;
}
.head-menu ul li {
  background: url(http://www.theembroideryshoppe.com/skin/frontend/default/default/images/nav1-bullet.png) no-repeat left center;
  display: block;
  font-size: 11px;
  line-height: 20px;
  text-align: left;
  float: right;
  padding: 10px 14px;
}
</style>