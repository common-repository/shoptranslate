<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Scripts Class
 *
 * Handles adding scripts functionality to the admin pages
 * as well as the front pages.
 *
 * @package wwt Translate
 * @since 1.0.0
 */

class Wwt_Tran_Scripts {

	//class constructor
	function __construct() {
		
	}
  
	/**
	 * Enqueue Scripts on Admin Side
	 * 
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	public function wwt_tran_admin_scripts_and_styles($hook_suffix){

		global $wwt_tran_options;
		
		$screen 		= get_current_screen();
		$current_screen = $screen->id;

		$Shop_List	= !empty( $wwt_tran_options['shop_list'] ) ? $wwt_tran_options['shop_list'] : array( 0 => array( 'name' => 'Foreign Shop', 'url' => '', 'lang' => 'en' ));
		$Shop_List_options = '';
		foreach ( $Shop_List as $key => $value ) {
			$Shop_List_options .= '<option data-lang="'.$value['lang'].'" value="'.$key.'">'.$value['name'].'</option>';
		}

		//Register & Enqueue Script
		wp_register_script( 'wwt-translate-admin-script', WWT_TRAN_URL.'includes/js/stsmw-admin.js', array(), null, true);
		wp_localize_script( 'wwt-translate-admin-script','WWT_Tran',array(
																		'ajaxurl' 				=> admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) ),
																		'price_msg' 			=> __( 'Please enter at least any one price.', WWT_TRAN_TEXT_DOMAIN ),
																		'pro_inquiry_btn_text' 	=> __( 'Send Your Inquiry', WWT_TRAN_TEXT_DOMAIN ),
																		'pro_own_btn_text'     	=> __( 'Add Own Translation', WWT_TRAN_TEXT_DOMAIN ),
																		'translate_btn_text'   	=> __( 'Translate', WWT_TRAN_TEXT_DOMAIN ),
																		'disable_pro_btn_text' 	=> __( 'Save', WWT_TRAN_TEXT_DOMAIN ),
																		'api_valid'  			=> !empty( $wwt_tran_options['general']['api_valid'] ) ? $wwt_tran_options['general']['api_valid'] : '',
																		'HOSTNAME'				=> HOSTNAME,
																		'site_url'				=> get_site_url(),
																		'current_screen'		=> $current_screen,
																		'Shop_List_options'		=> $Shop_List_options,
																	));
		wp_enqueue_script('wwt-translate-admin-script');

		wp_register_style('wwt-translate-admin-style', WWT_TRAN_URL.'includes/css/stsmw-admin.css', array(), WWT_TRAN_VERSION );
		wp_enqueue_style('wwt-translate-admin-style');
	}

	/**
	 * Loading Additional Java Script
	 *
	 * Loads the JavaScript required for toggling the meta boxes on the theme settings page.
	 *
	 * @package AHG FS Loan Comparison Calculator
	 * @since 1.0.0
	 */
	public function wwt_tran_page_load_scripts( $hook_suffix ) {

		wp_enqueue_script( 'postbox' );
		?>
			<script type="text/javascript">
				//<![CDATA[
				jQuery(document).ready( function($) {
					$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
					postboxes.add_postbox_toggles( '<?php echo $hook_suffix ?>' );
				});
				//]]>
			</script>
		<?php
	}


	/**
	 * Adding Hooks
	 *
	 * Adding hooks for the styles and scripts.
	 *
	 * @package wwt Translate
	 * @since 1.0.0
	 */
	function add_hooks(){
		
		//add admin scripts
		add_action( 'admin_enqueue_scripts', array( $this, 'wwt_tran_admin_scripts_and_styles' ) );
	}
}
?>