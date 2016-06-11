<div class="wrap about-wrap">
	<h1><?php _e( 'Conductor Add-ons', 'conductor' ); ?></h1>

	<?php do_action( 'conductor_options_notifications' ); ?>

	<?php // TODO ?>
	<div id="conductor-form" class="conductor-form-large conductor-form-full-width conductor-form-add-ons">
		<?php echo Conductor_Admin_Addons::fetch_addons_list(); ?>
	</div>

	<?php // include_once( 'html-conductor-options-sidebar.php' ); ?>
</div>