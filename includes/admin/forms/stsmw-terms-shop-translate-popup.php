<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

global $wwt_tran_options;

if ( !empty( $taxonomy_data ) ) {

$description	= !empty( $taxonomy_data->description )? $taxonomy_data->description : '';
$term_id		= !empty( $taxonomy_data->term_id )? $taxonomy_data->term_id : '';
$title		    = !empty( $taxonomy_data->name )? $taxonomy_data->name : '';

$description_word = !empty( $description ) ? str_word_count( strip_tags( $description ) ) : 0;
$title_word 	  = !empty( $title ) ? str_word_count( strip_tags( $title ) ) : 0;
$total_word 	  = $description_word + $title_word;

$price_of_word  = !empty( $wwt_tran_options['general']['special_id'] )?  (int)substr( $wwt_tran_options['general']['special_id'],0, 2 ) : 0;
$price_of_word  = is_numeric($price_of_word) ? $price_of_word  : 0;
$per_minute_price  = !empty($price_of_word) && $price_of_word != 0 ? $price_of_word / 15 : 0;
?>

<div class="wwt-tran-modal-overly"></div>

<!-- SEO Translate Popup -->
<div id="wwt-tran-term-seo-pro-modal" class="wwt-tran-modal">
	<div class="wwt-tran-modal-content">
		<div class="wwt-tran-modal-header">
			<span class="wwt-tran-modal-close">&times;</span>
			<h3><?php _e( 'SEO Professional Translate', WWT_TRAN_TEXT_DOMAIN ); ?></h3>
		</div>
		<div class="wwt-tran-modal-body">
			<p class="wwt-modal-logo">
				<img src="<?php echo WWT_TRAN_URL.'includes/images/ShopTranslateLogo.jpg' ?>" class="wwt-tran-logo-sidebar" alt="<?php _e( 'Shop Translate', WWT_TRAN_TEXT_DOMAIN ); ?>">
			</p>
			<p>	
				<div class="wwt-tran-msg"></div>
			</p>
			<p>
				<span class="wwt-modal-span"><?php _e( 'SEO Advise :', WWT_TRAN_TEXT_DOMAIN )?> </span>
				<input type="radio" value="Particular Question" checked="checked" class="wwt-tran-seo-advise" id="wwt-tran-question-type" name="wwt-tran-question-type"><label class="wwt-tran-label-normal" for="wwt-tran-question-type"><?php _e( 'Particular Question', WWT_TRAN_TEXT_DOMAIN ) ?></label>
				<input type="radio" value="General Advice" class="wwt-tran-seo-advise" id="wwt-tran-question-type1" name="wwt-tran-question-type"><label class="wwt-tran-label-normal" for="wwt-tran-question-type1"><?php _e( 'General Advice', WWT_TRAN_TEXT_DOMAIN ) ?></label>
			</p>
			<p class="wwt-tran-question-section">
				<span class="wwt-modal-span"><?php _e( 'Particular question :', WWT_TRAN_TEXT_DOMAIN ); ?></span><br/>
				<input type="text" id="wwt-tran-seo-text" name="wwt-tran-seo-text" class="wwt-tran-seo-text regular-text" placeholder="<?php _e( 'Enter your question here.', WWT_TRAN_TEXT_DOMAIN ); ?>">
			</p>
			<p>
				<span class="wwt-modal-span"><?php _e( 'Choose how much time we should spend :',  WWT_TRAN_TEXT_DOMAIN ) ?></span>
				<select name="wwt-tran-seo-spend-time" id="wwt-tran-seo-spend-time">
					<option value="15"><?php echo sprintf( __( '15 Minutes Costs: %s %s', WWT_TRAN_TEXT_DOMAIN ), '&#8364;', round($per_minute_price*15, 2) ); ?></option>
					<option value="30"><?php echo sprintf( __( '30 Minutes Costs: %s %s', WWT_TRAN_TEXT_DOMAIN ), '&#8364;', round($per_minute_price*30, 2) ); ?></option>
					<option value="45"><?php echo sprintf( __( '45 Minutes Costs: %s %s', WWT_TRAN_TEXT_DOMAIN ), '&#8364;', round($per_minute_price*45, 2) ); ?></option>
					<option value="60"><?php echo sprintf( __( '60 Minutes Costs: %s %s', WWT_TRAN_TEXT_DOMAIN ), '&#8364;', round($per_minute_price*60, 2) ); ?></option>
				</select>
			</p>
			<p>
				<span class="wwt-modal-span"><?php _e( 'Cost :', WWT_TRAN_TEXT_DOMAIN )?> </span>
				<span id="wwt-tran-spend-time-display"><?php _e( '15 Minutes', WWT_TRAN_TEXT_DOMAIN ); ?></span> 
				 &#8364; <input type="text" id="wwt-tran-seo-total-price" value="<?php echo round($per_minute_price*15,2); ?>" readonly>				
				<input type="hidden" id="wwt-tran-seo-word-price" name="wwt-tran-seo-word-price" value="<?php echo $per_minute_price;?>">
 			</p>
			<p><?php _e( 'The costs will be invoiced to your account. SEO suggestions will be send within 48 hours after payment is done.', WWT_TRAN_TEXT_DOMAIN ); ?></p>
			<p><input type="hidden" name="wwt-tran-term-id" id="wwt-tran-term-id" value="<?php echo $term_id; ?>"></p>
			
			
			<p><input type="hidden" name="wwt-tran-seo-type" id="wwt-tran-seo-type" value="seo"></p>
			<p> 
				<a href="javascript:void(0)" class="wwt-tran-send-seo-pro-term-btn wwt-tran-btn"><?php _e( 'Send Your Inquiry', WWT_TRAN_TEXT_DOMAIN );?></a>
				<div class="loader-wrap"><div class="wwt-tran-loading"></div></div>
			</p>
		</div>
	</div>
</div>
<!-- SEO Translate Popup -->

<?php }
