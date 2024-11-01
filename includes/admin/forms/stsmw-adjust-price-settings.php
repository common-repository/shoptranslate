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

//General Settings
$special_id	= !empty( $wwt_tran_options['general']['special_id'] ) ? $wwt_tran_options['general']['special_id'] : '' ;

if( isset( $_GET['settings-updated'] ) && !empty( $_GET['settings-updated'] ) ) { // Check settings updated or not

	echo '<div id="message" class="updated fade notice is-dismissible"><p><strong>' . __( 'Send Rule Successfully.', WWT_TRAN_TEXT_DOMAIN ) . '</strong></p></div>'; 
}
?>
<!-- Start wrap div -->
<div class="wrap">

	<h2 class="wwt-tran-settings-title">
		<?php  echo __( 'Rules Settings', WWT_TRAN_TEXT_DOMAIN ); ?>
	</h2>

	<form action="" method="post">

		<div id="wwt-tran-email-settings" class="post-box-container">
			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
					<div id="general-settings" class="postbox">

						<div class="handlediv" title="<?php _e( 'Click to toggle', WWT_TRAN_TEXT_DOMAIN ) ;?>"><br /></div>

						<!-- product box title -->
						<h3 class="hndle">
							<span><?php echo __( 'Pricing Settings', WWT_TRAN_TEXT_DOMAIN ); ?></span>
						</h3>

						<div class="inside">
							<table class="form-table wwt-tran-box">
								<tbody>
									<tr>
										<th scope="row">
											<label for="wwt-tran-price-options"><?php _e( 'Pricing Rule On :', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<select name="wwt-tran-price-options" id="wwt-tran-price-options">
												<option value="general"><?php _e( 'General Pricing', WWT_TRAN_TEXT_DOMAIN) ?></option>
												<option value="product"><?php _e( 'Product Pricing', WWT_TRAN_TEXT_DOMAIN) ?></option>
												<option value="category"><?php _e( 'Category Pricing', WWT_TRAN_TEXT_DOMAIN) ?></option>
											</select><br/>
											<span class="description"><?php echo __( 'Select your pricing rule.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr class="wwt-tran-product-section">
										<th scope="row">
											<label for="wwt-tran-price-product"><?php _e( 'Products :', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
										<?php
											//Pass argument
											$args = array(
														'post_type'   	 => 'product',
														'post_status'      => 'publish',
														'order'            => 'DESC',
														'posts_per_page' => -1,
													);

											//Get all products list
											$posts = get_posts( $args );
										?>
											<select name="wwt-tran-price-product" id="wwt-tran-price-product">
											<?php
												foreach ( $posts as $key => $value ) {
													echo '<option value="'.$value->ID.'">'.$value->post_title.'</option>';
												}
											?>
											</select><br/>
											<span class="description"><?php echo __( 'Select your product.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr class="wwt-tran-product-cat-section">
										<th scope="row">
											<label for="wwt-tran-product-cat"><?php _e( 'Categories :', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<?php $args = array(
															'show_option_all'    => '',
															'show_option_none'   => '',
															'option_none_value'  => '-1',
															'orderby'            => 'ID',
															'order'              => 'ASC',															
															'hide_empty'         => 1,
															'child_of'           => 0,
															'echo'               => 1,
															'selected'           => 0,
															'hierarchical'       => 0,
															'name'               => 'wwt-tran-product-cat',
															'id'                 => 'wwt-tran-product-cat',
															'class'              => 'wwt-tran-product-cat',
															'taxonomy'           => 'product_cat',
															'hide_if_empty'      => false,
															'value_field'	     => 'term_id',
														);
												wp_dropdown_categories( $args );
											?>
											<br/>
											<span class="description"><?php echo __( 'Select your product category.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-tran-product-price-in-percent"><?php _e( 'Price (percentage):', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="number" id="wwt-tran-product-price-in-percent" class="small-text" name="wwt-tran-product-price-in-percent" /> %<br />
											<span class="description"><?php echo __( 'Enter your amount in percentage.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-tran-product-price-in-flat"><?php _e( 'Extra Cost:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="number" step="0.01" id="wwt-tran-product-price-in-flat" class="small-text" name="wwt-tran-product-price-in-flat" /><br />
											<span class="description"><?php echo __( 'Enter your extra cost.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<!--<tr>
										<th scope="row">
											<label for="wwt-tran-product-price-format"><?php _e( 'Rule Type :', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="radio" value="flat" name="wwt-tran-product-price-format" class="wwt-tran-product-price-format" id="wwt-tran-product-price-format" checked="checked"><label for="wwt-tran-product-price-format"><?php _e( 'Flat', WWT_TRAN_TEXT_DOMAIN )?></label>
											<input type="radio" value="percentage" name="wwt-tran-product-price-format" class="wwt-tran-product-price-format" id="wwt-tran-product-price-format1"> <label for="wwt-tran-product-price-format1"><?php _e( 'Percentage', WWT_TRAN_TEXT_DOMAIN )?></label>
										</td>
									</tr>-->
									<tr>
										<td colspan="2">
											<input type="submit" class="button button-primary right wwt-tran-adjust-price-btn" name="wwt-tran-product-send-details" id="wwt_tran_adjust_price_save" value="<?php _e( 'Send Rule', WWT_TRAN_TEXT_DOMAIN );?>" />
										</td>
									</tr>
								</tbody>
							</table>
						</div>

					</div>
				</div>
			</div>
		</div>
	</form>

	<?php include( WWT_TRAN_ADMIN_DIR.'/forms/stsmw-product-log-list.php'); ?>
</div>	