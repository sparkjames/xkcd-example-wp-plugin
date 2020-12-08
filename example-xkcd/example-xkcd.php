<?php
/**
 * Plugin Name: XKCD Comics
 * Description: Demonstrate XKCD API.
 * Version: 1.0
 * Author: James Pfleiderer
 * Author URI: https://jpfleiderer.com
 */

require_once('settings.php');
require_once('options-page.php');



function jp_xkcd_shortcode_cb( $atts ){

	$comics = get_option('jp_xkcd_response');
	// echo '<pre>'; print_r($comics); echo '</pre>';

	$html = '';
	if( is_array($comics) && !empty($comics) ){
		$html .= '<ul class="xkcdList">';

		foreach( $comics as $comic ){
			$html .= '<li class="xkcdList-item"><img class="xkcdList-image" src="'.$comic['img'].'" alt="'.$comic['alt'].'"></li>';
		}

		$html .= '</ul>';
	}

	return $html;

}
add_shortcode( 'xkcd', 'jp_xkcd_shortcode_cb' );



function jpxkcd_get_response(){

	$options = get_option( 'jp_xkcd_options' );
	$endpoint = false;

	if( isset($options['jp_xkcd_field_endpoint']) ){
		$endpoint = $options['jp_xkcd_field_endpoint'];
	}

	if( $endpoint ){
		$response = jp_xkcd_do_curl($endpoint);
		$all_responses = [];

		if( isset($response['num']) ){

			$all_responses[] = $response;

			// Get 3 more comics
			$comic_id = intval($response['num']);
			$more_ids = [];
			for( $i=1; $i<4; $i++ ){
				$new_id = $comic_id - $i;
				if( $new_id ){
					$more_ids[] = $new_id;
				}
			}

			if( !empty($more_ids) ){
				foreach( $more_ids as $additional_comic_id ){
					$new_endpoint = str_replace( "xkcd.com", "xkcd.com/".$additional_comic_id, $endpoint );
					$additional_response = jp_xkcd_do_curl($new_endpoint);
					if( isset($additional_response['num']) ){
						$all_responses[] = $additional_response;
					}
				}
			}

		}
		update_option( 'jp_xkcd_response', $all_responses );

	}

}



function jp_xkcd_do_curl( $url ){
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
	));

	$response = curl_exec($curl);
	$response_array = json_decode($response, true);

	curl_close($curl);
	return $response_array;

}


