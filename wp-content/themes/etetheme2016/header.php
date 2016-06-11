<?php
/**
 * Displays the header section of the theme.
 *
 * @package Theme Horse
 * @subpackage Interface
 * @since Interface 1.0
 */
?>
<!DOCTYPE html>

<link rel="stylesheet" id="googlefonts-css" href="http://fonts.googleapis.com/css?family=Montserrat:400,700|Source+Sans+Pro:400&amp;subset=latin" type="text/css" media="all">
<link rel="stylesheet" id="font-awesome-styles-css" href="http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css?ver=3.5" type="text/css" media="all">
<link rel="stylesheet" id="font-awesome-corp-styles-css" href="http://35dddumuqm3bnp65td2z5128.wpengine.netdna-cdn.com/wp-content/plugins/font-awesome-more-icons/assets/css/font-awesome-corp.css?ver=3.5" type="text/css" media="all">
<link rel="stylesheet" id="font-awesome-ext-styles-css" href="http://35dddumuqm3bnp65td2z5128.wpengine.netdna-cdn.com/wp-content/plugins/font-awesome-more-icons/assets/css/font-awesome-ext.css?ver=3.5" type="text/css" media="all">
<link rel="stylesheet" id="font-awesome-social-styles-css" href="http://35dddumuqm3bnp65td2z5128.wpengine.netdna-cdn.com/wp-content/plugins/font-awesome-more-icons/assets/css/font-awesome-social.css?ver=3.5" type="text/css" media="all">

<!--[if lte IE 7]>
<link rel='stylesheet' id='font-awesome-more-ie7-css'  href='http://35dddumuqm3bnp65td2z5128.wpengine.netdna-cdn.com/wp-content/plugins/font-awesome-more-icons/assets/css/font-awesome-more-ie7.min.css?ver=3.5' type='text/css' media='all' />
<![endif]-->
<!--[if lte IE 7]>
<link rel='stylesheet' id='font-awesome-ie7-css'  href='http://35dddumuqm3bnp65td2z5128.wpengine.netdna-cdn.com/wp-content/plugins/font-awesome/assets/css/font-awesome-ie7.min.css?ver=3.2.1' type='text/css' media='all' />
<![endif]-->
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8) ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<?php
		/**
		 * interface_title hook
		 *
		 * HOOKED_FUNCTION_NAME PRIORITY
		 *
		 * interface_add_meta_name 5
		 *
		 */
			//global $interface_theme_setting_value;
		 //echo $interface_theme_setting_value['home_slogan1' ];
		do_action( 'interface_title' );

		/**
		 * interface_meta hook
		 */
		do_action( 'interface_meta' );

		/**
		 * interface_links hook
		 *
		 * HOOKED_FUNCTION_NAME PRIORITY
		 *
		 * interface_add_links 10
		 * interface_favicon 15
		 * interface_webpage_icon 20
		 *
		 */
		do_action( 'interface_links' ); ?>
<?php
		/**
		 * This hook is important for WordPress plugins and other many things
		 */
		wp_head();
	?>

</head>

<body <?php body_class(); ?>>
<?php
		/**
		 * interface_before hook
		 */
		do_action( 'interface_before
			' );
	?>
<div class="wrapper">
<?php
			/**
			 * interface_before_header hook
			 */
			do_action( 'interface_before_header' );
		?>
<header id="branding" >
  <?php
				/**
				 * interface_header hook
				 *
				 * HOOKED_FUNCTION_NAME PRIORITY
				 *
				 * interface_headercontent_details 10
				 */
				do_action( 'interface_header' );
			?>
</header>
<?php
			/**
			 * interface_after_header hook
			 */
			do_action( 'interface_after_header' );
		?>
<?php
			/**
			 * interface_before_main hook
			 */
			do_action( 'interface_before_main' );
		?>

<div id="main">

<!--<div class="container">-->
