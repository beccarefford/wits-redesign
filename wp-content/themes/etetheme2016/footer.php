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
				Copyright © 2016 <a href="http://chariotsolutions.com" target="_blank" title="Chariot Solutions"><span>Chariot Solutions</span></a>
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
