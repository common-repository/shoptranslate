<?php
/**
 * Handles settings
 *
 * @package Wwt Translate
 * @since 1.0.0
 */

// Exit if accessed directly 
if ( !defined( 'ABSPATH' ) ) exit;

global $wwt_tran_options;

if( !empty( $_GET['page'] ) &&  $_GET['page'] == 'wwt-tran-shop-request-view' && !empty( $_GET['shop_request_id'] ) ) {
	
	$shop_request_id = $_GET['shop_request_id'];
	$prefix 	= WWT_TRAN_PREFIX;

	$data 		= get_post( $shop_request_id );
	$tran_type  = get_post_meta( $shop_request_id, $prefix.'translate_type', true );
?>
<!-- Start wrap div -->
<div class="wrap">

	<h2 class="wwt-tran-settings-title">
		<?php  echo __( 'Shop Request View', WWT_TRAN_TEXT_DOMAIN ); ?>
	</h2>

	<div id="wwt-tran-email-settings" class="post-box-container">
		<div class="metabox-holder">
			<div class="meta-box-sortables ui-sortable">
				<div id="general-settings" class="postbox">

					<div class="handlediv" title="<?php _e( 'Click to toggle', WWT_TRAN_TEXT_DOMAIN ) ;?>"><br /></div>

					<!-- product box title -->
					<h3 class="hndle">
						<span><?php echo __( 'Details of shop request', WWT_TRAN_TEXT_DOMAIN ); ?></span>
					</h3>

					<div class="inside">
						<table class="form-table wwt-tran-box">
							<tbody>
								<tr>
									<th scope="row">
										<label><?php _e( 'Title:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
									</th>
									<td>
										<?php echo ucfirst($data->post_title); ?>
									</td>
								</tr>
								<?php 
								if( $tran_type == 'default' ) {
								?>
								<tr>
									<th scope="row">
										<label><?php _e( 'Description:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
									</th>
									<td>
										<?php echo $data->post_content; ?>
									</td>
								</tr>
								<?php } ?>
								<tr>
									<th scope="row">
										<label><?php _e( 'Partial Text:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
									</th>
									<td>
										<?php echo get_post_meta( $shop_request_id, $prefix.'partial_text', true ); ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label><?php _e( 'Shop Name:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
									</th>
									<td>
										<?php echo get_post_meta( $shop_request_id, $prefix.'shop_name', true ); ?>
									</td>
								</tr>
								<?php 
								if( $tran_type == 'default' ) { ?>
								<tr>
									<th scope="row">
										<label><?php _e( 'Total Price:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
									</th>
									<td>
										<?php echo get_post_meta( $shop_request_id, $prefix.'total_price', true ); ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label><?php _e( 'Total Words:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
									</th>
									<td>
										<?php echo get_post_meta( $shop_request_id, $prefix.'total_words', true ); ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label><?php _e( 'Price Per Word:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
									</th>
									<td>
										<?php echo get_post_meta( $shop_request_id, $prefix.'price_per_word', true ); ?>
									</td>
								</tr>
								<?php }

								if( $tran_type != 'own' ) { ?>
								<tr>
									<th scope="row">
										<label><?php _e( 'Remark Text:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
									</th>
									<td>
										<?php echo get_post_meta( $shop_request_id, $prefix.'remark_text', true ); ?>
									</td>
								</tr>
								<?php } ?>
								<tr>
									<th scope="row">
										<label><?php _e( 'Request Type:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
									</th>
									<td>
										<?php echo get_post_meta( $shop_request_id, $prefix.'type', true ); ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label><?php _e( 'Translate Type:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
									</th>
									<td>
										<?php 
											  switch ( $tran_type ){
												case "google":
													 echo "Google Translate";
        											 break;
        										case "disable_product":
													 echo "Disable Product";
        											 break;
        										case "own":
													 echo "	Own Translation";
        											 break;
        										 default:
        											echo "Professional Translate";
											} ?>
									</td>
								</tr>
								<?php if( $tran_type == 'own' ) { 
									
									$own_data = get_post_meta( $shop_request_id, $prefix.'own_data', true );
									?>
								<tr>
									<th scope="row">
										<label><?php _e( 'Description:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
									</th>
									<td>
										<?php echo $own_data['full_description']; ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label><?php _e( 'Short Description:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
									</th>
									<td>
										<?php echo $own_data['short_description']; ?>
									</td>
								</tr>
								<?php } ?>
								<tr>
									<th scope="row">
										<label><?php _e( 'Page Url:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
									</th>
									<td>
										<?php echo get_post_meta( $shop_request_id, $prefix.'page_url', true ); ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label><?php _e( 'Method:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
									</th>
									<td>
										<?php echo get_post_meta( $shop_request_id, $prefix.'method', true ); ?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label><?php _e( 'Status:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
									</th>
									<td class="wwt_status">
										<?php 
											$status = get_post_meta( $shop_request_id, $prefix.'status',true );
											echo  !empty( $status )? ucfirst( str_replace( '_',  ' ', $status ) ) : '';
										?>
									</td>
								</tr>
								<tr>
									<th scope="row">
										<label><?php _e( 'Translated Text:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
									</th>
									<td class="wwt_translated_text">
										<?php 
											$translated_text = get_post_meta( $shop_request_id, $prefix.'completed_doc',true );
											echo  !empty( $translated_text )? $translated_text : '-';
										?>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
}