<?php

### Add the options page to the Settings sub-navigation.
function jp_xkcd_add_admin_menu(){
	$hook_suffix = add_options_page( 'XKCD Settings', 'XKCD', 'manage_options', 'jp_xkcd', 'jp_xkcd_options_page_cb' );

	// Add the function that enqueues scripts/styles for the dashboard page.
	// add_action( 'admin_print_scripts-'.$hook_suffix, 'jp_xkcd_admin_scripts');

}
add_action( 'admin_menu', 'jp_xkcd_add_admin_menu' );



### HTML output for the options page.
function jp_xkcd_options_page_cb(){
	if( isset($_POST['responseSubmit']) && $_POST['responseSubmit'] === '1' ){
		jpxkcd_get_response();
	}
	?>

	<div class="wrap">

		<h1>XKCD Endpoint</h1>

		<form id="jp-xkcd-settings" action='options.php' method='post'>
		<?php
		settings_fields( 'jp_xkcd_settings_group' );
		do_settings_sections( 'jp_xkcd_settings_group' );
		submit_button('Save Endpoint', 'primary');
		?>
		</form>

		<?php
		$options = get_option( 'jp_xkcd_options' );
		$endpoint = false;

		if( isset($options['jp_xkcd_field_endpoint']) ){
			$endpoint = $options['jp_xkcd_field_endpoint'];
		}

		if( $endpoint ):?>
		<hr>

		<h2>Get API Response</h2>

		<form id="jp-xkcd-getResponse" action='' method='post'>

			<?php
			$response = get_option('jp_xkcd_response');
			?>
			<textarea disabled class="widefat">
				<?php echo serialize($response); ?>
			</textarea>

			<p class="submit">
				<button class="button button-primary" type="submit" name="responseSubmit" value="1">Get Response</button>
			</p>


		</form>

		<?php endif; ?>


	</div><!-- END .wrap -->

	<?php
}
