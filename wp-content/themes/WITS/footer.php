<?php
/**
 * Displays the footer section of the theme.
 *
 * @package 		Theme Horse
 * @subpackage 		Interface
 * @since 			Interface 1.0
 */
?>
<!--</div>-->
<!-- .container -->
</div>
<!-- #main -->

<?php
		/**
		 * interface_business_template_ourclients hook
		 *
		 * HOOKED_FUNCTION_NAME PRIORITY
		 *
		 * interface_display_business_template_ourclients 10
		 */

			if( is_page_template( 'page-templates/page-template-business.php' ) ) {
				do_action( 'interface_business_template_ourclients' );
			}

	?>
<?php
	      /**
	       * interface_after_main hook
	       */
	      do_action( 'interface_after_main' );
	   ?>
<?php
	   	/**
	   	 * interface_before_footer hook
	   	 */
	   	do_action( 'interface_before_footer' );
	   ?>

<footer id="colophon" class="clearfix">
	<div id="site-generator">
		<div class="container clearfix">
			<!-- .social-profiles -->
			<div class="copyright">
				<center>Copyright © 2016 Women in Tech Summit
					<br />Made with &hearts; by <a href="http://chariotsolutions.com"
					target="_blank" title="Chariot Solutions">
					<span>Chariot Solutions</span></a>
						<br /><a target="_blank" href="http://www.twitter.com/WomenTechSummit">
							<i class="fa fa-twitter-square fa-2x" aria-hidden="true"></i></a>
						<a target="_blank" href="http://www.facebook.com/WomenInTechSummit">
							<i class="fa fa-facebook-square fa-2x" aria-hidden="true"></i></a>
						<a target="_blank" href="https://www.instagram.com/womenintechsummit/">
							<i class="fa fa-instagram fa-2x"></i></a>
						<a target="_blank" href="https://www.youtube.com/channel/UCrmEDpAvErLjVkCLfvK-VCQ">
							<i class="fa fa-youtube-square fa-2x"></i></a>
			</div>
			<!-- .copyright -->
		</div>
		<!-- .container -->
	</div>
	<!-- #site-generator -->
</footer>
<?php
	   	/**
	   	 * interface_after_footer hook
	   	 */
	   	do_action( 'interface_after_footer' );
	   ?>
</div>
<!-- .wrapper -->

<?php
		/**
		 * interface_after hook
		 */
		do_action( 'interface_after' );
	?>
</body></html>
