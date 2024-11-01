<?php

if ( !defined( 'ABSPATH' ) ) exit;

class Wwt_Tran_Model {

	 public function wwt_tran_escape_attr($data){

	 	return esc_attr(stripslashes($data));
	 }

	 public function wwt_tran_escape_slashes_deep($data = array(),$flag = false){

	 	if($flag != true) {
			$data = $this->wwt_tran_nohtml_kses($data);
		}
		$data = stripslashes_deep($data);
		return $data;
	 }
 
	public function wwt_tran_nohtml_kses($data = array()) {

		if ( is_array($data) ) {

			$data = array_map(array($this,'wwt_tran_nohtml_kses'), $data);

		} elseif ( is_string( $data ) ) {

			$data = wp_filter_nohtml_kses($data);
		}

		return $data;
	}

	public function wwt_tran_pro_enable() {

		 global $post, $wwt_tran_options;

		 //Get post Id if Exist
		$post_id	  = isset( $post->ID ) ? $post->ID : '';

		//Get Current post type
		$post_type	  = get_post_type();

		//Get professional type if exist
		$professional_type = !empty( $wwt_tran_options['professional']['type'] ) ? $wwt_tran_options['professional']['type'] : array();

		//Get prevent type items if exist
		//$professional_items	= !empty( $wwt_tran_options['professional']['item_' . $post_type] ) ? $wwt_tran_options['professional']['item_' . $post_type] : array();

	    if( in_array( $post_type, $professional_type ) ) {
	    	return true;
	    }
	}

	public function wwt_tran_seo_pro_enable() {

		 global $post, $wwt_tran_options;

		 //Get post Id if Exist
		$post_id	  = isset( $post->ID ) ? $post->ID : '';

		//Get Current post type
		$post_type	  = get_post_type();

		//Get professional type if exist
		$seo_professional_type = !empty( $wwt_tran_options['seo_professional']['type'] ) ? $wwt_tran_options['seo_professional']['type'] : array();

	    if( in_array( $post_type, $seo_professional_type ) ) {
	    	return true;
	    }
	}

	public function wwt_tran_product_enable() {

		 global $post, $wwt_tran_options;

		 //Get post Id if Exist
		$post_id	  = isset( $post->ID ) ? $post->ID : '';

		//Get Current post type
		$post_type	  = get_post_type();

		//Get product enable type if exist
		$product_enable = !empty( $wwt_tran_options['product']['enable'] ) ? $wwt_tran_options['product']['enable'] : array();

	    if( !empty( $product_enable ) ) {
	    	return true;
	    }
	}

	public function wwt_tran_product_log_data( $args = array() ) {
		
		$prefix 	= WWT_TRAN_PREFIX;
		$data_res	= array();

		// Default argument
		$queryargs = array(
							'post_type' => WWT_TRAN_LOG_POST_TYPE,
							'post_status' => 'publish'
						);

		$queryargs = wp_parse_args( $args, $queryargs );

		// Fire query in to table for retriving data
		$result = new WP_Query( $queryargs );

		//retrived data is in object format so assign that data to array for listing
		$postslist = $this->wwt_tran_object_to_array($result->posts);

		$data_res['data'] 	= $postslist;

		//To get total count of post using "found_posts" and for users "total_users" parameter
		$data_res['total']	= isset($result->found_posts) ? $result->found_posts : '';

		return $data_res;
	}

	public function wwt_tran_object_to_array($result){

	    $array = array();
	    foreach ($result as $key=>$value){	

	        if (is_object($value)){
	            $array[$key]=$this->wwt_tran_object_to_array($value);
	        } else {
	        	$array[$key]=$value;
	        }
	       
	    }
	   
	    return $array;
	}

	function wwt_google_translate( $to_lang, $text ) {
		global $wwt_tran_options;

		$result = array();
		$ApiKey = !empty( $wwt_tran_options['general']['tran_api_key'] ) ? $wwt_tran_options['general']['tran_api_key'] : '' ;

		if( !empty( $ApiKey ) && !empty( $wwt_tran_options['general']['api_valid'] ) ) {
			$url    = add_query_arg( array( 
										'key' => $ApiKey,
										'q'   => rawurlencode( $text ),
										'source' => '',
										'target' => $to_lang
									 ), 'https://www.googleapis.com/language/translate/v2' );
	
	    	$response = wp_remote_get( $url );

	    	if( is_array( $response ) && $response['response']['code'] == 200 ) {
	
	    		$response = json_decode( wp_remote_retrieve_body($response), true );
	    		$result['sucess'] = '1';
		        $result['text'] = !empty( $response['data']['translations'][0]['translatedText'] ) ? $response['data']['translations'][0]['translatedText'] : '';
	    	}else{
	    		$result['error'] = __( 'Error in translations request, Please contact Adminstrator.', WWT_TRAN_TEXT_DOMAIN );
	    	}
		} else{
			$result['error'] = __( 'Set your valid Api Key', WWT_TRAN_TEXT_DOMAIN );
		}
	    return $result;
	}

	function wwt_google_translate_lang_list() {

		global $wwt_tran_options;

		$ApiKey = !empty( $wwt_tran_options['general']['tran_api_key'] ) ? $wwt_tran_options['general']['tran_api_key'] : '' ;

		if( !empty( $ApiKey ) && !empty( $wwt_tran_options['general']['api_valid'] ) ) {
		
			$url = 'https://translation.googleapis.com/language/translate/v2/languages?key='.$ApiKey.'&target=en';
			$response = wp_remote_get( $url );

			if( is_array( $response ) && $response['response']['code'] == 200 ) {
				$response = json_decode( wp_remote_retrieve_body($response), true );
				$result   = !empty( $response['data']['languages'] ) ?  $response['data']['languages'] : array();
			}else {
				$result = array();
			}
		}else{
			$result = array();
		}
		return $result;
	}

	function wwt_tran_get_product_data_for_email( $args ) {

		$product_list = '';
		$product_data = get_posts( $args );

		if( !empty( $product_data ) ) {

			foreach ( $product_data as $product ) {
				$product_list .= '<br/> - ID : '.$product->ID;
				$product_list .= '<br/> - Title : '.$product->post_title;
				$link = get_permalink($product->ID);
				$product_list .= '<br/> - Url : '.$link.'<br/>';
			}
		}

		return $product_list;
	}

	function wwt_tran_get_category_data_for_email( $cat_args ) {

		$cat_list = '';
		$cat_data = get_terms( $cat_args );
		if( !empty( $cat_data ) ) {

			foreach( $cat_data as $cat ) {
	
				$cat_list .= '<br/> - ID : '.$cat->term_id;
				$cat_list .= '<br/> - Title : '.$cat->name;
				$arg 	   = array( 
								'taxonomy' => !empty( $cat->taxonomy ) ? $cat->taxonomy :'',
								'tag_ID'   => $cat->term_id,
									 );
				$link    = add_query_arg( $arg , admin_url( 'term.php', ( is_ssl() ? 'https' : 'http' ) ) );
				$cat_list .= '<br/> - Url : '.$link.'<br/>';
			}
		}

		return $cat_list;
	}

	/**
	 * Get Shop plugin details
	 *
	 * @param : $shop_url => Shop Url for get details
	 */
	function wwt_tran_get_shop_plugin_details( $shop_url ) {

		$args = array(
					  'timeout'     => 10,
					  'redirection' => 5,
					  'httpversion' => '1.0',
					  'blocking'    => true,
					  'headers'     => array( 'get-all-plugins' => '1' ),
					  'body'        => null,
					  'compress'    => true,
					  'decompress'  => true,
					);

		//Build url for get data
		$shop_url 	   = add_query_arg( array( 'get-all-plugins' => 1 ), $shop_url );
		$plugin_data   = wp_remote_get( $shop_url, $args );

		//Check Error on request
		if( !is_wp_error( $plugin_data ) ) {

			//Get request redsponse code
			$response_code = wp_remote_retrieve_response_code( $plugin_data );

			//Check response sucess
			if( $response_code == 200 ) {

				//Get request body data
				$api_response = wp_remote_retrieve_body( $plugin_data );
				//Convert data json to array
				$api_response = json_decode( $api_response, true );

				//Check data is set
				if( !empty( $api_response )) {
					return $api_response;
				}else{
					return __( 'Requested Shop plugin not available', WWT_TRAN_TEXT_DOMAIN );
				}

			}else{
				return __( 'Requested Shop response not valid', WWT_TRAN_TEXT_DOMAIN );	
			}
		}else {
			return __( 'Requested Shop Url is not valid', WWT_TRAN_TEXT_DOMAIN );
		}
	}

	public function wwt_tran_shop_request_log_data( $args = array() ) {

		$prefix 	= WWT_TRAN_PREFIX;
		$data_res	= array();

		// Default argument
		$queryargs = array(
							'post_type'   => WWT_TRAN_REQUEST_PT,
							'post_status' => 'publish'
						);

		$queryargs = wp_parse_args( $args, $queryargs );

		// Fire query in to table for retriving data
		$result = new WP_Query( $queryargs );

		//retrived data is in object format so assign that data to array for listing
		$postslist = $this->wwt_tran_object_to_array($result->posts);

		$data_res['data'] 	= $postslist;

		//To get total count of post using "found_posts" and for users "total_users" parameter
		$data_res['total']	= isset($result->found_posts) ? $result->found_posts : '';

		return $data_res;
	}

	public function wwt_tran_seo_payment_add_to_cart_setup( $arg_body_parameter = array() ) {

		//Checkout Argument
		$args = array(
					'method' 		=> 'POST',
					'timeout' 		=> 45,
					'redirection' 	=> 5,
					'httpversion' 	=> '1.0',
					'blocking' 		=> true,
					'headers' 		=> array(),
					'body' 			=> $arg_body_parameter,
					'cookies' 		=> array(),
				);

		//Call CURL TO Setup Product
		$response_data = wp_remote_post( HOSTNAME, $args );

		//Check Error on request
		if( !is_wp_error( $response_data ) ) {

			//Get request redsponse code
			$response_code = wp_remote_retrieve_response_code( $response_data );

			//Check response sucess
			if( $response_code == 200 ) {

				//Get request body data
				$api_response = wp_remote_retrieve_body( $response_data );

				//Convert data json to array
				$api_response = json_decode( $api_response, true );

				//Check data is set
				if( !empty( $api_response ) ) {

					$response['sucess']  = 1;
					$response['post_id'] = $api_response;
					$response['msg'] 	 = __('Wait a moment we setup payment page for you...', WWT_TRAN_TEXT_DOMAIN);
					$response['data']['product_id'] = $api_response;

					//Shop Request Log Process
					$user_id 		   = get_current_user_id();
					$shop_request_args = array(
											'post_type' 	=> WWT_TRAN_REQUEST_PT,
											'post_status' 	=> 'publish',
											'post_title' 	=> !empty( $arg_body_parameter['createproduct'] )? $arg_body_parameter['createproduct'] : '',
											'post_author' 	=> $user_id,
											'post_content' 	=> !empty( $arg_body_parameter['description'] )? $arg_body_parameter['description'] : '',
											'menu_order' 	=> 0,
											'comment_status'=> 'closed'
											);

					//Create Shop Request Log post
					$shop_request_log_id 	= wp_insert_post( $shop_request_args );

					if( !empty( $shop_request_log_id ) ) {

						$price_per_word = isset($form_data['price_per_word']) ? $form_data['price_per_word'] : '';

						update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'total_price', !empty( $arg_body_parameter['productprice'] )? $arg_body_parameter['productprice'] : '' );
						update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'type', !empty( $arg_body_parameter['type'] )? $arg_body_parameter['type'] : '' );
						update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'method', 'Checkout' );
						update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'status', 'SEO Suggestions' );
						//update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'textmaster_data', $textmaster_data );
					}
				}else{
					$response['error'] = __('Service not available. Please try again later...', WWT_TRAN_TEXT_DOMAIN);
				}
			}
		}else{
			$response['error'] =  __('Service not available. Please try again later...', WWT_TRAN_TEXT_DOMAIN);
		}

		return $response;
	}
}
?>