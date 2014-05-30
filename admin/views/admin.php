<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   Custom Featured Image Metabox
 * @author    1fixdotio <1fixdotio@gmail.com>
 * @license   GPL-2.0+
 * @link      http://1fix.io
 * @copyright 2014 1Fix
 */
?>

<!-- Create a header in the default WordPress 'wrap' container -->
<div class="wrap">

	<div id="icon-themes" class="icon32"></div>
	<h2><?php echo esc_html( get_admin_page_title() ) . ' Settings'; ?></h2>
	<?php // settings_errors(); ?>

	<form method="post" action="options.php">
		<?php
			$plugin = Custom_Featured_Image_Metabox::get_instance();

			settings_fields( $plugin->get_plugin_slug() );
			do_settings_sections( $plugin->get_plugin_slug() );

			submit_button();

		?>
	</form>

</div><!-- /.wrap -->
