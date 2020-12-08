<?php

function jp_xkcd_settings_init(){
	register_setting(
        'jp_xkcd_settings_group', // Name of the settings section
        'jp_xkcd_options' // Name of the option for the DB, use get_option()
    );

	// Settings section - Text
	add_settings_section(
		'jp_xkcd_api_options',
		__( 'API Options', 'wordpress' ),
		'jp_xkcd_api_options_cb',
		'jp_xkcd_settings_group'
	);

		// Field - message
		add_settings_field(
			'jp_xkcd_field_endpoint',
			__( 'API Endpoint', 'wordpress' ),
			'jp_xkcd_field_endpoint_render',
			'jp_xkcd_settings_group',
			'jp_xkcd_api_options'
		);


}
add_action( 'admin_init', 'jp_xkcd_settings_init' );



### Field outputs
function jp_xkcd_field_render( $field_id='', $classes='', $placeholder='' ){
	$options = get_option( 'jp_xkcd_options' );

	$value = '';
	if( isset($options[$field_id]) ){
		$value = $options[$field_id];
	}

	?>
	<input class="<?php echo $classes; ?>" type='text' name='jp_xkcd_options[<?php echo $field_id; ?>]' value='<?php echo $value; ?>' placeholder="<?php echo $placeholder; ?>">
	<?php
}



function jp_xkcd_field_endpoint_render(){
	$field_id = 'jp_xkcd_field_endpoint';
	jp_xkcd_field_render($field_id, 'widefat', 'http://xkcd.com/info.0.json');
}



function jp_xkcd_api_options_cb(){
	?>
	<p><em>Set the API endpoint.</a></em></p>
	<?php
}
