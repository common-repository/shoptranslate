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

//Professional Data set
$professional_recipient		= !empty( $wwt_tran_options['professional']['admin_recipient'] ) ? $wwt_tran_options['professional']['admin_recipient'] : get_option('admin_email');
$professional_email_body	= !empty( $wwt_tran_options['professional']['admin_email_body'] ) ? $wwt_tran_options['professional']['admin_email_body'] : '' ; 
$professional_from_name		= !empty( $wwt_tran_options['professional']['from_name'] ) ? $wwt_tran_options['professional']['from_name'] : '' ; 
$professional_from_email	= !empty( $wwt_tran_options['professional']['from_email'] ) ? $wwt_tran_options['professional']['from_email'] : '' ; 
$term_professional_email_body = !empty( $wwt_tran_options['professional']['term_admin_email_body'] ) ? $wwt_tran_options['professional']['term_admin_email_body'] : '' ; 

//Schedule Data set
$schedule_recipient		= !empty( $wwt_tran_options['schedule']['admin_recipient'] ) ? $wwt_tran_options['schedule']['admin_recipient'] : get_option('admin_email');
$schedule_email_body	= !empty( $wwt_tran_options['schedule']['admin_email_body'] ) ? $wwt_tran_options['schedule']['admin_email_body'] : '' ; 
$schedule_from_name		= !empty( $wwt_tran_options['schedule']['from_name'] ) ? $wwt_tran_options['schedule']['from_name'] : '' ; 
$schedule_from_email	= !empty( $wwt_tran_options['schedule']['from_email'] ) ? $wwt_tran_options['schedule']['from_email'] : '' ; 
$schedule_type			= !empty( $wwt_tran_options['schedule']['schedule_type'] ) ? $wwt_tran_options['schedule']['schedule_type'] : '' ; 

//SEO Professional Data set
$seo_professional_recipient		= !empty( $wwt_tran_options['seo_professional']['admin_recipient'] ) ? $wwt_tran_options['seo_professional']['admin_recipient'] : get_option('admin_email');
$seo_professional_email_body	= !empty( $wwt_tran_options['seo_professional']['admin_email_body'] ) ? $wwt_tran_options['seo_professional']['admin_email_body'] : '' ; 
$seo_professional_from_name		= !empty( $wwt_tran_options['seo_professional']['from_name'] ) ? $wwt_tran_options['seo_professional']['from_name'] : '' ; 
$seo_professional_from_email	= !empty( $wwt_tran_options['seo_professional']['from_email'] ) ? $wwt_tran_options['seo_professional']['from_email'] : '' ; 
$term_seo_professional_email_body = !empty( $wwt_tran_options['seo_professional']['term_admin_email_body'] ) ? $wwt_tran_options['seo_professional']['term_admin_email_body'] : '' ;

//Prices Data set
$product_enable		= !empty( $wwt_tran_options['product']['enable'] ) ? $wwt_tran_options['product']['enable'] : '';
$product_recipient	= !empty( $wwt_tran_options['product']['admin_recipient'] ) ? $wwt_tran_options['product']['admin_recipient'] : get_option('admin_email');
$product_email_body	= !empty( $wwt_tran_options['product']['admin_email_body'] ) ? $wwt_tran_options['product']['admin_email_body'] : '' ;
$product_from_name	= !empty( $wwt_tran_options['product']['from_name'] ) ? $wwt_tran_options['product']['from_name'] : '' ;
$product_from_email	= !empty( $wwt_tran_options['product']['from_email'] ) ? $wwt_tran_options['product']['from_email'] : '' ;
$term_product_email_body	= !empty( $wwt_tran_options['product']['term_admin_email_body'] ) ? $wwt_tran_options['product']['term_admin_email_body'] : '' ;

//General Settings
$special_id	= !empty( $wwt_tran_options['general']['special_id'] ) ? $wwt_tran_options['general']['special_id'] : '' ;
$tran_api_key = !empty( $wwt_tran_options['general']['tran_api_key'] ) ? $wwt_tran_options['general']['tran_api_key'] : '' ;
$tran_api_valid = !empty( $wwt_tran_options['general']['api_valid'] ) ? $wwt_tran_options['general']['api_valid'] : '' ;

//Plugin Schedule Data set
$plug_schedule_recipient  = !empty( $wwt_tran_options['plugin_different_schedule']['admin_recipient'] ) ? $wwt_tran_options['plugin_different_schedule']['admin_recipient'] : get_option('admin_email');
$plug_schedule_email_body = !empty( $wwt_tran_options['plugin_different_schedule']['admin_email_body'] ) ? $wwt_tran_options['plugin_different_schedule']['admin_email_body'] : '' ; 
$plug_schedule_from_name  = !empty( $wwt_tran_options['plugin_different_schedule']['from_name'] ) ? $wwt_tran_options['plugin_different_schedule']['from_name'] : '' ; 
$plug_schedule_from_email = !empty( $wwt_tran_options['plugin_different_schedule']['from_email'] ) ? $wwt_tran_options['plugin_different_schedule']['from_email'] : '' ; 
$plug_schedule_type		  = !empty( $wwt_tran_options['plugin_different_schedule']['schedule_type'] ) ? $wwt_tran_options['plugin_different_schedule']['schedule_type'] : '' ; 

$shop_list	= !empty( $wwt_tran_options['shop_list'] ) ? $wwt_tran_options['shop_list'] : array( 0 => array( 'name' => 'Foreign Shop', 'lang' => 'en' ) );

$get_pages = get_pages();

$terms_list = get_taxonomies( array(), 'objects' );

if( isset( $_GET['settings-updated'] ) && !empty( $_GET['settings-updated'] ) ) { // Check settings updated or not

	echo '<div id="message" class="updated fade notice is-dismissible"><p><strong>' . __( 'Changes Saved Successfully.', WWT_TRAN_TEXT_DOMAIN ) . '</strong></p></div>'; 
}
?>
<!-- Start wrap div -->
<div class="wrap">

	<h2 class="wwt-tran-settings-title">
		<?php  echo __( 'Translate Settings', WWT_TRAN_TEXT_DOMAIN ); ?>
	</h2>

	<form action="options.php" method="post">

	<?php
		settings_fields( 'wwt_tran_plugin_options' ); ?>

		<div id="wwt-tran-email-settings" class="post-box-container">
			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
					<div id="general-settings" class="postbox">

						<div class="handlediv" title="<?php _e( 'Click to toggle', WWT_TRAN_TEXT_DOMAIN ) ;?>"><br /></div>

						<!-- product box title -->
						<h3 class="hndle">
							<span><?php echo __( 'General Settings', WWT_TRAN_TEXT_DOMAIN ); ?></span>
						</h3>

						<div class="inside">
							<table class="form-table wwt-tran-box">
								<tbody>
									<tr>
										<th scope="row">
											<label for="wwt-tran-special-id"><?php _e( 'Special Id:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="text" class="regular-text" id="wwt-tran-special-id" name="wwt_tran_options[general][special_id]" value="<?php echo $special_id; ?>"/><br />
											<span class="description"><?php echo __( 'Enter your Special id.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
											<br/>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-tran-api-key"><?php _e( 'Google Translation Api Key:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="text" class="regular-text" id="wwt-tran-api-key" name="wwt_tran_options[general][tran_api_key]" value="<?php echo $tran_api_key; ?>"/><br />
											<input type="hidden" name="wwt_tran_options[general][api_valid]" value="<?php echo $tran_api_valid; ?>"/>
											<span class="description"><?php echo __( 'Enter your google translation api key.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
											<br/>
										</td>
									</tr>
									
									<tr>
										<th colspan="2"><hr></th>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-tran-shops-list"><?php _e( 'Shops List:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
										<?php
											$language_list = $this->model->wwt_google_translate_lang_list();

											$i = 0;

											foreach ( $shop_list as $shop ){
											?>
												<div class="wwt-shop-field-wrap" id="wwt-shop-field-wrap_<?php echo $i;?>">
													<input type="text" <?php echo $i == 0 ? 'readonly' : '';?> placeholder="<?php _e( 'Shop Name', WWT_TRAN_TEXT_DOMAIN ); ?>" class="wwt-shop-name" id="wwt-shop-name-<?php echo $i;?>" name="wwt_tran_options[shop_list][<?php echo $i;?>][name]" value="<?php echo $shop['name']; ?>"/>
													<input type="text" size="60" class="wwt-shop-url" id="wwt-shop-url-<?php echo $i;?>" placeholder="<?php _e( 'Shop Url', WWT_TRAN_TEXT_DOMAIN ); ?>" name="wwt_tran_options[shop_list][<?php echo $i;?>][url]" value="<?php echo $shop['url']; ?>"/>

													<?php 
													//Check Api is valid
													if( !empty( $tran_api_valid ) ) { ?>
														<select name="wwt_tran_options[shop_list][<?php echo $i;?>][lang]" id="wwt-shop-lang-<?php echo $i; ?>" class="wwt-shop-lang" >
															<?php
																if( !empty( $language_list ) ) {
																	foreach ( $language_list as $lang ) {
																		echo '<option '.( $lang['language'] == $shop['lang'] ? ' selected="selected" ' : '').'value="'.$lang['language'].'" >'.$lang['name'].'</option>';
																	}
																}else{
																		echo '<option value="" >'.__( '0 language',WWT_TRAN_TEXT_DOMAIN ).'</option>';
																}	?>
														</select>
													<?php } ?>
													<input type="button" value="Delete" class="button wwt-shop-name-remove"><br/><br/>
												</div>
										<?php $i ++;
											} ?>
											<input type="button" value="<?php _e( 'Add More', WWT_TRAN_TEXT_DOMAIN ); ?>" class="button wwt-add-more-field"><br/>					
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<input type="submit" class="button button-primary right" name="wwt_tran_options[wwt_tran_save]" id="wwt_tran_save" value="<?php _e( 'Save Changes', WWT_TRAN_TEXT_DOMAIN );?>" />
										</td>
									</tr>
								</tbody>
							</table>
						</div>

					</div>
				</div>
			</div>
		</div>

		<div id="wwt-tran-schedule" class="post-box-container">
			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
					<div id="product" class="postbox">

						<div class="handlediv" title="<?php _e( 'Click to toggle', WWT_TRAN_TEXT_DOMAIN ) ;?>"><br /></div>

						<!-- product box title -->
						<h3 class="hndle">
							<span><?php echo __( 'Schedule Email Settings', WWT_TRAN_TEXT_DOMAIN ); ?></span>
						</h3>

						<div class="inside">
							<table class="form-table wwt-tran-box">
								<tbody>
									<tr valign="top">
										<th scope="row">
											<label for="wwt_tran_hide_pro_btn"><?php _e( 'Schedule:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<select name="wwt_tran_options[schedule][schedule_type]" id="wwt_tran_schedule_type">
												<option <?php echo $schedule_type == 'daily' ? 'selected="selected"' : ''; ?> value="daily"><?php _e( 'Daily', WWT_TRAN_TEXT_DOMAIN ); ?></option>
												<option <?php echo $schedule_type == 'weekly' ? 'selected="selected"' : ''; ?> value="weekly"><?php _e( 'Weekly', WWT_TRAN_TEXT_DOMAIN ); ?></option>
												<option <?php echo $schedule_type == 'monthly' ? 'selected="selected"' : ''; ?> value="monthly"><?php _e( 'Monthly', WWT_TRAN_TEXT_DOMAIN ); ?></option>
											</select><br>
											<span class="description"><?php _e( 'Select schedule email options.', WWT_TRAN_TEXT_DOMAIN ); ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-schedule-from-name"><?php _e( 'From Name:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="text" id="wwt-schedule-from-name" name="wwt_tran_options[schedule][from_name]" value="<?php echo $schedule_from_name; ?>" size="63" /><br />
											<span class="description"><?php echo __( 'Enter From Name for email.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-schedule-from-email"><?php _e( 'From Email:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="text" id="wwt-schedule-from-email" name="wwt_tran_options[schedule][from_email]" value="<?php echo $schedule_from_email; ?>" size="63" /><br />
											<span class="description"><?php echo __( 'Enter From email id for email.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-schedule-admin-email-recipient"><?php _e( 'Admin Recipient:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="text" id="wwt-schedule-admin-email-recipient" name="wwt_tran_options[schedule][admin_recipient]" value="<?php echo $schedule_recipient; ?>" size="63" /><br />
											<span class="description"><?php echo __( 'Enter email recipient by comma seperated.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-schedule-admin-email-body"><?php _e( 'Schedule Email Template:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
										<?php
											$schedule_settings = array( 'textarea_name' => 'wwt_tran_options[schedule][admin_email_body]',  'textarea_rows' => 10, 'teeny' => true);
											wp_editor( $schedule_email_body, 'wwt-schedule-admin-body', $schedule_settings );
										?><br />
										<?php
											$schedule_email_desc = 'This is the body of the email that will be sent to the admin emails.<br />
													<code>{product_list}</code> - Displays the list of products which created between schedule time.<br />
													<code>{category_list}</code> - Displays the list of categories which created between schedule time.<br />';
										?>
											<span class="description wwt-tran-description"><?php _e( $schedule_email_desc, WWT_TRAN_TEXT_DOMAIN);?></span>
										</td>
									</tr>

									<tr>
										<td colspan="2">
											<input type="submit" class="button button-primary right" name="wwt_tran_options[wwt_tran_save]" id="wwt_tran_save" value="<?php _e( 'Save Changes', WWT_TRAN_TEXT_DOMAIN );?>" />
										</td>
									</tr>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="wwt-tran-plugin-diffrent-schedule" class="post-box-container">
			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
					<div id="product" class="postbox">

						<div class="handlediv" title="<?php _e( 'Click to toggle', WWT_TRAN_TEXT_DOMAIN ) ;?>"><br /></div>

						<!-- product box title -->
						<h3 class="hndle">
							<span><?php echo __( 'Plugin Different Schedule Email Settings', WWT_TRAN_TEXT_DOMAIN ); ?></span>
						</h3>

						<div class="inside">
							<table class="form-table wwt-tran-box">
								<tbody>
									<tr valign="top">
										<th scope="row">
											<label for="wwt_tran_plu_shed_type"><?php _e( 'Schedule:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<select name="wwt_tran_options[plugin_different_schedule][schedule_type]" id="wwt_tran_plu_shed_type">
												<option <?php echo $plug_schedule_type == 'weekly' ? 'selected="selected"' : ''; ?> value="weekly"><?php _e( 'Weekly', WWT_TRAN_TEXT_DOMAIN ); ?></option>
												<option <?php echo $plug_schedule_type == 'monthly' ? 'selected="selected"' : ''; ?> value="monthly"><?php _e( 'Monthly', WWT_TRAN_TEXT_DOMAIN ); ?></option>
											</select><br>
											<span class="description"><?php _e( 'Select schedule email options.', WWT_TRAN_TEXT_DOMAIN ); ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-plug-schedule-from-name"><?php _e( 'From Name:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="text" id="wwt-plug-schedule-from-name" name="wwt_tran_options[plugin_different_schedule][from_name]" value="<?php echo $plug_schedule_from_name; ?>" size="63" /><br />
											<span class="description"><?php echo __( 'Enter From Name for email.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-plug-schedule-from-email"><?php _e( 'From Email:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="text" id="wwt-plug-schedule-from-email" name="wwt_tran_options[plugin_different_schedule][from_email]" value="<?php echo $plug_schedule_from_email; ?>" size="63" /><br />
											<span class="description"><?php echo __( 'Enter From email id for email.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-plug-schedule-admin-email-recipient"><?php _e( 'Admin Recipient:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="text" id="wwt-plug-schedule-admin-email-recipient" name="wwt_tran_options[plugin_different_schedule][admin_recipient]" value="<?php echo $plug_schedule_recipient; ?>" size="63" /><br />
											<span class="description"><?php echo __( 'Enter email recipient by comma seperated.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-schedule-plug-admin-email-body"><?php _e( 'Schedule Email Template:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
										<?php
											$plug_schedule_settings = array( 'textarea_name' => 'wwt_tran_options[plugin_different_schedule][admin_email_body]',  'textarea_rows' => 10, 'teeny' => true);
											wp_editor( $plug_schedule_email_body, 'wwt-plug-schedule-admin-body', $plug_schedule_settings );
										?><br />
										<?php
											$plug_schedule_email_desc = 'This is the body of the email that will be sent to the admin emails.<br />
													<code>{shop_details}</code> - Displays the list of plugins which diffent.<br />';
										?>
											<span class="description wwt-tran-description"><?php _e( $plug_schedule_email_desc, WWT_TRAN_TEXT_DOMAIN);?></span>
										</td>
									</tr>

									<tr>
										<td colspan="2">
											<input type="submit" class="button button-primary right" name="wwt_tran_options[wwt_tran_save]" id="wwt_tran_save" value="<?php _e( 'Save Changes', WWT_TRAN_TEXT_DOMAIN );?>" />
										</td>
									</tr>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="wwt-tran-email-settings" class="post-box-container">
			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
					<div id="product" class="postbox">

						<div class="handlediv" title="<?php _e( 'Click to toggle', WWT_TRAN_TEXT_DOMAIN ) ;?>"><br /></div>

						<!-- product box title -->
						<h3 class="hndle">
							<span><?php echo __( 'Professional Email Settings', WWT_TRAN_TEXT_DOMAIN ); ?></span>
						</h3>

						<div class="inside">
							<table class="form-table wwt-tran-box">
								<tbody>
									<tr valign="top">
										<th scope="row">
											<label for="wwt_tran_hide_pro_btn"><?php _e( 'Enable Posts Professional Translate Button:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<ul class="wwt_tran_hide_pro_btn" id="wwt_tran_hide_pro_btn">
											<?php
												$all_types = get_post_types( array( 'public' => true ), 'objects' );
											
												$all_types = is_array( $all_types ) ? $all_types : array();
												$professional_types_data = get_option( 'wwt_tran_options' );

												if( !empty( $professional_types_data['professional']['type'] ) ) {

													$professional_types = $professional_types_data['professional']['type'];

												} else {
													$professional_types = '';
												}

												$professional_types = is_array( $professional_types ) ? $professional_types : array();
												$count = 0;

												foreach ( $all_types as $type ) {

													//Check attachment then skip
													if( $type->name == 'attachment' ) continue;

													if( !empty( $professional_types_data['professional']['item_'.$type->name] ) ) {
														$items_count = count( $professional_types_data['professional']['item_'.$type->name] );
													} else {
														$professional_types_data['professional']['item_'.$type->name] = '';
														$items_count = 0;
													}

													if ( !is_object( $type ) ) continue;
														$label = @$type->labels->name ? $type->labels->name : $type->name;
														$selected = ( in_array( $type->name, $professional_types ) ) ? 'checked="checked"' : '';
													?>
												<li>
													<input type="checkbox" id="wwt_tran_professional_<?php echo $count.'_'.$type->name; ?>" name="wwt_tran_options[professional][type][]" value="<?php echo $type->name; ?>" <?php echo $selected; ?>/>

													<label for="wwt_tran_professional_<?php echo $count.'_'.$type->name; ?>"><?php echo $label; ?></label>
												</li>

												<?php
													$args = array( 'post_type' => $type->name, 'posts_per_page' => 100 );
													$wp_query = null;
													$wp_query = new WP_Query;
													$wp_query->query( $args );
													$professional_types_entries = get_option( 'wwt_tran_options' );

													if( !empty( $professional_types_entries['professional']['item_'.$type->name] ) ) {
														$professional_items = $professional_types_entries['professional']['item_'.$type->name];
													} else {
														$professional_items = '';
													}

													$professional_items = is_array( $professional_items ) ? $professional_items : array();

											 $count++;	}  ?>
										</ul>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt_tran_hide_pro_btn"><?php _e( 'Enable Taxonomies Professional Translate Button:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<ul>
											<?php
												$professional_terms_data =  get_option( 'wwt_tran_options' );

												if( !empty( $professional_terms_data['professional']['terms'] ) ) {

													$professional_terms = $professional_terms_data['professional']['terms'];

												} else {
													$professional_terms = '';
												}

												$professional_terms = is_array( $professional_terms ) ? $professional_terms : array();
												$count = 0;

												foreach ( $terms_list as $term ) {

													//Check post_format, product_shipping_class then skip
													if( $term->name == 'post_format' ||  $term->name == 'product_shipping_class' || $term->name == 'link_category' || $term->name == 'nav_menu' || $term->name == 'product_type' ) continue;

													if ( !is_object( $term ) ) continue;

														$label = @$term->labels->name ? $term->labels->name : $term->name;
														$selected = ( in_array( $term->name, $professional_terms ) ) ? 'checked="checked"' : '';
													?>
													<li>
														<input type="checkbox" id="wwt_tran_professional_<?php echo $count.'_'.$term->name; ?>" name="wwt_tran_options[professional][terms][]" value="<?php echo $term->name; ?>" <?php echo $selected; ?>/>
														<label for="wwt_tran_professional_<?php echo $count.'_'.$term->name; ?>"><?php echo $label; ?></label>
													</li>
												<?php	
												}
												?>
											</ul>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-tran-from-name"><?php _e( 'From Name:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="text" id="wwt-tran-from-name" name="wwt_tran_options[professional][from_name]" value="<?php echo $professional_from_name; ?>" size="63" /><br />
											<span class="description"><?php echo __( 'Enter From Name for email.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-tran-from-email"><?php _e( 'From Email:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="text" id="wwt-tran-from-email" name="wwt_tran_options[professional][from_email]" value="<?php echo $professional_from_email; ?>" size="63" /><br />
											<span class="description"><?php echo __( 'Enter From email id for email.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-tran-admin-email-recipient"><?php _e( 'Admin Recipient:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="text" id="wwt-tran-admin-email-recipient" name="wwt_tran_options[professional][admin_recipient]" value="<?php echo $professional_recipient; ?>" size="63" /><br />
											<span class="description"><?php echo __( 'Enter email recipient by comma seperated.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-tran-admin-email-body"><?php _e( 'Post Admin Email Template:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
										<?php
											$professional_settings = array( 'textarea_name' => 'wwt_tran_options[professional][admin_email_body]',  'textarea_rows' => 10, 'teeny' => true);
											wp_editor( $professional_email_body, 'wwt-tran-admin-body', $professional_settings );
										?><br />
										<?php
											$admin_email_desc = 'This is the body of the email that will be sent to the admin emails.<br />															
													<code>{shop_name}</code> - Displays the user shop name <br />
													<code>{id}</code> - Displays the current\'s page or post id <br />
													<code>{title}</code> - Displays the current\'s page or post title <br />
													<code>{content}</code> - Displays the current\'s page or post content <br />
													<code>{type}</code> - Displays the current\'s page or post type <br />
													<code>{page_url}</code> - Displays the current\'s page or post url <br />
													<code>{user}</code> - Displays the request sender name if user login another Guest<br />
													<code>{total_words}</code> - Displays the request sender page total words<br />
													<code>{price_per_word}</code> - Displays the request sender page per words price<br />
													<code>{total_price}</code> - Displays the request sender page total price<br />
													<code>{google_translate}</code> - Displays the current\'s page or post google translate Yes/No<br />
													<code>{partial_text}</code> - Displays the current\'s page or post product content selected<br />
													<code>{remark}</code> - Displays the current\'s user added note<br />';
										?>
											<span class="description wwt-tran-description"><?php _e( $admin_email_desc, WWT_TRAN_TEXT_DOMAIN);?></span>
										</td>
									</tr>

									<tr>
										<td colspan="2">
											<input type="submit" class="button button-primary right" name="wwt_tran_options[wwt_tran_save]" id="wwt_tran_save" value="<?php _e( 'Save Changes', WWT_TRAN_TEXT_DOMAIN );?>" />
										</td>
									</tr>

								</tbody>
							</table>
						</div>
						<div class="wwt_tran_overlay"></div>
					</div>
				</div>
			</div>
		</div>

		<div id="wwt-tran-seo-email-settings" class="post-box-container">
			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
					<div id="wwt-tran-seo-email-settings" class="postbox">

						<div class="handlediv" title="<?php _e( 'Click to toggle', WWT_TRAN_TEXT_DOMAIN ) ;?>"><br /></div>

						<!-- product box title -->
						<h3 class="hndle">
							<span><?php echo __( 'SEO Professional Email Settings', WWT_TRAN_TEXT_DOMAIN ); ?></span>
						</h3>

						<div class="inside">
							<table class="form-table wwt-tran-box">
								<tbody>
									<tr valign="top">
										<th scope="row">
											<label for="wwt_tran_enable_seo_btn"><?php _e( 'Enable SEO Professional Translate Button:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<ul class="wwt_tran_hide_seo_btn" id="wwt_tran_hide_seo_btn">
											<?php
												$all_types = get_post_types( array( 'public' => true ), 'objects' );
												$all_types = is_array( $all_types ) ? $all_types : array();

												$seo_professional_types_data = get_option( 'wwt_tran_options' );

												if( !empty( $seo_professional_types_data['seo_professional']['type'] ) ) {

													$seo_professional_types = $seo_professional_types_data['seo_professional']['type'];

												} else {
													$seo_professional_types = '';
												}

												$seo_professional_types = is_array( $seo_professional_types ) ? $seo_professional_types : array();
												$count = 0;

												foreach ( $all_types as $type ) {

													//Check attachment then skip
													if( $type->name == 'attachment' ) continue;

													if( !empty( $seo_professional_types_data['seo_professional']['item_'.$type->name] ) ) {
														$items_count = count( $professional_types_data['seo_professional']['item_'.$type->name] );
													} else {
														$seo_professional_types_data['seo_professional']['item_'.$type->name] = '';
														$items_count = 0;
													}

													if ( !is_object( $type ) ) continue;
														$label = @$type->labels->name ? $type->labels->name : $type->name;
														$selected = ( in_array( $type->name, $seo_professional_types ) ) ? 'checked="checked"' : '';
													?>
												<li>
													<input type="checkbox" id="wwt_tran_seo_professional_<?php echo $count.'_'.$type->name; ?>" name="wwt_tran_options[seo_professional][type][]" value="<?php echo $type->name; ?>" <?php echo $selected; ?>/>

													<label for="wwt_tran_seo_professional_<?php echo $count.'_'.$type->name; ?>"><?php echo $label; ?></label>
												</li>
											<?php
											 $count++;	}  ?>
										</ul>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt_tran_hide_pro_btn"><?php _e( 'Enable Taxonomies SEO Professional Translate Button:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<ul>
											<?php
											if( !empty( $wwt_tran_options['seo_professional']['terms'] ) ) {
													$seo_professional_terms = $wwt_tran_options['seo_professional']['terms'];
												} else {
													$seo_professional_terms = '';
												}

												$seo_professional_terms = is_array( $seo_professional_terms ) ? $seo_professional_terms : array();
												$count = 0;

												foreach ( $terms_list as $term ) {

													//Check post_format, product_shipping_class then skip
													if( $term->name == 'post_format' ||  $term->name == 'product_shipping_class' || $term->name == 'link_category' || $term->name == 'nav_menu' || $term->name == 'product_type' ) continue;

													if ( !is_object( $term ) ) continue;

														$label = @$term->labels->name ? $term->labels->name : $term->name;
														$selected = ( in_array( $term->name, $seo_professional_terms ) ) ? 'checked="checked"' : '';
													?>
													<li>
														<input type="checkbox" id="wwt_tran_seo_professional_<?php echo $count.'_'.$term->name; ?>" name="wwt_tran_options[seo_professional][terms][]" value="<?php echo $term->name; ?>" <?php echo $selected; ?>/>
														<label for="wwt_tran_seo_professional_<?php echo $count.'_'.$term->name; ?>"><?php echo $label; ?></label>
													</li>
												<?php
												}?>
											</ul>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-tran-seo-from-name"><?php _e( 'From Name:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="text" id="wwt-tran-seo-from-name" name="wwt_tran_options[seo_professional][from_name]" value="<?php echo $seo_professional_from_name; ?>" size="63" /><br />
											<span class="description"><?php echo __( 'Enter From Name for email.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-tran-seo-from-email"><?php _e( 'From Email:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="text" id="wwt-tran-seo-from-email" name="wwt_tran_options[seo_professional][from_email]" value="<?php echo $seo_professional_from_email; ?>" size="63" /><br />
											<span class="description"><?php echo __( 'Enter From email id for email.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-tran-seo-admin-email-recipient"><?php _e( 'Admin Recipient:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="text" id="wwt-tran-seo-admin-email-recipient" name="wwt_tran_options[seo_professional][admin_recipient]" value="<?php echo $seo_professional_recipient; ?>" size="63" /><br />
											<span class="description"><?php echo __( 'Enter email recipient by comma seperated.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-tran-seo-admin-email-body"><?php _e( 'Post Admin Email Template:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
										<?php
											$seo_professional_settings = array( 'textarea_name' => 'wwt_tran_options[seo_professional][admin_email_body]',  'textarea_rows' => 10, 'teeny' => true );
											wp_editor( $seo_professional_email_body, 'wwt-tran-seo-admin-body', $seo_professional_settings );
										?><br />
										<?php
											$admin_email_desc = 'This is the body of the email that will be sent to the admin emails.<br />
													<code>{id}</code> - Displays the current\'s page or post id <br/>
													<code>{title}</code> - Displays the current\'s page or post title <br/>
													<code>{type}</code> - Displays the current\'s page or post type <br/>
													<code>{page_url}</code> - Displays the current\'s page or post url <br/>
													<code>{user}</code> - Displays the request sender name if user login another Guest<br/>
													<code>{seo_advise}</code> - Displays the request sender particular question words<br/>
													<code>{spend_time}</code> - Displays the request sender time we should spend<br/>
													<code>{total_price}</code> - Displays the request sender page total price<br/>';
										?>
											<span class="description wwt-tran-description"><?php _e( $admin_email_desc, WWT_TRAN_TEXT_DOMAIN );?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-tran-seo-term-admin-email-body"><?php _e( 'Taxonomy Admin Email Template:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
										<?php
											$term_seo_professional_settings = array( 'textarea_name' => 'wwt_tran_options[seo_professional][term_admin_email_body]',  'textarea_rows' => 10, 'teeny' => true);
											wp_editor( $term_seo_professional_email_body, 'wwt-tran-seo-term-admin-body', $term_seo_professional_settings );
										?><br />
										<?php
											$admin_email_desc = 'This is the body of the email that will be sent to the admin emails.<br />
													<code>{id}</code> - Displays the current\'s page taxonomy id <br />
													<code>{title}</code> - Displays the current\'s page taxonomy title <br />
													<code>{type}</code> - Displays the current\'s page taxonomy name <br />
													<code>{page_url}</code> - Displays the current\'s page or post url <br />
													<code>{user}</code> - Displays the request sender name if user login another Guest<br />
													<code>{seo_advise}</code> - Displays the request sender particular question words<br/>
													<code>{spend_time}</code> - Displays the request sender time we should spend<br/>
													<code>{total_price}</code> - Displays the request sender page total price<br/>';
										?>
											<span class="description wwt-tran-description"><?php _e( $admin_email_desc, WWT_TRAN_TEXT_DOMAIN);?></span>
										</td>
									</tr>
									<tr>
										<td colspan="2">
											<input type="submit" class="button button-primary right" name="wwt_tran_options[wwt_tran_save]" id="wwt_tran_save" value="<?php _e( 'Save Changes', WWT_TRAN_TEXT_DOMAIN );?>" />
										</td>
									</tr>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="wwt-tran-product-email-settings" class="post-box-container">
			<div class="metabox-holder">
				<div class="meta-box-sortables ui-sortable">
					<div id="product-email-settings" class="postbox">

						<div class="handlediv" title="<?php _e( 'Click to toggle', WWT_TRAN_TEXT_DOMAIN ) ;?>"><br /></div>

						<!-- product box title -->
						<h3 class="hndle">
							<span><?php echo __( 'Rules Email Settings', WWT_TRAN_TEXT_DOMAIN ); ?></span>
						</h3>

						<div class="inside">
							<table class="form-table wwt-tran-box">
								<tbody>
									<tr>
										<th scope="row">
											<label for="wwt-tran-product-from-name"><?php _e( 'From Name:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="text" id="wwt-tran-product-from-name" name="wwt_tran_options[product][from_name]" value="<?php echo $product_from_name; ?>" size="63" /><br />
											<span class="description"><?php echo __( 'Enter From Name for email.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-tran-product-from-email"><?php _e( 'From Email:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="text" id="wwt-tran-product-from-email" name="wwt_tran_options[product][from_email]" value="<?php echo $product_from_email; ?>" size="63" /><br />
											<span class="description"><?php echo __( 'Enter From email id for email.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-tran-product-email-recipient"><?php _e( 'Admin Recipient:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
											<input type="text" id="wwt-tran-product-email-recipient" name="wwt_tran_options[product][admin_recipient]" value="<?php echo $product_recipient; ?>" size="63" /><br />
											<span class="description"><?php echo __( 'Enter email recipient by comma seperated.', WWT_TRAN_TEXT_DOMAIN ) ?></span>
										</td>
									</tr>
									<tr>
										<th scope="row">
											<label for="wwt-tran-product-email-body"><?php _e( 'Post Admin Email Template:', WWT_TRAN_TEXT_DOMAIN ); ?></label>
										</th>
										<td>
										<?php
											$product_settings = array( 'textarea_name' => 'wwt_tran_options[product][admin_email_body]',  'textarea_rows' => 10, 'teeny' => true);
											wp_editor( $product_email_body, 'wwt-tran-product-admin-body', $product_settings );
										?><br />
										<?php
											$admin_email_desc = 'This is the body of the email that will be sent to the admin emails.<br />
													<code>{price_rule}</code> - Displays the request sender selected if pricing rules<br />
													<code>{price}</code> - Displays the request sender given amount in percentage<br />
													<code>{extra_cost}</code> - Displays the request sender given extra cost.<br />
													<code>{id}</code> - Displays the request sender category  / product id displaying <br />
													<code>{title}</code> - Displays the request sender category / product title displaying <br />
													<code>{url}</code> - Displays the request sender category / product url displaying <br />
													<code>{user}</code> - Displays the request sender name if user login another Guest<br />';
										?>
											<span class="description wwt-tran-description"><?php _e( $admin_email_desc,WWT_TRAN_TEXT_DOMAIN);?></span>
										</td>
									</tr>

									<tr>
										<td colspan="2">
											<input type="submit" class="button button-primary right" name="wwt_tran_options[wwt_tran_save]" id="wwt_tran_save" value="<?php _e( 'Save Changes', WWT_TRAN_TEXT_DOMAIN );?>" />
										</td>
									</tr>

								</tbody>
							</table>
						</div>

					</div>
				</div>
			</div>
		</div>
 
		<div class="wwt_tran_overlay"></div>
	</form>

</div>	